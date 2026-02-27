<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'requester_type',
        'requester_id',
        'body',
    ];

    protected $casts = [
        'contract_id' => 'integer',
        'requester_id' => 'integer',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}

