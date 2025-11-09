<?php

namespace App\Http\Controllers\Salon;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Service;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalonAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        $period = $request->get('period', 'month'); // day, week, month, year
        $startDate = $this->getStartDate($period);
        $endDate = now();

        // Revenue Analytics
        $revenueData = $this->getRevenueAnalytics($salon, $startDate, $endDate);
        
        // Appointment Analytics
        $appointmentData = $this->getAppointmentAnalytics($salon, $startDate, $endDate);
        
        // Employee Performance
        $employeePerformance = $this->getEmployeePerformance($salon, $startDate, $endDate);
        
        // Service Popularity
        $servicePopularity = $this->getServicePopularity($salon, $startDate, $endDate);
        
        // Product Sales
        $productSales = $this->getProductSales($salon, $startDate, $endDate);
        
        // Customer Analytics
        $customerAnalytics = $this->getCustomerAnalytics($salon, $startDate, $endDate);

        return view('salon.analytics.index', compact(
            'salon',
            'period',
            'revenueData',
            'appointmentData',
            'employeePerformance',
            'servicePopularity',
            'productSales',
            'customerAnalytics'
        ));
    }

    private function getStartDate($period)
    {
        switch ($period) {
            case 'day':
                return now()->startOfDay();
            case 'week':
                return now()->startOfWeek();
            case 'year':
                return now()->startOfYear();
            case 'month':
            default:
                return now()->startOfMonth();
        }
    }

    private function getRevenueAnalytics($salon, $startDate, $endDate)
    {
        $revenue = Order::where('salon_id', $salon->id)
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $appointmentRevenue = Appointment::where('salon_id', $salon->id)
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(final_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = $revenue->sum('total') + $appointmentRevenue->sum('total');
        $totalOrders = Order::where('salon_id', $salon->id)
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'total' => $totalRevenue,
            'orders_revenue' => $revenue->sum('total'),
            'appointments_revenue' => $appointmentRevenue->sum('total'),
            'total_orders' => $totalOrders,
            'daily_revenue' => $revenue->merge($appointmentRevenue)->groupBy('date'),
        ];
    }

    private function getAppointmentAnalytics($salon, $startDate, $endDate)
    {
        $appointments = Appointment::where('salon_id', $salon->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $totalAppointments = $appointments->count();
        $completedAppointments = (clone $appointments)->where('status', 'completed')->count();
        $cancelledAppointments = (clone $appointments)->where('status', 'cancelled')->count();
        $pendingAppointments = (clone $appointments)->where('status', 'pending')->count();

        $completionRate = $totalAppointments > 0 ? ($completedAppointments / $totalAppointments) * 100 : 0;
        $cancellationRate = $totalAppointments > 0 ? ($cancelledAppointments / $totalAppointments) * 100 : 0;

        return [
            'total' => $totalAppointments,
            'completed' => $completedAppointments,
            'cancelled' => $cancelledAppointments,
            'pending' => $pendingAppointments,
            'completion_rate' => round($completionRate, 2),
            'cancellation_rate' => round($cancellationRate, 2),
        ];
    }

    private function getEmployeePerformance($salon, $startDate, $endDate)
    {
        $employees = User::where('salon_id', $salon->id)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['manager', 'employee']);
            })
            ->withCount([
                'appointments as total_appointments' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                },
                'appointments as completed_appointments' => function($q) use ($startDate, $endDate) {
                    $q->where('status', 'completed')
                        ->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->get();

        return $employees->map(function($employee) use ($startDate, $endDate) {
            $revenue = Appointment::where('employee_id', $employee->id)
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('final_amount');

            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'total_appointments' => $employee->total_appointments ?? 0,
                'completed_appointments' => $employee->completed_appointments ?? 0,
                'revenue' => $revenue ?? 0,
            ];
        });
    }

    private function getServicePopularity($salon, $startDate, $endDate)
    {
        return Service::where('salon_id', $salon->id)
            ->withCount([
                'appointments as bookings' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->having('bookings', '>', 0)
            ->orderByDesc('bookings')
            ->take(10)
            ->get();
    }

    private function getProductSales($salon, $startDate, $endDate)
    {
        return Product::where('salon_id', $salon->id)
            ->select('products.*')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.salon_id', $salon->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw('products.*, SUM(order_items.quantity) as total_sold, SUM(order_items.subtotal) as revenue')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();
    }

    private function getCustomerAnalytics($salon, $startDate, $endDate)
    {
        $newCustomers = Appointment::where('salon_id', $salon->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('customer_id')
            ->count('customer_id');

        $returningCustomers = Appointment::where('salon_id', $salon->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('customer_id')
            ->groupBy('customer_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        $topCustomers = User::whereHas('appointments', function($q) use ($salon, $startDate, $endDate) {
                $q->where('salon_id', $salon->id)
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->withCount([
                'appointments as visit_count' => function($q) use ($salon, $startDate, $endDate) {
                    $q->where('salon_id', $salon->id)
                        ->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->orderByDesc('visit_count')
            ->take(10)
            ->get();

        return [
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'top_customers' => $topCustomers,
        ];
    }

    public function export(Request $request)
    {
        $salon = Auth::user()->salon;
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $data = [
            'revenue' => $this->getRevenueAnalytics($salon, $startDate, $endDate),
            'appointments' => $this->getAppointmentAnalytics($salon, $startDate, $endDate),
            'employees' => $this->getEmployeePerformance($salon, $startDate, $endDate),
            'services' => $this->getServicePopularity($salon, $startDate, $endDate),
            'products' => $this->getProductSales($salon, $startDate, $endDate),
            'customers' => $this->getCustomerAnalytics($salon, $startDate, $endDate),
        ];

        $filename = 'salon-analytics-' . $period . '-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Revenue Section
            fputcsv($file, ['REVENUE ANALYTICS']);
            fputcsv($file, ['Total Revenue', '$' . number_format($data['revenue']['total'], 2)]);
            fputcsv($file, ['Orders Revenue', '$' . number_format($data['revenue']['orders_revenue'], 2)]);
            fputcsv($file, ['Appointments Revenue', '$' . number_format($data['revenue']['appointments_revenue'], 2)]);
            fputcsv($file, []);
            
            // Appointments Section
            fputcsv($file, ['APPOINTMENT ANALYTICS']);
            fputcsv($file, ['Total Appointments', $data['appointments']['total']]);
            fputcsv($file, ['Completed', $data['appointments']['completed']]);
            fputcsv($file, ['Cancelled', $data['appointments']['cancelled']]);
            fputcsv($file, ['Completion Rate', $data['appointments']['completion_rate'] . '%']);
            fputcsv($file, []);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
