<!DOCTYPE html>
<html lang="en" data-theme="light" data-color="blue">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SalonPro - Modern Admin')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/modern-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    
    @stack('styles')
</head>
<body>
    @auth
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <!-- Brand -->
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <i class="fas fa-cut"></i>
                </div>
                <span class="brand-name">SalonPro</span>
            </div>
            
            <!-- Menu -->
            <div class="sidebar-menu">
                <!-- Dashboard -->
                <div class="menu-section">
                    <div class="menu-section-title">Main</div>
                    <div class="menu-item">
                        <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-th-large"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </div>
                </div>
                
                @role('super_admin')
                <!-- Management -->
                <div class="menu-section">
                    <div class="menu-section-title">Management</div>
                    <div class="menu-item">
                        <a href="{{ route('admin.salons.index') }}" class="menu-link {{ request()->routeIs('admin.salons.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-store"></i>
                            <span class="menu-text">Salons</span>
                            <span class="menu-badge">{{ App\Models\Salon::count() }}</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-users"></i>
                            <span class="menu-text">Users</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.subscription-plans.index') }}" class="menu-link {{ request()->routeIs('admin.subscription-plans.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-credit-card"></i>
                            <span class="menu-text">Subscriptions</span>
                        </a>
                    </div>
                </div>
                
                <!-- Analytics -->
                <div class="menu-section">
                    <div class="menu-section-title">Insights</div>
                    <div class="menu-item">
                        <a href="{{ route('admin.analytics.index') }}" class="menu-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-chart-line"></i>
                            <span class="menu-text">Analytics</span>
                        </a>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="menu-section">
                    <div class="menu-section-title">Content</div>
                    <div class="menu-item">
                        <a href="{{ route('admin.landing-page.index') }}" class="menu-link {{ request()->routeIs('admin.landing-page.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-home"></i>
                            <span class="menu-text">Landing Page</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.blogs.index') }}" class="menu-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-newspaper"></i>
                            <span class="menu-text">Blog Posts</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.pages.index') }}" class="menu-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-file-alt"></i>
                            <span class="menu-text">Pages</span>
                        </a>
                    </div>
                </div>
                
                <!-- Settings -->
                <div class="menu-section">
                    <div class="menu-section-title">System</div>
                    <div class="menu-item">
                        <a href="{{ route('admin.settings.index') }}" class="menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-cog"></i>
                            <span class="menu-text">Settings</span>
                        </a>
                    </div>
                </div>
                @endrole
                
                @role('salon_owner|manager|employee')
                <!-- Dashboard -->
                <div class="menu-section">
                    <div class="menu-section-title">Dashboard</div>
                    <div class="menu-item">
                        @role('salon_owner')
                        <a href="{{ route('salon.dashboard') }}" class="menu-link {{ request()->routeIs('salon.dashboard') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-tachometer-alt"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                        @endrole
                        @role('manager|employee')
                        <a href="{{ route('employee.dashboard') }}" class="menu-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-tachometer-alt"></i>
                            <span class="menu-text">My Dashboard</span>
                        </a>
                        @endrole
                    </div>
                </div>
                
                <!-- Business -->
                <div class="menu-section">
                    <div class="menu-section-title">Business</div>
                    <div class="menu-item">
                        <a href="{{ route('salon.services.index') }}" class="menu-link {{ request()->routeIs('salon.services.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-concierge-bell"></i>
                            <span class="menu-text">Services</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('salon.products.index') }}" class="menu-link {{ request()->routeIs('salon.products.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-box"></i>
                            <span class="menu-text">Products</span>
                        </a>
                    </div>
                    @role('salon_owner')
                    <div class="menu-item">
                        <a href="{{ route('salon.customers.index') }}" class="menu-link {{ request()->routeIs('salon.customers.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-user-friends"></i>
                            <span class="menu-text">Customers</span>
                        </a>
                    </div>
                    @endrole
                </div>
                
                <!-- Operations -->
                <div class="menu-section">
                    <div class="menu-section-title">Operations</div>
                    @role('manager|employee')
                    <div class="menu-item">
                        <a href="{{ route('employee.appointments.index') }}" class="menu-link {{ request()->routeIs('employee.appointments.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-calendar-check"></i>
                            <span class="menu-text">My Appointments</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('employee.customers.index') }}" class="menu-link {{ request()->routeIs('employee.customers.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-users"></i>
                            <span class="menu-text">My Customers</span>
                        </a>
                    </div>
                    @endrole
                    @role('salon_owner')
                    <div class="menu-item">
                        <a href="{{ route('salon.calendar.index') }}" class="menu-link {{ request()->routeIs('salon.calendar.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-calendar-alt"></i>
                            <span class="menu-text">Calendar</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('salon.appointments.index') }}" class="menu-link {{ request()->routeIs('salon.appointments.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-calendar-check"></i>
                            <span class="menu-text">Appointments</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('salon.orders.index') }}" class="menu-link {{ request()->routeIs('salon.orders.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-shopping-cart"></i>
                            <span class="menu-text">Orders</span>
                        </a>
                    </div>
                    @endrole
                </div>
                
                @role('salon_owner')
                <!-- Analytics -->
                <div class="menu-section">
                    <div class="menu-section-title">Insights</div>
                    <div class="menu-item">
                        <a href="{{ route('salon.analytics.index') }}" class="menu-link {{ request()->routeIs('salon.analytics.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-chart-line"></i>
                            <span class="menu-text">Analytics</span>
                        </a>
                    </div>
                </div>
                
                <!-- Customer Engagement -->
                <div class="menu-section">
                    <div class="menu-section-title">Customer Engagement</div>
                    <div class="menu-item">
                        <a href="{{ route('salon.loyalty.index') }}" class="menu-link {{ request()->routeIs('salon.loyalty.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-star"></i>
                            <span class="menu-text">Loyalty Programs</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('salon.gift-cards.index') }}" class="menu-link {{ request()->routeIs('salon.gift-cards.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-gift"></i>
                            <span class="menu-text">Gift Cards</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('salon.reviews.index') }}" class="menu-link {{ request()->routeIs('salon.reviews.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-comment"></i>
                            <span class="menu-text">Reviews</span>
                        </a>
                    </div>
                </div>
                
                <!-- Team -->
                <div class="menu-section">
                    <div class="menu-section-title">Team</div>
                    <div class="menu-item">
                        <a href="{{ route('salon.employees.index') }}" class="menu-link {{ request()->routeIs('salon.employees.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-users"></i>
                            <span class="menu-text">Employees</span>
                        </a>
                    </div>
                </div>
                
                <!-- Communications -->
                <div class="menu-section">
                    <div class="menu-section-title">Communications</div>
                    <div class="menu-item">
                        <a href="{{ route('notifications.index') }}" class="menu-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-bell"></i>
                            <span class="menu-text">Notifications</span>
                        </a>
                    </div>
                </div>
                
                <!-- Settings -->
                <div class="menu-section">
                    <div class="menu-section-title">System</div>
                    <div class="menu-item">
                        <a href="{{ route('salon.settings.index') }}" class="menu-link {{ request()->routeIs('salon.settings.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-cog"></i>
                            <span class="menu-text">Settings</span>
                        </a>
                    </div>
                </div>
                @endrole
                @endrole
                
                @role('customer')
                <div class="menu-section">
                    <div class="menu-section-title">My Account</div>
                    <div class="menu-item">
                        <a href="{{ route('customer.appointments.index') }}" class="menu-link {{ request()->routeIs('customer.appointments.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-calendar-check"></i>
                            <span class="menu-text">My Appointments</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('customer.orders.index') }}" class="menu-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-shopping-bag"></i>
                            <span class="menu-text">My Orders</span>
                        </a>
                    </div>
                </div>
                @endrole
            </div>
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="user-avatar" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff'">
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ Auth::user()->roles->first()->name ?? 'User' }}</div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="header-search">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Search anything...">
                    </div>
                </div>
                
                <div class="header-right">
                    <!-- Theme Toggle -->
                    <button class="header-btn" onclick="toggleTheme()" title="Toggle Dark Mode">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>
                    
                    <!-- Color Picker -->
                    <div class="dropdown">
                        <button class="header-btn" onclick="toggleDropdown('colorPicker')" title="Change Theme Color">
                            <i class="fas fa-palette"></i>
                        </button>
                        <div id="colorPicker" class="dropdown-menu">
                            <div style="padding: 0.75rem;">
                                <div style="font-weight: 600; margin-bottom: 0.5rem; font-size: 0.75rem; color: var(--text-secondary);">THEME COLOR</div>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                                    <button onclick="changeColor('blue')" style="width: 32px; height: 32px; border-radius: 50%; background: #3b82f6; border: 2px solid #e5e7eb; cursor: pointer;"></button>
                                    <button onclick="changeColor('purple')" style="width: 32px; height: 32px; border-radius: 50%; background: #8b5cf6; border: 2px solid #e5e7eb; cursor: pointer;"></button>
                                    <button onclick="changeColor('green')" style="width: 32px; height: 32px; border-radius: 50%; background: #10b981; border: 2px solid #e5e7eb; cursor: pointer;"></button>
                                    <button onclick="changeColor('orange')" style="width: 32px; height: 32px; border-radius: 50%; background: #f97316; border: 2px solid #e5e7eb; cursor: pointer;"></button>
                                    <button onclick="changeColor('pink')" style="width: 32px; height: 32px; border-radius: 50%; background: #ec4899; border: 2px solid #e5e7eb; cursor: pointer;"></button>
                                    <button onclick="changeColor('cyan')" style="width: 32px; height: 32px; border-radius: 50%; background: #06b6d4; border: 2px solid #e5e7eb; cursor: pointer;"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Settings -->
                    <button class="header-btn" onclick="toggleRightPanel()" title="Settings">
                        <i class="fas fa-sliders-h"></i>
                    </button>
                    
                    <!-- Notifications -->
                    <button class="header-btn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge-dot"></span>
                    </button>
                    
                    <!-- Messages -->
                    <button class="header-btn" title="Messages">
                        <i class="fas fa-envelope"></i>
                        <span class="badge-dot"></span>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="user-dropdown">
                        <div class="user-trigger" onclick="toggleDropdown('userMenu')">
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="trigger-avatar" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff'">
                            <span class="trigger-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down trigger-icon"></i>
                        </div>
                        <div id="userMenu" class="dropdown-menu">
                            <div class="dropdown-header">
                                <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                                <div class="dropdown-user-email">{{ Auth::user()->email }}</div>
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
                                <button type="submit" class="dropdown-item" style="color: var(--danger);">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="admin-content">
                <!-- Alerts -->
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Success!</div>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Error!</div>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Please fix the following errors:</div>
                        <ul style="margin: 0.5rem 0 0 0; padding-left: 1.25rem;">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif
                
                @yield('content')
            </div>
        </main>
        
        <!-- Right Settings Panel -->
        <div class="right-panel" id="rightPanel">
            <div class="right-panel-header">
                <h3>Settings</h3>
                <button onclick="toggleRightPanel()" class="panel-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="right-panel-content">
                <div class="setting-group">
                    <h4>Appearance</h4>
                    <div class="form-switch">
                        <input type="checkbox" id="darkModeSwitch" class="switch-input" onchange="toggleThemeSwitch()">
                        <label for="darkModeSwitch" class="switch-label">Dark Mode</label>
                    </div>
                </div>
                <div class="setting-group">
                    <h4>Sidebar</h4>
                    <div class="form-switch">
                        <input type="checkbox" id="sidebarCollapsed" class="switch-input" onchange="toggleSidebarSwitch()">
                        <label for="sidebarCollapsed" class="switch-label">Collapsed Sidebar</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-panel-overlay" id="rightPanelOverlay" onclick="toggleRightPanel()"></div>
    </div>
    @else
    @yield('content')
    @endauth
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Initialize Select2 on all select elements
        $(document).ready(function() {
            $('.form-select').select2({
                theme: 'default',
                width: '100%'
            });
        });
        
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
        
        // Toggle Theme
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
            document.getElementById('darkModeSwitch').checked = newTheme === 'dark';
        }
        
        function toggleThemeSwitch() {
            toggleTheme();
        }
        
        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        
        // Change Color
        function changeColor(color) {
            document.documentElement.setAttribute('data-color', color);
            localStorage.setItem('themeColor', color);
        }
        
        // Toggle Dropdown
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('show');
            
            // Close other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu.id !== id) {
                    menu.classList.remove('show');
                }
            });
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-dropdown') && !e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
        
        // Toggle Sidebar Switch
        function toggleSidebarSwitch() {
            toggleSidebar();
        }
        
        // Toggle Right Panel
        function toggleRightPanel() {
            document.getElementById('rightPanel').classList.toggle('show');
            document.getElementById('rightPanelOverlay').classList.toggle('show');
        }
        
        // Load saved preferences
        document.addEventListener('DOMContentLoaded', function() {
            // Load theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
            document.getElementById('darkModeSwitch').checked = savedTheme === 'dark';
            
            // Load color
            const savedColor = localStorage.getItem('themeColor') || 'blue';
            document.documentElement.setAttribute('data-color', savedColor);
            
            // Load sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.getElementById('adminSidebar').classList.add('collapsed');
                document.getElementById('sidebarCollapsed').checked = true;
            }
            
            // Auto hide alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 5000);
        });
    </script>
    
    <style>
        /* Right Panel Styles */
        .right-panel {
            position: fixed;
            top: 0;
            right: -320px;
            width: 320px;
            height: 100vh;
            background: var(--card-bg);
            border-left: 1px solid var(--border-color);
            box-shadow: var(--shadow-xl);
            transition: var(--transition);
            z-index: 1100;
        }
        
        .right-panel.show {
            right: 0;
        }
        
        .right-panel-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .right-panel-header h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }
        
        .panel-close {
            width: 32px;
            height: 32px;
            border: none;
            background: var(--bg-tertiary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            transition: var(--transition);
        }
        
        .panel-close:hover {
            background: var(--danger);
            color: white;
        }
        
        .right-panel-content {
            padding: 1.5rem;
        }
        
        .setting-group {
            margin-bottom: 2rem;
        }
        
        .setting-group h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .right-panel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1050;
        }
        
        .right-panel-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .dropdown {
            position: relative;
        }
    </style>
    
    @stack('scripts')
</body>
</html>
