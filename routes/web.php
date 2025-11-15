<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\SalonOwnerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\PlatformSettingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminAnalyticsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoyaltyProgramController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Landing Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Public Blog Pages
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'subject' => 'required|string',
        'message' => 'required|string|max:1000',
    ]);
    
    // Here you would normally send an email or save to database
    // For now, we'll just redirect back with success message
    
    return redirect()->route('contact')
        ->with('success', 'Thank you for contacting us! We\'ll get back to you soon.');
})->name('contact.submit');

// Test route for debugging
Route::get('/test-login', function () {
    return response()->json([
        'message' => 'Login test route working',
        'auth_check' => auth()->check(),
        'user' => auth()->user() ? auth()->user()->name : 'Not logged in'
    ]);
});

// Debug route for salon access
Route::get('/debug-salon-access', function () {
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $user = auth()->user();
    $hasRole = $user->hasRole('salon_owner');
    $roles = $user->roles->pluck('name');
    
    return response()->json([
        'user' => $user->name,
        'email' => $user->email,
        'has_salon_owner_role' => $hasRole,
        'roles' => $roles,
        'salon_id' => $user->salon_id
    ]);
})->middleware('auth');

// Simple test route for salon owner
Route::get('/test-salon-owner', function () {
    if (!auth()->check()) {
        return 'Not authenticated';
    }
    
    $user = auth()->user();
    if ($user->hasRole('salon_owner')) {
        return 'Access granted! User: ' . $user->name . ' has salon_owner role';
    }
    
    return 'Access denied! User: ' . $user->name . ' does not have salon_owner role. Roles: ' . $user->roles->pluck('name')->implode(', ');
})->middleware('auth');

// Test route for employee access
Route::get('/test-employee', function () {
    if (!auth()->check()) {
        return 'Not authenticated';
    }
    
    $user = auth()->user();
    $hasManagerRole = $user->hasRole('manager');
    $hasEmployeeRole = $user->hasRole('employee');
    $hasAnyRole = $user->hasAnyRole(['manager', 'employee']);
    
    return response()->json([
        'user' => $user->name,
        'email' => $user->email,
        'has_manager_role' => $hasManagerRole,
        'has_employee_role' => $hasEmployeeRole,
        'has_any_role' => $hasAnyRole,
        'all_roles' => $user->roles->pluck('name'),
        'salon_id' => $user->salon_id
    ]);
})->middleware('auth');

// Test route for employee dashboard access
Route::get('/test-employee-dashboard', function () {
    if (!auth()->check()) {
        return 'Not authenticated';
    }
    
    $user = auth()->user();
    
    if ($user->hasAnyRole(['manager', 'employee'])) {
        return 'Access granted! User: ' . $user->name . ' can access employee dashboard';
    }
    
    return 'Access denied! User: ' . $user->name . ' cannot access employee dashboard. Roles: ' . $user->roles->pluck('name')->implode(', ');
})->middleware('auth');


// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Email Verification Routes (require auth)
Route::middleware(['auth', 'signed'])->group(function () {
    Route::get('/email/verify', [AuthController::class, 'showEmailVerification'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->name('verification.send');
});

// Payment Routes
Route::middleware('auth')->group(function () {
    Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.create-intent');
    Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
    Route::post('/payment/create-subscription', [PaymentController::class, 'createSubscription'])->name('payment.create-subscription');
    Route::post('/payment/cancel-subscription', [PaymentController::class, 'cancelSubscription'])->name('payment.cancel-subscription');
});

// Stripe Webhook (no auth required)
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

// Notification Routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

// Invoice Routes
Route::middleware('auth')->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/order/{id}', [InvoiceController::class, 'showOrderInvoice'])->name('invoices.order.show');
    Route::get('/invoices/subscription/{id}', [InvoiceController::class, 'showSubscriptionInvoice'])->name('invoices.subscription.show');
    Route::get('/invoices/download/{type}/{id}', [InvoiceController::class, 'downloadInvoice'])->name('invoices.download');
    Route::post('/invoices/email/{type}/{id}', [InvoiceController::class, 'emailInvoice'])->name('invoices.email');
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Test route for debugging
    Route::get('/test-role', function () {
        $user = auth()->user();
        return response()->json([
            'user' => $user->name,
            'roles' => $user->roles->pluck('name'),
            'has_salon_owner' => $user->hasRole('salon_owner'),
            'has_any_role' => $user->hasAnyRole(['salon_owner', 'manager', 'employee'])
        ]);
    })->name('test.role');
    
    // Test view for debugging
    Route::get('/test-role-view', function () {
        return view('test-role');
    })->name('test.role.view');
});

