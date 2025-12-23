<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可チェック（true のため、このリクエストは誰でも送信可能。必要ならログイン必須などに変更）
        return true;
    }

    public function rules(): array
    {
        return [
            // 会社名（必須・文字列・255文字以内）
            'company_name' => ['required', 'string', 'max:255'],
            // 会社概要（任意・文字列・2000文字以内）
            'overview' => ['nullable', 'string', 'max:2000'],
            // 担当者名（任意・文字列・255文字以内）
            'contact_name' => ['nullable', 'string', 'max:255'],
            // 部署名（任意・文字列・255文字以内）
            'department' => ['nullable', 'string', 'max:255'],
            // 紹介文/メッセージ（任意・文字列・2000文字以内）
            'introduction' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => '企業名を入力してください。',
            'company_name.string' => '企業名は文字列で入力してください。',
            'company_name.max' => '企業名は255文字以内で入力してください。',

            'overview.string' => '会社概要は文字列で入力してください。',
            'overview.max' => '会社概要は2000文字以内で入力してください。',

            'contact_name.string' => '担当者名は文字列で入力してください。',
            'contact_name.max' => '担当者名は255文字以内で入力してください。',

            'department.string' => '部署名は文字列で入力してください。',
            'department.max' => '部署名は255文字以内で入力してください。',

            'introduction.string' => '自己紹介は文字列で入力してください。',
            'introduction.max' => '自己紹介は2000文字以内で入力してください。',
        ];
    }
}

