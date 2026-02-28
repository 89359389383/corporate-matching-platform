{{-- resources/views/corporate/contracts/show.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>契約表示（法人）- AITECH</title>

    {{-- ✅ 上部ヘッダーは変更しない --}}
    @include('partials.corporate-header-style')
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root{
            --page-bg:#f6f8fb;
            --card:#ffffff;
            --line:#e5e7eb;
            --muted:#6b7280;
            --text:#111827;
            --shadow: 0 1px 2px rgba(16,24,40,.06), 0 6px 18px rgba(16,24,40,.06);
            --radius: 14px;
        }
        body{ background:var(--page-bg); color:var(--text); }
        .container-max{ max-width: 1120px; }
        .card{
            background:var(--card);
            border:1px solid rgba(17,24,39,.08);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .tab{
            position: relative;
            padding:.75rem 1rem;
            font-weight:800;
            color:#374151;
            border-bottom:2px solid transparent;
        }
        .tab.active{
            color:#1d4ed8;
            border-bottom-color:#1d4ed8;
        }
        .pill{
            display:inline-flex;
            align-items:center;
            gap:.4rem;
            padding:.2rem .6rem;
            border-radius: 999px;
            font-weight:800;
            font-size:12px;
            border:1px solid rgba(17,24,39,.10);
            background:#fff;
        }
        /* 色付きアクセントを無効化（上部の青・紫ラインを削除） */
        .pill.purple,
        .pill.blue {
            background: #fff;
            border-color: rgba(17,24,39,.10);
            color: #374151;
        }
        .pill.gray{ background:rgba(107,114,128,.08); border-color:rgba(107,114,128,.20); color:#374151; }
        .btn{
            display:inline-flex;
            align-items:center;
            gap:.5rem;
            padding:.65rem 1rem;
            border-radius: 12px;
            font-weight:900;
            border:1px solid rgba(17,24,39,.12);
            background:#fff;
            color:#111827;
            transition:.15s;
        }
        .btn:hover{ filter:brightness(.98); transform: translateY(-1px); }
        .btn:active{ transform: translateY(0); }
        .btn.primary{
            background:#1d4ed8;
            border-color:#1d4ed8;
            color:#fff;
        }
        .kv{
            display:grid;
            grid-template-columns: 140px 1fr;
            gap: .75rem;
            padding: .65rem 0;
            border-top:1px solid rgba(17,24,39,.08);
        }
        .kv:first-child{ border-top:none; padding-top:0; }
        .k{ color:#6b7280; font-weight:800; font-size: 13px; }
        .v{ font-weight:900; color:#111827; white-space: pre-wrap; }
        .subv{ font-weight:800; color:#374151; white-space: pre-wrap; }
        .check-item{
            display:flex; align-items:flex-start; gap:.6rem;
            padding:.35rem 0;
        }
        .amount-value {
            font-size: 16px !important;
        }
        .toast{
            position: fixed;
            left: 50%;
            bottom: 24px;
            transform: translateX(-50%);
            background: rgba(17,24,39,.92);
            color: #fff;
            padding: .8rem 1.1rem;
            border-radius: 999px;
            font-weight: 900;
            box-shadow: 0 12px 30px rgba(0,0,0,.18);
            opacity: 0;
            pointer-events: none;
            transition: .2s;
            z-index: 9999;
        }
        .toast.show{ opacity:1; }
    </style>
</head>
<body>
@include('partials.corporate-header')

@php
    $t = $contract->terms_json ?? [];

    // ✅ 法人側のデータ参照（ここだけが企業側と違う）
    $corpName   = $t['corporate_name'] ?? '';      // 法人名（terms_jsonにある想定）
    $companyName = $contract->company->name ?? ''; // 企業名
    $jobTitle  = $contract->job ? $contract->job->title : '（スカウト）';

    $start = $contract->start_date ? $contract->start_date->format('Y/m/d') : '';
    $end   = $contract->end_date ? $contract->end_date->format('Y/m/d') : '';

    $createdAt = $contract->created_at ? $contract->created_at->format('Y/m/d H:i') : '';
    $ver = 'v' . ($contract->version ?? '1');

    // ✅ 企業側と完全同一構造でコピー文を生成（文言は必要に応じて自由に）
    $copyLines = [];
    $copyLines[] = "【契約】";
    $contractTitle = $t['title'] ?? ($contract->job ? $contract->job->title : ($contract->contract_type ?? '契約'));
    $copyLines[] = "タイトル: " . $contractTitle;
    $copyLines[] = "作成日: {$createdAt}";
    $copyLines[] = "バージョン: {$ver}";
    $copyLines[] = "タイプ: " . ($contract->contract_type ?? '');
    $copyLines[] = "状態: " . ($contract->status ?? '');
    $copyLines[] = "";
    $copyLines[] = "【契約概要】";
    $copyLines[] = ($t['scope'] ?? '');
    $copyLines[] = "";
    $copyLines[] = "【当事者】";
    $copyLines[] = "企業: {$companyName}";
    $copyLines[] = "法人: {$corpName}";
    $copyLines[] = "";
    $copyLines[] = "【契約期間】";
    $copyLines[] = "開始日: {$start}";
    $copyLines[] = "終了日: {$end}";
    $copyLines[] = "稼働時間: " . ($t['trade_terms'] ?? '');
    $copyLines[] = "";
    $copyLines[] = "【報酬】";
    $copyLines[] = "金額: " . ($t['amount'] ?? '');
    $copyLines[] = "支払条件: " . ($t['payment_terms'] ?? '');
    $copyLines[] = "";
    $copyLines[] = "【成果物】";
    $copyLines[] = (is_array($t['deliverables'] ?? null) ? implode("\n", $t['deliverables']) : ($t['deliverables'] ?? ''));
    $copyLines[] = "";
    $copyLines[] = "【秘密保持】";
    $copyLines[] = "保持期間: " . ($t['confidentiality_period'] ?? '');
    $copyLines[] = "対象範囲: " . ($t['confidentiality_scope'] ?? '');
    $copyLines[] = "";
    $copyLines[] = "【その他条項】";
    $copyLines[] = ($t['special_terms'] ?? '');
    $copyLines[] = ($t['free_text'] ?? '');
    $copyLines[] = "";
    $copyLines[] = "【基本情報（ハッシュ等）】";
    $copyLines[] = "案件: {$jobTitle}";
    $copyLines[] = "提示日時: " . ($contract->proposed_at ? $contract->proposed_at->format('Y/m/d H:i') : '');
    $copyLines[] = "締結日時: " . ($contract->signed_at ? $contract->signed_at->format('Y/m/d H:i') : '');
    $copyLines[] = "文面ハッシュ: " . ($contract->document_hash ?? '');
    $copyLines[] = "PDFハッシュ: " . ($contract->pdf_hash ?? '');

    $copyAllText = implode("\n", $copyLines);

    // ✅ ステータスバッジ判定も企業側と同一
    $statusLabel = $contract->status ?? '';
    $statusPillClass = 'pill gray';
    if (in_array($statusLabel, ['ready_to_sign','proposed','negotiating'], true)) $statusPillClass = 'pill purple';
    if (in_array($statusLabel, ['signed','active'], true)) $statusPillClass = 'pill blue';

    $hasCompanySig = $contract->signatures->where('signer_type', 'company')->count() > 0;
    $hasCorporateSig = $contract->signatures->where('signer_type', 'corporate')->count() > 0;
    $bothUnsigned = (!$hasCompanySig && !$hasCorporateSig);
    $canCorporateSignNow = $bothUnsigned && in_array($contract->status, [\App\Models\Contract::STATUS_DRAFT, \App\Models\Contract::STATUS_PROPOSED, \App\Models\Contract::STATUS_NEGOTIATING], true);
@endphp

<main class="container-max mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- ✅ 企業側と同一：上部（戻る + タイトル + バッジ + アクション） --}}
    <div class="flex flex-col gap-3">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3 min-w-0">
                <a href="{{ route('corporate.contracts.index') }}"
                   class="mt-1 inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                   aria-label="戻る">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div class="min-w-0">
                    <h1 class="text-[22px] sm:text-[26px] font-black tracking-tight truncate">
                        {{ $contractTitle }}
                    </h1>
                    <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-gray-500 font-bold">
                        <span>作成日: {{ $createdAt }}</span>
                        <span class="text-gray-300">/</span>
                        <span>バージョン: {{ $ver }}</span>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="pill blue">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ $contract->contract_type ?? '個別契約' }}
                        </span>
                        <span class="{{ $statusPillClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2">
                @if($bothUnsigned)
                    <form method="POST" action="{{ route('corporate.contracts.agree', ['contract' => $contract]) }}">
                        @csrf
                        <button type="submit" class="btn primary" {{ $canCorporateSignNow ? '' : 'disabled' }} {{ $canCorporateSignNow ? '' : 'style=opacity:.55;cursor:not-allowed;' }}>
                            署名する
                        </button>
                    </form>
                @endif
                {{-- ✅ 企業側と同一：全てコピー --}}
                <button type="button" id="copyAllBtn" class="btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
                        <rect x="2" y="2" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    全てコピー
                </button>

                <!-- PDF出力（非表示） -->

                {{-- 署名ボタンは条件に応じて表示 --}}
            </div>
        </div>
    </div>

    {{-- ✅ 企業側と同一：通知 --}}
    @if(session('success'))
        <div class="mt-5 card p-4 border border-emerald-200 bg-emerald-50 text-emerald-900 font-black">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mt-5 card p-4 border border-rose-200 bg-rose-50 text-rose-900 font-black">
            {{ session('error') }}
        </div>
    @endif
    @include('partials.error-panel')

    {{-- ✅ 企業側と同一：本体（左メイン / 右サイド） --}}
    <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- 左（2列分） --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- 契約概要 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="#111827" stroke-width="2"/>
                            <path d="M12 16v-5" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 8h.01" stroke="#111827" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-black">契約概要</h2>
                        <p class="mt-2 text-gray-700 font-bold leading-relaxed">
                            {{ $t['scope'] ?? '' }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- 契約期間 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="#111827" stroke-width="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">契約期間</h2>

                        <div class="mt-3">
                            <div class="kv">
                                <div class="k">開始日</div>
                                <div class="v">{{ $contract->start_date ? $contract->start_date->format('Y/m/d') : '' }}</div>
                            </div>
                            <div class="kv">
                                <div class="k">終了日</div>
                                <div class="v">{{ $contract->end_date ? $contract->end_date->format('Y/m/d') : '' }}</div>
                            </div>
                            <div class="kv">
                                <div class="k">稼働時間</div>
                                <div class="subv">{{ $t['trade_terms'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 報酬 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M6 3l6 9 6-9" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 12h12" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                            <path d="M6 16h12" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 12v9" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">報酬</h2>

                        <div class="mt-3">
                            <div class="kv">
                                <div class="k">金額</div>
                                <div class="v amount-value">{{ $t['amount'] ?? '' }}</div>
                            </div>
                            <div class="kv">
                                <div class="k">支払条件</div>
                                <div class="subv">{{ $t['payment_terms'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 成果物 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M9 11l3 3L22 4" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">成果物</h2>

                        @php
                            $deliverables = $t['deliverables'] ?? [];
                            if (!is_array($deliverables)) {
                                $tmp = preg_split("/\r\n|\r|\n|、|,/", (string)$deliverables);
                                $deliverables = array_values(array_filter(array_map('trim', $tmp)));
                            }
                        @endphp

                        <div class="mt-3 space-y-1">
                            @foreach($deliverables as $d)
                                <div class="check-item">
                                    <div class="mt-0.5">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" stroke="#22c55e" stroke-width="2"/>
                                            <path d="M8 12l2.5 2.5L16 9" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div class="font-bold text-gray-700">{{ $d }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- 秘密保持 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2l8 4v6c0 5-3.5 9.5-8 10-4.5-.5-8-5-8-10V6l8-4z" stroke="#111827" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">秘密保持</h2>
                        <div class="mt-3">
                            <div class="kv">
                                <div class="k">保持期間</div>
                                <div class="v">{{ $t['confidentiality_period'] ?? '' }}</div>
                            </div>
                            <div class="kv">
                                <div class="k">対象範囲</div>
                                <div class="subv">{{ $t['confidentiality_scope'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- その他条項 --}}
            <section class="card p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#111827" stroke-width="2"/>
                            <path d="M14 2v6h6" stroke="#111827" stroke-width="2"/>
                            <path d="M8 13h8M8 17h8M8 9h3" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2 class="text-lg font-black">その他条項</h2>
                        <div class="mt-3 text-gray-700 font-bold leading-relaxed">
                            {{ $t['special_terms'] ?? '' }}
                            @if(!empty($t['free_text']))
                                <div class="mt-3 text-gray-600 font-bold whitespace-pre-wrap">{{ $t['free_text'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- 右（サイド） --}}
        <aside class="space-y-5">
            {{-- 当事者（企業側と同一構造・同一クラス） --}}
            <section class="card p-5">
                <h3 class="text-lg font-black">当事者</h3>

                <div class="mt-4 space-y-4">
                    <div class="flex gap-3">
                        <div class="mt-0.5">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M3 21h18" stroke="#9ca3af" stroke-width="2" stroke-linecap="round"/>
                                <path d="M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17" stroke="#9ca3af" stroke-width="2"/>
                                <path d="M9 7h1M9 10h1M9 13h1M14 7h1M14 10h1M14 13h1" stroke="#9ca3af" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-black text-gray-500">企業</div>
                            <div class="mt-1 font-black text-gray-900 break-words">{{ $companyName }}</div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <div class="flex gap-3">
                        <div class="mt-0.5">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M20 21a8 8 0 0 0-16 0" stroke="#9ca3af" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="7" r="4" stroke="#9ca3af" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-black text-gray-500">法人</div>
                            <div class="mt-1 font-black text-gray-900 break-words">{{ $corpName }}</div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ステータス --}}
            <section class="card p-5">
                <h3 class="text-lg font-black">ステータス</h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <div class="text-xs font-black text-gray-500">現在のステータス</div>
                        <div class="mt-2">
                            <span class="{{ $statusPillClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-black text-gray-500">作成日時</div>
                        <div class="mt-1 font-black text-gray-900">{{ $createdAt }}</div>
                    </div>
                </div>
            </section>

            {{-- 基本情報（法人側の情報項目を維持しつつ、クラス/構造は同一） --}}
            <section class="card p-5">
                <h3 class="text-lg font-black">基本情報</h3>
                <div class="mt-3">
                    <div class="kv">
                        <div class="k">企業</div>
                        <div class="subv">{{ $companyName }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">案件</div>
                        <div class="subv">{{ $jobTitle }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">開始日</div>
                        <div class="subv">{{ $contract->start_date ? $contract->start_date->format('Y-m-d') : '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">終了日</div>
                        <div class="subv">{{ $contract->end_date ? $contract->end_date->format('Y-m-d') : '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">提示日時</div>
                        <div class="subv">{{ $contract->proposed_at ? $contract->proposed_at->format('Y-m-d H:i') : '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">締結日時</div>
                        <div class="subv">{{ $contract->signed_at ? $contract->signed_at->format('Y-m-d H:i') : '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">文面ハッシュ</div>
                        <div class="subv break-all">{{ $contract->document_hash ?? '' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">PDFハッシュ</div>
                        <div class="subv break-all">{{ $contract->pdf_hash ?? '' }}</div>
                    </div>
                </div>
            </section>

            {{-- 署名状況 --}}
            <section class="card p-5">
                <h3 class="text-lg font-black">署名状況</h3>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="pill gray">署名数: {{ $contract->signatures->count() }}/2</span>
                    <span class="pill gray">企業: {{ $contract->signatures->where('signer_type','company')->count() ? '済' : '未' }}</span>
                    <span class="pill gray">法人: {{ $contract->signatures->where('signer_type','corporate')->count() ? '済' : '未' }}</span>
                </div>
            </section>
        </aside>
    </div>

    {{-- 差し戻し履歴 --}}
    @if($contract->changeRequests->count() > 0)
        <section class="mt-5 card p-5">
            <h3 class="text-lg font-black">差し戻し履歴</h3>
            <div class="mt-3 space-y-3">
                @foreach($contract->changeRequests as $cr)
                    <div class="border-t border-gray-100 pt-3">
                        <div class="text-xs font-black text-gray-500">
                            {{ $cr->created_at ? $cr->created_at->format('Y-m-d H:i') : '' }}
                        </div>
                        <div class="mt-2 font-bold text-gray-800 whitespace-pre-wrap">{{ $cr->body }}</div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 監査ログ（法人側で存在するなら表示。存在しないなら削除OK） --}}
    @if(isset($contract->auditLogs))
        <section class="mt-5 card p-5">
            <h3 class="text-lg font-black">監査ログ</h3>
            <div class="mt-3 space-y-2">
                @forelse($contract->auditLogs as $log)
                    <div class="border-t border-gray-100 pt-3 text-sm font-bold text-gray-700">
                        {{ $log->occurred_at ? $log->occurred_at->format('Y-m-d H:i') : '' }}
                        <span class="text-gray-300">/</span> {{ $log->action }}
                        <span class="text-gray-300">/</span> {{ $log->actor_type }}
                    </div>
                @empty
                    <div class="text-sm font-bold text-gray-500">ログはありません</div>
                @endforelse
            </div>
        </section>
    @endif

    {{-- ✅ 1回コピー用（画面には出さない） --}}
    <textarea id="copyAllText" class="sr-only" aria-hidden="true">{{ $copyAllText }}</textarea>
    <div id="toast" class="toast">コピーしました</div>
</main>

<script>
(function(){
    const btn = document.getElementById('copyAllBtn');
    const ta  = document.getElementById('copyAllText');
    const toast = document.getElementById('toast');

    function showToast(message){
        toast.textContent = message;
        toast.classList.add('show');
        clearTimeout(showToast._t);
        showToast._t = setTimeout(()=> toast.classList.remove('show'), 1600);
    }

    async function copyText(text){
        try{
            await navigator.clipboard.writeText(text);
            return true;
        }catch(e){
            try{
                ta.classList.remove('sr-only');
                ta.value = text;
                ta.select();
                ta.setSelectionRange(0, ta.value.length);
                const ok = document.execCommand('copy');
                ta.classList.add('sr-only');
                return ok;
            }catch(_e){
                return false;
            }
        }
    }

    if(btn){
        btn.addEventListener('click', async ()=>{
            const text = (ta && ta.value) ? ta.value : (ta ? ta.textContent : '');
            const ok = await copyText(text);
            showToast(ok ? '全てコピーしました' : 'コピーに失敗しました');
        });
    }
})();
</script>

</body>
</html>