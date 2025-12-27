<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>案件詳細 - AITECH</title>
    <style>
        :root {
            --header-height: 104px;       /* 80px * 1.3 */
            --header-height-mobile: 91px; /* 70px * 1.3 */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 97.5%; }
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafbfc;
            color: #24292e;
            line-height: 1.5;
        }

        /* Header Styles - Minimalist */
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
            flex-direction: row;
            gap: 3rem;
            align-items: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            justify-content: center;
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
        .user-menu {
            display: flex;
            align-items: center;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
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
        }
        .user-avatar:hover { transform: scale(1.08); box-shadow: 0 4px 16px rgba(0,0,0,0.2); }
        .user-avatar:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.25), 0 2px 8px rgba(0,0,0,0.1); }

        /* Main Layout */
        .main-content {
            display: flex;
            max-width: 1600px;
            margin: 0 auto;
            padding: 3rem;
            gap: 3rem;
        }
        .content-area { flex: 1; min-width: 0; }
        .sidebar {
            width: 360px;
            flex-shrink: 0;
            position: sticky;
            top: calc(var(--header-height) + 1.5rem);
            align-self: flex-start;
        }

        .page-breadcrumbs {
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
            color: #6a737d;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .page-breadcrumbs a {
            color: #0366d6;
            text-decoration: none;
        }
        .page-breadcrumbs a:hover { text-decoration: underline; }

        .hero {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin-bottom: 0.5rem;
            color: #24292e;
            line-height: 1.2;
        }
        .hero-company { color: #586069; font-size: 1.05rem; font-weight: 600; }
        .hero-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.25rem;
        }
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-weight: 800;
            font-size: 0.85rem;
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            color: #24292e;
            white-space: nowrap;
        }
        .chip.primary {
            background: #f1f8ff;
            border-color: #c8e1ff;
            color: #0366d6;
        }

        .section {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            margin-bottom: 2rem;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 900;
            margin-bottom: 1rem;
            color: #24292e;
            letter-spacing: -0.01em;
        }
        .section p, .section li { color: #586069; font-size: 1rem; line-height: 1.7; }
        .section ul { padding-left: 1.25rem; display: grid; gap: 0.5rem; }

        .job-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .detail-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            background-color: #f6f8fa;
            border-radius: 10px;
        }
        .detail-label {
            font-size: 0.75rem;
            color: #6a737d;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .detail-value {
            font-weight: 900;
            color: #24292e;
            font-size: 1.05rem;
            white-space: nowrap;
        }

        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        .skill-tag {
            background-color: #f1f8ff;
            color: #0366d6;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid #c8e1ff;
        }

        /* Sidebar cards */
        .side-card {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            margin-bottom: 2rem;
        }
        .side-title { font-size: 1.1rem; font-weight: 900; margin-bottom: 1.25rem; }
        .kv {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 0.75rem 1rem;
        }
        .k { color: #6a737d; font-weight: 800; font-size: 0.9rem; }
        .v { color: #24292e; font-weight: 900; font-size: 0.95rem; }
        .help { color: #6a737d; font-size: 0.85rem; line-height: 1.5; }

        .btn-row {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        .btn-row.horizontal {
            flex-direction: row;
            gap: 1rem;
        }
        .btn-row.horizontal .btn {
            flex: 1;
        }
        .btn {
            padding: 0.875rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
            letter-spacing: -0.01em;
        }
        .btn-primary { background-color: #0366d6; color: white; }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3); }
        .btn-secondary { background-color: #586069; color: white; }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }
        .btn-ghost {
            background: #fafbfc;
            color: #24292e;
            border: 1px solid #e1e4e8;
        }
        .btn-ghost:hover { background: #f6f8fa; transform: translateY(-1px); }

        /* Dropdown Menu */
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
            margin: 0 0.25rem;
            white-space: nowrap;
        }
        .dropdown-item:hover { background-color: #f6f8fa; color: #24292e; }
        .dropdown-divider { height: 1px; background-color: #e1e4e8; margin: 0.5rem 0; }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-content { padding: 2rem; gap: 2rem; }
            .sidebar { width: 320px; }
            .job-details { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .header-content { padding: 0 1.5rem; height: var(--header-height-mobile); }
            .nav-links { gap: 1.5rem; position: static; left: auto; transform: none; justify-content: flex-start; flex-direction: row; flex-wrap: wrap; }
            .user-menu { position: static; right: auto; top: auto; transform: none; margin-left: auto; }
            .nav-link { padding: 0.5rem 1rem; font-size: 1rem; }
            .main-content { flex-direction: column; padding: 1.5rem; }
            .sidebar { width: 100%; order: -1; position: static; top: auto; }
            .kv { grid-template-columns: 1fr; }
            .btn-row.horizontal { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <nav class="nav-links">
                <a href="#" class="nav-link active">案件一覧</a>
                <a href="#" class="nav-link has-badge">
                    応募した案件
                    <span class="badge">3</span>
                </a>
                <a href="#" class="nav-link has-badge">
                    スカウト
                    <span class="badge">1</span>
                </a>
            </nav>
            <div class="user-menu">
                <div class="dropdown" id="userDropdown">
                    <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">山</button>
                    <div class="dropdown-content" id="userDropdownMenu" role="menu" aria-label="ユーザーメニュー">
                        <a href="{{ route('freelancer.profile.settings') }}" class="dropdown-item" role="menuitem">プロフィール設定</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('auth.logout') }}" class="dropdown-item" role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="content-area">
            <div class="page-breadcrumbs" aria-label="パンくず">
                <a href="#">案件一覧</a>
                <span aria-hidden="true">/</span>
                <span>案件詳細</span>
            </div>

            <section class="hero" aria-label="案件概要">
                <div>
                    <h1 class="hero-title">ECサイト機能拡張プロジェクト</h1>
                    <div class="hero-company">株式会社AITECH</div>
                </div>
                <div class="hero-meta" aria-label="タグ">
                    <span class="chip primary">公開中</span>
                    <span class="chip">週20〜30時間 / 3ヶ月</span>
                    <span class="chip">50〜70万円</span>
                </div>

                <div class="job-details" aria-label="主要条件">
                    <div class="detail-item">
                        <div class="detail-label">報酬目安</div>
                        <div class="detail-value">50〜70万円</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">想定稼働時間／期間</div>
                        <div class="detail-value">週20〜30時間 / 3ヶ月</div>
                    </div>
                </div>

                <div class="skills" aria-label="必要スキル">
                    <span class="skill-tag">PHP</span>
                    <span class="skill-tag">Laravel</span>
                    <span class="skill-tag">JavaScript</span>
                    <span class="skill-tag">Vue.js</span>
                    <span class="skill-tag">MySQL</span>
                </div>
            </section>

            <section class="section" aria-label="業務内容">
                <div class="section-title">業務内容</div>
                <p>既存ECサイトに新機能を追加するプロジェクトです。商品管理・管理画面改善・パフォーマンス改善などを担当します。</p>
            </section>

            <section class="section" aria-label="応募">
                <div class="btn-row horizontal">
                    <a href="#" class="btn btn-primary">応募する</a>
                    <a href="#" class="btn btn-secondary">一覧に戻る</a>
                </div>
            </section>
        </div>
    </main>

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
</body>
</html>
