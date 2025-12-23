<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可チェック（true のため、このリクエストは誰でも送信可能。必要ならログイン必須などに変更）
        return true;
    }

    public function rules(): array
    {
        return [
            // 対象フリーランス（必須）
            'freelancer_id' => ['required', 'integer', 'exists:freelancers,id'],
            // 任意の案件（任意）
            'job_id' => ['nullable', 'integer', 'exists:jobs,id'],
            // スカウト時に送るメッセージ本文（必須・文字列・2000文字以内）
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'freelancer_id.required' => 'スカウトするフリーランスを選択してください。',
            'freelancer_id.integer' => 'フリーランスの指定が不正です。',
            'freelancer_id.exists' => '選択されたフリーランスが見つかりません。',

            'job_id.integer' => '案件の指定が不正です。',
            'job_id.exists' => '選択された案件が見つかりません。',

            'message.required' => 'スカウトメッセージを入力してください。',
            'message.string' => 'スカウトメッセージは文字列で入力してください。',
            'message.max' => 'スカウトメッセージは2000文字以内で入力してください。',
        ];
    }
}

