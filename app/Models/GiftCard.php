<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'salon_id',
        'created_by',
        'customer_id',
        'initial_amount',
        'balance',
        'currency',
        'message',
        'expires_at',
        'is_active',
        'redeemed_at',
        'settings',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
        'initial_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($giftCard) {
            if (empty($giftCard->code)) {
                $giftCard->code = strtoupper(Str::random(12));
            }
        });
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isRedeemed()
    {
        return !is_null($this->redeemed_at);
    }

    public function isActive()
    {
        return $this->is_active && !$this->isExpired() && !$this->isRedeemed();
    }
}
