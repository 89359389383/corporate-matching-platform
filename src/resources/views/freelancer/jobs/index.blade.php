<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリーランス案件一覧 - AITECH</title>
    <style>
        :root {
            --header-height: 104px;       /* 80px * 1.3 */
            --header-height-mobile: 91px; /* 70px * 1.3 */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 97.5%;
        }

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

        .nav-link.has-badge {
            padding-right: 3rem; /* badge 分の余白 */
        }

        .nav-link:hover {
            background-color: #f6f8fa;
            color: #24292e;
        }

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
            margin-left: 0;
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

        .user-avatar:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        }

        .user-avatar:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.25), 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Main Layout - Clean and Spacious */
        .main-content {
            display: flex;
            max-width: 1600px;
            margin: 0 auto;
            padding: 3rem;
            gap: 3rem;
        }

        /* Sidebar - Minimal */
        .sidebar {
            width: 320px;
            flex-shrink: 0;
            position: sticky;
            top: calc(var(--header-height) + 1.5rem);
            align-self: flex-start;
        }

        .search-section {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            margin-bottom: 2rem;
        }

        .search-section h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: #24292e;
            letter-spacing: -0.01em;
        }

        .search-group {
            margin-bottom: 2rem;
        }

        .search-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #586069;
            font-size: 0.9rem;
        }

        .search-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
            background-color: #fafbfc;
        }

        .search-input:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
            background-color: white;
        }

        .radio-group {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .radio-input {
            margin: 0;
            width: 16px;
            height: 16px;
        }

        .price-range {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .price-row {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .price-row-label {
            flex: 0 0 auto;
            min-width: 3.2rem;
            font-size: 0.9rem;
            color: #6a737d;
            font-weight: 600;
            white-space: nowrap;
        }

        .price-row-unit {
            flex: 0 0 auto;
            white-space: nowrap;
            font-weight: 600;
            color: #586069;
        }

        .price-help {
            margin-top: 0.75rem;
            font-size: 0.85rem;
            color: #6a737d;
            line-height: 1.4;
        }

        .price-input {
            flex: 1 1 0;
            width: 0;
            min-width: 0;
            padding: 0.5rem 0.625rem;
            border: 2px solid #e1e4e8;
            border-radius: 6px;
            font-size: 0.9rem;
            background-color: #fafbfc;
            transition: all 0.15s ease;
        }

        .price-input:focus {
            border-color: #0366d6;
            background-color: white;
        }

        /* Remove number input spinners */
        .price-input[type="number"] {
            -moz-appearance: textfield; /* Firefox */
            appearance: textfield;
        }
        .price-input[type="number"]::-webkit-outer-spin-button,
        .price-input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none; /* Chrome, Safari, Edge */
            margin: 0;
        }

        .search-btn {
            width: 100%;
            background-color: #0366d6;
            color: white;
            border: none;
            padding: 0.875rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 0.95rem;
        }

        .search-btn:hover {
            background-color: #0256cc;
            box-shadow: 0 2px 8px rgba(3, 102, 214, 0.3);
        }

        /* Content Area */
        .content-area {
            flex: 1;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2.5rem;
            color: #24292e;
            letter-spacing: -0.025em;
        }

        .jobs-grid {
            display: grid;
            gap: 2rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2.5rem;
        }

        .pagination-list {
            list-style: none;
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
            padding: 0.75rem;
            background-color: white;
            border: 1px solid #e1e4e8;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .pagination-link {
            min-width: 40px;
            height: 40px;
            padding: 0 0.75rem;
            border-radius: 10px;
            border: 1px solid #e1e4e8;
            background-color: #fafbfc;
            color: #24292e;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .pagination-link:hover {
            background-color: #f6f8fa;
            border-color: #d1d5da;
            transform: translateY(-1px);
        }

        .pagination-link.is-active {
            background-color: #0366d6;
            border-color: #0366d6;
            color: white;
            box-shadow: 0 2px 8px rgba(3, 102, 214, 0.25);
        }

        .pagination-link.is-disabled {
            opacity: 0.45;
            pointer-events: none;
        }

        .pagination-ellipsis {
            color: #6a737d;
            padding: 0 0.25rem;
            font-weight: 700;
        }

        .job-card {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            transition: all 0.2s ease;
            border: 1px solid #e1e4e8;
            position: relative;
            overflow: hidden;
        }

        .job-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .job-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .job-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #24292e;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .company-name {
            color: #586069;
            font-size: 1rem;
            font-weight: 500;
        }

        .job-description {
            color: #586069;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            font-size: 1rem;
        }

        .job-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-item {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            background-color: #f6f8fa;
            border-radius: 8px;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #6a737d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0;
            white-space: nowrap;
        }

        .detail-value {
            font-weight: 700;
            color: #24292e;
            font-size: 1.1rem;
            white-space: nowrap;
        }

        .skills-section {
            margin-bottom: 2rem;
        }

        .skills-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #586069;
            margin-bottom: 0.75rem;
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
            font-weight: 600;
            border: 1px solid #c8e1ff;
        }

        .job-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #e1e4e8;
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
        }

        .btn-secondary {
            background-color: #586069;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4c5561;
            transform: translateY(-1px);
        }

        .btn-primary {
            background-color: #0366d6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0256cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3);
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-content {
                padding: 2rem;
                gap: 2rem;
            }

            .sidebar {
                width: 280px;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                padding: 0 1.5rem;
                height: var(--header-height-mobile);
            }

            .nav-links {
                gap: 1.5rem;
                position: static;
                left: auto;
                transform: none;
                justify-content: flex-start;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .user-menu {
                position: static;
                right: auto;
                top: auto;
                transform: none;
                margin-left: auto;
            }

            .nav-link {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }

            .main-content {
                flex-direction: column;
                padding: 1.5rem;
            }

            .sidebar {
                width: 100%;
                order: -1;
                position: static;
                top: auto;
            }

            .search-section {
                margin-bottom: 1.5rem;
            }

            .job-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .job-details {
                grid-template-columns: 1fr;
            }

            .pagination-list {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
                border-radius: 12px;
            }
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

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

        .dropdown.is-open .dropdown-content {
            display: block;
        }

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

        .dropdown-item:hover {
            background-color: #f6f8fa;
            color: #24292e;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #e1e4e8;
            margin: 0.5rem 0;
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

    <!-- Main Content -->
    <main class="main-content">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="search-section">
                <h3>検索条件</h3>

                <div class="search-group">
                    <label for="keyword">キーワード</label>
                    <input type="text" id="keyword" class="search-input" placeholder="案件名 / 会社名 / スキル など">
                </div>

                <div class="search-group">
                    <label>報酬</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" id="priceTypeMonthly" name="price-type" class="radio-input" value="monthly" checked>
                            <span>単価</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" id="priceTypeHourly" name="price-type" class="radio-input" value="hourly">
                            <span>時給</span>
                        </label>
                    </div>
                    <div class="price-range">
                        <div class="price-row">
                            <span class="price-row-label">下限</span>
                            <input type="number" id="priceMin" class="price-input" placeholder="例: 50" min="0" step="1" inputmode="numeric">
                            <span class="price-row-unit priceUnit">万円</span>
                        </div>
                        <div class="price-row">
                            <span class="price-row-label">上限</span>
                            <input type="number" id="priceMax" class="price-input" placeholder="例: 70" min="0" step="1" inputmode="numeric">
                            <span class="price-row-unit priceUnit">万円</span>
                        </div>
                    </div>
                    <div class="price-help" id="priceHelp">例: 50 〜 70（万円）</div>
                </div>

                <button class="search-btn">検索</button>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="content-area">
            <h1 class="page-title">公開中の案件</h1>

            <div class="jobs-grid">
                <!-- Job Card 1 -->
                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <h2 class="job-title">ECサイト機能拡張プロジェクト</h2>
                            <div class="company-name">株式会社AITECH</div>
                        </div>
                    </div>

                    <p class="job-description">既存のECサイトに新機能を追加するプロジェクトです。商品管理システムの改善とユーザー体験の向上を目的としています。</p>

                    <div class="job-details">
                        <div class="detail-item">
                            <div class="detail-label">稼働時間</div>
                            <div class="detail-value">週20〜30時間</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">契約期間</div>
                            <div class="detail-value">3ヶ月</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">報酬</div>
                            <div class="detail-value">50〜70万円</div>
                        </div>
                    </div>

                    <div class="skills-section">
                        <div class="skills-title">必要スキル</div>
                        <div class="skills">
                            <span class="skill-tag">PHP</span>
                            <span class="skill-tag">Laravel</span>
                            <span class="skill-tag">JavaScript</span>
                            <span class="skill-tag">Vue.js</span>
                            <span class="skill-tag">MySQL</span>
                        </div>
                    </div>

                    <div class="job-actions">
                        <a href="#" class="btn btn-secondary">詳細</a>
                        <button class="btn btn-primary">応募</button>
                    </div>
                </div>

                <!-- Job Card 2 -->
                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <h2 class="job-title">モバイルアプリ開発</h2>
                            <div class="company-name">Tech Solutions Inc.</div>
                        </div>
                    </div>

                    <p class="job-description">React Nativeを使用したクロスプラットフォームモバイルアプリの開発。ユーザビリティを重視した設計を求めています。</p>

                    <div class="job-details">
                        <div class="detail-item">
                            <div class="detail-label">稼働時間</div>
                            <div class="detail-value">週25〜35時間</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">契約期間</div>
                            <div class="detail-value">4ヶ月</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">報酬</div>
                            <div class="detail-value">60〜80万円</div>
                        </div>
                    </div>

                    <div class="skills-section">
                        <div class="skills-title">必要スキル</div>
                        <div class="skills">
                            <span class="skill-tag">React Native</span>
                            <span class="skill-tag">JavaScript</span>
                            <span class="skill-tag">TypeScript</span>
                            <span class="skill-tag">Firebase</span>
                        </div>
                    </div>

                    <div class="job-actions">
                        <a href="#" class="btn btn-secondary">詳細</a>
                        <button class="btn btn-primary">応募</button>
                    </div>
                </div>

                <!-- Job Card 3 -->
                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <h2 class="job-title">データ分析システム構築</h2>
                            <div class="company-name">Data Analytics Corp.</div>
                        </div>
                    </div>

                    <p class="job-description">ビッグデータの分析と可視化を行うシステムの構築。Pythonと機械学習の知識を活かせるプロジェクトです。</p>

                    <div class="job-details">
                        <div class="detail-item">
                            <div class="detail-label">稼働時間</div>
                            <div class="detail-value">週30〜40時間</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">契約期間</div>
                            <div class="detail-value">6ヶ月</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">報酬</div>
                            <div class="detail-value">80〜100万円</div>
                        </div>
                    </div>

                    <div class="skills-section">
                        <div class="skills-title">必要スキル</div>
                        <div class="skills">
                            <span class="skill-tag">Python</span>
                            <span class="skill-tag">Pandas</span>
                            <span class="skill-tag">TensorFlow</span>
                            <span class="skill-tag">PostgreSQL</span>
                        </div>
                    </div>

                    <div class="job-actions">
                        <a href="#" class="btn btn-secondary">詳細</a>
                        <button class="btn btn-primary">応募</button>
                    </div>
                </div>

                <!-- Job Card 4 - Already Applied -->
                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <h2 class="job-title">API開発プロジェクト</h2>
                            <div class="company-name">API Solutions Ltd.</div>
                        </div>
                    </div>

                    <p class="job-description">RESTful APIの設計・開発。マイクロサービスアーキテクチャを採用し、スケーラブルなシステム構築を目指します。</p>

                    <div class="job-details">
                        <div class="detail-item">
                            <div class="detail-label">稼働時間</div>
                            <div class="detail-value">週15〜25時間</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">契約期間</div>
                            <div class="detail-value">2ヶ月</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">報酬</div>
                            <div class="detail-value">40〜60万円</div>
                        </div>
                    </div>

                    <div class="skills-section">
                        <div class="skills-title">必要スキル</div>
                        <div class="skills">
                            <span class="skill-tag">Node.js</span>
                            <span class="skill-tag">Express</span>
                            <span class="skill-tag">MongoDB</span>
                            <span class="skill-tag">Docker</span>
                        </div>
                    </div>

                    <div class="job-actions">
                        <a href="#" class="btn btn-secondary">詳細</a>
                        <button class="btn btn-success">応募済み（チャットを開く）</button>
                    </div>
                </div>

                <!-- Job Card 5 -->
                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <h2 class="job-title">UI/UXデザイン改善</h2>
                            <div class="company-name">Design Studio</div>
                        </div>
                    </div>

                    <p class="job-description">既存WebサービスのUI/UXを改善するプロジェクト。ユーザビリティテストとデザインシステムの構築を担当していただきます。</p>

                    <div class="job-details">
                        <div class="detail-item">
                            <div class="detail-label">稼働時間</div>
                            <div class="detail-value">週10〜20時間</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">契約期間</div>
                            <div class="detail-value">3ヶ月</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">報酬</div>
                            <div class="detail-value">45〜65万円</div>
                        </div>
                    </div>

                    <div class="skills-section">
                        <div class="skills-title">必要スキル</div>
                        <div class="skills">
                            <span class="skill-tag">Figma</span>
                            <span class="skill-tag">Sketch</span>
                            <span class="skill-tag">Adobe XD</span>
                            <span class="skill-tag">HTML</span>
                            <span class="skill-tag">CSS</span>
                        </div>
                    </div>

                    <div class="job-actions">
                        <a href="#" class="btn btn-secondary">詳細</a>
                        <button class="btn btn-primary">応募</button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav class="pagination" aria-label="ページネーション">
                <ul class="pagination-list">
                    <li><a class="pagination-link is-disabled" href="#" aria-disabled="true">前へ</a></li>
                    <li><a class="pagination-link is-active" href="#" aria-current="page">1</a></li>
                    <li><a class="pagination-link" href="#">2</a></li>
                    <li><a class="pagination-link" href="#">3</a></li>
                    <li><span class="pagination-ellipsis" aria-hidden="true">…</span></li>
                    <li><a class="pagination-link" href="#">10</a></li>
                    <li><a class="pagination-link" href="#">次へ</a></li>
                </ul>
            </nav>
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
    <script>
        (function () {
            const monthly = document.getElementById('priceTypeMonthly');
            const hourly = document.getElementById('priceTypeHourly');
            const minInput = document.getElementById('priceMin');
            const maxInput = document.getElementById('priceMax');
            const unitEls = document.querySelectorAll('.priceUnit');
            const help = document.getElementById('priceHelp');
            if (!monthly || !hourly || !minInput || !maxInput || unitEls.length === 0 || !help) return;

            const applyType = (type) => {
                const isMonthly = type === 'monthly';

                unitEls.forEach((el) => {
                    el.textContent = isMonthly ? '万円' : '円/時';
                });

                minInput.placeholder = isMonthly ? '例: 50' : '例: 2500';
                maxInput.placeholder = isMonthly ? '例: 70' : '例: 4000';

                minInput.step = isMonthly ? '1' : '10';
                maxInput.step = isMonthly ? '1' : '10';

                help.textContent = isMonthly
                    ? '例: 50 〜 70（万円）'
                    : '例: 2500 〜 4000（円/時）';
            };

            const onChange = () => applyType(monthly.checked ? 'monthly' : 'hourly');
            monthly.addEventListener('change', onChange);
            hourly.addEventListener('change', onChange);

            // 初期反映
            onChange();
        })();
    </script>
</body>
</html>
