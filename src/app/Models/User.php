<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 繝ｦ繝ｼ繧ｶ繝ｼ縺御ｼ∵･ｭ繧｢繧ｫ繧ｦ繝ｳ繝医・蝣ｴ蜷医・莨∵･ｭ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝ｭ繧ｰ繧､繝ｳ蠕後・繝ｦ繝ｼ繧ｶ繝ｼ繧ｿ繧､繝怜愛螳壹ｄ莨∵･ｭ諠・ｱ縺ｮ蜿門ｾ玲凾縺ｪ縺ｩ
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    /**
     * 繝ｦ繝ｼ繧ｶ繝ｼ縺後ヵ繝ｪ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ繧｢繧ｫ繧ｦ繝ｳ繝医・蝣ｴ蜷医・繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝ｭ繧ｰ繧､繝ｳ蠕後・繝ｦ繝ｼ繧ｶ繝ｼ繧ｿ繧､繝怜愛螳壹ｄ繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ縺ｮ蜿門ｾ玲凾縺ｪ縺ｩ
     */
    public function corporate(): HasOne
    {
        return $this->hasOne(Corporate::class);
    }
}