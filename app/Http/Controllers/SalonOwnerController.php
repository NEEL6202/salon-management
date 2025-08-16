<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\User;
use App\Models\Service;
use App\Models\Product;
use App\Models\Appointment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SalonOwnerController extends Controller
{
    public function dashboard()
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        // Get salon statistics
        $totalEmployees = $salon->users()->whereHas('roles', function($q) {
            $q->whereIn('name', ['manager', 'employee']);
        })->count();

        $totalServices = $salon->services()->count();
        $totalProducts = $salon->products()->count();
        
        // Appointment statistics
        $todayAppointments = $salon->appointments()
            ->whereDate('appointment_date', today())
            ->count();
        
        $pendingAppointments = $salon->appointments()
            ->where('status', 'pending')
            ->count();
        
        $completedAppointments = $salon->appointments()
            ->where('status', 'completed')
            ->whereMonth('appointment_date', now()->month)
            ->count();

        // Revenue statistics
        $monthlyRevenue = $salon->orders()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount') ?? 0;

        $todayRevenue = $salon->orders()
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount') ?? 0;

        // Recent activities
        $recentAppointments = $salon->appointments()
            ->with(['customer', 'service'])
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = $salon->orders()
            ->with(['customer'])
            ->latest()
            ->take(5)
            ->get();

        $recentEmployees = $salon->users()
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['manager', 'employee']);
            })
            ->latest()
            ->take(5)
            ->get();



        return view('salon.dashboard', compact(
            'salon',
            'totalEmployees',
            'totalServices',
            'totalProducts',
            'todayAppointments',
            'pendingAppointments',
            'completedAppointments',
            'monthlyRevenue',
            'todayRevenue',
            'recentAppointments',
            'recentOrders',
            'recentEmployees'
        ));
    }

    public function profile()
    {
        $salon = Auth::user()->salon;
        return view('salon.profile', compact('salon'));
    }

    public function updateProfile(Request $request)
    {
        $salon = Auth::user()->salon;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'opening_hours' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name', 'description', 'address', 'phone', 'email', 
            'website', 'opening_hours'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($salon->logo) {
                Storage::disk('public')->delete($salon->logo);
            }
            $data['logo'] = $request->file('logo')->store('salons/logos', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($salon->banner) {
                Storage::disk('public')->delete($salon->banner);
            }
            $data['banner'] = $request->file('banner')->store('salons/banners', 'public');
        }

        $salon->update($data);

        return redirect()->route('salon.profile')
            ->with('success', 'Salon profile updated successfully.');
    }

    public function employees()
    {
        $salon = Auth::user()->salon;
        $employees = $salon->users()
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['manager', 'employee']);
            })
            ->with('roles')
            ->latest()
            ->paginate(15);

        return view('salon.employees.index', compact('employees', 'salon'));
    }

    public function createEmployee()
    {
        return view('salon.employees.create');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:manager,employee',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string',
        ]);

        $salon = Auth::user()->salon;

        // Check employee limit based on subscription plan
        $currentEmployees = $salon->users()
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['manager', 'employee']);
            })
            ->count();

        $maxEmployees = $salon->subscriptionPlan->max_employees ?? 1;
        
        if ($currentEmployees >= $maxEmployees) {
            return back()->withErrors(['limit' => "You've reached the maximum number of employees allowed by your subscription plan."]);
        }

        $data = $request->only([
            'name', 'email', 'phone', 'gender', 'date_of_birth', 'address'
        ]);

        $data['password'] = Hash::make($request->password);
        $data['salon_id'] = $salon->id;
        $data['status'] = 'active';
        $data['created_by'] = Auth::id();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('users/avatars', 'public');
        }

        $user = User::create($data);
        $user->assignRole($request->role);

        return redirect()->route('salon.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function editEmployee(User $employee)
    {
        // Ensure the employee belongs to the current salon
        if ($employee->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        return view('salon.employees.edit', compact('employee'));
    }

    public function updateEmployee(Request $request, User $employee)
    {
        // Ensure the employee belongs to the current salon
        if ($employee->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:manager,employee',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'gender', 'date_of_birth', 'address', 'status'
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($employee->avatar) {
                Storage::disk('public')->delete($employee->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('users/avatars', 'public');
        }

        $employee->update($data);
        
        // Update role
        $employee->syncRoles([$request->role]);

        return redirect()->route('salon.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function showEmployee(User $employee)
    {
        // Ensure the employee belongs to the current salon
        if ($employee->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        // Get employee statistics
        $totalAppointments = $employee->appointments()->count();
        $completedAppointments = $employee->appointments()->where('status', 'completed')->count();
        $monthlyAppointments = $employee->appointments()
            ->whereMonth('created_at', now()->month)
            ->count();

        // Recent appointments
        $recentAppointments = $employee->appointments()
            ->with(['customer', 'service'])
            ->latest()
            ->take(10)
            ->get();

        return view('salon.employees.show', compact(
            'employee',
            'totalAppointments',
            'completedAppointments',
            'monthlyAppointments',
            'recentAppointments'
        ));
    }

    public function destroyEmployee(User $employee)
    {
        // Ensure the employee belongs to the current salon
        if ($employee->salon_id !== Auth::user()->salon_id) {
            abort(403);
        }

        // Check if employee has active appointments
        if ($employee->appointments()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Cannot delete employee with pending appointments.');
        }

        // Delete avatar if exists
        if ($employee->avatar) {
            Storage::disk('public')->delete($employee->avatar);
        }

        $employee->delete();

        return redirect()->route('salon.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
} 