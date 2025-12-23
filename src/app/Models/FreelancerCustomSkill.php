<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreelancerCustomSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'name',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * 縺薙・繧ｫ繧ｹ繧ｿ繝繧ｹ繧ｭ繝ｫ繧呈園譛峨☆繧九ヵ繝ｪ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繧ｫ繧ｹ繧ｿ繝繧ｹ繧ｭ繝ｫ邱ｨ髮・凾縺ｫ繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞盾辣ｧ縺吶ｋ髫帙↑縺ｩ
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }
}