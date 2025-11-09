<?php

namespace App\Http\Controllers\Salon;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalonCustomerController extends Controller
{
    public function index(Request $request)
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'recent'); // recent, visits, revenue

        $customersQuery = User::whereHas('appointments', function($q) use ($salon) {
                $q->where('salon_id', $salon->id);
            })
            ->withCount([
                'appointments as total_visits' => function($q) use ($salon) {
                    $q->where('salon_id', $salon->id);
                },
                'appointments as completed_visits' => function($q) use ($salon) {
                    $q->where('salon_id', $salon->id)->where('status', 'completed');
                }
            ])
            ->with(['appointments' => function($q) use ($salon) {
                $q->where('salon_id', $salon->id)->latest()->take(1);
            }]);

        if ($search) {
            $customersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        switch ($sortBy) {
            case 'visits':
                $customersQuery->orderByDesc('total_visits');
                break;
            case 'name':
                $customersQuery->orderBy('name');
                break;
            case 'recent':
            default:
                $customersQuery->orderByDesc('created_at');
                break;
        }

        $customers = $customersQuery->paginate(20);

        // Calculate total revenue for each customer
        foreach ($customers as $customer) {
            $customer->total_revenue = Appointment::where('customer_id', $customer->id)
                ->where('salon_id', $salon->id)
                ->where('payment_status', 'paid')
                ->sum('final_amount');
            
            $customer->last_visit = $customer->appointments->first()?->appointment_date;
        }

        return view('salon.customers.index', compact('customers', 'salon', 'search', 'sortBy'));
    }

    public function show(User $customer)
    {
        $salon = Auth::user()->salon;
        
        // Verify customer has appointments at this salon
        if (!$customer->appointments()->where('salon_id', $salon->id)->exists()) {
            abort(404, 'Customer not found');
        }

        // Customer statistics
        $totalVisits = $customer->appointments()
            ->where('salon_id', $salon->id)
            ->count();

        $completedVisits = $customer->appointments()
            ->where('salon_id', $salon->id)
            ->where('status', 'completed')
            ->count();

        $cancelledVisits = $customer->appointments()
            ->where('salon_id', $salon->id)
            ->where('status', 'cancelled')
            ->count();

        $totalSpent = $customer->appointments()
            ->where('salon_id', $salon->id)
            ->where('payment_status', 'paid')
            ->sum('final_amount');

        $totalOrders = $customer->orders()
            ->where('salon_id', $salon->id)
            ->count();

        $ordersValue = $customer->orders()
            ->where('salon_id', $salon->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // Appointment history
        $appointments = $customer->appointments()
            ->where('salon_id', $salon->id)
            ->with(['service', 'employee'])
            ->orderByDesc('appointment_date')
            ->paginate(10);

        // Order history
        $orders = $customer->orders()
            ->where('salon_id', $salon->id)
            ->with(['items.product'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Favorite services
        $favoriteServices = \DB::table('appointments')
            ->select('services.name', \DB::raw('COUNT(*) as booking_count'))
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.customer_id', $customer->id)
            ->where('appointments.salon_id', $salon->id)
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('booking_count')
            ->take(5)
            ->get();

        // Last visit
        $lastVisit = $customer->appointments()
            ->where('salon_id', $salon->id)
            ->orderByDesc('appointment_date')
            ->first();

        return view('salon.customers.show', compact(
            'customer',
            'salon',
            'totalVisits',
            'completedVisits',
            'cancelledVisits',
            'totalSpent',
            'totalOrders',
            'ordersValue',
            'appointments',
            'orders',
            'favoriteServices',
            'lastVisit'
        ));
    }

    public function create()
    {
        return view('salon.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'password' => bcrypt('password123'), // Default password
            'status' => 'active',
        ]);

        $customer->assignRole('customer');

        return redirect()->route('salon.customers.index')
            ->with('success', 'Customer created successfully. Default password: password123');
    }

    public function stats(Request $request)
    {
        $salon = Auth::user()->salon;
        $period = $request->get('period', 'month');

        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $newCustomers = User::whereHas('appointments', function($q) use ($salon, $startDate) {
                $q->where('salon_id', $salon->id)
                    ->whereBetween('created_at', [$startDate, now()]);
            })
            ->whereBetween('created_at', [$startDate, now()])
            ->count();

        $returningCustomers = User::whereHas('appointments', function($q) use ($salon, $startDate) {
                $q->where('salon_id', $salon->id)
                    ->whereBetween('created_at', [$startDate, now()]);
            })
            ->withCount([
                'appointments as visit_count' => function($q) use ($salon, $startDate) {
                    $q->where('salon_id', $salon->id)
                        ->whereBetween('created_at', [$startDate, now()]);
                }
            ])
            ->having('visit_count', '>', 1)
            ->count();

        $totalCustomers = User::whereHas('appointments', function($q) use ($salon) {
                $q->where('salon_id', $salon->id);
            })->count();

        return response()->json([
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'retention_rate' => $totalCustomers > 0 ? round(($returningCustomers / $totalCustomers) * 100, 2) : 0,
        ]);
    }
}
