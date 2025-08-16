<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('super_admin')) {
            $orders = Order::with(['customer', 'salon', 'items.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif ($user->hasRole('customer')) {
            $orders = Order::where('customer_id', $user->id)
                ->with(['salon', 'items.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $orders = Order::where('salon_id', $user->salon_id)
                ->with(['customer', 'items.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->hasRole('customer')) {
            $products = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->with('salon')
                ->get();
        } else {
            $products = Product::where('salon_id', $user->salon_id)
                ->where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->get();
        }
        
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->get();
        
        return view('orders.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,online',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        DB::beginTransaction();
        
        try {
            $total = 0;
            $items = [];
            
            // Calculate total and validate stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }
                
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;
                
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ];
                
                // Update stock
                $product->decrement('stock_quantity', $item['quantity']);
            }
            
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'salon_id' => $user->salon_id,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->notes,
            ]);
            
            // Create order items
            foreach ($items as $item) {
                $order->items()->create($item);
            }
            
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Order created successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'salon', 'items.product']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $user = Auth::user();
        
        if ($user->hasRole('customer')) {
            $products = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->with('salon')
                ->get();
        } else {
            $products = Product::where('salon_id', $user->salon_id)
                ->where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->get();
        }
        
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->get();
        
        return view('orders.edit', compact('order', 'products', 'customers'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'payment_method' => 'required|in:cash,card,online',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'order_status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update([
            'customer_id' => $request->customer_id,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'order_status' => $request->order_status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        if ($order->order_status !== 'pending') {
            return back()->withErrors(['error' => 'Cannot delete non-pending orders.']);
        }

        // Restore stock
        foreach ($order->items as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'order_status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'order_status' => $request->order_status,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order status updated successfully.');
    }
} 