<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Scout extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_CLOSED = 2;

    protected $fillable = [
        'company_id',
        'freelancer_id',
        'job_id',
        'message',
        'status',
    ];

    protected $casts = [
        'job_id' => 'integer',
        'status' => 'integer',
    ];

    /**
     * 繧ｹ繧ｫ繧ｦ繝医ｒ騾∽ｿ｡縺励◆莨∵･ｭ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繧ｹ繧ｫ繧ｦ繝郁ｩｳ邏ｰ逕ｻ髱｢縺ｧ莨∵･ｭ諠・ｱ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * 繧ｹ繧ｫ繧ｦ繝医ｒ蜿嶺ｿ｡縺励◆繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 莨∵･ｭ縺後せ繧ｫ繧ｦ繝磯∽ｿ｡螻･豁ｴ繧堤｢ｺ隱阪☆繧矩圀縺ｪ縺ｩ
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }

    /**
     * 繧ｹ繧ｫ繧ｦ繝医↓髢｢騾｣縺吶ｋ豎ゆｺｺ諠・ｱ繧貞叙蠕暦ｼ井ｻｻ諢擾ｼ・     * 菴ｿ逕ｨ蝣ｴ髱｢: 迚ｹ螳壹・豎ゆｺｺ縺ｫ髢｢騾｣縺吶ｋ繧ｹ繧ｫ繧ｦ繝医°縺ｩ縺・°繧貞愛螳壹☆繧矩圀縺ｪ縺ｩ
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * 縺薙・繧ｹ繧ｫ繧ｦ繝医↓髢｢騾｣縺吶ｋ繧ｹ繝ｬ繝・ラ・医Γ繝・そ繝ｼ繧ｸ繧・ｊ蜿悶ｊ・峨ｒ蜿門ｾ・     * 繧ｹ繧ｭ繝ｼ繝樔ｸ・threads 縺ｫ scout_id 縺ｯ辟｡縺・◆繧√・     * 縲慶ompany_id + freelancer_id + job_id・・ullable・峨阪〒蟆主・縺励∪縺吶・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繧ｹ繧ｫ繧ｦ繝亥ｾ後・繝｡繝・そ繝ｼ繧ｸ螻･豁ｴ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function thread(): HasOne
    {
        $relation = $this->hasOne(Thread::class, 'company_id', 'company_id')
            ->where('freelancer_id', $this->freelancer_id);

        if ($this->job_id === null) {
            return $relation->whereNull('job_id');
        }

        return $relation->where('job_id', $this->job_id);
    }
}