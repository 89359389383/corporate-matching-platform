<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定 - AITECH</title>
    <style>
        /* ヘッダー共通スタイル（省略） */
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="header" role="banner">
        <div class="header-content">
            <div class="header-left">
                <div class="logo" aria-hidden="true">
                    <div class="logo-text">複業AI</div>
                </div>
            </div>
            <nav class="nav-links" role="navigation" aria-label="法人ナビゲーション">
                <a href="{{ route('corporate.jobs.index') }}" class="nav-link">案件一覧</a>
                @php
                    $appUnread = ($unreadApplicationCount ?? 0);
                    $scoutUnread = ($unreadScoutCount ?? 0);
                @endphp
                <a href="{{ route('corporate.applications.index') }}" class="nav-link {{ Request::routeIs('corporate.applications.*') ? 'active' : '' }} {{ $appUnread > 0 ? 'has-badge' : '' }}">
                    応募した案件
                    @if($appUnread > 0)
                        <span class="badge" aria-live="polite">{{ $appUnread }}</span>
                    @endif
                </a>
                <a href="{{ route('corporate.scouts.index') }}" class="nav-link {{ Request::routeIs('corporate.scouts.*') ? 'active' : '' }} {{ $scoutUnread > 0 ? 'has-badge' : '' }}">
                    スカウト
                    @if($scoutUnread > 0)
                        <span class="badge" aria-hidden="false">{{ $scoutUnread }}</span>
                    @endif
                </a>
            </nav>
            <div class="header-right" role="region" aria-label="ユーザー">
                <div class="user-menu">
                    <div class="dropdown" id="userDropdown">
                        <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">
                            @if(isset($corporate) && $corporate && $corporate->icon_path)
                                <img src="{{ asset('storage/' . $corporate->icon_path) }}" alt="プロフィール画像" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                {{ $userInitial ?? 'U' }}
                            @endif
                        </button>
                        <div class="dropdown-content" id="userDropdownMenu" role="menu" aria-label="ユーザーメニュー">
                            <a href="{{ route('corporate.profile.settings') }}" class="dropdown-item" role="menuitem">プロフィール設定</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('auth.logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item" role="menuitem">ログアウト</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10 flex flex-col lg:flex-row gap-6 lg:gap-8">
        <aside class="sidebar w-full lg:w-80 lg:sticky lg:top-[calc(var(--header-height)+1.5rem)]" aria-label="設定メニュー">
            <div class="panel profile-card" >
                <div class="panel-title">プレビュー</div>
                <div class="profile-head">
                    <div class="big-avatar" id="preview-avatar">
                        @if($corporate && $corporate->icon_path)
                            <img src="{{ asset('storage/' . $corporate->icon_path) }}" alt="プロフィール画像">
                        @else
                            {{ mb_substr($corporate->display_name ?? ($user->email ?? 'U'), 0, 1) }}
                        @endif
                    </div>
                    <div style="min-width:0;">
                        <div class="name" id="preview-name">{{ $corporate->display_name ?? '未入力' }}</div>
                        <div class="headline" id="preview-headline">{{ $corporate->job_title ?? '未入力' }}</div>
                    </div>
                </div>
                <div class="skills" id="preview-skills" aria-label="スキル">
                    @if($corporate && $corporate->customSkills)
                        @foreach($corporate->customSkills as $skill)
                            <span class="skill-tag">{{ $skill->name }}</span>
                        @endforeach
                    @endif
                </div>
                <div class="divider"></div>
                <div class="kv" aria-label="条件">
                    <div class="k">希望単価</div>
                    <div class="v" id="preview-rate">
                        @if($corporate && ($corporate->min_rate || $corporate->max_rate))
                            @if($corporate->min_rate && $corporate->max_rate)
                                {{ $corporate->min_rate }}〜{{ $corporate->max_rate }}万円
                            @else
                                {{ $corporate->min_rate ?? $corporate->max_rate }}万円
                            @endif
                        @else
                            未設定
                        @endif
                    </div>
                    <div class="k">稼働</div>
                    <div class="v" id="preview-hours">
                        @if($corporate && ($corporate->min_hours_per_week || $corporate->max_hours_per_week))
                            @if($corporate->min_hours_per_week && $corporate->max_hours_per_week)
                                週{{ $corporate->min_hours_per_week }}〜{{ $corporate->max_hours_per_week }}h
                            @else
                                週{{ $corporate->min_hours_per_week ?? $corporate->max_hours_per_week }}h
                            @endif
                        @else
                            未設定
                        @endif
                    </div>
                    <div class="k">日</div>
                    <div class="v" id="preview-days">
                        @if($corporate && ($corporate->hours_per_day || $corporate->days_per_week))
                            @if($corporate->hours_per_day && $corporate->days_per_week)
                                {{ $corporate->hours_per_day }}h/day・{{ $corporate->days_per_week }}日/week
                            @else
                                {{ $corporate->hours_per_day ?? '' }}{{ $corporate->hours_per_day ? 'h/day' : '' }}{{ $corporate->days_per_week ?? '' }}{{ $corporate->days_per_week ? '日/week' : '' }}
                            @endif
                        @else
                            未設定
                        @endif
                    </div>
                </div>
                <p class="help" style="margin-top:1rem;">プロフィールが充実しているほどスカウトが届きやすくなります。</p>
            </div>
        </aside>

        <div class="content-area flex-1 min-w-0">
            <h1 class="page-title">プロフィール設定</h1>
            @include('partials.error-panel')
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
                <form class="form" action="{{ route('corporate.profile.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="grid-2 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div class="row">
                            <div class="label">表示名</div>
                            <input class="input @error('display_name') is-invalid @enderror" id="display_name" name="display_name" type="text" value="{{ old('display_name', $corporate->display_name ?? '') }}">
                            @error('display_name')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="label">職種（自由入力）</div>
                            <input class="input @error('job_title') is-invalid @enderror" id="job_title" name="job_title" type="text" value="{{ old('job_title', $corporate->job_title ?? '') }}">
                            @error('job_title')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">自己紹介</div>
                        <textarea class="textarea @error('bio') is-invalid @enderror" id="bio" name="bio" placeholder="あなたの経験や得意分野について教えてください">{{ old('bio', $corporate->bio ?? '') }}</textarea>
                        @error('bio')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="label">スキル（カンマ区切りで複数入力）</div>
                        @php
                            $skillNames = [];
                            if (isset($corporate) && $corporate) {
                                if ($corporate->skills) {
                                    $skillNames = array_merge($skillNames, $corporate->skills->pluck('name')->toArray());
                                }
                                if ($corporate->customSkills) {
                                    $skillNames = array_merge($skillNames, $corporate->customSkills->pluck('name')->toArray());
                                }
                            }
                            $skillInputValue = old('skills', implode(', ', $skillNames));
                        @endphp
                        <input class="input @error('skills') is-invalid @enderror" id="skills" name="skills" type="text" value="{{ $skillInputValue }}" placeholder="例: PHP, Laravel, JavaScript">
                        @error('skills')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="label">希望単価（万円/月）</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input class="input @error('min_rate') is-invalid @enderror" id="min_rate" name="min_rate" type="number" placeholder="下限" value="{{ old('min_rate', $corporate->min_rate ?? '') }}" min="0">
                                @error('min_rate')
                                <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <input class="input @error('max_rate') is-invalid @enderror" id="max_rate" name="max_rate" type="number" placeholder="上限" value="{{ old('max_rate', $corporate->max_rate ?? '') }}" min="0">
                                @error('max_rate')
                                <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">稼働時間（時間/週）</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input class="input @error('min_hours_per_week') is-invalid @enderror" id="min_hours_per_week" name="min_hours_per_week" type="number" placeholder="下限" value="{{ old('min_hours_per_week', $corporate->min_hours_per_week ?? '') }}" min="0">
                                @error('min_hours_per_week')
                                <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <input class="input @error('max_hours_per_week') is-invalid @enderror" id="max_hours_per_week" name="max_hours_per_week" type="number" placeholder="上限" value="{{ old('max_hours_per_week', $corporate->max_hours_per_week ?? '') }}" min="0">
                                @error('max_hours_per_week')
                                <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">1日の稼働時間</div>
                        <input class="input @error('hours_per_day') is-invalid @enderror" id="hours_per_day" name="hours_per_day" type="number" value="{{ old('hours_per_day', $corporate->hours_per_day ?? '') }}" min="0" placeholder="例: 8">
                        @error('hours_per_day')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="label">週の稼働日数</div>
                        <input class="input @error('days_per_week') is-invalid @enderror" id="days_per_week" name="days_per_week" type="number" value="{{ old('days_per_week', $corporate->days_per_week ?? '') }}" min="0" max="7" placeholder="例: 5">
                        @error('days_per_week')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="label">プロフィール画像</div>
                        <input type="file" class="file-input @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/*">
                        @if($corporate && $corporate->icon_path)
                            <p style="margin-top: 0.5rem; font-size: 0.9rem; color: #6a737d;">現在の画像を変更する場合のみ選択してください。</p>
                        @endif
                        @error('icon')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="actions flex flex-col md:flex-row justify-end gap-3 md:gap-4 border-t border-slate-200 pt-4">
                        <button class="btn btn-primary w-full md:w-auto" type="submit">更新</button>
                    </div>
                </form>
            </section>
        </div>
    </main>

    <script>
        // プレビュー用スクリプト（freelancer→corporateに合わせて変数名を変えています）
        (function () {
            const displayName = document.getElementById('display_name');
            const jobTitle = document.getElementById('job_title');
            const skills = document.getElementById('skills');
            const minRate = document.getElementById('min_rate');
            const maxRate = document.getElementById('max_rate');
            const minHours = document.getElementById('min_hours_per_week');
            const maxHours = document.getElementById('max_hours_per_week');
            const hoursPerDay = document.getElementById('hours_per_day');
            const daysPerWeek = document.getElementById('days_per_week');

            const previewName = document.getElementById('preview-name');
            const previewHeadline = document.getElementById('preview-headline');
            const previewSkills = document.getElementById('preview-skills');
            const previewRate = document.getElementById('preview-rate');
            const previewHours = document.getElementById('preview-hours');
            const previewDays = document.getElementById('preview-days');

            function updatePreview() {
                if (displayName && previewName) { previewName.textContent = displayName.value || '未入力'; }
                if (jobTitle && previewHeadline) { previewHeadline.textContent = jobTitle.value || '未入力'; }
                if (skills && previewSkills) {
                    const skillText = skills.value;
                    previewSkills.innerHTML = '';
                    if (skillText.trim()) {
                        const skillArray = skillText.split(',').map(s => s.trim()).filter(s => s);
                        skillArray.forEach(skill => {
                            const tag = document.createElement('span');
                            tag.className = 'skill-tag';
                            tag.textContent = skill;
                            previewSkills.appendChild(tag);
                        });
                    }
                }
                if (minRate && maxRate && previewRate) {
                    const min = minRate.value; const max = maxRate.value;
                    if (min && max) previewRate.textContent = min + '〜' + max + '万円';
                    else if (min || max) previewRate.textContent = (min || max) + '万円';
                    else previewRate.textContent = '未設定';
                }
                if (minHours && maxHours && previewHours) {
                    const min = minHours.value; const max = maxHours.value;
                    if (min && max) previewHours.textContent = '週' + min + '〜' + max + 'h';
                    else if (min || max) previewHours.textContent = '週' + (min || max) + 'h';
                    else previewHours.textContent = '未設定';
                }
                if (hoursPerDay && daysPerWeek && previewDays) {
                    const hours = hoursPerDay.value; const days = daysPerWeek.value;
                    if (hours && days) previewDays.textContent = hours + 'h/day・' + days + '日/week';
                    else if (hours || days) previewDays.textContent = (hours ? hours + 'h/day' : '') + (days ? days + '日/week' : '');
                    else previewDays.textContent = '未設定';
                }
            }

            [displayName, jobTitle, skills, minRate, maxRate, minHours, maxHours, hoursPerDay, daysPerWeek].forEach(el => { if (el) el.addEventListener('input', updatePreview); });
            updatePreview();
        })();
    </script>
</body>
</html>
