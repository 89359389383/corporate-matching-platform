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
            'required_skills_text' => ['nullable', 'string', 'max:500'],
            // 報酬種別（必須）
            'reward_type' => ['required', 'string', 'in:monthly,hourly'],
            // 報酬レンジ（必須）
            'min_rate' => ['required', 'integer', 'min:0'],
            'max_rate' => ['required', 'integer', 'min:0', 'gte:min_rate'],
            // 稼働条件（必須）
            'work_time_text' => ['required', 'string', 'max:100'],
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
            'required_skills_text.max' => '必須スキルは500文字以内で入力してください。',

            'reward_type.required' => '報酬タイプを選択してください。',
            'reward_type.string' => '報酬タイプは文字列で入力してください。',
            'reward_type.in' => '報酬タイプは「月額/案件単価」または「時給」を選択してください。',

            'min_rate.required' => '最低単価を入力してください。',
            'min_rate.integer' => '最低単価は整数で入力してください。',
            'min_rate.min' => '最低単価は0以上で入力してください。',

            'max_rate.required' => '最高単価を入力してください。',
            'max_rate.integer' => '最高単価は整数で入力してください。',
            'max_rate.min' => '最高単価は0以上で入力してください。',
            'max_rate.gte' => '最高単価は最低単価以上で入力してください。',

            'work_time_text.required' => '稼働条件を入力してください。',
            'work_time_text.string' => '稼働条件は文字列で入力してください。',
            'work_time_text.max' => '稼働条件は100文字以内で入力してください。',

            'status.required' => 'ステータスを指定してください。',
            'status.integer' => 'ステータスの指定が不正です。',
            'status.in' => 'ステータスの指定が不正です。',
        ];
    }
}

