<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Freelancer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'job_title',
        'bio',
        'min_hours_per_week',
        'max_hours_per_week',
        'hours_per_day',
        'days_per_week',
        'work_style_text',
        'min_rate',
        'max_rate',
        'experience_companies',
        'icon_path',
    ];

    protected $casts = [
        'min_hours_per_week' => 'integer',
        'max_hours_per_week' => 'integer',
        'hours_per_day' => 'integer',
        'days_per_week' => 'integer',
        'min_rate' => 'integer',
        'max_rate' => 'integer',
    ];

    /**
     * フリーランサーに紐づくユーザーアカウント情報を取征E     * 使用場面: ログイン認証めE��ーザー惁E��の取得時など
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * フリーランサーが応募した求人一覧を取征E     * 使用場面: マイペ�Eジで応募履歴を表示する際など
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * フリーランサーが受け取ったスカウト一覧を取征E     * 使用場面: スカウト受信箱で受信したスカウトを表示する際など
     */
    public function scouts(): HasMany
    {
        return $this->hasMany(Scout::class);
    }

    /**
     * フリーランサーが参加してぁE��メチE��ージスレチE��一覧を取征E     * 使用場面: メチE��ージ一覧画面でフリーランサーのスレチE��を表示する際など
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * フリーランサーが持つスキル�E��Eスタスキル�E�一覧を取征E     * 使用場面: プロフィール表示めE��キル検索時�Eマッチングなど
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'freelancer_skill')
            ->using(FreelancerSkill::class)
            ->withTimestamps();
    }

    /**
     * フリーランサーが独自に登録したカスタムスキル一覧を取征E     * 使用場面: プロフィール編雁E��面めE��示画面でカスタムスキルを表示する際など
     */
    public function customSkills(): HasMany
    {
        return $this->hasMany(FreelancerCustomSkill::class)->orderBy('sort_order');
    }

    /**
     * フリーランサーのポ�EトフォリオURL一覧を取征E     * 使用場面: プロフィール表示画面でポ�Eトフォリオリンクを表示する際など
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(FreelancerPortfolio::class)->orderBy('sort_order');
    }
}