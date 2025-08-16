<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'category_id',
        'name',
        'description',
        'sku',
        'price',
        'cost_price',
        'stock_quantity',
        'min_stock_level',
        'image',
        'images',
        'unit',
        'is_active',
        'is_featured',
        'specifications',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'images' => 'array',
        'specifications' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-product.png');
    }

    public function getImagesUrlsAttribute()
    {
        if ($this->images) {
            return collect($this->images)->map(function ($image) {
                return asset('storage/' . $image);
            });
        }
        return collect();
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getFormattedCostPriceAttribute()
    {
        return '$' . number_format($this->cost_price, 2);
    }

    public function getProfitAttribute()
    {
        return $this->price - $this->cost_price;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->cost_price > 0) {
            return (($this->price - $this->cost_price) / $this->cost_price) * 100;
        }
        return 0;
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function isOutOfStock()
    {
        return $this->stock_quantity <= 0;
    }
} 