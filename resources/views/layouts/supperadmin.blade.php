<!DOCTYPE html>
<html lang="en">
<?php
$settingModel = \App\Models\CompanySetting::first();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- icon link --}}
    <link rel="icon" href="{{ url($settingModel->favicon) }}" type="image/x-icon" />
    <style>
        :root {
            --primary: #318069;
            --primary-light: rgba(49, 128, 105, 0.1);
            --primary-dark: #276854;
            --secondary: #FFC107;
            --accent: #7E57C2;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #3B82F6;
            --light: #F9FAFB;
            --dark: #1F2937;
            --text-light: #6B7280;
            --text-dark: #111827;
            --glass-bg: rgba(255, 255, 255, 0.98);
            --glass-border: rgba(255, 255, 255, 0.15);
            --sidebar-width: 240px;
            --sidebar-collapsed: 65px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-subtle: 0 1px 3px rgba(0, 0, 0, 0.04);
            --shadow-medium: 0 2px 8px rgba(0, 0, 0, 0.06);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
            color: inherit
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: var(--dark);
            min-height: 100vh;
            overflow-x: hidden;
            font-size: 0.875rem;
        }

        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-bottom: 0px;
        }


        .act-btn {
            height: 32px;
            width: 32px;
            background-color: #fff;
            border: 1px solid #c3c3c39c;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
        }

        .act-btn:focus {
            outline: 0;
        }

        .act-btn.feature-btn {
            border: 1px solid green;
            background-color: #00800024;
            color: green;
        }

        .act-btn.btn-warning {
            background-color: green;
            color: #fff;
        }

        .act-btn.feature-btn:hover {
            background-color: #00800042;
        }

        .act-btn.delete-btn {
            border: 1px solid #dc3545;
            background-color: #dc35451f;
            color: #dc3545;
        }

        .act-btn.delete-btn:hover {
            background-color: #dc35453d;
        }

        .act-btn.edit-btn {
            border: 1px solid #ffc107;
            background-color: #ffc1070f;
            color: #e8b213;
        }

        .act-btn.edit-btn:hover {
            background-color: #ffc1072e;
        }

        .act-btn.upload-btn {
            border: 1px solid #007bff;
            background-color: #007bff21;
            color: #007bff;
        }

        .act-btn.upload-btn:hover {
            background-color: #007bff3d;
        }



        /* Compact Layout */
        .layout-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            background: #f5f7fa;
        }

        /* Compact Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid #e5e7eb;
            box-shadow: var(--shadow-subtle);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        /* Sidebar Header - Compact */
        .sidebar-header {
            padding: 10px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            background: white;
            min-height: 65px;
        }

        .brand img {
            max-width: 175px;
        }

        .brand-text {
            flex: 1;
            overflow: hidden;
        }

        .brand-text h1 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
            line-height: 1.2;
            white-space: nowrap;
        }

        .brand-text p {
            font-size: 0.75rem;
            color: var(--text-light);
            margin: 0;
            font-weight: 500;
            white-space: nowrap;
        }

        .toggle-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid #e5e7eb;
            background: white;
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .toggle-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        /* Compact Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 1.25rem;
        }

        .section-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 0.75rem 0.5rem;
            margin-bottom: 0.25rem;
            transition: var(--transition);
        }

        .sidebar.collapsed .section-label {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }

        .nav-list {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            position: relative;
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.85rem;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 1px 3px rgba(49, 128, 105, 0.15);
        }

        .nav-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            color: inherit;
            flex-shrink: 0;
        }

        .nav-text {
            flex: 1;
            font-size: 0.85rem;
            transition: var(--transition);
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .nav-badge {
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 10px;
            font-weight: 600;
            min-width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        /* Sidebar Footer - Compact */
        .sidebar-footer {
            padding: 0.875rem;
            border-top: 1px solid #e5e7eb;
            background: white;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            transition: var(--transition);
            overflow: hidden;
        }

        .user-info h4 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info p {
            font-size: 0.75rem;
            color: var(--text-light);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
        }

        .logout-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid #e5e7eb;
            background: white;
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .logout-btn:hover {
            border-color: var(--danger);
            color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
        }

        /* Main Content - Compact */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f5f7fa;
        }

        .sidebar.collapsed~.main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Compact Top Bar */
        .top-bar {
            background: white;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            min-height: 60px;
        }

        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            color: var(--primary);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            background: var(--primary-light);
        }

        .page-title {
            flex: 1;
            min-width: 0;
        }

        .page-title h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .page-title h1::before {
            content: '';
            width: 3px;
            height: 20px;
            background: var(--primary);
            border-radius: 1.5px;
            flex-shrink: 0;
        }

        .page-title p {
            font-size: 0.85rem;
            color: var(--text-light);
            margin: 0.125rem 0 0 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }

        .search-box {
            position: relative;
            flex-shrink: 1;
        }

        .search-input {
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-md);
            background: white;
            color: var(--dark);
            font-size: 0.85rem;
            width: 220px;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(49, 128, 105, 0.1);
            width: 260px;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1rem;
        }

        .notification-btn {
            position: relative;
            background: transparent;
            border: none;
            color: var(--dark);
            font-size: 1.1rem;
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
        }

        .notification-btn:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Compact Quick Action */
        .quick-action-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 0.625rem 1rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .quick-action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(49, 128, 105, 0.2);
        }

        /* Content Wrapper - Compact */
        .content-wrapper {
            flex: 1;
            padding: 1.25rem;
        }

        /* Compact Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .dashboard-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: var(--shadow-subtle);
            border: 1px solid #e5e7eb;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .card-icon.primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        .card-icon.success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        }

        .card-icon.warning {
            background: linear-gradient(135deg, var(--warning) 0%, #D97706 100%);
        }

        .card-icon.danger {
            background: linear-gradient(135deg, var(--danger) 0%, #DC2626 100%);
        }

        .card-title {
            font-size: 0.8rem;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0.5rem 0;
            line-height: 1;
        }

        .card-change {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .card-change.positive {
            color: var(--success);
        }

        .card-change.negative {
            color: var(--danger);
        }

        /* Compact Alerts */
        .alert {
            border-radius: var(--radius-md);
            border: none;
            box-shadow: var(--shadow-subtle);
            padding: 0.875rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-left: 3px solid var(--success);
            color: var(--dark);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-left: 3px solid var(--danger);
            color: var(--dark);
        }

        .alert .btn-close {
            padding: 0.75rem;
        }

        /* Compact Footer */
        .footer {
            background: white;
            padding: 1rem 1.5rem;
            text-align: center;
            color: var(--text-light);
            font-size: 0.8rem;
            border-top: 1px solid #e5e7eb;
            margin-top: auto;
        }

        /* Mobile Responsive */
        @media (max-width: 1200px) {
            .search-input:focus {
                width: 240px;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .mobile-menu-btn {
                display: block;
            }

            .search-input {
                width: 180px;
            }

            .search-input:focus {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .top-bar {
                padding: 0.75rem 1rem;
                flex-wrap: wrap;
                justify-content: space-between;
                gap: 1rem;
            }

            .top-bar-actions {
                width: 100%;
                justify-content: space-between;
            }

            .search-box {
                flex: 1;
                min-width: 200px;
            }

            .search-input {
                width: 100%;
            }

            .content-wrapper {
                padding: 1rem;
            }

            .dashboard-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .dashboard-card {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .page-title h1 {
                font-size: 1.25rem;
            }
        }

        /* Sidebar Backdrop for Mobile */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 999;
        }

        .sidebar-backdrop.active {
            display: block;
        }

        /* Dropdown Menu - Compact */
        .dropdown-menu {
            border: 1px solid #e5e7eb;
            box-shadow: var(--shadow-medium);
            border-radius: var(--radius-md);
            padding: 0.5rem 0;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.625rem 1rem;
            font-size: 0.85rem;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .dropdown-item i {
            width: 18px;
            text-align: center;
            margin-right: 0.5rem;
        }

        /* Custom Scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(49, 128, 105, 0.2);
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(49, 128, 105, 0.3);
        }

        /* Data Tables & Forms - Compact */
        .table th {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            padding: 0.75rem 1rem;
            border-bottom: 2px solid #e5e7eb;
            background: #f9fafb;
        }

        .table td {
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-control,
        .form-select {
            font-size: 0.85rem;
            padding: 0.625rem 0.875rem;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-md);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(49, 128, 105, 0.1);
        }

        .btn {
            font-size: 0.85rem;
            padding: 0.625rem 1rem;
            border-radius: var(--radius-md);
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(49, 128, 105, 0.2);
        }
    </style>
</head>

<body>
    <div class="layout-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="brand d-flex">
                    <img alt="Doctor Directory Logo" class="h-12 w-auto object-contain"
                        src="{{ url('images/logo.png') }}">
                </div>
                <!-- <button class="toggle-btn" id="sidebarToggle">
                    <i class="ri-arrow-left-s-line"></i>
                </button> -->
            </div>

           <nav class="sidebar-nav">

    {{-- Super Admin --}}
    <div class="nav-section">
        <div class="section-label">Super Admin</div>
        <ul class="nav-list">

            <li class="nav-item">
                <a href="{{ url('superadmin/dashboard') }}"
                   class="nav-link {{ request()->is('superadmin/dashboard') ? 'active' : '' }}">
                    <i class="ri-dashboard-line nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.index') }}"
                   class="nav-link {{ request()->is('users') || request()->is('users/*') ? 'active' : '' }}">
                    <i class="ri-user-2-line nav-icon"></i>
                    <span class="nav-text">Doctor List</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('packages') }}"
                   class="nav-link {{ request()->is('packages*') ? 'active' : '' }}">
                    <i class="ri-box-3-line nav-icon"></i>
                    <span class="nav-text">Packages</span>
                </a>
            </li>

{{-- coupons --}}
            <li class="nav-item">
                <a href="{{ url('coupons') }}"
                   class="nav-link {{ request()->is('coupons*') ? 'active' : '' }}">
                    <i class="ri-price-tag-3-line nav-icon"></i>
                    <span class="nav-text">Coupons</span>
                </a>
            </li>

        </ul>
    </div>
    {{-- article posts --}}
    <div class="nav-section">
        <div class="section-label">Articles & Posts</div>
        <ul class="nav-list">

            <li class="nav-item">
                <a href="{{ url('posts') }}"
                   class="nav-link {{ request()->is('posts*') ? 'active' : '' }}">
                    <i class="ri-file-text-line nav-icon"></i>
                    <span class="nav-text">Articles</span>
                </a>
            </li>
            {{-- types of articles --}}
            <li class="nav-item">
                <a href="{{ url('post-types') }}"
                   class="nav-link {{ request()->is('post-types*') ? 'active' : '' }}">
                    <i class="ri-chat-1-line nav-icon"></i>
                    <span class="nav-text">Types</span>
                </a>
            </li>
            {{-- category of articles --}}
            <li class="nav-item">
                <a href="{{ url('categories') }}"
                   class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                    <i class="ri-folder-2-line nav-icon"></i>
                    <span class="nav-text">Categories</span>
                </a>
            </li>

        </ul>
    </div>

    {{-- Management --}}
    <div class="nav-section">
        <div class="section-label">Management</div>
        <ul class="nav-list">

            {{-- Marketing --}}
            <li class="nav-item dropdown
                {{ request()->is('superadmin/marketing/*') ? 'active' : '' }}">

                <a href="javascript:void(0)"
                   class="nav-link dropdown-toggle
                   {{ request()->is('superadmin/marketing/*') ? 'active' : '' }}"
                   data-bs-toggle="dropdown">
                    <i class="ri-megaphone-line nav-icon"></i>
                    <span class="nav-text">Marketing</span>
                </a>

                <ul class="dropdown-menu">

                    <li>
                        <a href="{{ route('superadmin.marketing.contacts.index') }}"
                           class="dropdown-item {{ request()->is('superadmin/marketing/contacts*') ? 'active' : '' }}">
                            Audience Contacts
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('superadmin.marketing.templates.index') }}"
                           class="dropdown-item {{ request()->is('superadmin/marketing/templates*') ? 'active' : '' }}">
                            Message Templates
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('superadmin.marketing.segments.index') }}"
                           class="dropdown-item {{ request()->is('superadmin/marketing/segments*') ? 'active' : '' }}">
                            Audience Segments
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('superadmin.marketing.campaigns.index') }}"
                           class="dropdown-item {{ request()->is('superadmin/marketing/campaigns*') ? 'active' : '' }}">
                            Campaign Management
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ url('superadmin/leads') }}"
                   class="nav-link {{ request()->is('superadmin/leads*') ? 'active' : '' }}">
                    <i class="ri-user-search-line nav-icon"></i>
                    <span class="nav-text">Leads</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('superadmin/specialties') }}"
                   class="nav-link {{ request()->is('superadmin/specialties*') ? 'active' : '' }}">
                    <i class="ri-stethoscope-line nav-icon"></i>
                    <span class="nav-text">Specialties</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('superadmin/settings/company') }}"
                   class="nav-link {{ request()->is('superadmin/settings*') ? 'active' : '' }}">
                    <i class="ri-settings-3-line nav-icon"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('about') }}" target="_blank"
                   class="nav-link">
                    <i class="ri-information-line nav-icon"></i>
                    <span class="nav-text">About Page</span>
                </a>
            </li>
            {{-- superadmin/templates set --}}
            <li>
                <a href="{{ url('superadmin/templates') }}"
                   class="nav-link {{ request()->is('superadmin/templates*') ? 'active' : '' }}">
                    <i class="ri-file-code-line nav-icon"></i>
                    <span class="nav-text">Templates Set</span>
                </a>
            </li>
            {{-- company-incomes --}}
 <li>
    <a href="{{ url('superadmin/company-incomes') }}"
       class="nav-link {{ request()->is('superadmin/company-incomes*') ? 'active' : '' }}">
        <i class="ri-building-4-line nav-icon"></i>
        <span class="nav-text">Company Incomes</span>
    </a>
