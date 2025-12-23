<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定 - AITECH</title>
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
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            color: #24292e;
            letter-spacing: -0.025em;
        }
        .page-subtitle {
            color: #6a737d;
            font-size: 1rem;
            margin-bottom: 2.25rem;
        }

        .panel {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            margin-bottom: 2rem;
        }

        .panel-title {
            font-size: 1.1rem;
            font-weight: 900;
            margin-bottom: 1.25rem;
            color: #24292e;
            letter-spacing: -0.01em;
        }

        .menu {
            display: grid;
            gap: 0.5rem;
        }
        .menu a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: #24292e;
            padding: 0.9rem 1rem;
            border-radius: 12px;
            border: 1px solid transparent;
            font-weight: 900;
            background: #ffffff;
            transition: all 0.15s ease;
        }
        .menu a:hover { background: #f6f8fa; border-color: #e1e4e8; }
        .menu a.active { background: #f1f8ff; border-color: #c8e1ff; color: #0366d6; }
        .menu small { color: #6a737d; font-weight: 800; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .row { display: grid; gap: 0.6rem; }
        .label { font-weight: 900; color: #586069; font-size: 0.9rem; }
        .input, .select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
            background-color: #fafbfc;
        }
        .input:focus, .select:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
            background-color: white;
        }
        .help { color: #6a737d; font-size: 0.85rem; line-height: 1.5; }

        .setting-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 1rem 0;
            border-top: 1px solid #e1e4e8;
        }
        .setting-row:first-of-type { border-top: none; padding-top: 0; }
        .setting-row strong { font-weight: 900; }
        .setting-row p { color: #6a737d; font-weight: 800; font-size: 0.85rem; margin-top: 0.25rem; }

        /* Switch */
        .switch {
            position: relative;
            width: 54px;
            height: 30px;
            flex: 0 0 auto;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: #d1d5da;
            border-radius: 999px;
            transition: 0.15s ease;
            border: 1px solid #cfd3d7;
        }
        .slider:before {
            content: "";
            position: absolute;
            height: 24px;
            width: 24px;
            left: 3px;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border-radius: 50%;
            transition: 0.15s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.25);
        }
        .switch input:checked + .slider {
            background: #0366d6;
            border-color: #0366d6;
        }
        .switch input:checked + .slider:before { transform: translate(24px, -50%); }
        .switch input:focus-visible + .slider { box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.25); }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #e1e4e8;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.875rem 1.75rem;
            border-radius: 10px;
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
        .btn-primary { background-color: #0366d6; color: white; }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3); }
        .btn-secondary { background-color: #586069; color: white; }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }
        .btn-danger { background-color: #d73a49; color: white; }
        .btn-danger:hover { background-color: #c7303f; transform: translateY(-1px); }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-content { padding: 2rem; gap: 2rem; }
            .sidebar { width: 280px; }
        }
        @media (max-width: 768px) {
            .header-content { padding: 0 1.5rem; height: var(--header-height-mobile); }
            .nav-links { gap: 1.5rem; position: static; left: auto; transform: none; justify-content: flex-start; flex-direction: row; flex-wrap: wrap; }
            .user-menu { position: static; right: auto; top: auto; transform: none; margin-left: auto; }
            .nav-link { padding: 0.5rem 1rem; font-size: 1rem; }
            .main-content { flex-direction: column; padding: 1.5rem; }
            .sidebar { width: 100%; order: -1; position: static; top: auto; }
            .grid-2 { grid-template-columns: 1fr; }
            .actions { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <nav class="nav-links">
                <a href="#" class="nav-link">案件一覧</a>
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
                        <a href="#" class="dropdown-item" role="menuitem">プロフィール設定</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" role="menuitem">ログアウト</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <aside class="sidebar" aria-label="設定メニュー">
            <div class="panel">
                <div class="panel-title">プロフィール設定</div>
                <nav class="menu">
                    <a class="active" href="#"><span>プロフィール</span><small>表示名/職種/スキル/条件</small></a>
                </nav>
            </div>
        </aside>

        <div class="content-area">
            <h1 class="page-title">プロフィール設定</h1>
            <p class="page-subtitle">プロフィール（メール/パスワード以外）を編集します。</p>

            <section class="panel" aria-label="プロフィール編集">
                <div class="panel-title">プロフィール</div>
                <form class="form" action="#" method="post" enctype="multipart/form-data">

                    <div class="grid-2">
                        <div class="row">
                            <div class="label">表示名</div>
                            <input class="input" name="display_name" type="text" value="山田 太郎" required>
                        </div>
                        <div class="row">
                            <div class="label">職種（自由入力）</div>
                            <input class="input" name="job_title" type="text" value="Laravelエンジニア" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">自己紹介</div>
                        <textarea class="textarea" name="bio" required>Laravelを中心にWeb開発を5年経験。EC/在庫管理の実務経験があります。</textarea>
                    </div>

                    <div class="row">
                        <div class="label">スキル（自由入力）</div>
                        <div class="grid-2">
                            <input class="input" name="custom_skills[]" type="text" value="Laravel" placeholder="例: Laravel">
                            <input class="input" name="custom_skills[]" type="text" value="Vue.js" placeholder="例: Vue.js">
                        </div>
                        <div class="grid-2" style="margin-top:0.75rem;">
                            <input class="input" name="custom_skills[]" type="text" value="MySQL" placeholder="例: MySQL">
                            <input class="input" name="custom_skills[]" type="text" value="Docker" placeholder="例: Docker">
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">経験企業（任意）</div>
                        <textarea class="textarea" name="experience_companies">株式会社◯◯（2021-2023）</textarea>
                    </div>

                    <div class="divider"></div>

                    <div class="panel-title" style="margin-bottom:1rem;">稼働条件</div>
                    <div class="grid-2">
                        <div class="row">
                            <div class="label">稼働可能時間（週）</div>
                            <div class="grid-2">
                                <input class="input" name="min_hours_per_week" type="number" value="20" placeholder="下限(h)" required>
                                <input class="input" name="max_hours_per_week" type="number" value="30" placeholder="上限(h)" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="label">稼働（day / week）</div>
                            <div class="grid-2">
                                <input class="input" name="hours_per_day" type="number" value="6" placeholder="h/day" required>
                                <input class="input" name="days_per_week" type="number" value="5" placeholder="日/week" required>
                            </div>
                        </div>
                    </div>

                    <div class="grid-2" style="margin-top:1.25rem;">
                        <div class="row">
                            <div class="label">働き方（自由入力）</div>
                            <textarea class="textarea" name="work_style_text">リモート中心、平日10-18で稼働可能</textarea>
                        </div>
                        <div class="row">
                            <div class="label">希望単価（下限〜上限）</div>
                            <div class="grid-2">
                                <input class="input" name="min_rate" type="number" value="50" placeholder="下限">
                                <input class="input" name="max_rate" type="number" value="70" placeholder="上限">
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="panel-title" style="margin-bottom:1rem;">ポートフォリオ</div>
                    <div class="grid-2">
                        <input class="input" name="portfolio_urls[]" type="url" value="https://portfolio.example.com" placeholder="https://...">
                        <input class="input" name="portfolio_urls[]" type="url" value="https://github.com/example" placeholder="https://...">
                    </div>
                    <div class="grid-2" style="margin-top:0.75rem;">
                        <input class="input" name="portfolio_urls[]" type="url" value="" placeholder="https://...">
                        <input class="input" name="portfolio_urls[]" type="url" value="" placeholder="https://...">
                    </div>

                    <div class="row" style="margin-top:1.25rem;">
                        <div class="label">ユーザーアイコン（任意）</div>
                        <input class="input" name="icon" type="file" accept="image/*">
                    </div>

                    <div class="actions">
                        <a class="btn btn-secondary" href="#" role="button">戻る</a>
                        <button class="btn btn-primary" type="submit">更新</button>
                    </div>
                </form>
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