// Admin routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Salon Management
    Route::resource('salons', SalonController::class);
    
    // User Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::get('/users/export', [AdminUserController::class, 'export'])->name('users.export');
    Route::get('/users/stats', [AdminUserController::class, 'getStats'])->name('users.stats');
    Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Blog Management
    Route::resource('blogs', \App\Http\Controllers\Admin\AdminBlogController::class)->names('blogs');
    Route::post('/blogs/bulk-action', [\App\Http\Controllers\Admin\AdminBlogController::class, 'bulkAction'])->name('blogs.bulk-action');
    Route::get('/blogs/export', [\App\Http\Controllers\Admin\AdminBlogController::class, 'export'])->name('blogs.export');
    Route::get('/blogs/stats', [\App\Http\Controllers\Admin\AdminBlogController::class, 'getStats'])->name('blogs.stats');
    Route::post('/blogs/{blog}/toggle-status', [\App\Http\Controllers\Admin\AdminBlogController::class, 'toggleStatus'])->name('blogs.toggle-status');

    // Landing Page Management
    Route::get('/landing-page', [\App\Http\Controllers\Admin\LandingPageController::class, 'index'])->name('landing-page.index');
    Route::post('/landing-page/initialize', [\App\Http\Controllers\Admin\LandingPageController::class, 'initialize'])->name('landing-page.initialize');
    Route::get('/landing-page/{id}/edit', [\App\Http\Controllers\Admin\LandingPageController::class, 'edit'])->name('landing-page.edit');
    Route::put('/landing-page/{id}', [\App\Http\Controllers\Admin\LandingPageController::class, 'update'])->name('landing-page.update');
    Route::post('/landing-page/{id}/toggle', [\App\Http\Controllers\Admin\LandingPageController::class, 'toggleStatus'])->name('landing-page.toggle');
    
    // Page Management (CMS)
    Route::resource('pages', \App\Http\Controllers\Admin\AdminPageController::class)->names('pages');
    Route::post('/pages/bulk-action', [\App\Http\Controllers\Admin\AdminPageController::class, 'bulkAction'])->name('pages.bulk-action');
    Route::get('/pages/export', [\App\Http\Controllers\Admin\AdminPageController::class, 'export'])->name('pages.export');
    Route::get('/pages/stats', [\App\Http\Controllers\Admin\AdminPageController::class, 'getStats'])->name('pages.stats');
    Route::post('/pages/{page}/toggle-status', [\App\Http\Controllers\Admin\AdminPageController::class, 'toggleStatus'])->name('pages.toggle-status');
    Route::post('/pages/{page}/set-homepage', [\App\Http\Controllers\Admin\AdminPageController::class, 'setHomepage'])->name('pages.set-homepage');
    Route::get('/pages/{page}/preview', [\App\Http\Controllers\Admin\AdminPageController::class, 'preview'])->name('pages.preview');
    Route::post('/pages/{page}/duplicate', [\App\Http\Controllers\Admin\AdminPageController::class, 'duplicate'])->name('pages.duplicate');
    
    // System Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/{group}', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'updateGroup'])->name('settings.update-group');
    
    // Settings sub-pages
    Route::get('/settings/general', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'general'])->name('settings.general');
    Route::get('/settings/email', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'email'])->name('settings.email');
    Route::get('/settings/payment', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'payment'])->name('settings.payment');
    Route::get('/settings/notifications', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'notifications'])->name('settings.notifications');
    Route::get('/settings/security', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'security'])->name('settings.security');
    Route::get('/settings/appearance', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'appearance'])->name('settings.appearance');
    Route::get('/settings/integrations', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'integrations'])->name('settings.integrations');
    Route::get('/settings/backup', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'backup'])->name('settings.backup');
    
    // System Maintenance
    Route::post('/settings/clear-cache', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/optimize-database', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'optimizeDatabase'])->name('settings.optimize-database');
    Route::post('/settings/create-backup', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'createBackup'])->name('settings.create-backup');
    Route::post('/settings/toggle-maintenance', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'toggleMaintenanceMode'])->name('settings.toggle-maintenance');
    Route::post('/settings/system-check', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'runSystemCheck'])->name('settings.system-check');
    Route::post('/settings/clear-logs', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'clearLogs'])->name('settings.clear-logs');
    
    // Platform Settings
    Route::resource('platform-settings', PlatformSettingController::class);
    Route::post('/platform-settings/bulk-update', [PlatformSettingController::class, 'updateBulk'])->name('platform-settings.bulk-update');
    Route::get('/platform-settings/public', [PlatformSettingController::class, 'getPublicSettings'])->name('platform-settings.public');
    
    // Analytics & Reports
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export', [AdminAnalyticsController::class, 'export'])->name('analytics.export');
    
    // Subscription Plans
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    
    // Messages
    Route::resource('messages', MessageController::class);
    Route::get('/messages/inbox', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
    Route::post('/messages/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
    Route::get('/messages/unread-count', [MessageController::class, 'getUnreadCount'])->name('messages.unread-count');
});

