<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'freelancer_id',
        'job_id',
        'latest_sender_type',
        'latest_sender_id',
        'latest_message_at',
        'is_unread_for_company',
        'is_unread_for_freelancer',
    ];

    protected $casts = [
        'job_id' => 'integer',
        'latest_sender_id' => 'integer',
        'latest_message_at' => 'datetime',
        'is_unread_for_company' => 'boolean',
        'is_unread_for_freelancer' => 'boolean',
    ];

    /**
     * 縺薙・繧ｹ繝ｬ繝・ラ縺ｫ蜿ょ刈縺励※縺・ｋ莨∵･ｭ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繧ｹ繝ｬ繝・ラ荳隕ｧ逕ｻ髱｢縺ｧ莨∵･ｭ蜷阪ｒ陦ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * 縺薙・繧ｹ繝ｬ繝・ラ縺ｫ蜿ょ刈縺励※縺・ｋ繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ諠・ｱ繧貞叙蠕・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繧ｹ繝ｬ繝・ラ荳隕ｧ逕ｻ髱｢縺ｧ繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ蜷阪ｒ陦ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }

    /**
     * 縺薙・繧ｹ繝ｬ繝・ラ縺ｫ髢｢騾｣縺吶ｋ豎ゆｺｺ諠・ｱ繧貞叙蠕暦ｼ井ｻｻ諢擾ｼ・     * 菴ｿ逕ｨ蝣ｴ髱｢: 豎ゆｺｺ縺ｫ髢｢騾｣縺吶ｋ繝｡繝・そ繝ｼ繧ｸ縺九←縺・°繧貞愛螳壹☆繧矩圀縺ｪ縺ｩ
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * 縺薙・繧ｹ繝ｬ繝・ラ蜀・・繝｡繝・そ繝ｼ繧ｸ荳隕ｧ繧貞叙蠕暦ｼ磯∽ｿ｡譌･譎る・ｼ・     * 菴ｿ逕ｨ蝣ｴ髱｢: 繝｡繝・そ繝ｼ繧ｸ隧ｳ邏ｰ逕ｻ髱｢縺ｧ莨夊ｩｱ螻･豁ｴ繧定｡ｨ遉ｺ縺吶ｋ髫帙↑縺ｩ
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('sent_at');
    }
}