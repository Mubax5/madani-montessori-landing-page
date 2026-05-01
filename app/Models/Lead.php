<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'parent_name',
        'child_name',
        'child_age',
        'selected_program',
        'whatsapp_number',
        'note',
        'source_page',
        'status',
    ];
}
