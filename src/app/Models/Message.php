<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'thread_id',
        'sender_type',
        'sender_id',
        'body',
        'sent_at',
    ];

    protected $casts = [
        'sender_id' => 'integer',
        'sent_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * 縺薙・繝｡繝・そ繝ｼ繧ｸ縺悟ｱ槭☆繧九せ繝ｬ繝・ラ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝｡繝・そ繝ｼ繧ｸ荳隕ｧ陦ｨ遉ｺ譎ゅ↓繧ｹ繝ｬ繝・ラ諠・ｱ繧貞盾辣ｧ縺吶ｋ髫帙↑縺ｩ
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * 繝｡繝・そ繝ｼ繧ｸ騾∽ｿ｡閠・′莨∵･ｭ縺ｮ蝣ｴ蜷医・莨∵･ｭ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝｡繝・そ繝ｼ繧ｸ陦ｨ遉ｺ譎ゅ↓騾∽ｿ｡閠・錐繧・い繧､繧ｳ繝ｳ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function senderCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'sender_id');
    }

    /**
     * 繝｡繝・そ繝ｼ繧ｸ騾∽ｿ｡閠・′繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ縺ｮ蝣ｴ蜷医・繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝｡繝・そ繝ｼ繧ｸ陦ｨ遉ｺ譎ゅ↓騾∽ｿ｡閠・錐繧・い繧､繧ｳ繝ｳ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function senderFreelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class, 'sender_id');
    }
}