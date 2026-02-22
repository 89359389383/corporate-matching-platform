<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Corporate;
use App\Models\Thread;

class CompanyCorporateController extends Controller
{
    /**
     * 法人一覧（検索付き）を表示する
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'company') {
            abort(403);
        }

        if ($user->company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        $keyword = $request->query('keyword');
        $rateMinRaw = $request->query('rate_min');
        $rateMaxRaw = $request->query('rate_max');
        $rateMin = (is_numeric($rateMinRaw) ? (int)$rateMinRaw : null);
        $rateMax = (is_numeric($rateMaxRaw) ? (int)$rateMaxRaw : null);
        if ($rateMin !== null && $rateMax !== null && $rateMin > $rateMax) {
            [$rateMin, $rateMax] = [$rateMax, $rateMin];
        }

        $query = Corporate::query()->with(['skills', 'customSkills', 'portfolios']);

        if (is_string($keyword) && $keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('display_name', 'like', '%' . $keyword . '%')
                    ->orWhere('job_title', 'like', '%' . $keyword . '%')
                    ->orWhere('bio', 'like', '%' . $keyword . '%')
                    ->orWhere('work_style_text', 'like', '%' . $keyword . '%')
                    ->orWhere('experience_companies', 'like', '%' . $keyword . '%')
                    ->orWhereRaw('CAST(min_rate as CHAR) like ?', ['%' . $keyword . '%'])
                    ->orWhereRaw('CAST(max_rate as CHAR) like ?', ['%' . $keyword . '%'])
                    ->orWhereHas('skills', function ($sq) use ($keyword) {
                        $sq->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('customSkills', function ($cq) use ($keyword) {
                        $cq->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $hasRateFilter = ($rateMin !== null || $rateMax !== null);
        if ($hasRateFilter) {
            $query->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->whereNotNull('min_rate')->where('min_rate', '>', 0);
                })->orWhere(function ($qq) {
                    $qq->whereNotNull('max_rate')->where('max_rate', '>', 0);
                });
            });

            if ($rateMin !== null && $rateMax !== null) {
                $query->whereRaw('COALESCE(NULLIF(max_rate, 0), NULLIF(min_rate, 0)) >= ?', [$rateMin])
                    ->whereRaw('COALESCE(NULLIF(min_rate, 0), NULLIF(max_rate, 0)) <= ?', [$rateMax]);
            } elseif ($rateMin !== null) {
                $query->whereRaw('COALESCE(NULLIF(max_rate, 0), NULLIF(min_rate, 0)) >= ?', [$rateMin]);
            } else {
                $query->whereRaw('COALESCE(NULLIF(min_rate, 0), NULLIF(max_rate, 0)) <= ?', [$rateMax]);
            }
        }

        $corporates = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $companyId = $user->company->id;

        $scoutThreadMap = [];
        foreach ($corporates as $corporate) {
            $thread = Thread::where('company_id', $companyId)
                ->where('corporate_id', $corporate->id)
                ->whereNull('job_id')
                ->first();
            if ($thread) {
                $scoutThreadMap[$corporate->id] = $thread->id;
            }
        }

        $unreadApplicationCount = Thread::query()
            ->where('company_id', $companyId)
            ->whereNotNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $unreadScoutCount = Thread::query()
            ->where('company_id', $companyId)
            ->whereNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $userInitial = '企';
        if ($user->company !== null && !empty($user->company->name)) {
            $userInitial = mb_substr($user->company->name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        return view('company.corporates.index', [
            'corporates' => $corporates,
            'keyword' => $keyword,
            'rateMin' => $rateMinRaw,
            'rateMax' => $rateMaxRaw,
            'scoutThreadMap' => $scoutThreadMap,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
        ]);
    }

    public function show(Corporate $corporate)
    {
        $user = Auth::user();
        if ($user->role !== 'company') abort(403);
        if ($user->company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        $corporate->load(['skills', 'customSkills', 'portfolios']);
        $company = $user->company;

        $unreadApplicationCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $unreadScoutCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $userInitial = '企';
        if ($company !== null && !empty($company->name)) {
            $userInitial = mb_substr($company->name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        return view('company.corporates.show', [
            'corporate' => $corporate,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
        ]);
    }
}

