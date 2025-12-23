<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Freelancer;

class CompanyFreelancerController extends Controller
{
    /**
     * フリーランス一覧（検索付き）を表示する
     */
    public function index(Request $request)
    {
        // 認証ユーザーを取得する
        $user = Auth::user();

        // 企業以外は拒否する
        if ($user->role !== 'company') {
            abort(403);
        }

        // 企業プロフィールが無い場合は先に登録へ誘導する
        if ($user->company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        // 検索キーワードを取得する（keywordはGETクエリ）
        $keyword = $request->query('keyword');

        // フリーランスをベースに検索クエリを作る（プロフィール全体を検索）
        $query = Freelancer::query()->with(['skills', 'customSkills', 'portfolios']);

        // keyword がある場合、LIKE検索をかける（横断検索）
        if (is_string($keyword) && $keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                // 表示名にヒットさせる
                $q->where('display_name', 'like', '%' . $keyword . '%')
                    // 職種にヒットさせる
                    ->orWhere('job_title', 'like', '%' . $keyword . '%')
                    // 自己紹介にヒットさせる
                    ->orWhere('bio', 'like', '%' . $keyword . '%')
                    // 働き方にヒットさせる
                    ->orWhere('work_style_text', 'like', '%' . $keyword . '%')
                    // 経験企業にヒットさせる
                    ->orWhere('experience_companies', 'like', '%' . $keyword . '%')
                    // 希望単価（数値）にもヒットさせる
                    ->orWhereRaw('CAST(min_rate as CHAR) like ?', ['%' . $keyword . '%'])
                    ->orWhereRaw('CAST(max_rate as CHAR) like ?', ['%' . $keyword . '%'])
                    // マスタスキル名にもヒットさせる（JOIN代替: whereHas）
                    ->orWhereHas('skills', function ($sq) use ($keyword) {
                        $sq->where('name', 'like', '%' . $keyword . '%');
                    })
                    // カスタムスキル名にもヒットさせる
                    ->orWhereHas('customSkills', function ($cq) use ($keyword) {
                        $cq->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        // 一覧はページングで取得する
        $freelancers = $query->orderByDesc('id')->paginate(20)->withQueryString();

        // 一覧ビューへ返す
        return view('company.freelancers.index', [
            // 表示用の一覧
            'freelancers' => $freelancers,
            // 検索キーワードを保持する
            'keyword' => $keyword,
        ]);
    }

    /**
     * フリーランス詳細を表示する
     */
    public function show(Freelancer $freelancer)
    {
        // 認証ユーザーを取得する
        $user = Auth::user();

        // 企業以外は拒否する
        if ($user->role !== 'company') {
            abort(403);
        }

        // 企業プロフィールが無い場合は先に登録へ誘導する
        if ($user->company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        // 表示用に必要なリレーションを読み込む
        $freelancer->load(['skills', 'customSkills', 'portfolios']);

        // 詳細ビューへ返す
        return view('company.freelancers.show', [
            // 表示対象のフリーランス
            'freelancer' => $freelancer,
        ]);
    }
}