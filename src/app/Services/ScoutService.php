<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Corporate;
use App\Models\Job;
use App\Models\Message;
use App\Models\Scout;
use App\Models\Thread;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScoutService
{
    /**
     * スカウト（Scout）を作成し、Thread を生成/再利用し、最初の Message を作成する
     *
     * 設計根拠（ScoutService 詳細設計）
     * - scout + thread + message の複数更新を1つの手続きとして扱う
     * - 既存スレッドがあれば再利用し、なければ新規作成する
     * - 途中失敗時に不整合を残さないため、トランザクションでまとめる
     */
    public function send(int $companyId, int $corporateId, ?int $jobId, string $messageBody): Thread
    {
        // 入力の空白を取り除き、「空メッセージ」を弾きやすくする
        $messageBody = trim($messageBody);

        // スカウトメッセージが空なら、設計どおりバリデーションエラーにする
        if ($messageBody === '') {
            throw ValidationException::withMessages([
                'message' => 'スカウトメッセージを入力してください',
            ]);
        }

        // 企業が存在するかを念のため確認する（設計：存在確認）
        Company::query()->findOrFail($companyId);

        // フリーランスが存在するかを確認する（設計：存在確認）
        Corporate::query()->findOrFail($corporateId);

        // job_id が指定されている場合は、案件が存在するかを確認する（設計：任意の案件紐付け）
        if ($jobId !== null) {
            // 案件を取得する（存在しなければ例外）
            $job = Job::query()->findOrFail($jobId);

            // 念のため「自社案件」か確認する（Controllerでも見ているが二重防御）
            if ((int) $job->company_id !== (int) $companyId) {
                throw ValidationException::withMessages([
                    'job_id' => 'この案件は自社案件ではありません',
                ]);
            }
        }

        // 複数テーブル更新をまとめるため、トランザクションで行う
        return DB::transaction(function () use ($companyId, $corporateId, $jobId, $messageBody): Thread {
            // 時刻を1回だけ作って、thread/messageの整合を取りやすくする
            $now = Carbon::now();

            // scouts テーブルにスカウト履歴を作成する（設計：スカウトレコード作成）
            $scout = Scout::create([
                // 送信した企業
                'company_id' => $companyId,
                // 送信先フリーランス
                'corporate_id' => $corporateId,
                // 任意の案件（紐付けがない場合はnull）
                'job_id' => $jobId,
                // スカウト本文
                'message' => $messageBody,
                // 初期状態は「未対応」
                'status' => Scout::STATUS_PENDING,
            ]);

            // thread は company + freelancer + job(or null) の組み合わせで導出する（設計：既存スレッド再利用）
            $threadQuery = Thread::query()
                // 企業
                ->where('company_id', $companyId)
                // フリーランス
                ->where('corporate_id', $corporateId);

            // job_id がnullならwhereNullで一致させる（設計：案件紐付けなしスカウトもOK）
            if ($jobId === null) {
                $threadQuery->whereNull('job_id');
            } else {
                $threadQuery->where('job_id', $jobId);
            }

            // 既存スレッドがあれば再利用し、なければ新規作成する
            $thread = $threadQuery->first();

            // 見つからなかった場合は新規作成する
            if ($thread === null) {
                // 初期状態は「送信者=company」「相手=freelancerを未読」にする
                $thread = Thread::create([
                    // 相手企業
                    'company_id' => $companyId,
                    // 相手フリーランス
                    'corporate_id' => $corporateId,
                    // 任意の案件
                    'job_id' => $jobId,
                    // 最後に送ったのは企業
                    'latest_sender_type' => 'company',
                    // latest_sender_id は threads.company_id（= CompanyモデルのID）
                    'latest_sender_id' => $companyId,
                    // 最新メッセージ時刻
                    'latest_message_at' => $now,
                    // 企業側は既読（自分が送った直後）
                    'is_unread_for_company' => false,
                    // フリーランス側は未読（相手が読むべき）
                    'is_unread_for_corporate' => true,
                ]);
            }

            // messages に「最初のスカウトメッセージ」を保存する（設計：messagesテーブル更新）
            Message::create([
                // どのスレッドに属するか
                'thread_id' => $thread->id,
                // 送信者の種別
                'sender_type' => 'company',
                // 送信者ID（CompanyのID）
                'sender_id' => $companyId,
                // メッセージ本文（scoutsの本文と同じ）
                'body' => $scout->message,
                // 送信時刻
                'sent_at' => $now,
            ]);

            // スレッド側も「最新情報」を揃えておく（再利用スレッドだった場合にも更新する）
            $thread->forceFill([
                // 最後に送ったのは企業
                'latest_sender_type' => 'company',
                // 最後に送った人（Company ID）
                'latest_sender_id' => $companyId,
                // 最終送信時刻
                'latest_message_at' => $now,
                // 企業側は既読のまま
                'is_unread_for_company' => false,
                // フリーランス側は未読にする
                'is_unread_for_corporate' => true,
            ])->save();

            // Controllerはこのthreadへ遷移する（設計：送信後は即チャット）
            return $thread;
        });
    }
}

