<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリーランス一覧（企業）- AITECH</title>
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
            font-size: 0.9rem;
            padding: 0.65rem 1.1rem;
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
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none;
            padding: 0;
            appearance: none;
        }
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
        .sidebar.right { width: 380px; }
        .content-area { flex: 1; min-width: 0; }

        /* Panels / Inputs */
        .panel {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            margin-bottom: 2rem;
            min-width: 0;
            overflow: hidden;
        }
        .panel h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #24292e;
            letter-spacing: -0.01em;
        }
        .field { margin-bottom: 1.25rem; }
        .field label {
            display: block;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #586069;
            font-size: 0.9rem;
        }
        .input, .textarea, .select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
            background-color: #fafbfc;
        }
        .textarea { min-height: 140px; resize: vertical; }
        .input:focus, .textarea:focus, .select:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
            background-color: white;
        }
        .btn {
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
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
            white-space: nowrap;
        }
        .btn-primary { background-color: #0366d6; color: white; }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3); }
        .btn-secondary { background-color: #586069; color: white; }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }

        /* Cards */
        .page-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 2rem;
            color: #24292e;
            letter-spacing: -0.025em;
        }
        .list { display: grid; gap: 1.5rem; }
        .card {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            transition: all 0.2s ease;
            border: 1px solid #e1e4e8;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            min-width: 0;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08); }
        .card.is-selected { outline: 3px solid rgba(3, 102, 214, 0.2); border-color: rgba(3,102,214,0.35); }
        .row { display: flex; gap: 1rem; align-items: flex-start; min-width: 0; }
        .avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            flex: 0 0 auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
        }
        .name { font-size: 1.1rem; font-weight: 800; line-height: 1.2; margin-bottom: 0.25rem; word-wrap: break-word; overflow-wrap: break-word; }
        .sub { color: #586069; font-weight: 600; font-size: 0.85rem; word-wrap: break-word; overflow-wrap: break-word; }
        .desc { color: #586069; margin-top: 0.75rem; line-height: 1.6; word-wrap: break-word; overflow-wrap: break-word; }
        .tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.85rem; }
        .tag { background-color: #f1f8ff; color: #0366d6; padding: 0.3rem 0.75rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; border: 1px solid #c8e1ff; }
        .meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
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
            min-width: 0;
            overflow: hidden;
        }
        .meta-label { font-size: 0.7rem; color: #6a737d; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; flex-shrink: 0; }
        .meta-value { font-weight: 800; color: #24292e; word-wrap: break-word; overflow-wrap: break-word; min-width: 0; text-align: right; }

        /* Right detail */
        .detail-title { font-size: 0.95rem; font-weight: 800; margin-top: 0.75rem; margin-bottom: 0.5rem; }
        .detail-actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; flex-wrap: wrap; }
        .link { color: #0366d6; text-decoration: none; font-weight: 800; word-break: break-all; overflow-wrap: break-word; display: block; }
        .link:hover { text-decoration: underline; }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-content { padding: 2rem; gap: 2rem; }
            .sidebar { width: 260px; }
            .sidebar.right { width: 320px; }
            .nav-links { gap: 1rem; }
            .nav-link { font-size: 0.85rem; padding: 0.55rem 0.85rem; }
            .nav-link.has-badge { padding-right: 2.4rem; }
        }
        @media (max-width: 920px) {
            .header-content { height: var(--header-height-mobile); }
            .nav-links { position: static; left: auto; transform: none; justify-content: flex-start; }
            .user-menu { position: static; transform: none; margin-left: auto; }
            .main-content { flex-direction: column; padding: 1.5rem; }
            .sidebar, .sidebar.right { width: 100%; position: static; top: auto; order: -1; }
            .meta { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <nav class="nav-links">
                <a href="{{ route('company.freelancers.index') }}" class="nav-link active">フリーランス一覧</a>
                <a href="{{ route('company.jobs.index') }}" class="nav-link">案件一覧</a>
                <a href="{{ route('company.applications.index') }}" class="nav-link has-badge">応募された案件 <span class="badge">3</span></a>
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
        <aside class="sidebar">
            <div class="panel">
                <h3>検索条件</h3>
                <form method="GET" action="{{ route('company.freelancers.index') }}">
                    <div class="field">
                        <label for="keyword">フリーワード</label>
                        <input id="keyword" name="keyword" class="input" type="text" placeholder="職種 / スキル / 自己紹介 / 希望単価 など" value="{{ old('keyword', $keyword ?? '') }}">
                    </div>
                    <button class="btn btn-primary" type="submit">検索</button>
                </form>
            </div>
        </aside>

        <section class="content-area">
            <h1 class="page-title">フリーランス一覧</h1>
            <div class="list" id="freelancerList" aria-label="フリーランス一覧">
                @forelse($freelancers as $index => $freelancer)
                    @php
                        $avatarText = mb_substr($freelancer->display_name, 0, 1);
                        $allSkills = $freelancer->skills->pluck('name')->merge($freelancer->customSkills->pluck('name'));
                        $workHours = $freelancer->min_hours_per_week . '〜' . $freelancer->max_hours_per_week . 'h';
                        $rateText = ($freelancer->min_rate / 10000) . '〜' . ($freelancer->max_rate / 10000) . '万';
                    @endphp
                    <article class="card {{ $index === 0 ? 'is-selected' : '' }}" tabindex="0" role="button" data-id="{{ $freelancer->id }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
                        <div class="row">
                            <div class="avatar" aria-hidden="true">{{ $avatarText }}</div>
                            <div style="min-width:0; flex: 1; overflow: hidden;">
                                <div class="name">{{ $freelancer->display_name }}</div>
                                <div class="sub">{{ $freelancer->job_title ?? '' }}</div>
                                @if($allSkills->isNotEmpty())
                                    <div class="tags" aria-label="スキル">
                                        @foreach($allSkills as $skill)
                                            <span class="tag">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                @if($freelancer->bio)
                                    <div class="desc">{{ $freelancer->bio }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="meta" aria-label="ワークスタイル">
                            <div class="meta-item">
                                <div class="meta-label">稼働</div>
                                <div class="meta-value">週{{ $workHours }}</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">希望単価</div>
                                <div class="meta-value">{{ $rateText }}</div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; padding: 3rem; color: #586069;">
                        <p>フリーランスが見つかりませんでした。</p>
                    </div>
                @endforelse
            </div>
            @if($freelancers->hasPages())
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    {{ $freelancers->links() }}
                </div>
            @endif
        </section>

        <aside class="sidebar right">
            <div class="panel" id="detailPanel" aria-live="polite">
                <h3>選択中のフリーランス</h3>
                <div id="detailContent" style="display: none;">
                    <div class="row">
                        <div class="avatar" id="dAvatar" aria-hidden="true"></div>
                        <div style="min-width:0; flex: 1; overflow: hidden;">
                            <div class="name" id="dName"></div>
                            <div class="sub" id="dRole"></div>
                        </div>
                    </div>

                    <div class="detail-title">自己紹介</div>
                    <div class="desc" id="dBio"></div>

                    <div class="detail-title">スキル</div>
                    <div class="tags" id="dSkills"></div>

                    <div class="detail-title">ワークスタイル</div>
                    <div class="meta" id="dMeta"></div>

                    <div class="detail-title">ポートフォリオ</div>
                    <div class="desc" id="dPortfolio" style="word-break: break-all; overflow-wrap: break-word;"></div>

                    <div class="detail-actions">
                        <a class="btn btn-primary" id="dScoutLink" href="{{ $freelancers->isNotEmpty() ? route('company.scouts.create', ['freelancer_id' => $freelancers->first()->id]) : '#' }}">スカウト</a>
                    </div>
                </div>
                <div id="detailEmpty" style="text-align: center; padding: 2rem; color: #586069;">
                    <p>フリーランスを選択してください</p>
                </div>
            </div>
        </aside>
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
            // フリーランスデータをJSON形式で埋め込む
            @php
                $freelancerDataArray = $freelancers->map(function($freelancer) {
                    $allSkills = $freelancer->skills->pluck('name')->merge($freelancer->customSkills->pluck('name'));
                    $workHours = $freelancer->min_hours_per_week . '〜' . $freelancer->max_hours_per_week . 'h';
                    $rateText = ($freelancer->min_rate / 10000) . '〜' . ($freelancer->max_rate / 10000) . '万';
                    
                    return [
                        'id' => $freelancer->id,
                        'avatar' => mb_substr($freelancer->display_name, 0, 1),
                        'name' => $freelancer->display_name,
                        'role' => $freelancer->job_title ?? '',
                        'bio' => $freelancer->bio ?? '',
                        'skills' => $allSkills->toArray(),
                        'workHours' => $workHours,
                        'rateText' => $rateText,
                        'portfolios' => $freelancer->portfolios->pluck('url')->toArray(),
                    ];
                })->values()->toArray();
            @endphp
            const freelancerData = @json($freelancerDataArray);

            const list = document.getElementById('freelancerList');
            const detailContent = document.getElementById('detailContent');
            const detailEmpty = document.getElementById('detailEmpty');
            const dAvatar = document.getElementById('dAvatar');
            const dName = document.getElementById('dName');
            const dRole = document.getElementById('dRole');
            const dBio = document.getElementById('dBio');
            const dSkills = document.getElementById('dSkills');
            const dMeta = document.getElementById('dMeta');
            const dPortfolio = document.getElementById('dPortfolio');
            const dScoutLink = document.getElementById('dScoutLink');
            
            if (!list || !detailContent || !detailEmpty || !dAvatar || !dName || !dRole || !dBio || !dSkills || !dMeta || !dPortfolio || !dScoutLink) return;

            // データをIDでマッピング
            const dataMap = {};
            freelancerData.forEach(item => {
                dataMap[item.id] = item;
            });

            const render = (id) => {
                const x = dataMap[id];
                if (!x) {
                    detailContent.style.display = 'none';
                    detailEmpty.style.display = 'block';
                    return;
                }
                
                detailContent.style.display = 'block';
                detailEmpty.style.display = 'none';
                
                dAvatar.textContent = x.avatar;
                dName.textContent = x.name;
                dRole.textContent = x.role;
                dBio.textContent = x.bio || '（未設定）';
                dSkills.innerHTML = x.skills.length > 0 
                    ? x.skills.map(s => `<span class="tag">${s}</span>`).join('')
                    : '<span style="color: #586069;">（未設定）</span>';
                dMeta.innerHTML = `
                    <div class="meta-item">
                        <div class="meta-label">稼働</div>
                        <div class="meta-value">週${x.workHours}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">希望単価</div>
                        <div class="meta-value">${x.rateText}</div>
                    </div>
                `;
                
                if (x.portfolios && x.portfolios.length > 0) {
                    dPortfolio.innerHTML = x.portfolios.map(url => 
                        `<a class="link" href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>`
                    ).join('<br>');
                } else {
                    dPortfolio.innerHTML = '<span style="color: #586069;">（未設定）</span>';
                }
                
                dScoutLink.href = '{{ route("company.scouts.create") }}?freelancer_id=' + id;
            };

            const selectCard = (card) => {
                list.querySelectorAll('.card').forEach((el) => {
                    el.classList.remove('is-selected');
                    el.setAttribute('aria-pressed', 'false');
                });
                card.classList.add('is-selected');
                card.setAttribute('aria-pressed', 'true');
                render(parseInt(card.getAttribute('data-id')));
            };

            // 初期表示：最初のカードを選択
            const firstCard = list.querySelector('.card');
            if (firstCard) {
                selectCard(firstCard);
            }

            list.addEventListener('click', (e) => {
                const card = e.target.closest('.card');
                if (!card) return;
                selectCard(card);
            });
            list.addEventListener('keydown', (e) => {
                if (e.key !== 'Enter' && e.key !== ' ') return;
                const card = e.target.closest('.card');
                if (!card) return;
                e.preventDefault();
                selectCard(card);
            });
        })();
    </script>
</body>
</html>
