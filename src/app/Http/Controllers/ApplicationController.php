<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Job;
use App\Models\Thread;
use App\Services\ApplicationService;

class ApplicationController extends Controller
{
    /**
     * 応募確認・応募メッセージ入力画面を表示する
     *
     * 入口: GET /freelancer/jobs/{job}/apply
     * 出口: view（応募入力画面）
     */
    public function create(Job $job)
    {
        // 認証ユーザーを取得する
        $user = Auth::user();

        // フリーランス以外は拒否する
        if ($user->role !== 'freelancer') {
            // 想定外ロールならアクセス禁止にする
            abort(403);
        }

        // freelancerプロフィールを取得する（応募判定に必要）
        $freelancer = $user->freelancer;

        // freelancerプロフィールが未登録なら、先に登録へ誘導する
        if ($freelancer === null) {
            return redirect('/freelancer/profile')->with('error', '応募するにはプロフィール登録が必要です');
        }

        // 公開中以外の案件は応募不可
        if ((int) $job->status !== (int) Job::STATUS_PUBLISHED) {
            return redirect('/freelancer/jobs')->with('error', 'この案件は現在応募できません');
        }

        // 既応募判定を行う
        $alreadyApplied = Application::query()
            ->where('job_id', $job->id)
            ->where('freelancer_id', $freelancer->id)
            ->exists();

        // 既応募なら既存スレッドへリダイレクトする
        if ($alreadyApplied) {
            // 応募スレッドは company + freelancer + job で一意
            $thread = Thread::query()
                ->where('company_id', $job->company_id)
                ->where('freelancer_id', $freelancer->id)
                ->where('job_id', $job->id)
                ->first();

            // スレッドが見つかるならチャットへ遷移
            if ($thread !== null) {
                return redirect()->route('freelancer.threads.show', ['thread' => $thread]);
            }

            return redirect('/freelancer/jobs')->with('error', '既に応募済みですがスレッドが見つかりません');
        }

        // 応募入力画面を返す
        return view('freelancer.applications.create', [
            // 画面に案件情報を渡す（企業名表示のため company も読み込む）
            'job' => $job->load('company'),
        ]);
    }

    /**
     * 応募処理を実行し、応募スレッドと初回メッセージを作成する
     *
     * 入口: POST /freelancer/jobs/{job}/apply
     * 出口: redirect /freelancer/threads/{thread}
     */
    public function store(ApplicationRequest $request, Job $job, ApplicationService $applicationService)
    {
        // 認証ユーザーを取得する
        $user = Auth::user();

        // フリーランス以外は拒否する
        if ($user->role !== 'freelancer') {
            // 想定外ロールは拒否する
            abort(403);
        }

        // freelancerプロフィールを取得する（応募に必要）
        $freelancer = $user->freelancer;

        // freelancerプロフィールが未登録なら、先に登録へ誘導する
        if ($freelancer === null) {
            // 先にプロフィール登録へ誘導する
            return redirect('/freelancer/profile')->with('error', '応募するにはプロフィール登録が必要です');
        }

        // 公開中以外の案件は応募不可
        if ((int) $job->status !== (int) Job::STATUS_PUBLISHED) {
            return redirect('/freelancer/jobs')->with('error', 'この案件は現在応募できません');
        }

        // 応募内容をバリデーションする（message required / 文字数制限など）
        $validated = $request->validated();

        // 既応募なら既存スレッドへ誘導する
        $alreadyApplied = Application::query()
            ->where('job_id', $job->id)
            ->where('freelancer_id', $freelancer->id)
            ->exists();

        // 既応募の場合は新規作成せずスレッドへ遷移
        if ($alreadyApplied) {
            // スレッドを取得してチャットへ遷移する
            $thread = Thread::query()
                ->where('company_id', $job->company_id)
                ->where('freelancer_id', $freelancer->id)
                ->where('job_id', $job->id)
                ->firstOrFail();

            return redirect()->route('freelancer.threads.show', ['thread' => $thread]);
        }

        // 応募（Application + Thread + 初回Message）を作成する
        $thread = $applicationService->apply($freelancer->id, $job, $validated['message']);

        // 応募後はチャットへ遷移する
        return redirect()
            ->route('freelancer.threads.show', ['thread' => $thread])
            ->with('success', '応募が完了しました');
    }
}