<?php

namespace App\Http\Controllers\Salon;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalonCalendarController extends Controller
{
    public function index(Request $request)
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        $view = $request->get('view', 'month'); // day, week, month
        $date = $request->get('date', now()->format('Y-m-d'));
        $employeeId = $request->get('employee_id');

        $currentDate = Carbon::parse($date);

        // Get appointments based on view
        $appointmentsQuery = Appointment::where('salon_id', $salon->id)
            ->with(['customer', 'service', 'employee']);

        if ($employeeId) {
            $appointmentsQuery->where('employee_id', $employeeId);
        }

        switch ($view) {
            case 'day':
                $start = $currentDate->copy()->startOfDay();
                $end = $currentDate->copy()->endOfDay();
                break;
            case 'week':
                $start = $currentDate->copy()->startOfWeek();
                $end = $currentDate->copy()->endOfWeek();
                break;
            case 'month':
            default:
                $start = $currentDate->copy()->startOfMonth();
                $end = $currentDate->copy()->endOfMonth();
                break;
        }

        $appointments = $appointmentsQuery
            ->whereBetween('appointment_date', [$start, $end])
            ->orderBy('appointment_date')
            ->get();

        // Get employees for filter
        $employees = User::where('salon_id', $salon->id)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['manager', 'employee']);
            })
            ->get();

        // Format appointments for calendar
        $events = $appointments->map(function($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->customer->name . ' - ' . $appointment->service->name,
                'start' => $appointment->appointment_date->format('Y-m-d H:i:s'),
                'end' => $appointment->end_time->format('Y-m-d H:i:s'),
                'color' => $this->getStatusColor($appointment->status),
                'customer' => $appointment->customer->name,
                'service' => $appointment->service->name,
                'employee' => $appointment->employee?->name ?? 'Unassigned',
                'status' => $appointment->status,
                'amount' => $appointment->final_amount,
            ];
        });

        return view('salon.calendar.index', compact(
            'salon',
            'events',
            'view',
            'currentDate',
            'employees',
            'employeeId'
        ));
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'confirmed' => '#28a745',
            'completed' => '#007bff',
            'cancelled' => '#dc3545',
            'in_progress' => '#ffc107',
            default => '#6c757d',
        };
    }

    public function getEvents(Request $request)
    {
        $salon = Auth::user()->salon;
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        $employeeId = $request->employee_id;

        $appointmentsQuery = Appointment::where('salon_id', $salon->id)
            ->with(['customer', 'service', 'employee'])
            ->whereBetween('appointment_date', [$start, $end]);

        if ($employeeId) {
            $appointmentsQuery->where('employee_id', $employeeId);
        }

        $appointments = $appointmentsQuery->get();

        $events = $appointments->map(function($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->customer->name . ' - ' . $appointment->service->name,
                'start' => $appointment->appointment_date->toIso8601String(),
                'end' => $appointment->end_time->toIso8601String(),
                'backgroundColor' => $this->getStatusColor($appointment->status),
                'borderColor' => $this->getStatusColor($appointment->status),
                'extendedProps' => [
                    'customer' => $appointment->customer->name,
                    'service' => $appointment->service->name,
                    'employee' => $appointment->employee?->name ?? 'Unassigned',
                    'status' => $appointment->status,
                    'amount' => '$' . number_format($appointment->final_amount, 2),
                    'phone' => $appointment->customer->phone,
                ]
            ];
        });

        return response()->json($events);
    }
}
