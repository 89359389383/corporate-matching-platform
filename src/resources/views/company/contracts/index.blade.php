<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>契約一覧（企業）- AITECH</title>

    {{-- 上部ヘッダーは既存のものをそのまま使用 --}}
    @include('partials.company-header-style')
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- ✅ ここにCSSを全て集約（ヘッダー以外は「応募した案件」ページと同じ見た目） --}}
    <style>
        /* Base */
        :root {
            --header-height-current: 72px;
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
            gap: 1rem;
        }

        .job-card {
            display: block;
            text-decoration: none;
            color: inherit;
            background: #ffffff;
            border: 1px solid #d9dee7;
            border-radius: 14px;
            padding: 1.35rem 1.5rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
            transition: box-shadow .15s ease, border-color .15s ease;
        }
        .job-card:hover {
            border-color: #c8d1dd;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .title-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.45rem;
        }
        .job-title {
            font-size: 1.9rem;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.25;
        }
        .type-chip {
            display: inline-flex;
            align-items: center;
            border-radius: 8px;
            padding: 0.22rem 0.52rem;
            font-size: 0.78rem;
            font-weight: 900;
            border: 1px solid;
            white-space: nowrap;
        }
        .type-chip.blue {
            color: #2563eb;
            background: #eff6ff;
            border-color: #bfdbfe;
        }
        .type-chip.purple {
            color: #7c3aed;
            background: #f5f3ff;
            border-color: #ddd6fe;
        }
        .company-name {
            color: #64748b;
            font-size: 1.08rem;
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 0.85rem;
        }
        .meta-inline {
            color: #94a3b8;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.2rem 0.65rem;
            font-size: 0.76rem;
            font-weight: 900;
            border: 1px solid;
            white-space: nowrap;
        }
        .status-badge.signed {
            color: #15803d;
            background: #dcfce7;
            border-color: #bbf7d0;
        }
        .status-badge.draft {
            color: #475569;
            background: #f1f5f9;
            border-color: #e2e8f0;
        }
        .status-badge.progress {
            color: #1d4ed8;
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .job-details {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 0.35rem;
        }
        .detail-item {
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
            min-width: 0;
        }
        .detail-icon {
            color: #94a3b8;
            flex: 0 0 auto;
            margin-top: 0.05rem;
        }
        .detail-label {
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 900;
            margin-bottom: 0.15rem;
        }
        .detail-value {
            color: #0f172a;
            font-size: 1.05rem;
            font-weight: 900;
            line-height: 1.35;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .detail-sub {
            color: #64748b;
            font-size: 0.88rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 960px) {
            .job-title { font-size: 1.3rem; }
            .company-name { font-size: 1rem; }
            .job-details { grid-template-columns: 1fr; }
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
    {{-- 上部ヘッダーは全く変えない --}}
    @include('partials.company-header')

    <main class="main-content max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">契約</h1>
            <p class="page-subtitle">自社が当事者の契約一覧です（スレッドに紐づきます）。</p>

            <div class="jobs-grid grid grid-cols-1 gap-5 lg:gap-6" id="jobs-grid">
                @forelse($contracts as $contract)
                    @php
                        $corporate = $contract->corporate ?? null;
                        $job = $contract->job ?? null;

                        $contractStatus = $contract->status ?? '不明';

                        $contractType = $contract->contract_type ?? '不明';
                        $version = $contract->version ?? '';
                        $signedCount = $contract->signatures->count() ?? 0;

                        $title = $corporate->display_name ?? ($corporate->corporation_name ?? '法人');
                        $subTitle = $job ? $job->title : '案件情報なし';

                        $terms = $contract->terms_json;
                        if (is_string($terms)) {
                            $decodedTerms = json_decode($terms, true);
                            $terms = is_array($decodedTerms) ? $decodedTerms : [];
                        } elseif (!is_array($terms)) {
                            $terms = [];
                        }

                        $amountText = $terms['amount'] ?? '未設定';
                        $startDate = $contract->start_date ? $contract->start_date->format('Y/m/d') : '';
                        $endDate = $contract->end_date ? $contract->end_date->format('Y/m/d') : '';
                        $periodText = ($startDate !== '' || $endDate !== '') ? trim($startDate . ' 〜 ' . $endDate) : '未設定';
                        $counterpartySub = $terms['corporate_representative'] ?? ($terms['representative_name'] ?? '-');

                        $statusLabel = $contractStatus;
                        if ($contractStatus === 'completed') {
                            $statusLabel = '完了';
                        } elseif (in_array($contractStatus, ['signed', 'active'], true)) {
                            $statusLabel = '締結済み';
                        } elseif ($contractStatus === 'draft') {
                            $statusLabel = '下書き';
                        }

                        $statusClass = 'progress';
                        if (in_array($contractStatus, ['signed', 'active', 'completed'], true)) {
                            $statusClass = 'signed';
                        } elseif (in_array($contractStatus, ['draft', 'pending', 'closed', 'terminated', 'archived'], true)) {
                            $statusClass = 'draft';
                        }

                        $typeClass = str_contains((string)$contractType, '機密') ? 'purple' : 'blue';
                    @endphp

                    <a href="{{ route('company.contracts.show', ['contract' => $contract]) }}" class="job-card">
                        <div class="job-header">
                            <div>
                                <div class="title-row">
                                    <h2 class="job-title">{{ $title }}</h2>
                                    <span class="type-chip {{ $typeClass }}">{{ $contractType }}</span>
                                </div>
                                <div class="company-name">{{ $subTitle }}</div>
                                <div class="meta-inline">版: v{{ $version }} / 署名: {{ $signedCount }}/2</div>
                            </div>
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>

                        <div class="job-details">
                            <div class="detail-item">
                                <svg class="detail-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <circle cx="8.5" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                    <path d="M20 8v6M23 11h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <div>
                                    <div class="detail-label">当事者</div>
                                    <div class="detail-value">{{ $title }}</div>
                                </div>
                            </div>
                            <div class="detail-item">
                                <svg class="detail-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                    <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <div>
                                    <div class="detail-label">契約期間</div>
                                    <div class="detail-value">{{ $periodText }}</div>
                                </div>
                            </div>
                            <div class="detail-item">
                                <svg class="detail-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <div>
                                    <div class="detail-label">報酬</div>
                                    <div class="detail-value">{{ $amountText }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty-card">
                        <p>契約はありません。</p>
                    </div>
                @endforelse
            </div>

            <div style="margin-top:1rem;">
                {{ $contracts->links() }}
            </div>
        </div>
    </main>
</body>
</html>