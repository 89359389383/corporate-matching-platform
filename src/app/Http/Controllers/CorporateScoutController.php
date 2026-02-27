<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Thread;
use App\Models\Scout;

class CorporateScoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') abort(403);
        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        $threads = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->with(['company', 'messages', 'scout'])
            ->orderByDesc('id')
            ->paginate(20);

        $applicationCount = 0;
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

        return view('corporate.scouts.index', [
            'threads' => $threads,
            'applicationCount' => $applicationCount,
            'scoutCount' => $scoutCount,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
            'corporate' => $corporate,
        ]);
    }
}

