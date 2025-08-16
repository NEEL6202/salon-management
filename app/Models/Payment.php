<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salon_id',
        'subscription_id',
        'order_id',
        'payment_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'type',
        'payment_data',
        'description',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_data' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'processing' => 'badge-info',
            'completed' => 'badge-success',
            'failed' => 'badge-danger',
            'refunded' => 'badge-secondary',
            'cancelled' => 'badge-dark',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getTypeBadgeAttribute()
    {
        $badges = [
            'subscription' => 'badge-primary',
            'order' => 'badge-success',
            'refund' => 'badge-warning',
            'credit' => 'badge-info',
        ];

        return $badges[$this->type] ?? 'badge-secondary';
    }

    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
} 