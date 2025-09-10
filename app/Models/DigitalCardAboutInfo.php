<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalCardAboutInfo extends Model
{
    use HasFactory;

    protected $table = 'digital_card_about_info';

    protected $fillable = [
        'digital_card_id',
        'description',
        'skills',
        'experience',
    ];

    protected $casts = [
        'skills' => 'array',
        'experience' => 'integer',
    ];

    public function digitalCard(): BelongsTo
    {
        return $this->belongsTo(DigitalCard::class);
    }
}
