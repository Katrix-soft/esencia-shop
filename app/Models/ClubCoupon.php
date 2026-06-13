<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubCoupon extends Model
{
    protected $fillable = [
        'name',
        'points_required',
        'discount_amount',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'discount_amount' => 'decimal:2',
        ];
    }
}
