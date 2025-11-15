<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salon_id',
        'loyalty_program_id',
        'points',
        'source',
        'sourceable_type',
        'sourceable_id',
        'description',
        'is_redeemed',
    ];

    protected $casts = [
        'is_redeemed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function loyaltyProgram()
    {
        return $this->belongsTo(LoyaltyProgram::class);
    }

    public function sourceable()
    {
        return $this->morphTo();
    }
}
