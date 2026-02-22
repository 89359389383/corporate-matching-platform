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
            'display_name.required' => '表示名を入力してください。',
            'job_title.required' => '職種を入力してください。',
            'bio.required' => '自己紹介文を入力してください。',
            // ... 他のメッセージは Freelancer と同様
        ];
    }
}

