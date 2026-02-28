@php
    $ct = old('contract_type', $contractType ?? 'nda');
@endphp

<div style="display:grid; gap:0.9rem;">
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">契約タイプ（必須）</label>
        <select name="contract_type" class="@error('contract_type') is-invalid @enderror" style="width:100%; padding:0.6rem; border:1px solid #e1e4e8; border-radius:10px;">
            <option value="nda" {{ $ct === 'nda' ? 'selected' : '' }}>NDA</option>
            <option value="basic" {{ $ct === 'basic' ? 'selected' : '' }}>基本契約</option>
            <option value="individual" {{ $ct === 'individual' ? 'selected' : '' }}>個別契約</option>
        </select>
        @error('contract_type')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div style="display:flex; gap:0.75rem;">
        <div style="flex:1;">
            <label style="font-weight:900; display:block; margin-bottom:0.35rem;">開始日（必須）</label>
            <input type="date" name="start_date" value="{{ old('start_date', $startDate ?? '') }}" class="@error('start_date') is-invalid @enderror" style="width:100%; padding:0.6rem; border:1px solid #e1e4e8; border-radius:10px;">
            @error('start_date')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div style="flex:1;">
            <label style="font-weight:900; display:block; margin-bottom:0.35rem;">終了日（必須）</label>
            <input type="date" name="end_date" value="{{ old('end_date', $endDate ?? '') }}" class="@error('end_date') is-invalid @enderror" style="width:100%; padding:0.6rem; border:1px solid #e1e4e8; border-radius:10px;">
            @error('end_date')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">契約期間（必須）</label>
        <textarea name="contract_period" rows="2" class="@error('contract_period') is-invalid @enderror" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;">{{ old('contract_period', $terms['contract_period'] ?? '') }}</textarea>
        @error('contract_period')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">取引条件（必須）</label>
        <textarea name="trade_terms" rows="3" class="@error('trade_terms') is-invalid @enderror" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;">{{ old('trade_terms', $terms['trade_terms'] ?? '') }}</textarea>
        @error('trade_terms')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">金額（必須）</label>
        <input name="amount" value="{{ old('amount', $terms['amount'] ?? '') }}" class="@error('amount') is-invalid @enderror" style="width:100%; padding:0.6rem; border:1px solid #e1e4e8; border-radius:10px;">
        @error('amount')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">支払条件（必須）</label>
        <textarea name="payment_terms" rows="3" class="@error('payment_terms') is-invalid @enderror" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;">{{ old('payment_terms', $terms['payment_terms'] ?? '') }}</textarea>
        @error('payment_terms')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">成果物（必須）</label>
        <textarea name="deliverables" rows="3" class="@error('deliverables') is-invalid @enderror" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;">{{ old('deliverables', $terms['deliverables'] ?? '') }}</textarea>
        @error('deliverables')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">納期（必須）</label>
        <textarea name="due_date" rows="2" class="@error('due_date') is-invalid @enderror" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;">{{ old('due_date', $terms['due_date'] ?? '') }}</textarea>
        @error('due_date')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">業務範囲（必須）</label>
        <textarea name="scope" rows="3" class="@error('scope') is-invalid @enderror" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;">{{ old('scope', $terms['scope'] ?? '') }}</textarea>
        @error('scope')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">特約（任意）</label>
        <textarea name="special_terms" rows="3" class="@error('special_terms') is-invalid @enderror" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;">{{ old('special_terms', $terms['special_terms'] ?? '') }}</textarea>
        @error('special_terms')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label style="font-weight:900; display:block; margin-bottom:0.35rem;">自由記述（任意）</label>
        <textarea name="free_text" rows="6" class="@error('free_text') is-invalid @enderror" style="width:100%; padding:0.7rem; border:1px solid #e1e4e8; border-radius:10px;">{{ old('free_text', $terms['free_text'] ?? '') }}</textarea>
        @error('free_text')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
</div>

