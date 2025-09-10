<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalCardContactInfo extends Model
{
    use HasFactory;

    protected $table = 'digital_card_contact_info';

    protected $fillable = [
        'digital_card_id',
        'email',
        'phone',
        'linkedin',
        'website',
        'twitter',
        'instagram',
        'github',
        'youtube',
        'tiktok',
        'whatsapp',
        'facebook',
    ];

    public function digitalCard(): BelongsTo
    {
        return $this->belongsTo(DigitalCard::class);
    }
}
