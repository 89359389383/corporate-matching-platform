<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PROPOSED = 'proposed';
    public const STATUS_NEGOTIATING = 'negotiating';
    public const STATUS_READY_TO_SIGN = 'ready_to_sign';
    public const STATUS_SIGNED = 'signed';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_TERMINATED = 'terminated';
    public const STATUS_ARCHIVED = 'archived';

    public const TYPE_NDA = 'nda';
    public const TYPE_BASIC = 'basic';
    public const TYPE_INDIVIDUAL = 'individual';

    protected $fillable = [
        'thread_id',
        'company_id',
        'corporate_id',
        'job_id',
        'contract_type',
        'version',
        'status',
        'start_date',
        'end_date',
        'terms_json',
        'proposed_at',
        'signed_at',
        'active_at',
        'completed_at',
        'terminated_at',
        'archived_at',
        'superseded_by_contract_id',
        'pdf_path',
        'document_hash',
        'pdf_hash',
    ];

    protected $casts = [
        'thread_id' => 'integer',
        'company_id' => 'integer',
        'corporate_id' => 'integer',
        'job_id' => 'integer',
        'version' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'terms_json' => 'array',
        'proposed_at' => 'datetime',
        'signed_at' => 'datetime',
        'active_at' => 'datetime',
        'completed_at' => 'datetime',
        'terminated_at' => 'datetime',
        'archived_at' => 'datetime',
        'superseded_by_contract_id' => 'integer',
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(ContractSignature::class);
    }

    public function changeRequests(): HasMany
    {
        return $this->hasMany(ContractChangeRequest::class)->orderByDesc('id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(ContractAuditLog::class)->orderByDesc('occurred_at');
    }

    public function isCurrent(): bool
    {
        return $this->superseded_by_contract_id === null;
    }

    public function isEditableDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT && $this->isCurrent();
    }
}

