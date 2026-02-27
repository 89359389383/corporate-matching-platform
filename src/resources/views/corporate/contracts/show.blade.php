<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>契約表示（法人）- AITECH</title>
    @include('partials.corporate-header-style')
    <style>
        .row { display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; }
        .badge { display:inline-flex; padding: 0.2rem 0.6rem; border-radius: 999px; border:1px solid #e1e4e8; background:#fafbfc; font-weight:900; font-size: 12px; margin-right: 0.25rem; }
        .btn-outline { display:inline-flex; padding: 0.65rem 0.95rem; border-radius: 10px; font-weight: 900; border:1px solid #e1e4e8; background:#fff; text-decoration:none; color:#111827; cursor:pointer; }
        .btn-outline:hover { opacity:0.92; }
        .k { color:#6a737d; font-weight:900; width: 180px; }
        .v { font-weight:800; white-space:pre-wrap; }
        .contract-table { width:100%; border-collapse: collapse; }
        .contract-table td { border-top:1px solid #e1e4e8; padding: 0.7rem 0.5rem; vertical-align:top; }
        .muted { color:#6a737d; font-weight:700; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

    <main class="main-content max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">契約</h1>
            <p class="page-subtitle">
                v{{ $contract->version }} / タイプ: {{ $contract->contract_type }} / 状態: {{ $contract->status }}
                @if($contract->isCurrent())（current）@endif
            </p>

            @if(session('success'))
                <div class="card mb-5" style="border-color:#b7f5c3; background:#e6ffed; font-weight:900;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="card mb-5" style="border-color:#ffccd2; background:#fff5f5; font-weight:900;">{{ session('error') }}</div>
            @endif

            <div class="card mb-5">
                <div class="row">
                    <a class="btn-outline" href="{{ route('corporate.threads.contracts.index', ['thread' => $contract->thread_id]) }}">スレッドの契約一覧へ</a>
                    <a class="btn-outline" href="{{ route('corporate.threads.show', ['thread' => $contract->thread_id]) }}">スレッドへ</a>

                    @if($contract->status === 'proposed' && $contract->isCurrent())
                        <form method="POST" action="{{ route('corporate.contracts.agree', ['contract' => $contract]) }}" style="display:inline;">
                            @csrf
                            <button class="btn btn-primary" type="submit">同意する</button>
                        </form>
                        <form method="POST" action="{{ route('corporate.contracts.return', ['contract' => $contract]) }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="body" value="修正をお願いします。">
                            <button class="btn-outline" type="submit">差し戻し（簡易）</button>
                        </form>
                    @endif

                    @if($contract->pdf_path)
                        <a class="btn-outline" href="{{ route('corporate.contracts.pdf', ['contract' => $contract]) }}">PDF</a>
                    @endif
                </div>
            </div>

            @if($contract->status === 'proposed' && $contract->isCurrent())
                <div class="card mb-5">
                    <div style="font-weight:900; margin-bottom:0.6rem;">差し戻し（詳細）</div>
                    <form method="POST" action="{{ route('corporate.contracts.return', ['contract' => $contract]) }}">
                        @csrf
                        <textarea name="body" rows="3" style="width:100%; padding:0.7rem; border:2px solid #e1e4e8; border-radius:10px; font-size:0.95rem;" placeholder="修正依頼コメント">{{ old('body') }}</textarea>
                        @error('body') <div style="color:#dc2626;font-weight:900;">{{ $message }}</div> @enderror
                        <div style="margin-top:0.75rem;">
                            <button class="btn btn-secondary" type="submit">差し戻す</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="card mb-5">
                <div style="font-weight:900; margin-bottom:0.6rem;">基本情報</div>
                <table class="contract-table">
                    <tr><td class="k">企業</td><td class="v">{{ $contract->company->name ?? '' }}</td></tr>
                    <tr><td class="k">案件</td><td class="v">{{ $contract->job ? $contract->job->title : '（スカウト）' }}</td></tr>
                    <tr><td class="k">開始日</td><td class="v">{{ $contract->start_date ? $contract->start_date->format('Y-m-d') : '' }}</td></tr>
                    <tr><td class="k">終了日</td><td class="v">{{ $contract->end_date ? $contract->end_date->format('Y-m-d') : '' }}</td></tr>
                    <tr><td class="k">提示日時</td><td class="v">{{ $contract->proposed_at ? $contract->proposed_at->format('Y-m-d H:i') : '' }}</td></tr>
                    <tr><td class="k">締結日時</td><td class="v">{{ $contract->signed_at ? $contract->signed_at->format('Y-m-d H:i') : '' }}</td></tr>
                    <tr><td class="k">文面ハッシュ</td><td class="v">{{ $contract->document_hash ?? '' }}</td></tr>
                    <tr><td class="k">PDFハッシュ</td><td class="v">{{ $contract->pdf_hash ?? '' }}</td></tr>
                </table>
            </div>

            <div class="card mb-5">
                <div style="font-weight:900; margin-bottom:0.6rem;">契約本文</div>
                @php $t = $contract->terms_json ?? []; @endphp
                <table class="contract-table">
                    <tr><td class="k">法人名</td><td class="v">{{ $t['corporate_name'] ?? '' }}</td></tr>
                    <tr><td class="k">案件名</td><td class="v">{{ $t['job_title'] ?? '' }}</td></tr>
                    <tr><td class="k">契約期間</td><td class="v">{{ $t['contract_period'] ?? '' }}</td></tr>
                    <tr><td class="k">取引条件</td><td class="v">{{ $t['trade_terms'] ?? '' }}</td></tr>
                    <tr><td class="k">金額</td><td class="v">{{ $t['amount'] ?? '' }}</td></tr>
                    <tr><td class="k">支払条件</td><td class="v">{{ $t['payment_terms'] ?? '' }}</td></tr>
                    <tr><td class="k">成果物</td><td class="v">{{ $t['deliverables'] ?? '' }}</td></tr>
                    <tr><td class="k">納期</td><td class="v">{{ $t['due_date'] ?? '' }}</td></tr>
                    <tr><td class="k">業務範囲</td><td class="v">{{ $t['scope'] ?? '' }}</td></tr>
                    <tr><td class="k">特約</td><td class="v">{{ $t['special_terms'] ?? '' }}</td></tr>
                    <tr><td class="k">自由記述</td><td class="v">{{ $t['free_text'] ?? '' }}</td></tr>
                </table>
            </div>

            <div class="card mb-5">
                <div style="font-weight:900; margin-bottom:0.6rem;">署名状況</div>
                <div class="row">
                    <span class="badge">署名数: {{ $contract->signatures->count() }}/2</span>
                    <span class="badge">企業: {{ $contract->signatures->where('signer_type','company')->count() ? '済' : '未' }}</span>
                    <span class="badge">法人: {{ $contract->signatures->where('signer_type','corporate')->count() ? '済' : '未' }}</span>
                </div>
            </div>

            @if($contract->changeRequests->count() > 0)
                <div class="card">
                    <div style="font-weight:900; margin-bottom:0.6rem;">差し戻し履歴</div>
                    @foreach($contract->changeRequests as $cr)
                        <div style="border-top:1px solid #e1e4e8; padding-top:0.6rem; margin-top:0.6rem;">
                            <div class="muted" style="font-weight:900;">{{ $cr->created_at ? $cr->created_at->format('Y-m-d H:i') : '' }}</div>
                            <div class="v">{{ $cr->body }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</body>
</html>
