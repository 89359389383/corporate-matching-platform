<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>契約一覧（法人）- AITECH</title>
    @include('partials.corporate-header-style')
    <style>
        .row { display:flex; justify-content: space-between; gap: 1rem; align-items: baseline; flex-wrap: wrap; }
        .muted { color:#6a737d; font-weight:700; }
        .badge { display:inline-flex; padding: 0.2rem 0.6rem; border-radius: 999px; border:1px solid #e1e4e8; background:#fafbfc; font-weight:900; font-size: 12px; margin-right: 0.25rem; }
        .card-link { display:block; text-decoration:none; color:inherit; }
        .card-link:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.18); border-radius: 14px; }
        .actions { font-weight: 900; color:#0366d6; white-space: nowrap; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

    <main class="main-content max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">契約</h1>
            <p class="page-subtitle">自分が当事者の契約一覧です（作成はできません）。</p>

            @foreach($contracts as $contract)
                <a class="card card-link mb-5" href="{{ route('corporate.contracts.show', ['contract' => $contract]) }}">
                    <div class="row">
                        <div>
                            <div style="font-weight:900;font-size:1.1rem;">
                                {{ $contract->company->name ?? '企業' }}
                                @if($contract->job)
                                    / {{ $contract->job->title }}
                                @endif
                            </div>
                            <div class="muted" style="margin-top:0.25rem;">
                                <span class="badge">状態: {{ $contract->status }}</span>
                                <span class="badge">タイプ: {{ $contract->contract_type }}</span>
                                <span class="badge">版: v{{ $contract->version }}</span>
                                <span class="badge">署名: {{ $contract->signatures->count() }}/2</span>
                            </div>
                        </div>
                        <div class="actions">詳細へ</div>
                    </div>
                </a>
            @endforeach

            <div style="margin-top:2rem;">
                {{ $contracts->links() }}
            </div>
        </div>
    </main>
</body>
</html>
