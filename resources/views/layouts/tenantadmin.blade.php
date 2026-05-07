<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Super Admin</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #318069;
            --primary-dark: #276854;
            --primary-light: #e8f5f0;
            --secondary: #FFC107;
            --dark: #1e293b;
            --gray: #64748b;
            --gray-light: #f1f5f9;
            --border: #e2e8f0;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            overflow-x: hidden;
        }

        /* App Wrapper */
        .app-wrapper {
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .brand-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.3px;
        }

        .brand-badge {
            font-size: 0.65rem;
            background: var(--primary-light);
            color: var(--primary);
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Navigation Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            color: var(--gray);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        /* Header Right */
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-action-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            color: var(--gray);
        }

        .header-action-btn:hover {
            background: var(--gray-light);
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #ef4444;
            color: white;
            font-size: 0.6rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
            cursor: pointer;
        }

        .user-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            background: var(--gray-light);
            border-radius: 10px;
            transition: all 0.2s;
        }

        .user-trigger:hover {
            background: #e2e8f0;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark);
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--gray);
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            min-width: 220px;
            z-index: 1000;
            display: none;
        }

        .dropdown-menu-custom.show {
            display: block;
        }

        .dropdown-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
        }

        .dropdown-header .name {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--dark);
        }

        .dropdown-header .email {
            font-size: 0.7rem;
            color: var(--gray);
            margin-top: 0.25rem;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            color: var(--gray);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .dropdown-item i {
            font-size: 1rem;
            width: 20px;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 0.5rem 0;
        }

        /* Flash Messages */
        .flash-container {
            padding: 1rem 1.5rem 0;
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .alert-success {
            background: #f0fdf4;
            border-left: 3px solid #22c55e;
        }

        .alert-danger {
            background: #fef2f2;
            border-left: 3px solid #ef4444;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .alert-message {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .alert-close {
            background: none;
            border: none;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Content Area */
        .content-area {
            max-width: 930px;
            margin: 0 auto;
            flex: 1;
            padding: 1.5rem;
        }

        /* Footer */
        .footer {
            background: white;
            border-top: 1px solid var(--border);
            padding: 1rem 1.5rem;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.8rem;
            color: var(--gray);
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            color: var(--gray);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .top-header {
                flex-wrap: wrap;
                height: auto;
                padding: 0.75rem 1rem;
                gap: 0.75rem;
            }
            
            .header-left {
                width: 100%;
                justify-content: space-between;
            }
            
            .nav-menu {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 0.5rem;
            }
            
            .user-info {
                display: none;
            }
            
            .user-trigger {
                padding: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .content-area {
                padding: 1rem;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            
            .nav-link span {
                display: none;
            }
            
            .nav-link {
                padding: 0.5rem;
            }
            
            .nav-link i {
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .header-actions {
                gap: 0.25rem;
            }
            
            .header-action-btn {
                width: 35px;
                height: 35px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="app-wrapper">
        <!-- TOP HEADER WITH MENU -->
        <header class="top-header">
            <div class="header-left">
                <div class="brand-wrapper">
                    <div class="brand-icon">
                        <i class="ri-building-2-line"></i>
                    </div>
                    <span class="brand-text">Doctors Profile</span>
                </div>

                <!-- Navigation Menu -->
                <nav class="nav-menu">
                    <a href="{{ url('superadmin/dashboard') }}" class="nav-link {{ request()->is('superadmin/dashboard') ? 'active' : '' }}">
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ url('tenantadmin/templates') }}" class="nav-link">
                        <span>Templates</span>
                    </a>
                    <a href="#" class="nav-link">
                        <span>Support</span>
                    </a>
                </nav>
            </div>

            <div class="header-right">
                <div class="header-actions">
                    <button class="header-action-btn">
                        <i class="ri-mail-line"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <button class="header-action-btn">
                        <i class="ri-notification-3-line"></i>
                        <span class="notification-badge">5</span>
                    </button>
                    
                    <!-- User Dropdown with Logout -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-trigger" onclick="toggleUserMenu()">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
                            </div>
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                        <div class="dropdown-menu-custom" id="userMenu">
                            <div class="dropdown-header">
                                <div class="name">{{ Auth::user()->name ?? 'Admin' }}</div>
                                <div class="email">{{ Auth::user()->email ?? 'admin@example.com' }}</div>
                            </div>
                            <div class="dropdown-divider"></div>
                    <a href="/profile" class="dropdown-item">
                                <i class="ri-user-settings-line"></i>
                                <span>Profile Settings</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="ri-lock-password-line"></i>
                                <span>Change Password</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ri-logout-box-r-line"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- FLASH MESSAGES -->
        <div class="flash-container">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="ri-checkbox-circle-line fs-5"></i>
                <div class="alert-content">
                    <div class="alert-title">Success!</div>
                    <div class="alert-message">{{ session('success') }}</div>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                <i class="ri-error-warning-line fs-5"></i>
                <div class="alert-content">
                    <div class="alert-title">Error!</div>
                    <div class="alert-message">{{ session('error') }}</div>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            @endif
        </div>

        <!-- CONTENT AREA -->
        <div class="content-area">
            @yield('content')
        </div>

        <!-- FOOTER -->
        <footer class="footer">
            <div class="footer-content">
                <div>
                    <i class="ri-copyright-line"></i>
                    {{ date('Y') }} DoctorHub. All rights reserved.
                </div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // User Dropdown Toggle
        function toggleUserMenu() {
            const userMenu = document.getElementById('userMenu');
            userMenu.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userDropdown = document.getElementById('userDropdown');
            const userMenu = document.getElementById('userMenu');
            
            if (userDropdown && !userDropdown.contains(event.target)) {
                userMenu.classList.remove('show');
            }
        });

        // Auto-dismiss flash messages
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.transition = 'all 0.3s ease';
                    alert.style.transform = 'translateX(100%)';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 5000);
        });

        // Active link highlighting
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>