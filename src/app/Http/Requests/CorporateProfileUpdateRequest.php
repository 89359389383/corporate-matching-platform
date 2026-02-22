<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CorporateProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_name' => ['sometimes', 'string', 'max:255'],
            'job_title' => ['sometimes', 'string', 'max:255'],
            'bio' => ['sometimes', 'string', 'max:5000'],
            'min_hours_per_week' => ['sometimes', 'integer', 'min:0', 'max:168'],
            'max_hours_per_week' => ['sometimes', 'integer', 'min:0', 'max:168'],
            'hours_per_day' => ['sometimes', 'integer', 'min:0', 'max:24'],
            'days_per_week' => ['sometimes', 'integer', 'min:0', 'max:7'],
            'work_style_text' => ['nullable', 'string', 'max:5000'],
            'min_rate' => ['nullable', 'integer', 'min:0'],
            'max_rate' => ['nullable', 'integer', 'min:0', 'gte:min_rate'],
            'experience_companies' => ['nullable', 'string', 'max:5000'],
            'icon' => ['nullable', 'file', 'image', 'max:5120'],
            'skills' => ['sometimes', 'array'],
            'skills.*' => ['integer'],
            'custom_skills' => ['sometimes', 'array'],
            'custom_skills.*' => ['required', 'string', 'max:255'],
            'portfolio_urls' => ['sometimes', 'array'],
            'portfolio_urls.*' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

