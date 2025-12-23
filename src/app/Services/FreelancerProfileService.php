<?php

namespace App\Services;

use App\Models\Freelancer;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FreelancerProfileService
{
    /**
     * フリーランスプロフィールを登録する
     *
     * 設計根拠（FreelancerProfileService 詳細設計）
     * - 複数テーブル（freelancers / freelancer_skill / freelancer_custom_skills / freelancer_portfolios）をまとめて登録する
     * - 画像（アイコン）保存を含む可能性がある
     * - 途中失敗時に不整合を残さないため、トランザクションで一括保存する
     */
    public function register(User $user, array $payload): Freelancer
    {
        // 複数テーブル更新になるので、全体をトランザクションでまとめる
        return DB::transaction(function () use ($user, $payload): Freelancer {
            // 既にプロフィールがある場合は再登録させない（Controllerでも防ぐが二重防御）
            if ($user->freelancer()->exists()) {
                // ここでは「既存を返す」ことで、アプリを落とさず安全側に倒す
                return $user->freelancer()->firstOrFail();
            }

            // アイコンがある場合は保存してパスを保持する（設計：画像保存の可能性）
            $iconPath = null;
            // payloadにiconがあり、かつUploadedFileならファイルとして扱う
            if (isset($payload['icon']) && $payload['icon'] instanceof UploadedFile) {
                // publicディスクに保存して、保存先パスを受け取る
                $iconPath = $payload['icon']->store('freelancer_icons', 'public');
            }

            // freelancers テーブルへ基本情報・稼働条件などを保存する（設計：基本情報保存）
            $freelancer = Freelancer::create([
                // 紐づくユーザーID
                'user_id' => $user->id,
                // 表示名
                'display_name' => $payload['display_name'],
                // 職種（表示用）
                'job_title' => $payload['job_title'],
                // 自己紹介
                'bio' => $payload['bio'],
                // 週の最小稼働時間
                'min_hours_per_week' => $payload['min_hours_per_week'],
                // 週の最大稼働時間
                'max_hours_per_week' => $payload['max_hours_per_week'],
                // 1日の稼働時間
                'hours_per_day' => $payload['hours_per_day'],
                // 週の稼働日数
                'days_per_week' => $payload['days_per_week'],
                // 働き方テキスト（任意）
                'work_style_text' => $payload['work_style_text'] ?? null,
                // 希望単価（下限・任意）
                'min_rate' => $payload['min_rate'] ?? null,
                // 希望単価（上限・任意）
                'max_rate' => $payload['max_rate'] ?? null,
                // 経験企業（任意）
                'experience_companies' => $payload['experience_companies'] ?? null,
                // アイコン保存先（任意）
                'icon_path' => $iconPath,
            ]);

            // skills（複数選択）を中間テーブルへ登録する（設計：スキル関連付け）
            if (!empty($payload['skills']) && is_array($payload['skills'])) {
                // syncで中間テーブルをまとめて関連付けする
                $freelancer->skills()->sync($payload['skills']);
            }

            // custom_skills（自由入力スキル）を登録する（設計：カスタムスキル登録）
            if (!empty($payload['custom_skills']) && is_array($payload['custom_skills'])) {
                // 表示順を管理するカウンタ
                $order = 1;
                // 入力されたスキル名を順番に処理する
                foreach ($payload['custom_skills'] as $skillName) {
                    // 空文字などは保存しない（入力ノイズを除去）
                    if (!is_string($skillName) || trim($skillName) === '') {
                        continue;
                    }

                    // 1件ずつ関連テーブルへ登録する
                    $freelancer->customSkills()->create([
                        // 表示用スキル名
                        'name' => trim($skillName),
                        // 画面表示順
                        'sort_order' => $order,
                    ]);

                    // 次の表示順へ進める
                    $order++;
                }
            }

            // portfolio_urls（ポートフォリオURL）を登録する（設計：ポートフォリオ登録）
            if (!empty($payload['portfolio_urls']) && is_array($payload['portfolio_urls'])) {
                // 表示順を管理するカウンタ
                $order = 1;
                // URLを順番に処理する
                foreach ($payload['portfolio_urls'] as $url) {
                    // 空文字などは保存しない（入力ノイズを除去）
                    if (!is_string($url) || trim($url) === '') {
                        continue;
                    }

                    // 1件ずつ関連テーブルへ登録する
                    $freelancer->portfolios()->create([
                        // URL本体
                        'url' => trim($url),
                        // 画面表示順
                        'sort_order' => $order,
                    ]);

                    // 次の表示順へ進める
                    $order++;
                }
            }

            // 作成した freelancer を返す（Controllerはこの後リダイレクトする）
            return $freelancer;
        });
    }

    /**
     * フリーランスプロフィールを更新する（設定画面用）
     *
     * ※設計書（routes）に settings が存在するため、最低限の更新処理を用意する
     */
    public function update(Freelancer $freelancer, array $payload): Freelancer
    {
        // 更新も将来の拡張（複数テーブル更新）に備えてトランザクションでまとめる
        return DB::transaction(function () use ($freelancer, $payload): Freelancer {
            // アイコン更新がある場合は、古いアイコンを削除して差し替える
            if (isset($payload['icon']) && $payload['icon'] instanceof UploadedFile) {
                // 既存パスがあれば削除する（publicディスク想定）
                if ($freelancer->icon_path) {
                    // ファイルが無くてもdeleteは安全に失敗するのでそのまま呼ぶ
                    Storage::disk('public')->delete($freelancer->icon_path);
                }

                // 新しいアイコンを保存して、保存先パスをセットする
                $freelancer->icon_path = $payload['icon']->store('freelancer_icons', 'public');
            }

            // fillさせないキー（別テーブル更新用）を除外する
            $profilePayload = $payload;
            // skills/custom_skills/portfolio_urls/iconは別処理で更新するので外す
            unset($profilePayload['skills'], $profilePayload['custom_skills'], $profilePayload['portfolio_urls'], $profilePayload['icon']);

            // それ以外のプロフィール項目を更新する（fillableの範囲で反映される）
            $freelancer->fill($profilePayload)->save();

            // skillsが来たら同期する（設計：スキル関連付け）
            if (array_key_exists('skills', $payload) && is_array($payload['skills'])) {
                // 中間テーブルをまとめて更新する
                $freelancer->skills()->sync($payload['skills']);
            }

            // custom_skillsが来たら置き換える（簡易実装：全削除→再作成）
            if (array_key_exists('custom_skills', $payload) && is_array($payload['custom_skills'])) {
                // 既存の自由入力スキルをいったん削除する
                $freelancer->customSkills()->delete();
                // 表示順を管理するカウンタ
                $order = 1;
                // 入力されたスキル名を順番に処理する
                foreach ($payload['custom_skills'] as $skillName) {
                    // 空文字などは保存しない（入力ノイズを除去）
                    if (!is_string($skillName) || trim($skillName) === '') {
                        continue;
                    }
                    // 1件ずつ関連テーブルへ登録する
                    $freelancer->customSkills()->create([
                        // 表示用スキル名
                        'name' => trim($skillName),
                        // 画面表示順
                        'sort_order' => $order,
                    ]);
                    // 次の表示順へ進める
                    $order++;
                }
            }

            // portfolio_urlsが来たら置き換える（簡易実装：全削除→再作成）
            if (array_key_exists('portfolio_urls', $payload) && is_array($payload['portfolio_urls'])) {
                // 既存のポートフォリオURLをいったん削除する
                $freelancer->portfolios()->delete();
                // 表示順を管理するカウンタ
                $order = 1;
                // URLを順番に処理する
                foreach ($payload['portfolio_urls'] as $url) {
                    // 空文字などは保存しない（入力ノイズを除去）
                    if (!is_string($url) || trim($url) === '') {
                        continue;
                    }
                    // 1件ずつ関連テーブルへ登録する
                    $freelancer->portfolios()->create([
                        // URL本体
                        'url' => trim($url),
                        // 画面表示順
                        'sort_order' => $order,
                    ]);
                    // 次の表示順へ進める
                    $order++;
                }
            }

            // 更新済みのプロフィールを返す
            return $freelancer;
        });
    }
}

