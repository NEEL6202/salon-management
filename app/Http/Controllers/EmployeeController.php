<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $salonId = $user->salon_id;
        
        // Get employee-specific data
        $todayAppointments = Appointment::where('employee_id', $user->id)
            ->whereDate('appointment_date', today())
            ->count();
            
        $pendingAppointments = Appointment::where('employee_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $totalCustomers = User::where('salon_id', $salonId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->count();
            
        $completedToday = Appointment::where('employee_id', $user->id)
            ->whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();
        
        // Get recent appointments for this employee only
        $recentAppointments = Appointment::where('employee_id', $user->id)
            ->with(['customer', 'service'])
            ->latest()
            ->take(10)
            ->get();
        
        // Get customers for this salon
        $customers = User::where('salon_id', $salonId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->get(['id', 'name', 'phone']);
        
        // Get services for this salon
        $services = Service::where('salon_id', $salonId)
            ->where('is_active', true)
            ->get(['id', 'name', 'price', 'duration']);
        
        // Prepare calendar events for this employee only
        $calendarEvents = Appointment::where('employee_id', $user->id)
            ->with(['customer', 'service'])
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->customer->name . ' - ' . $appointment->service->name,
                    'start' => $appointment->appointment_date . 'T' . $appointment->appointment_time,
                    'end' => Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)
                        ->addMinutes($appointment->service->duration)
                        ->format('Y-m-d\TH:i:s'),
                    'status' => $appointment->status,
                    'extendedProps' => [
                        'customer_name' => $appointment->customer->name,
                        'service_name' => $appointment->service->name,
                        'status' => $appointment->status,
                        'notes' => $appointment->notes
                    ]
                ];
            });
        
        return view('employee.dashboard', compact(
            'todayAppointments',
            'pendingAppointments',
            'totalCustomers',
            'completedToday',
            'recentAppointments',
            'customers',
            'services',
            'calendarEvents'
        ));
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('employee.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only(['name', 'phone', 'gender', 'date_of_birth', 'address']);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('users/avatars', 'public');
        }
        
        $user->update($data);
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    public function appointments()
    {
        $user = Auth::user();
        
        $appointments = Appointment::where('employee_id', $user->id)
            ->with(['customer', 'service'])
            ->latest()
            ->paginate(15);
        
        return view('employee.appointments.index', compact('appointments'));
    }
    
    public function showAppointment(Appointment $appointment)
    {
        // Ensure the appointment belongs to this employee
        if ($appointment->employee_id !== Auth::id()) {
            abort(403, 'You can only view your own appointments.');
        }
        
        return view('employee.appointments.show', compact('appointment'));
    }
    
    public function createAppointment()
    {
        $user = Auth::user();
        $salonId = $user->salon_id;
        
        $customers = User::where('salon_id', $salonId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->get();
            
        $services = Service::where('salon_id', $salonId)
            ->where('is_active', true)
            ->get();
        
        return view('employee.appointments.create', compact('customers', 'services'));
    }
    
    public function storeAppointment(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);
        
        // Ensure customer belongs to the same salon
        $customer = User::find($request->customer_id);
        if ($customer->salon_id !== $user->salon_id) {
            return response()->json(['success' => false, 'message' => 'Invalid customer selected.']);
        }
        
        // Ensure service belongs to the same salon
        $service = Service::find($request->service_id);
        if ($service->salon_id !== $user->salon_id) {
            return response()->json(['success' => false, 'message' => 'Invalid service selected.']);
        }
        
        $appointment = Appointment::create([
            'customer_id' => $request->customer_id,
            'employee_id' => $user->id,
            'service_id' => $request->service_id,
            'salon_id' => $user->salon_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);
        
        return response()->json(['success' => true, 'message' => 'Appointment created successfully!']);
    }
    
    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to this employee
        if ($appointment->employee_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You can only update your own appointments.']);
        }
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);
        
        $appointment->update(['status' => $request->status]);
        
        return response()->json(['success' => true, 'message' => 'Appointment status updated successfully!']);
    }
    
    public function customers()
    {
        $user = Auth::user();
        $salonId = $user->salon_id;
        
        $customers = User::where('salon_id', $salonId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->withCount(['appointments' => function($q) use ($user) {
                $q->where('employee_id', $user->id);
            }])
            ->paginate(15);
        
        return view('employee.customers.index', compact('customers'));
    }
    
    public function showCustomer(User $customer)
    {
        $user = Auth::user();
        
        // Ensure customer belongs to the same salon
        if ($customer->salon_id !== $user->salon_id) {
            abort(403, 'You can only view customers from your salon.');
        }
        
        // Get appointments for this customer with this employee
        $appointments = Appointment::where('customer_id', $customer->id)
            ->where('employee_id', $user->id)
            ->with(['service'])
            ->latest()
            ->paginate(10);
        
        return view('employee.customers.show', compact('customer', 'appointments'));
    }
}

