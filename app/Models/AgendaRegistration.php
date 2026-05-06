<?php

namespace App\Models;

use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaRegistration extends Model
{
    protected $fillable = [
        'agenda_id',
        'parent_name',
        'child_name',
        'child_age',
        'whatsapp_number',
        'whatsapp_normalized',
        'email',
        'participant_count',
        'note',
        'status',
        'source',
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    protected static function booted(): void
    {
        static::saving(function (AgendaRegistration $registration): void {
            $registration->whatsapp_normalized = PhoneNumber::normalizeIndonesianWhatsapp($registration->whatsapp_number);
        });
    }
}
