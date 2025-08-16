<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('super_admin')) {
            $appointments = Appointment::with(['customer', 'service.salon', 'employee'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
        } elseif ($user->hasRole('customer')) {
            $appointments = Appointment::where('customer_id', $user->id)
                ->with(['service.salon', 'employee'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
        } else {
            $appointments = Appointment::where('salon_id', $user->salon_id)
                ->with(['customer', 'service', 'employee'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
        }
        
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->hasRole('customer')) {
            $services = Service::where('is_active', true)
                ->with('salon')
                ->get();
            $employees = collect(); // Customers can't see employees
        } else {
            $services = Service::where('salon_id', $user->salon_id)
                ->where('is_active', true)
                ->get();
            $employees = User::where('salon_id', $user->salon_id)
                ->whereHas('roles', function($query) {
                    $query->whereIn('name', ['manager', 'employee']);
                })
                ->get();
        }
        
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->get();
        
        return view('appointments.create', compact('services', 'employees', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'customer_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required|date_format:H:i',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $user = Auth::user();
        $service = Service::findOrFail($request->service_id);
        
        // Combine date and time
        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        
        // Check for conflicts
        $conflict = Appointment::where('employee_id', $request->assigned_to)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_date', '>=', $appointmentDateTime)
            ->where('appointment_date', '<', $appointmentDateTime->copy()->addMinutes($service->duration))
            ->where('status', '!=', 'cancelled')
            ->exists();
            
        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked.']);
        }

        $appointment = Appointment::create([
            'service_id' => $request->service_id,
            'customer_id' => $request->customer_id,
            'salon_id' => $service->salon_id,
            'employee_id' => $request->assigned_to,
            'appointment_date' => $appointmentDateTime,
            'end_time' => $appointmentDateTime->copy()->addMinutes($service->duration),
            'total_amount' => $service->price,
            'final_amount' => $service->price,
            'notes' => $request->notes,
            'status' => $request->status,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('salon.appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'service.salon', 'employee']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $user = Auth::user();
        
        if ($user->hasRole('customer')) {
            $services = Service::where('is_active', true)
                ->with('salon')
                ->get();
            $employees = collect();
        } else {
            $services = Service::where('salon_id', $user->salon_id)
                ->where('is_active', true)
                ->get();
            $employees = User::where('salon_id', $user->salon_id)
                ->whereHas('roles', function($query) {
                    $query->whereIn('name', ['manager', 'employee']);
                })
                ->get();
        }
        
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->get();
        
        return view('appointments.edit', compact('appointment', 'services', 'employees', 'customers'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'customer_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $service = Service::findOrFail($request->service_id);
        
        // Combine date and time
        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        
        // Check for conflicts (excluding current appointment)
        $conflict = Appointment::where('employee_id', $request->assigned_to)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_date', '>=', $appointmentDateTime)
            ->where('appointment_date', '<', $appointmentDateTime->copy()->addMinutes($service->duration))
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $appointment->id)
            ->exists();
            
        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked.']);
        }

        $appointment->update([
            'service_id' => $request->service_id,
            'customer_id' => $request->customer_id,
            'salon_id' => $service->salon_id,
            'employee_id' => $request->assigned_to,
            'appointment_date' => $appointmentDateTime,
            'end_time' => $appointmentDateTime->copy()->addMinutes($service->duration),
            'total_amount' => $service->price,
            'final_amount' => $service->price,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->route('salon.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('salon.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $appointment->update(['status' => $request->status]);

        return redirect()->route('salon.appointments.index')
            ->with('success', 'Appointment status updated successfully.');
    }
} 