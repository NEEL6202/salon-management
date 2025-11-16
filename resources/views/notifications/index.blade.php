@extends('layouts.modern')

@section('title', 'Notifications')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Notifications</h1>
        <p class="page-subtitle">Manage your system notifications</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="markAllAsRead()">
            <i class="fas fa-check-double me-2"></i> Mark All as Read
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($notifications->count() > 0)
            <div class="list-group">
                @foreach($notifications as $notification)
                    <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}" 
                         id="notification-{{ $notification->id }}">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="mb-0 me-2">{{ $notification->title }}</h6>
                                    @if(!$notification->read_at)
                                        <span class="badge bg-primary">New</span>
                                    @endif
                                </div>
                                <p class="mb-2">{{ $notification->message }}</p>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-3">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    <small class="text-muted">
                                        @if($notification->type === 'appointment_confirmation')
                                            <i class="fas fa-calendar-check text-success me-1"></i> Appointment
                                        @elseif($notification->type === 'appointment_reminder')
                                            <i class="fas fa-bell text-warning me-1"></i> Reminder
                                        @elseif($notification->type === 'subscription_expiry')
                                            <i class="fas fa-exclamation-triangle text-danger me-1"></i> Subscription
                                        @elseif($notification->type === 'payment_failed')
                                            <i class="fas fa-times-circle text-danger me-1"></i> Payment
                                        @elseif($notification->type === 'welcome')
                                            <i class="fas fa-heart text-primary me-1"></i> Welcome
                                        @elseif($notification->type === 'low_stock')
                                            <i class="fas fa-boxes text-warning me-1"></i> Inventory
                                        @elseif($notification->type === 'new_order')
                                            <i class="fas fa-shopping-cart text-success me-1"></i> Order
                                        @else
                                            <i class="fas fa-info-circle text-info me-1"></i> System
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="ms-3">
                                @if(!$notification->read_at)
                                    <button class="btn btn-sm btn-outline-success mb-2" 
                                            onclick="markAsRead({{ $notification->id }})">
                                        <i class="fas fa-check me-1"></i> Mark Read
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-outline-danger" 
                                        onclick="deleteNotification({{ $notification->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No notifications</h5>
                <p class="text-muted">You're all caught up! Check back later for new updates.</p>
            </div>
        @endif
    </div>
</div>

<script>
function markAsRead(id) {
    fetch(`/notifications/${id}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.getElementById(`notification-${id}`);
            notification.classList.remove('bg-light');
            const badge = notification.querySelector('.badge');
            if (badge) {
                badge.remove();
            }
            const markReadButton = notification.querySelector('.btn-outline-success');
            if (markReadButton) {
                markReadButton.remove();
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteNotification(id) {
    if (confirm('Are you sure you want to delete this notification?')) {
        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`notification-${id}`).remove();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endsection