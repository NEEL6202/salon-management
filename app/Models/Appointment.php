<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'customer_id',
        'employee_id',
        'service_id',
        'appointment_date',
        'end_time',
        'status',
        'notes',
        'customer_notes',
        'total_amount',
        'discount_amount',
        'final_amount',
        'payment_status',
        'payment_method',
        'service_details',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'end_time' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'service_details' => 'array',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->appointment_date->format('M d, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->appointment_date->format('h:i A');
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('h:i A') : null;
    }

    public function getFormattedTotalAmountAttribute()
    {
        return '$' . number_format($this->total_amount, 2);
    }

    public function getFormattedFinalAmountAttribute()
    {
        return '$' . number_format($this->final_amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'confirmed' => 'badge-info',
            'in_progress' => 'badge-primary',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            'no_show' => 'badge-secondary',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'refunded' => 'badge-info',
        ];

        return $badges[$this->payment_status] ?? 'badge-secondary';
    }

    public function isUpcoming()
    {
        return $this->appointment_date->isFuture();
    }

    public function isToday()
    {
        return $this->appointment_date->isToday();
    }

    public function isPast()
    {
        return $this->appointment_date->isPast();
    }
} 