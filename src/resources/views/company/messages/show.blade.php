<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募案件チャット（企業）- AITECH</title>
    <style>
        :root { --header-height: 104px; --header-height-mobile: 91px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 97.5%; }
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafbfc;
            color: #24292e;
            line-height: 1.5;
        }

        /* Header（基準UIと統一） */
        .header {
            background-color: #ffffff;
            border-bottom: 1px solid #e1e4e8;
            padding: 0 3rem;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .header-content {
            max-width: 1600px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: var(--header-height);
            position: relative;
        }
        .nav-links {
            display: flex;
            flex-direction: row;
            gap: 3rem;
            align-items: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            justify-content: center;
        }
        .nav-link {
            text-decoration: none;
            color: #586069;
            font-weight: 500;
            font-size: 1.1rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            transition: all 0.15s ease;
            position: relative;
            letter-spacing: -0.01em;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .nav-link.has-badge { padding-right: 3rem; }
        .nav-link:hover { background-color: #f6f8fa; color: #24292e; }
        .nav-link.active {
            background-color: #0366d6;
            color: white;
            box-shadow: 0 2px 8px rgba(3, 102, 214, 0.3);
        }
        .badge {
            background-color: #d73a49;
            color: white;
            border-radius: 50%;
            padding: 0.15rem 0.45rem;
            font-size: 0.7rem;
            font-weight: 600;
            min-width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(209, 58, 73, 0.3);
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }
        .user-menu { display: flex; align-items: center; position: absolute; right: 0; top: 50%; transform: translateY(-50%); }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; cursor: pointer; transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none; padding: 0; appearance: none;
        }
        .user-avatar:hover { transform: scale(1.08); box-shadow: 0 4px 16px rgba(0,0,0,0.2); }
        .dropdown { position: relative; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 240px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            z-index: 1000;
            border: 1px solid #e1e4e8;
            margin-top: 0.5rem;
        }
        .dropdown.is-open .dropdown-content { display: block; }
        .dropdown-item {
            display: block;
            padding: 0.875rem 1.25rem;
            text-decoration: none;
            color: #586069;
            transition: all 0.15s ease;
            border-radius: 6px;
            margin: 0 0.25rem;
            white-space: nowrap;
        }
        .dropdown-item:hover { background-color: #f6f8fa; color: #24292e; }
        .dropdown-divider { height: 1px; background-color: #e1e4e8; margin: 0.5rem 0; }

        /* Page（基準UIと統一） */
        .main-content {
            max-width: 1600px;
            margin: 0 auto;
            padding: 3rem;
        }
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            color: #24292e;
            letter-spacing: -0.025em;
        }
        .page-subtitle {
            color: #6a737d;
            font-size: 1rem;
            margin-bottom: 2.25rem;
        }
        .panel {
            background-color: white;
            border-radius: 16px;
            padding: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            overflow: hidden;
        }
        .chat-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e1e4e8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .chat-title {
            display: grid;
            gap: 0.2rem;
            min-width: 0;
        }
        .chat-title strong {
            font-size: 1.05rem;
            font-weight: 900;
            color: #24292e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .chat-title span {
            color: #6a737d;
            font-size: 0.85rem;
            font-weight: 800;
        }
        .chat-tools {
            display: inline-flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .select {
            padding: 0.7rem 0.85rem;
            border: 2px solid #e1e4e8;
            border-radius: 10px;
            background: #fafbfc;
            font-weight: 900;
            color: #24292e;
            cursor: pointer;
        }
        .select:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3,102,214,0.1);
            background: #fff;
        }
        .btn {
            padding: 0.7rem 1rem;
            border-radius: 10px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            cursor: pointer;
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            color: #24292e;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        .btn:hover { background: #f6f8fa; transform: translateY(-1px); }

        .messages {
            padding: 70px;
            overflow-y: auto;
            display: grid;
            gap: 0.85rem;
            background: linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
            position: relative;
            max-height: 620px;
        }
        .bubble-row { display: flex; align-items: flex-end; gap: 0.75rem; }
        .bubble-row.me { justify-content: flex-end; }
        .bubble-row.first-message { max-width: 320px; }
        .bubble-row.first-message.me { justify-content: flex-end; margin-left: auto; }
        .bubble-row.first-message:not(.me) { justify-content: flex-start; margin-right: auto; }

        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: grid;
            place-items: center;
            color: white;
            font-weight: 900;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .bubble {
            max-width: 74%;
            padding: 0.9rem 1rem;
            border-radius: 14px;
            border: 1px solid #e1e4e8;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .bubble.me { background: #f1f8ff; border-color: #c8e1ff; }
        .bubble.first-message {
            max-width: 100%;
            background: #f6f8fa;
            border: 1px solid #e1e4e8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 1rem 1.25rem;
        }
        .bubble p { color: #24292e; font-size: 0.95rem; line-height: 1.6; white-space: pre-wrap; }
        .bubble small { display: block; margin-top: 0.4rem; color: #6a737d; font-weight: 800; font-size: 0.8rem; }
        .del {
            background: none;
            border: none;
            color: #d73a49;
            font-weight: 900;
            cursor: pointer;
            padding: 0;
        }

        .composer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #e1e4e8;
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            background: #ffffff;
        }
        .input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
            background-color: #fafbfc;
            min-height: 14rem;
            resize: vertical;
        }
        .input:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
            background-color: white;
        }
        .send {
            padding: 0.875rem 1.25rem;
            border-radius: 12px;
            font-weight: 900;
            border: none;
            background: #0366d6;
            color: white;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 0.95rem;
            width: 500px;
            max-width: 100%;
            margin: 0 auto;
        }
        .send:hover { background: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3); }

        @media (max-width: 920px) {
            .header-content { height: var(--header-height-mobile); }
            .nav-links { position: static; left: auto; transform: none; justify-content: flex-start; }
            .user-menu { position: static; transform: none; margin-left: auto; }
            .main-content { padding: 1.5rem; }
            .messages { padding: 1.5rem; max-height: 520px; }
            .bubble { max-width: 90%; }
            .bubble-row.first-message { max-width: calc(100% - 3rem); }
        }
        @media (max-width: 1200px) {
            .nav-links { gap: 1.5rem; }
            .nav-link { font-size: 1rem; padding: 0.6rem 0.9rem; }
            .nav-link.has-badge { padding-right: 2.6rem; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <nav class="nav-links">
                <a href="{{ route('company.freelancers.index') }}" class="nav-link">フリーランス一覧</a>
                <a href="{{ route('company.jobs.index') }}" class="nav-link">案件一覧</a>
                @php
                    $totalUnreadCount = ($unreadApplicationCount ?? 0) + ($unreadScoutCount ?? 0);
                @endphp
                <a href="{{ route('company.applications.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }} active">
                    応募された案件
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('company.scouts.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
            </nav>
            <div class="user-menu">
                <div class="dropdown" id="userDropdown">
                    <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">企</button>
                    <div class="dropdown-content" id="userDropdownMenu" role="menu" aria-label="ユーザーメニュー">
                        <a href="{{ route('company.profile.settings') }}" class="dropdown-item" role="menuitem">プロフィール設定</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('auth.logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item" role="menuitem" style="width: 100%; text-align: left; background: none; border: none; padding: 0.875rem 1.25rem; color: #586069; cursor: pointer; font-size: inherit; font-family: inherit;">ログアウト</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <h1 class="page-title">応募案件チャット</h1>
        <p class="page-subtitle">応募に関するやり取りを確認できます。開いた時点で未読が解除されます。</p>

        <section class="panel chat-pane" aria-label="応募チャット">
            <div class="chat-header">
                <div class="chat-title">
                    <strong>{{ $thread->job ? $thread->job->title : '案件' }}</strong>
                    <span>応募者：{{ $thread->freelancer->display_name ?? '不明' }} / 企業：{{ $thread->company->name ?? '不明' }}</span>
                </div>
                <div class="chat-tools">
                    @if($application)
                        <form method="POST" action="{{ route('company.threads.application-status.update', ['thread' => $thread]) }}" id="statusForm" style="display:inline-flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                            @csrf
                            @method('PATCH')
                            <label for="statusSelect" style="font-weight:900; color:#586069;">応募ステータス</label>
                            <select class="select" id="statusSelect" name="status" aria-label="応募ステータス">
                                <option value="0" {{ $application->status === \App\Models\Application::STATUS_PENDING ? 'selected' : '' }}>未対応</option>
                                <option value="1" {{ $application->status === \App\Models\Application::STATUS_IN_PROGRESS ? 'selected' : '' }}>対応中</option>
                                <option value="2" {{ $application->status === \App\Models\Application::STATUS_CLOSED ? 'selected' : '' }}>クローズ（終了）</option>
                            </select>
                        </form>
                    @endif
                    <a class="btn" href="{{ route('company.applications.index') }}">一覧へ</a>
                </div>
            </div>

            <div class="messages" id="messages" aria-label="メッセージ一覧">
                @php
                    $activeMessages = $messages->whereNull('deleted_at')->sortBy('sent_at')->values();
                    $latestMessage = $activeMessages->last();
                    $freelancerInitial = mb_substr($thread->freelancer->display_name ?? 'F', 0, 1);
                @endphp

                @forelse($activeMessages as $message)
                    @php
                        $isMe = $message->sender_type === 'company';
                        $isFirst = $loop->first;
                        $sentAt = $message->sent_at ? $message->sent_at->format('Y/m/d H:i') : '';
                        $isLatest = $latestMessage && $latestMessage->id === $message->id;
                        $canDelete = $isMe && $isLatest;
                    @endphp

                    @if($isFirst)
                        <div class="bubble-row first-message {{ $isMe ? 'me' : '' }}">
                            <div class="bubble first-message">
                                <p>{{ $message->body }}</p>
                                <small>
                                    {{ $sentAt }}
                                    @if($canDelete)
                                        <span style="margin-left:0.75rem;">
                                            <form action="{{ route('company.messages.destroy', ['message' => $message]) }}" method="POST" style="display:inline;" onsubmit="return confirm('このメッセージを削除しますか？');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="del">削除</button>
                                            </form>
                                        </span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="bubble-row {{ $isMe ? 'me' : '' }}">
                            @if(!$isMe)
                                <div class="avatar">{{ $freelancerInitial }}</div>
                            @endif
                            <div class="bubble {{ $isMe ? 'me' : '' }}">
                                <p>{{ $message->body }}</p>
                                <small>
                                    {{ $sentAt }}
                                    @if($canDelete)
                                        <span style="margin-left:0.75rem;">
                                            <form action="{{ route('company.messages.destroy', ['message' => $message]) }}" method="POST" style="display:inline;" onsubmit="return confirm('このメッセージを削除しますか？');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="del">削除</button>
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

            <form class="composer" method="POST" action="{{ route('company.threads.messages.store', ['thread' => $thread]) }}" id="messageForm">
                @csrf
                <textarea class="input" id="messageInput" name="content" placeholder="メッセージを入力（クローズ時は送信不可）" aria-label="メッセージ入力" required></textarea>
                <button class="send" id="sendBtn" type="submit">送信</button>
            </form>
        </section>
    </main>

    <script>
        (function () {
            const dropdown = document.getElementById('userDropdown');
            const toggle = document.getElementById('userDropdownToggle');
            const menu = document.getElementById('userDropdownMenu');
            if (!dropdown || !toggle || !menu) return;
            const open = () => { dropdown.classList.add('is-open'); toggle.setAttribute('aria-expanded', 'true'); };
            const close = () => { dropdown.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); };
            const isOpen = () => dropdown.classList.contains('is-open');
            toggle.addEventListener('click', (e) => { e.stopPropagation(); isOpen() ? close() : open(); });
            document.addEventListener('click', (e) => { if (!dropdown.contains(e.target)) close(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
        })();
    </script>
    <script>
        (function () {
            const status = document.getElementById('statusSelect');
            const statusForm = document.getElementById('statusForm');
            const input = document.getElementById('messageInput');
            const btn = document.getElementById('sendBtn');
            if (!status || !input || !btn) return;

            const apply = () => {
                const closed = status.value === '2';
                input.disabled = closed;
                btn.disabled = closed;
                btn.style.opacity = closed ? '0.5' : '1';
                btn.style.pointerEvents = closed ? 'none' : 'auto';
            };
            
            // ステータス変更時に自動送信
            if (statusForm) {
                status.addEventListener('change', () => {
                    statusForm.submit();
                });
            }
            
            apply();
        })();
    </script>
    <script>
        (function () {
            const el = document.getElementById('messages');
            if (!el) return;
            el.scrollTop = el.scrollHeight;
        })();
    </script>
</body>
</html>

