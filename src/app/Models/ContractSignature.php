<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'signer_type',
        'signer_id',
        'organization_type',
        'organization_id',
        'ip',
        'user_agent',
        'signed_at',
        'document_hash',
    ];

    protected $casts = [
        'contract_id' => 'integer',
        'signer_id' => 'integer',
        'organization_id' => 'integer',
        'signed_at' => 'datetime',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}

