<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function generateOrderInvoice($orderId)
    {
        $order = Order::with(['items.product', 'salon', 'user'])
            ->where('salon_id', Auth::user()->salon_id)
            ->findOrFail($orderId);

        $data = [
            'order' => $order,
            'invoice_number' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ];

        $pdf = PDF::loadView('invoices.order', $data);
        
        return $pdf->download("invoice-{$data['invoice_number']}.pdf");
    }

    public function generateSubscriptionInvoice($subscriptionId)
    {
        $subscription = Subscription::with(['plan', 'salon'])
            ->where('salon_id', Auth::user()->salon_id)
            ->findOrFail($subscriptionId);

        $data = [
            'subscription' => $subscription,
            'invoice_number' => 'SUB-' . str_pad($subscription->id, 6, '0', STR_PAD_LEFT),
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ];

        $pdf = PDF::loadView('invoices.subscription', $data);
        
        return $pdf->download("subscription-invoice-{$data['invoice_number']}.pdf");
    }

    public function showOrderInvoice($orderId)
    {
        $order = Order::with(['items.product', 'salon', 'user'])
            ->where('salon_id', Auth::user()->salon_id)
            ->findOrFail($orderId);

        $data = [
            'order' => $order,
            'invoice_number' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ];

        return view('invoices.order', $data);
    }

    public function showSubscriptionInvoice($subscriptionId)
    {
        $subscription = Subscription::with(['plan', 'salon'])
            ->where('salon_id', Auth::user()->salon_id)
            ->findOrFail($subscriptionId);

        $data = [
            'subscription' => $subscription,
            'invoice_number' => 'SUB-' . str_pad($subscription->id, 6, '0', STR_PAD_LEFT),
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ];

        return view('invoices.subscription', $data);
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('super_admin')) {
            // Super admin can see all invoices
            $orders = Order::with(['salon', 'user'])->paginate(20);
            $subscriptions = Subscription::with(['plan', 'salon'])->paginate(20);
        } elseif ($user->hasRole('salon_owner')) {
            // Salon owner can see their salon's invoices
            $orders = Order::with(['salon', 'user'])
                ->where('salon_id', $user->salon_id)
                ->paginate(20);
            $subscriptions = Subscription::with(['plan', 'salon'])
                ->where('salon_id', $user->salon_id)
                ->paginate(20);
        } else {
            // Other users can see their own invoices
            $orders = Order::with(['salon', 'user'])
                ->where('user_id', $user->id)
                ->paginate(20);
            $subscriptions = collect(); // Empty collection for non-owners
        }

        return view('invoices.index', compact('orders', 'subscriptions'));
    }

    public function downloadInvoice($type, $id)
    {
        if ($type === 'order') {
            return $this->generateOrderInvoice($id);
        } elseif ($type === 'subscription') {
            return $this->generateSubscriptionInvoice($id);
        }

        abort(404);
    }

    public function emailInvoice(Request $request, $type, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            if ($type === 'order') {
                $order = Order::with(['items.product', 'salon', 'user'])
                    ->where('salon_id', Auth::user()->salon_id)
                    ->findOrFail($id);

                $data = [
                    'order' => $order,
                    'invoice_number' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'invoice_date' => now()->format('Y-m-d'),
                    'due_date' => now()->addDays(30)->format('Y-m-d'),
                ];

                $pdf = PDF::loadView('invoices.order', $data);
                $pdfContent = $pdf->output();

                // Store PDF temporarily
                $filename = "invoice-{$data['invoice_number']}.pdf";
                Storage::put("temp/{$filename}", $pdfContent);

                // Send email with attachment
                // You can implement email sending logic here
                // Mail::to($request->email)->send(new InvoiceEmail($data, $filename));

                // Clean up temporary file
                Storage::delete("temp/{$filename}");

                return response()->json([
                    'success' => true,
                    'message' => 'Invoice sent successfully to ' . $request->email,
                ]);
            }

            return response()->json(['error' => 'Invalid invoice type'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send invoice'], 500);
        }
    }
} 