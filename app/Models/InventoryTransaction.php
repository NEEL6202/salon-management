<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'product_id',
        'user_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'notes',
        'reference_type',
        'reference_id',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeBadgeAttribute()
    {
        $badges = [
            'in' => 'badge-success',
            'out' => 'badge-danger',
            'adjustment' => 'badge-warning',
            'return' => 'badge-info',
        ];

        return $badges[$this->type] ?? 'badge-secondary';
    }

    public function getFormattedQuantityAttribute()
    {
        $prefix = $this->type === 'in' || $this->type === 'return' ? '+' : '-';
        return $prefix . $this->quantity;
    }
} 