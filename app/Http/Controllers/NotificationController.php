<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Appointment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentReminder;
use App\Mail\SubscriptionExpiry;
use App\Mail\PaymentFailed;
use App\Mail\WelcomeEmail;
use App\Services\SmsService;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->delete();

        return response()->json(['success' => true]);
    }

    // Static methods for sending notifications
    public static function sendAppointmentConfirmation(Appointment $appointment)
    {
        try {
            $user = $appointment->user;
            $salon = $appointment->salon;

            // Send email
            Mail::to($user->email)->send(new AppointmentConfirmation($appointment));

            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'appointment_confirmation',
                'title' => 'Appointment Confirmed',
                'message' => "Your appointment at {$salon->name} has been confirmed for " . $appointment->appointment_date->format('M d, Y \a\t g:i A'),
                'data' => [
                    'appointment_id' => $appointment->id,
                    'salon_name' => $salon->name,
                    'appointment_date' => $appointment->appointment_date,
                ],
            ]);

            Log::info("Appointment confirmation sent for appointment ID: {$appointment->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send appointment confirmation: " . $e->getMessage());
        }
    }

    public static function sendAppointmentReminder(Appointment $appointment)
    {
        try {
            $user = $appointment->user;
            $salon = $appointment->salon;

            // Send email reminder
            Mail::to($user->email)->send(new AppointmentReminder($appointment));

            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'appointment_reminder',
                'title' => 'Appointment Reminder',
                'message' => "Reminder: You have an appointment at {$salon->name} tomorrow at " . $appointment->appointment_date->format('g:i A'),
                'data' => [
                    'appointment_id' => $appointment->id,
                    'salon_name' => $salon->name,
                    'appointment_date' => $appointment->appointment_date,
                ],
            ]);

            Log::info("Appointment reminder sent for appointment ID: {$appointment->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send appointment reminder: " . $e->getMessage());
        }
    }

    public static function sendSubscriptionExpiry(Subscription $subscription)
    {
        try {
            $salon = $subscription->salon;
            $owner = $salon->owner;

            // Send email
            Mail::to($owner->email)->send(new SubscriptionExpiry($subscription));

            // Create notification
            Notification::create([
                'user_id' => $owner->id,
                'type' => 'subscription_expiry',
                'title' => 'Subscription Expiring Soon',
                'message' => "Your subscription for {$salon->name} will expire on " . $subscription->ends_at->format('M d, Y'),
                'data' => [
                    'subscription_id' => $subscription->id,
                    'salon_name' => $salon->name,
                    'expiry_date' => $subscription->ends_at,
                ],
            ]);

            Log::info("Subscription expiry notification sent for subscription ID: {$subscription->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send subscription expiry notification: " . $e->getMessage());
        }
    }

    public static function sendPaymentFailed(Subscription $subscription)
    {
        try {
            $salon = $subscription->salon;
            $owner = $salon->owner;

            // Send email
            Mail::to($owner->email)->send(new PaymentFailed($subscription));

            // Create notification
            Notification::create([
                'user_id' => $owner->id,
                'type' => 'payment_failed',
                'title' => 'Payment Failed',
                'message' => "Payment for your subscription at {$salon->name} has failed. Please update your payment method.",
                'data' => [
                    'subscription_id' => $subscription->id,
                    'salon_name' => $salon->name,
                ],
            ]);

            Log::info("Payment failed notification sent for subscription ID: {$subscription->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send payment failed notification: " . $e->getMessage());
        }
    }

    public static function sendWelcomeEmail(User $user)
    {
        try {
            // Send welcome email
            Mail::to($user->email)->send(new WelcomeEmail($user));

            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'welcome',
                'title' => 'Welcome to Salon Management System',
                'message' => 'Thank you for joining us! We\'re excited to have you on board.',
                'data' => [
                    'user_name' => $user->name,
                ],
            ]);

            Log::info("Welcome email sent for user ID: {$user->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send welcome email: " . $e->getMessage());
        }
    }

    public static function sendLowStockAlert($product)
    {
        try {
            $salon = $product->salon;
            $owner = $salon->owner;

            // Create notification
            Notification::create([
                'user_id' => $owner->id,
                'type' => 'low_stock',
                'title' => 'Low Stock Alert',
                'message' => "Product '{$product->name}' is running low on stock. Current quantity: {$product->stock_quantity}",
                'data' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'current_stock' => $product->stock_quantity,
                ],
            ]);

            Log::info("Low stock alert sent for product ID: {$product->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send low stock alert: " . $e->getMessage());
        }
    }

    public static function sendNewOrderNotification($order)
    {
        try {
            $salon = $order->salon;
            $owner = $salon->owner;

            // Create notification
            Notification::create([
                'user_id' => $owner->id,
                'type' => 'new_order',
                'title' => 'New Order Received',
                'message' => "New order #{$order->order_number} received for {$order->total_amount}",
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                ],
            ]);

            Log::info("New order notification sent for order ID: {$order->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send new order notification: " . $e->getMessage());
        }
    }

    public static function sendSmsAppointmentConfirmation(Appointment $appointment)
    {
        try {
            $user = $appointment->user;
            $salon = $appointment->salon;

            // Send SMS
            $smsService = new SmsService();
            $smsService->sendAppointmentConfirmation($appointment);

            Log::info("Appointment confirmation SMS sent for appointment ID: {$appointment->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send appointment confirmation SMS: " . $e->getMessage());
        }
    }

    public static function sendSmsAppointmentReminder(Appointment $appointment)
    {
        try {
            $user = $appointment->user;
            $salon = $appointment->salon;

            // Send SMS
            $smsService = new SmsService();
            $smsService->sendAppointmentReminder($appointment);

            Log::info("Appointment reminder SMS sent for appointment ID: {$appointment->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send appointment reminder SMS: " . $e->getMessage());
        }
    }

    public static function sendSmsSubscriptionExpiry(Subscription $subscription)
    {
        try {
            $salon = $subscription->salon;
            $owner = $salon->owner;

            // Send SMS
            $smsService = new SmsService();
            $smsService->sendSubscriptionExpiry($subscription);

            Log::info("Subscription expiry SMS sent for subscription ID: {$subscription->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send subscription expiry SMS: " . $e->getMessage());
        }
    }

    public static function sendSmsPaymentFailed(Subscription $subscription)
    {
        try {
            $salon = $subscription->salon;
            $owner = $salon->owner;

            // Send SMS
            $smsService = new SmsService();
            $smsService->sendPaymentFailed($subscription);

            Log::info("Payment failed SMS sent for subscription ID: {$subscription->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send payment failed SMS: " . $e->getMessage());
        }
    }

    public static function sendSmsWelcome(User $user)
    {
        try {
            // Send welcome SMS
            $smsService = new SmsService();
            $smsService->sendWelcomeSms($user);

            Log::info("Welcome SMS sent for user ID: {$user->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send welcome SMS: " . $e->getMessage());
        }
    }

    public static function sendSmsLowStockAlert($product)
    {
        try {
            $salon = $product->salon;
            $owner = $salon->owner;

            // Send SMS
            $smsService = new SmsService();
            $smsService->sendLowStockAlert($product);

            Log::info("Low stock alert SMS sent for product ID: {$product->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send low stock alert SMS: " . $e->getMessage());
        }
    }

    public static function sendSmsNewOrderNotification($order)
    {
        try {
            $salon = $order->salon;
            $owner = $salon->owner;

            // Send SMS
            $smsService = new SmsService();
            $smsService->sendNewOrderNotification($order);

            Log::info("New order notification SMS sent for order ID: {$order->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send new order notification SMS: " . $e->getMessage());
        }
    }

    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
} 