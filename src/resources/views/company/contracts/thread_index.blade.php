<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>契約（スレッド）- AITECH</title>
    @include('partials.company-header-style')
    <style>
        :root {
            --header-height: 72px;
            --header-height-mobile: 72px;
            --header-height-sm: 72px;
            --header-height-md: 72px;
            --header-height-lg: 72px;
            --header-height-xl: 72px;
            --header-height-current: var(--header-height-mobile);
            --header-padding-x: 1rem;
        }
        @media (min-width: 640px) {
            :root { --header-padding-x: 1.5rem; --header-height-current: var(--header-height-sm); }
        }
        @media (min-width: 768px) {
            :root { --header-padding-x: 2rem; --header-height-current: var(--header-height-md); }
        }
        @media (min-width: 1024px) {
            :root { --header-padding-x: 2.5rem; --header-height-current: var(--header-height-lg); }
        }
        @media (min-width: 1280px) {
            :root { --header-padding-x: 3rem; --header-height-current: var(--header-height-xl); }
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 97.5%; }
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafbfc;
            color: #24292e;
            line-height: 1.5;
        }
        .header {
            background-color: #ffffff;
            border-bottom: 1px solid #e1e4e8;
            padding: 0 var(--header-padding-x);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            min-height: var(--header-height-current);
        }
        .header-content {
            max-width: 1600px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 0.5rem;
            height: var(--header-height-current);
            position: relative;
            min-width: 0;
            padding: 0.25rem 0;
        }
        @media (min-width: 768px) {
            .header-content { grid-template-columns: auto 1fr auto; gap: 1rem; }
        }
        @media (min-width: 1024px) {
            .header-content { gap: 1.5rem; padding: 0.5rem 0; }
        }
        .header-left { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
        .header-right { display: flex; align-items: center; justify-content: flex-end; min-width: 0; gap: 0.75rem; }
        .logo { display: flex; align-items: center; gap: 8px; min-width: 0; }
        .logo-text {
            font-weight: 900;
            font-size: 18px;
            margin-left: 0;
            color: #111827;
            letter-spacing: 1px;
            white-space: nowrap;
        }
        @media (min-width: 640px) { .logo-text { font-size: 20px; } }
        @media (min-width: 768px) { .logo-text { font-size: 22px; } }
        @media (min-width: 1024px) { .logo-text { font-size: 24px; } }
        @media (min-width: 1280px) { .logo-text { font-size: 26px; } }
        .logo-badge {
            background: #0366d6;
            color: #fff;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        .nav-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #e1e4e8;
            background: #fff;
            cursor: pointer;
            transition: all 0.15s ease;
            flex: 0 0 auto;
        }
        .nav-toggle:hover { background: #f6f8fa; }
        .nav-toggle:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.15); }
        .nav-toggle svg { width: 22px; height: 22px; color: #24292e; }
        @media (min-width: 768px) { .nav-toggle { display: none; } }
        .nav-links {
            display: none;
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            min-width: 0;
            overflow: hidden;
            gap: 1.25rem;
        }
        @media (min-width: 640px) { .nav-links { display: none; } }
        @media (min-width: 768px) { .nav-links { display: flex; gap: 1.25rem; } }
        @media (min-width: 1024px) { .nav-links { gap: 2rem; } }
        @media (min-width: 1280px) { .nav-links { gap: 3rem; } }
        .nav-link {
            text-decoration: none;
            color: #586069;
            font-weight: 500;
            font-size: 1.05rem;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            transition: all 0.15s ease;
            position: relative;
            letter-spacing: -0.01em;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        @media (min-width: 768px) { .nav-link { font-size: 1.1rem; padding: 0.75rem 1.25rem; } }
        @media (min-width: 1280px) { .nav-link { font-size: 1.15rem; } }
        .nav-link.has-badge { padding-right: 3rem; }
        .nav-link:hover { background-color: #f6f8fa; color: #24292e; }
        .nav-link.active {
            background-color: #0366d6;
            color: white;
            box-shadow: 0 2px 8px rgba(3, 102, 214, 0.3);
        }
        .header .badge {
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
        .user-menu { display: flex; align-items: center; position: static; transform: none; }
        .mobile-nav {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-bottom: 1px solid #e1e4e8;
            box-shadow: 0 16px 40px rgba(0,0,0,0.10);
            padding: 0.75rem var(--header-padding-x);
            display: none;
            z-index: 110;
        }
        .header.is-mobile-nav-open .mobile-nav { display: block; }
        @media (min-width: 768px) { .mobile-nav { display: none !important; } }
        .mobile-nav-inner {
            max-width: 1600px;
            margin: 0 auto;
            display: grid;
            gap: 0.5rem;
        }
        .mobile-nav .nav-link {
            width: 100%;
            justify-content: flex-start;
            background: #fafbfc;
            border: 1px solid #e1e4e8;
            padding: 0.875rem 1rem;
        }
        .mobile-nav .nav-link:hover { background: #f6f8fa; }
        .mobile-nav .nav-link.active {
            background-color: #0366d6;
            color: #fff;
            border-color: #0366d6;
        }
        .mobile-nav .nav-link.has-badge { padding-right: 1rem; }
        .mobile-nav .badge {
            position: static;
            transform: none;
            margin-left: auto;
            margin-right: 0;
        }
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
            margin: 0.25rem;
            white-space: nowrap;
        }
        .dropdown-item:hover { background-color: #f6f8fa; color: #24292e; }
        .dropdown-divider { height: 1px; background-color: #e1e4e8; margin: 0.5rem 0; }

        .main { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .title { font-size: 26px; font-weight: 900; margin-bottom: 0.75rem; }
        .card { background: #fff; border: 1px solid #e1e4e8; border-radius: 14px; padding: 1rem 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 0.75rem; }
        .muted { color:#6a737d; font-weight:700; }
        .badge { display:inline-flex; padding: 0.2rem 0.6rem; border-radius: 999px; border:1px solid #e1e4e8; background:#fafbfc; font-weight:900; font-size: 12px; }
        .btn { display:inline-flex; padding: 0.6rem 0.9rem; border-radius: 10px; font-weight: 900; border:1px solid #e1e4e8; background:#fff; text-decoration:none; color:#111827; }
        .btn.primary { background:#0366d6; color:#fff; border-color:#0366d6; }
        .btn:hover { opacity:0.92; }
        .badge.status-active { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.25); color:#065f46; }
        .badge.status-completed { background: rgba(37,99,235,0.10); border-color: rgba(37,99,235,0.25); color:#1e40af; }
        .badge.status-draft { background: rgba(100,116,139,0.10); border-color: rgba(100,116,139,0.25); color:#334155; }
        .badge.status-proposed, .badge.status-negotiating, .badge.status-ready_to_sign, .badge.status-signed { background: rgba(245,158,11,0.10); border-color: rgba(245,158,11,0.25); color:#92400e; }
        .badge.status-terminated, .badge.status-archived { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.20); color:#7f1d1d; }
        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 0.9rem;
            border: 1px solid #000;
            background: #fff;
            color: #000;
            border-radius: 10px;
            font-weight: 900;
            font-size: 0.95rem;
            text-decoration: none;
        }
        .status-pill + .btn { margin-left: 0.5rem; }
        .filter-row { display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap; }
    </style>
</head>
<body>
@include('partials.company-header')

@php
    $statusLabels = [
        'draft' => '下書き',
        'proposed' => '提示中',
        'negotiating' => '交渉中',
        'ready_to_sign' => '署名待ち',
        'signed' => '締結済み',
        'active' => '履行中',
        'completed' => '完了',
        'terminated' => '終了',
        'archived' => 'アーカイブ',
    ];
    $selectedStatus = $selectedStatus ?? null;
    $filters = [
        '' => '全て',
        'active' => '履行中',
        'completed' => '完了',
        'draft' => '下書き',
        'proposed' => '提示中',
        'negotiating' => '交渉中',
        'ready_to_sign' => '署名待ち',
        'signed' => '締結済み',
        'terminated' => '終了',
        'archived' => 'アーカイブ',
    ];
@endphp

<main class="main">
    <h1 class="title">契約（スレッド）</h1>
    <p class="muted" style="margin-bottom:1.25rem;">
        相手: {{ $thread->corporate->display_name ?? '法人' }}
        @if($thread->job) / 案件: {{ $thread->job->title }} @endif
    </p>

    <div class="filter-row" style="margin-bottom:1rem;">
        <a class="btn" href="{{ route('company.threads.show', ['thread' => $thread]) }}">スレッドへ</a>
        @if($canCreate)
            <a class="btn primary" href="{{ route('company.threads.contracts.create', ['thread' => $thread]) }}">契約作成（下書き）</a>
        @endif

        @foreach($filters as $key => $label)
            @php
                $isActive = ($key === '' && ($selectedStatus === null || $selectedStatus === '')) || ($key !== '' && $selectedStatus === $key);
            @endphp
            <a
                class="btn {{ $isActive ? 'primary' : '' }}"
                href="{{ route('company.threads.contracts.index', array_filter(['thread' => $thread, 'status' => ($key !== '' ? $key : null)])) }}"
            >{{ $label }}</a>
        @endforeach
    </div>

    @forelse($contracts as $contract)
        <div class="card">
            <div style="display:flex; justify-content:space-between; gap:1rem;">
                <div>
                    <div style="font-weight:900;">v{{ $contract->version }} / {{ $contract->contract_type }}</div>
                    <div class="muted" style="margin-top:0.25rem;">
                        @php $sl = $statusLabels[$contract->status] ?? $contract->status; @endphp
                    </div>
                </div>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <span class="status-pill">{{ $sl }}</span>
                    <a class="btn" href="{{ route('company.contracts.show', ['contract' => $contract]) }}">表示</a>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="muted">契約はまだありません。</div>
        </div>
    @endforelse
</main>
</body>
</html>

