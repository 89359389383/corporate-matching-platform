<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メッセージ - AITECH</title>
    <style>
        /* ヘッダー共通スタイル（省略） */
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="header" role="banner">
        <div class="header-content">
            <div class="header-left">
                <div class="logo" aria-hidden="true">
                    <div class="logo-text">複業AI</div>
                </div>
            </div>

            <nav class="nav-links" role="navigation" aria-label="法人ナビゲーション">
                <a href="{{ route('corporate.jobs.index') }}" class="nav-link">案件一覧</a>
                @php
                    $appUnread = ($unreadApplicationCount ?? 0);
                    $scoutUnread = ($unreadScoutCount ?? 0);
                @endphp
                <a href="{{ route('corporate.applications.index') }}" class="nav-link {{ (Request::routeIs('corporate.applications.*') || (isset($thread) && isset($thread->job_id) && $thread->job_id !== null)) ? 'active' : '' }} {{ $appUnread > 0 ? 'has-badge' : '' }}">
                    応募した案件
                    @if($appUnread > 0)
                        <span class="badge" aria-live="polite">{{ $appUnread }}</span>
                    @endif
                </a>
                <a href="{{ route('corporate.scouts.index') }}" class="nav-link {{ (Request::routeIs('corporate.scouts.*') || (isset($thread) && isset($thread->job_id) && $thread->job_id === null)) ? 'active' : '' }} {{ $scoutUnread > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($scoutUnread > 0)
                        <span class="badge" aria-hidden="false">{{ $scoutUnread }}</span>
                    @endif
                </a>
            </nav>

            <div class="header-right" role="region" aria-label="ユーザー">
                <div class="user-menu">
                    <div class="dropdown" id="userDropdown">
                        <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">
                            @if(isset($corporate) && $corporate && $corporate->icon_path)
                                <img src="{{ asset('storage/' . $corporate->icon_path) }}" alt="プロフィール画像" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                {{ $userInitial ?? 'U' }}
                            @endif
                        </button>
                        <div class="dropdown-content" id="userDropdownMenu" role="menu" aria-label="ユーザーメニュー">
                            <a href="{{ route('corporate.profile.settings') }}" class="dropdown-item" role="menuitem">プロフィール設定</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('auth.logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item" role="menuitem">ログアウト</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <section class="panel chat-pane" aria-label="チャット">
            <div class="chat-header">
                <div class="chat-title">
                    <strong>{{ $thread->company->name ?? '企業名不明' }}@if($thread->job) / {{ $thread->job->title }}@endif</strong>
                </div>
                @if($thread->job)
                    <a class="btn" href="{{ route('corporate.jobs.show', ['job' => $thread->job->id]) }}">案件詳細</a>
                @endif
            </div>

            <div class="messages max-h-[70vh] md:max-h-[64vh] lg:max-h-[66vh]" id="messages" aria-label="メッセージ一覧">
                @forelse($messages as $message)
                    @php
                        $isMe = $message->sender_type === 'corporate';
                        $isFirst = $loop->first;
                        $senderName = '';
                        if ($message->sender_type === 'company') {
                            $senderName = mb_substr($thread->company->name ?? '企業', 0, 1);
                        } elseif ($message->sender_type === 'corporate') {
                            $senderName = mb_substr(auth()->user()->corporate->display_name ?? auth()->user()->email ?? 'U', 0, 1);
                        }
                        $sentAt = $message->sent_at ? $message->sent_at->format('m/d H:i') : '';
                        $isLatest = $loop->last;
                        $canDelete = $isMe && $message->sender_type === 'corporate';
                    @endphp
                    @if($isFirst)
                        <div class="bubble-row first-message">
                            <div class="bubble first-message">
                                <p>{{ $message->body }}</p>
                                <small>{{ $sentAt }}</small>
                            </div>
                        </div>
                    @endif
                    @if(!$isFirst)
                        <div class="bubble-row {{ $isMe ? 'me' : '' }}">
                            <div class="bubble {{ $isMe ? 'me' : '' }}">
                                <p>{{ $message->body }}</p>
                                <small>
                                    {{ $sentAt }}
                                    @if($canDelete && $isLatest)
                                        <span style="margin-left:0.75rem;">
                                            <form action="{{ route('corporate.messages.destroy', ['message' => $message->id]) }}" method="POST" style="display:inline;" class="delete-form" data-message-id="{{ $message->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="delete-trigger" style="background:none;border:none;color:#d73a49;font-weight:900;cursor:pointer;">削除</button>
                                            </form>
                                        </span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endif
                @empty
                    <div style="text-align: center; padding: 2rem; color: #6a737d;">
                        <p>メッセージがありません。</p>
                    </div>
                @endforelse
            </div>

            <form class="composer" action="{{ route('corporate.threads.messages.store', ['thread' => $thread->id]) }}" method="post">
                @csrf
                <textarea class="input @error('content') is-invalid @enderror" name="content" placeholder="メッセージを入力…" aria-label="メッセージを入力"></textarea>
                @error('content')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <button class="send w-full md:w-auto" type="submit">送信</button>
            </form>
        </section>
    </main>

    <script>
        (function () {
            const el = document.getElementById('messages');
            if (el) el.scrollTop = el.scrollHeight;
        })();
    </script>
</body>
</html>

