<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Salon;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;

class AdminUserController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at the route level
    }

    /**
     * Display a listing of users with advanced filtering and search
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'salon']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Role filtering
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Status filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Salon filtering
        if ($request->filled('salon')) {
            $query->where('salon_id', $request->salon);
        }

        // Date range filtering
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20)->withQueryString();

        // Get available filters
        $roles = SpatieRole::all();
        $salons = Salon::all();
        $statuses = ['active', 'inactive', 'suspended', 'pending'];

        // Statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'salons', 'statuses', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = SpatieRole::all();
        $salons = Salon::all();
        
        return view('admin.users.create', compact('roles', 'salons'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'salon_id' => 'nullable|exists:salons,id',
            'status' => 'required|in:active,inactive,suspended,pending',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userData = $request->only(['name', 'email', 'phone', 'status']);
        $userData['password'] = Hash::make($request->password);
        $userData['email_verified_at'] = now(); // Auto-verify admin-created users

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar_url'] = asset('storage/' . $avatarPath);
        }

        $user = User::create($userData);

        // Assign role
        $user->assignRole($request->role);

        // Assign salon if specified
        if ($request->filled('salon_id')) {
            $user->update(['salon_id' => $request->salon_id]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['roles', 'salon', 'permissions']);
        
        // Get user activity (placeholder - you can implement actual activity tracking)
        $recentActivity = collect([
            (object) [
                'action' => 'Login',
                'description' => 'User logged in',
                'timestamp' => $user->last_login_at ?? 'Never',
                'ip_address' => 'N/A'
            ]
        ]);

        return view('admin.users.show', compact('user', 'recentActivity'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = SpatieRole::all();
        $salons = Salon::all();
        $user->load('roles');
        
        return view('admin.users.edit', compact('user', 'roles', 'salons'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'salon_id' => 'nullable|exists:salons,id',
            'status' => 'required|in:active,inactive,suspended,pending',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userData = $request->only(['name', 'email', 'phone', 'status']);

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar_url'] = asset('storage/' . $avatarPath);
        }

        $user->update($userData);

        // Update role
        $user->syncRoles([$request->role]);

        // Update salon
        $user->update(['salon_id' => $request->salon_id]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deletion of super admin users
        if ($user->hasRole('super_admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete super admin users.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk actions on users
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,suspend,delete,change_role',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'new_role' => 'required_if:action,change_role|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $users = User::whereIn('id', $request->user_ids);

        switch ($request->action) {
            case 'activate':
                $users->update(['status' => 'active']);
                $message = 'Users activated successfully.';
                break;

            case 'deactivate':
                $users->update(['status' => 'inactive']);
                $message = 'Users deactivated successfully.';
                break;

            case 'suspend':
                $users->update(['status' => 'suspended']);
                $message = 'Users suspended successfully.';
                break;

            case 'delete':
                // Prevent deletion of super admin users
                $superAdmins = $users->whereHas('roles', function($q) {
                    $q->where('name', 'super_admin');
                })->count();
                
                if ($superAdmins > 0) {
                    return redirect()->back()
                        ->with('error', 'Cannot delete super admin users.');
                }
                
                $users->delete();
                $message = 'Users deleted successfully.';
                break;

            case 'change_role':
                $users->get()->each(function($user) use ($request) {
                    $user->syncRoles([$request->new_role]);
                });
                $message = 'User roles updated successfully.';
                break;
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $users = User::with(['roles', 'salon'])->get();
        
        if ($format === 'json') {
            return response()->json($users);
        }

        // For CSV export, you would implement CSV generation logic here
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'suspended_users' => User::where('status', 'suspended')->count(),
            'pending_users' => User::where('status', 'pending')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_users_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'User password reset successfully.');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => "User status changed to {$newStatus}",
            'new_status' => $newStatus
        ]);
    }
} 