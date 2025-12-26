<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募された案件（企業）- AITECH</title>
    <style>
        :root { --header-height: 104px; --header-height-mobile: 91px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 130%; }
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: var(--header-height);
            position: relative;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
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
            font-size: 1.05rem;
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
            border-radius: 999px;
            padding: 0.15rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
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
            color: white; font-weight: 700; cursor: pointer; transition: all 0.15s ease;
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
            margin: 0.25rem;
            white-space: nowrap;
        }
        .dropdown-item:hover { background-color: #f6f8fa; color: #24292e; }
        .dropdown-divider { height: 1px; background-color: #e1e4e8; margin: 0.5rem 0; }

        /* Main */
        .main-content { max-width: 1600px; margin: 0 auto; padding: 3rem; }
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: #24292e;
            letter-spacing: -0.025em;
        }
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
            border-radius: 16px;
            padding: 1.75rem;
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
        .title { font-size: 1.35rem; font-weight: 900; line-height: 1.2; margin-bottom: 0.25rem; }
        .sub { color: #586069; font-weight: 700; }
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
        .meta-label { font-size: 0.75rem; color: #6a737d; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; }
        .meta-value { font-weight: 900; color: #24292e; white-space: nowrap; }
        .desc { color: #586069; margin-top: 0.75rem; line-height: 1.65; }
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
            font-weight: 800;
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
        .btn-primary { background-color: #0366d6; color: #fff; }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3); }
        .btn-secondary { background-color: #586069; color: #fff; }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }

        @media (max-width: 920px) {
            .header-content { height: var(--header-height-mobile); }
            .nav-links { position: static; left: auto; transform: none; justify-content: flex-start; }
            .user-menu { position: static; transform: none; margin-left: auto; }
            .main-content { padding: 1.5rem; }
            .meta { grid-template-columns: 1fr; }
            .actions .btn { width: 100%; }
        }
        @media (max-width: 1200px) {
            .nav-links { gap: 1rem; }
            .nav-link { font-size: 0.95rem; padding: 0.6rem 0.9rem; }
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
                <a href="{{ route('company.applications.index') }}" class="nav-link has-badge active">応募された案件 <span class="badge">3</span></a>
                <a href="{{ route('company.scouts.index') }}" class="nav-link has-badge">スカウト <span class="badge">1</span></a>
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
        <h1 class="page-title">応募された案件</h1>

        <div class="tabs" role="tablist" aria-label="応募タブ">
            <button class="tab is-active" type="button" role="tab" aria-selected="true" aria-controls="tab-open" id="tabOpenBtn">応募中</button>
            <button class="tab" type="button" role="tab" aria-selected="false" aria-controls="tab-closed" id="tabClosedBtn">終了</button>
        </div>

        <section id="tab-open" role="tabpanel" aria-labelledby="tabOpenBtn">
            <div class="list">
                <article class="card">
                    <div class="top">
                        <div style="min-width:0;">
                            <div class="title">ECサイト機能拡張プロジェクト</div>
                            <div class="sub">株式会社AITECH</div>
                            <div class="row" style="margin-top:0.75rem;">
                                <span class="pill status">応募ステータス：未対応</span>
                                <span class="pill unread">未読 2</span>
                            </div>
                        </div>
                        <div class="row" aria-label="応募者">
                            <span class="avatar" aria-hidden="true">山</span>
                            <span style="font-weight:900;">山田 太郎</span>
                        </div>
                    </div>
                    <div class="desc">既存ECの改善・機能追加。管理画面の刷新、商品管理の最適化を進めます。</div>
                    <div class="meta" aria-label="案件情報">
                        <div class="meta-item"><div class="meta-label">報酬</div><div class="meta-value">50〜70万</div></div>
                        <div class="meta-item"><div class="meta-label">稼働</div><div class="meta-value">週20〜30h</div></div>
                        <div class="meta-item"><div class="meta-label">期間</div><div class="meta-value">3ヶ月</div></div>
                        <div class="meta-item"><div class="meta-label">スキル</div><div class="meta-value">Laravel</div></div>
                    </div>
                    <div class="actions">
                        <a href="#" class="btn btn-secondary">詳細</a>
                        <a href="#" class="btn btn-primary">チャットへ</a>
                    </div>
                </article>

                <article class="card">
                    <div class="top">
                        <div style="min-width:0;">
                            <div class="title">API開発プロジェクト</div>
                            <div class="sub">API Solutions Ltd.</div>
                            <div class="row" style="margin-top:0.75rem;">
                                <span class="pill status">応募ステータス：対応中</span>
                                <span class="pill">未読なし</span>
                            </div>
                        </div>
                        <div class="row">
                            <span class="avatar" aria-hidden="true">佐</span>
                            <span style="font-weight:900;">佐藤 花子</span>
                        </div>
                    </div>
                    <div class="desc">RESTful API設計・開発。スケーラブルなアーキテクチャを想定。</div>
                    <div class="meta">
                        <div class="meta-item"><div class="meta-label">報酬</div><div class="meta-value">40〜60万</div></div>
                        <div class="meta-item"><div class="meta-label">稼働</div><div class="meta-value">週15〜25h</div></div>
                        <div class="meta-item"><div class="meta-label">期間</div><div class="meta-value">2ヶ月</div></div>
                        <div class="meta-item"><div class="meta-label">スキル</div><div class="meta-value">Node.js</div></div>
                    </div>
                    <div class="actions">
                        <a href="#" class="btn btn-secondary">詳細</a>
                        <a href="#" class="btn btn-primary">チャットへ</a>
                    </div>
                </article>
            </div>
        </section>

        <section id="tab-closed" role="tabpanel" aria-labelledby="tabClosedBtn" hidden>
            <div class="list">
                <article class="card">
                    <div class="top">
                        <div style="min-width:0;">
                            <div class="title">UI/UXデザイン改善</div>
                            <div class="sub">Design Studio</div>
                            <div class="row" style="margin-top:0.75rem;">
                                <span class="pill status">応募ステータス：クローズ</span>
                                <span class="pill">未読なし</span>
                            </div>
                        </div>
                        <div class="row">
                            <span class="avatar" aria-hidden="true">鈴</span>
                            <span style="font-weight:900;">鈴木 健</span>
                        </div>
                    </div>
                    <div class="desc">既存WebサービスのUI/UX改善。デザインシステム整備を含む。</div>
                    <div class="meta">
                        <div class="meta-item"><div class="meta-label">報酬</div><div class="meta-value">45〜65万</div></div>
                        <div class="meta-item"><div class="meta-label">稼働</div><div class="meta-value">週10〜20h</div></div>
                        <div class="meta-item"><div class="meta-label">期間</div><div class="meta-value">3ヶ月</div></div>
                        <div class="meta-item"><div class="meta-label">スキル</div><div class="meta-value">Figma</div></div>
                    </div>
                    <div class="actions">
                        <a href="#" class="btn btn-secondary">詳細</a>
                        <a href="#" class="btn btn-primary">チャット履歴</a>
                    </div>
                </article>
            </div>
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
            const openBtn = document.getElementById('tabOpenBtn');
            const closedBtn = document.getElementById('tabClosedBtn');
            const openPanel = document.getElementById('tab-open');
            const closedPanel = document.getElementById('tab-closed');
            if (!openBtn || !closedBtn || !openPanel || !closedPanel) return;

            const setTab = (which) => {
                const isOpen = which === 'open';
                openBtn.classList.toggle('is-active', isOpen);
                closedBtn.classList.toggle('is-active', !isOpen);
                openBtn.setAttribute('aria-selected', String(isOpen));
                closedBtn.setAttribute('aria-selected', String(!isOpen));
                openPanel.hidden = !isOpen;
                closedPanel.hidden = isOpen;
            };
            openBtn.addEventListener('click', () => setTab('open'));
            closedBtn.addEventListener('click', () => setTab('closed'));
        })();
    </script>
</body>
</html>
