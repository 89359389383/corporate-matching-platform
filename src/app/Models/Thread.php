<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'corporate_id',
        'job_id',
        'latest_sender_type',
        'latest_sender_id',
        'latest_message_at',
        'is_unread_for_company',
        'is_unread_for_corporate',
    ];

    protected $casts = [
        'job_id' => 'integer',
        'latest_sender_id' => 'integer',
        'latest_message_at' => 'datetime',
        'is_unread_for_company' => 'boolean',
        'is_unread_for_corporate' => 'boolean',
    ];

    /**
     * ??E???E????????E?????E?????E     * ????: ???E??????????????????
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * ??E???E????????E??????????E?????E     * ????: ???E???????????????????????
     */
    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }

    /**
     * ??E???E??????????E?????????E     * ????: ?????????E???????E??????????
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * ??E???E???E?E??E???????????????E??E     * ????: ??E?????????????????????
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('sent_at');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class)->orderByDesc('version');
    }

    public function currentContract(): HasOne
    {
        return $this->hasOne(Contract::class)
            ->whereNull('superseded_by_contract_id')
            ->orderByDesc('version');
    }

    /**
     * Scout (inverse of Scout::thread)
     *
     * Note: Scout stores company_id, corporate_id and optional job_id.
     * We mirror Scout::thread() logic here so Thread->scout returns
     * the matching scout (if any) for this thread.
     */
    public function scout(): HasOne
    {
        $relation = $this->hasOne(Scout::class, 'company_id', 'company_id')
            ->where('corporate_id', $this->corporate_id);

        if ($this->job_id === null) {
            return $relation->whereNull('job_id');
        }

        return $relation->where('job_id', $this->job_id);
    }
}