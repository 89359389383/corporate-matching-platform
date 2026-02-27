<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募する - AITECH</title>
    @include('partials.corporate-header-style')
    <style>
        /* Apply page styles */
        /* Main Layout */
        .main-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem;
        }
        .content-area { width: 100%; }
        .page-title {
            font-size: 2rem;
            font-weight: 700;
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
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 1.25rem;
            color: #24292e;
            letter-spacing: -0.01em;
        }

        .job-summary {
            margin-top: 0.25rem;
        }

        .job-summary-table-wrap {
            overflow-x: auto;
            border: 1px solid #e1e4e8;
            border-radius: 12px;
            background: #fff;
        }
        .job-summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .job-summary-table th,
        .job-summary-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e1e4e8;
            vertical-align: top;
            font-size: 20px;
            line-height: 1.6;
        }
        .job-summary-table tr:last-child th,
        .job-summary-table tr:last-child td {
            border-bottom: 0;
        }
        .job-summary-table th {
            width: 260px;
            background: #f6f8fa;
            color: #6a737d;
            font-weight: 800;
            text-align: left;
            white-space: nowrap;
        }
        .job-summary-table td {
            background: #ffffff;
            color: #24292e;
            font-weight: 700;
        }
        .job-summary-table .skills {
            margin-top: 0;
            gap: 0.6rem;
        }
        @media (max-width: 640px) {
            .job-summary-table th { width: 170px; }
            .job-summary-table th,
            .job-summary-table td { padding: 0.85rem 1rem; font-size: 18px; }
        }
        .summary-line {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 0.25rem;
        }
        .summary-label {
            color: #6a737d;
            font-weight: 700;
            font-size: 20px;
            flex-shrink: 0;
        }
        .summary-value {
            color: #24292e;
            font-weight: 700;
            font-size: 20px;
        }
        .summary-separator {
            color: #6a737d;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0 0.25rem;
        }
        .kv {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 0.75rem 1rem;
            align-items: start;
        }
        .k { color: #6a737d; font-weight: 700; font-size: 0.9rem; }
        .v { color: #24292e; font-weight: 700; font-size: 0.95rem; }

        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.5rem;
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

        .form {
            display: grid;
            gap: 1.25rem;
        }
        .form-row { display: grid; gap: 0.6rem; }
        .label {
            font-weight: 800;
            color: #586069;
            font-size: 18px;
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
            border-radius: 8px;
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
            border-color: rgba(239, 68, 68, 0.8);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.10);
        }
        .error-message {
            display: block;
            margin-top: 6px;
            font-size: 13px;
            font-weight: 800;
            color: #dc2626;
        }
        .help {
            color: #6a737d;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .inline-input {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .inline-input .input {
            flex: 1 1 220px;
            min-width: 160px;
        }
        .input-unit {
            color: #24292e;
            font-weight: 800;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .weekday-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 0.75rem;
        }
        @media (max-width: 900px) {
            .weekday-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }
        @media (max-width: 480px) {
            .weekday-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        .weekday-item {
            position: relative;
            display: block;
        }
        .weekday-item input {
            position: absolute;
            inset: 0;
            opacity: 0;
            pointer-events: none;
        }
        .weekday-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.8rem 0.75rem;
            border-radius: 8px;
            border: 2px solid #e1e4e8;
            background: #ffffff;
            color: #24292e;
            font-weight: 900;
            user-select: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .weekday-box::before {
            content: "";
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid currentColor;
            display: inline-block;
            background: transparent;
            flex: 0 0 auto;
        }
        .weekday-item input:checked + .weekday-box {
            background: #0366d6;
            border-color: #0366d6;
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(3, 102, 214, 0.22);
            transform: translateY(-1px);
        }
        .weekday-item input:checked + .weekday-box::before {
            content: "✓";
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 900;
            background: #ffffff;
            color: #0366d6;
            border-color: #ffffff;
        }
        .weekday-item:focus-within .weekday-box {
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.12);
        }

        .time-range {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .time-range .input {
            flex: 1 1 180px;
            min-width: 160px;
        }
        .time-sep {
            font-weight: 900;
            color: #586069;
            white-space: nowrap;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
            padding-top: 1rem;
            border-top: 1px solid #e1e4e8;
            flex-wrap: wrap;
        }
        .actions .btn {
            flex: 1;
            min-width: 0;
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
            white-space: nowrap;
        }
        .btn-secondary {
            background-color: #586069;
            color: white;
            font-size: 20px;
            padding: 15px 60px;
        }
        .btn-secondary:hover { background-color: #4c5561; transform: translateY(-1px); }
        .btn-primary {
            background-color: #0366d6;
            color: white;
            font-size: 20px;
            padding: 15px 60px;
        }
        .btn-primary:hover { background-color: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3); }

    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

    <main class="main-content max-w-5xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">応募する</h1>
            @include('partials.error-panel')
            <p class="page-subtitle">応募メッセージを入力して応募を送信します。送信後はチャット画面へ遷移します。</p>

            <!-- 応募先案件 -->
            <div class="panel">
                <div class="panel-title">応募先案件</div>
                <div class="job-summary">
                    <div class="job-summary-table-wrap" role="group" aria-label="応募先案件の概要">
                        <table class="job-summary-table" role="table" aria-label="応募先案件の概要">
                            <tbody>
                                <tr>
                                    <th scope="row">会社名：</th>
                                    <td>{{ $job->company->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">求人名：</th>
                                    <td>{{ $job->title }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">報酬：</th>
                                    <td>
                                        @php
                                            $rewardText = '';
                                            if ($job->reward_type === 'monthly') {
                                                $rewardText = ($job->min_rate / 10000) . '〜' . ($job->max_rate / 10000) . '万円';
                                            } else {
                                                $rewardText = number_format($job->min_rate) . '〜' . number_format($job->max_rate) . '円/時';
                                            }
                                        @endphp
                                        {{ $rewardText }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">想定稼働時間／期間：</th>
                                    <td>{{ $job->work_time_text }}</td>
                                </tr>
                                @if($job->required_skills_text)
                                    <tr>
                                        <th scope="row">必要スキル：</th>
                                        <td>
                                            <div class="skills" aria-label="必要スキル">
                                                @php
                                                    $skills = explode(',', $job->required_skills_text);
                                                @endphp
                                                @foreach($skills as $skill)
                                                    <span class="skill-tag">{{ trim($skill) }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 応募内容 -->
            <div class="panel">
                <div class="panel-title">応募内容</div>
                <form class="form" action="{{ route('corporate.jobs.apply.store', $job) }}" method="post">
                    @csrf
                    <div class="form-row">
                        <label class="label" for="desired_hourly_rate">希望時間単価 <span class="required">必須</span></label>
                        <div class="inline-input">
                            <input
                                id="desired_hourly_rate"
                                class="input @error('desired_hourly_rate') is-invalid @enderror"
                                type="number"
                                name="desired_hourly_rate"
                                value="{{ old('desired_hourly_rate') }}"
                                min="0"
                                step="100"
                                inputmode="numeric"
                                placeholder="2,000"
                                required
                            >
                            <span class="input-unit">円/時間</span>
                        </div>
                        @error('desired_hourly_rate')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <span class="label">稼働曜日（目安） <span class="required">必須</span></span>
                        @php
                            $oldDays = old('work_days', []);
                            if (!is_array($oldDays)) $oldDays = [];
                            $days = ['月','火','水','木','金','土','日'];
                        @endphp
                        <div class="weekday-grid" role="group" aria-label="稼働曜日（目安）">
                            @foreach($days as $day)
                                <label class="weekday-item">
                                    <input type="checkbox" name="work_days[]" value="{{ $day }}" {{ in_array($day, $oldDays, true) ? 'checked' : '' }}>
                                    <span class="weekday-box">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('work_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        @error('work_days.*')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label class="label" for="work_time_from">稼働時間帯（目安） <span class="required">必須</span></label>
                        <div class="time-range" aria-label="稼働時間帯（目安）">
                            <input
                                id="work_time_from"
                                class="input @error('work_time_from') is-invalid @enderror"
                                type="time"
                                name="work_time_from"
                                value="{{ old('work_time_from') }}"
                                required
                            >
                            <span class="time-sep">〜</span>
                            <input
                                id="work_time_to"
                                class="input @error('work_time_to') is-invalid @enderror"
                                type="time"
                                name="work_time_to"
                                value="{{ old('work_time_to') }}"
                                required
                            >
                        </div>
                        @error('work_time_from')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        @error('work_time_to')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label class="label" for="note">備考</label>
                        <textarea
                            id="note"
                            class="textarea @error('note') is-invalid @enderror"
                            name="note"
                            placeholder="稼働曜日・時間帯に関する備考があればご記入ください"
                        >{{ old('note') }}</textarea>
                        @error('note')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label class="label" for="weekly_hours">合計週稼働時間（目安） <span class="required">必須</span></label>
                        <select id="weekly_hours" class="select @error('weekly_hours') is-invalid @enderror" name="weekly_hours" required>
                            <option value="" disabled {{ old('weekly_hours') === null ? 'selected' : '' }}>稼働時間を選択してください</option>
                            <option value="5" {{ old('weekly_hours') == 5 ? 'selected' : '' }}>週5時間程度（20時間／月）</option>
                            <option value="10" {{ old('weekly_hours') == 10 ? 'selected' : '' }}>週10時間程度（40時間／月）</option>
                            <option value="20" {{ old('weekly_hours') == 20 ? 'selected' : '' }}>週20時間程度（80時間／月）</option>
                            <option value="30" {{ old('weekly_hours') == 30 ? 'selected' : '' }}>週30時間程度（120時間／月）</option>
                            <option value="40" {{ old('weekly_hours') == 40 ? 'selected' : '' }}>週40時間程度（160時間／月）</option>
                        </select>
                        @error('weekly_hours')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label class="label" for="available_start">開始可能日 <span class="required">必須</span></label>
                        <select id="available_start" class="select @error('available_start') is-invalid @enderror" name="available_start" required>
                            <option value="" disabled {{ old('available_start') === null ? 'selected' : '' }}>稼働可能開始日を選択してください</option>
                            @foreach(['即日','2週間後','1ヶ月後','3ヶ月後以降'] as $opt)
                                <option value="{{ $opt }}" {{ old('available_start') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('available_start')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label class="label" for="message">応募メッセージ <span class="required">必須</span></label>
                        <textarea id="message" class="textarea @error('message') is-invalid @enderror" name="message" placeholder="例) 要件の◯◯に対して、Laravel + Vueでの実装経験があります。稼働は週25h、開始は1月上旬から可能です。実績: https://...">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="actions flex flex-col md:flex-row gap-3 md:gap-4">
                        <a class="btn btn-secondary w-full md:flex-1" href="{{ route('corporate.jobs.show', $job) }}" role="button">戻る</a>
                        <button class="btn btn-primary w-full md:flex-1" type="submit">応募を送信</button>
                    </div>
                </form>
            </div>

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
    </script>
</body>
</html>