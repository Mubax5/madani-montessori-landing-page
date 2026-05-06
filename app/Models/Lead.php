<?php

namespace App\Models;

use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'parent_name',
        'child_name',
        'child_age',
        'selected_program',
        'whatsapp_number',
        'whatsapp_normalized',
        'note',
        'source_page',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (Lead $lead): void {
            $lead->whatsapp_normalized = PhoneNumber::normalizeIndonesianWhatsapp($lead->whatsapp_number);
        });
    }
}
