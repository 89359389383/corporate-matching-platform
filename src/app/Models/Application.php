<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_CLOSED = 2;

    protected $fillable = [
        'job_id',
        'freelancer_id',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * 蠢懷供蜈医・豎ゆｺｺ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 蠢懷供隧ｳ邏ｰ逕ｻ髱｢縺ｧ豎ゆｺｺ諠・ｱ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * 蠢懷供縺励◆繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 莨∵･ｭ縺悟ｿ懷供閠・ｸ隕ｧ繧堤｢ｺ隱阪☆繧矩圀縺ｪ縺ｩ
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }

    /**
     * 縺薙・蠢懷供縺ｫ髢｢騾｣縺吶ｋ繧ｹ繝ｬ繝・ラ・医Γ繝・そ繝ｼ繧ｸ繧・ｊ蜿悶ｊ・峨ｒ蜿門ｾ・     * 繧ｹ繧ｭ繝ｼ繝樔ｸ・threads 縺ｫ application_id 縺ｯ辟｡縺・◆繧√・     * 縲桂ob_id + freelancer_id・・ 螳溯ｳｪ company 縺ｯ job 縺ｫ蠕灘ｱ橸ｼ峨阪〒蟆主・縺励∪縺吶・     * 菴ｿ逕ｨ蝣ｴ髱｢: 蠢懷供蠕後・繝｡繝・そ繝ｼ繧ｸ螻･豁ｴ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function thread(): HasOne
    {
        return $this->hasOne(Thread::class, 'job_id', 'job_id')
            ->where('freelancer_id', $this->freelancer_id);
    }
}