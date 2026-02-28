<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募一覧 - AITECH</title>

    {{-- 上部ヘッダーは既存のものをそのまま使用 --}}
    @include('partials.corporate-header-style')
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- ✅ ここにCSSを全て集約（ヘッダー以外は「応募した案件」ページと同じ見た目） --}}
    <style>
        /* Base */
        :root {
            --header-height-current: 91px;
            --header-padding-x: 1rem;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { font-size: 97.5%; }

        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafbfc;
            color: #24292e;
            line-height: 1.5;
        }

        /* Main Layout */
        .main-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 3rem;
        }

        .content-area { width: 100%; }

        .page-title {
            font-size: 28px;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.01em;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #6a737d;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .jobs-grid {
            display: grid;
            gap: 2rem;
        }

        /* Job Card */
        .job-card {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            transition: all 0.2s ease;
            border: 1px solid #e1e4e8;
            position: relative;
            overflow: hidden;
            display: block;
            text-decoration: none;
            color: inherit;
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
            margin-bottom: 1.25rem;
            gap: 1rem;
        }

        .job-title {
            font-size: 24px;
            font-weight: 700;
            color: #0060ff;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .company-name {
            color: #586069;
            font-size: 18px;
            font-weight: 500;
        }

        .job-meta {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.85rem;
            border: 1px solid #e1e4e8;
            background: #fafbfc;
            color: #24292e;
            white-space: nowrap;
        }

        .chip.status-pending {
            background: #fff5b1;
            border-color: #f1e05a;
            color: #7a5a00;
        }
        .chip.status-interview {
            background: #f1f8ff;
            border-color: #c8e1ff;
            color: #0366d6;
        }
        .chip.status-closed {
            background: #f6f8fa;
            border-color: #d1d5da;
            color: #6a737d;
        }

        .chip.unread {
            background: #ffeef0;
            border-color: #ffdce0;
            color: #d73a49;
        }

        .job-description {
            color: #586069;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            font-size: 1rem;
        }

        .job-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.75rem;
            max-width: 600px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            background-color: #f6f8fa;
            border-radius: 10px;
        }

        .detail-label {
            font-size: 1rem;
            color: #6a737d;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0;
            white-space: nowrap;
        }

        .detail-value {
            font-weight: 900;
            color: #24292e;
            font-size: 1.1rem;
            white-space: nowrap;
        }

        .skills-section { margin-bottom: 1.75rem; }

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
            font-size: 16px;
            font-weight: 600;
            border: 1px solid #c8e1ff;
        }

        .job-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #e1e4e8;
            flex-wrap: wrap;
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
        .btn-secondary:hover {
            background-color: #4c5561;
            transform: translateY(-1px);
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

        .btn-danger {
            background-color: #d73a49;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c7303f;
            transform: translateY(-1px);
        }

        /* Empty */
        .empty-card {
            border-radius: 16px;
            background: #fff;
            border: 1px solid #e1e4e8;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 2.5rem;
            text-align: center;
            color: #6a737d;
        }
        .empty-card p {
            font-size: 1.05rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
    {{-- 上部ヘッダー（ここは除外対象なので既存パーツを使用） --}}
    @include('partials.corporate-header')

    <main class="main-content max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">応募一覧</h1>

            <div class="jobs-grid grid grid-cols-1 gap-5 lg:gap-6" id="jobs-grid">
                @forelse($applications as $app)
                    @php
                        $job = $app->job;
                        $company = $job->company ?? null;
                        $thread = $app->thread ?? null;

                        // 未読（存在しない場合はfalse）
                        $isUnread = $app->is_unread ?? false;

                        // ステータス表示（既存の Application 定数が使える場合はそれを使う）
                        $statusLabels = [
                            \App\Models\Application::STATUS_PENDING => '未対応',
                            \App\Models\Application::STATUS_IN_PROGRESS => '対応中',
                            \App\Models\Application::STATUS_CLOSED => 'クローズ',
                        ];
                        $statusLabel = $statusLabels[$app->status] ?? ($app->status ?? '不明');

                        // ステータス用のCSSクラス名
                        $statusClassMap = [
                            \App\Models\Application::STATUS_PENDING => 'status-pending',
                            \App\Models\Application::STATUS_IN_PROGRESS => 'status-interview',
                            \App\Models\Application::STATUS_CLOSED => 'status-closed',
                        ];
                        $statusClass = $statusClassMap[$app->status] ?? '';

                        // 報酬表示
                        $rewardText = '';
                        if ($job && $job->reward_type === 'monthly') {
                            $rewardText = ($job->min_rate / 10000) . '〜' . ($job->max_rate / 10000) . '万円';
                        } elseif ($job && $job->reward_type === 'hourly') {
                            $rewardText = number_format($job->min_rate) . '〜' . number_format($job->max_rate) . '円/時';
                        }

                        // スキル（カンマ区切り）
                        $skills = [];
                        if ($job && !empty($job->required_skills_text)) {
                            $skills = array_map('trim', explode(',', $job->required_skills_text));
                            $skills = array_filter($skills);
                        }

                        // クローズ済みか
                        $isClosed = ($app->status ?? null) === \App\Models\Application::STATUS_CLOSED;
                    @endphp

                    <div class="job-card {{ $isClosed ? 'closed-job' : '' }} p-5 md:p-7" role="button" tabindex="0">
                        <div class="job-header">
                            <div>
                                <h2 class="job-title">{{ $job->title ?? '案件' }}</h2>
                                <div class="company-name">{{ $company->name ?? '' }}</div>
                            </div>
                            <div class="job-meta">
                                @if($isUnread)
                                    <span class="chip unread">未読</span>
                                @endif
                                <span class="chip {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                        </div>

                        {{-- 応募メッセージ（応募一覧ページも同じ構造で表示） --}}
                        <p class="job-description">{{ $app->message ?? '' }}</p>

                        <div class="job-details grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <div class="detail-item">
                                <div class="detail-label">想定稼働時間／期間</div>
                                <div class="detail-value">{{ $job->work_time_text ?? '' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">報酬</div>
                                <div class="detail-value">{{ $rewardText }}</div>
                            </div>
                        </div>

                        @if(count($skills) > 0)
                            <div class="skills-section">
                                <div class="skills-title">必要スキル</div>
                                <div class="skills">
                                    @foreach($skills as $skill)
                                        <span class="skill-tag">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="job-actions flex flex-col md:flex-row gap-3 border-t border-slate-200 pt-4 mt-5">
                            {{-- 企業側に案件詳細導線があるならここをそのルートへ差し替え --}}
                            @if($job)
                                <a href="{{ route('corporate.jobs.show', ['job' => $job->id]) }}" class="btn btn-secondary w-full md:flex-1">案件詳細</a>
                            @else
                                <span class="btn btn-secondary w-full md:flex-1" style="opacity:0.6;cursor:not-allowed;">案件詳細</span>
                            @endif

                            @if($thread)
                                <a href="{{ route('corporate.threads.show', ['thread' => $thread->id]) }}" class="btn btn-primary w-full md:flex-1">チャットを開く</a>
                            @else
                                <span class="btn btn-primary w-full md:flex-1" style="opacity:0.6;cursor:not-allowed;">チャット（準備中）</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-card">
                        <p>応募はありません。</p>
                    </div>
                @endforelse
            </div>

            <div style="margin-top:2rem;">
                {{ $applications->links() }}
            </div>
        </div>
    </main>
</body>
</html>