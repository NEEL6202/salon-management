<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'salon_id',
        'points_per_dollar',
        'points_required',
        'reward_value',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function loyaltyRewards()
    {
        return $this->hasMany(LoyaltyReward::class);
    }
}
