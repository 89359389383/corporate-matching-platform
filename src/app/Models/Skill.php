<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * こ�Eスキルを持つフリーランサー一覧を取征E     * 使用場面: スキル検索で該当するフリーランサーを探す際など
     */
    public function corporates(): BelongsToMany
    {
        return $this->belongsToMany(Corporate::class, 'corporate_skill')
            ->using(CorporateSkill::class)
            ->withTimestamps();
    }
}