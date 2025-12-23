<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可チェック（true のため、このリクエストは誰でも送信可能。必要ならログイン必須などに変更）
        return true;
    }

    public function rules(): array
    {
        return [
            // メッセージ本文（必須・文字列・2000文字以内）
            'content' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'メッセージ本文を入力してください。',
            'content.string' => 'メッセージ本文は文字列で入力してください。',
            'content.max' => 'メッセージ本文は2000文字以内で入力してください。',
        ];
    }

    protected function prepareForValidation(): void
    {
        // 互換用：body で送られてきた場合は content に寄せる
        if ($this->has('body') && !$this->has('content')) {
            $this->merge([
                'content' => $this->input('body'),
            ]);
        }
    }
}

