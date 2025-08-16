@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Notifications</h4>
                    <div>
                        <button class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-1"></i> Mark All as Read
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-primary' }}" 
                                     id="notification-{{ $notification->id }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $notification->title }}</h6>
                                        <small class="text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                            @if(!$notification->read_at)
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endif
                                        </small>
                                    </div>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            @if($notification->type === 'appointment_confirmation')
                                                <i class="fas fa-calendar-check text-success"></i> Appointment
                                            @elseif($notification->type === 'appointment_reminder')
                                                <i class="fas fa-bell text-warning"></i> Reminder
                                            @elseif($notification->type === 'subscription_expiry')
                                                <i class="fas fa-exclamation-triangle text-danger"></i> Subscription
                                            @elseif($notification->type === 'payment_failed')
                                                <i class="fas fa-times-circle text-danger"></i> Payment
                                            @elseif($notification->type === 'welcome')
                                                <i class="fas fa-heart text-primary"></i> Welcome
                                            @elseif($notification->type === 'low_stock')
                                                <i class="fas fa-boxes text-warning"></i> Inventory
                                            @elseif($notification->type === 'new_order')
                                                <i class="fas fa-shopping-cart text-success"></i> Order
                                            @else
                                                <i class="fas fa-info-circle text-info"></i> System
                                            @endif
                                        </small>
                                        <div>
                                            @if(!$notification->read_at)
                                                <button class="btn btn-sm btn-outline-success me-2" 
                                                        onclick="markAsRead({{ $notification->id }})">
                                                    <i class="fas fa-check"></i> Mark Read
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
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications</h5>
                            <p class="text-muted">You're all caught up! Check back later for new updates.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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
            notification.classList.remove('list-group-item-primary');
            notification.querySelector('.badge').remove();
            notification.querySelector('.btn-outline-success').remove();
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