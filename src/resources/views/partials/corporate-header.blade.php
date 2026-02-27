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
                $navApplicationsActive = Request::routeIs('corporate.applications.*') || (($activeNav ?? null) === 'applications');
                $navScoutsActive = Request::routeIs('corporate.scouts.*') || (($activeNav ?? null) === 'scouts');
            @endphp
            <a href="{{ route('corporate.applications.index') }}" class="nav-link {{ $navApplicationsActive ? 'active' : '' }} {{ $appUnread > 0 ? 'has-badge' : '' }}">
                応募した案件
                @if($appUnread > 0)
                    <span class="badge" aria-live="polite">{{ $appUnread }}</span>
                @endif
            </a>
            <a href="{{ route('corporate.scouts.index') }}" class="nav-link {{ $navScoutsActive ? 'active' : '' }} {{ $scoutUnread > 0 ? 'has-badge' : '' }}">
                スカウト
                @if($scoutUnread > 0)
                    <span class="badge" aria-hidden="false">{{ $scoutUnread }}</span>
                @endif
            </a>
            <a href="{{ route('corporate.contracts.index') }}" class="nav-link {{ Request::routeIs('corporate.contracts.*') ? 'active' : '' }}">契約</a>
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

    <div class="mobile-nav" id="mobileNav" role="menu" aria-label="モバイルナビゲーション">
        <div class="mobile-nav-inner">
            <a href="{{ route('corporate.jobs.index') }}" class="nav-link {{ Request::routeIs('corporate.jobs.*') ? 'active' : '' }}">案件一覧</a>
            <a href="{{ route('corporate.applications.index') }}" class="nav-link {{ $navApplicationsActive ? 'active' : '' }} {{ $appUnread > 0 ? 'has-badge' : '' }}">
                応募した案件
                @if($appUnread > 0)
                    <span class="badge" aria-live="polite">{{ $appUnread }}</span>
                @endif
            </a>
            <a href="{{ route('corporate.scouts.index') }}" class="nav-link {{ $navScoutsActive ? 'active' : '' }} {{ $scoutUnread > 0 ? 'has-badge' : '' }}">
                スカウト
                @if($scoutUnread > 0)
                    <span class="badge" aria-hidden="false">{{ $scoutUnread }}</span>
                @endif
            </a>
            <a href="{{ route('corporate.contracts.index') }}" class="nav-link {{ Request::routeIs('corporate.contracts.*') ? 'active' : '' }}">契約</a>
        </div>
    </div>
</header>
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
<script>
(function () {
    const dropdown = document.getElementById('userDropdown');
    const toggle = document.getElementById('userDropdownToggle');
    const menu = document.getElementById('userDropdownMenu');
    if (!dropdown || !toggle || !menu) return;
    const open = () => { dropdown.classList.add('is-open'); toggle.setAttribute('aria-expanded', 'true'); };
    const close = () => { dropdown.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); };
    const isOpen = () => dropdown.classList.contains('is-open');
    toggle.addEventListener('click', (e) => { e.stopPropagation(); if (isOpen()) close(); else open(); });
    document.addEventListener('click', (e) => { if (!dropdown.contains(e.target)) close(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
})();
</script>
