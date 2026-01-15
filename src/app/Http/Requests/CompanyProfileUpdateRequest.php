<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可チェック（true のため、このリクエストは誰でも送信可能。必要ならログイン必須などに変更）
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'overview' => ['nullable', 'string', 'max:2000'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'introduction' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '会社名は必須です。',
            'name.string' => '会社名は文字列で入力してください。',
            'name.max' => '会社名は255文字以内で入力してください。',
            'overview.string' => '概要は文字列で入力してください。',
            'overview.max' => '概要は2000文字以内で入力してください。',
            'contact_name.string' => '担当者名は文字列で入力してください。',
            'contact_name.max' => '担当者名は255文字以内で入力してください。',
            'department.string' => '部署は文字列で入力してください。',
            'department.max' => '部署は255文字以内で入力してください。',
            'introduction.string' => '紹介文は文字列で入力してください。',
            'introduction.max' => '紹介文は2000文字以内で入力してください。',
        ];
    }
}

