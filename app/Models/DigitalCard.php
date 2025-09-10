<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DigitalCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_active',
        'is_public',
        'slug',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function personalInfo(): HasOne
    {
        return $this->hasOne(DigitalCardPersonalInfo::class);
    }

    public function contactInfo(): HasOne
    {
        return $this->hasOne(DigitalCardContactInfo::class);
    }

    public function aboutInfo(): HasOne
    {
        return $this->hasOne(DigitalCardAboutInfo::class);
    }

}
