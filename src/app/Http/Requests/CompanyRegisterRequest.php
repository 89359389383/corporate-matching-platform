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
            // 会社名カナ（任意・文字列・255文字以内）
            'company_name_kana' => ['nullable', 'string', 'max:255'],
            // 会社概要（任意・文字列・2000文字以内）
            'overview' => ['nullable', 'string', 'max:2000'],
            // 担当者名（任意・文字列・255文字以内）
            'contact_name' => ['nullable', 'string', 'max:255'],
            // 部署名（任意・文字列・255文字以内）
            'department' => ['nullable', 'string', 'max:255'],
            // 紹介文/メッセージ（任意・文字列・2000文字以内）
            'introduction' => ['nullable', 'string', 'max:2000'],

            // 追加項目
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
            'company_name.required' => '企業名を入力してください。',
            'company_name.string' => '企業名は文字列で入力してください。',
            'company_name.max' => '企業名は255文字以内で入力してください。',

            'company_name_kana.string' => '企業名カナは文字列で入力してください。',
            'company_name_kana.max' => '企業名カナは255文字以内で入力してください。',

            'overview.string' => '会社概要は文字列で入力してください。',
            'overview.max' => '会社概要は2000文字以内で入力してください。',

            'contact_name.string' => '担当者名は文字列で入力してください。',
            'contact_name.max' => '担当者名は255文字以内で入力してください。',

            'department.string' => '部署名は文字列で入力してください。',
            'department.max' => '部署名は255文字以内で入力してください。',

            'introduction.string' => '自己紹介は文字列で入力してください。',
            'introduction.max' => '自己紹介は2000文字以内で入力してください。',

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

