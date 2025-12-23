<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ScoutRequest;
use App\Models\Freelancer;
use App\Models\Job;
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

        // クエリから freelancer_id を取得する
        $freelancerId = $request->query('freelancer_id');

        // freelancer_id が無い場合は一覧へ戻す
        if ($freelancerId === null) {
            return redirect('/company/freelancers')->with('error', 'スカウト対象のフリーランスが指定されていません');
        }

        // 対象フリーランスを取得する（表示用）
        $freelancer = Freelancer::query()->findOrFail($freelancerId);

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

        // 入力フォームビューを返す
        return view('company.scouts.create', [
            // 表示用フリーランス
            'freelancer' => $freelancer,
            // 任意の紐付け案件
            'job' => $job,
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
            (int) $validated['freelancer_id'],
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

        // スカウト一覧は company の thread を表示する
        $threads = Thread::query()
            ->where('company_id', $company->id)
            // 企業側は相手フリーランス・案件を表示する
            ->with(['freelancer', 'job'])
            // 最新順
            ->orderByDesc('latest_message_at')
            ->paginate(20);

        // 未読判定を付ける（threadのフラグを優先）
        $threads->getCollection()->transform(function (Thread $thread) {
            $thread->is_unread = (bool) $thread->is_unread_for_company;

            // 付与した thread を返す
            return $thread;
        });

        // 一覧ビューへ返す
        return view('company.scouts.index', [
            // 表示用スレッド一覧
            'threads' => $threads,
        ]);
    }
}