<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>案件詳細 - AITECH</title>
    @include('partials.corporate-header-style')
    <style>
        /* Main Layout (job show page) */
        .main-content {
            display: flex;
            max-width: 1000px;
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

        .page-breadcrumbs {
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
            color: #6a737d;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .page-breadcrumbs a {
            color: #0366d6;
            text-decoration: none;
        }
        .page-breadcrumbs a:hover { text-decoration: underline; }

        .hero {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        .hero-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #0060ff;
            line-height: 1.3;
        }
        .hero-company {
            color: #586069;
            font-size: 20px;
            font-weight: 500;
        }
        .hero-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.25rem;
        }
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-weight: 800;
            font-size: 0.85rem;
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            color: #24292e;
            white-space: nowrap;
        }
        .chip.primary {
            background: #f1f8ff;
            border-color: #c8e1ff;
            color: #0366d6;
        }

        /* Match corporate/jobs/index meta line (work start / publish end) */
        .job-meta-line {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            color: #586069;
            font-weight: 800;
            font-size: 16px;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }
        .meta-bold { font-weight: 900; }
        .meta-days { color: #dc2626; } /* 赤 */
        .meta-date { color: #16a34a; } /* 緑 */
        .meta-muted { color: #6a737d; font-weight: 900; }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-top: 0.25rem;
            margin-bottom: 1rem;
        }
        .inline { display: inline-flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-weight: 900;
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            white-space: nowrap;
            font-size: 0.85rem;
        }
        .pill.public { background: #e6ffed; border-color: #b7f5c3; color: #1a7f37; }
        .pill.draft { background: #fff8c5; border-color: #f5e58a; color: #7a5d00; }
        .pill.stopped { background: #fff5f5; border-color: #ffccd2; color: #b31d28; }

        .hero-subtitle {
            color: #586069;
            font-size: 18px;
            font-weight: 800;
        }
        .hero-company-label {
            color: #6a737d;
            font-size: 0.85rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }
        .hero-company {
            color: #24292e;
            font-size: 18px;
            font-weight: 900;
        }

        .persona-section {
            margin-top: 0.9rem;
            background-color: #f6f8fa;
            border: 1px solid #e1e4e8;
            border-radius: 12px;
            padding: 0.9rem 1rem;
        }
        .overview-section {
            margin-top: 0.9rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid #d8e8ff;
            border-radius: 12px;
            padding: 1rem 1.1rem;
        }
        .persona-title {
            font-size: 0.8rem;
            color: #6a737d;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .overview-title {
            font-size: 0.8rem;
            color: #4b5563;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.55rem;
        }
        .overview-text {
            color: #1f2937;
            font-weight: 700;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        .persona-text {
            color: #24292e;
            font-weight: 800;
            line-height: 1.7;
            white-space: pre-wrap;
        }

        .skills-block { margin-top: 1.25rem; }
        .skills-label {
            font-size: 0.8rem;
            color: #6a737d;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .prose { white-space: pre-wrap; }

        .section {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            margin-bottom: 2rem;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 900;
            margin-bottom: 1rem;
            color: #24292e;
            letter-spacing: -0.01em;
        }
        .section p, .section li { color: #586069; font-size: 1rem; line-height: 1.7; }
        .section ul { padding-left: 1.25rem; display: grid; gap: 0.5rem; }

        .job-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .detail-item {
            display: flex;
            max-width: 280px;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            background-color: #f6f8fa;
            border-radius: 10px;
        }
        .detail-label {
            font-size: 0.75rem;
            color: #6a737d;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .detail-value {
            font-weight: 900;
            color: #24292e;
            font-size: 1.05rem;
            white-space: nowrap;
        }

        .job-details {
            max-width: 600px;
        }

        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.75rem;
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

        /* Sidebar cards */
        .side-card {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            margin-bottom: 2rem;
        }
        .side-title { font-size: 1.1rem; font-weight: 900; margin-bottom: 1.25rem; }
        .kv {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 0.75rem 1rem;
        }
        .k { color: #6a737d; font-weight: 800; font-size: 0.9rem; }
        .v { color: #24292e; font-weight: 900; font-size: 0.95rem; }
        .help { color: #6a737d; font-size: 0.85rem; line-height: 1.5; }

        .btn-row {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        .btn-row.horizontal {
            flex-direction: row;
            gap: 1rem;
        }
        .btn-row.horizontal .btn {
            flex: 1;
        }
        .btn {
            padding: 0.875rem 1.25rem;
            border-radius: 10px;
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
        }
        .btn-primary {
            background-color: #0366d6;
            color: white;
            font-size: 20px;
            padding: 15px 60px;
        }
        .btn-primary:hover {
            background-color: #0256cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(3, 102, 214, 0.3);
        }
        .btn-secondary {
            background-color: #586069;
            color: white;
            font-size: 20px;
            padding: 15px 60px;
        }
        .btn-secondary:hover {
            background-color: #4c5561;
            transform: translateY(-1px);
        }
        .btn-ghost {
            background: #fafbfc;
            color: #24292e;
            border: 1px solid #e1e4e8;
        }
        .btn-ghost:hover { background: #f6f8fa; transform: translateY(-1px); }

    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

    <main class="main-content max-w-5xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <div class="page-breadcrumbs" aria-label="パンくず">
                <a href="{{ route('corporate.jobs.index') }}">案件一覧</a>
                <span aria-hidden="true">/</span>
                <span>案件詳細</span>
            </div>

            <section class="hero" aria-label="案件概要">
                @php
                    $rewardText = '';
                    if ($job->reward_type === 'monthly') {
                        $rewardText = ($job->min_rate / 10000) . '〜' . ($job->max_rate / 10000) . '万円';
                    } elseif ($job->reward_type === 'hourly') {
                        $rewardText = number_format($job->min_rate) . '〜' . number_format($job->max_rate) . '円/時';
                    }

                    // ステータス pill（index と同じ）
                    $statusClass = '';
                    $statusText = '';
                    switch($job->status) {
                        case \App\Models\Job::STATUS_PUBLISHED:
                            $statusClass = 'public';
                            $statusText = '公開';
                            break;
                        case \App\Models\Job::STATUS_DRAFT:
                            $statusClass = 'draft';
                            $statusText = '下書き';
                            break;
                        case \App\Models\Job::STATUS_STOPPED:
                            $statusClass = 'stopped';
                            $statusText = '停止';
                            break;
                    }

                    // 掲載終了までの日数 / 稼働開始日（index と同じ見え方）
                    $today = \Illuminate\Support\Carbon::today();
                    $daysUntilPublishEnd = null;
                    if (!empty($job->publish_end_date)) {
                        $end = ($job->publish_end_date instanceof \Illuminate\Support\Carbon)
                            ? $job->publish_end_date
                            : \Illuminate\Support\Carbon::parse($job->publish_end_date);
                        $daysUntilPublishEnd = $today->diffInDays($end, false);
                    }

                    $workStartDate = null;
                    if (!empty($job->work_start_date)) {
                        $start = ($job->work_start_date instanceof \Illuminate\Support\Carbon)
                            ? $job->work_start_date
                            : \Illuminate\Support\Carbon::parse($job->work_start_date);
                        $workStartDate = $start->format('m/d');
                    }
                @endphp

                <div class="job-meta-line" aria-label="掲載終了までの日数と稼働開始日">
                    @if($daysUntilPublishEnd !== null)
                        @if($daysUntilPublishEnd >= 0)
                            <span class="meta-bold">あと <span class="meta-days">{{ $daysUntilPublishEnd }}日</span>で掲載終了</span>
                        @else
                            <span class="meta-bold">掲載終了（<span class="meta-days">{{ abs($daysUntilPublishEnd) }}日</span>前）</span>
                        @endif
                    @else
                        <span class="meta-muted">掲載終了 未設定</span>
                    @endif
                    @if($workStartDate)
                        <span class="meta-bold"><span class="meta-date">{{ $workStartDate }}</span>稼働開始</span>
                    @else
                        <span class="meta-muted">稼働開始 未定</span>
                    @endif
                </div>

                <div class="job-header">
                    <div>
                        <h1 class="hero-title">{{ $job->title }}</h1>
                        <div class="hero-subtitle">#{{ $job->subtitle }}</div>
                        <div class="hero-company-label">会社名</div>
                        <div class="hero-company">{{ $job->company->name ?? '' }}</div>

                        <div class="overview-section" aria-label="案件概要">
                            <div class="overview-title">案件概要</div>
                            <div class="overview-text">{{ $job->description ?: '未設定' }}</div>
                        </div>

                        <div class="persona-section" aria-label="求めている人物像">
                            <div class="persona-title">求めている人物像</div>
                            <div class="persona-text">{{ $job->desired_persona ?: '未設定' }}</div>
                        </div>
                    </div>
                    <div class="inline" aria-label="ステータス">
                        <span class="pill {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>

                <div class="job-details grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4" aria-label="主要条件">
                    <div class="detail-item">
                        <div class="detail-label">報酬</div>
                        <div class="detail-value">{{ $rewardText }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">稼働条件</div>
                        <div class="detail-value">{{ $job->work_time_text }}</div>
                    </div>
                </div>

                @if($job->required_skills_text)
                    @php
                        $skills = explode(',', $job->required_skills_text);
                    @endphp
                    <div class="skills-block" aria-label="必要スキル">
                        <div class="skills-label">必要スキル</div>
                        <div class="skills">
                            @foreach($skills as $skill)
                                <span class="skill-tag">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="skills-block" aria-label="必要スキル">
                        <div class="skills-label">必要スキル</div>
                        <div class="help">未設定</div>
                    </div>
                @endif
            </section>

            <section class="section" aria-label="応募">
                <div class="btn-row horizontal flex flex-col md:flex-row gap-3 md:gap-4">
                    @if($alreadyApplied)
                        @if($thread)
                            <a href="{{ route('corporate.threads.show', $thread->id) }}" class="btn btn-primary w-full md:flex-1">応募済み（チャットを開く）</a>
                        @else
                            <button class="btn btn-primary w-full md:flex-1" disabled>応募済み</button>
                        @endif
                    @else
                        <a href="{{ route('corporate.jobs.apply.create', $job->id) }}" class="btn btn-primary w-full md:flex-1">応募する</a>
                    @endif
                    <a href="{{ route('corporate.jobs.index') }}" class="btn btn-secondary w-full md:flex-1">一覧に戻る</a>
                </div>
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
    </script>
</body>
</html>
