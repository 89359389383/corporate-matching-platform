<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メッセージ - AITECH</title>

    @include('partials.corporate-header-style')

    <style>
        :root {
            --bg: #f6f8fb;
            --surface: #ffffff;
            --surface-2: #fbfcfe;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e6eaf2;
            --border-2: #dbe2ee;
            --primary: #0366d6;
            --primary-2: #0256cc;
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.06);
            --focus: 0 0 0 4px rgba(3, 102, 214, 0.14);
        }

        body {
            background-color: #fafbfc;
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
        }

        .panel {
            background-color: white;
            border-radius: 16px;
            padding: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e1e4e8;
            overflow: hidden;
        }

        .chat-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: linear-gradient(180deg, var(--surface) 0%, var(--surface-2) 100%);
        }

        .chat-title strong {
            font-size: 22px;
            font-weight: 900;
            color: var(--text);
        }

        .btn {
            padding: 0.6rem 1rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid var(--border-2);
            background: #fff;
            color: var(--text);
            font-size: 0.9rem;
        }

        .messages {
            padding: 20px;
            overflow-y: auto;
            display: grid;
            gap: 1rem;
            background:
                radial-gradient(900px 420px at 10% 0%, rgba(3,102,214,0.06), transparent 60%),
                linear-gradient(180deg,#ffffff 0%,#f8fafc 100%);
        }

        .bubble-row {
            display: flex;
            gap: 0.75rem;
        }

        .bubble-row.me {
            justify-content: flex-end;
        }

        .bubble {
            max-width: 75%;
            padding: 1rem 1.2rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #ffffff;
            box-shadow: var(--shadow-sm);
        }

        .bubble.me {
            background: linear-gradient(180deg,#f1f8ff 0%,#ecf6ff 100%);
            border-color: #cfe4ff;
        }

        .bubble p {
            font-size: 0.95rem;
            line-height: 1.7;
            white-space: pre-wrap;
        }

        .bubble small {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 0.5rem;
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .composer {
            padding: 1.25rem;
            border-top: 1px solid var(--border);
            display: grid;
            gap: 0.75rem;
            background: linear-gradient(180deg,var(--surface-2) 0%,var(--surface) 100%);
        }

        .input {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--border-2);
            border-radius: 14px;
            font-size: 0.95rem;
            min-height: 7rem;
            resize: vertical;
        }

        .input:focus {
            outline: none;
            border-color: rgba(3,102,214,0.5);
            box-shadow: var(--focus);
        }

        .send {
            padding: 12px 32px;
            border-radius: 14px;
            font-weight: 900;
            border: none;
            background: linear-gradient(180deg,var(--primary) 0%,var(--primary-2) 100%);
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-left: auto;
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
@include('partials.corporate-header')

<main class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-8">
<section class="panel">

<div class="chat-header">
    <div class="chat-title">
        <strong>
            {{ $thread->company->name ?? '企業名不明' }}
            @if($thread->job) / {{ $thread->job->title }} @endif
        </strong>
    </div>

    <div class="flex gap-3">
        @if($thread->job)
            <a class="btn" href="{{ route('corporate.jobs.show',['job'=>$thread->job->id]) }}">案件詳細</a>
        @endif
        @if(optional($thread->currentContract)->id)
            <a class="btn" href="{{ route('corporate.contracts.show', ['contract' => $thread->currentContract->id]) }}">契約</a>
        @else
            <button class="btn is-disabled" type="button" disabled>契約</button>
        @endif
    </div>
</div>

<div class="messages max-h-[65vh]" id="messages">
@php
    $activeMessages = $messages->whereNull('deleted_at')->sortBy('sent_at')->values();
    $latestMessage = $activeMessages->last();
@endphp

@forelse($activeMessages as $message)
@php
    $isMe = $message->sender_type === 'corporate';
    $sentAt = $message->sent_at ? $message->sent_at->format('Y/m/d H:i') : '';
    $isLatest = $latestMessage && $latestMessage->id === $message->id;
    $canDelete = $isMe && $isLatest;
@endphp

<div class="bubble-row {{ $isMe ? 'me' : '' }}">
    <div class="bubble {{ $isMe ? 'me' : '' }}">
        <p>{{ $message->body }}</p>
        <small>
            {{ $sentAt }}
            @if($canDelete)
            <span>
                <form action="{{ route('corporate.messages.destroy',['message'=>$message]) }}"
                      method="POST"
                      class="delete-form"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="delete-trigger"
                            style="background:none;border:none;color:#d73a49;font-weight:900;cursor:pointer;">
                        削除
                    </button>
                </form>
            </span>
            @endif
        </small>
    </div>
</div>
@empty
<div class="text-center py-10 text-gray-400">
    メッセージがありません。
</div>
@endforelse
</div>

<form class="composer"
      method="POST"
      action="{{ route('corporate.threads.messages.store',['thread'=>$thread]) }}">
@csrf
<textarea class="input @error('content') is-invalid @enderror"
          name="content"
          placeholder="メッセージを入力"></textarea>

@error('content')
<span class="text-red-600 text-sm font-bold">{{ $message }}</span>
@enderror

<button class="send" type="submit">送信</button>
</form>

</section>
</main>

<script>
(function(){
    const el=document.getElementById('messages');
    if(el) el.scrollTop=el.scrollHeight;
})();
</script>

<!-- 削除確認モーダル（機能そのまま） -->
@include('partials.delete-confirm-modal')

<script>
(function () {
    let pendingForm=null;
    document.addEventListener('click',e=>{
        const trigger=e.target.closest('.delete-trigger');
        if(trigger){
            e.preventDefault();
            pendingForm=trigger.closest('form');
            if(confirm('本当に削除しますか？')){
                pendingForm.submit();
            }
        }
    });
})();
</script>

</body>
</html>