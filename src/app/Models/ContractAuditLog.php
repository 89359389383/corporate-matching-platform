<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'action',
        'actor_type',
        'actor_id',
        'ip',
        'user_agent',
        'meta_json',
        'occurred_at',
    ];

    protected $casts = [
        'contract_id' => 'integer',
        'actor_id' => 'integer',
        'meta_json' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}

