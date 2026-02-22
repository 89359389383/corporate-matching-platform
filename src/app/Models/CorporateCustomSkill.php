<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CorporateCustomSkill extends Model
{
    use HasFactory;

    protected $table = 'corporate_custom_skills';

    protected $fillable = [
        'corporate_id',
        'name',
        'sort_order',
    ];

    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }
}

