<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Corporate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_type',
        'corporation_name',
        'corporation_contact_name',
        'company_site_url',
        'display_name',
        'job_title',
        'bio',
        'min_hours_per_week',
        'max_hours_per_week',
        'hours_per_day',
        'days_per_week',
        'work_style_text',
        'min_rate',
        'max_rate',
        'experience_companies',
        'icon_path',
    ];

    protected $casts = [
        'min_hours_per_week' => 'integer',
        'max_hours_per_week' => 'integer',
        'hours_per_day' => 'integer',
        'days_per_week' => 'integer',
        'min_rate' => 'integer',
        'max_rate' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function scouts(): HasMany
    {
        return $this->hasMany(Scout::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'corporate_skill')
            ->using(CorporateSkill::class)
            ->withTimestamps();
    }

    public function customSkills(): HasMany
    {
        return $this->hasMany(CorporateCustomSkill::class)->orderBy('sort_order');
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(CorporatePortfolio::class)->orderBy('sort_order');
    }
}

