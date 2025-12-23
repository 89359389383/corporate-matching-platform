<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Thread;

class FreelancerScoutController extends Controller
{
    /**
     * スカウト一覧を表示する（フリーランス側）
     *
     * - スカウトは job_id = null の thread を対象
     * - 未読はスレッド単位（thread.is_unread_for_freelancer）
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'freelancer') {
            abort(403);
        }

        $freelancer = $user->freelancer;

        if ($freelancer === null) {
            return redirect('/freelancer/profile')->with('error', '先にプロフィール登録が必要です');
        }

        $threads = Thread::query()
            ->where('freelancer_id', $freelancer->id)
            ->whereNull('job_id')
            ->with([
                'company',
                'messages' => function ($q) {
                    $q->orderByDesc('sent_at')->limit(1);
                },
            ])
            ->orderByDesc('latest_message_at')
            ->paginate(20)
            ->withQueryString();

        $threads->getCollection()->transform(function (Thread $thread) {
            $thread->is_unread = (bool) $thread->is_unread_for_freelancer;
            return $thread;
        });

        return view('freelancer.scouts.index', [
            'threads' => $threads,
        ]);
    }
}