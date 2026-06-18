<?php

namespace KatrixSoft\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'katrix_orders';

    protected $fillable = [
        'user_id',
        'shipping_address',
        'payment_method',
        'payment_status',
        'mp_payment_id',
        'paid_at',
        'status',
        'shipping_cost',
        'subtotal',
        'total',
        'transfer_issuer_name',
        'transfer_issuer_cuit',
        'transfer_receipt_path',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'paid_at'          => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('katrix-cart.user_model'));
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
