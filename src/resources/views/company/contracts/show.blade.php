{{-- resources/views/company/contracts/show.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>契約表示（企業）- AITECH</title>

    {{-- ✅ 上部ヘッダーは変更しない --}}
    @include('partials.company-header-style')
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --header-height: 72px;           /* md 基本高さ */
            --header-height-mobile: 72px;     /* xs / mobile */
            --header-height-sm: 72px;         /* sm */
            --header-height-md: 72px;        /* md */
            --header-height-lg: 72px;        /* lg */
            --header-height-xl: 72px;        /* xl */
            --header-height-current: var(--header-height-mobile);
            --header-padding-x: 1rem;
        }

        /* Breakpoint: sm (>=640px) */
        @media (min-width: 640px) {
            :root {
                --header-padding-x: 1.5rem;
                --header-height-current: var(--header-height-sm);
            }
        }

        /* Breakpoint: md (>=768px) -- デスクトップの基本 */
        @media (min-width: 768px) {
            :root {
                --header-padding-x: 2rem;
                --header-height-current: var(--header-height-md);
            }
        }

        /* Breakpoint: lg (>=1024px) */
        @media (min-width: 1024px) {
            :root {
                --header-padding-x: 2.5rem;
                --header-height-current: var(--header-height-lg);
            }
        }

        /* Breakpoint: xl (>=1280px) */
        @media (min-width: 1280px) {
            :root {
                --header-padding-x: 3rem;
                --header-height-current: var(--header-height-xl);
            }
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 97.5%; }
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafbfc;
            color: #24292e;
            line-height: 1.5;
        }

        /* Header (4 breakpoints: sm/md/lg/xl) */
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
            grid-template-columns: 1fr auto; /* mobile: ロゴ / 右側 */
            align-items: center;
            gap: 0.5rem;
            height: var(--header-height-current);
            position: relative;
            min-width: 0;
            padding: 0.25rem 0; /* 縦余白を確保 */
        }

        /* md以上: ロゴ / 中央ナビ / 右側 (ユーザー) */
        @media (min-width: 768px) {
            .header-content { grid-template-columns: auto 1fr auto; gap: 1rem; }
        }

        /* lg: より広く間隔を取る */
        @media (min-width: 1024px) {
            .header-content { gap: 1.5rem; padding: 0.5rem 0; }
        }

        .header-left { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
        .header-right { display: flex; align-items: center; justify-content: flex-end; min-width: 0; gap: 0.75rem; }

        /* ロゴ（左） */
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

        /* Mobile nav toggle */
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
            display: none; /* mobile: hidden (use hamburger) */
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            min-width: 0;
            overflow: hidden;
            gap: 1.25rem;
        }
        @media (min-width: 640px) { .nav-links { display: none; } } /* smではまだ省スペースにすることが多い */
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
        .user-menu { display: flex; align-items: center; position: static; transform: none; }

        /* Mobile nav menu */
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
            flex: 0 0 auto;
        }
        /* avatar size responsive */
        @media (min-width: 640px) { .user-avatar { width: 40px; height: 40px; } }
        @media (min-width: 768px) { .user-avatar { width: 44px; height: 44px; } }
        @media (min-width: 1024px) { .user-avatar { width: 48px; height: 48px; } }
        @media (min-width: 1280px) { .user-avatar { width: 52px; height: 52px; } }
        .user-avatar:hover { transform: scale(1.08); box-shadow: 0 4px 16px rgba(0,0,0,0.2); }
        .user-avatar:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.25), 0 2px 8px rgba(0,0,0,0.1); }

        /* Dropdown */
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

        :root{
            --page-bg:#f6f8fb;
            --card:#ffffff;
            --line:#e5e7eb;
            --muted:#6b7280;
            --text:#111827;
            --shadow: 0 1px 2px rgba(16,24,40,.06), 0 6px 18px rgba(16,24,40,.06);
            --radius: 14px;
        }
        body{ background:var(--page-bg); color:var(--text); }
        .container-max{ max-width: 1120px; }
        .card{
            background:var(--card);
            border:1px solid rgba(17,24,39,.08);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .tab{
            position: relative;
            padding:.75rem 1rem;
            font-weight:800;
            color:#374151;
            border-bottom:2px solid transparent;
        }
        .tab.active{
            color:#1d4ed8;
            border-bottom-color:#1d4ed8;
        }
        .pill{
            display:inline-flex;
            align-items:center;
            gap:.4rem;
            padding:.2rem .6rem;
            border-radius: 999px;
            font-weight:800;
            font-size:12px;
            border:1px solid rgba(17,24,39,.10);
            background:#fff;
        }
        .pill.purple{ background:rgba(168,85,247,.10); border-color:rgba(168,85,247,.25); color:#7c3aed; }
        .pill.blue{ background:rgba(59,130,246,.10); border-color:rgba(59,130,246,.25); color:#2563eb; }
        .pill.gray{ background:rgba(107,114,128,.08); border-color:rgba(107,114,128,.20); color:#374151; }
        .btn{
            display:inline-flex;
            align-items:center;
            gap:.5rem;
            padding:.65rem 1rem;
            border-radius: 12px;
            font-weight:900;
            border:1px solid rgba(17,24,39,.12);
            background:#fff;
            color:#111827;
            transition:.15s;
        }
        .btn:hover{ filter:brightness(.98); transform: translateY(-1px); }
        .btn:active{ transform: translateY(0); }
        .btn.primary{
            background:#1d4ed8;
            border-color:#1d4ed8;
            color:#fff;
        }
        .kv{
            display:grid;
            grid-template-columns: 140px 1fr;
            gap: .75rem;
            padding: .65rem 0;
            border-top:1px solid rgba(17,24,39,.08);
        }
        .kv:first-child{ border-top:none; padding-top:0; }
        .k{ color:#6b7280; font-weight:800; font-size: 13px; }
        .v{ font-weight:900; color:#111827; white-space: pre-wrap; }
        .subv{ font-weight:800; color:#374151; white-space: pre-wrap; }
        .amount-value {
            font-size: 16px !important;
            margin-left: -10px;
            display: inline-block;
        }
        .check-item{
            display:flex; align-items:flex-start; gap:.6rem;
            padding:.35rem 0;
        }
        .toast{
            position: fixed;
            left: 50%;
            bottom: 24px;
            transform: translateX(-50%);
            background: rgba(17,24,39,.92);
            color: #fff;
            padding: .8rem 1.1rem;
            border-radius: 999px;
            font-weight: 900;
            box-shadow: 0 12px 30px rgba(0,0,0,.18);
            opacity: 0;
            pointer-events: none;
            transition: .2s;
            z-index: 9999;
        }
        .toast.show{ opacity:1; }
    </style>
</head>
<body>
@include('partials.company-header')

@php
    $t = $contract->terms_json ?? [];

    $corpName = $contract->corporate->display_name ?? ($contract->corporate->corporation_name ?? '');
    $jobTitle  = $contract->job ? $contract->job->title : '（スカウト）';

    $start = $contract->start_date ? $contract->start_date->format('Y/m/d') : '';
    $end   = $contract->end_date ? $contract->end_date->format('Y/m/d') : '';

    $createdAt = $contract->created_at ? $contract->created_at->format('Y/m/d H:i') : '';
    $ver = 'v' . ($contract->version ?? '1');

    // ✅ 画面の表示項目は維持したまま、コピー用テキストを「1回で全部」作る
    $copyLines = [];
    $copyLines[] = "【契約】";
    $contractTitle = $t['title'] ?? ($contract->job ? $contract->job->title : ($contract->contract_type ?? '契約'));
    $copyLines[] = "タイトル: " . $contractTitle;
    $copyLines[] = "作成日: {$createdAt}";
    $copyLines[] = "バージョン: {$ver}";
    $copyLines[] = "タイプ: " . ($contract->contract_type ?? '');
    $copyLines[] = "状態: " . ($contract->status ?? '');
    $copyLines[] = "";
    $copyLines[] = "【契約概要】";
    $copyLines[] = ($t['scope'] ?? ''); // 画面の概要に合わせて scope を概要扱い（必要なら別キーに差し替えOK）
    $copyLines[] = "";
    $copyLines[] = "【当事者】";
    $copyLines[] = "企業: {$corpName}";
    $copyLines[] = "フリーランス: " . ($t['freelancer_name'] ?? ''); // 無ければ空
    $copyLines[] = "";
    $copyLines[] = "【契約期間】";
    $copyLines[] = "開始日: {$start}";
    $copyLines[] = "終了日: {$end}";
    $copyLines[] = "稼働時間: " . ($t['trade_terms'] ?? ''); // 稼働時間を trade_terms に入れている想定（違うならキー変更）
    $copyLines[] = "";
    $copyLines[] = "【報酬】";
    $copyLines[] = "金額: " . ($t['amount'] ?? '');
    $copyLines[] = "支払条件: " . ($t['payment_terms'] ?? '');
    $copyLines[] = "";
    $copyLines[] = "【成果物】";
    $copyLines[] = (is_array($t['deliverables'] ?? null) ? implode("\n", $t['deliverables']) : ($t['deliverables'] ?? ''));
    $copyLines[] = "";
    $copyLines[] = "【秘密保持】";
    $copyLines[] = "保持期間: " . ($t['confidentiality_period'] ?? '');
    $copyLines[] = "対象範囲: " . ($t['confidentiality_scope'] ?? '');
    $copyLines[] = "";
    $copyLines[] = "【その他条項】";
    $copyLines[] = ($t['special_terms'] ?? '');
    $copyLines[] = ($t['free_text'] ?? '');
    $copyLines[] = "";
    $copyLines[] = "【基本情報（ハッシュ等）】";
    $copyLines[] = "案件: {$jobTitle}";
    $copyLines[] = "提示日時: " . ($contract->proposed_at ? $contract->proposed_at->format('Y/m/d H:i') : '');
    $copyLines[] = "締結日時: " . ($contract->signed_at ? $contract->signed_at->format('Y/m/d H:i') : '');
    $copyLines[] = "文面ハッシュ: " . ($contract->document_hash ?? '');
    $copyLines[] = "PDFハッシュ: " . ($contract->pdf_hash ?? '');

    $copyAllText = implode("\n", $copyLines);

    // ステータス表示（スクショの紫バッジに寄せる）
    $statusLabel = $contract->status ?? '';
    $statusPillClass = 'pill gray';
    if (in_array($statusLabel, ['ready_to_sign','proposed','negotiating'], true)) $statusPillClass = 'pill purple';
    if (in_array($statusLabel, ['signed','active'], true)) $statusPillClass = 'pill blue';

    $hasCompanySig = $contract->signatures->where('signer_type', 'company')->count() > 0;
    $hasCorporateSig = $contract->signatures->where('signer_type', 'corporate')->count() > 0;
    $canCompanySignNow = ($hasCorporateSig && !$hasCompanySig && ($contract->status === \App\Models\Contract::STATUS_READY_TO_SIGN));
    $canCompleteNow = ($hasCompanySig && $hasCorporateSig && in_array($contract->status, [\App\Models\Contract::STATUS_SIGNED, \App\Models\Contract::STATUS_ACTIVE], true));
@endphp

<main class="container-max mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- 上部：戻る + タイトル + バッジ + アクション（スクショに寄せる） --}}
    <div class="flex flex-col gap-3">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3 min-w-0">
                <a href="{{ route('company.contracts.index') }}"
                   class="mt-1 inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                   aria-label="戻る">
                    {{-- back icon --}}
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div class="min-w-0">
                    <h1 class="text-[22px] sm:text-[26px] font-black tracking-tight truncate">
                        {{ $contractTitle }}
                    </h1>
                    <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-gray-500 font-bold">
                        <span>作成日: {{ $createdAt }}</span>
                        <span class="text-gray-300">/</span>
                        <span>バージョン: {{ $ver }}</span>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="pill blue">
                            {{-- doc icon --}}
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ $contract->contract_type ?? '個別契約' }}
                        </span>
                        <span class="{{ $statusPillClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2">
                @if($canCompleteNow)
                    <form method="POST" action="{{ route('company.contracts.complete', ['contract' => $contract]) }}">
                        @csrf
                        <button type="submit" class="btn primary">完了する</button>
                    </form>
                @endif
                @if($canCompanySignNow)
                    <form method="POST" action="{{ route('company.contracts.agree', ['contract' => $contract]) }}">
                        @csrf
                        <button type="submit" class="btn primary">署名する</button>
                    </form>
                @endif
                {{-- 編集ボタン：コピーの左に配置（クリックで編集画面へ遷移） --}}
                <a href="{{ route('company.contracts.edit', ['contract' => $contract]) }}" class="btn" aria-label="編集">
                    {{-- edit icon --}}
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="mr-1">
                        <path d="M3 21v-3.75L17.81 2.44a2 2 0 0 1 2.83 0l0 0a2 2 0 0 1 0 2.83L5.83 20.08 3 21z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 4l6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    編集
                </a>
                {{-- ✅ 1回のボタンで全部コピー --}}
                <button type="button" id="copyAllBtn" class="btn">
                    {{-- copy icon --}}
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
                        <rect x="2" y="2" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    全てコピー
                </button>

                <!-- PDF出力（非表示） -->

                {{-- 署名ボタンは条件に応じて表示 --}}
            </div>
        </div>
    </div>

    {{-- 通知 --}}
    @if(session('success'))
        <div class="mt-5 card p-4 border border-emerald-200 bg-emerald-50 text-emerald-900 font-black">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mt-5 card p-4 border border-rose-200 bg-rose-50 text-rose-900 font-black">
            {{ session('error') }}
        </div>
    @endif
    @include('partials.error-panel')

    {{-- 本体（スクショ：左メイン / 右サイド） --}}
    <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- 左（2列分） --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- 契約概要 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        {{-- info icon --}}
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="#111827" stroke-width="2"/>
                            <path d="M12 16v-5" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 8h.01" stroke="#111827" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-black">契約概要</h2>
                        <p class="mt-2 text-gray-700 font-bold leading-relaxed">
                            {{ $t['scope'] ?? 'React/TypeScriptを使用したWebアプリケーションのフロントエンド開発業務' }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- 契約期間 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        {{-- calendar --}}
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="#111827" stroke-width="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">契約期間</h2>

                        <div class="mt-3">
                            <div class="kv">
                                <div class="k">開始日</div>
                                <div class="v">{{ $contract->start_date ? $contract->start_date->format('Y/m/d') : '' }}</div>
                            </div>
                            <div class="kv">
                                <div class="k">終了日</div>
                                <div class="v">{{ $contract->end_date ? $contract->end_date->format('Y/m/d') : '' }}</div>
                            </div>
                            <div class="kv">
                                <div class="k">稼働時間</div>
                                <div class="subv">{{ $t['trade_terms'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 報酬 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        {{-- yen --}}
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M6 3l6 9 6-9" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 12h12" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                            <path d="M6 16h12" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 12v9" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">報酬</h2>

                        <div class="mt-3">
                            <div class="kv">
                                <div class="k">金額</div>
                                <div class="v amount-value"> {{ $t['amount'] ?? '' }}</div>
                            </div>
                            <div class="kv">
                                <div class="k">支払条件</div>
                                <div class="subv">{{ $t['payment_terms'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 成果物 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        {{-- checklist --}}
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M9 11l3 3L22 4" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">成果物</h2>

                        @php
                            $deliverables = $t['deliverables'] ?? [];
                            if (!is_array($deliverables)) {
                                // 文字列なら改行/カンマ区切りをざっくり配列化
                                $tmp = preg_split("/\r\n|\r|\n|、|,/", (string)$deliverables);
                                $deliverables = array_values(array_filter(array_map('trim', $tmp)));
                            }
                        @endphp

                        <div class="mt-3 space-y-1">
                            @foreach($deliverables as $d)
                                <div class="check-item">
                                    <div class="mt-0.5">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" stroke="#22c55e" stroke-width="2"/>
                                            <path d="M8 12l2.5 2.5L16 9" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div class="font-bold text-gray-700">{{ $d }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- 秘密保持 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        {{-- shield --}}
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2l8 4v6c0 5-3.5 9.5-8 10-4.5-.5-8-5-8-10V6l8-4z" stroke="#111827" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">秘密保持</h2>
                        <div class="mt-3">
                            <div class="kv">
                                <div class="k">保持期間</div>
                                <div class="v">{{ $t['confidentiality_period'] ?? '' }}</div>
                            </div>
                            <div class="kv">
                                <div class="k">対象範囲</div>
                                <div class="subv">{{ $t['confidentiality_scope'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- その他条項 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        {{-- paper --}}
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#111827" stroke-width="2"/>
                            <path d="M14 2v6h6" stroke="#111827" stroke-width="2"/>
                            <path d="M8 13h8M8 17h8M8 9h3" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">その他条項</h2>
                        <div class="mt-3 text-gray-700 font-bold leading-relaxed">
                            {{ $t['special_terms'] ?? '' }}
                            @if(!empty($t['free_text']))
                                <div class="mt-3 text-gray-600 font-bold whitespace-pre-wrap">{{ $t['free_text'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- 右（サイド） --}}
        <aside class="space-y-5">
            {{-- 当事者 --}}
            <section class="card p-5">
                <h3 class="text-lg font-black">当事者</h3>

                <div class="mt-4 space-y-4">
                    <div class="flex gap-3">
                        <div class="mt-0.5">
                            {{-- building --}}
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M3 21h18" stroke="#9ca3af" stroke-width="2" stroke-linecap="round"/>
                                <path d="M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17" stroke="#9ca3af" stroke-width="2"/>
                                <path d="M9 7h1M9 10h1M9 13h1M14 7h1M14 10h1M14 13h1" stroke="#9ca3af" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-black text-gray-500">企業</div>
                            <div class="mt-1 font-black text-gray-900 break-words">{{ $corpName }}</div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <div class="flex gap-3">
                        <div class="mt-0.5">
                            {{-- user --}}
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M20 21a8 8 0 0 0-16 0" stroke="#9ca3af" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="7" r="4" stroke="#9ca3af" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-black text-gray-500">フリーランス</div>
                            <div class="mt-1 font-black text-gray-900 break-words">
                                {{ $t['freelancer_name'] ?? '山田太郎' }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ステータス --}}
            <section class="card p-5">
                <h3 class="text-lg font-black">ステータス</h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <div class="text-xs font-black text-gray-500">現在のステータス</div>
                        <div class="mt-2">
                            <span class="{{ $statusPillClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-black text-gray-500">作成日時</div>
                        <div class="mt-1 font-black text-gray-900">{{ $createdAt }}</div>
                    </div>
                </div>
            </section>

            {{-- 基本情報（画面の項目は維持） --}}
            <section class="card p-5">
                <h3 class="text-lg font-black">基本情報</h3>
                <div class="mt-3">
                    <div class="kv">
                        <div class="k">案件</div>
                        <div class="subv">{{ $jobTitle }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">開始日</div>
                        <div class="subv">{{ $contract->start_date ? $contract->start_date->format('Y-m-d') : '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">終了日</div>
                        <div class="subv">{{ $contract->end_date ? $contract->end_date->format('Y-m-d') : '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">提示日時</div>
                        <div class="subv">{{ $contract->proposed_at ? $contract->proposed_at->format('Y-m-d H:i') : '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">締結日時</div>
                        <div class="subv">{{ $contract->signed_at ? $contract->signed_at->format('Y-m-d H:i') : '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">文面ハッシュ</div>
                        <div class="subv break-all">{{ $contract->document_hash ?? '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">PDFハッシュ</div>
                        <div class="subv break-all">{{ $contract->pdf_hash ?? '' }}</div>
                    </div>
                </div>
            </section>

            {{-- 署名状況（表示は維持。締結ボタンは出さない） --}}
            <section class="card p-5">
                <h3 class="text-lg font-black">署名状況</h3>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="pill gray">署名数: {{ $contract->signatures->count() }}/2</span>
                    <span class="pill gray">企業: {{ $contract->signatures->where('signer_type','company')->count() ? '済' : '未' }}</span>
                    <span class="pill gray">法人: {{ $contract->signatures->where('signer_type','corporate')->count() ? '済' : '未' }}</span>
                </div>
            </section>
        </aside>
    </div>

    {{-- 差し戻し履歴（元の項目を維持） --}}
    @if($contract->changeRequests->count() > 0)
        <section class="mt-5 card p-5">
            <h3 class="text-lg font-black">差し戻し履歴</h3>
            <div class="mt-3 space-y-3">
                @foreach($contract->changeRequests as $cr)
                    <div class="border-t border-gray-100 pt-3">
                        <div class="text-xs font-black text-gray-500">
                            {{ $cr->created_at ? $cr->created_at->format('Y-m-d H:i') : '' }}
                        </div>
                        <div class="mt-2 font-bold text-gray-800 whitespace-pre-wrap">{{ $cr->body }}</div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 監査ログ（元の項目を維持） --}}
    <section class="mt-5 card p-5">
        <h3 class="text-lg font-black">監査ログ</h3>
        <div class="mt-3 space-y-2">
            @forelse($contract->auditLogs as $log)
                <div class="border-t border-gray-100 pt-3 text-sm font-bold text-gray-700">
                    {{ $log->occurred_at ? $log->occurred_at->format('Y-m-d H:i') : '' }}
                    <span class="text-gray-300">/</span> {{ $log->action }}
                    <span class="text-gray-300">/</span> {{ $log->actor_type }}
                </div>
            @empty
                <div class="text-sm font-bold text-gray-500">ログはありません</div>
            @endforelse
        </div>
    </section>

    {{-- ✅ 1回コピー用（画面には出さない） --}}
    <textarea id="copyAllText" class="sr-only" aria-hidden="true">{{ $copyAllText }}</textarea>

    <div id="toast" class="toast">コピーしました</div>
</main>

<script>
(function(){
    const btn = document.getElementById('copyAllBtn');
    const ta  = document.getElementById('copyAllText');
    const toast = document.getElementById('toast');

    function showToast(message){
        toast.textContent = message;
        toast.classList.add('show');
        clearTimeout(showToast._t);
        showToast._t = setTimeout(()=> toast.classList.remove('show'), 1600);
    }

    async function copyText(text){
        // navigator.clipboard が使えないケースもあるのでフォールバック付き
        try{
            await navigator.clipboard.writeText(text);
            return true;
        }catch(e){
            try{
                ta.classList.remove('sr-only');
                ta.value = text;
                ta.select();
                ta.setSelectionRange(0, ta.value.length);
                const ok = document.execCommand('copy');
                ta.classList.add('sr-only');
                return ok;
            }catch(_e){
                return false;
            }
        }
    }

    if(btn){
        btn.addEventListener('click', async ()=>{
            const text = (ta && ta.value) ? ta.value : (ta ? ta.textContent : '');
            const ok = await copyText(text);
            showToast(ok ? '全てコピーしました' : 'コピーに失敗しました');
        });
    }
})();
</script>

</body>
</html>