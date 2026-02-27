<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>契約（スレッド）- AITECH</title>
    @include('partials.corporate-header-style')
    <style>
        .row { display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; }
        .muted { color:#6a737d; font-weight:700; }
        .badge { display:inline-flex; padding: 0.2rem 0.6rem; border-radius: 999px; border:1px solid #e1e4e8; background:#fafbfc; font-weight:900; font-size: 12px; margin-right: 0.25rem; }
        .card-link { display:block; text-decoration:none; color:inherit; }
        .card-link:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.18); border-radius: 14px; }
        .filter-row { display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap; }
        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 0.9rem;
            border: 1px solid #000;
            background: #fff;
            color: #000;
            border-radius: 10px;
            font-weight: 900;
            font-size: 0.95rem;
            text-decoration: none;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

    @php
        $statusLabels = [
            'draft' => '下書き',
            'proposed' => '提示中',
            'negotiating' => '交渉中',
            'ready_to_sign' => '署名待ち',
            'signed' => '締結済み',
            'active' => '履行中',
            'completed' => '完了',
            'terminated' => '終了',
            'archived' => 'アーカイブ',
        ];
        $selectedStatus = $selectedStatus ?? null;
        $filters = [
            '' => '全て',
            'active' => '履行中',
            'completed' => '完了',
            'draft' => '下書き',
            'proposed' => '提示中',
            'negotiating' => '交渉中',
            'ready_to_sign' => '署名待ち',
            'signed' => '締結済み',
            'terminated' => '終了',
            'archived' => 'アーカイブ',
        ];
    @endphp

    <main class="main-content max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">契約（スレッド）</h1>
            <p class="page-subtitle" style="margin-bottom:1.25rem;">
                企業: {{ $thread->company->name ?? '企業' }}
                @if($thread->job) / 案件: {{ $thread->job->title }} @endif
            </p>

            <div class="filter-row" style="margin-bottom:1.5rem;">
                <a class="btn btn-primary" href="{{ route('corporate.threads.show', ['thread' => $thread]) }}">スレッドへ</a>

                @foreach($filters as $key => $label)
                    @php
                        $isActive = ($key === '' && ($selectedStatus === null || $selectedStatus === '')) || ($key !== '' && $selectedStatus === $key);
                    @endphp
                    <a
                        class="btn {{ $isActive ? 'btn-primary' : '' }}"
                        href="{{ route('corporate.threads.contracts.index', array_filter(['thread' => $thread, 'status' => ($key !== '' ? $key : null)])) }}"
                    >{{ $label }}</a>
                @endforeach
            </div>

            @forelse($contracts as $contract)
                <div class="card mb-5">
                    <div style="display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; align-items:center;">
                        <div>
                            <div style="font-weight:900;">v{{ $contract->version }} / {{ $contract->contract_type }}</div>
                            <div class="muted" style="margin-top:0.25rem;">
                                @php $sl = $statusLabels[$contract->status] ?? $contract->status; @endphp
                            </div>
                        </div>
                        <div style="display:flex; gap:0.5rem; align-items:center;">
                            <span class="status-pill">{{ $sl }}</span>
                            <a class="btn btn-primary" href="{{ route('corporate.contracts.show', ['contract' => $contract]) }}">表示</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="muted">契約はまだありません。</div>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
