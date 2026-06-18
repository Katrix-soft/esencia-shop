<?php

namespace KatrixSoft\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'katrix_order_items';

    protected $fillable = [
        'order_id',
        'product_id', // ID del modelo de stock (configurable)
        'name',
        'quantity',
        'price',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
