<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyContractUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contract_type' => ['required', 'string', Rule::in(['nda', 'basic', 'individual'])],

            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],

            'scope' => ['required', 'string', 'max:5000'],
            'amount' => ['required', 'string', 'max:1000'],
            'payment_terms' => ['required', 'string', 'max:5000'],

            'deliverables' => ['required', 'string', 'max:5000'],
            'due_date' => ['required', 'string', 'max:2000'],

            'contract_period' => ['required', 'string', 'max:2000'],
            'trade_terms' => ['required', 'string', 'max:5000'],
            'special_terms' => ['nullable', 'string', 'max:5000'],
            'free_text' => ['nullable', 'string', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'contract_type.required' => '契約タイプを選択してください。',
            'contract_type.in' => '契約タイプの指定が不正です。',

            'start_date.required' => '開始日を入力してください。',
            'start_date.date' => '開始日は日付形式で入力してください。',

            'end_date.required' => '終了日を入力してください。',
            'end_date.date' => '終了日は日付形式で入力してください。',
            'end_date.after_or_equal' => '終了日は開始日以降の日付を入力してください。',

            'scope.required' => '業務範囲を入力してください。',
            'scope.string' => '業務範囲は文字列で入力してください。',
            'scope.max' => '業務範囲は5000文字以内で入力してください。',

            'amount.required' => '金額を入力してください。',
            'amount.string' => '金額は文字列で入力してください。',
            'amount.max' => '金額は1000文字以内で入力してください。',

            'payment_terms.required' => '支払条件を入力してください。',
            'payment_terms.string' => '支払条件は文字列で入力してください。',
            'payment_terms.max' => '支払条件は5000文字以内で入力してください。',

            'deliverables.required' => '成果物を入力してください。',
            'deliverables.string' => '成果物は文字列で入力してください。',
            'deliverables.max' => '成果物は5000文字以内で入力してください。',

            'due_date.required' => '納期を入力してください。',
            'due_date.string' => '納期は文字列で入力してください。',
            'due_date.max' => '納期は2000文字以内で入力してください。',

            'contract_period.required' => '契約期間を入力してください。',
            'contract_period.string' => '契約期間は文字列で入力してください。',
            'contract_period.max' => '契約期間は2000文字以内で入力してください。',

            'trade_terms.required' => '取引条件を入力してください。',
            'trade_terms.string' => '取引条件は文字列で入力してください。',
            'trade_terms.max' => '取引条件は5000文字以内で入力してください。',

            'special_terms.string' => '特約は文字列で入力してください。',
            'special_terms.max' => '特約は5000文字以内で入力してください。',

            'free_text.string' => '自由記述は文字列で入力してください。',
            'free_text.max' => '自由記述は10000文字以内で入力してください。',
        ];
    }
}

