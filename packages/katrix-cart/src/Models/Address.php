<?php

namespace KatrixSoft\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $table = 'katrix_addresses';

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'province',
        'locality',
        'zip_code',
        'district',
        'address',
        'apartment',
        'reference',
        'contact',
        'phone',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('katrix-cart.user_model'));
    }
}
