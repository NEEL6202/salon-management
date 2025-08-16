<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Salon Management System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body>
    @auth
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <div class="text-center mb-4">
                            <h5 class="text-white">
                                <i class="fas fa-cut"></i>
                                Salon Management
                            </h5>
                        </div>
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                            </li>
                            
                            @role('super_admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.salons.*') ? 'active' : '' }}" href="{{ route('admin.salons.index') }}">
                                        <i class="fas fa-store"></i>
                                        Salons
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                        <i class="fas fa-users"></i>
                                        Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.subscription-plans.*') ? 'active' : '' }}" href="{{ route('admin.subscription-plans.index') }}">
                                        <i class="fas fa-credit-card"></i>
                                        Subscription Plans
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                        <i class="fas fa-cog"></i>
                                        Settings
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                                        <i class="fas fa-chart-line"></i>
                                        Analytics
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                                        <i class="fas fa-newspaper"></i>
                                        Blog Management
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                                        <i class="fas fa-file-alt"></i>
                                        Page Management
                                    </a>
                                </li>
                            @endrole
                            
                            @role('salon_owner|manager|employee')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('salon.services.*') ? 'active' : '' }}" href="{{ route('salon.services.index') }}">
                                        <i class="fas fa-concierge-bell"></i>
                                        Services
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('salon.products.*') ? 'active' : '' }}" href="{{ route('salon.products.index') }}">
                                        <i class="fas fa-box"></i>
                                        Products
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('salon.appointments.*') ? 'active' : '' }}" href="{{ route('salon.appointments.index') }}">
                                        <i class="fas fa-calendar-check"></i>
                                        Appointments
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('salon.orders.*') ? 'active' : '' }}" href="{{ route('salon.orders.index') }}">
                                        <i class="fas fa-shopping-cart"></i>
                                        Orders
                                    </a>
                                </li>
                                @role('salon_owner')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('salon.employees.*') ? 'active' : '' }}" href="{{ route('salon.employees.index') }}">
                                            <i class="fas fa-users"></i>
                                            Employees
                                        </a>
                                    </li>
                                @endrole
                            @endrole
                            
                            @role('customer')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('customer.appointments.*') ? 'active' : '' }}" href="{{ route('customer.appointments.index') }}">
                                        <i class="fas fa-calendar-check"></i>
                                        My Appointments
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">
                                        <i class="fas fa-shopping-cart"></i>
                                        My Orders
                                    </a>
                                </li>
                            @endrole
                        </ul>
                    </div>
                </nav>

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                    <!-- Top navbar -->
                    <nav class="navbar navbar-expand-lg navbar-light bg-white rounded-3 shadow-sm mb-4">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            
                            <div class="navbar-nav ms-auto">
                                <div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                        <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="user-avatar me-2">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <!-- Page content -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html> 