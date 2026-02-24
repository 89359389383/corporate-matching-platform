<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Models\Thread;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public function apply(int $corporateId, Job $job, array $applicationData): Thread
    {
        $messageBody = (string) ($applicationData['message'] ?? '');
        Log::info('[ApplicationService] applyメソッド開始', [
            'corporate_id' => $corporateId,
            'job_id' => $job->id,
            'job_title' => $job->title ?? 'N/A',
            'company_id' => $job->company_id,
            'message_body_length' => strlen($messageBody),
            'message_body_preview' => mb_substr($messageBody, 0, 50) . (mb_strlen($messageBody) > 50 ? '...' : ''),
        ]);

        // 入力の空白を取り除き、「空メッセージ」を弾きやすくする
        $messageBody = trim($messageBody);
        Log::debug('[ApplicationService] メッセージトリミング後', [
            'trimmed_length' => strlen($messageBody),
            'trimmed_preview' => mb_substr($messageBody, 0, 50) . (mb_strlen($messageBody) > 50 ? '...' : ''),
        ]);

        // 応募メッセージが空なら、設計どおりバリデーションエラーにする
        if ($messageBody === '') {
            Log::warning('[ApplicationService] 応募メッセージが空のためバリデーションエラー', [
                'corporate_id' => $corporateId,
                'job_id' => $job->id,
            ]);
            throw ValidationException::withMessages([
                'message' => '応募メッセージを入力してください',
            ]);
        }

        // 案件が公開中でなければ応募できない（設計の「公開状態チェック」）
        Log::debug('[ApplicationService] 案件ステータスチェック', [
            'job_id' => $job->id,
            'job_status' => $job->status,
            'expected_status' => Job::STATUS_PUBLISHED,
            'is_published' => (int) $job->status === (int) Job::STATUS_PUBLISHED,
        ]);
        if ((int) $job->status !== (int) Job::STATUS_PUBLISHED) {
            Log::warning('[ApplicationService] 案件が公開中でないため応募不可', [
                'corporate_id' => $corporateId,
                'job_id' => $job->id,
                'job_status' => $job->status,
                'expected_status' => Job::STATUS_PUBLISHED,
            ]);
            throw ValidationException::withMessages([
                'job' => 'この案件は現在応募できません',
            ]);
        }

        // 応募〜スレッド作成は複数テーブル更新なので、トランザクションで安全にまとめる
        Log::info('[ApplicationService] トランザクション開始');
        return DB::transaction(function () use ($corporateId, $job, $messageBody, $applicationData): Thread {
            // 時刻を1回だけ作って、thread/messageの整合を取りやすくする
            $now = Carbon::now();
            Log::debug('[ApplicationService] トランザクション内: 基準時刻を設定', [
                'now' => $now->toDateTimeString(),
                'timestamp' => $now->timestamp,
            ]);

            // 既に応募していないかをチェックする（Controllerでも見るが二重防御）
            Log::debug('[ApplicationService] 既存応募チェック開始', [
                'job_id' => $job->id,
                'corporate_id' => $corporateId,
            ]);
            $alreadyApplied = Application::query()
                // 同じ案件への応募か
                ->where('job_id', $job->id)
                // 同じフリーランスの応募か
                ->where('corporate_id', $corporateId)
                // 1件でもあれば「既応募」
                ->exists();
            Log::info('[ApplicationService] 既存応募チェック結果', [
                'already_applied' => $alreadyApplied,
                'job_id' => $job->id,
                'corporate_id' => $corporateId,
            ]);

            // 既応募なら、新規作成はせず既存スレッドへ誘導する（重複応募防止）
            if ($alreadyApplied) {
                Log::info('[ApplicationService] 既応募を検出: 既存スレッドを取得', [
                    'job_id' => $job->id,
                    'corporate_id' => $corporateId,
                    'company_id' => $job->company_id,
                ]);
                // 応募スレッドは company + corporate + job の組み合わせで導出する
                $existingThread = Thread::query()
                    // 企業は案件に紐づく
                    ->where('company_id', $job->company_id)
                    // 応募者（フリーランス）
                    ->where('corporate_id', $corporateId)
                    // 案件
                    ->where('job_id', $job->id)
                    // 見つからないのは不整合なので例外にする
                    ->firstOrFail();
                Log::info('[ApplicationService] 既存スレッドを取得完了', [
                    'thread_id' => $existingThread->id,
                    'latest_message_at' => $existingThread->latest_message_at?->toDateTimeString(),
                    'is_unread_for_company' => $existingThread->is_unread_for_company,
                    'is_unread_for_corporate' => $existingThread->is_unread_for_corporate,
                ]);
                return $existingThread;
            }

            // applications に応募レコードを作成する（応募履歴の記録）
            Log::info('[ApplicationService] 新規応募レコード作成開始', [
                'job_id' => $job->id,
                'corporate_id' => $corporateId,
                'status' => Application::STATUS_PENDING,
                'message_length' => strlen($messageBody),
            ]);
            $application = Application::create([
                // どの案件に応募したか
                'job_id' => $job->id,
                // 誰が応募したか
                'corporate_id' => $corporateId,
                // 応募時の本文（設計：applicationsにも保持）
                'message' => $messageBody,
                // 追加の応募情報
                'desired_hourly_rate' => $applicationData['desired_hourly_rate'] ?? null,
                'work_days' => $applicationData['work_days'] ?? null,
                'work_time_from' => $applicationData['work_time_from'] ?? null,
                'work_time_to' => $applicationData['work_time_to'] ?? null,
                'note' => $applicationData['note'] ?? null,
                'weekly_hours' => $applicationData['weekly_hours'] ?? null,
                'available_start' => $applicationData['available_start'] ?? null,
                // 初期状態は「未対応」
                'status' => Application::STATUS_PENDING,
            ]);
            Log::info('[ApplicationService] 応募レコード作成完了', [
                'application_id' => $application->id,
                'job_id' => $application->job_id,
                'corporate_id' => $application->corporate_id,
                'status' => $application->status,
                'created_at' => $application->created_at?->toDateTimeString(),
            ]);

            // threads は company + corporate + job の組み合わせで「部屋」を表す（再利用あり）
            Log::info('[ApplicationService] スレッド作成/取得開始', [
                'company_id' => $job->company_id,
                'corporate_id' => $corporateId,
                'job_id' => $job->id,
            ]);
            $threadWasRecentlyCreated = !Thread::query()
                ->where('company_id', $job->company_id)
                ->where('corporate_id', $corporateId)
                ->where('job_id', $job->id)
                ->exists();
            $thread = Thread::query()->firstOrCreate(
                [
                    // 相手企業
                    'company_id' => $job->company_id,
                    // 応募者
                    'corporate_id' => $corporateId,
                    // 対象案件
                    'job_id' => $job->id,
                ],
                [
                    // 最後の送信者は応募者（フリーランス）
                    'latest_sender_type' => 'corporate',
                    // latest_sender_id は threads.corporate_id（= FreelancerモデルのID）
                    'latest_sender_id' => $corporateId,
                    // 最新メッセージ時刻
                    'latest_message_at' => $now,
                    // 企業側は未読（相手が読むべき）
                    'is_unread_for_company' => true,
                    // フリーランス側は既読（自分が送った直後）
                    'is_unread_for_corporate' => false,
                ]
            );
            Log::info('[ApplicationService] スレッド作成/取得完了', [
                'thread_id' => $thread->id,
                'was_newly_created' => $threadWasRecentlyCreated,
                'company_id' => $thread->company_id,
                'corporate_id' => $thread->corporate_id,
                'job_id' => $thread->job_id,
                'latest_sender_type' => $thread->latest_sender_type,
                'latest_sender_id' => $thread->latest_sender_id,
                'latest_message_at' => $thread->latest_message_at?->toDateTimeString(),
                'is_unread_for_company' => $thread->is_unread_for_company,
                'is_unread_for_corporate' => $thread->is_unread_for_corporate,
            ]);

            // messages に「最初の応募メッセージ」を保存する（チャット履歴として残す）
            Log::info('[ApplicationService] メッセージ作成開始', [
                'thread_id' => $thread->id,
                'sender_type' => 'corporate',
                'sender_id' => $corporateId,
                'message_length' => strlen($application->message),
                'sent_at' => $now->toDateTimeString(),
            ]);
            $message = Message::create([
                // どのスレッドに属するか
                'thread_id' => $thread->id,
                // 送信者の種別
                'sender_type' => 'corporate',
                // 送信者ID（FreelancerのID）
                'sender_id' => $corporateId,
                // メッセージ本文（applicationsの本文と同じ）
                'body' => $application->message,
                // 送信時刻
                'sent_at' => $now,
            ]);
            Log::info('[ApplicationService] メッセージ作成完了', [
                'message_id' => $message->id,
                'thread_id' => $message->thread_id,
                'sender_type' => $message->sender_type,
                'sender_id' => $message->sender_id,
                'body_length' => strlen($message->body),
                'sent_at' => $message->sent_at?->toDateTimeString(),
            ]);

            // スレッド側も「最新情報」を揃えておく（再利用スレッドだった場合にも更新する）
            Log::info('[ApplicationService] スレッド更新開始', [
                'thread_id' => $thread->id,
                'update_data' => [
                    'latest_sender_type' => 'corporate',
                    'latest_sender_id' => $corporateId,
                    'latest_message_at' => $now->toDateTimeString(),
                    'is_unread_for_company' => true,
                    'is_unread_for_corporate' => false,
                ],
            ]);
            $thread->forceFill([
                // 最後に送ったのはフリーランス
                'latest_sender_type' => 'corporate',
                // 最後に送った人（Freelancer ID）
                'latest_sender_id' => $corporateId,
                // 最終送信時刻
                'latest_message_at' => $now,
                // 企業側は未読にする
                'is_unread_for_company' => true,
                // フリーランス側は既読のまま
                'is_unread_for_corporate' => false,
            ])->save();
            Log::info('[ApplicationService] スレッド更新完了', [
                'thread_id' => $thread->id,
                'latest_sender_type' => $thread->latest_sender_type,
                'latest_sender_id' => $thread->latest_sender_id,
                'latest_message_at' => $thread->latest_message_at?->toDateTimeString(),
                'is_unread_for_company' => $thread->is_unread_for_company,
                'is_unread_for_corporate' => $thread->is_unread_for_corporate,
            ]);

            // Controllerはこのthreadへ遷移する（設計：応募後は即チャット）
            Log::info('[ApplicationService] トランザクション完了: 応募処理成功', [
                'thread_id' => $thread->id,
                'application_id' => $application->id,
                'message_id' => $message->id,
                'corporate_id' => $corporateId,
                'job_id' => $job->id,
            ]);
            return $thread;
        });
    }
}

