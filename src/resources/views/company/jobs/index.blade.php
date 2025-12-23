<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自社案件一覧（企業）- AITECH</title>
    <style>
        :root { --header-height: 104px; --header-height-mobile: 91px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 100%; }
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

        /* Layout */
        .main-content {
            display: flex;
            max-width: 1600px;
            margin: 0 auto;
            padding: 3rem;
            gap: 3rem;
        }
        .sidebar {
            width: 320px;
            flex-shrink: 0;
            position: sticky;
            top: calc(var(--header-height) + 1.5rem);
            align-self: flex-start;
        }
        .content-area { flex: 1; min-width: 0; }
        .panel {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            margin-bottom: 2rem;
        }
        .panel h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 1.5rem; color: #24292e; letter-spacing: -0.01em; }
        .field { margin-bottom: 1.25rem; }
        .field label { display: block; font-weight: 800; margin-bottom: 0.75rem; color: #586069; font-size: 0.9rem; }
        .input, .select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
            background-color: #fafbfc;
        }
        .input:focus, .select:focus { outline: none; border-color: #0366d6; box-shadow: 0 0 0 3px rgba(3,102,214,0.1); background-color: #fff; }
        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            font-size: 0.875rem;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }
        .btn-primary { background-color: #0366d6; color: #fff; }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3,102,214,0.3); }
        .btn-secondary { background-color: #586069; color: #fff; }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }
        .btn-danger { background-color: #d73a49; color: #fff; }
        .btn-danger:hover { background-color: #c62f3c; transform: translateY(-1px); }

        .page-title {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            color: #24292e;
            letter-spacing: -0.025em;
        }
        .topbar { display: flex; justify-content: space-between; gap: 1rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem; }
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
        .title { font-size: 1.35rem; font-weight: 900; margin-bottom: 0.25rem; }
        .sub { color: #586069; font-weight: 700; }
        .desc { color: #586069; margin-top: 0.75rem; line-height: 1.65; }
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
        .pill {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-weight: 900;
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            white-space: nowrap;
            font-size: 0.85rem;
        }
        .pill.public { background: #e6ffed; border-color: #b7f5c3; color: #1a7f37; }
        .pill.draft { background: #fff8c5; border-color: #f5e58a; color: #7a5d00; }
        .pill.stopped { background: #fff5f5; border-color: #ffccd2; color: #b31d28; }
        .actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #e1e4e8; flex-wrap: wrap; }
        .inline { display: inline-flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }

        @media (max-width: 920px) {
            .header-content { height: var(--header-height-mobile); }
            .nav-links { position: static; left: auto; transform: none; justify-content: flex-start; }
            .user-menu { position: static; transform: none; margin-left: auto; }
            .main-content { flex-direction: column; padding: 1.5rem; }
            .sidebar { width: 100%; position: static; top: auto; order: -1; }
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
                <a href="#" class="nav-link">フリーランス一覧</a>
                <a href="#" class="nav-link active">案件一覧</a>
                <a href="#" class="nav-link has-badge">応募された案件 <span class="badge">3</span></a>
                <a href="#" class="nav-link has-badge">スカウト <span class="badge">1</span></a>
            </nav>
            <div class="user-menu">
                <div class="dropdown" id="userDropdown">
                    <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">企</button>
                    <div class="dropdown-content" id="userDropdownMenu" role="menu" aria-label="ユーザーメニュー">
                        <a href="#" class="dropdown-item" role="menuitem">プロフィール設定</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" role="menuitem">ログアウト</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <aside class="sidebar">
            <div class="panel">
                <h3>検索</h3>
                <div class="field">
                    <label for="keyword">キーワード</label>
                    <input id="keyword" class="input" type="text" placeholder="タイトル / 概要 / スキル など">
                </div>
                <button class="btn btn-primary" type="button">検索</button>
            </div>
        </aside>

        <section class="content-area">
            <div class="topbar">
                <h1 class="page-title">自社案件一覧</h1>
                <a class="btn btn-primary" href="#">新規登録</a>
            </div>

            <div class="list">
                <article class="card">
                    <div class="inline">
                        <span class="pill public">公開</span>
                        <div class="title">ECサイト機能拡張プロジェクト</div>
                    </div>
                    <div class="sub">株式会社AITECH</div>
                    <div class="desc">既存ECの管理画面と商品登録フローを改善。速度改善とUX向上が目的。</div>
                    <div class="meta">
                        <div class="meta-item"><div class="meta-label">報酬</div><div class="meta-value">50〜70万</div></div>
                        <div class="meta-item"><div class="meta-label">期間</div><div class="meta-value">3ヶ月</div></div>
                        <div class="meta-item"><div class="meta-label">稼働</div><div class="meta-value">週20〜30h</div></div>
                        <div class="meta-item"><div class="meta-label">スキル</div><div class="meta-value">Laravel</div></div>
                    </div>
                    <div class="actions">
                        <label class="inline" style="margin-right:auto; font-weight:900; color:#586069;">
                            ステータス
                            <select class="select" aria-label="ステータス">
                                <option selected>公開</option>
                                <option>下書き</option>
                                <option>停止</option>
                            </select>
                        </label>
                        <a class="btn btn-secondary" href="#">編集</a>
                        <button class="btn btn-danger" type="button">削除</button>
                    </div>
                </article>

                <article class="card">
                    <div class="inline">
                        <span class="pill draft">下書き</span>
                        <div class="title">モバイルアプリ開発（新規）</div>
                    </div>
                    <div class="sub">株式会社AITECH</div>
                    <div class="desc">React Native での新規アプリ立ち上げ。画面設計〜実装まで。</div>
                    <div class="meta">
                        <div class="meta-item"><div class="meta-label">報酬</div><div class="meta-value">60〜80万</div></div>
                        <div class="meta-item"><div class="meta-label">期間</div><div class="meta-value">4ヶ月</div></div>
                        <div class="meta-item"><div class="meta-label">稼働</div><div class="meta-value">週25〜35h</div></div>
                        <div class="meta-item"><div class="meta-label">スキル</div><div class="meta-value">RN</div></div>
                    </div>
                    <div class="actions">
                        <label class="inline" style="margin-right:auto; font-weight:900; color:#586069;">
                            ステータス
                            <select class="select" aria-label="ステータス">
                                <option>公開</option>
                                <option selected>下書き</option>
                                <option>停止</option>
                            </select>
                        </label>
                        <a class="btn btn-secondary" href="#">編集</a>
                        <button class="btn btn-danger" type="button">削除</button>
                    </div>
                </article>

                <article class="card">
                    <div class="inline">
                        <span class="pill stopped">停止</span>
                        <div class="title">API開発プロジェクト（運用停止）</div>
                    </div>
                    <div class="sub">株式会社AITECH</div>
                    <div class="desc">API設計・実装。停止中のため、新規応募は不可（応募履歴側で閲覧のみ想定）。</div>
                    <div class="meta">
                        <div class="meta-item"><div class="meta-label">報酬</div><div class="meta-value">40〜60万</div></div>
                        <div class="meta-item"><div class="meta-label">期間</div><div class="meta-value">2ヶ月</div></div>
                        <div class="meta-item"><div class="meta-label">稼働</div><div class="meta-value">週15〜25h</div></div>
                        <div class="meta-item"><div class="meta-label">スキル</div><div class="meta-value">Node.js</div></div>
                    </div>
                    <div class="actions">
                        <label class="inline" style="margin-right:auto; font-weight:900; color:#586069;">
                            ステータス
                            <select class="select" aria-label="ステータス">
                                <option>公開</option>
                                <option>下書き</option>
                                <option selected>停止</option>
                            </select>
                        </label>
                        <a class="btn btn-secondary" href="#">編集</a>
                        <button class="btn btn-danger" type="button">削除</button>
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
</body>
</html>
