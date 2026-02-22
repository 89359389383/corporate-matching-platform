<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スカウト一覧 - AITECH</title>
    {{-- ヘッダーに必要なスタイルのみをここに記載 --}}
    <style>
        /* Header (企業側と同じレスポンシブ構造) */
        .header { background-color: #ffffff; border-bottom: 1px solid #e1e4e8; padding: 0 var(--header-padding-x, 1rem); position: sticky; top: 0; z-index: 100; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); min-height: var(--header-height-current, 91px); }
        /* ... (既存スタイルを保持) ... */
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
                <a href="{{ route('corporate.jobs.index') }}" class="nav-link {{ Request::routeIs('corporate.jobs.*') ? 'active' : '' }}">案件一覧</a>
                @php
                    $appUnread = ($unreadApplicationCount ?? 0);
                    $scoutUnread = ($unreadScoutCount ?? 0);
                @endphp
                <a href="{{ route('corporate.applications.index') }}" class="nav-link {{ Request::routeIs('corporate.applications.*') ? 'active' : '' }} {{ $appUnread > 0 ? 'has-badge' : '' }}">
                    応募した案件
                    @if($appUnread > 0)
                        <span class="badge" aria-live="polite">{{ $appUnread }}</span>
                    @endif
                </a>
                <a href="{{ route('corporate.scouts.index') }}" class="nav-link {{ Request::routeIs('corporate.scouts.*') ? 'active' : '' }} {{ $scoutUnread > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($scoutUnread > 0)
                        <span class="badge" aria-hidden="false">{{ $scoutUnread }}</span>
                    @endif
                </a>
            </nav>

            <div class="header-right" role="region" aria-label="ユーザー">
                <button class="nav-toggle" id="mobileNavToggle" type="button" aria-label="メニューを開く" aria-haspopup="menu" aria-expanded="false" aria-controls="mobileNav">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"></path><path d="M3 12h18"></path><path d="M3 18h18"></path></svg>
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
    </header>

    <main class="main-content max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">スカウト</h1>
            <p class="page-subtitle">企業からのスカウトを確認できます。スレッドを開くとチャット画面に遷移します。</p>

            @if($threads->count() > 0)
                <div class="list grid grid-cols-1 gap-5 lg:gap-6">
                    @foreach($threads as $thread)
                        @php
                            $threadUrl = route('corporate.threads.show', ['thread' => $thread->id]);
                        @endphp
                        <article class="card rounded-2xl bg-white border border-slate-200 shadow-sm p-5 md:p-7 relative overflow-hidden" role="link" tabindex="0" onclick="window.location.href='{{ $threadUrl }}'" style="cursor: pointer;">
                            <div class="card-head">
                                <div>
                                    <h2 class="title">{{ $thread->company->name ?? '企業名不明' }}</h2>
                                    <div class="company">スカウト</div>
                                </div>
                                <div class="meta">
                                    @if($thread->is_unread)
                                        <span class="chip new">未読</span>
                                    @else
                                        <span class="chip read">既読</span>
                                    @endif
                                </div>
                            </div>
                            @php
                                $latestMessage = $thread->messages->first();
                                $scoutMessage = $thread->scout ? $thread->scout->message : null;
                                $displayMessage = $latestMessage ? $latestMessage->body : ($scoutMessage ?? 'メッセージがありません');
                            @endphp
                            <p class="desc">{{ $displayMessage }}</p>
                            <div class="actions flex flex-col md:flex-row justify-end gap-3 border-t border-slate-200 pt-4 mt-5">
                                <a class="btn btn-primary w-full md:w-auto" href="{{ route('corporate.threads.show', ['thread' => $thread->id]) }}">チャットへ</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- ページネーション --}}
                @if($threads->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $threads->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-8 md:p-10 text-center text-slate-600">
                    <p class="text-base md:text-lg font-semibold">スカウトはまだありません</p>
                </div>
            @endif
        </div>
    </main>

    <script>
        (function () {
            const header = document.querySelector('header.header');
            const toggle = document.getElementById('mobileNavToggle');
            const mobileNav = document.getElementById('mobileNav');
            if (!header || !toggle || !mobileNav) return;
            const OPEN_CLASS = 'is-mobile-nav-open';
            const isOpen = () => header.classList.contains(OPEN_CLASS);
            const open = () => { header.classList.add(OPEN_CLASS); toggle.setAttribute('aria-expanded', 'true'); };
            const close = () => { header.classList.remove(OPEN_CLASS); toggle.setAttribute('aria-expanded', 'false'); };
            toggle.addEventListener('click', (e) => { e.stopPropagation(); if (isOpen()) close(); else open(); });
            document.addEventListener('click', (e) => { if (!header.contains(e.target)) close(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
            window.addEventListener('resize', () => { if (window.innerWidth >= 768) close(); });
        })();
    </script>
</body>
</html>

