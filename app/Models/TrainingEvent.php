<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingEvent extends Model
{
    protected $fillable = [
        'title',
        'topic',
        'target_audience',
        'event_date',
        'event_time',
        'description',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return ['event_date' => 'date'];
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->orderBy('sort_order')->orderBy('event_date');
    }
}
