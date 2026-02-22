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
            'name_kana' => ['nullable', 'string', 'max:255'],
            'overview' => ['nullable', 'string', 'max:2000'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'introduction' => ['nullable', 'string', 'max:2000'],

            'email' => ['required', 'email', 'max:255'],
            'corporate_number' => ['required', 'digits:13'],
            'representative_phone' => ['required', 'string', 'max:32'],

            'hq_postal_code' => ['required', 'string', 'max:16'],
            'hq_prefecture' => ['required', 'string', 'max:64'],
            'hq_city' => ['required', 'string', 'max:128'],
            'hq_address' => ['required', 'string', 'max:255'],

            'representative_last_name' => ['required', 'string', 'max:20'],
            'representative_first_name' => ['required', 'string', 'max:20'],
            'representative_last_name_kana' => ['required', 'string', 'max:20'],
            'representative_first_name_kana' => ['required', 'string', 'max:20'],
            'representative_birthdate' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '会社名は必須です。',
            'name.string' => '会社名は文字列で入力してください。',
            'name.max' => '会社名は255文字以内で入力してください。',
            'name_kana.string' => '会社名カナは文字列で入力してください。',
            'name_kana.max' => '会社名カナは255文字以内で入力してください。',
            'overview.string' => '概要は文字列で入力してください。',
            'overview.max' => '概要は2000文字以内で入力してください。',
            'contact_name.string' => '担当者名は文字列で入力してください。',
            'contact_name.max' => '担当者名は255文字以内で入力してください。',
            'department.string' => '部署は文字列で入力してください。',
            'department.max' => '部署は255文字以内で入力してください。',
            'introduction.string' => '紹介文は文字列で入力してください。',
            'introduction.max' => '紹介文は2000文字以内で入力してください。',

            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスの形式が正しくありません。',
            'email.max' => 'メールアドレスは255文字以内で入力してください。',

            'corporate_number.required' => '法人番号を入力してください。',
            'corporate_number.digits' => '法人番号は13桁で入力してください。',

            'representative_phone.required' => '代表電話番号を入力してください。',
            'representative_phone.string' => '代表電話番号は文字列で入力してください。',
            'representative_phone.max' => '代表電話番号は32文字以内で入力してください。',

            'hq_postal_code.required' => '郵便番号を入力してください。',
            'hq_postal_code.string' => '郵便番号は文字列で入力してください。',
            'hq_postal_code.max' => '郵便番号は16文字以内で入力してください。',

            'hq_prefecture.required' => '都道府県を入力してください。',
            'hq_prefecture.string' => '都道府県は文字列で入力してください。',
            'hq_prefecture.max' => '都道府県は64文字以内で入力してください。',

            'hq_city.required' => '市区町村を入力してください。',
            'hq_city.string' => '市区町村は文字列で入力してください。',
            'hq_city.max' => '市区町村は128文字以内で入力してください。',

            'hq_address.required' => '住所を入力してください。',
            'hq_address.string' => '住所は文字列で入力してください。',
            'hq_address.max' => '住所は255文字以内で入力してください。',

            'representative_last_name.required' => '代表者名（姓）を入力してください。',
            'representative_last_name.string' => '代表者名（姓）は文字列で入力してください。',
            'representative_last_name.max' => '代表者名（姓）は20文字以内で入力してください。',

            'representative_first_name.required' => '代表者名（名）を入力してください。',
            'representative_first_name.string' => '代表者名（名）は文字列で入力してください。',
            'representative_first_name.max' => '代表者名（名）は20文字以内で入力してください。',

            'representative_last_name_kana.required' => '代表者名カナ（セイ）を入力してください。',
            'representative_last_name_kana.string' => '代表者名カナ（セイ）は文字列で入力してください。',
            'representative_last_name_kana.max' => '代表者名カナ（セイ）は20文字以内で入力してください。',

            'representative_first_name_kana.required' => '代表者名カナ（メイ）を入力してください。',
            'representative_first_name_kana.string' => '代表者名カナ（メイ）は文字列で入力してください。',
            'representative_first_name_kana.max' => '代表者名カナ（メイ）は20文字以内で入力してください。',

            'representative_birthdate.date' => '代表者の生年月日は日付形式で入力してください。',
        ];
    }
}

