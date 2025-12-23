<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Models\Thread;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApplicationService
{
    /**
     * 応募（Application）を作成し、Thread を生成/再利用し、最初の Message を作成する
     *
     * 設計根拠（ApplicationService 詳細設計）
     * - application + thread + message の複数更新を1つの手続きとして扱う
     * - 既応募の場合は「二重応募」を防ぎ、既存スレッドへ誘導する
     * - 途中失敗時に不整合を残さないため、トランザクションでまとめる
     */
    public function apply(int $freelancerId, Job $job, string $messageBody): Thread
    {
        // 入力の空白を取り除き、「空メッセージ」を弾きやすくする
        $messageBody = trim($messageBody);

        // 応募メッセージが空なら、設計どおりバリデーションエラーにする
        if ($messageBody === '') {
            throw ValidationException::withMessages([
                'message' => '応募メッセージを入力してください',
            ]);
        }

        // 案件が公開中でなければ応募できない（設計の「公開状態チェック」）
        if ((int) $job->status !== (int) Job::STATUS_PUBLISHED) {
            throw ValidationException::withMessages([
                'job' => 'この案件は現在応募できません',
            ]);
        }

        // 応募〜スレッド作成は複数テーブル更新なので、トランザクションで安全にまとめる
        return DB::transaction(function () use ($freelancerId, $job, $messageBody): Thread {
            // 時刻を1回だけ作って、thread/messageの整合を取りやすくする
            $now = Carbon::now();

            // 既に応募していないかをチェックする（Controllerでも見るが二重防御）
            $alreadyApplied = Application::query()
                // 同じ案件への応募か
                ->where('job_id', $job->id)
                // 同じフリーランスの応募か
                ->where('freelancer_id', $freelancerId)
                // 1件でもあれば「既応募」
                ->exists();

            // 既応募なら、新規作成はせず既存スレッドへ誘導する（重複応募防止）
            if ($alreadyApplied) {
                // 応募スレッドは company + freelancer + job の組み合わせで導出する
                return Thread::query()
                    // 企業は案件に紐づく
                    ->where('company_id', $job->company_id)
                    // 応募者（フリーランス）
                    ->where('freelancer_id', $freelancerId)
                    // 案件
                    ->where('job_id', $job->id)
                    // 見つからないのは不整合なので例外にする
                    ->firstOrFail();
            }

            // applications に応募レコードを作成する（応募履歴の記録）
            $application = Application::create([
                // どの案件に応募したか
                'job_id' => $job->id,
                // 誰が応募したか
                'freelancer_id' => $freelancerId,
                // 応募時の本文（設計：applicationsにも保持）
                'message' => $messageBody,
                // 初期状態は「未対応」
                'status' => Application::STATUS_PENDING,
            ]);

            // threads は company + freelancer + job の組み合わせで「部屋」を表す（再利用あり）
            $thread = Thread::query()->firstOrCreate(
                [
                    // 相手企業
                    'company_id' => $job->company_id,
                    // 応募者
                    'freelancer_id' => $freelancerId,
                    // 対象案件
                    'job_id' => $job->id,
                ],
                [
                    // 最後の送信者は応募者（フリーランス）
                    'latest_sender_type' => 'freelancer',
                    // latest_sender_id は threads.freelancer_id（= FreelancerモデルのID）
                    'latest_sender_id' => $freelancerId,
                    // 最新メッセージ時刻
                    'latest_message_at' => $now,
                    // 企業側は未読（相手が読むべき）
                    'is_unread_for_company' => true,
                    // フリーランス側は既読（自分が送った直後）
                    'is_unread_for_freelancer' => false,
                ]
            );

            // messages に「最初の応募メッセージ」を保存する（チャット履歴として残す）
            Message::create([
                // どのスレッドに属するか
                'thread_id' => $thread->id,
                // 送信者の種別
                'sender_type' => 'freelancer',
                // 送信者ID（FreelancerのID）
                'sender_id' => $freelancerId,
                // メッセージ本文（applicationsの本文と同じ）
                'body' => $application->message,
                // 送信時刻
                'sent_at' => $now,
            ]);

            // スレッド側も「最新情報」を揃えておく（再利用スレッドだった場合にも更新する）
            $thread->forceFill([
                // 最後に送ったのはフリーランス
                'latest_sender_type' => 'freelancer',
                // 最後に送った人（Freelancer ID）
                'latest_sender_id' => $freelancerId,
                // 最終送信時刻
                'latest_message_at' => $now,
                // 企業側は未読にする
                'is_unread_for_company' => true,
                // フリーランス側は既読のまま
                'is_unread_for_freelancer' => false,
            ])->save();

            // Controllerはこのthreadへ遷移する（設計：応募後は即チャット）
            return $thread;
        });
    }
}

