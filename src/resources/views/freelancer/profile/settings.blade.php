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
            --container-max-width: 1600px;
            --main-padding: 3rem;
            --sidebar-width: 320px;
            --sidebar-gap: 3rem;
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
            max-width: var(--container-max-width);
            margin: 0 auto;
            padding: var(--main-padding);
            gap: var(--sidebar-gap);
        }
        .sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            /* スクロールしても同じ位置に固定（デスクトップ） */
            position: fixed;
            left: calc((100vw - min(var(--container-max-width), 100vw)) / 2 + var(--main-padding));
            align-self: flex-start;
            z-index: 50;
        }
        /* fixed sidebar による被りを避ける */
        .content-area {
            flex: 1;
            min-width: 0;
            margin-left: calc(var(--sidebar-width) + var(--sidebar-gap));
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
        .input.is-invalid, .textarea.is-invalid, .select.is-invalid {
            border-color: #d73a49;
        }
        .help { color: #6a737d; font-size: 0.85rem; line-height: 1.5; }
        .error-message {
            color: #d73a49;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }
        .divider {
            height: 1px;
            background: #e1e4e8;
            margin: 2rem 0;
        }

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
            padding: 15px 60px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            font-size: 20px;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }
        .btn-primary { background-color: #0366d6; color: white; }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3); }
        .btn-secondary { background-color: #586069; color: white; }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }
        .btn-danger { background-color: #d73a49; color: white; }
        .btn-danger:hover { background-color: #c7303f; transform: translateY(-1px); }
        .btn-outline {
            background-color: transparent;
            color: #0366d6;
            border: 2px solid #0366d6;
            padding: 6px;
        }
        .btn-outline:hover {
            background-color: #f1f8ff;
            color: #0256cc;
            border-color: #0256cc;
        }
        .btn-remove {
            background-color: transparent;
            color: #d73a49;
            border: 2px solid #d73a49;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        .btn-remove:hover {
            background-color: #f8d7da;
            color: #c7303f;
            border-color: #c7303f;
        }
        .skills-container, .portfolio-container {
            display: grid;
            gap: 0.75rem;
        }
        .skill-input-row, .portfolio-input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 42px;
            align-items: start;
        }
        .skill-input-row > div, .portfolio-input-row > div {
            position: relative;
        }
        .remove-skill-btn, .remove-portfolio-btn {
            position: absolute;
            right: -2.5rem;
            top: 50%;
            transform: translateY(-50%);
        }
        @media (max-width: 768px) {
            .skill-input-row, .portfolio-input-row {
                grid-template-columns: 1fr;
            }
            .remove-skill-btn, .remove-portfolio-btn {
                position: static;
                transform: none;
                margin-top: 0.5rem;
                right: auto;
            }
        }

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
            overflow: hidden;
            object-fit: cover;
        }
        .big-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .name { font-weight: 900; font-size: 1.15rem; }
        .headline { color: #586069; font-weight: 800; font-size: 0.9rem; }
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
        .kv {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 0.75rem 1rem;
            margin-top: 1rem;
        }
        .k { color: #6a737d; font-weight: 900; font-size: 0.85rem; }
        .v { color: #24292e; font-weight: 900; font-size: 0.9rem; white-space: nowrap; }

        /* Responsive */
        @media (max-width: 1200px) {
            :root {
                --main-padding: 2rem;
                --sidebar-width: 280px;
                --sidebar-gap: 2rem;
            }
        }
        @media (max-width: 768px) {
            :root {
                --main-padding: 1.5rem;
            }
            .header-content { padding: 0 1.5rem; height: var(--header-height-mobile); }
            .nav-links { gap: 1.5rem; position: static; left: auto; transform: none; justify-content: flex-start; flex-direction: row; flex-wrap: wrap; }
            .user-menu { position: static; right: auto; top: auto; transform: none; margin-left: auto; }
            .nav-link { padding: 0.5rem 1rem; font-size: 1rem; }
            .main-content { flex-direction: column; padding: 1.5rem; }
            .sidebar {
                width: 100%;
                order: -1;
                position: static;
                top: auto;
                left: auto;
                z-index: auto;
            }
            .content-area {
                margin-left: 0;
            }
            .grid-2 { grid-template-columns: 1fr; }
            .kv { grid-template-columns: 1fr; }
            .actions { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
    @include('partials.aitech-responsive')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <nav class="nav-links">
                <a href="{{ route('freelancer.jobs.index') }}" class="nav-link">案件一覧</a>
                @php
                    $totalUnreadCount = ($unreadApplicationCount ?? 0) + ($unreadScoutCount ?? 0);
                @endphp
                <a href="{{ route('freelancer.applications.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }}">
                    応募した案件
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('freelancer.scouts.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
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
        <aside class="sidebar" aria-label="設定メニュー">
            <div class="panel profile-card" >
                <div class="panel-title">プレビュー</div>
                <div class="profile-head">
                    <div class="big-avatar" id="preview-avatar">
                        @if($freelancer && $freelancer->icon_path)
                            <img src="{{ asset('storage/' . $freelancer->icon_path) }}" alt="プロフィール画像">
                        @else
                            {{ mb_substr($freelancer->display_name ?? ($user->email ?? 'U'), 0, 1) }}
                        @endif
                    </div>
                    <div style="min-width:0;">
                        <div class="name" id="preview-name">{{ $freelancer->display_name ?? '未入力' }}</div>
                        <div class="headline" id="preview-headline">{{ $freelancer->job_title ?? '未入力' }}</div>
                    </div>
                </div>
                <div class="skills" id="preview-skills" aria-label="スキル">
                    @if($freelancer && $freelancer->customSkills)
                        @foreach($freelancer->customSkills as $skill)
                            <span class="skill-tag">{{ $skill->name }}</span>
                        @endforeach
                    @endif
                </div>
                <div class="divider"></div>
                <div class="kv" aria-label="条件">
                    <div class="k">希望単価</div>
                    <div class="v" id="preview-rate">
                        @if($freelancer && ($freelancer->min_rate || $freelancer->max_rate))
                            @if($freelancer->min_rate && $freelancer->max_rate)
                                {{ $freelancer->min_rate }}〜{{ $freelancer->max_rate }}万円
                            @else
                                {{ $freelancer->min_rate ?? $freelancer->max_rate }}万円
                            @endif
                        @else
                            未設定
                        @endif
                    </div>
                    <div class="k">稼働</div>
                    <div class="v" id="preview-hours">
                        @if($freelancer && ($freelancer->min_hours_per_week || $freelancer->max_hours_per_week))
                            @if($freelancer->min_hours_per_week && $freelancer->max_hours_per_week)
                                週{{ $freelancer->min_hours_per_week }}〜{{ $freelancer->max_hours_per_week }}h
                            @else
                                週{{ $freelancer->min_hours_per_week ?? $freelancer->max_hours_per_week }}h
                            @endif
                        @else
                            未設定
                        @endif
                    </div>
                    <div class="k">日</div>
                    <div class="v" id="preview-days">
                        @if($freelancer && ($freelancer->hours_per_day || $freelancer->days_per_week))
                            @if($freelancer->hours_per_day && $freelancer->days_per_week)
                                {{ $freelancer->hours_per_day }}h/day・{{ $freelancer->days_per_week }}日/week
                            @else
                                {{ $freelancer->hours_per_day ?? '' }}{{ $freelancer->hours_per_day ? 'h/day' : '' }}{{ $freelancer->days_per_week ?? '' }}{{ $freelancer->days_per_week ? '日/week' : '' }}
                            @endif
                        @else
                            未設定
                        @endif
                    </div>
                </div>
                <p class="help" style="margin-top:1rem;">プロフィールが充実しているほどスカウトが届きやすくなります。</p>
            </div>
        </aside>

        <div class="content-area">
            <h1 class="page-title">プロフィール設定</h1>
            <p class="page-subtitle">プロフィール（メール/パスワード以外）を編集します。</p>

            <section class="panel" aria-label="プロフィール編集">
                <div class="panel-title">プロフィール</div>
                @if(session('success'))
                    <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #c3e6cb;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f5c6cb;">
                        {{ session('error') }}
                    </div>
                @endif
                <form class="form" action="{{ route('freelancer.profile.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="grid-2">
                        <div class="row">
                            <div class="label">表示名</div>
                            <input class="input @error('display_name') is-invalid @enderror" id="display_name" name="display_name" type="text" value="{{ old('display_name', $freelancer->display_name ?? '') }}">
                            @error('display_name')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="label">職種（自由入力）</div>
                            <input class="input @error('job_title') is-invalid @enderror" id="job_title" name="job_title" type="text" value="{{ old('job_title', $freelancer->job_title ?? '') }}">
                            @error('job_title')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">自己紹介</div>
                        <textarea class="textarea @error('bio') is-invalid @enderror" name="bio">{{ old('bio', $freelancer->bio ?? '') }}</textarea>
                        @error('bio')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="label">スキル（自由入力）</div>
                        <div class="help">複数入力できます。</div>
                        @php
                            $customSkills = old('custom_skills', $freelancer && $freelancer->customSkills ? $freelancer->customSkills->pluck('name')->toArray() : []);
                            // データがない場合は最低2つの空欄を表示
                            if (empty($customSkills)) {
                                $customSkills = ['', ''];
                            }
                            // 奇数個の場合は空欄を1つ追加して偶数にする（2列表示のため）
                            if (count($customSkills) % 2 !== 0) {
                                $customSkills[] = '';
                            }
                        @endphp
                        <div class="skills-container" id="skills-container">
                            @foreach(array_chunk($customSkills, 2) as $rowIndex => $row)
                                <div class="skill-input-row" data-row-index="{{ $rowIndex }}">
                                @foreach($row as $skillIndex => $skillValue)
                                    <div>
                                        <input class="input skill-input @error('custom_skills.' . ($rowIndex * 2 + $skillIndex)) is-invalid @enderror" name="custom_skills[]" type="text" value="{{ $skillValue }}" placeholder="例: Laravel" data-skill-input>
                                        @if($rowIndex > 0 || $skillIndex > 0)
                                            <button type="button" class="btn btn-remove remove-skill-btn" style="padding: 0.5rem 0.75rem; font-size: 0.8rem;" title="削除">×</button>
                                        @endif
                                    </div>
                                @endforeach
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline" id="add-skill-btn" style="margin-top:0.75rem;">スキルを追加</button>
                        @error('custom_skills.*')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="label">経験企業（任意）</div>
                        <textarea class="textarea @error('experience_companies') is-invalid @enderror" name="experience_companies">{{ old('experience_companies', $freelancer->experience_companies ?? '') }}</textarea>
                        @error('experience_companies')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="divider"></div>

                    <div class="panel-title" style="margin-bottom:1rem;">稼働条件</div>
                    <div class="grid-2">
                        <div class="row">
                            <div class="label">稼働可能時間（週）</div>
                            <div class="grid-2">
                                <input class="input @error('min_hours_per_week') is-invalid @enderror" id="min_hours_per_week" name="min_hours_per_week" type="number" value="{{ old('min_hours_per_week', $freelancer->min_hours_per_week ?? '') }}" placeholder="下限(h)">
                                <input class="input @error('max_hours_per_week') is-invalid @enderror" id="max_hours_per_week" name="max_hours_per_week" type="number" value="{{ old('max_hours_per_week', $freelancer->max_hours_per_week ?? '') }}" placeholder="上限(h)">
                            </div>
                            @error('min_hours_per_week')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                            @error('max_hours_per_week')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="label">稼働（day / week）</div>
                            <div class="grid-2">
                                <input class="input @error('hours_per_day') is-invalid @enderror" id="hours_per_day" name="hours_per_day" type="number" value="{{ old('hours_per_day', $freelancer->hours_per_day ?? '') }}" placeholder="h/day">
                                <input class="input @error('days_per_week') is-invalid @enderror" id="days_per_week" name="days_per_week" type="number" value="{{ old('days_per_week', $freelancer->days_per_week ?? '') }}" placeholder="日/week">
                            </div>
                            @error('hours_per_day')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                            @error('days_per_week')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid-2" style="margin-top:1.25rem; align-items: start;">
                        <div class="row">
                            <div class="label">働き方（自由入力）</div>
                            <textarea class="textarea @error('work_style_text') is-invalid @enderror" name="work_style_text">{{ old('work_style_text', $freelancer->work_style_text ?? '') }}</textarea>
                            @error('work_style_text')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row" style="align-items: start;">
                            <div class="label">希望単価（下限〜上限）</div>
                            <div class="grid-2" style="align-items: start;">
                                <input class="input @error('min_rate') is-invalid @enderror" id="min_rate" name="min_rate" type="number" value="{{ old('min_rate', $freelancer->min_rate ?? '') }}" placeholder="下限" min="0" step="1">
                                <input class="input @error('max_rate') is-invalid @enderror" id="max_rate" name="max_rate" type="number" value="{{ old('max_rate', $freelancer->max_rate ?? '') }}" placeholder="上限" min="0" step="1">
                            </div>
                            @error('min_rate')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                            @error('max_rate')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="panel-title" style="margin-bottom:1rem;">ポートフォリオ</div>
                    @php
                        $portfolioUrls = old('portfolio_urls', $freelancer && $freelancer->portfolios ? $freelancer->portfolios->pluck('url')->toArray() : []);
                        // データがない場合は最低2つの空欄を表示
                        if (empty($portfolioUrls)) {
                            $portfolioUrls = ['', ''];
                        }
                        // 奇数個の場合は空欄を1つ追加して偶数にする（2列表示のため）
                        if (count($portfolioUrls) % 2 !== 0) {
                            $portfolioUrls[] = '';
                        }
                    @endphp
                    <div class="portfolio-container" id="portfolio-container">
                        @foreach(array_chunk($portfolioUrls, 2) as $rowIndex => $row)
                            <div class="portfolio-input-row" data-row-index="{{ $rowIndex }}">
                                @foreach($row as $urlIndex => $urlValue)
                                    <div>
                                        <input class="input portfolio-input @error('portfolio_urls.' . ($rowIndex * 2 + $urlIndex)) is-invalid @enderror" name="portfolio_urls[]" type="url" value="{{ $urlValue }}" placeholder="https://...">
                                        @if($rowIndex > 0 || $urlIndex > 0)
                                            <button type="button" class="btn btn-remove remove-portfolio-btn" style="padding: 0.5rem 0.75rem; font-size: 0.8rem;" title="削除">×</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline" id="add-portfolio-btn" style="margin-top:0.75rem; width: 100%;">ポートフォリオURLを追加</button>
                    @error('portfolio_urls.*')
                    <span class="error-message">{{ $message }}</span>
                    @enderror

                    <div class="row" style="margin-top:1.25rem;">
                        <div class="label">ユーザーアイコン（任意）</div>
                        @if($freelancer && $freelancer->icon_path)
                            <div style="margin-bottom: 0.75rem;">
                                <img src="{{ asset('storage/' . $freelancer->icon_path) }}" alt="現在のアイコン" id="current-icon" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #e1e4e8;">
                                <div class="help" style="margin-top: 0.25rem;">現在のアイコン</div>
                            </div>
                        @endif
                        <input class="input @error('icon') is-invalid @enderror" id="icon" name="icon" type="file" accept="image/*">
                        @error('icon')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="help">画像を選択してください（最大5MB）。未選択の場合は現在のアイコンが維持されます。</div>
                    </div>

                    <div class="actions">
                        <a class="btn btn-secondary" href="{{ route('freelancer.jobs.index') }}" role="button">戻る</a>
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

        // カスタムスキル追加機能
        (function () {
            const addSkillBtn = document.getElementById('add-skill-btn');
            const skillsContainer = document.getElementById('skills-container');

            if (addSkillBtn && skillsContainer) {
                addSkillBtn.addEventListener('click', function() {
                    const rows = skillsContainer.querySelectorAll('.skill-input-row');
                    const lastRow = rows[rows.length - 1];
                    const inputsInLastRow = lastRow.querySelectorAll('.skill-input');
                    
                    // 最後の行に空きがある場合はそこに追加、なければ新しい行を作成
                    if (inputsInLastRow.length < 2) {
                        // 最後の行に追加
                        const wrapper = document.createElement('div');
                        
                        const newInput = document.createElement('input');
                        newInput.className = 'input skill-input';
                        newInput.name = 'custom_skills[]';
                        newInput.type = 'text';
                        newInput.placeholder = '例: Laravel';
                        newInput.setAttribute('data-skill-input', '');
                        newInput.addEventListener('input', updatePreview);
                        newInput.addEventListener('change', updatePreview);
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-remove remove-skill-btn';
                        removeBtn.style.cssText = 'padding: 0.5rem 0.75rem; font-size: 0.8rem;';
                        removeBtn.title = '削除';
                        removeBtn.textContent = '×';
                        removeBtn.addEventListener('click', function() {
                            wrapper.remove();
                            updatePreview();
                            // 行が空になったら行ごと削除
                            const remainingInputs = lastRow.querySelectorAll('.skill-input');
                            if (remainingInputs.length === 0) {
                                lastRow.remove();
                            }
                        });
                        
                        wrapper.appendChild(newInput);
                        wrapper.appendChild(removeBtn);
                        lastRow.appendChild(wrapper);
                    } else {
                        // 新しい行を作成
                        const newRow = document.createElement('div');
                        newRow.className = 'skill-input-row';
                        const rowIndex = rows.length;
                        newRow.setAttribute('data-row-index', rowIndex);
                        
                        const wrapper = document.createElement('div');
                        
                        const newInput = document.createElement('input');
                        newInput.className = 'input skill-input';
                        newInput.name = 'custom_skills[]';
                        newInput.type = 'text';
                        newInput.placeholder = '例: Laravel';
                        newInput.setAttribute('data-skill-input', '');
                        newInput.addEventListener('input', updatePreview);
                        newInput.addEventListener('change', updatePreview);
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-remove remove-skill-btn';
                        removeBtn.style.cssText = 'padding: 0.5rem 0.75rem; font-size: 0.8rem;';
                        removeBtn.title = '削除';
                        removeBtn.textContent = '×';
                        removeBtn.addEventListener('click', function() {
                            newRow.remove();
                            updatePreview();
                        });
                        
                        wrapper.appendChild(newInput);
                        wrapper.appendChild(removeBtn);
                        newRow.appendChild(wrapper);
                        skillsContainer.appendChild(newRow);
                    }
                });

                // 既存の削除ボタンにイベントリスナーを追加
                skillsContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-skill-btn')) {
                        const btn = e.target;
                        const wrapper = btn.parentElement;
                        const row = wrapper.closest('.skill-input-row');
                        
                        wrapper.remove();
                        updatePreview();
                        
                        // 行が空になったら行ごと削除（ただし最低1行は残す）
                        const remainingInputs = row.querySelectorAll('.skill-input');
                        if (remainingInputs.length === 0 && skillsContainer.querySelectorAll('.skill-input-row').length > 1) {
                            row.remove();
                        }
                    }
                });
            }
        })();

        // ポートフォリオURL追加機能
        (function () {
            const addPortfolioBtn = document.getElementById('add-portfolio-btn');
            const portfolioContainer = document.getElementById('portfolio-container');

            if (addPortfolioBtn && portfolioContainer) {
                addPortfolioBtn.addEventListener('click', function() {
                    const rows = portfolioContainer.querySelectorAll('.portfolio-input-row');
                    const lastRow = rows[rows.length - 1];
                    const inputsInLastRow = lastRow.querySelectorAll('.portfolio-input');
                    
                    // 最後の行に空きがある場合はそこに追加、なければ新しい行を作成
                    if (inputsInLastRow.length < 2) {
                        // 最後の行に追加
                        const wrapper = document.createElement('div');
                        
                        const newInput = document.createElement('input');
                        newInput.className = 'input portfolio-input';
                        newInput.name = 'portfolio_urls[]';
                        newInput.type = 'url';
                        newInput.placeholder = 'https://...';
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-remove remove-portfolio-btn';
                        removeBtn.style.cssText = 'padding: 0.5rem 0.75rem; font-size: 0.8rem;';
                        removeBtn.title = '削除';
                        removeBtn.textContent = '×';
                        removeBtn.addEventListener('click', function() {
                            wrapper.remove();
                            // 行が空になったら行ごと削除
                            const remainingInputs = lastRow.querySelectorAll('.portfolio-input');
                            if (remainingInputs.length === 0) {
                                lastRow.remove();
                            }
                        });
                        
                        wrapper.appendChild(newInput);
                        wrapper.appendChild(removeBtn);
                        lastRow.appendChild(wrapper);
                    } else {
                        // 新しい行を作成
                        const newRow = document.createElement('div');
                        newRow.className = 'portfolio-input-row';
                        const rowIndex = rows.length;
                        newRow.setAttribute('data-row-index', rowIndex);
                        
                        const wrapper = document.createElement('div');
                        
                        const newInput = document.createElement('input');
                        newInput.className = 'input portfolio-input';
                        newInput.name = 'portfolio_urls[]';
                        newInput.type = 'url';
                        newInput.placeholder = 'https://...';
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-remove remove-portfolio-btn';
                        removeBtn.style.cssText = 'padding: 0.5rem 0.75rem; font-size: 0.8rem;';
                        removeBtn.title = '削除';
                        removeBtn.textContent = '×';
                        removeBtn.addEventListener('click', function() {
                            newRow.remove();
                        });
                        
                        wrapper.appendChild(newInput);
                        wrapper.appendChild(removeBtn);
                        newRow.appendChild(wrapper);
                        portfolioContainer.appendChild(newRow);
                    }
                });

                // 既存の削除ボタンにイベントリスナーを追加
                portfolioContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-portfolio-btn')) {
                        const btn = e.target;
                        const wrapper = btn.parentElement;
                        const row = wrapper.closest('.portfolio-input-row');
                        
                        wrapper.remove();
                        
                        // 行が空になったら行ごと削除（ただし最低1行は残す）
                        const remainingInputs = row.querySelectorAll('.portfolio-input');
                        if (remainingInputs.length === 0 && portfolioContainer.querySelectorAll('.portfolio-input-row').length > 1) {
                            row.remove();
                        }
                    }
                });
            }
        })();

        // アイコンファイルプレビュー機能
        (function () {
            const iconInput = document.getElementById('icon');
            const previewAvatar = document.getElementById('preview-avatar');
            const currentIcon = document.getElementById('current-icon');
            const defaultAvatarText = '{{ mb_substr($freelancer->display_name ?? ($user->email ?? "U"), 0, 1) }}';

            if (iconInput && previewAvatar) {
                iconInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // 既存の画像を削除
                            const existingImg = previewAvatar.querySelector('img');
                            if (existingImg) {
                                existingImg.remove();
                            }
                            // テキストを削除
                            previewAvatar.textContent = '';
                            // 新しい画像を追加
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'プレビュー画像';
                            previewAvatar.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // ファイルが選択されていない場合、デフォルトに戻す
                        const existingImg = previewAvatar.querySelector('img');
                        if (existingImg) {
                            existingImg.remove();
                        }
                        // 現在のアイコンがある場合はそれを使用、なければテキスト
                        if (currentIcon) {
                            const img = document.createElement('img');
                            img.src = currentIcon.src;
                            img.alt = '現在のアイコン';
                            previewAvatar.appendChild(img);
                        } else {
                            previewAvatar.textContent = defaultAvatarText;
                        }
                    }
                });
            }
        })();

        // プレビュー更新機能
        const updatePreview = function () {
            // 表示名
            const displayNameInput = document.getElementById('display_name');
            const displayName = displayNameInput ? displayNameInput.value.trim() : '';
            const previewName = document.getElementById('preview-name');
            const previewAvatar = document.getElementById('preview-avatar');
            
            if (previewName) {
                previewName.textContent = displayName || '未入力';
            }
            
            if (previewAvatar && !previewAvatar.querySelector('img')) {
                const firstChar = displayName ? displayName.charAt(0) : '{{ mb_substr($freelancer->display_name ?? ($user->email ?? "U"), 0, 1) }}';
                previewAvatar.textContent = firstChar;
            }

            // 職種
            const jobTitleInput = document.getElementById('job_title');
            const jobTitle = jobTitleInput ? jobTitleInput.value.trim() : '';
            const previewHeadline = document.getElementById('preview-headline');
            if (previewHeadline) {
                previewHeadline.textContent = jobTitle || '未入力';
            }

            // スキル
            const skillInputs = document.querySelectorAll('input[data-skill-input]');
            const skillsContainer = document.getElementById('preview-skills');
            if (skillsContainer) {
                skillsContainer.innerHTML = '';
                skillInputs.forEach(input => {
                    const value = input.value.trim();
                    if (value) {
                        const tag = document.createElement('span');
                        tag.className = 'skill-tag';
                        tag.textContent = value;
                        skillsContainer.appendChild(tag);
                    }
                });
            }

            // 希望単価
            const minRateInput = document.getElementById('min_rate');
            const maxRateInput = document.getElementById('max_rate');
            const minRate = minRateInput ? minRateInput.value : '';
            const maxRate = maxRateInput ? maxRateInput.value : '';
            const previewRate = document.getElementById('preview-rate');
            if (previewRate) {
                if (minRate || maxRate) {
                    const rateText = minRate && maxRate ? `${minRate}〜${maxRate}万円` : (minRate || maxRate ? `${minRate || maxRate}万円` : '未設定');
                    previewRate.textContent = rateText;
                } else {
                    previewRate.textContent = '未設定';
                }
            }

            // 稼働時間
            const minHoursInput = document.getElementById('min_hours_per_week');
            const maxHoursInput = document.getElementById('max_hours_per_week');
            const minHours = minHoursInput ? minHoursInput.value : '';
            const maxHours = maxHoursInput ? maxHoursInput.value : '';
            const previewHours = document.getElementById('preview-hours');
            if (previewHours) {
                if (minHours || maxHours) {
                    const hoursText = minHours && maxHours ? `週${minHours}〜${maxHours}h` : (minHours || maxHours ? `週${minHours || maxHours}h` : '未設定');
                    previewHours.textContent = hoursText;
                } else {
                    previewHours.textContent = '未設定';
                }
            }

            // 日数
            const hoursPerDayInput = document.getElementById('hours_per_day');
            const daysPerWeekInput = document.getElementById('days_per_week');
            const hoursPerDay = hoursPerDayInput ? hoursPerDayInput.value : '';
            const daysPerWeek = daysPerWeekInput ? daysPerWeekInput.value : '';
            const previewDays = document.getElementById('preview-days');
            if (previewDays) {
                if (hoursPerDay || daysPerWeek) {
                    const daysText = hoursPerDay && daysPerWeek ? `${hoursPerDay}h/day・${daysPerWeek}日/week` : (hoursPerDay || daysPerWeek || '未設定');
                    previewDays.textContent = daysText;
                } else {
                    previewDays.textContent = '未設定';
                }
            }
        };

        // フォーム入力時にプレビューを更新
        (function () {
            const formInputs = document.querySelectorAll('#display_name, #job_title, #min_rate, #max_rate, #min_hours_per_week, #max_hours_per_week, #hours_per_day, #days_per_week, input[data-skill-input]');
            formInputs.forEach(input => {
                input.addEventListener('input', updatePreview);
                input.addEventListener('change', updatePreview);
            });

            // 初期プレビュー更新
            updatePreview();
        })();
    </script>
</body>
</html>
