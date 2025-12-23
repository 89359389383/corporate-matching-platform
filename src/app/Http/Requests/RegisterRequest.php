<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可チェック（true のため、このリクエストは誰でも送信可能。必要ならログイン必須などに変更）
        return true;
    }

    public function rules(): array
    {
        return [
            // 登録用メールアドレス（必須・メール形式・users.email で重複不可）
            'email' => ['required', 'email', 'unique:users,email'],
            // 登録用パスワード（必須・8文字以上・確認用パスワードと一致）
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 確認用パスワード（必須）
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスの形式が正しくありません。',
            'email.unique' => 'このメールアドレスは既に登録されています。',

            'password.required' => 'パスワードを入力してください。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.confirmed' => 'パスワード（確認）が一致しません。',

            'password_confirmation.required' => 'パスワード（確認）を入力してください。',
        ];
    }
}

