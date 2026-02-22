<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Scout;
use App\Models\Thread;

class CorporateApplicationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') abort(403);
        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        $status = $request->query('status', 'pending');
        $query = Application::query()
            ->where('corporate_id', $corporate->id)
            ->with(['job.company']);

        if ($status === 'closed') {
            $query->where('status', Application::STATUS_CLOSED);
        } else {
            $query->whereIn('status', [Application::STATUS_PENDING, Application::STATUS_IN_PROGRESS]);
        }

        $applications = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $threadsByKey = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereIn('job_id', $applications->getCollection()->pluck('job_id')->unique()->values())
            ->get()
            ->keyBy(function (Thread $t) {
                return (string) $t->job_id;
            });

        $applications->getCollection()->transform(function (Application $app) use ($threadsByKey) {
            $thread = $threadsByKey->get((string) $app->job_id);
            $app->thread = $thread;
            $app->is_unread = $thread ? ($thread->latest_sender_type !== 'corporate') : false;
            if ($thread) {
                $app->is_unread = (bool) $thread->is_unread_for_corporate;
            }
            return $app;
        });

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

        return view('corporate.applications.index', [
            'applications' => $applications,
            'status' => $status,
            'applicationCount' => $applicationCount,
            'scoutCount' => $scoutCount,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
            'corporate' => $corporate,
        ]);
    }
}

