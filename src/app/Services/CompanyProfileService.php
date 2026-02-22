<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyProfileService
{
    /**
     * 企業プロフィールを登録する
     *
     * 設計根拠（CompanyProfileService 詳細設計）
     * - Controllerは入口に徹し、保存ロジックはServiceへ集約する
     * - 将来的な拡張（追加テーブル更新など）に備え、トランザクションでまとめる
     */
    public function register(User $user, array $payload): Company
    {
        // 今はcompaniesだけだが、将来の複数更新に備えてトランザクションでまとめる
        return DB::transaction(function () use ($user, $payload): Company {
            // 既に企業プロフィールがある場合は二重登録を防ぐ（Controllerでも防ぐが二重防御）
            if ($user->company()->exists()) {
                // ここでは「既存を返す」ことで、アプリを落とさず安全側に倒す
                return $user->company()->firstOrFail();
            }

            // companies テーブルへINSERTする（設計：企業プロフィール作成）
            return Company::create([
                // 紐づくユーザー
                'user_id' => $user->id,
                // 企業名（payloadはcompany_nameで来る想定）
                'name' => $payload['company_name'],
                // 企業名カナ（任意）
                'name_kana' => $payload['company_name_kana'] ?? null,
                // 会社概要（任意）
                'overview' => $payload['overview'] ?? null,
                // 担当者名（任意）
                'contact_name' => $payload['contact_name'] ?? null,
                // 部署（任意）
                'department' => $payload['department'] ?? null,
                // 自己紹介（任意）
                'introduction' => $payload['introduction'] ?? null,

                // 追加項目（登録時）
                'email' => $payload['email'],
                'corporate_number' => $payload['corporate_number'],
                'representative_phone' => $payload['representative_phone'],
                'hq_postal_code' => $payload['hq_postal_code'],
                'hq_prefecture' => $payload['hq_prefecture'],
                'hq_city' => $payload['hq_city'],
                'hq_address' => $payload['hq_address'],
                'representative_last_name' => $payload['representative_last_name'],
                'representative_first_name' => $payload['representative_first_name'],
                'representative_last_name_kana' => $payload['representative_last_name_kana'],
                'representative_first_name_kana' => $payload['representative_first_name_kana'],
                'representative_birthdate' => $payload['representative_birthdate'] ?? null,
            ]);
        });
    }
}

