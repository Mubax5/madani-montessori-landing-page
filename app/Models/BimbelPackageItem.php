<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BimbelPackageItem extends Model
{
    protected $fillable = [
        'package_id',
        'title',
        'description',
        'sort_order',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(BimbelPackage::class, 'package_id');
    }
}
