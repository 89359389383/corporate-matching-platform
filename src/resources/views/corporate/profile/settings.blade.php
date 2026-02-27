<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定 - AITECH</title>
    @include('partials.corporate-header-style')
    <style>
        /* Profile settings page styles */
        :root {
            --header-height: 72px;
            --header-height-mobile: 72px;
            --header-height-sm: 72px;         /* sm */
            --header-height-md: 72px;        /* md */
            --header-height-lg: 72px;        /* lg */
            --header-height-xl: 72px;        /* xl */
            --header-height-current: var(--header-height-mobile);
            --header-padding-x: 1rem;
        }

        /* Breakpoint: sm (>=640px) */
        @media (min-width: 640px) {
            :root {
                --header-padding-x: 1.5rem;
                --header-height-current: var(--header-height-sm);
            }
        }

        /* Breakpoint: md (>=768px) */
        @media (min-width: 768px) {
            :root {
                --header-padding-x: 2rem;
                --header-height-current: var(--header-height-md);
            }
        }

        /* Breakpoint: lg (>=1024px) */
        @media (min-width: 1024px) {
            :root {
                --header-padding-x: 2.5rem;
                --header-height-current: var(--header-height-lg);
            }
        }

        /* Breakpoint: xl (>=1280px) */
        @media (min-width: 1280px) {
            :root {
                --header-padding-x: 3rem;
                --header-height-current: var(--header-height-xl);
            }
        }

        :root {
            --header-height: 72px;
            --header-height-mobile: 72px;
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

        .main-content {
            display: flex;
            max-width: 1150px;
            margin: 0 auto;
            padding: var(--main-padding);
            gap: var(--sidebar-gap);
        }
        .sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            /* デフォルトでは固定しない（モバイル/タブレットで通常フローにする） */
            position: static;
            top: auto;
            align-self: flex-start;
        }

        /* 大きい画面（lg相当）でのみ固定する */
        @media (min-width: 1024px) {
            .sidebar {
                position: sticky;
                top: calc(var(--header-height) + 1.5rem);
            }
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

        .profile-card {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
        }
        .profile-head {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        .big-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .big-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .name {
            font-size: 1.25rem;
            font-weight: 900;
            color: #24292e;
            margin-bottom: 0.25rem;
        }
        .headline {
            color: #6a737d;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .skill-tag {
            background-color: #f1f8ff;
            color: #0366d6;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid #c8e1ff;
        }
        .divider {
            height: 1px;
            background-color: #e1e4e8;
            margin: 1rem 0;
        }
        .kv {
            display: grid;
            gap: 0.75rem;
        }
        .k {
            font-weight: 800;
            color: #586069;
            font-size: 0.9rem;
        }
        .v {
            color: #24292e;
            font-weight: 600;
        }
        .help {
            color: #6a737d;
            font-size: 0.9rem;
            font-style: italic;
        }

        .form {
            display: grid;
            gap: 1.5rem;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            /* 左カラム(表示名)のエラーメッセージで行の高さが増えても、
               右カラム(職種)が同じ高さに引き伸ばされないようにする */
            align-items: start;
        }
        .row {
            display: grid;
            gap: 0.5rem;
        }
        .label {
            font-weight: 900;
            color: #586069;
            font-size: 0.9rem;
        }
        .required {
            font-size: 0.75rem;
            font-weight: 900;
            color: white;
            background: #d73a49;
            border-radius: 999px;
            padding: 0.15rem 0.55rem;
            letter-spacing: 0.02em;
            margin-left: 0.5rem;
            display: inline-block;
            line-height: 1.2;
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
        .input:focus, .textarea:focus, .select:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
            background-color: white;
        }
        .input.is-invalid, .textarea.is-invalid {
            border-color: #d73a49;
        }
        .textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.6;
        }
        .file-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px dashed #e1e4e8;
            border-radius: 10px;
            background-color: #fafbfc;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .file-input:hover {
            border-color: #0366d6;
            background-color: #f6f8fa;
        }
        .file-input:focus {
            outline: none;
            border-color: #0366d6;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
        }
        .error-message {
            display: block;
            margin-top: 6px;
            font-size: 13px;
            font-weight: 800;
            color: #dc2626;
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

        .btn-outline {
            background-color: transparent;
            color: #0366d6;
            border: 2px solid #0366d6;
            padding: 8px 14px;
            font-size: 16px;
        }
        .btn-outline:hover {
            background-color: #f1f8ff;
            color: #0256cc;
            border-color: #0256cc;
        }

        .skills-container {
            display: grid;
            gap: 0.75rem;
        }
        .skill-input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }
        @media (max-width: 768px) {
            .skill-input-row {
                grid-template-columns: 1fr;
            }
        }

    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

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

                    @php
                        $recipientType = old('recipient_type', $corporate->recipient_type ?? 'individual');
                    @endphp

                    <div class="row">
                        <div class="label">受注者タイプ <span class="required">（必須）</span></div>
                        <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                            <label style="display:inline-flex; align-items:center; gap:0.5rem; font-weight:800; color:#24292e;">
                                <input type="radio" name="recipient_type" value="individual" {{ $recipientType === 'individual' ? 'checked' : '' }}>
                                個人
                            </label>
                            <label style="display:inline-flex; align-items:center; gap:0.5rem; font-weight:800; color:#24292e;">
                                <input type="radio" name="recipient_type" value="corporation" {{ $recipientType === 'corporation' ? 'checked' : '' }}>
                                法人
                            </label>
                        </div>
                        @error('recipient_type')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="corporation-fields" class="row" style="display:none;">
                        <div class="grid-2">
                            <div class="row">
                                <div class="label">法人名 <span class="required">（必須）</span></div>
                                <input class="input @error('corporation_name') is-invalid @enderror" id="corporation_name" name="corporation_name" type="text" value="{{ old('corporation_name', $corporate->corporation_name ?? '') }}" placeholder="例: 株式会社AITECH">
                                @error('corporation_name')
                                <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="label">担当者名 <span class="required">（必須）</span></div>
                                <input class="input @error('corporation_contact_name') is-invalid @enderror" id="corporation_contact_name" name="corporation_contact_name" type="text" value="{{ old('corporation_contact_name', $corporate->corporation_contact_name ?? '') }}" placeholder="例: 山田 太郎">
                                @error('corporation_contact_name')
                                <span class="error-message">{{ $message }}</span>
                                @enderror
                                <div class="help">企業側がチャットで呼ぶ名前になります。</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">会社サイトURL（任意）</div>
                        <input class="input @error('company_site_url') is-invalid @enderror" id="company_site_url" name="company_site_url" type="url" value="{{ old('company_site_url', $corporate->company_site_url ?? '') }}" placeholder="例: https://aitech.example.com">
                        @error('company_site_url')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="help">信頼材料として掲載できます（ポートフォリオURLと同列の扱いでもOKです）。</div>
                    </div>

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
                        <div class="label">スキル（1つ以上推奨・複数入力）</div>
                        <div class="help">複数入力できます。</div>
                        @php
                            $skillsInvalid = $errors->has('custom_skills');
                            $customSkillValues = old('custom_skills');
                            if (!is_array($customSkillValues)) {
                                $customSkillValues = [];
                                if (isset($corporate) && $corporate && $corporate->customSkills) {
                                    $customSkillValues = $corporate->customSkills->pluck('name')->toArray();
                                }
                            }
                            $minSlots = 4;
                            if (count($customSkillValues) < $minSlots) {
                                $customSkillValues = array_pad($customSkillValues, $minSlots, null);
                            }
                        @endphp

                        <div class="skills-container" id="skills-container">
                            @for($i = 0; $i < count($customSkillValues); $i += 2)
                                <div class="skill-input-row">
                                    <input class="input skill-input {{ $skillsInvalid ? 'is-invalid' : '' }}" name="custom_skills[]" type="text" value="{{ $customSkillValues[$i] ?? '' }}" placeholder="例: Laravel">
                                    <input class="input skill-input" name="custom_skills[]" type="text" value="{{ $customSkillValues[$i + 1] ?? '' }}" placeholder="例: Vue.js">
                                </div>
                            @endfor
                        </div>
                        <button type="button" class="btn btn-outline" id="add-skill-btn" style="margin-top:0.75rem;">追加する</button>
                        @error('custom_skills')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                        @error('custom_skills.*')
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
        (function () {
            const header = document.querySelector('header.header');
            const toggle = document.getElementById('mobileNavToggle');
            const mobileNav = document.getElementById('mobileNav');
            if (!header || !toggle || !mobileNav) return;

            const OPEN_CLASS = 'is-mobile-nav-open';
            const isOpen = () => header.classList.contains(OPEN_CLASS);

            const open = () => {
                header.classList.add(OPEN_CLASS);
                toggle.setAttribute('aria-expanded', 'true');
            };

            const close = () => {
                header.classList.remove(OPEN_CLASS);
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (isOpen()) close();
                else open();
            });

            document.addEventListener('click', (e) => {
                if (!header.contains(e.target)) close();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) close();
            });
        })();
    </script>
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

        // リアルタイムプレビュー機能
        (function () {
            const displayName = document.getElementById('display_name');
            const jobTitle = document.getElementById('job_title');
            const minRate = document.getElementById('min_rate');
            const maxRate = document.getElementById('max_rate');
            const minHours = document.getElementById('min_hours_per_week');
            const maxHours = document.getElementById('max_hours_per_week');
            const hoursPerDay = document.getElementById('hours_per_day');
            const daysPerWeek = document.getElementById('days_per_week');
            const addSkillBtn = document.getElementById('add-skill-btn');
            const skillsContainer = document.getElementById('skills-container');

            const previewName = document.getElementById('preview-name');
            const previewHeadline = document.getElementById('preview-headline');
            const previewSkills = document.getElementById('preview-skills');
            const previewRate = document.getElementById('preview-rate');
            const previewHours = document.getElementById('preview-hours');
            const previewDays = document.getElementById('preview-days');

            function updatePreview() {
                if (displayName && previewName) {
                    previewName.textContent = displayName.value || '未入力';
                }
                if (jobTitle && previewHeadline) {
                    previewHeadline.textContent = jobTitle.value || '未入力';
                }
                if (previewSkills) {
                    previewSkills.innerHTML = '';
                    const skillInputs = document.querySelectorAll('input[name="custom_skills[]"]');
                    skillInputs.forEach(input => {
                        const value = (input.value || '').trim();
                        if (!value) return;
                        const tag = document.createElement('span');
                        tag.className = 'skill-tag';
                        tag.textContent = value;
                        previewSkills.appendChild(tag);
                    });
                }
                if (minRate && maxRate && previewRate) {
                    const min = minRate.value;
                    const max = maxRate.value;
                    if (min && max) {
                        previewRate.textContent = min + '〜' + max + '万円';
                    } else if (min || max) {
                        previewRate.textContent = (min || max) + '万円';
                    } else {
                        previewRate.textContent = '未設定';
                    }
                }
                if (minHours && maxHours && previewHours) {
                    const min = minHours.value;
                    const max = maxHours.value;
                    if (min && max) {
                        previewHours.textContent = '週' + min + '〜' + max + 'h';
                    } else if (min || max) {
                        previewHours.textContent = '週' + (min || max) + 'h';
                    } else {
                        previewHours.textContent = '未設定';
                    }
                }
                if (hoursPerDay && daysPerWeek && previewDays) {
                    const hours = hoursPerDay.value;
                    const days = daysPerWeek.value;
                    if (hours && days) {
                        previewDays.textContent = hours + 'h/day・' + days + '日/week';
                    } else if (hours || days) {
                        previewDays.textContent = (hours ? hours + 'h/day' : '') + (days ? days + '日/week' : '');
                    } else {
                        previewDays.textContent = '未設定';
                    }
                }
            }

            // イベントリスナーを追加
            [displayName, jobTitle, minRate, maxRate, minHours, maxHours, hoursPerDay, daysPerWeek].forEach(el => {
                if (el) el.addEventListener('input', updatePreview);
            });

            // スキル入力欄追加機能（createと同様）
            if (addSkillBtn && skillsContainer) {
                addSkillBtn.addEventListener('click', function() {
                    const lastRow = skillsContainer.lastElementChild;
                    const inputsInLastRow = lastRow ? lastRow.querySelectorAll('.skill-input') : [];

                    if (lastRow && inputsInLastRow.length < 2) {
                        const newInput = document.createElement('input');
                        newInput.className = 'input skill-input';
                        newInput.name = 'custom_skills[]';
                        newInput.type = 'text';
                        newInput.placeholder = '例: スキル名';
                        lastRow.appendChild(newInput);
                        newInput.addEventListener('input', updatePreview);
                        newInput.addEventListener('change', updatePreview);
                    } else {
                        const newRow = document.createElement('div');
                        newRow.className = 'skill-input-row';

                        const newInput = document.createElement('input');
                        newInput.className = 'input skill-input';
                        newInput.name = 'custom_skills[]';
                        newInput.type = 'text';
                        newInput.placeholder = '例: スキル名';
                        newRow.appendChild(newInput);
                        skillsContainer.appendChild(newRow);

                        newInput.addEventListener('input', updatePreview);
                        newInput.addEventListener('change', updatePreview);
                    }
                });
            }

            // 既存スキル入力にもプレビュー更新を紐づけ
            const skillInputs = document.querySelectorAll('input[name="custom_skills[]"]');
            skillInputs.forEach(input => {
                input.addEventListener('input', updatePreview);
                input.addEventListener('change', updatePreview);
            });

            // 初期表示
            updatePreview();
        })();

        // 受注者タイプ（個人/法人）で法人項目を出し分け
        (function () {
            const radios = document.querySelectorAll('input[name="recipient_type"]');
            const corpFields = document.getElementById('corporation-fields');
            const corpName = document.getElementById('corporation_name');
            const corpContact = document.getElementById('corporation_contact_name');

            function sync() {
                const checked = document.querySelector('input[name="recipient_type"]:checked');
                const isCorp = checked && checked.value === 'corporation';
                if (corpFields) corpFields.style.display = isCorp ? '' : 'none';
                if (corpName) corpName.disabled = !isCorp;
                if (corpContact) corpContact.disabled = !isCorp;
            }

            radios.forEach(r => r.addEventListener('change', sync));
            sync();
        })();
    </script>
</body>
</html>
