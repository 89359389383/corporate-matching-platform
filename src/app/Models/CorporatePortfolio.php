<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CorporatePortfolio extends Model
{
    use HasFactory;

    protected $table = 'corporate_portfolios';

    protected $fillable = [
        'corporate_id',
        'url',
        'sort_order',
    ];

    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }
}

