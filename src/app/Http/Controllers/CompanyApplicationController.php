<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Thread;

class CompanyApplicationController extends Controller
{
    /**
     * 応募された案件一覧（企業側）を表示する
     *
     * 入口:
     * - GET /company/applications?status=pending
     * - GET /company/applications?status=closed
     */
    public function index(Request $request)
    {
        // 認証ユーザーを取得する（企業想定）
        $user = Auth::user();

        // 企業以外は拒否する
        if ($user->role !== 'company') {
            abort(403);
        }

        // company を取得する（自社の応募だけを見るため）
        $company = $user->company;

        // company が無い場合は先に登録へ誘導する
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        // status パラメータを取得する（pending/closed）
        $status = $request->query('status', 'pending');

        // 自社案件への応募だけを取得する（company_idに紐づく応募のみ）
        $query = Application::query()
            // 一覧/モーダル表示に必要なカラムを明示（selectされない事故を防ぐ）
            ->select([
                'applications.id',
                'applications.job_id',
                'applications.corporate_id',
                'applications.message',
                'applications.desired_hourly_rate',
                'applications.work_days',
                'applications.work_time_from',
                'applications.work_time_to',
                'applications.note',
                'applications.weekly_hours',
                'applications.available_start',
                'applications.status',
                'applications.created_at',
                'applications.updated_at',
            ])
            ->whereHas('job', function ($q) use ($company) {
                // job.company_id が自社のものだけに絞る
                $q->where('company_id', $company->id);
            })
            // 表示に必要なリレーションを先読みする
            ->with([
                'job.company',
                // 応募者（法人）名だけ使うので最小限ロード
                'corporate:id,display_name',
            ]);

        // status に応じて絞り込む
        if ($status === 'closed') {
            // closed は クローズ のみ
            $query->where('status', Application::STATUS_CLOSED);
        } else {
            // pending は 未対応/対応中
            $query->whereIn('status', [Application::STATUS_PENDING, Application::STATUS_IN_PROGRESS]);
        }

        // 応募一覧を取得する（最新順）
        $applications = $query->orderByDesc('id')->paginate(20)->withQueryString();

        // 一覧に対応する thread をまとめて取得する（thread単位の未読表示に使用）
        $threadKeys = $applications->getCollection()->map(function (Application $app) {
            // thread は job_id + corporate_id の組み合わせで導出する
            return $app->job_id . ':' . $app->corporate_id;
        })->unique()->values();

        // thread をキー付きで引けるようにする（N+1回避）
        $threadsByKey = Thread::query()
            ->where('company_id', $company->id)
            ->whereIn('job_id', $applications->getCollection()->pluck('job_id')->unique()->values())
            ->get()
            ->keyBy(function (Thread $t) {
                // job_id:corporate_id のキーでまとめる
                return $t->job_id . ':' . $t->corporate_id;
            });

        // 応募に thread と未読フラグを付与する（未読=相手が最新送信者）
        $applications->getCollection()->transform(function (Application $app) use ($threadsByKey) {
            // thread を導出して紐付ける
            $thread = $threadsByKey->get($app->job_id . ':' . $app->corporate_id);

            // viewで使えるように動的プロパティを付ける
            $app->thread = $thread;

            // 未読判定（相手が最新送信者なら未読）
            $app->is_unread = $thread ? ($thread->latest_sender_type !== 'company') : false;

            // threadのフラグがあればそちらを優先する
            if ($thread) {
                $app->is_unread = (bool) $thread->is_unread_for_company;
            }

            // 付与した応募を返す
            return $app;
        });

        // ヘッダー用の未読数を計算する
        // 応募に関連するthreadの未読数（企業側）
        $unreadApplicationCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNotNull('job_id') // 応募はjob_idが必須
            ->where('is_unread_for_company', true)
            ->count();

        // スカウトに関連するthreadの未読数（企業側、job_idがnullのもの）
        $unreadScoutCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id') // スカウトはjob_idがnull
            ->where('is_unread_for_company', true)
            ->count();

        // ユーザー名の最初の文字を取得（アバター表示用）
        $userInitial = '企';
        if ($company !== null && !empty($company->name)) {
            $userInitial = mb_substr($company->name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        // 一覧ビューへ返す
        return view('company.applications.index', [
            // 表示用応募一覧
            'applications' => $applications,
            // タブ制御用
            'status' => $status,
            // ヘッダー用未読数
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            // ユーザー情報
            'userInitial' => $userInitial,
        ]);
    }

    /**
     * 応募ステータスを更新する
     *
     * 入口:
     * - PATCH /company/applications/{application}
     */
    public function update(Request $request, Application $application)
    {
        // 認証ユーザーを取得する（企業想定）
        $user = Auth::user();

        // 企業以外は拒否する
        if ($user->role !== 'company') {
            abort(403);
        }

        // company を取得する
        $company = $user->company;

        // company が無い場合は先に登録へ誘導する
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        // 応募が自社案件へのものかどうか確認する
        if ($application->job->company_id !== $company->id) {
            abort(403, 'アクセス権限がありません');
        }

        // バリデーション
        $request->validate([
            'status' => 'required|integer|in:0,1,2', // 0:未対応, 1:対応中, 2:クローズ
        ]);

        // ステータスを更新する
        $application->update([
            'status' => $request->status,
        ]);

        // 成功メッセージを付けて一覧へリダイレクト
        return redirect()->back()->with('success', '応募ステータスを更新しました');
    }
}