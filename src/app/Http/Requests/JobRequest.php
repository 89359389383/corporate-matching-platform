<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Job;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可チェック（true のため、このリクエストは誰でも送信可能。必要ならログイン必須などに変更）
        return true;
    }

    public function rules(): array
    {
        return [
            // 案件名（必須）
            'title' => ['required', 'string', 'max:255'],
            // 説明（必須）
            'description' => ['required', 'string', 'max:5000'],
            // 必須スキル（任意）
            'required_skills_text' => ['nullable', 'string', 'max:2000'],
            // 報酬種別（任意）
            'reward_type' => ['nullable', 'string', 'max:50'],
            // 報酬レンジ（任意）
            'min_rate' => ['nullable', 'integer', 'min:0'],
            'max_rate' => ['nullable', 'integer', 'min:0'],
            // 稼働条件（任意）
            'work_time_text' => ['nullable', 'string', 'max:2000'],
            // ステータス（必須）
            'status' => ['required', 'integer', 'in:' . Job::STATUS_DRAFT . ',' . Job::STATUS_PUBLISHED . ',' . Job::STATUS_STOPPED],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '案件名を入力してください。',
            'title.string' => '案件名は文字列で入力してください。',
            'title.max' => '案件名は255文字以内で入力してください。',

            'description.required' => '説明を入力してください。',
            'description.string' => '説明は文字列で入力してください。',
            'description.max' => '説明は5000文字以内で入力してください。',

            'required_skills_text.string' => '必須スキルは文字列で入力してください。',
            'required_skills_text.max' => '必須スキルは2000文字以内で入力してください。',

            'reward_type.string' => '報酬種別は文字列で入力してください。',
            'reward_type.max' => '報酬種別は50文字以内で入力してください。',

            'min_rate.integer' => '報酬下限は整数で入力してください。',
            'min_rate.min' => '報酬下限は0以上で入力してください。',

            'max_rate.integer' => '報酬上限は整数で入力してください。',
            'max_rate.min' => '報酬上限は0以上で入力してください。',

            'work_time_text.string' => '稼働条件は文字列で入力してください。',
            'work_time_text.max' => '稼働条件は2000文字以内で入力してください。',

            'status.required' => 'ステータスを指定してください。',
            'status.integer' => 'ステータスの指定が不正です。',
            'status.in' => 'ステータスの指定が不正です。',
        ];
    }
}

