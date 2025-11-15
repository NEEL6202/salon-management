<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class SmsService
{
    protected $twilio;
    protected $fromNumber;

    public function __construct()
    {
        // Initialize Twilio client if credentials are available
        $sid = setting('twilio_sid');
        $token = setting('twilio_token');
        $this->fromNumber = setting('twilio_from_number');

        if ($sid && $token && $this->fromNumber) {
            $this->twilio = new Client($sid, $token);
        }
    }

    /**
     * Send an SMS message
     */
    public function send($to, $message)
    {
        // Check if SMS is enabled
        if (!setting('sms_enabled', false)) {
            Log::info('SMS sending skipped - SMS not enabled');
            return false;
        }

        // Validate phone number format
        $to = $this->formatPhoneNumber($to);

        if (!$to) {
            Log::error('Invalid phone number format: ' . $to);
            return false;
        }

        // Check if Twilio is configured
        if (!$this->twilio) {
            Log::error('Twilio not configured properly');
            return false;
        }

        try {
            $this->twilio->messages->create($to, [
                'from' => $this->fromNumber,
                'body' => $message
            ]);

            Log::info("SMS sent successfully to {$to}");
            return true;
        } catch (Exception $e) {
            Log::error("Failed to send SMS to {$to}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number for Twilio
     */
    protected function formatPhoneNumber($number)
    {
        // Remove all non-digit characters
        $number = preg_replace('/\D/', '', $number);

        // If it starts with 0, assume it's a local number and add country code
        if (substr($number, 0, 1) === '0') {
            $number = '+1' . substr($number, 1);
        }

        // If it doesn't start with +, add +1 (assuming US/Canada)
        if (substr($number, 0, 1) !== '+') {
            $number = '+1' . $number;
        }

        // Validate the number format
        if (preg_match('/^\+\d{10,15}$/', $number)) {
            return $number;
        }

        return false;
    }

    /**
     * Send appointment confirmation SMS
     */
    public function sendAppointmentConfirmation($appointment)
    {
        $message = "Your appointment at {$appointment->salon->name} has been confirmed for " . 
                  $appointment->appointment_date->format('M d, Y \a\t g:i A') . 
                  ". Service: {$appointment->service->name}. Reply STOP to unsubscribe.";

        return $this->send($appointment->user->phone, $message);
    }

    /**
     * Send appointment reminder SMS
     */
    public function sendAppointmentReminder($appointment)
    {
        $message = "Reminder: You have an appointment at {$appointment->salon->name} tomorrow at " . 
                  $appointment->appointment_date->format('g:i A') . 
                  ". Reply STOP to unsubscribe.";

        return $this->send($appointment->user->phone, $message);
    }

    /**
     * Send subscription expiry SMS
     */
    public function sendSubscriptionExpiry($subscription)
    {
        $message = "Your subscription for {$subscription->salon->name} will expire on " . 
                  $subscription->ends_at->format('M d, Y') . 
                  ". Please renew to continue using our services. Reply STOP to unsubscribe.";

        return $this->send($subscription->salon->owner->phone, $message);
    }

    /**
     * Send payment failed SMS
     */
    public function sendPaymentFailed($subscription)
    {
        $message = "Payment for your subscription at {$subscription->salon->name} has failed. " . 
                  "Please update your payment method to avoid service interruption. Reply STOP to unsubscribe.";

        return $this->send($subscription->salon->owner->phone, $message);
    }

    /**
     * Send welcome SMS
     */
    public function sendWelcomeSms($user)
    {
        $message = "Welcome to our salon management system! We're excited to have you on board. Reply STOP to unsubscribe.";

        return $this->send($user->phone, $message);
    }

    /**
     * Send low stock alert SMS
     */
    public function sendLowStockAlert($product)
    {
        $message = "Low stock alert: Product '{$product->name}' is running low. Current quantity: {$product->stock_quantity}. Reply STOP to unsubscribe.";

        return $this->send($product->salon->owner->phone, $message);
    }

    /**
     * Send new order notification SMS
     */
    public function sendNewOrderNotification($order)
    {
        $message = "New order #{$order->order_number} received for \${$order->total_amount}. Reply STOP to unsubscribe.";

        return $this->send($order->salon->owner->phone, $message);
    }
}