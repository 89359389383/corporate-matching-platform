<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募された案件（企業）- AITECH</title>
    @include('partials.company-header-style')
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

        /* Main */
        .main-content { max-width: 1000px; margin: 0 auto; padding: 2.25rem 3rem 3rem; background-color: #fafbfc; }
        .page-head {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }
        .page-title {
            font-size: 2.6rem;
            font-weight: 900;
            color: #24292e;
            letter-spacing: -0.025em;
        }
        .total-unread {
            background: #d73a49;
            color: #fff;
            border-radius: 999px;
            padding: 0.6rem 1.15rem;
            font-size: 1.17rem;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            line-height: 1;
            box-shadow: 0 2px 10px rgba(215,58,73,0.18);
            white-space: nowrap;
            cursor: default;
        }

        /* View Tabs + Filter (案件一覧 / 法人一覧 / 未読のみ) */
        .view-tabs {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin: 0.75rem 0 1.25rem;
            flex-wrap: wrap;
        }
        .tab-group { display: flex; gap: 0.75rem; }
        .view-tab {
            padding: 0.6rem 1.15rem;
            border-radius: 999px;
            font-size: 1.17rem;
            font-weight: 900;
            cursor: pointer;
            background: #e1e4e8;
            color: #24292e;
            border: none;
        }
        .view-tab.active {
            background: #0366d6;
            color: #fff;
            box-shadow: 0 2px 10px rgba(3, 102, 214, 0.25);
        }
        .filter-btn {
            font-size: 1.1rem;
            font-weight: 900;
            padding: 0.55rem 1rem;
            border-radius: 999px;
            border: 1px solid #d1d5da;
            background: #fff;
            cursor: pointer;
            white-space: nowrap;
        }
        .filter-btn.active { background: #24292e; color: #fff; border-color: #24292e; }

        /* Layout (案件一覧 + 応募者一覧) */
        .layout { display: none; gap: 1.5rem; align-items: flex-start; }
        .layout.active { display: flex; }
        .jobs { width: 70%; min-width: 0; }
        .applicants {
            width: 30%;
            background: #fff;
            border: 1px solid #e1e4e8;
            border-radius: 14px;
            padding: 1.25rem;
            position: sticky;
            top: calc(var(--header-height-current) + 64px);
        }
        .job-applicants-placeholder { display: none; }

        /* 1024px以下: 応募者（紐付フリーランス）を「クリックした案件カード直下」に表示する */
        @media (max-width: 1024px) {
            #applicants { display: none; } /* 旧：ページ下部に出る領域は隠す（中身はJSで移動） */
            .jobs { width: 100%; }
            .job-applicants {
                background: #fff;
                border: 1px solid #e1e4e8;
                border-radius: 14px;
                padding: 1.25rem;
                margin: 0 0 1rem;
            }
        }
        .applicants-title {
            font-size: 1rem;
            font-weight: 900;
            margin-bottom: 0.75rem;
            color: #24292e;
            letter-spacing: -0.01em;
        }

        /* Job card (案件カード) */
        .job-card {
            background-color: white;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            transition: all 0.2s ease;
            border: 1px solid #e1e4e8;
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
            cursor: pointer;
        }
        .job-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        .job-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08); }
        .job-title {
            font-size: 1.56rem;
            font-weight: 900;
            color: #0060ff;
            line-height: 1.35;
            margin-bottom: 0.25rem;
        }
        .job-sub {
            color: #586069;
            font-weight: 700;
            font-size: 1.24rem;
            margin-bottom: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .job-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            color: #24292e;
            font-weight: 800;
            font-size: 1.11rem;
        }
        .job-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.75rem;
            gap: 0.75rem;
        }

        /* Badges (未読など) */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            background: #d73a49;
            color: #fff;
            border-radius: 999px;
            padding: 0.28rem 0.65rem;
            font-size: 1.01rem;
            font-weight: 900;
            white-space: nowrap;
        }
        .badge-pill.gray { background: #6a737d; }

        /* Freelancer row (応募者行) */
        .freelancer-row {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0;
            border: none;
        }
        .freelancer-link {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .freelancer-link:hover {
            text-decoration: none;
            color: inherit;
        }
        .freelancer-body { flex: 1; min-width: 0; }
        .freelancer-name {
            font-weight: 900;
            font-size: 1.24rem;
            color: #24292e;
            display: flex;
            gap: 0.5rem;
            align-items: baseline;
            flex-wrap: wrap;
        }
        .job-label {
            font-weight: 900;
            font-size: 1.04rem;
            color: #6a737d;
            white-space: nowrap;
        }
        .message {
            font-size: 1.14rem;
            color: #24292e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        .time {
            font-size: 1.01rem;
            color: #6a737d;
            margin-top: 0.15rem;
            white-space: nowrap;
        }
        .freelancer-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .chat-btn {
            background: #0366d6;
            color: #fff;
            padding: 0.28rem 0.65rem;
            border-radius: 10px;
            font-size: 1.04rem;
            font-weight: 900;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            min-width: 78px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .chat-btn.is-disabled { opacity: 0.55; cursor: not-allowed; pointer-events: none; }
        .application-info-btn {
            background: #fff;
            color: #0366d6;
            padding: 0.28rem 0.65rem;
            border-radius: 10px;
            font-size: 1.04rem;
            font-weight: 900;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            min-width: 90px;
            text-decoration: none;
            border: 1px solid #b6d4fe;
            cursor: pointer;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }
        .application-info-btn:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #1d4ed8;
        }
        .application-info-btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.15);
        }
        .application-info-template { display: none; }

        .application-modal {
            position: fixed;
            inset: 0;
            z-index: 1200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .application-modal.is-open { display: flex; }
        .application-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.56);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        .application-modal-dialog {
            position: relative;
            width: min(760px, 100%);
            max-height: min(88vh, 900px);
            overflow: auto;
            border-radius: 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 24px 64px rgba(15, 23, 42, 0.25);
            padding: 1rem;
        }
        .application-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.25rem 0 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }
        .application-modal-title {
            font-size: 1.2rem;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.01em;
        }
        .application-modal-subtitle {
            margin-top: 0.25rem;
            font-size: 0.94rem;
            color: #6b7280;
            font-weight: 700;
        }
        .application-modal-close {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            font-weight: 700;
            cursor: pointer;
            flex: 0 0 auto;
        }
        .application-modal-close:hover { background: #f1f5f9; }
        .application-modal-body { display: grid; gap: 0.75rem; }
        .application-info-grid {
            display: grid;
            grid-template-columns: minmax(130px, 170px) 1fr;
            gap: 0.5rem 0.85rem;
            align-items: start;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.85rem;
            background: #fbfdff;
        }
        .application-info-label {
            color: #475569;
            font-size: 0.9rem;
            font-weight: 900;
        }
        .application-info-value {
            color: #0f172a;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.6;
            word-break: break-word;
            white-space: pre-wrap;
        }
        @media (max-width: 640px) {
            .application-modal { padding: 0.6rem; }
            .application-modal-dialog { padding: 0.8rem; max-height: 92vh; border-radius: 14px; }
            .application-modal-title { font-size: 1.06rem; }
            .application-modal-subtitle { font-size: 0.84rem; }
            .application-info-grid { grid-template-columns: 1fr; gap: 0.2rem; padding: 0.75rem; }
            .application-info-label { font-size: 0.82rem; }
            .application-info-value { font-size: 0.9rem; margin-bottom: 0.35rem; }
        }

        /* Status pill / select (既存更新機能を維持しつつ小さく表示) */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.55rem;
            border-radius: 999px;
            font-weight: 900;
            font-size: 1.01rem;
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            color: #24292e;
            white-space: nowrap;
        }
        .status-select {
            background: transparent;
            border: none;
            font-weight: 900;
            color: inherit;
            cursor: pointer;
        }

        /* Freelancer view */
        #freelancerView { display: none; }
        .empty-state { text-align: center; padding: 3rem 1rem; color: #586069; }
        .tabs {
            display: inline-flex;
            gap: 0.5rem;
            padding: 0.5rem;
            background: #fff;
            border: 1px solid #e1e4e8;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 1.75rem;
        }
        .tab {
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            color: #24292e;
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-block;
        }
        .tab:hover { background: #f6f8fa; transform: translateY(-1px); }
        .tab.is-active {
            background-color: #0366d6;
            border-color: #0366d6;
            color: #fff;
            box-shadow: 0 2px 8px rgba(3, 102, 214, 0.25);
        }

        .list { display: grid; gap: 1.5rem; }
        .card {
            background-color: white;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            transition: all 0.2s ease;
            border: 1px solid #e1e4e8;
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08); }
        .top {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        .title { font-size: 24px; font-weight: 700; color: #0060ff; margin-bottom: 0.5rem; line-height: 1.3; }
        .sub { color: #586069; font-weight: 500; font-size: 18px; }
        .row { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 900;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            font-weight: 900;
            font-size: 0.85rem;
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            color: #24292e;
            white-space: nowrap;
        }
        .pill.unread { background: #fff5f5; border-color: #ffccd2; color: #b31d28; }
        .pill.status { background: #f6f8fa; }
        .meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
            max-width: 500px;
        }
        .meta-item {
            padding: 0.85rem;
            background-color: #f6f8fa;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            align-items: center;
        }
        .meta-label { font-size: 16px; color: #6a737d; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; }
        .meta-value { font-weight: 900; color: #24292e; white-space: nowrap; font-size: 1.05rem; }
        .skills-section {
            margin-top: 1rem;
            padding: 0.85rem;
            background-color: #f6f8fa;
            border-radius: 10px;
        }
        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .skill-tag {
            background-color: #f1f8ff;
            color: #0366d6;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 600;
            border: 1px solid #c8e1ff;
        }
        .desc { color: #586069; margin-top: 0.75rem; line-height: 1.65; font-size: 1rem; }
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid #e1e4e8;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }
        .btn-primary { background-color: #0366d6; color: #fff; font-size: 20px; padding: 15px 60px; }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3); }
        .btn-secondary { background-color: #586069; color: #fff; font-size: 20px; padding: 15px 60px; }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }

    /* Centered tab bar styling to mimic freelancer view (inside style tag) */
    .tabs-bar {
        background-color: #ffffff;
        border-bottom: 1px solid #e1e4e8;
        padding: 0 var(--header-padding-x);
        position: sticky;
        top: var(--header-height-current);
        z-index: 99;
    }
    .tabs-container {
        max-width: 1600px;
        width: 100%;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        gap: 0;
    }
    .tab-link {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: #586069;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        border-bottom: 3px solid transparent;
        transition: all 0.15s ease;
        position: relative;
        letter-spacing: -0.01em;
    }
    .tab-link:hover { color: #24292e; background-color: #f6f8fa; }
    .tab-link.active {
        color: #0366d6;
        border-bottom-color: #0366d6;
        background-color: transparent;
    }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.company-header')
    <!-- Tabs Bar -->
    <div class="tabs-bar">
        <div class="tabs-container">
            <nav aria-label="応募一覧タブ">
                <a class="tab-link {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('company.applications.index', ['status' => 'pending']) }}" data-tab="active" id="tab-active">応募中</a>
                <a class="tab-link {{ $status === 'closed' ? 'active' : '' }}" href="{{ route('company.applications.index', ['status' => 'closed']) }}" data-tab="closed" id="tab-closed">終了</a>
            </nav>
        </div>
    </div>
    <!-- Main Content -->
    <main class="main-content max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            @php
                $applicationItems = $applications instanceof \Illuminate\Pagination\AbstractPaginator
                    ? $applications->getCollection()
                    : collect($applications);

                $totalUnreadOnPage = $applicationItems->filter(fn ($a) => (bool)($a->is_unread ?? false))->count();

                $jobGroups = $applicationItems
                    ->groupBy(function ($application) {
                        return optional($application->job)->id ?? ('unknown-' . $application->id);
                    })
                    ->map(function ($group, $jobId) {
                        $first = $group->first();
                        $job = $first->job ?? null;
                        $company = $job->company ?? null;
                        $unreadCount = $group->filter(fn ($a) => (bool)($a->is_unread ?? false))->count();
                        $lastAtTs = $group->max(function ($a) {
                            $dt = $a->created_at ?? $a->updated_at ?? null;
                            return $dt ? $dt->getTimestamp() : 0;
                        });

                        return (object)[
                            'key' => 'job_' . $jobId,
                            'job' => $job,
                            'company' => $company,
                            'applications' => $group,
                            'unreadCount' => $unreadCount,
                            'lastAtTs' => $lastAtTs,
                        ];
                    })
                    ->sortByDesc('lastAtTs')
                    ->values();

                $freelancerRows = $applicationItems
                    ->sortByDesc(function ($a) {
                        $dt = $a->created_at ?? $a->updated_at ?? null;
                        return $dt ? $dt->getTimestamp() : 0;
                    })
                    ->values();
            @endphp

            <div class="view-tabs flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4" aria-label="表示切替">
                <span class="total-unread" id="totalUnread" data-total-unread="{{ $totalUnreadOnPage }}">未読 {{ $totalUnreadOnPage }}</span>
                <div class="tab-group flex flex-wrap gap-2 md:gap-3" role="tablist" aria-label="表示タブ">
                    <button type="button" class="view-tab active" id="tabJobs" data-view-tab="jobs" aria-selected="true">案件一覧</button>
                    <button type="button" class="view-tab" id="tabFreelancers" data-view-tab="freelancers" aria-selected="false">法人一覧</button>
                </div>
                <button type="button" class="filter-btn w-full md:w-auto" id="filterBtn" aria-pressed="false">未読のみ</button>
            </div>

            @if($jobGroups->count() === 0)
                <div class="empty-state rounded-2xl bg-white border border-slate-200 shadow-sm p-8 md:p-10 text-center text-slate-600">
                    <p class="text-base md:text-lg font-black">応募がありません</p>
                </div>
            @else
                <div class="layout active flex flex-col lg:flex-row gap-5 lg:gap-6" id="jobView">
                    <section class="jobs w-full lg:w-2/3" id="jobs" aria-label="案件一覧">
                        @foreach($jobGroups as $group)
                            @php
                                $job = $group->job;
                                $company = $group->company;

                                $rewardText = '';
                                if ($job && $job->reward_type === 'monthly') {
                                    $rewardText = number_format($job->min_rate / 10000, 0) . '〜' . number_format($job->max_rate / 10000, 0) . '万';
                                } elseif ($job) {
                                    $rewardText = number_format($job->min_rate) . '〜' . number_format($job->max_rate) . '円/時';
                                }
                                $workTimeText = $job->work_time_text ?? '';
                            @endphp

                            <div class="job-card rounded-2xl bg-white border border-slate-200 shadow-sm p-5 md:p-6" data-job-key="{{ $group->key }}" data-unread-count="{{ $group->unreadCount }}" role="button" tabindex="0">
                                <div class="job-title">{{ $job->title ?? '案件名不明' }}</div>
                                <div class="job-sub">{{ $company->name ?? '企業名不明' }}</div>
                                <div class="job-meta">
                                    @if($rewardText)<span>報酬：{{ $rewardText }}</span>@endif
                                    @if($workTimeText)<span>稼働：{{ $workTimeText }}</span>@endif
                                </div>
                                <div class="job-footer flex flex-col md:flex-row md:items-center md:justify-between gap-2 md:gap-3">
                                    <span style="font-weight:900;">応募者 {{ $group->applications->count() }}名</span>
                                    <span class="badge-pill {{ $group->unreadCount === 0 ? 'gray' : '' }}">未読 {{ $group->unreadCount }}</span>
                                </div>
                            </div>
                        @endforeach
                    </section>

                    <aside class="applicants w-full lg:w-1/3 lg:sticky lg:top-[calc(var(--header-height-current)+64px)]" id="applicants" aria-label="応募者一覧">
                        @foreach($jobGroups as $group)
                            @php $job = $group->job; @endphp
                            <div class="job-applicants-placeholder" data-job-key="{{ $group->key }}" aria-hidden="true"></div>
                            <div class="job-applicants" data-job-key="{{ $group->key }}" style="display:none;">
                                <div class="applicants-title">{{ $job->title ?? '案件名不明' }}</div>
                                @foreach($group->applications as $application)
                                    @php
                                        $corporate = $application->corporate;
                                        $corporateInitial = mb_substr($corporate->display_name ?? '未', 0, 1);
                                        $dt = $application->created_at ?? $application->updated_at ?? null;
                                        $timeText = $dt ? $dt->format('Y/m/d H:i') : '';
                                        $workDays = $application->work_days;
                                        if (is_string($workDays)) {
                                            $decoded = json_decode($workDays, true);
                                            $workDays = is_array($decoded) ? $decoded : [$workDays];
                                        } elseif (!is_array($workDays)) {
                                            $workDays = [];
                                        }
                                        $workDaysText = count($workDays) ? implode(' / ', $workDays) : '-';
                                        $weeklyHoursMap = [
                                            5 => '週5時間程度（20時間/月）',
                                            10 => '週10時間程度（40時間/月）',
                                            20 => '週20時間程度（80時間/月）',
                                            30 => '週30時間程度（120時間/月）',
                                            40 => '週40時間程度（160時間/月）',
                                        ];
                                        $weeklyHoursText = $application->weekly_hours !== null
                                            ? ($weeklyHoursMap[(int)$application->weekly_hours] ?? ('週' . (int)$application->weekly_hours . '時間'))
                                            : '-';
                                        $applicationInfoTarget = 'application-info-job-' . $application->id;

                                        $statusText = '';
                                        if ($application->status === \App\Models\Application::STATUS_PENDING) {
                                            $statusText = '未対応';
                                        } elseif ($application->status === \App\Models\Application::STATUS_IN_PROGRESS) {
                                            $statusText = '対応中';
                                        } else {
                                            $statusText = 'クローズ';
                                        }

                                        $chatUrl = $application->thread
                                            ? route('company.threads.show', ['thread' => $application->thread])
                                            : null;
                                    @endphp

                                    @if($chatUrl)
                                        <a href="{{ $chatUrl }}" class="freelancer-row freelancer-link" data-unread="{{ ($application->is_unread ?? false) ? '1' : '0' }}" data-job-key="{{ $group->key }}">
                                    @else
                                        <div class="freelancer-row" data-unread="{{ ($application->is_unread ?? false) ? '1' : '0' }}" data-job-key="{{ $group->key }}">
                                    @endif
                                        <div class="avatar" aria-hidden="true">{{ $corporateInitial }}</div>
                                        <div class="freelancer-body">
                                            <div class="freelancer-name">
                                                <span>{{ $corporate->display_name ?? '名前不明' }}</span>

                                                @if($status === 'pending')
                                                    @if($application->status === \App\Models\Application::STATUS_PENDING)
                                                        <form method="POST" action="{{ route('company.applications.update', ['application' => $application->id]) }}" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <label class="status-pill">
                                                                応募：
                                                                <select name="status" class="status-select" onchange="this.form.submit()">
                                                                    <option value="{{ \App\Models\Application::STATUS_PENDING }}" selected>未対応</option>
                                                                    <option value="{{ \App\Models\Application::STATUS_IN_PROGRESS }}">対応中</option>
                                                                    <option value="{{ \App\Models\Application::STATUS_CLOSED }}">クローズ</option>
                                                                </select>
                                                            </label>
                                                        </form>
                                                    @else
                                                        <span class="status-pill">応募：{{ $statusText }}</span>
                                                    @endif
                                                @else
                                                    <span class="status-pill">応募：{{ $statusText }}</span>
                                                @endif
                                            </div>
                                            <div class="message">{{ Str::limit($application->message ?? ($job->description ?? ''), 38) }}</div>
                                            <div class="time">{{ $timeText }}</div>
                                            <div class="freelancer-actions flex flex-wrap items-center gap-2" aria-label="操作">
                                                @if($application->is_unread ?? false)
                                                    <span class="badge-pill">未読</span>
                                                @endif
                                                <span class="chat-btn {{ $chatUrl ? '' : 'is-disabled' }}">チャット</span>
                                                <button type="button" class="application-info-btn" data-application-info-target="{{ $applicationInfoTarget }}">応募情報</button>
                                            </div>
                                        </div>
                                    @if($chatUrl)
                                        </a>
                                    @else
                                        </div>
                                    @endif
                                    <div class="application-info-template" id="{{ $applicationInfoTarget }}" aria-hidden="true">
                                        <div class="application-info-grid">
                                            <div class="application-info-label">法人名</div>
                                            <div class="application-info-value">{{ $corporate->display_name ?? '名前不明' }}</div>
                                            <div class="application-info-label">希望時間単価</div>
                                            <div class="application-info-value">{{ $application->desired_hourly_rate !== null ? number_format((int)$application->desired_hourly_rate) . '円/時間' : '-' }}</div>
                                            <div class="application-info-label">稼働曜日（目安）</div>
                                            <div class="application-info-value">{{ $workDaysText }}</div>
                                            <div class="application-info-label">稼働時間帯（目安）</div>
                                            <div class="application-info-value">{{ ($application->work_time_from ?: '-') . ' 〜 ' . ($application->work_time_to ?: '-') }}</div>
                                            <div class="application-info-label">合計週稼働時間</div>
                                            <div class="application-info-value">{{ $weeklyHoursText }}</div>
                                            <div class="application-info-label">開始可能日</div>
                                            <div class="application-info-value">{{ $application->available_start ?: '-' }}</div>
                                            <div class="application-info-label">備考</div>
                                            <div class="application-info-value">{{ $application->note ?: '-' }}</div>
                                            <div class="application-info-label">応募メッセージ</div>
                                            <div class="application-info-value">{{ $application->message ?: '-' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </aside>
                </div>

                <div id="freelancerView" aria-label="法人一覧">
                    <div class="list grid grid-cols-1 gap-5 lg:gap-6">
                        @foreach($freelancerRows as $application)
                            @php
                                $job = $application->job;
                                $corporate = $application->corporate;
                                $jobGroupKey = 'job_' . (optional($job)->id ?? ('unknown-' . $application->id));

                                $corporateInitial = mb_substr($corporate->display_name ?? '未', 0, 1);
                                $dt = $application->created_at ?? $application->updated_at ?? null;
                                $timeText = $dt ? $dt->format('Y/m/d H:i') : '';
                                $workDays = $application->work_days;
                                if (is_string($workDays)) {
                                    $decoded = json_decode($workDays, true);
                                    $workDays = is_array($decoded) ? $decoded : [$workDays];
                                } elseif (!is_array($workDays)) {
                                    $workDays = [];
                                }
                                $workDaysText = count($workDays) ? implode(' / ', $workDays) : '-';
                                $weeklyHoursMap = [
                                    5 => '週5時間程度（20時間/月）',
                                    10 => '週10時間程度（40時間/月）',
                                    20 => '週20時間程度（80時間/月）',
                                    30 => '週30時間程度（120時間/月）',
                                    40 => '週40時間程度（160時間/月）',
                                ];
                                $weeklyHoursText = $application->weekly_hours !== null
                                    ? ($weeklyHoursMap[(int)$application->weekly_hours] ?? ('週' . (int)$application->weekly_hours . '時間'))
                                    : '-';
                                $applicationInfoTarget = 'application-info-freelancer-' . $application->id;
                                $chatUrl = $application->thread
                                    ? route('company.threads.show', ['thread' => $application->thread])
                                    : null;
                            @endphp
                            <div class="card rounded-2xl bg-white border border-slate-200 shadow-sm p-5 md:p-6 relative overflow-hidden">
                                @if($chatUrl)
                                    <a href="{{ $chatUrl }}" class="freelancer-row freelancer-link" data-unread="{{ ($application->is_unread ?? false) ? '1' : '0' }}" data-job-key="{{ $jobGroupKey }}">
                                @else
                                    <div class="freelancer-row" data-unread="{{ ($application->is_unread ?? false) ? '1' : '0' }}" data-job-key="{{ $jobGroupKey }}">
                                @endif
                                    <div class="avatar" aria-hidden="true">{{ $corporateInitial }}</div>
                                    <div class="freelancer-body">
                                        <div class="freelancer-name">
                                            <span>{{ $corporate->display_name ?? '名前不明' }}</span>
                                            <span class="job-label">案件：{{ $job->title ?? '案件名不明' }}</span>
                                        </div>
                                        <div class="message">{{ Str::limit($application->message ?? ($job->description ?? ''), 44) }}</div>
                                        <div class="time">{{ $timeText }}</div>
                                        <div class="freelancer-actions" aria-label="操作">
                                            @if($application->is_unread ?? false)
                                                <span class="badge-pill">未読</span>
                                            @endif
                                            <span class="chat-btn {{ $chatUrl ? '' : 'is-disabled' }}">チャット</span>
                                            <button type="button" class="application-info-btn" data-application-info-target="{{ $applicationInfoTarget }}">応募情報</button>
                                        </div>
                                    </div>
                                @if($chatUrl)
                                    </a>
                                @else
                                    </div>
                                @endif
                                <div class="application-info-template" id="{{ $applicationInfoTarget }}" aria-hidden="true">
                                    <div class="application-info-grid">
                                        <div class="application-info-label">法人名</div>
                                        <div class="application-info-value">{{ $corporate->display_name ?? '名前不明' }}</div>
                                        <div class="application-info-label">希望時間単価</div>
                                        <div class="application-info-value">{{ $application->desired_hourly_rate !== null ? number_format((int)$application->desired_hourly_rate) . '円/時間' : '-' }}</div>
                                        <div class="application-info-label">稼働曜日（目安）</div>
                                        <div class="application-info-value">{{ $workDaysText }}</div>
                                        <div class="application-info-label">稼働時間帯（目安）</div>
                                        <div class="application-info-value">{{ ($application->work_time_from ?: '-') . ' 〜 ' . ($application->work_time_to ?: '-') }}</div>
                                        <div class="application-info-label">合計週稼働時間</div>
                                        <div class="application-info-value">{{ $weeklyHoursText }}</div>
                                        <div class="application-info-label">開始可能日</div>
                                        <div class="application-info-value">{{ $application->available_start ?: '-' }}</div>
                                        <div class="application-info-label">備考</div>
                                        <div class="application-info-value">{{ $application->note ?: '-' }}</div>
                                        <div class="application-info-label">応募メッセージ</div>
                                        <div class="application-info-value">{{ $application->message ?: '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($applications->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </main>

    <div class="application-modal" id="applicationInfoModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="applicationInfoModalTitle">
        <div class="application-modal-backdrop" data-application-modal-close></div>
        <div class="application-modal-dialog">
            <div class="application-modal-header">
                <div>
                    <h2 class="application-modal-title" id="applicationInfoModalTitle">応募情報</h2>
                    <p class="application-modal-subtitle">応募時に入力された内容を表示しています</p>
                </div>
                <button type="button" class="application-modal-close" data-application-modal-close aria-label="モーダルを閉じる">×</button>
            </div>
            <div class="application-modal-body" id="applicationInfoModalBody"></div>
        </div>
    </div>

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
            const tabJobs = document.getElementById('tabJobs');
            const tabFreelancers = document.getElementById('tabFreelancers');
            const jobView = document.getElementById('jobView');
            const freelancerView = document.getElementById('freelancerView');
            const filterBtn = document.getElementById('filterBtn');
            const totalUnreadEl = document.getElementById('totalUnread');

            // 応募が無いケースなど、要素が無ければ何もしない
            if (!tabJobs || !tabFreelancers || !jobView || !freelancerView || !filterBtn) return;

            let unreadOnly = false;
            let currentJobKey = null;

            const qsa = (sel, root = document) => Array.from(root.querySelectorAll(sel));
            const jobCards = () => qsa('.job-card');
            const jobApplicantsPanels = () => qsa('.job-applicants');
            const jobApplicantsPlaceholders = () => qsa('.job-applicants-placeholder');
            const allFreelancerRows = () => qsa('.freelancer-row');
            const applicantsAside = document.getElementById('applicants');

            const isNarrowLayout = () => window.matchMedia('(max-width: 1024px)').matches;

            function findJobCard(jobKey) {
                return jobCards().find(c => c.dataset.jobKey === jobKey) || null;
            }

            function restorePanelToAside(panel) {
                if (!panel || !panel.dataset || !panel.dataset.jobKey) return;
                const ph = jobApplicantsPlaceholders().find(p => p.dataset.jobKey === panel.dataset.jobKey) || null;
                if (ph && ph.parentElement) {
                    ph.insertAdjacentElement('afterend', panel);
                } else if (applicantsAside) {
                    applicantsAside.appendChild(panel);
                }
            }

            function ensurePanelsInAside() {
                if (!applicantsAside) return;
                jobApplicantsPanels().forEach(p => {
                    // 既に aside 配下ならそのまま
                    if (applicantsAside.contains(p)) return;
                    restorePanelToAside(p);
                });
            }

            function placeApplicantsPanel(jobKey, selectedCardEl = null) {
                // 1200以下…ではなく、この画面は 1024 以下が対象
                if (!jobKey) return;
                const panel = jobApplicantsPanels().find(p => p.dataset.jobKey === jobKey) || null;
                if (!panel) return;

                if (!isNarrowLayout()) {
                    ensurePanelsInAside();
                    return;
                }

                const card = selectedCardEl || findJobCard(jobKey);
                if (!card) return;
                card.insertAdjacentElement('afterend', panel);
            }

            function setActiveJob(jobKey, selectedCardEl = null) {
                currentJobKey = jobKey;
                jobCards().forEach(c => c.classList.toggle('active', c.dataset.jobKey === jobKey));
                jobApplicantsPanels().forEach(p => {
                    p.style.display = (p.dataset.jobKey === jobKey ? '' : 'none');
                });
                placeApplicantsPanel(jobKey, selectedCardEl);
                applyUnreadFilter();
            }

            function firstVisibleJobKey() {
                const visible = jobCards().find(c => c.style.display !== 'none');
                return visible ? visible.dataset.jobKey : null;
            }

            function applyUnreadFilter() {
                // 案件カード
                jobCards().forEach(card => {
                    const unreadCount = Number(card.dataset.unreadCount || 0);
                    const hide = unreadOnly && unreadCount === 0;
                    card.style.display = hide ? 'none' : '';
                });

                // アクティブ案件が隠れたら、先頭の表示案件を選択
                if (currentJobKey) {
                    const activeCard = jobCards().find(c => c.dataset.jobKey === currentJobKey);
                    if (!activeCard || activeCard.style.display === 'none') {
                        const nextKey = firstVisibleJobKey();
                        if (nextKey) setActiveJob(nextKey);
                    }
                }

                // 応募者行（両ビュー共通）
                allFreelancerRows().forEach(row => {
                    const isUnread = row.dataset.unread === '1';
                    row.style.display = (unreadOnly && !isUnread) ? 'none' : '';
                });

                // 右側（現在の案件）の0件表示
                if (jobView.classList.contains('active') && currentJobKey) {
                    const panel = jobApplicantsPanels().find(p => p.dataset.jobKey === currentJobKey);
                    if (panel) {
                        const rows = qsa('.freelancer-row', panel).filter(r => r.style.display !== 'none');
                        const existingEmpty = panel.querySelector('[data-empty="1"]');
                        if (existingEmpty) existingEmpty.remove();
                        if (rows.length === 0) {
                            const empty = document.createElement('div');
                            empty.className = 'empty-state';
                            empty.dataset.empty = '1';
                            empty.style.padding = '1.5rem 0.5rem';
                            empty.innerHTML = '<p style="font-weight:900;">該当する応募がありません</p>';
                            panel.appendChild(empty);
                        }
                    }
                }

                // 1024px以下では、アクティブ案件のパネルがカード直下に居ることを保証
                if (currentJobKey) {
                    placeApplicantsPanel(currentJobKey);
                }

                // 上部の総未読（ページ内）表示は固定値（サーバー計算）でOK
                if (totalUnreadEl) {
                    const total = totalUnreadEl.dataset.totalUnread || '0';
                    totalUnreadEl.textContent = `未読 ${total}`;
                }
            }

            function showJobs() {
                tabJobs.classList.add('active');
                tabFreelancers.classList.remove('active');
                tabJobs.setAttribute('aria-selected', 'true');
                tabFreelancers.setAttribute('aria-selected', 'false');
                // 表示を案件レイアウトに切り替え（jobView を表示、freelancerView を非表示）
                jobView.classList.add('active');
                jobView.style.display = ''; // 元のCSS/Tailwindに任せる
                freelancerView.style.display = 'none';
                applyUnreadFilter();
            }

            function showFreelancers() {
                tabFreelancers.classList.add('active');
                tabJobs.classList.remove('active');
                tabFreelancers.setAttribute('aria-selected', 'true');
                tabJobs.setAttribute('aria-selected', 'false');
                // 法人一覧のみを表示（案件一覧・応募者パネルは非表示）
                jobView.classList.remove('active');
                jobView.style.display = 'none';
                freelancerView.style.display = 'block';
                applyUnreadFilter();
            }

            tabJobs.addEventListener('click', showJobs);
            tabFreelancers.addEventListener('click', showFreelancers);

            filterBtn.addEventListener('click', () => {
                unreadOnly = !unreadOnly;
                filterBtn.classList.toggle('active', unreadOnly);
                filterBtn.setAttribute('aria-pressed', unreadOnly ? 'true' : 'false');
                applyUnreadFilter();
            });

            // 案件選択（クリック + Enter/Space）
            jobCards().forEach(card => {
                const key = card.dataset.jobKey;
                const onSelect = () => setActiveJob(key, card);
                card.addEventListener('click', onSelect);
                card.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        onSelect();
                    }
                });
            });

            // 初期：先頭案件を選択して案件一覧表示
            const initKey = firstVisibleJobKey();
            if (initKey) setActiveJob(initKey);
            showJobs();

            // リサイズで 1024px を跨いだら、応募者パネルの配置を戻す/差し込む
            let resizeTimer = null;
            window.addEventListener('resize', () => {
                window.clearTimeout(resizeTimer);
                resizeTimer = window.setTimeout(() => {
                    if (!currentJobKey) return;
                    if (!isNarrowLayout()) {
                        ensurePanelsInAside();
                    } else {
                        placeApplicantsPanel(currentJobKey);
                    }
                }, 80);
            });
        })();
    </script>
    <script>
        (function () {
            const modal = document.getElementById('applicationInfoModal');
            const body = document.getElementById('applicationInfoModalBody');
            if (!modal || !body) return;

            let previouslyFocused = null;

            const closeSelectors = '[data-application-modal-close]';

            const openModal = (targetId) => {
                const template = document.getElementById(targetId);
                if (!template) return;
                previouslyFocused = document.activeElement;
                body.innerHTML = template.innerHTML;
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                const closeBtn = modal.querySelector('.application-modal-close');
                if (closeBtn) closeBtn.focus();
            };

            const closeModal = () => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                body.innerHTML = '';
                document.body.style.overflow = '';
                if (previouslyFocused && typeof previouslyFocused.focus === 'function') {
                    previouslyFocused.focus();
                }
            };

            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-application-info-target]');
                if (trigger) {
                    event.preventDefault();
                    event.stopPropagation();
                    const targetId = trigger.getAttribute('data-application-info-target');
                    if (targetId) openModal(targetId);
                    return;
                }

                if (event.target.closest(closeSelectors)) {
                    event.preventDefault();
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>
