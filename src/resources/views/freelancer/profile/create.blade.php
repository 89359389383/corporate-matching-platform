<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール作成 - AITECH</title>
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
        .content-area { flex: 1; min-width: 0; }
        .sidebar {
            width: 360px;
            flex-shrink: 0;
            position: sticky;
            top: calc(var(--header-height) + 1.5rem);
            align-self: flex-start;
        }

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

        .form { display: grid; gap: 1.25rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .row { display: grid; gap: 0.6rem; }
        .label {
            font-weight: 900;
            color: #586069;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .required {
            font-size: 0.75rem;
            font-weight: 900;
            color: white;
            background: #d73a49;
            border-radius: 999px;
            padding: 0.15rem 0.55rem;
            letter-spacing: 0.02em;
        }
        .input, .textarea, .select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
            background-color: #fafbfc;
        }
        .textarea { min-height: 160px; resize: vertical; line-height: 1.6; }
        .input:focus, .textarea:focus, .select:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
            background-color: white;
        }
        .help {
            color: #6a737d;
            font-size: 0.85rem;
            line-height: 1.5;
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
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid #c8e1ff;
        }
        .tag-input {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }
        .tag-input .input { flex: 1; }

        .divider {
            height: 1px;
            background: #e1e4e8;
            margin: 0.5rem 0;
        }

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

        /* Preview */
        .profile-card {
            position: relative;
            overflow: hidden;
        }
        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        .profile-head {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        .big-avatar {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: grid;
            place-items: center;
            color: white;
            font-weight: 900;
            font-size: 1.1rem;
        }
        .name { font-weight: 900; font-size: 1.15rem; }
        .headline { color: #586069; font-weight: 800; font-size: 0.9rem; }
        .kv {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 0.75rem 1rem;
            margin-top: 1rem;
        }
        .k { color: #6a737d; font-weight: 900; font-size: 0.85rem; }
        .v { color: #24292e; font-weight: 900; font-size: 0.9rem; }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-content { padding: 2rem; gap: 2rem; }
            .sidebar { width: 320px; }
        }
        @media (max-width: 768px) {
            .header-content { padding: 0 1.5rem; height: var(--header-height-mobile); }
            .nav-links { gap: 1.5rem; position: static; left: auto; transform: none; justify-content: flex-start; flex-direction: row; flex-wrap: wrap; }
            .user-menu { position: static; right: auto; top: auto; transform: none; margin-left: auto; }
            .nav-link { padding: 0.5rem 1rem; font-size: 1rem; }
            .main-content { flex-direction: column; padding: 1.5rem; }
            .sidebar { width: 100%; order: -1; position: static; top: auto; }
            .grid-2 { grid-template-columns: 1fr; }
            .kv { grid-template-columns: 1fr; }
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
        <!-- Sidebar preview -->
        <aside class="sidebar">
            <div class="panel profile-card">
                <div class="panel-title">プレビュー</div>
                <div class="profile-head">
                    <div class="big-avatar">山</div>
                    <div style="min-width:0;">
                        <div class="name">山田 太郎</div>
                        <div class="headline">Laravelエンジニア</div>
                    </div>
                </div>
                <div class="skills" aria-label="スキル">
                    <span class="skill-tag">Laravel</span>
                    <span class="skill-tag">Vue.js</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">Docker</span>
                </div>
                <div class="divider"></div>
                <div class="kv" aria-label="条件">
                    <div class="k">希望単価</div>
                    <div class="v">50〜70</div>
                    <div class="k">稼働</div>
                    <div class="v">週20〜30h</div>
                    <div class="k">日</div>
                    <div class="v">6h/day・5日/week</div>
                </div>
                <p class="help" style="margin-top:1rem;">プロフィールが充実しているほどスカウトが届きやすくなります。</p>
            </div>
        </aside>

        <!-- Form -->
        <div class="content-area">
            <h1 class="page-title">プロフィール作成</h1>
            <p class="page-subtitle">機能要件のプロフィール項目を登録します。</p>

            <div class="panel">
                <div class="panel-title">基本情報</div>
                <form class="form" action="#" method="post" enctype="multipart/form-data">
                    <div class="grid-2">
                        <div class="row">
                            <label class="label" for="display_name">表示名 <span class="required">必須</span></label>
                            <input class="input" id="display_name" name="display_name" type="text" value="山田 太郎" placeholder="例: 山田 太郎" required>
                        </div>
                        <div class="row">
                            <label class="label" for="job_title">職種（自由入力） <span class="required">必須</span></label>
                            <input class="input" id="job_title" name="job_title" type="text" value="Laravelエンジニア" placeholder="例: Laravelエンジニア" required>
                        </div>
                    </div>

                    <div class="row">
                        <label class="label" for="bio">自己紹介文 <span class="required">必須</span></label>
                        <textarea class="textarea" id="bio" name="bio" placeholder="例) Laravelを中心にWeb開発を5年経験。EC/在庫管理などの業務ドメインに強みがあります。" required>Laravelを中心にWeb開発を5年経験。EC/在庫管理の実務経験があります。</textarea>
                        <div class="help">成果（数値/期間/担当範囲）を入れると伝わりやすいです。</div>
                    </div>

                    <div class="row">
                        <label class="label" for="experience_companies">経験企業（任意）</label>
                        <textarea class="textarea" id="experience_companies" name="experience_companies" placeholder="例) 株式会社◯◯（2021-2023）&#10;株式会社△△（2019-2021）">株式会社◯◯（2021-2023）</textarea>
                    </div>

                    <div class="divider"></div>

                    <div class="panel-title" style="margin-bottom:1rem;">スキル / 実績</div>
                    <div class="row">
                        <label class="label">スキル（自由入力・必須）</label>
                        <div class="help">複数入力できます。</div>
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
                        <label class="label">ポートフォリオURL（任意・複数）</label>
                        <div class="grid-2">
                            <input class="input" name="portfolio_urls[]" type="url" value="https://portfolio.example.com" placeholder="例: https://example.com/portfolio">
                            <input class="input" name="portfolio_urls[]" type="url" value="https://github.com/example" placeholder="例: https://github.com/yourname">
                        </div>
                        <div class="grid-2" style="margin-top:0.75rem;">
                            <input class="input" name="portfolio_urls[]" type="url" value="" placeholder="例: https://...">
                            <input class="input" name="portfolio_urls[]" type="url" value="" placeholder="例: https://...">
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="panel-title" style="margin-bottom:1rem;">稼働条件</div>
                    <div class="grid-2">
                        <div class="row">
                            <label class="label" for="min_rate">希望単価（下限〜上限）</label>
                            <div class="grid-2">
                                <input class="input" id="min_rate" name="min_rate" type="number" value="50" placeholder="下限">
                                <input class="input" name="max_rate" type="number" value="70" placeholder="上限">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label">稼働可能時間 <span class="required">必須</span></label>
                            <div class="grid-2">
                                <input class="input" name="min_hours_per_week" type="number" value="20" placeholder="週 下限(h)" required>
                                <input class="input" name="max_hours_per_week" type="number" value="30" placeholder="週 上限(h)" required>
                            </div>
                            <div class="grid-2" style="margin-top:0.75rem;">
                                <input class="input" name="hours_per_day" type="number" value="6" placeholder="h/day" required>
                                <input class="input" name="days_per_week" type="number" value="5" placeholder="日/week" required>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top:1.25rem;">
                        <label class="label" for="work_style_text">働き方（自由入力テキスト）</label>
                        <textarea class="textarea" id="work_style_text" name="work_style_text" placeholder="例) リモート中心、平日10-18で稼働可能">リモート中心、平日10-18で稼働可能</textarea>
                    </div>

                    <div class="row" style="margin-top:1.25rem;">
                        <label class="label" for="icon">ユーザーアイコン <span class="required">必須</span></label>
                        <input class="input" id="icon" name="icon" type="file" accept="image/*" required>
                        <div class="help">画像を選択してください（最大5MB）。</div>
                    </div>

                    <div class="actions">
                        <a class="btn btn-secondary" href="#" role="button">キャンセル</a>
                        <button class="btn btn-primary" type="submit">登録</button>
                    </div>
                </form>
            </div>
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
