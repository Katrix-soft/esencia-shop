<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'discount',
        'image',
        'product_ids',
    ];

    protected function casts(): array
    {
        return [
            'product_ids' => 'array',
        ];
    }
    
    public function getDiscountedPriceAttribute()
    {
        if (!$this->discount || $this->discount <= 0) {
            return $this->price;
        }
        return $this->price - ($this->price * ($this->discount / 100));
    }
}