</li>

{{-- subscriptions --}}
<li>
    <a href="{{ url('superadmin/subscriptions') }}"
       class="nav-link {{ request()->is('superadmin/subscriptions*') ? 'active' : '' }}">
        <i class="ri-coin-line nav-icon"></i>
        <span class="nav-text">Subscriptions</span>
    </a>
</li>




        </ul>
    </div>
</nav>


            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h4>{{ auth()->user()->name }}</h4>
                        <p>{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <a href="{{ route('admin.logout') }}" class="logout-btn"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-logout-box-r-line"></i>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Backdrop -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="brand d-flex d-md-none">
                    <img alt="Doctor Directory Logo" class="h-12 w-auto object-contain"
                        src="{{ url('images/logo.png') }}">
                </div>
                
                

                <div class="page-title d-none d-md-flex">
                    <h1>@yield('page-title', 'Super Admin')</h1>
                    @hasSection('page-subtitle')
                        <p>@yield('page-subtitle')</p>
                    @endif
                </div>

                <div class="top-bar-actions d-none d-md-flex">
                    <div class="search-box">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="search-input" placeholder="Search...">
                    </div>

                    {{-- <button class="notification-btn">
                        <i class="ri-notification-3-line"></i>
                        <span class="notification-badge">3</span>
                    </button> --}}

                    <button class="quick-action-btn" data-bs-toggle="dropdown">
                        <i class="ri-add-line"></i>
                        Quick Action
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ url('doctor/create') }}" target="_blank">
                                <i class="ri-user-add-line me-2"></i>Add Doctor</a></li>
                        <li>
                            <a class="dropdown-item" href="{{ url('packages/create') }}">
                                <i class="ri-box-3-line me-2"></i>Create Package</a></li>
                        <li>
                            <a class="dropdown-item" href="{{ url('superadmin/settings/company') }}">
                                <i class="ri-settings-3-line me-2"></i>Update Settings</a></li>
                        <li>
                            <a class="dropdown-item" href="{{ url('superadmin/templates') }}">
                                <i class="ri-file-code-line me-2"></i>Manage Templates</a></li>
                        <li>
                            <a class="dropdown-item" href="{{ url('superadmin/specialties') }}">
                                <i class="ri-stethoscope-line me-2"></i>Update Specialties</a></li>
                    </ul>
                </div>

                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="ri-menu-line"></i>
                </button>
            </div>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill me-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ri-error-warning-fill me-2"></i>
                            <div>{{ session('error') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                <!-- Dashboard Stats -->
                @hasSection('dashboard-stats')
                    <div class="dashboard-cards">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <div class="card-icon primary">
                                    <i class="ri-user-2-line"></i>
                                </div>
                                <span class="card-title">Total Doctors</span>
                            </div>
                            <div class="card-value">1,234</div>
                            <div class="card-change positive">
                                <i class="ri-arrow-up-line"></i> 12%
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <div class="card-icon success">
                                    <i class="ri-calendar-check-line"></i>
                                </div>
                                <span class="card-title">Appointments</span>
                            </div>
                            <div class="card-value">345</div>
                            <div class="card-change positive">
                                <i class="ri-arrow-up-line"></i> 8%
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <div class="card-icon warning">
                                    <i class="ri-time-line"></i>
                                </div>
                                <span class="card-title">Reviews</span>
                            </div>
                            <div class="card-value">23</div>
                            <div class="card-change negative">
                                <i class="ri-arrow-down-line"></i> 3%
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <div class="card-icon danger">
                                    <i class="ri-alert-line"></i>
                                </div>
                                <span class="card-title">Tickets</span>
                            </div>
                            <div class="card-value">15</div>
                            <div class="card-change positive">
                                <i class="ri-arrow-down-line"></i> 5%
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="footer">
                <p>© {{ date('Y') }} DoctorHub | v2.0.0</p>
            </footer>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Auto-calculate yearly price
            $('#price_monthly').on('input', function() {
                const monthly = parseFloat($(this).val());
                if (!isNaN(monthly) && monthly > 0 && $('#price_yearly').val() === '') {
                    $('#price_yearly').val((monthly * 12).toFixed(2));
                }
            });

            // Clicking on toggle label now works because we're using proper label with "for" attribute
            // The toggle switch fix is in the HTML structure
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            // Toggle sidebar collapse/expand
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    const icon = this.querySelector('i');
                    if (!icon) return;

                    if (sidebar.classList.contains('collapsed')) {
                        icon.classList.remove('ri-arrow-left-s-line');
                        icon.classList.add('ri-arrow-right-s-line');
                    } else {
                        icon.classList.remove('ri-arrow-right-s-line');
                        icon.classList.add('ri-arrow-left-s-line');
                    }
                });
            }

            // Mobile menu toggle
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.add('mobile-open');
                sidebarBackdrop.classList.add('active');
            });

            // Close mobile menu
            sidebarBackdrop.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                this.classList.remove('active');
            });

            // Set active nav link
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Search functionality
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const query = this.value.trim();
                        if (query) {
                            window.location.href = `{{ url('superadmin/dashboard') }}?q=${encodeURIComponent(query)}`;
                        }
                    }
                });
            }

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992 && !sidebar.contains(e.target) &&
                    !mobileMenuBtn.contains(e.target) && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    sidebarBackdrop.classList.remove('active');
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            if (window.innerWidth > 992) {
                sidebar.classList.remove('mobile-open');
                sidebarBackdrop.classList.remove('active');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
