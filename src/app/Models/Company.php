<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'overview',
        'contact_name',
        'department',
        'introduction',
    ];

    /**
     * 莨∵･ｭ縺ｫ邏舌▼縺上Θ繝ｼ繧ｶ繝ｼ繧｢繧ｫ繧ｦ繝ｳ繝域ュ蝣ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝ｭ繧ｰ繧､繝ｳ隱崎ｨｼ繧・Θ繝ｼ繧ｶ繝ｼ諠・ｱ縺ｮ蜿門ｾ玲凾縺ｪ縺ｩ
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 莨∵･ｭ縺梧兜遞ｿ縺励◆豎ゆｺｺ荳隕ｧ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 莨∵･ｭ繝繝・す繝･繝懊・繝峨〒閾ｪ遉ｾ縺ｮ豎ゆｺｺ荳隕ｧ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    /**
     * 莨∵･ｭ縺碁∽ｿ｡縺励◆繧ｹ繧ｫ繧ｦ繝井ｸ隕ｧ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繧ｹ繧ｫ繧ｦ繝磯∽ｿ｡螻･豁ｴ縺ｮ遒ｺ隱阪ｄ邂｡逅・判髱｢縺ｧ縺ｮ陦ｨ遉ｺ縺ｪ縺ｩ
     */
    public function scouts(): HasMany
    {
        return $this->hasMany(Scout::class);
    }

    /**
     * 莨∵･ｭ縺悟盾蜉縺励※縺・ｋ繝｡繝・そ繝ｼ繧ｸ繧ｹ繝ｬ繝・ラ荳隕ｧ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝｡繝・そ繝ｼ繧ｸ荳隕ｧ逕ｻ髱｢縺ｧ莨∵･ｭ縺ｮ繧ｹ繝ｬ繝・ラ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }
}