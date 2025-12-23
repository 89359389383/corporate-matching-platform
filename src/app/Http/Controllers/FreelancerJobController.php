<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Job;
use App\Models\Thread;

class FreelancerJobController extends Controller
{
    /**
     * 公開案件一覧を表示する（フリーランス側）
     */
    public function index(Request $request)
    {
        // 認証ユーザーを取得する（middlewareでも保護されている想定）
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // フリーランス以外は拒否する
        if ($user->role !== 'freelancer') {
            abort(403);
        }

        // キーワードを受け取る（keyword検索は任意）
        $keyword = $request->query('keyword');

        // 公開中の案件だけを対象にする（status = publish のみ）
        $query = Job::query()
            ->where('status', Job::STATUS_PUBLISHED)
            ->with('company');

        // キーワードがある場合、全体検索（LIKE連結）を行う
        if (is_string($keyword) && $keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                // タイトルにヒットさせる
                $q->where('title', 'like', '%' . $keyword . '%')
                    // 概要にヒットさせる
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    // 必須スキルにヒットさせる
                    ->orWhere('required_skills_text', 'like', '%' . $keyword . '%')
                    // 報酬種別にもヒットさせる
                    ->orWhere('reward_type', 'like', '%' . $keyword . '%')
                    // 稼働条件にヒットさせる
                    ->orWhere('work_time_text', 'like', '%' . $keyword . '%')
                    // 会社名にもヒットさせる（リレーション経由）
                    ->orWhereHas('company', function ($cq) use ($keyword) {
                        $cq->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        // 一覧をページングして取得する（大量データ想定）
        $jobs = $query->orderByDesc('id')->paginate(20)->withQueryString();

        // 一覧ビューへ返す
        return view('freelancer.jobs.index', [
            // 画面表示用の案件一覧
            'jobs' => $jobs,
            // 入力した検索キーワード（画面で保持するため）
            'keyword' => $keyword,
        ]);
    }

    /**
     * 案件詳細を表示し、応募済みならボタンを切り替える
     */
    public function show(Job $job)
    {
        // 認証ユーザーを取得する
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // フリーランス以外は拒否する
        if ($user->role !== 'freelancer') {
            abort(403);
        }

        // 公開中以外の案件は見せない（一覧へ戻す）
        if ((int) $job->status !== (int) Job::STATUS_PUBLISHED) {
            return redirect('/freelancer/jobs')->with('error', 'この案件は現在閲覧できません');
        }

        // freelancerプロフィールを取得する（応募済み判定に必要）
        $freelancer = $user->freelancer;

        // プロフィールが未登録なら登録へ誘導する
        if ($freelancer === null) {
            return redirect('/freelancer/profile')->with('error', '先にプロフィール登録が必要です');
        }

        // 応募済みかどうかを判定する（exists(Application)）
        $alreadyApplied = Application::query()
            ->where('job_id', $job->id)
            ->where('freelancer_id', $freelancer->id)
            ->exists();

        // 応募済みの場合、該当スレッドも取得しておく（「チャットを開く」導線用）
        $thread = null;
        if ($alreadyApplied) {
            // thread は company + freelancer + job で一意なのでそこから取得する
            $thread = Thread::query()
                ->where('company_id', $job->company_id)
                ->where('freelancer_id', $freelancer->id)
                ->where('job_id', $job->id)
                ->first();
        }

        // 詳細ビューへ返す
        return view('freelancer.jobs.show', [
            // 案件詳細（企業名表示のため company も読み込む）
            'job' => $job->load('company'),
            // 応募済みかどうか（ボタン切替用）
            'alreadyApplied' => $alreadyApplied,
            // 応募済みの場合のスレッド（チャット導線用）
            'thread' => $thread,
        ]);
    }
}