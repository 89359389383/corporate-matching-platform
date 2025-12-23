<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreelancerPortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'url',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * 縺薙・繝昴・繝医ヵ繧ｩ繝ｪ繧ｪ繧呈園譛峨☆繧九ヵ繝ｪ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝昴・繝医ヵ繧ｩ繝ｪ繧ｪ邱ｨ髮・凾縺ｫ繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞盾辣ｧ縺吶ｋ髫帙↑縺ｩ
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }
}