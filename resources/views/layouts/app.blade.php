<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Salon Management System')</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body class="modern-layout">
    @auth
        <div class="app-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <a href="{{ route('dashboard') }}" class="sidebar-brand">
                        <div class="brand-icon">
                            <i class="fas fa-cut"></i>
                        </div>
                        <span class="brand-text">SalonPro</span>
                    </a>
                    <button class="sidebar-close" onclick="toggleSidebar()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="sidebar-content">
                    <nav class="sidebar-nav">
                        <!-- Dashboard -->
                        <div class="nav-section">
                            <div class="nav-section-title">Main</div>
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-th-large nav-icon"></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </div>
                        
                        @role('super_admin')
                        <!-- Admin Section -->
                        <div class="nav-section">
                            <div class="nav-section-title">Management</div>
                            <a href="{{ route('admin.salons.index') }}" class="nav-link {{ request()->routeIs('admin.salons.*') ? 'active' : '' }}">
                                <i class="fas fa-store nav-icon"></i>
                                <span class="nav-text">Salons</span>
                                <span class="badge badge-primary ms-auto">{{ App\Models\Salon::count() }}</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon"></i>
                                <span class="nav-text">Users</span>
                            </a>
                            <a href="{{ route('admin.subscription-plans.index') }}" class="nav-link {{ request()->routeIs('admin.subscription-plans.*') ? 'active' : '' }}">
                                <i class="fas fa-credit-card nav-icon"></i>
                                <span class="nav-text">Subscription Plans</span>
                            </a>
                        </div>
                        
                        <!-- Analytics -->
                        <div class="nav-section">
                            <div class="nav-section-title">Insights</div>
                            <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line nav-icon"></i>
                                <span class="nav-text">Analytics</span>
                            </a>
                        </div>
                        
                        <!-- Content -->
                        <div class="nav-section">
                            <div class="nav-section-title">Content</div>
                            <a href="{{ route('admin.blogs.index') }}" class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                                <i class="fas fa-newspaper nav-icon"></i>
                                <span class="nav-text">Blog Posts</span>
                            </a>
                            <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt nav-icon"></i>
                                <span class="nav-text">Pages</span>
                            </a>
                        </div>
                        
                        <!-- Settings -->
                        <div class="nav-section">
                            <div class="nav-section-title">System</div>
                            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="fas fa-cog nav-icon"></i>
                                <span class="nav-text">Settings</span>
                            </a>
                        </div>
                        @endrole
                        
                        @role('salon_owner|manager|employee')
                        <!-- Business Section -->
                        <div class="nav-section">
                            <div class="nav-section-title">Business</div>
                            <a href="{{ route('salon.services.index') }}" class="nav-link {{ request()->routeIs('salon.services.*') ? 'active' : '' }}">
                                <i class="fas fa-concierge-bell nav-icon"></i>
                                <span class="nav-text">Services</span>
                            </a>
                            <a href="{{ route('salon.products.index') }}" class="nav-link {{ request()->routeIs('salon.products.*') ? 'active' : '' }}">
                                <i class="fas fa-box nav-icon"></i>
                                <span class="nav-text">Products</span>
                            </a>
                        </div>
                        
                        <!-- Operations -->
                        <div class="nav-section">
                            <div class="nav-section-title">Operations</div>
                            <a href="{{ route('salon.appointments.index') }}" class="nav-link {{ request()->routeIs('salon.appointments.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check nav-icon"></i>
                                <span class="nav-text">Appointments</span>
                            </a>
                            <a href="{{ route('salon.orders.index') }}" class="nav-link {{ request()->routeIs('salon.orders.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart nav-icon"></i>
                                <span class="nav-text">Orders</span>
                            </a>
                        </div>
                        
                        @role('salon_owner')
                        <!-- Team -->
                        <div class="nav-section">
                            <div class="nav-section-title">Team</div>
                            <a href="{{ route('salon.employees.index') }}" class="nav-link {{ request()->routeIs('salon.employees.*') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon"></i>
                                <span class="nav-text">Employees</span>
                            </a>
                        </div>
                        @endrole
                        @endrole
                        
                        @role('customer')
                        <div class="nav-section">
                            <div class="nav-section-title">My Account</div>
                            <a href="{{ route('customer.appointments.index') }}" class="nav-link {{ request()->routeIs('customer.appointments.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check nav-icon"></i>
                                <span class="nav-text">My Appointments</span>
                            </a>
                            <a href="{{ route('customer.orders.index') }}" class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-bag nav-icon"></i>
                                <span class="nav-text">My Orders</span>
                            </a>
                        </div>
                        @endrole
                    </nav>
                </div>
                
                <div class="sidebar-footer">
                    <div class="user-profile">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="user-avatar" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff'">
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">{{ Auth::user()->roles->first()->name ?? 'User' }}</div>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Main Content -->
            <div class="main-wrapper">
                <!-- Top Header -->
                <header class="top-header">
                    <div class="header-left">
                        <button class="sidebar-toggle" onclick="toggleSidebar()">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="breadcrumb">
                            <i class="fas fa-home breadcrumb-icon"></i>
                            <span class="breadcrumb-text">@yield('breadcrumb', 'Dashboard')</span>
                        </div>
                    </div>
                    
                    <div class="header-right">
                        <!-- Search -->
                        <div class="header-search">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" placeholder="Search..." class="search-input">
                        </div>
                        
                        <!-- Notifications -->
                        <div class="header-item">
                            <button class="icon-button">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge">3</span>
                            </button>
                        </div>
                        
                        <!-- Messages -->
                        <div class="header-item">
                            <button class="icon-button">
                                <i class="fas fa-envelope"></i>
                                <span class="notification-badge">5</span>
                            </button>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="header-item user-menu-wrapper">
                            <button class="user-menu-trigger" onclick="toggleUserMenu()">
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="user-avatar-sm" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff'">
                                <span class="user-name-text">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown" id="userDropdown">
                                <div class="dropdown-header">
                                    <div class="user-details">
                                        <div class="user-name">{{ Auth::user()->name }}</div>
                                        <div class="user-email">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    <span>My Profile</span>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Account Settings</span>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-question-circle"></i>
                                    <span>Help & Support</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>
                
                <!-- Page Content -->
                <main class="content-area">
                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                            <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                <strong>Please fix the following errors:</strong>
                                <ul class="error-list">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </main>
                
                <!-- Footer -->
                <footer class="main-footer">
                    <div class="footer-content">
                        <div class="footer-left">
                            <span>&copy; {{ date('Y') }} SalonPro. All rights reserved.</span>
                        </div>
                        <div class="footer-right">
                            <a href="#" class="footer-link">Privacy Policy</a>
                            <a href="#" class="footer-link">Terms of Service</a>
                            <a href="#" class="footer-link">Support</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', document.getElementById('sidebar').classList.contains('collapsed'));
        }
        
        // Toggle User Menu
        function toggleUserMenu() {
            document.getElementById('userDropdown').classList.toggle('show');
        }
        
        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu-wrapper');
            const dropdown = document.getElementById('userDropdown');
            if (dropdown && !userMenu.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
        
        // Load sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.getElementById('sidebar')?.classList.add('collapsed');
            }
        });
        
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html> 