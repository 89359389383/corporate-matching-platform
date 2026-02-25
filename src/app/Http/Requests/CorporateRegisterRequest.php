<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CorporateRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_type' => ['required', 'string', 'in:individual,corporation'],
            'corporation_name' => ['required_if:recipient_type,corporation', 'nullable', 'string', 'max:255'],
            'corporation_contact_name' => ['required_if:recipient_type,corporation', 'nullable', 'string', 'max:255'],
            'company_site_url' => ['nullable', 'url', 'max:2000'],
            'display_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:5000'],
            'min_hours_per_week' => ['required', 'integer', 'min:0', 'max:168'],
            'max_hours_per_week' => ['required', 'integer', 'min:0', 'max:168'],
            'hours_per_day' => ['required', 'integer', 'min:0', 'max:24'],
            'days_per_week' => ['required', 'integer', 'min:0', 'max:7'],
            'work_style_text' => ['nullable', 'string', 'max:5000'],
            'min_rate' => ['nullable', 'integer', 'min:0'],
            'max_rate' => ['nullable', 'integer', 'min:0', 'gte:min_rate'],
            'experience_companies' => ['nullable', 'string', 'max:5000'],
            'icon' => ['nullable', 'file', 'image', 'max:5120'],
            'skills' => ['sometimes', 'array', 'min:1'],
            'skills.*' => ['integer'],
            'custom_skills' => ['required_without:skills', 'array', 'min:1'],
            'custom_skills.*' => ['required', 'string', 'max:255'],
            'portfolio_urls' => ['sometimes', 'array'],
            'portfolio_urls.*' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        $recipientType = $this->input('recipient_type');
        if (is_string($recipientType)) {
            $recipientType = trim($recipientType);
        }
        if ($recipientType === null || $recipientType === '') {
            $recipientType = 'individual';
        }
        $merge['recipient_type'] = $recipientType;

        foreach (['corporation_name', 'corporation_contact_name', 'company_site_url'] as $key) {
            $value = $this->input($key);
            if (is_string($value)) {
                $value = trim($value);
                if ($value === '') {
                    $value = null;
                }
                $merge[$key] = $value;
            }
        }

        if ($recipientType !== 'corporation') {
            $merge['corporation_name'] = null;
            $merge['corporation_contact_name'] = null;
        }

        $min = $this->input('min_rate');
        $max = $this->input('max_rate');
        if ($min === null && $max === null) {
            $merge['min_rate'] = 0;
            $merge['max_rate'] = 0;
        } elseif ($min === null && $max !== null) {
            $merge['min_rate'] = $max;
        } elseif ($max === null && $min !== null) {
            $merge['max_rate'] = $min;
        }

        $customSkills = $this->input('custom_skills');
        if (is_array($customSkills)) {
            $normalized = [];
            foreach ($customSkills as $skill) {
                if (!is_string($skill)) {
                    continue;
                }
                $skill = trim($skill);
                if ($skill === '') {
                    continue;
                }
                $normalized[] = $skill;
            }
            $merge['custom_skills'] = $normalized;
        }

        if (!empty($merge)) {
            $this->merge($merge);
        }
    }

    public function messages(): array
    {
        return [
            'recipient_type.required' => '受注者タイプを選択してください。',
            'recipient_type.in' => '受注者タイプの選択が不正です。',
            'corporation_name.required_if' => '法人を選んだ場合、法人名は必須です。',
            'corporation_name.max' => '法人名は255文字以内で入力してください。',
            'corporation_contact_name.required_if' => '法人を選んだ場合、担当者名は必須です。',
            'corporation_contact_name.max' => '担当者名は255文字以内で入力してください。',
            'company_site_url.url' => '会社サイトURLは正しいURL形式で入力してください。',
            'company_site_url.max' => '会社サイトURLは2000文字以内で入力してください。',
            'display_name.required' => '表示名を入力してください。',
            'job_title.required' => '職種を入力してください。',
            'bio.required' => '自己紹介文を入力してください。',
            // ... 他のメッセージは Freelancer と同様
        ];
    }
}

