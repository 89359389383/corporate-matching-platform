<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FreelancerSkill extends Pivot
{
    use HasFactory;

    protected $table = 'freelancer_skill';

    /**
     * 繝斐・繝・ヨ縺ｧ縺吶′縲√％縺ｮ繝・・繝悶Ν縺ｯ id 繧呈戟縺､縺溘ａ incrementing 繧呈怏蜉ｹ蛹悶＠縺ｾ縺吶・     */
    public $incrementing = true;

    protected $fillable = [
        'freelancer_id',
        'skill_id',
    ];

    /**
     * 縺薙・繧ｹ繧ｭ繝ｫ邏蝉ｻ倥￠繧呈園譛峨☆繧九ヵ繝ｪ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繧ｹ繧ｭ繝ｫ邏蝉ｻ倥￠縺ｮ邂｡逅・ｄ讀懃ｴ｢譎ゅ・蜿ら・縺ｪ縺ｩ
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }

    /**
     * 縺薙・邏蝉ｻ倥￠縺ｫ髢｢騾｣縺吶ｋ繧ｹ繧ｭ繝ｫ繝槭せ繧ｿ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繧ｹ繧ｭ繝ｫ蜷阪・陦ｨ遉ｺ繧・せ繧ｭ繝ｫ讀懃ｴ｢譎ゅ・蜿ら・縺ｪ縺ｩ
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}