<?php

namespace App\Models;

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
}