// Salon Owner routes
Route::middleware(['auth', 'role:salon_owner'])->prefix('salon')->name('salon.')->group(function () {
    Route::get('/dashboard', [SalonOwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [SalonOwnerController::class, 'profile'])->name('profile');
    Route::put('/profile', [SalonOwnerController::class, 'updateProfile'])->name('profile.update');
    
    // Analytics & Reports
    Route::get('/analytics', [\App\Http\Controllers\Salon\SalonAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export', [\App\Http\Controllers\Salon\SalonAnalyticsController::class, 'export'])->name('analytics.export');
    
    // Calendar & Schedule
    Route::get('/calendar', [\App\Http\Controllers\Salon\SalonCalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [\App\Http\Controllers\Salon\SalonCalendarController::class, 'getEvents'])->name('calendar.events');
    
    // Customer Management
    Route::get('/customers', [\App\Http\Controllers\Salon\SalonCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [\App\Http\Controllers\Salon\SalonCustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [\App\Http\Controllers\Salon\SalonCustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [\App\Http\Controllers\Salon\SalonCustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/stats', [\App\Http\Controllers\Salon\SalonCustomerController::class, 'stats'])->name('customers.stats');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Salon\SalonSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Salon\SalonSettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/business-hours', [\App\Http\Controllers\Salon\SalonSettingsController::class, 'businessHours'])->name('settings.business-hours');
    Route::put('/settings/business-hours', [\App\Http\Controllers\Salon\SalonSettingsController::class, 'updateBusinessHours'])->name('settings.business-hours.update');
    
    // Employee Management
    Route::get('/employees', [SalonOwnerController::class, 'employees'])->name('employees.index');
    Route::get('/employees/create', [SalonOwnerController::class, 'createEmployee'])->name('employees.create');
    Route::post('/employees', [SalonOwnerController::class, 'storeEmployee'])->name('employees.store');
    Route::get('/employees/{employee}', [SalonOwnerController::class, 'showEmployee'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [SalonOwnerController::class, 'editEmployee'])->name('employees.edit');
    Route::put('/employees/{employee}', [SalonOwnerController::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/employees/{employee}', [SalonOwnerController::class, 'destroyEmployee'])->name('employees.destroy');
    
    // Services
    Route::resource('services', ServiceController::class);
    
    // Products
    Route::resource('products', ProductController::class);
    
    // Appointments
    Route::resource('appointments', AppointmentController::class);
    
    // Orders
    Route::resource('orders', OrderController::class);
    
    // Loyalty Program
    Route::resource('loyalty', LoyaltyProgramController::class);
    Route::post('/loyalty/{loyaltyProgram}/award-points', [LoyaltyProgramController::class, 'awardPoints'])->name('loyalty.award-points');
    Route::post('/loyalty/{loyaltyProgram}/redeem-points', [LoyaltyProgramController::class, 'redeemPoints'])->name('loyalty.redeem-points');
    Route::get('/loyalty/{loyaltyProgram}/customer-points/{userId}', [LoyaltyProgramController::class, 'customerPoints'])->name('loyalty.customer-points');
    
    // Gift Cards
    Route::resource('gift-cards', GiftCardController::class);
    Route::post('/gift-cards/{giftCard}/redeem', [GiftCardController::class, 'redeem'])->name('gift-cards.redeem');
    Route::post('/gift-cards/apply', [GiftCardController::class, 'apply'])->name('gift-cards.apply');
    
    // Reviews
    Route::resource('reviews', ReviewController::class);
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/feature', [ReviewController::class, 'feature'])->name('reviews.feature');
    Route::get('/reviews/stats', [ReviewController::class, 'stats'])->name('reviews.stats');
});

// Salon Employee routes (for both manager and employee roles)
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    // Debug route to test access
    Route::get('/debug', function () {
        $user = auth()->user();
        return response()->json([
            'user' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'),
            'salon_id' => $user->salon_id,
            'can_access' => $user->hasAnyRole(['manager', 'employee'])
        ]);
    })->name('debug');
    
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [EmployeeController::class, 'profile'])->name('profile');
    Route::put('/profile', [EmployeeController::class, 'updateProfile'])->name('profile.update');
    
    // Appointments (employee-specific)
    Route::get('/appointments', [EmployeeController::class, 'appointments'])->name('appointments.index');
    Route::get('/appointments/create', [EmployeeController::class, 'createAppointment'])->name('appointments.create');
    Route::post('/appointments', [EmployeeController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [EmployeeController::class, 'showAppointment'])->name('appointments.show');
    Route::patch('/appointments/{appointment}/status', [EmployeeController::class, 'updateAppointmentStatus'])->name('appointments.update-status');
    
    // Customers (employee can view their customers)
    Route::get('/customers', [EmployeeController::class, 'customers'])->name('customers.index');
    Route::get('/customers/{customer}', [EmployeeController::class, 'showCustomer'])->name('customers.show');
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('appointments', AppointmentController::class);
    Route::resource('orders', OrderController::class);
});
