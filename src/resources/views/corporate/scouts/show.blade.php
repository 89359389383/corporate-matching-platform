<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スカウト詳細 - AITECH</title>
    {{-- ヘッダーに必要なスタイルのみをここに記載 --}}
    <style>
        /* Header (企業側と同じレスポンシブ構造: 640 / 768 / 1024 / 1280) */
        .header {
            background-color: #ffffff;
            border-bottom: 1px solid #e1e4e8;
            padding: 0 var(--header-padding-x, 1rem);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            min-height: var(--header-height-current, 91px);
        }
        /* ... (既存スタイルを保持) ... */
    </style>
    <style>
        /* 元のスカウト詳細ページのスタイルをそのまま保持します */
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
                <a href="{{ route('corporate.applications.index') }}" class="nav-link {{ (Request::routeIs('corporate.applications.*') || (isset($thread) && $thread->job_id !== null)) ? 'active' : '' }} {{ $appUnread > 0 ? 'has-badge' : '' }}">
                    応募した案件
                    @if($appUnread > 0)
                        <span class="badge" aria-live="polite">{{ $appUnread }}</span>
                    @endif
                </a>
                <a href="{{ route('corporate.scouts.index') }}" class="nav-link {{ (Request::routeIs('corporate.scouts.*') || (isset($thread) && $thread->job_id === null)) ? 'active' : '' }} {{ $scoutUnread > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($scoutUnread > 0)
                        <span class="badge" aria-hidden="false">{{ $scoutUnread }}</span>
                    @endif
                </a>
            </nav>

            <div class="header-right" role="region" aria-label="ユーザー">
                <button
                    class="nav-toggle"
                    id="mobileNavToggle"
                    type="button"
                    aria-label="メニューを開く"
                    aria-haspopup="menu"
                    aria-expanded="false"
                    aria-controls="mobileNav"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 6h18"></path>
                        <path d="M3 12h18"></path>
                        <path d="M3 18h18"></path>
                    </svg>
                </button>

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
                                <button type="submit" class="dropdown-item" role="menuitem" style="width: 100%; text-align: left; background: none; border: none; padding: 0.875rem 1.25rem; color: #586069; cursor: pointer; font-size: inherit; font-family: inherit;">ログアウト</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mobile-nav" id="mobileNav" role="menu" aria-label="モバイルナビゲーション">
            <div class="mobile-nav-inner">
                <a href="{{ route('corporate.jobs.index') }}" class="nav-link">案件一覧</a>
                <a href="{{ route('corporate.applications.index') }}" class="nav-link {{ (Request::routeIs('corporate.applications.*') || (isset($thread) && $thread->job_id !== null)) ? 'active' : '' }} {{ $appUnread > 0 ? 'has-badge' : '' }}">
                    応募した案件
                    @if($appUnread > 0)
                        <span class="badge" aria-live="polite">{{ $appUnread }}</span>
                    @endif
                </a>
                <a href="{{ route('corporate.scouts.index') }}" class="nav-link {{ (Request::routeIs('corporate.scouts.*') || (isset($thread) && $thread->job_id === null)) ? 'active' : '' }} {{ $scoutUnread > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($scoutUnread > 0)
                        <span class="badge" aria-hidden="false">{{ $scoutUnread }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <main class="main-content max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <section class="panel chat-pane" aria-label="チャット">
            <div class="chat-header">
                <div class="chat-title">
                    <strong>{{ $thread->company->name ?? '企業名不明' }}とのチャット</strong>
                </div>
                <a class="btn" href="{{ route('corporate.scouts.index') }}">一覧へ</a>
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
                    <div class="bubble-row {{ $isMe ? 'me' : '' }} {{ $isFirst ? 'is-first' : '' }}">
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
            const header = document.querySelector('header.header');
            const toggle = document.getElementById('mobileNavToggle');
            const mobileNav = document.getElementById('mobileNav');
            if (!header || !toggle || !mobileNav) return;
            const OPEN_CLASS = 'is-mobile-nav-open';
            const isOpen = () => header.classList.contains(OPEN_CLASS);
            const open = () => {
                header.classList.add(OPEN_CLASS);
                toggle.setAttribute('aria-expanded', 'true');
            };
            const close = () => {
                header.classList.remove(OPEN_CLASS);
                toggle.setAttribute('aria-expanded', 'false');
            };
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (isOpen()) close();
                else open();
            });
            document.addEventListener('click', (e) => {
                if (!header.contains(e.target)) close();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) close();
            });
        })();
    </script>
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
            if (el) el.scrollTop = el.scrollHeight;
        })();
    </script>
    <!-- 削除確認モーダル -->
    <div id="confirmDeleteModal" role="dialog" aria-hidden="true" aria-labelledby="confirmDeleteTitle" style="display:block;">
        <div style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;z-index:1000;">
            <div id="confirmDeleteDialog" style="pointer-events:auto;width:min(540px,92%);background:#fff;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.15);padding:1.25rem;display:none;" aria-modal="true">
                <h2 id="confirmDeleteTitle" style="margin:0 0 0.5rem;font-size:1.05rem;font-weight:800;color:#0f172a;">本当に削除しますか？</h2>
                <p style="margin:0 0 1rem;color:#64748b;">この操作は取り消せません。よろしければ「削除する」をクリックしてください。</p>
                <div style="display:flex;gap:0.75rem;">
                    <button id="cancelDeleteBtn" style="flex:1;padding:0.6rem 0.9rem;border-radius:8px;border:1px solid #e6eaf2;background:#fafbfc;cursor:pointer;">キャンセル</button>
                    <button id="confirmDeleteBtn" style="flex:1;padding:0.6rem 0.9rem;border-radius:8px;border:none;background:#d73a49;color:#fff;cursor:pointer;">削除する</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

