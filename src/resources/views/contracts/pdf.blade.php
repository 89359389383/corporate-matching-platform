<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>契約書</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 10px; }
        h2 { font-size: 14px; margin: 18px 0 8px; }
        .meta { margin: 0 0 12px; color: #444; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        th { background: #f5f5f5; text-align: left; }
        .small { font-size: 11px; color: #444; }
        .sig { margin-top: 18px; }
    </style>
</head>
<body>
    <h1>契約書</h1>
    <div class="meta">
        契約ID: {{ $contract->id }} / 版: v{{ $contract->version }} / タイプ: {{ $contract->contract_type }}<br>
        企業: {{ $contract->company->name ?? '' }}<br>
        法人: {{ $contract->corporate->display_name ?? ($contract->corporate->corporation_name ?? '') }}<br>
        案件: {{ $contract->job ? $contract->job->title : '（スカウト）' }}<br>
        締結日時: {{ $contract->signed_at ? $contract->signed_at->format('Y-m-d H:i') : '' }}
    </div>

    @php $t = $contract->terms_json ?? []; @endphp

    <h2>契約内容</h2>
    <table>
        <tr><th>契約期間</th><td>{{ $t['contract_period'] ?? '' }}</td></tr>
        <tr><th>取引条件</th><td style="white-space:pre-wrap;">{{ $t['trade_terms'] ?? '' }}</td></tr>
        <tr><th>金額</th><td>{{ $t['amount'] ?? '' }}</td></tr>
        <tr><th>支払条件</th><td style="white-space:pre-wrap;">{{ $t['payment_terms'] ?? '' }}</td></tr>
        <tr><th>成果物</th><td style="white-space:pre-wrap;">{{ $t['deliverables'] ?? '' }}</td></tr>
        <tr><th>納期</th><td style="white-space:pre-wrap;">{{ $t['due_date'] ?? '' }}</td></tr>
        <tr><th>業務範囲</th><td style="white-space:pre-wrap;">{{ $t['scope'] ?? '' }}</td></tr>
        <tr><th>特約</th><td style="white-space:pre-wrap;">{{ $t['special_terms'] ?? '' }}</td></tr>
        <tr><th>自由記述</th><td style="white-space:pre-wrap;">{{ $t['free_text'] ?? '' }}</td></tr>
    </table>

    <div class="sig">
        <h2>署名ログ（電子同意）</h2>
        <table>
            <tr>
                <th>種別</th>
                <th>署名者ID</th>
                <th>IP</th>
                <th>UA</th>
                <th>署名日時</th>
                <th>文面ハッシュ</th>
            </tr>
            @foreach($contract->signatures as $s)
                <tr>
                    <td>{{ $s->signer_type }}</td>
                    <td>{{ $s->signer_id }}</td>
                    <td>{{ $s->ip }}</td>
                    <td class="small">{{ $s->user_agent }}</td>
                    <td>{{ $s->signed_at ? $s->signed_at->format('Y-m-d H:i') : '' }}</td>
                    <td class="small">{{ $s->document_hash }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>

