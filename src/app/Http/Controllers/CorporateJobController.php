<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Job;
use App\Models\Scout;
use App\Models\Thread;

class CorporateJobController extends Controller
{
    /**
     * 公開案件一覧を表示する（法人側）
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') {
            abort(403);
        }

        $keyword = $request->query('keyword');
        $query = Job::query()
            ->where('status', Job::STATUS_PUBLISHED)
            ->with('company');

        if (is_string($keyword) && $keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('required_skills_text', 'like', '%' . $keyword . '%')
                    ->orWhere('reward_type', 'like', '%' . $keyword . '%')
                    ->orWhere('work_time_text', 'like', '%' . $keyword . '%')
                    ->orWhereHas('company', function ($cq) use ($keyword) {
                        $cq->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $jobs = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $corporate = $user->corporate;

        $appliedJobIds = [];
        $threadMap = [];
        $applicationCount = 0;
        $scoutCount = 0;
        if ($corporate !== null) {
            $appliedJobIds = Application::query()
                ->where('corporate_id', $corporate->id)
                ->whereIn('job_id', $jobs->pluck('id'))
                ->pluck('job_id')
                ->toArray();

            if (!empty($appliedJobIds)) {
                $threads = Thread::query()
                    ->where('corporate_id', $corporate->id)
                    ->whereIn('job_id', $appliedJobIds)
                    ->get()
                    ->keyBy('job_id');
                $threadMap = $threads->toArray();
            }

            $applicationCount = Application::query()->where('corporate_id', $corporate->id)->count();
            $scoutCount = Scout::query()->where('corporate_id', $corporate->id)->count();

            $unreadScoutCount = Thread::query()
                ->where('corporate_id', $corporate->id)
                ->whereNull('job_id')
                ->where('is_unread_for_corporate', true)
                ->count();

            $unreadApplicationCount = Thread::query()
                ->where('corporate_id', $corporate->id)
                ->whereNotNull('job_id')
                ->where('is_unread_for_corporate', true)
                ->count();
        } else {
            $applicationCount = 0;
            $scoutCount = 0;
            $unreadScoutCount = 0;
            $unreadApplicationCount = 0;
        }

        $userInitial = 'U';
        if ($corporate !== null && !empty($corporate->display_name)) {
            $userInitial = mb_substr($corporate->display_name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        return view('corporate.jobs.index', [
            'jobs' => $jobs,
            'keyword' => $keyword,
            'appliedJobIds' => $appliedJobIds,
            'threadMap' => $threadMap,
            'corporate' => $corporate,
            'applicationCount' => $applicationCount,
            'scoutCount' => $scoutCount,
            'unreadApplicationCount' => $unreadApplicationCount ?? 0,
            'unreadScoutCount' => $unreadScoutCount ?? 0,
            'userInitial' => $userInitial,
        ]);
    }

    public function show(Job $job)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') abort(403);
        if ((int) $job->status !== (int) Job::STATUS_PUBLISHED) {
            return redirect('/corporate/jobs')->with('error', 'この案件は現在閲覧できません');
        }
        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        $alreadyApplied = Application::query()
            ->where('job_id', $job->id)
            ->where('corporate_id', $corporate->id)
            ->exists();

        $thread = null;
        if ($alreadyApplied) {
            $thread = Thread::query()
                ->where('company_id', $job->company_id)
                ->where('corporate_id', $corporate->id)
                ->where('job_id', $job->id)
                ->first();
        }

        $applicationCount = Application::query()->where('corporate_id', $corporate->id)->count();
        $scoutCount = Scout::query()->where('corporate_id', $corporate->id)->count();

        $unreadScoutCount = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereNull('job_id')
            ->where('is_unread_for_corporate', true)
            ->count();

        $unreadApplicationCount = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_corporate', true)
            ->count();

        $userInitial = 'U';
        if ($corporate !== null && !empty($corporate->display_name)) {
            $userInitial = mb_substr($corporate->display_name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        return view('corporate.jobs.show', [
            'job' => $job->load('company'),
            'alreadyApplied' => $alreadyApplied,
            'thread' => $thread,
            'applicationCount' => $applicationCount,
            'scoutCount' => $scoutCount,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
            'corporate' => $corporate,
        ]);
    }
}

