<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use KatrixSoft\Cart\Contracts\StockableProduct;

class Product extends Model implements StockableProduct
{
    public function getStock(): int { return $this->stock; }
    public function getId(): int|string { return $this->id; }
    public static function findForCart($id): ?static { return static::find($id); }
    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'image',
        'wood', 'citrus', 'floral', 'stock',
        'fragella_id', 'brand', 'year', 'rating', 'popularity',
        'gender', 'longevity', 'sillage', 'general_notes',
        'main_accords', 'main_accords_percentage', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'general_notes' => 'array',
            'main_accords' => 'array',
            'main_accords_percentage' => 'array',
            'notes' => 'array',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
