<?php $setting = \DB::table('settings')->first(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Doctor Panel</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="{{ url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">

    <!-- Summernote -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">

    <!-- Remix Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">

    @if (!empty($setting->site_logo))
        <link rel="icon" href="{{ asset($setting->site_logo) }}" type="image/x-icon">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Modern CSS -->
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
            --primary-blue-light: #dbeafe;
            --accent-teal: #0d9488;
            --accent-purple: #7c3aed;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --border-light: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-blue: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-light);
        }

        /* Modern Header */
        .main-header {
            background: var(--bg-white) !important;
            border-bottom: 1px solid var(--border-light) !important;
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(10px);
        }

        .navbar-light .navbar-nav .nav-link {
            color: var(--text-secondary);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: var(--primary-blue);
            background: var(--primary-blue-light);
            border-radius: 8px;
        }

        /* Modern Sidebar */
        .main-sidebar {
            background: var(--bg-white) !important;
            border-right: 1px solid var(--border-light) !important;
            box-shadow: var(--shadow-sm);
        }

        .sidebar-light-primary .brand-link {
            background: var(--gradient-blue) !important;
            border-bottom: none !important;
            border-right: none !important;
        }

        .sidebar-light-primary .brand-link .brand-text {
            color: white !important;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .brand-link img {
            border: 2px solid rgba(255,255,255,0.3) !important;
            box-shadow: var(--shadow-sm);
        }

        /* Modern Navigation */
        .nav-sidebar > .nav-item > .nav-link {
            margin: 0.25rem 0.75rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .nav-sidebar > .nav-item > .nav-link:hover {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            transform: translateX(5px);
        }

        .nav-sidebar > .nav-item > .nav-link.active {
            background: var(--primary-blue) !important;
            color: white !important;
            box-shadow: var(--shadow-md);
        }

        .nav-sidebar .nav-icon {
            color: inherit !important;
            transition: all 0.3s ease;
        }

        /* Content Wrapper */
        .content-wrapper {
            background: var(--bg-light) !important;
        }

        /* Modern Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            background: var(--bg-white);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--bg-white) !important;
            border-bottom: 1px solid var(--border-light) !important;
            border-radius: 16px 16px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.25rem;
        }

        /* Modern Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: var(--gradient-blue);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .btn-info {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
        }

        /* Modern Dropdown */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
        }

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 0.125rem 0.5rem;
            width: auto;
        }

        .dropdown-item:hover {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
        }

        /* User Dropdown */
        .user-dropdown-header {
            background: var(--gradient-blue);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        /* Modern Footer */
        .main-footer {
            background: var(--bg-white) !important;
            border-top: 1px solid var(--border-light) !important;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* DataTables Modernization */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--text-secondary) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-blue) !important;
            color: white !important;
            border: none;
        }

        /* Preloader */
        .preloader {
            background: var(--bg-light);
        }

        .preloader img {
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
        }

        /* Status Badges */
        .badge {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Improvements */
        @media (max-width: 768px) {
            .main-sidebar {
                transform: translateX(-100%);
            }

            .sidebar-open .main-sidebar {
                transform: translateX(0);
            }

            .card {
                margin: 0.5rem;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-light: #0f172a;
                --bg-white: #1e293b;
                --text-primary: #f1f5f9;
                --text-secondary: #cbd5e1;
                --border-light: #334155;
            }
        }
    </style>
</head>

<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        @if (!empty($setting->site_logo))
            <!-- Modern Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <div class="text-center">
                    <img class="animation__wobble mb-4" src="{{ url(@$setting->site_logo) }}" alt="Logo" height="80" width="80">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading Doctor Panel...</p>
                </div>
            </div>
        @endif

        <!-- Modern Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/" class="nav-link" target="_blank">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        View Website
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Bell -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">3 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-calendar-check mr-2 text-success"></i>
                            New appointment request
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-comment-medical mr-2 text-info"></i>
                            Patient message
                            <span class="float-right text-muted text-sm">1 hour</span>
                        </a>
                    </div>
                </li>

                <!-- Fullscreen Toggle -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" href="#">
                        <div class="d-none d-md-flex flex-column text-right mr-2">
                            <span class="font-weight-bold text-dark">{{ Auth::user()->name }}</span>
                            <small class="text-muted">
                                @switch(Auth::user()->role)
                                    @case('admin')
                                        <i class="fas fa-shield-alt mr-1"></i>Administrator
                                    @break
                                    @case('editor')
                                        <i class="fas fa-edit mr-1"></i>Editor
                                    @break
                                    @default
                                        <i class="fas fa-user mr-1"></i>User
                                @endswitch
                            </small>
                        </div>
                        @if (!empty($setting->site_logo))
                            <img src="{{ url(@$setting->site_logo) }}" class="img-circle" width="40" height="40" alt="User Avatar">
                        @else
                            <div class="img-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-user-md"></i>
                            </div>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow-lg border-0">
                        <!-- User Info -->
                        <div class="user-dropdown-header px-4 py-3">
                            <div class="d-flex align-items-center">
                                @if (!empty($setting->site_logo))
                                    <img src="{{ url(@$setting->site_logo) }}" class="img-circle mr-3" width="50" height="50" alt="User">
                                @endif
                                <div>
                                    <h6 class="mb-0 font-weight-bold">{{ Auth::user()->name }}</h6>
                                    <small>
                                        @switch(Auth::user()->role)
                                            @case('admin')
                                                <i class="fas fa-shield-alt mr-1"></i>Administrator
                                            @break
                                            @case('editor')
                                                <i class="fas fa-edit mr-1"></i>Editor
                                            @break
                                            @default
                                                <i class="fas fa-user mr-1"></i>User
                                        @endswitch
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item d-flex align-items-center py-2">
                                <i class="fas fa-user-cog mr-3 text-primary"></i>
                                <span>Doctor Profile</span>
                            </a>

                            <a href="#" class="dropdown-item d-flex align-items-center py-2">
                                <i class="fas fa-cog mr-3 text-secondary"></i>
                                <span>Settings</span>
                            </a>

                            <div class="dropdown-divider my-1"></div>

                            <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Modern Sidebar -->
        <aside class="main-sidebar sidebar-light-primary">
            <!-- Brand Logo -->
            <a href="{{ url('admin/dashboard') }}" class="brand-link text-center">
                @if (!empty($setting->site_logo))
                    <img src="{{ url(@$setting->site_logo) }}" alt="Logo" class="brand-image">
                @endif
                <span class="brand-text font-weight-bold">DOCTOR PANEL</span>
            </a>

            <!-- Sidebar Menu -->
            <div class="sidebar">
                <nav class="mt-4">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url('admin/chambers') }}"
                               class="nav-link {{ request()->is('admin/chambers') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clinic-medical"></i>
                                <p>Clinic / Chamber</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('admin/appointments') }}"
                               class="nav-link {{ request()->is('admin/appointments') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>Appointments</p>
                                <span class="badge badge-info right">5</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('admin/posts') }}"
                               class="nav-link {{ request()->is('admin/posts') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>Health Articles</p>
                            </a>
                        </li>

                        <!-- Additional Menu Items -->
                        {{-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Patients</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Analytics</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Messages</p>
                                <span class="badge badge-success right">12</span>
                            </a>
                        </li> --}}
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Modern Footer -->
        <footer class="main-footer">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ @$setting->site_name }}</a>.</strong>
                        All rights reserved.
                    </div>
                    <div class="col-sm-6 text-right">
                        Developed by <a href="https://trustitbdltd.com/" target="_blank" class="text-primary">Trust-IT (BD) Ltd</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('dist/js/adminlte.js') }}"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>

    <!-- Summernote -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#datatable').DataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records..."
                }
            });

            // Initialize Summernote
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });

        // SweetAlert Notifications
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb',
                background: '#ffffff',
                iconColor: '#10b981'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: '{{ session('warning') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#f59e0b'
            });
        @endif

        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Information',
                text: '{{ session('info') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif

        // Add fade-in animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });
        });
    </script>
</body>
</html>
