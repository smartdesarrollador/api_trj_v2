<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalCardPersonalInfo extends Model
{
    use HasFactory;

    protected $table = 'digital_card_personal_info';

    protected $fillable = [
        'digital_card_id',
        'name',
        'title',
        'location',
        'photo',
    ];

    public function digitalCard(): BelongsTo
    {
        return $this->belongsTo(DigitalCard::class);
    }
}
