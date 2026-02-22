<?php

namespace App\Services;

use App\Models\Corporate;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CorporateProfileService
{
    public function register(User $user, array $payload): Corporate
    {
        Log::info('【CorporateProfileService::register】プロフィール登録処理開始', ['user_id' => $user->id]);

        try {
            return DB::transaction(function () use ($user, $payload): Corporate {
                $existing = $user->corporate()->first();
                if ($existing) {
                    return $existing;
                }

                $iconPath = null;
                if (isset($payload['icon']) && $payload['icon'] instanceof UploadedFile) {
                    $iconPath = $payload['icon']->store('corporate_icons', 'public');
                }

                $corporate = Corporate::create([
                    'user_id' => $user->id,
                    'display_name' => $payload['display_name'],
                    'job_title' => $payload['job_title'],
                    'bio' => $payload['bio'],
                    'min_hours_per_week' => $payload['min_hours_per_week'],
                    'max_hours_per_week' => $payload['max_hours_per_week'],
                    'hours_per_day' => $payload['hours_per_day'],
                    'days_per_week' => $payload['days_per_week'],
                    'work_style_text' => $payload['work_style_text'] ?? null,
                    'min_rate' => $payload['min_rate'] ?? null,
                    'max_rate' => $payload['max_rate'] ?? null,
                    'experience_companies' => $payload['experience_companies'] ?? null,
                    'icon_path' => $iconPath,
                ]);

                if (!empty($payload['skills']) && is_array($payload['skills'])) {
                    $corporate->skills()->sync($payload['skills']);
                }

                if (!empty($payload['custom_skills']) && is_array($payload['custom_skills'])) {
                    $order = 1;
                    foreach ($payload['custom_skills'] as $skillName) {
                        if (!is_string($skillName) || trim($skillName) === '') {
                            continue;
                        }
                        $corporate->customSkills()->create([
                            'name' => trim($skillName),
                            'sort_order' => $order,
                        ]);
                        $order++;
                    }
                }

                if (!empty($payload['portfolio_urls']) && is_array($payload['portfolio_urls'])) {
                    $order = 1;
                    foreach ($payload['portfolio_urls'] as $url) {
                        if (!is_string($url) || trim($url) === '') {
                            continue;
                        }
                        $corporate->portfolios()->create([
                            'url' => trim($url),
                            'sort_order' => $order,
                        ]);
                        $order++;
                    }
                }

                return $corporate;
            });
        } catch (\Exception $e) {
            Log::error('【CorporateProfileService::register】エラー', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update(Corporate $corporate, array $payload): Corporate
    {
        try {
            return DB::transaction(function () use ($corporate, $payload): Corporate {
                if (isset($payload['icon']) && $payload['icon'] instanceof UploadedFile) {
                    if ($corporate->icon_path) {
                        Storage::disk('public')->delete($corporate->icon_path);
                    }
                    $newIcon = $payload['icon']->store('corporate_icons', 'public');
                    $corporate->icon_path = $newIcon;
                }

                $profilePayload = $payload;
                unset($profilePayload['skills'], $profilePayload['custom_skills'], $profilePayload['portfolio_urls'], $profilePayload['icon']);

                $corporate->fill($profilePayload)->save();

                if (array_key_exists('skills', $payload) && is_array($payload['skills'])) {
                    $corporate->skills()->sync($payload['skills']);
                }

                if (array_key_exists('custom_skills', $payload) && is_array($payload['custom_skills'])) {
                    $corporate->customSkills()->delete();
                    $order = 1;
                    foreach ($payload['custom_skills'] as $skillName) {
                        if (!is_string($skillName) || trim($skillName) === '') continue;
                        $corporate->customSkills()->create([
                            'name' => trim($skillName),
                            'sort_order' => $order,
                        ]);
                        $order++;
                    }
                }

                if (array_key_exists('portfolio_urls', $payload) && is_array($payload['portfolio_urls'])) {
                    $corporate->portfolios()->delete();
                    $order = 1;
                    foreach ($payload['portfolio_urls'] as $url) {
                        if (!is_string($url) || trim($url) === '') continue;
                        $corporate->portfolios()->create([
                            'url' => trim($url),
                            'sort_order' => $order,
                        ]);
                        $order++;
                    }
                }

                return $corporate;
            });
        } catch (\Exception $e) {
            Log::error('【CorporateProfileService::update】エラー', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}

