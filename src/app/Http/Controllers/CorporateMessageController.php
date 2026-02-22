<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MessageRequest;
use App\Models\Application;
use App\Models\Message;
use App\Models\Scout;
use App\Models\Thread;
use App\Services\MessageService;

class CorporateMessageController extends Controller
{
    public function show(Thread $thread, MessageService $messageService)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') abort(403);

        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        if ((int) $thread->corporate_id !== (int) $corporate->id) {
            abort(403);
        }

        $thread->load(['company', 'job', 'messages']);

        $scout = null;
        $application = null;
        if ($thread->job_id === null) {
            $scout = Scout::query()
                ->where('company_id', $thread->company_id)
                ->where('corporate_id', $thread->corporate_id)
                ->whereNull('job_id')
                ->latest('id')
                ->first();
        } else {
            $application = Application::query()
                ->where('job_id', $thread->job_id)
                ->where('corporate_id', $thread->corporate_id)
                ->latest('id')
                ->first();
            if ($application === null) {
                $scout = Scout::query()
                    ->where('company_id', $thread->company_id)
                    ->where('corporate_id', $thread->corporate_id)
                    ->where('job_id', $thread->job_id)
                    ->latest('id')
                    ->first();
            }
        }

        $messageService->markRead($thread, 'corporate');

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

        if ($scout !== null && $application === null) {
            return view('corporate.scouts.show', [
                'thread' => $thread,
                'scout' => $scout,
                'messages' => $thread->messages,
                'applicationCount' => $applicationCount,
                'scoutCount' => $scoutCount,
                'unreadApplicationCount' => $unreadApplicationCount,
                'unreadScoutCount' => $unreadScoutCount,
                'userInitial' => $userInitial,
                'corporate' => $corporate,
            ]);
        }

        return view('corporate.messages.show', [
            'thread' => $thread,
            'application' => $application,
            'messages' => $thread->messages,
            'applicationCount' => $applicationCount,
            'scoutCount' => $scoutCount,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
            'corporate' => $corporate,
        ]);
    }

    public function store(MessageRequest $request, Thread $thread, MessageService $messageService)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') abort(403);
        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }
        if ((int) $thread->corporate_id !== (int) $corporate->id) abort(403);
        $validated = $request->validated();
        $messageService->send($thread, 'corporate', $corporate->id, $validated['content']);
        return redirect()->route('corporate.threads.show', ['thread' => $thread])->with('success', 'メッセージを送信しました');
    }

    public function destroy(Message $message)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') abort(403);
        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }
        $thread = $message->thread;
        if ($thread === null || (int) $thread->corporate_id !== (int) $corporate->id) abort(403);
        if ($message->sender_type !== 'corporate' || (int) $message->sender_id !== (int) $corporate->id) abort(403);
        $latestMessage = Message::query()
            ->where('thread_id', $thread->id)
            ->whereNull('deleted_at')
            ->orderByDesc('sent_at')
            ->first();
        if ($latestMessage === null || (int) $latestMessage->id !== (int) $message->id) {
            return redirect()->route('corporate.threads.show', ['thread' => $thread])->with('error', '削除できるのは直前の自分のメッセージのみです');
        }
        $message->delete();
        return redirect()->route('corporate.threads.show', ['thread' => $thread])->with('success', 'メッセージを削除しました');
    }
}

