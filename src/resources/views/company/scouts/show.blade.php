<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スカウトチャット（企業）- AITECH</title>
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
        html { font-size: 97.5%; }
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafbfc;
            color: #24292e;
            line-height: 1.5;
        }

        /* Header */
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
            border-bottom: 1px solid #e1e4e8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: var(--header-height);
            position: relative;
        }
        .nav-links {
            display: flex;
            gap: 3rem;
            align-items: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            justify-content: center;
            flex-wrap: nowrap;
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
            background-color: #d73a49; color: white; border-radius: 50%;
            padding: 0.15rem 0.45rem; font-size: 0.7rem; font-weight: 600;
            min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center;
            box-shadow: 0 1px 3px rgba(209, 58, 73, 0.3);
            position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
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
            display: none; position: absolute; right: 0; top: 100%;
            background-color: white; min-width: 240px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px; z-index: 1000; border: 1px solid #e1e4e8; margin-top: 0.5rem;
        }
        .dropdown.is-open .dropdown-content { display: block; }
        .dropdown-item {
            display: block; padding: 0.875rem 1.25rem; text-decoration: none; color: #586069;
            transition: all 0.15s ease; border-radius: 6px; margin: 0 0.25rem; white-space: nowrap;
        }
        .dropdown-item:hover { background-color: #f6f8fa; color: #24292e; }
        .dropdown-divider { height: 1px; background-color: #e1e4e8; margin: 0.5rem 0; }

        /* Page */
        .main-content { max-width: 1200px; margin: 0 auto; padding: 3rem; }
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
            max-height: 620px;
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
            padding: 14px 40px;
            border-radius: 14px;
            font-weight: 900;
            border: none;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-2) 100%);
            color: white;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 20px;
            min-width: 260px;
            max-width: 100%;
            margin-left: auto;
            box-shadow: 0 10px 20px rgba(3, 102, 214, 0.22);
        }
        .send:hover { transform: translateY(-1px); box-shadow: 0 14px 26px rgba(3, 102, 214, 0.26); }
        .send:active { transform: translateY(0px); }
        .send:focus-visible { outline: none; box-shadow: var(--focus), 0 14px 26px rgba(3, 102, 214, 0.26); }

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
        /* Delete confirmation modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2,6,23,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .modal-overlay.is-open { display: flex; }
        .modal-dialog {
            background: var(--surface);
            border-radius: 12px;
            padding: 1.5rem;
            max-width: 420px;
            width: calc(100% - 2rem);
            box-shadow: 0 14px 40px rgba(2,6,23,0.18);
            border: 1px solid var(--border-2);
            text-align: left;
        }
        .modal-dialog h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.05rem;
            font-weight: 900;
            color: var(--text);
        }
        .modal-dialog p { color: var(--muted); margin: 0 0 1rem 0; }
        .modal-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        .modal-actions > button {
            flex: 1;
            min-width: 0; /* allow shrinking in flex */
            font-size: 0.92rem; /* match .btn */
            padding: 0.72rem 1rem;
            font-weight: 800;
            border-radius: 10px;
        }
        .btn-danger {
            background: linear-gradient(180deg, #d73a49 0%, #c5303f 100%);
            border: none;
            color: white;
            cursor: pointer;
        }
        .modal-actions .btn {
            background: linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
            color: var(--text);
            border: 1px solid var(--border-2);
        }
    </style>
    @include('partials.aitech-responsive')
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
                <a href="{{ route('company.applications.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }}">
                    応募された案件
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('company.scouts.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }} active">
                    スカウト
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
            </nav>
            <div class="user-menu">
                <div class="dropdown" id="userDropdown">
                    <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">{{ $userInitial ?? '企' }}</button>
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
        <h1 class="page-title">スカウトチャット</h1>
        <p class="page-subtitle">スカウト送信時のメッセージを先頭に表示します。開いた時点で未読が解除されます。</p>

        <section class="panel chat-pane" aria-label="スカウトチャット">
            <div class="chat-header">
                <div class="chat-title">
                    <strong>スカウトチャット</strong>
                    <span>宛先：{{ $thread->freelancer->display_name ?? '不明' }}（{{ $thread->job ? $thread->job->title : '案件紐付けなし' }}）</span>
                </div>
                <a class="btn" href="{{ route('company.scouts.index') }}">一覧へ</a>
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
                                            <form action="{{ route('company.messages.destroy', ['message' => $message->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('このメッセージを削除しますか？');">
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
                            <div class="bubble {{ $isMe ? 'me' : '' }}">
                                <p>{{ $message->body }}</p>
                                <small>
                                    {{ $sentAt }}
                                    @if($canDelete)
                                        <span style="margin-left:0.75rem;">
                                            <form action="{{ route('company.messages.destroy', ['message' => $message->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('このメッセージを削除しますか？');">
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

            <form class="composer" action="{{ route('company.threads.messages.store', ['thread' => $thread->id]) }}" method="POST">
                @csrf
                <textarea class="input" name="content" placeholder="メッセージを入力…" aria-label="メッセージ入力"></textarea>
                @error('content')
                    <div class="error" style="color: #e11d48; font-weight: 700; font-size: 0.9rem; margin-top: 0.25rem;">
                        {{ $message }}
                    </div>
                @enderror
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
            const el = document.getElementById('messages');
            if (!el) return;
            el.scrollTop = el.scrollHeight;
        })();
    </script>
</body>
</html>
