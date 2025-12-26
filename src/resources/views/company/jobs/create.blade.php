<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>案件 新規登録（企業）- AITECH</title>
    <style>
        :root { --header-height: 104px; --header-height-mobile: 91px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 130%; }
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafbfc;
            color: #24292e;
            line-height: 1.5;
        }
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
        .nav-link.active { background-color: #0366d6; color: white; box-shadow: 0 2px 8px rgba(3, 102, 214, 0.3); }
        .badge {
            background-color: #d73a49; color: white; border-radius: 999px;
            padding: 0.15rem 0.5rem; font-size: 0.7rem; font-weight: 700;
            min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center;
            box-shadow: 0 1px 3px rgba(209, 58, 73, 0.3);
            position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
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
            display: none; position: absolute; right: 0; top: 100%;
            background-color: white; min-width: 240px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px; z-index: 1000; border: 1px solid #e1e4e8; margin-top: 0.5rem;
        }
        .dropdown.is-open .dropdown-content { display: block; }
        .dropdown-item {
            display: block; padding: 0.875rem 1.25rem; text-decoration: none; color: #586069;
            transition: all 0.15s ease; border-radius: 6px; margin: 0.25rem; white-space: nowrap;
        }
        .dropdown-item:hover { background-color: #f6f8fa; color: #24292e; }
        .dropdown-divider { height: 1px; background-color: #e1e4e8; margin: 0.5rem 0; }

        .main-content { max-width: 1100px; margin: 0 auto; padding: 3rem; }
        .page-title { font-size: 2rem; font-weight: 900; margin-bottom: 1.5rem; letter-spacing: -0.025em; }
        .panel {
            background-color: white; border-radius: 16px; padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
        }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; }
        .field { margin-bottom: 1.25rem; }
        .field label { display: block; font-weight: 900; margin-bottom: 0.75rem; color: #586069; font-size: 0.9rem; }
        .input, .textarea, .select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
            background-color: #fafbfc;
        }
        .textarea { min-height: 140px; resize: vertical; }
        .input:focus, .textarea:focus, .select:focus { outline: none; border-color: #0366d6; box-shadow: 0 0 0 3px rgba(3,102,214,0.1); background-color: #fff; }
        .help { margin-top: 0.5rem; color: #6a737d; font-weight: 800; font-size: 0.85rem; }
        .btn-row { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.25rem; flex-wrap: wrap; }
        .btn {
            padding: 0.875rem 1.75rem; border-radius: 10px; font-weight: 900;
            text-decoration: none; display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.15s ease; cursor: pointer; border: none; font-size: 0.95rem; white-space: nowrap;
        }
        .btn-primary { background-color: #0366d6; color: #fff; }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3,102,214,0.3); }
        .btn-secondary { background-color: #586069; color: #fff; }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }

        @media (max-width: 920px) {
            .header-content { height: var(--header-height-mobile); }
            .nav-links { position: static; left: auto; transform: none; justify-content: flex-start; }
            .user-menu { position: static; transform: none; margin-left: auto; }
            .main-content { padding: 1.5rem; }
            .grid { grid-template-columns: 1fr; }
            .grid-3 { grid-template-columns: 1fr; }
            .btn-row .btn { width: 100%; }
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
                <a href="{{ route('company.jobs.index') }}" class="nav-link active">案件一覧</a>
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
        <h1 class="page-title">案件 新規登録</h1>
        
        @if ($errors->any())
            <div class="panel" style="margin-bottom: 1.5rem; background-color: #fff3cd; border-color: #ffc107;">
                <div style="color: #856404; font-weight: 700;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="panel" style="margin-bottom: 1.5rem; background-color: #d4edda; border-color: #28a745;">
                <div style="color: #155724; font-weight: 700;">{{ session('success') }}</div>
            </div>
        @endif

        <div class="panel">
            <form action="{{ route('company.jobs.store') }}" method="post">
                @csrf
                <div class="grid">
                    <div class="field">
                        <label for="title">タイトル（必須）</label>
                        <input id="title" name="title" class="input" type="text" placeholder="例: ECサイト機能拡張プロジェクト" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="help" style="color: #d73a49;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="companyName">会社名</label>
                        <input id="companyName" class="input" type="text" value="{{ Auth::user()->company->name ?? '未登録' }}" disabled>
                        <div class="help">企業プロフィールから自動取得されます</div>
                        @if (!Auth::user()->company)
                            <div class="help" style="color: #d73a49;">先に企業プロフィールを登録してください</div>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <label for="description">案件概要（必須）</label>
                    <textarea id="description" name="description" class="textarea" placeholder="案件の概要を入力してください" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="help" style="color: #d73a49;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="required_skills_text">必要スキル（自由入力）</label>
                    <input id="required_skills_text" name="required_skills_text" class="input" type="text" placeholder="例: PHP, Laravel, Vue.js, MySQL" value="{{ old('required_skills_text') }}">
                    <div class="help">カンマ区切りで入力してください</div>
                    @error('required_skills_text')
                        <div class="help" style="color: #d73a49;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid-3">
                    <div class="field">
                        <label for="reward_type">報酬タイプ（必須）</label>
                        <select id="reward_type" name="reward_type" class="select" required>
                            <option value="monthly" {{ old('reward_type', 'monthly') === 'monthly' ? 'selected' : '' }}>月額/案件単価</option>
                            <option value="hourly" {{ old('reward_type') === 'hourly' ? 'selected' : '' }}>時給</option>
                        </select>
                        @error('reward_type')
                            <div class="help" style="color: #d73a49;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="min_rate">最低単価（必須）</label>
                        <input id="min_rate" name="min_rate" class="input" type="number" placeholder="例: 300000" value="{{ old('min_rate') }}" min="0" required>
                        <div class="help">円単位で入力してください</div>
                        @error('min_rate')
                            <div class="help" style="color: #d73a49;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="max_rate">最高単価（必須）</label>
                        <input id="max_rate" name="max_rate" class="input" type="number" placeholder="例: 500000" value="{{ old('max_rate') }}" min="0" required>
                        <div class="help">円単位で入力してください</div>
                        @error('max_rate')
                            <div class="help" style="color: #d73a49;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field">
                    <label for="work_time_text">稼働条件（必須）</label>
                    <input id="work_time_text" name="work_time_text" class="input" type="text" placeholder="例: 週10〜20時間、2〜3ヶ月" value="{{ old('work_time_text') }}" required>
                    <div class="help">稼働時間や期間などを自由に入力してください</div>
                    @error('work_time_text')
                        <div class="help" style="color: #d73a49;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="status">ステータス（必須）</label>
                    <select id="status" name="status" class="select" required>
                        <option value="{{ App\Models\Job::STATUS_PUBLISHED }}" {{ old('status', App\Models\Job::STATUS_PUBLISHED) == App\Models\Job::STATUS_PUBLISHED ? 'selected' : '' }}>公開</option>
                        <option value="{{ App\Models\Job::STATUS_DRAFT }}" {{ old('status') == App\Models\Job::STATUS_DRAFT ? 'selected' : '' }}>下書き</option>
                        <option value="{{ App\Models\Job::STATUS_STOPPED }}" {{ old('status') == App\Models\Job::STATUS_STOPPED ? 'selected' : '' }}>停止</option>
                    </select>
                    <div class="help">公開のみフリーランス側の案件一覧に表示される想定</div>
                    @error('status')
                        <div class="help" style="color: #d73a49;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="btn-row">
                    <a class="btn btn-secondary" href="{{ route('company.jobs.index') }}">キャンセル</a>
                    <button class="btn btn-primary" type="submit">登録</button>
                </div>
            </form>
        </div>
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
