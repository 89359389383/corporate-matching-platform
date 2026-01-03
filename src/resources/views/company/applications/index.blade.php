<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募された案件（企業）- AITECH</title>
    <style>
        :root { --header-height: 104px; --header-height-mobile: 91px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 97.5%; }
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
            gap: 3rem;
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
            font-size: 1.1rem;
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
        .user-menu { display: flex; align-items: center; position: absolute; right: 0; top: 50%; transform: translateY(-50%); }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; cursor: pointer; transition: all 0.15s ease;
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
        .title { font-size: 1.4rem; font-weight: 900; line-height: 1.2; margin-bottom: 0.25rem; }
        .sub { color: #586069; font-weight: 700; font-size: 1rem; }
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
        .meta-value { font-weight: 900; color: #24292e; white-space: nowrap; font-size: 1.05rem; }
        .meta-value.skills { white-space: normal; word-break: break-word; line-height: 1.6; }
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
                @php
                    $totalUnreadCount = ($unreadApplicationCount ?? 0) + ($unreadScoutCount ?? 0);
                @endphp
                <a href="{{ route('company.applications.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }} active">
                    応募された案件
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('company.scouts.index') }}" class="nav-link {{ $totalUnreadCount > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($totalUnreadCount > 0)
                        <span class="badge">{{ $totalUnreadCount }}</span>
                    @endif
                </a>
            </nav>
            <div class="user-menu">
                <div class="dropdown" id="userDropdown">
                    <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">{{ $userInitial ?? '企' }}</button>
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
            <a href="{{ route('company.applications.index', ['status' => 'pending']) }}" class="tab {{ $status === 'pending' ? 'is-active' : '' }}" role="tab" aria-selected="{{ $status === 'pending' ? 'true' : 'false' }}" aria-controls="tab-open" id="tabOpenBtn">応募中</a>
            <a href="{{ route('company.applications.index', ['status' => 'closed']) }}" class="tab {{ $status === 'closed' ? 'is-active' : '' }}" role="tab" aria-selected="{{ $status === 'closed' ? 'true' : 'false' }}" aria-controls="tab-closed" id="tabClosedBtn">終了</a>
        </div>

        <section id="tab-open" role="tabpanel" aria-labelledby="tabOpenBtn" {{ $status === 'closed' ? 'hidden' : '' }}>
            <div class="list">
                @forelse($applications as $application)
                    @php
                        $job = $application->job;
                        $freelancer = $application->freelancer;
                        $company = $job->company ?? null;
                        
                        // ステータス表示用
                        $statusText = '';
                        if ($application->status === \App\Models\Application::STATUS_PENDING) {
                            $statusText = '未対応';
                        } elseif ($application->status === \App\Models\Application::STATUS_IN_PROGRESS) {
                            $statusText = '対応中';
                        } else {
                            $statusText = 'クローズ';
                        }
                        
                        // 報酬表示用
                        $rewardText = '';
                        if ($job->reward_type === 'monthly') {
                            $rewardText = number_format($job->min_rate / 10000, 0) . '〜' . number_format($job->max_rate / 10000, 0) . '万';
                        } else {
                            $rewardText = number_format($job->min_rate) . '〜' . number_format($job->max_rate) . '円/時';
                        }
                        
                        // フリーランス名の最初の文字（アバター用）
                        $freelancerInitial = mb_substr($freelancer->display_name ?? '未', 0, 1);
                        
                        // チャットURL
                        $chatUrl = $application->thread 
                            ? route('company.threads.show', ['thread' => $application->thread])
                            : '#';
                    @endphp
                    <article class="card">
                        <div class="top">
                            <div style="min-width:0;">
                                <div class="title">{{ $job->title }}</div>
                                <div class="sub">{{ $company->name ?? '企業名不明' }}</div>
                                <div class="row" style="margin-top:0.75rem;">
                                    <span class="pill status">応募ステータス：{{ $statusText }}</span>
                                    @if($application->is_unread ?? false)
                                        <span class="pill unread">未読</span>
                                    @else
                                        <span class="pill">未読なし</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row" aria-label="応募者">
                                <span class="avatar" aria-hidden="true">{{ $freelancerInitial }}</span>
                                <span style="font-weight:900;">{{ $freelancer->display_name ?? '名前不明' }}</span>
                            </div>
                        </div>
                        <div class="desc">{{ Str::limit($application->message ?? $job->description, 100) }}</div>
                        <div class="meta" aria-label="案件情報">
                            <div class="meta-item"><div class="meta-label">報酬</div><div class="meta-value">{{ $rewardText }}</div></div>
                            <div class="meta-item"><div class="meta-label">稼働</div><div class="meta-value">{{ $job->work_time_text }}</div></div>
                            @if($job->required_skills_text)
                                <div class="meta-item"><div class="meta-label">スキル</div><div class="meta-value skills">{{ $job->required_skills_text }}</div></div>
                            @endif
                        </div>
                        <div class="actions">
                            @if($application->thread)
                                <a href="{{ $chatUrl }}" class="btn btn-primary">チャットへ</a>
                            @else
                                <span class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed;">チャット未開始</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; padding: 3rem; color: #586069;">
                        <p style="font-size: 1.1rem; font-weight: 700;">応募がありません</p>
                    </div>
                @endforelse
            </div>
            
            @if($applications->hasPages())
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    {{ $applications->links() }}
                </div>
            @endif
        </section>

        <section id="tab-closed" role="tabpanel" aria-labelledby="tabClosedBtn" {{ $status === 'pending' ? 'hidden' : '' }}>
            <div class="list">
                @forelse($applications as $application)
                    @php
                        $job = $application->job;
                        $freelancer = $application->freelancer;
                        $company = $job->company ?? null;
                        
                        // ステータス表示用
                        $statusText = 'クローズ';
                        
                        // 報酬表示用
                        $rewardText = '';
                        if ($job->reward_type === 'monthly') {
                            $rewardText = number_format($job->min_rate / 10000, 0) . '〜' . number_format($job->max_rate / 10000, 0) . '万';
                        } else {
                            $rewardText = number_format($job->min_rate) . '〜' . number_format($job->max_rate) . '円/時';
                        }
                        
                        // フリーランス名の最初の文字（アバター用）
                        $freelancerInitial = mb_substr($freelancer->display_name ?? '未', 0, 1);
                        
                        // チャットURL
                        $chatUrl = $application->thread 
                            ? route('company.threads.show', ['thread' => $application->thread])
                            : '#';
                    @endphp
                    <article class="card">
                        <div class="top">
                            <div style="min-width:0;">
                                <div class="title">{{ $job->title }}</div>
                                <div class="sub">{{ $company->name ?? '企業名不明' }}</div>
                                <div class="row" style="margin-top:0.75rem;">
                                    <span class="pill status">応募ステータス：{{ $statusText }}</span>
                                    @if($application->is_unread ?? false)
                                        <span class="pill unread">未読</span>
                                    @else
                                        <span class="pill">未読なし</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row" aria-label="応募者">
                                <span class="avatar" aria-hidden="true">{{ $freelancerInitial }}</span>
                                <span style="font-weight:900;">{{ $freelancer->display_name ?? '名前不明' }}</span>
                            </div>
                        </div>
                        <div class="desc">{{ Str::limit($application->message ?? $job->description, 100) }}</div>
                        <div class="meta" aria-label="案件情報">
                            <div class="meta-item"><div class="meta-label">報酬</div><div class="meta-value">{{ $rewardText }}</div></div>
                            <div class="meta-item"><div class="meta-label">稼働</div><div class="meta-value">{{ $job->work_time_text }}</div></div>
                            @if($job->required_skills_text)
                                <div class="meta-item"><div class="meta-label">スキル</div><div class="meta-value skills">{{ $job->required_skills_text }}</div></div>
                            @endif
                        </div>
                        <div class="actions">
                            <a href="{{ route('freelancer.jobs.show', ['job' => $job->id]) }}" class="btn btn-secondary">詳細</a>
                            @if($application->thread)
                                <a href="{{ $chatUrl }}" class="btn btn-primary">チャット履歴</a>
                            @else
                                <span class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed;">チャット未開始</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; padding: 3rem; color: #586069;">
                        <p style="font-size: 1.1rem; font-weight: 700;">終了した応募がありません</p>
                    </div>
                @endforelse
            </div>
            
            @if($applications->hasPages())
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    {{ $applications->links() }}
                </div>
            @endif
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
