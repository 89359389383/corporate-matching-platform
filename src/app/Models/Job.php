<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 0;
    public const STATUS_PUBLISHED = 1;
    public const STATUS_STOPPED = 2;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'required_skills_text',
        'reward_type',
        'min_rate',
        'max_rate',
        'work_time_text',
        'status',
    ];

    protected $casts = [
        'min_rate' => 'integer',
        'max_rate' => 'integer',
        'status' => 'integer',
    ];

    /**
     * 縺薙・豎ゆｺｺ繧呈兜遞ｿ縺励◆莨∵･ｭ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 豎ゆｺｺ隧ｳ邏ｰ逕ｻ髱｢縺ｧ莨∵･ｭ諠・ｱ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * 縺薙・豎ゆｺｺ縺ｸ縺ｮ蠢懷供荳隕ｧ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 莨∵･ｭ縺悟ｿ懷供閠・ｸ隕ｧ繧堤｢ｺ隱阪☆繧矩圀縺ｪ縺ｩ
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * 縺薙・豎ゆｺｺ縺ｫ髢｢騾｣縺吶ｋ繧ｹ繧ｫ繧ｦ繝井ｸ隕ｧ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 豎ゆｺｺ縺ｫ髢｢騾｣縺吶ｋ繧ｹ繧ｫ繧ｦ繝磯∽ｿ｡螻･豁ｴ繧堤｢ｺ隱阪☆繧矩圀縺ｪ縺ｩ
     */
    public function scouts(): HasMany
    {
        return $this->hasMany(Scout::class);
    }

    /**
     * 縺薙・豎ゆｺｺ縺ｫ髢｢騾｣縺吶ｋ繝｡繝・そ繝ｼ繧ｸ繧ｹ繝ｬ繝・ラ荳隕ｧ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 豎ゆｺｺ縺ｫ髢｢騾｣縺吶ｋ繝｡繝・そ繝ｼ繧ｸ螻･豁ｴ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }
}