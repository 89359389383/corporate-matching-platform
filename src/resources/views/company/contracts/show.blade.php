<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>契約表示（企業）- AITECH</title>
    @include('partials.company-header-style')
    <script src="https://cdn.tailwindcss.com"></script>
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

        .main { max-width: 1100px; margin: 0 auto; padding: 2rem; }
        .title { font-size: 26px; font-weight: 900; margin-bottom: 0.5rem; }
        .sub { color:#6a737d; font-weight:700; margin-bottom: 1.25rem; }
        .card { background:#fff; border:1px solid #e1e4e8; border-radius:14px; padding: 1.25rem; margin-bottom: 0.9rem; }
        .row { display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; }
        .badge { display:inline-flex; padding: 0.2rem 0.6rem; border-radius: 999px; border:1px solid #e1e4e8; background:#fafbfc; font-weight:900; font-size: 12px; }
        .btn { display:inline-flex; padding: 0.65rem 0.95rem; border-radius: 10px; font-weight: 900; border:1px solid #e1e4e8; background:#fff; text-decoration:none; color:#111827; cursor:pointer; }
        .btn.primary { background:#0366d6; color:#fff; border-color:#0366d6; }
        .btn.danger { background:#b31d28; color:#fff; border-color:#b31d28; }
        .btn:hover { opacity:0.92; }
        .k { color:#6a737d; font-weight:900; width: 180px; }
        .v { font-weight:800; white-space:pre-wrap; }
        table { width:100%; border-collapse: collapse; }
        td { border-top:1px solid #e1e4e8; padding: 0.7rem 0.5rem; vertical-align:top; }
    </style>
</head>
<body>
@include('partials.company-header')

<main class="main">
    <h1 class="title">契約</h1>
    <p class="sub">
        v{{ $contract->version }} / タイプ: {{ $contract->contract_type }} / 状態: {{ $contract->status }}
        @if($contract->isCurrent())（current）@endif
    </p>

    @if(session('success'))
        <div class="card" style="border-color:#b7f5c3; background:#e6ffed; font-weight:900;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="card" style="border-color:#ffccd2; background:#fff5f5; font-weight:900;">{{ session('error') }}</div>
    @endif
    @include('partials.error-panel')

    <div class="card">
        <div class="row">
            <a class="btn" href="{{ route('company.threads.contracts.index', ['thread' => $contract->thread_id]) }}">スレッドの契約一覧へ</a>
            <a class="btn" href="{{ route('company.threads.show', ['thread' => $contract->thread_id]) }}">スレッドへ</a>

            @if($contract->isEditableDraft())
                <a class="btn primary" href="{{ route('company.contracts.edit', ['contract' => $contract]) }}">編集</a>
                <form method="POST" action="{{ route('company.contracts.propose', ['contract' => $contract]) }}" style="display:inline;">
                    @csrf
                    <button class="btn primary" type="submit">法人へ提示</button>
                </form>
            @endif

            @if(in_array($contract->status, ['proposed','negotiating'], true) && $contract->isCurrent())
                <a class="btn" href="{{ route('company.contracts.versions.create', ['contract' => $contract]) }}">新版作成</a>
            @endif

            @if($contract->status === 'ready_to_sign' && $contract->isCurrent())
                <form method="POST" action="{{ route('company.contracts.agree', ['contract' => $contract]) }}" style="display:inline;">
                    @csrf
                    <button class="btn primary" type="submit">同意する（締結）</button>
                </form>
            @endif

            @if(in_array($contract->status, ['signed','active'], true) && $contract->isCurrent())
                <form method="POST" action="{{ route('company.contracts.complete', ['contract' => $contract]) }}" style="display:inline;">
                    @csrf
                    <button class="btn primary" type="submit">完了</button>
                </form>
            @endif

            @if($contract->pdf_path)
                <a class="btn" href="{{ route('company.contracts.pdf', ['contract' => $contract]) }}">PDF</a>
            @endif
        </div>
    </div>

    <div class="card">
        <div style="font-weight:900; margin-bottom:0.6rem;">基本情報</div>
        <table>
            <tr><td class="k">法人</td><td class="v">{{ $contract->corporate->display_name ?? ($contract->corporate->corporation_name ?? '') }}</td></tr>
            <tr><td class="k">案件</td><td class="v">{{ $contract->job ? $contract->job->title : '（スカウト）' }}</td></tr>
            <tr><td class="k">開始日</td><td class="v">{{ $contract->start_date ? $contract->start_date->format('Y-m-d') : '' }}</td></tr>
            <tr><td class="k">終了日</td><td class="v">{{ $contract->end_date ? $contract->end_date->format('Y-m-d') : '' }}</td></tr>
            <tr><td class="k">提示日時</td><td class="v">{{ $contract->proposed_at ? $contract->proposed_at->format('Y-m-d H:i') : '' }}</td></tr>
            <tr><td class="k">締結日時</td><td class="v">{{ $contract->signed_at ? $contract->signed_at->format('Y-m-d H:i') : '' }}</td></tr>
            <tr><td class="k">文面ハッシュ</td><td class="v">{{ $contract->document_hash ?? '' }}</td></tr>
            <tr><td class="k">PDFハッシュ</td><td class="v">{{ $contract->pdf_hash ?? '' }}</td></tr>
        </table>
    </div>

    <div class="card">
        <div style="font-weight:900; margin-bottom:0.6rem;">契約本文（terms_json）</div>
        @php $t = $contract->terms_json ?? []; @endphp
        <table>
            <tr><td class="k">法人名</td><td class="v">{{ $t['corporate_name'] ?? '' }}</td></tr>
            <tr><td class="k">案件名</td><td class="v">{{ $t['job_title'] ?? '' }}</td></tr>
            <tr><td class="k">契約期間</td><td class="v">{{ $t['contract_period'] ?? '' }}</td></tr>
            <tr><td class="k">取引条件</td><td class="v">{{ $t['trade_terms'] ?? '' }}</td></tr>
            <tr><td class="k">金額</td><td class="v">{{ $t['amount'] ?? '' }}</td></tr>
            <tr><td class="k">支払条件</td><td class="v">{{ $t['payment_terms'] ?? '' }}</td></tr>
            <tr><td class="k">成果物</td><td class="v">{{ $t['deliverables'] ?? '' }}</td></tr>
            <tr><td class="k">納期</td><td class="v">{{ $t['due_date'] ?? '' }}</td></tr>
            <tr><td class="k">業務範囲</td><td class="v">{{ $t['scope'] ?? '' }}</td></tr>
            <tr><td class="k">特約</td><td class="v">{{ $t['special_terms'] ?? '' }}</td></tr>
            <tr><td class="k">自由記述</td><td class="v">{{ $t['free_text'] ?? '' }}</td></tr>
        </table>
    </div>

    <div class="card">
        <div style="font-weight:900; margin-bottom:0.6rem;">署名状況</div>
        <div class="row">
            <span class="badge">署名数: {{ $contract->signatures->count() }}/2</span>
            <span class="badge">企業: {{ $contract->signatures->where('signer_type','company')->count() ? '済' : '未' }}</span>
            <span class="badge">法人: {{ $contract->signatures->where('signer_type','corporate')->count() ? '済' : '未' }}</span>
        </div>
    </div>

    @if($contract->status === 'active' && $contract->isCurrent())
        <div class="card">
            <div style="font-weight:900; margin-bottom:0.6rem;">契約終了（企業のみ）</div>
            <form method="POST" action="{{ route('company.contracts.terminate', ['contract' => $contract]) }}">
                @csrf
                <textarea name="reason" rows="3" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;" placeholder="終了理由（期間満了/合意終了/解除など）">{{ old('reason') }}</textarea>
                @error('reason') <div style="color:#dc2626;font-weight:900;">{{ $message }}</div> @enderror
                <div style="margin-top:0.75rem;">
                    <button class="btn danger" type="submit">終了する</button>
                </div>
            </form>
        </div>
    @endif

    @if($contract->changeRequests->count() > 0)
        <div class="card">
            <div style="font-weight:900; margin-bottom:0.6rem;">差し戻し履歴</div>
            @foreach($contract->changeRequests as $cr)
                <div style="border-top:1px solid #e1e4e8; padding-top:0.6rem; margin-top:0.6rem;">
                    <div class="muted" style="font-weight:900;">{{ $cr->created_at ? $cr->created_at->format('Y-m-d H:i') : '' }}</div>
                    <div class="v">{{ $cr->body }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="card">
        <div style="font-weight:900; margin-bottom:0.6rem;">監査ログ</div>
        @foreach($contract->auditLogs as $log)
            <div style="border-top:1px solid #e1e4e8; padding-top:0.6rem; margin-top:0.6rem;">
                <div class="muted">{{ $log->occurred_at ? $log->occurred_at->format('Y-m-d H:i') : '' }} / {{ $log->action }} / {{ $log->actor_type }}</div>
            </div>
        @endforeach
    </div>
</main>
</body>
</html>

