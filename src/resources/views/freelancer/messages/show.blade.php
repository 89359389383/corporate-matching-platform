<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メッセージ - AITECH</title>
    <style>
        :root {
            --header-height: 104px;       /* 80px * 1.3 */
            --header-height-mobile: 91px; /* 70px * 1.3 */

            /* UI tokens (high readability / production-grade) */
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
            --shadow-md: 0 10px 30px rgba(15, 23, 42, 0.10);
            --radius-lg: 18px;
            --radius-md: 14px;
            --radius-sm: 12px;
            --focus: 0 0 0 4px rgba(3, 102, 214, 0.14);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 100%; }
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background:
                radial-gradient(1200px 600px at 20% 0%, rgba(3, 102, 214, 0.08), transparent 60%),
                radial-gradient(900px 500px at 90% 10%, rgba(102, 126, 234, 0.10), transparent 55%),
                var(--bg);
            color: var(--text);
            line-height: 1.6;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Header Styles - Minimalist */
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
        .user-menu {
            display: flex;
            align-items: center;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none;
            padding: 0;
            appearance: none;
        }
        .user-avatar:hover { transform: scale(1.08); box-shadow: 0 4px 16px rgba(0,0,0,0.2); }
        .user-avatar:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.25), 0 2px 8px rgba(0,0,0,0.1); }

        /* Dropdown Menu */
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

        /* Page */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.75rem 3rem;
        }
        .page-title {
            font-size: 1.9rem;
            font-weight: 900;
            margin-bottom: 0.6rem;
            color: var(--text);
            letter-spacing: -0.03em;
        }
        .page-subtitle {
            color: var(--muted);
            font-size: 1.02rem;
            margin-bottom: 1.75rem;
            font-weight: 600;
        }

        .chat-shell {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 2rem;
            align-items: start;
        }

        .panel {
            background-color: var(--surface);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .panel-title {
            font-size: 1.05rem;
            font-weight: 900;
            margin-bottom: 1rem;
            color: #24292e;
        }

        .search-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
            background-color: #fafbfc;
            margin-bottom: 1rem;
        }
        .search-input:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
            background-color: white;
        }

        .thread-list {
            display: grid;
            gap: 0.5rem;
            max-height: 620px;
            overflow: auto;
            padding-right: 0.25rem;
        }

        .thread {
            display: grid;
            grid-template-columns: 44px 1fr auto;
            gap: 0.75rem;
            align-items: center;
            padding: 0.75rem;
            border-radius: 12px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .thread:hover { background: #f6f8fa; border-color: #e1e4e8; }
        .thread.is-active { background: #f1f8ff; border-color: #c8e1ff; }

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
            border: 1px solid rgba(255,255,255,0.55);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.18);
        }

        .thread-main { min-width: 0; }
        .thread-title {
            font-weight: 900;
            color: #24292e;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .thread-snippet {
            color: #6a737d;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 0.15rem;
        }
        .thread-meta {
            display: grid;
            justify-items: end;
            gap: 0.25rem;
        }
        .time { color: #6a737d; font-weight: 800; font-size: 0.8rem; }
        .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 0.4rem;
            border-radius: 999px;
            background: #d73a49;
            color: white;
            font-weight: 900;
            font-size: 0.75rem;
        }

        /* Chat pane */
        .chat-pane {
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: min(860px, calc(100vh - var(--header-height) - 7.5rem));
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
        .chat-title {
            display: grid;
            gap: 0.2rem;
            min-width: 0;
        }
        .chat-title strong {
            font-size: 1.05rem;
            font-weight: 900;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: -0.02em;
        }
        .chat-title span {
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .btn {
            padding: 0.72rem 1rem;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            cursor: pointer;
            border: 1px solid var(--border-2);
            background: linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
            color: var(--text);
            font-size: 0.92rem;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-sm); }
        .btn:focus-visible { outline: none; box-shadow: var(--focus), var(--shadow-sm); }

        .messages {
            padding: 1.25rem 1.25rem 1rem;
            overflow-y: auto;
            display: grid;
            gap: 0.85rem;
            background:
                radial-gradient(900px 420px at 10% 0%, rgba(3, 102, 214, 0.06), transparent 60%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            position: relative;
            scrollbar-gutter: stable;
            flex: 1 1 auto;
        }

        .bubble-row { display: flex; align-items: flex-end; gap: 0.75rem; }
        .bubble-row.me { justify-content: flex-end; }
        .bubble-row.first-message {
            justify-content: flex-end;
            width: 100%;
            margin-left: auto;
            position: sticky; /* メッセージ欄の右上に固定表示（見た目のみ） */
            top: 1rem;
            z-index: 3;
        }
        .bubble {
            max-width: 74%;
            width: 80%;
            padding: 0.9rem 1rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.92);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            overflow-wrap: anywhere;
        }
        .bubble.me {
            background: linear-gradient(180deg, rgba(241,248,255,0.98) 0%, rgba(236,246,255,0.98) 100%);
            border-color: #cfe4ff;
            width: 80%;
            padding: 20px;
        }
        .bubble.first-message {
            width: 80%;
            background: linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.98) 100%);
            margin-bottom: 50px;
            border: 1px solid var(--border-2);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.14);
            padding: 1rem 1.1rem 0.9rem;
            position: relative;
            border-radius: 18px;
        }
        .bubble.first-message::before {
            content: "最初のメッセージ";
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 22px;
            padding: 0 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.02em;
            color: rgba(2, 86, 204, 0.95);
            background: rgba(3, 102, 214, 0.10);
            border: 1px solid rgba(3, 102, 214, 0.18);
            margin-bottom: 0.5rem;
        }
        .bubble.first-message p {
            color: var(--text);
            font-weight: 650;
            font-size: 0.95rem;
            line-height: 1.7;
            white-space: pre-wrap;
        }
        .bubble.first-message small {
            color: var(--muted);
            font-weight: 800;
        }
        .bubble p { color: var(--text); font-size: 0.95rem; line-height: 1.7; white-space: pre-wrap; }
        .bubble small {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 0.45rem;
            color: var(--muted);
            font-weight: 800;
            font-size: 0.82rem;
        }

        .composer {
            padding: 1rem 1.25rem 1.25rem;
            border-top: 1px solid var(--border);
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            background: linear-gradient(180deg, var(--surface-2) 0%, var(--surface) 100%);
        }
        .input {
            width: 100%;
            padding: 0.95rem 1rem;
            border: 1px solid var(--border-2);
            border-radius: 14px;
            font-size: 0.98rem;
            transition: all 0.15s ease;
            background-color: #ffffff;
            min-height: 7.25rem;
            max-height: 18rem;
            resize: vertical;
            line-height: 1.7;
            box-shadow: inset 0 1px 0 rgba(15, 23, 42, 0.03);
        }
        .input:focus {
            outline: none;
            border-color: rgba(3, 102, 214, 0.55);
            box-shadow: var(--focus);
        }
        .send {
            padding: 0.85rem 1.2rem;
            border-radius: 14px;
            font-weight: 900;
            border: none;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-2) 100%);
            color: white;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 0.98rem;
            width: fit-content;
            min-width: 160px;
            max-width: 100%;
            margin-left: auto;
            box-shadow: 0 10px 20px rgba(3, 102, 214, 0.22);
        }
        .send:hover { transform: translateY(-1px); box-shadow: 0 14px 26px rgba(3, 102, 214, 0.26); }
        .send:active { transform: translateY(0px); }
        .send:focus-visible { outline: none; box-shadow: var(--focus), 0 14px 26px rgba(3, 102, 214, 0.26); }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-content { padding: 2rem 1.25rem 2.5rem; }
            .chat-shell { grid-template-columns: 340px 1fr; }
        }
        @media (max-width: 900px) {
            .chat-shell { grid-template-columns: 1fr; }
            .thread-list { max-height: 360px; }
            .messages { max-height: 500px; }
        }
        @media (max-width: 768px) {
            .header-content { padding: 0 1.5rem; height: var(--header-height-mobile); }
            .nav-links { gap: 1.5rem; position: static; left: auto; transform: none; justify-content: flex-start; flex-direction: row; flex-wrap: wrap; }
            .user-menu { position: static; right: auto; top: auto; transform: none; margin-left: auto; }
            .nav-link { padding: 0.5rem 1rem; font-size: 1rem; }
            .main-content { padding: 1.5rem 1rem 2rem; }
            .page-title { font-size: 1.65rem; }
            .page-subtitle { font-size: 0.98rem; }
            .chat-pane { min-height: min(860px, calc(100vh - var(--header-height-mobile) - 6.25rem)); }
            .messages { padding: 1rem; }
            .bubble { max-width: 92%; }
            .bubble-row.first-message {
                max-width: calc(100% - 1rem);
                margin-left: auto;
            }
            .bubble.first-message {
                max-width: 100%;
            }
            .composer { grid-template-columns: 1fr; }
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; scroll-behavior: auto !important; }
        }
    </style>
    @include('partials.aitech-responsive')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <nav class="nav-links">
                <a href="{{ route('freelancer.jobs.index') }}" class="nav-link">案件一覧</a>
                @php
                    $totalUnreadCount = ($unreadApplicationCount ?? 0) + ($unreadScoutCount ?? 0);
                @endphp
                <a href="{{ route('freelancer.applications.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }} active">
                    応募した案件
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('freelancer.scouts.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
            </nav>
            <div class="user-menu">
                <div class="dropdown" id="userDropdown">
                    <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">{{ $userInitial ?? 'U' }}</button>
                    <div class="dropdown-content" id="userDropdownMenu" role="menu" aria-label="ユーザーメニュー">
                        <a href="{{ route('freelancer.profile.settings') }}" class="dropdown-item" role="menuitem">プロフィール設定</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('auth.logout') }}" class="dropdown-item" role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <h1 class="page-title">応募案件チャット</h1>
        <p class="page-subtitle">応募に関するやり取りを確認できます。開いた時点で未読が解除されます。</p>

        <section class="panel chat-pane" aria-label="チャット">
            <div class="chat-header">
                <div class="chat-title">
                    <strong>{{ $thread->company->name ?? '企業名不明' }}@if($thread->job) / {{ $thread->job->title }}@endif</strong>
                    <span>案件に関するやり取り</span>
                </div>
                @if($thread->job)
                    <a class="btn" href="{{ route('freelancer.jobs.show', ['job' => $thread->job->id]) }}">案件詳細</a>
                @endif
            </div>

            <div class="messages" id="messages" aria-label="メッセージ一覧">
                @forelse($messages as $message)
                    @php
                        $isMe = $message->sender_type === 'freelancer';
                        $isFirst = $loop->first;
                        $senderName = '';
                        if ($message->sender_type === 'company') {
                            $senderName = mb_substr($thread->company->name ?? '企業', 0, 1);
                        } elseif ($message->sender_type === 'freelancer') {
                            $senderName = mb_substr(auth()->user()->freelancer->display_name ?? auth()->user()->email ?? 'U', 0, 1);
                        }
                        $sentAt = $message->sent_at ? $message->sent_at->format('m/d H:i') : '';
                        $isLatest = $loop->last;
                        $canDelete = $isMe && $message->sender_type === 'freelancer';
                    @endphp
                    @if($isFirst)
                        <div class="bubble-row first-message">
                            <div class="bubble first-message">
                                <p>{{ $message->body }}</p>
                                <small>
                                    {{ $sentAt }}
                                    @if($canDelete)
                                        <span style="margin-left:0.75rem;">
                                            <form action="{{ route('freelancer.messages.destroy', ['message' => $message->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('このメッセージを削除しますか？');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background:none;border:none;color:#d73a49;font-weight:900;cursor:pointer;">削除</button>
                                            </form>
                                        </span>
                                    @endif
                                </small>
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
                                            <form action="{{ route('freelancer.messages.destroy', ['message' => $message->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('このメッセージを削除しますか？');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background:none;border:none;color:#d73a49;font-weight:900;cursor:pointer;">削除</button>
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

            <form class="composer" action="{{ route('freelancer.threads.messages.store', ['thread' => $thread->id]) }}" method="post">
                @csrf
                <textarea class="input" name="content" placeholder="メッセージを入力…" aria-label="メッセージを入力" required></textarea>
                <button class="send" type="submit">送信</button>
            </form>
        </section>
    </main>

    <script>
        (function () {
            const dropdown = document.getElementById('userDropdown');
            const toggle = document.getElementById('userDropdownToggle');
            const menu = document.getElementById('userDropdownMenu');
            if (!dropdown || !toggle || !menu) return;

            const open = () => {
                dropdown.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
            };

            const close = () => {
                dropdown.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            };

            const isOpen = () => dropdown.classList.contains('is-open');

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (isOpen()) close();
                else open();
            });

            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) close();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });
        })();
    </script>

    <script>
        (function () {
            const el = document.getElementById('messages');
            if (!el) return;
            // 初期表示で最下部へ
            el.scrollTop = el.scrollHeight;
        })();
    </script>
</body>
</html>

