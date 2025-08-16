<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Subscription as StripeSubscription;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);
        $user = Auth::user();

        try {
            // Create or get Stripe customer
            $customer = $this->getOrCreateStripeCustomer($user);

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $plan->price * 100, // Convert to cents
                'currency' => 'usd',
                'customer' => $customer->id,
                'metadata' => [
                    'subscription_plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'salon_id' => $user->salon_id,
                ],
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'customer_id' => $customer->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment intent creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment setup failed'], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = Auth::user();
        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        try {
            // Retrieve payment intent
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                // Create subscription
                $subscription = Subscription::create([
                    'salon_id' => $user->salon_id,
                    'subscription_plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => $this->calculateEndDate($plan->billing_cycle),
                    'trial_ends_at' => null, // Trial is over
                ]);

                // Create payment record
                Payment::create([
                    'subscription_id' => $subscription->id,
                    'amount' => $plan->price,
                    'currency' => 'usd',
                    'payment_method' => 'stripe',
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful! Subscription activated.',
                    'subscription_id' => $subscription->id,
                ]);
            } else {
                return response()->json(['error' => 'Payment not completed'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment confirmation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment confirmation failed'], 500);
        }
    }

    public function createSubscription(Request $request)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'payment_method_id' => 'required|string',
        ]);

        $user = Auth::user();
        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        try {
            // Create or get Stripe customer
            $customer = $this->getOrCreateStripeCustomer($user);

            // Create Stripe subscription
            $stripeSubscription = StripeSubscription::create([
                'customer' => $customer->id,
                'items' => [['price' => $plan->stripe_price_id]],
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            // Create local subscription
            $subscription = Subscription::create([
                'salon_id' => $user->salon_id,
                'subscription_plan_id' => $plan->id,
                'status' => 'incomplete',
                'stripe_subscription_id' => $stripeSubscription->id,
                'starts_at' => now(),
                'ends_at' => $this->calculateEndDate($plan->billing_cycle),
            ]);

            return response()->json([
                'subscription_id' => $subscription->id,
                'client_secret' => $stripeSubscription->latest_invoice->payment_intent->client_secret,
            ]);
        } catch (\Exception $e) {
            Log::error('Subscription creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Subscription creation failed'], 500);
        }
    }

    public function cancelSubscription(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        $subscription = Subscription::where('salon_id', Auth::user()->salon_id)
            ->findOrFail($request->subscription_id);

        try {
            if ($subscription->stripe_subscription_id) {
                $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_subscription_id);
                $stripeSubscription->cancel();
            }

            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription cancelled successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Subscription cancellation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Subscription cancellation failed'], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentSucceeded($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if ($subscription) {
            $subscription->update(['status' => 'active']);
            
            Payment::create([
                'subscription_id' => $subscription->id,
                'amount' => $invoice->amount_paid / 100,
                'currency' => $invoice->currency,
                'payment_method' => 'stripe',
                'stripe_invoice_id' => $invoice->id,
                'status' => 'completed',
                'paid_at' => now(),
            ]);
        }
    }

    private function handlePaymentFailed($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if ($subscription) {
            $subscription->update(['status' => 'past_due']);
        }
    }

    private function handleSubscriptionDeleted($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();
        
        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);
        }
    }

    private function getOrCreateStripeCustomer($user)
    {
        if ($user->stripe_customer_id) {
            return Customer::retrieve($user->stripe_customer_id);
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
                'salon_id' => $user->salon_id,
            ],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);
        return $customer;
    }

    private function calculateEndDate($billingCycle)
    {
        switch ($billingCycle) {
            case 'monthly':
                return now()->addMonth();
            case 'quarterly':
                return now()->addMonths(3);
            case 'yearly':
                return now()->addYear();
            default:
                return now()->addMonth();
        }
    }
} 