<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可チェック（true のため、このリクエストは誰でも送信可能。必要ならログイン必須などに変更）
        return true;
    }

    public function rules(): array
    {
        return [
            // 応募時に送るメッセージ本文（必須・文字列）
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => '応募メッセージを入力してください。',
            'message.string' => '応募メッセージは文字列で入力してください。',
            'message.max' => '応募メッセージは2000文字以内で入力してください。',
        ];
    }
}

