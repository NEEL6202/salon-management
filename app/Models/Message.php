<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'salon_id',
        'subject',
        'message',
        'type',
        'priority',
        'read_at',
        'sent_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
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

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'badge-secondary',
            'normal' => 'badge-info',
            'high' => 'badge-warning',
            'urgent' => 'badge-danger',
        ];

        return $badges[$this->priority] ?? 'badge-secondary';
    }

    public function getTypeBadgeAttribute()
    {
        $badges = [
            'internal' => 'badge-primary',
            'notification' => 'badge-info',
            'system' => 'badge-dark',
        ];

        return $badges[$this->type] ?? 'badge-secondary';
    }
} 