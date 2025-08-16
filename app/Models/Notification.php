<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salon_id',
        'type',
        'title',
        'message',
        'data',
        'action_url',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function getTypeBadgeAttribute()
    {
        $badges = [
            'appointment' => 'badge-primary',
            'order' => 'badge-success',
            'payment' => 'badge-info',
            'system' => 'badge-dark',
            'reminder' => 'badge-warning',
        ];

        return $badges[$this->type] ?? 'badge-secondary';
    }

    public function getIconAttribute()
    {
        $icons = [
            'appointment' => 'fas fa-calendar-check',
            'order' => 'fas fa-shopping-cart',
            'payment' => 'fas fa-credit-card',
            'system' => 'fas fa-cog',
            'reminder' => 'fas fa-bell',
        ];

        return $icons[$this->type] ?? 'fas fa-info-circle';
    }
} 