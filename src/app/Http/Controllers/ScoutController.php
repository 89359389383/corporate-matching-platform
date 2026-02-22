<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ScoutRequest;
use App\Models\Corporate;
use App\Models\Job;
use App\Models\Scout;
use App\Models\Thread;
use App\Services\ScoutService;

class ScoutController extends Controller
{
    /**
     * スカウト入力画面を表示する（表示のみ）
     *
     * 入口: GET /company/scouts/create?freelancer_id={id}
     */
    public function create(Request $request)
    {
        // 認証ユーザーを取得する
        $user = Auth::user();

        // 企業以外は拒否する
        if ($user->role !== 'company') {
            abort(403);
        }

        // companyプロフィールを取得する
        $company = $user->company;

        // companyプロフィールが無い場合は先に登録へ誘導する
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        // クエリから corporate_id を取得する
        $corporateId = $request->query('corporate_id');

        // corporate_id が無い場合は一覧へ戻す
        if ($corporateId === null) {
            return redirect('/company/corporates')->with('error', 'スカウト対象の法人が指定されていません');
        }

        // 対象法人を取得する（表示用）
        $corporate = Corporate::query()->findOrFail($corporateId);

        // job_id は任意
        $jobId = $request->query('job_id');

        // job は任意で取得する（案件紐付けスカウトの場合）
        $job = null;
        if ($jobId !== null) {
            // 自社案件に限定して取得する（不正な紐付け防止）
            $job = Job::query()
                ->where('company_id', $company->id)
                ->findOrFail($jobId);
        }

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

        // 入力フォームビューを返す
        return view('company.scouts.create', [
            // 表示用法人
            'corporate' => $corporate,
            // 任意の紐付け案件
            'job' => $job,
            // ヘッダー用未読数
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            // ユーザー情報
            'userInitial' => $userInitial,
        ]);
    }

    /**
     * スカウト送信処理（store は Service に委譲）
     *
     * 入口: POST /company/scouts
     * 出口: チャット画面へリダイレクト（応募/スカウト後は即チャットへ）
     */
    public function store(ScoutRequest $request, ScoutService $scoutService)
    {
        // 認証ユーザーを取得する
        $user = Auth::user();

        // 企業以外は拒否する
        if ($user->role !== 'company') {
            abort(403);
        }

        // companyプロフィールを取得する
        $company = $user->company;

        // companyプロフィールが無い場合は先に登録へ誘導する
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        // 入力をバリデーションする（FormRequest に委譲）
        $validated = $request->validated();

        // job_id がある場合は「自社案件のみ」許可する
        $jobId = $validated['job_id'] ?? null;
        if ($jobId !== null) {
            // 自社案件でない場合は拒否する
            $jobBelongsToCompany = Job::query()
                ->where('id', $jobId)
                ->where('company_id', $company->id)
                ->exists();

            // 自社以外なら不正なので拒否する
            if (!$jobBelongsToCompany) {
                abort(403);
            }
        }

        // 既存スレッド有無チェック〜作成はServiceに委譲する（ScoutService::send）
        $thread = $scoutService->send(
            $company->id,
            (int) $validated['corporate_id'],
            $jobId === null ? null : (int) $jobId,
            $validated['message']
        );

        // 送信後は即チャットへ遷移する
        return redirect()
            ->route('company.threads.show', ['thread' => $thread])
            ->with('success', 'スカウトを送信しました');
    }

    /**
     * スカウト一覧（routes 対応のため最低限）
     *
     * ※設計書にはないが、web.php に /company/scouts があるため最低限を用意する
     */
    public function index()
    {
        // 認証ユーザーを取得する
        $user = Auth::user();

        // 企業以外は拒否する
        if ($user->role !== 'company') {
            abort(403);
        }

        // companyプロフィールを取得する
        $company = $user->company;

        // companyプロフィールが無い場合は先に登録へ誘導する
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        // スカウト一覧は company の thread を表示する（job_idがnullのもの）
        $threads = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id') // スカウトはjob_idがnull
            // 企業側は相手法人・案件を表示する
            ->with(['corporate', 'job'])
            // 最新メッセージを取得
            ->with(['messages' => function ($query) {
                $query->whereNull('deleted_at')
                    ->orderByDesc('sent_at')
                    ->limit(1);
            }])
            // 最新順
            ->orderByDesc('latest_message_at')
            ->paginate(20);

        // 未読判定とスカウト情報を付ける
        foreach ($threads->items() as $thread) {
            $thread->is_unread = (bool) $thread->is_unread_for_company;

            // 未読メッセージ数を計算（企業側から見て、法人が送信したメッセージ数）
            if ($thread->is_unread) {
                $thread->unread_count = \App\Models\Message::query()
                    ->where('thread_id', $thread->id)
                    ->whereNull('deleted_at')
                    ->where('sender_type', 'corporate')
                    ->count();
            } else {
                $thread->unread_count = 0;
            }

            // スカウト情報を取得（job_idがnullのスカウト）
            $scout = Scout::query()
                ->where('company_id', $company->id)
                ->where('corporate_id', $thread->corporate_id)
                ->whereNull('job_id')
                ->latest('id')
                ->first();
            
            $thread->scout = $scout;
        }

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
        return view('company.scouts.index', [
            // 表示用スレッド一覧
            'threads' => $threads,
            // ヘッダー用未読数
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            // ユーザー情報
            'userInitial' => $userInitial,
        ]);
    }
}