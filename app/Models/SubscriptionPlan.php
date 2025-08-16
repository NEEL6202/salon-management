<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'trial_days',
        'max_employees',
        'max_services',
        'max_products',
        'is_active',
        'is_popular',
        'features',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    public function salons()
    {
        return $this->hasMany(Salon::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getFormattedBillingCycleAttribute()
    {
        return ucfirst($this->billing_cycle);
    }

    public function getFormattedPricePerMonthAttribute()
    {
        if ($this->billing_cycle === 'yearly') {
            return '$' . number_format($this->price / 12, 2);
        }
        return $this->formatted_price;
    }
} 