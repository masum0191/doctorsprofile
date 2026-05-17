<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Doctor Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- favicon --}}
    <link rel="icon" type="image/png" href="{{ url(auth()->user()->photo) }}">

    <style>
        /* ===== Sidebar Active Color Override ===== */
        /* Sidebar active color */
        .nav-link.active,
        .dropdown-item.active {
            background-color: #318069 !important;
            color: #ffffff !important;
        }

        .nav-link.active .nav-icon,
        .dropdown-item.active i {
            color: #ffffff !important;
        }

        /* Open dropdown parent */
        .nav-item.has-dropdown.active>.nav-link {
            background-color: #318069 !important;
            color: white !important;
        }

        .nav-item.has-dropdown.active>.nav-link .nav-icon {
            color: white !important;
        }

        .nav-item.has-dropdown.active>.nav-link .dropdown-icon {
            color: white !important;
        }

        /* Keep dropdown open when parent is active */
        .nav-item.has-dropdown.active .dropdown-menu {
            display: block !important;
        }

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

        .text-primary {
            color: var(--primary) !important;
        }

        .border-primary {
            border-color: var(--primary) !important;
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

        .brand img {
            max-width: 175px;
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

        .brand-img img {
            max-width: 175px;
        }
        .mob-logo{
            width: auto;
            height: 30px; 
            object-fit: contain;
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
            overflow: hidden;
        }

        .sidebar.collapsed~.main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Compact Top Bar */
        .top-bar {
            background: white;
            padding: 0.70rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: end;
            gap: 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            min-height: 60px;
        }

        .mobile-menu-btn {
            display: none;
            background: #f3f3f3;
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

        .header-search-input {
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-md);
            background: white;
            color: var(--dark);
            font-size: 0.85rem;
            width: 220px;
            transition: var(--transition);
        }

        .header-search-input:focus {
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

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            background: transparent;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-md);
            padding: 0.375rem 0.55rem 0.375rem 0.75rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .profile-btn:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .profile-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-light);
        }

        .profile-name {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--dark);
        }

        .profile-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-medium);
            min-width: 200px;
            z-index: 1000;
            display: none;
        }

        .profile-dropdown-menu.show {
            display: block;
        }

        .profile-dropdown-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-dropdown-header h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0;
        }

        .profile-dropdown-header p {
            font-size: 0.75rem;
            color: var(--text-light);
            margin: 0.25rem 0 0 0;
        }

        .profile-dropdown-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .profile-dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .profile-dropdown-item i {
            width: 20px;
            font-size: 1rem;
        }

        .profile-dropdown-divider {
            height: 1px;
            background: #e5e7eb;
        }

        /* Simple Dropdown Styles */
        .sidebar .has-dropdown {
            position: relative;
        }

        .sidebar .dropdown-menu {
            display: none;
            padding: 0.5rem 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }

        .sidebar .has-dropdown.active .dropdown-menu {
            display: block;
        }

        .sidebar .dropdown-item {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            color: var(--text-light);
            font-size: 0.85rem;
            text-decoration: none;
            display: block;
            transition: var(--transition);
            position: relative;
        }

        .sidebar .dropdown-item:hover {
            color: var(--primary);
            background: var(--primary-light);
        }

        .sidebar .dropdown-item i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .sidebar .dropdown-icon {
            transition: transform 0.2s ease;
            margin-left: auto;
        }

        .sidebar .has-dropdown.active .dropdown-icon {
            transform: rotate(180deg);
        }

        /* Active dropdown item */
        .sidebar .dropdown-item.active {
            background-color: #318069 !important;
            color: white !important;
        }

        .sidebar .dropdown-item.active i {
            color: white !important;
        }

        /* Collapsed sidebar dropdown */
        .sidebar.collapsed .dropdown-menu {
            position: fixed;
            left: var(--sidebar-collapsed);
            top: auto;
            margin: 0;
            width: 200px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-medium);
        }

        .quick-actions {
            display: flex;
            gap: 0.75rem;
        }


        /* Modal Enhancement */
        .primary-modal .modal-content {
            border-radius: 12px;
            border: 1px solid rgba(49, 128, 105, 0.15);
            box-shadow: 0 10px 30px rgba(49, 128, 105, 0.15);
        }

        .primary-modal .modal-header {
            background: var(--primary);
            color: white;
            border-radius: 12px 12px 0 0;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .primary-modal .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .primary-modal .modal-body {
            padding: 1.5rem;
        }

        .primary-modal .modal-footer {
            border-top: 1px solid var(--primary-light);
            padding: 1.25rem 1.5rem;
            background: var(--primary-soft);
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
            .header-search-input:focus {
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

            .header-search-input {
                width: 180px;
            }

            .header-search-input:focus {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .top-bar {
                padding: 0.75rem 1rem;
                justify-content: space-between;
                gap: 1rem;
            }

            

            .search-box {
                flex: 1;
                min-width: 200px;
            }

            .header-search-input {
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

            .profile-name {
                display: none;
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
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .dropdown-item i {
            width: 18px;
            text-align: center;
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
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            background: var(--primary);
            box-shadow: 0 2px 8px rgba(49, 128, 105, 0.2);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .booking-modal {
    border-radius: 12px;
    overflow: hidden;
}

.booking-header {
    background: linear-gradient(135deg, #007a70, #009688);
    color: #fff;
}

.booking-header .btn-close {
    filter: invert(1);
}

.form-card {
    background: #f9fafb;
    padding: 14px;
    border-radius: 10px;
    border: 1px solid #eee;
}

.booking-footer {
    background: #f8f9fa;
}

.form-control, .form-select {
    border-radius: 8px;
}

    </style>
</head>

<body>
    @php
        $currentTenant = function_exists('tenant') ? tenant() : null;
        $currentPackage = null;
        $activeSubscription = null;

        if ($currentTenant) {
            $activeSubscription = \App\Models\Subscription::where('tenant_id', tenant('id'))
                ->where('status', 'active')
                ->where('ends_at', '>', now())
                ->with('package')
                ->latest()
                ->first();
        }

        if ($activeSubscription && $activeSubscription->package) {
            $currentPackage = $activeSubscription->package;
        } elseif ($currentTenant && (data_get($currentTenant, 'package_id') || data_get($currentTenant, 'data.package_id'))) {
            $currentPackage = \App\Models\Package::find(data_get($currentTenant, 'package_id') ?: data_get($currentTenant, 'data.package_id'));
        } elseif (auth()->check() && (data_get(auth()->user(), 'package_id') || data_get(auth()->user(), 'package'))) {
            $currentPackage = \App\Models\Package::find(data_get(auth()->user(), 'package_id') ?: data_get(auth()->user(), 'package'));
        }

        $packageFeatures = $currentPackage && method_exists($currentPackage, 'featureMap')
            ? $currentPackage->featureMap()
            : config('package_features.presets.free', []);

        $can = fn (string $feature): bool => (bool) ($packageFeatures[$feature] ?? false);
    @endphp

    <div class="layout-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="brand-img d-flex">
                    <img alt="Doctor Directory Logo" class="h-12 w-auto object-contain"
                        src="{{ auth()->user()->photo ? url(auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=318069&color=fff' }}" height="50">
                </div>
                <button class="toggle-btn d-none" id="sidebarToggle">
                    <i class="ri-arrow-left-s-line"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                @if($can('doctor'))
                    <div class="nav-section">
                        <div class="section-label">Doctor</div>
                        <ul class="nav-list">
                            <li class="nav-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                                    <i class="ri-dashboard-line nav-icon"></i>
                                    <span class="nav-text">Dashboard</span>
                                </a>
                            </li>
                            @if($can('profile_basic') || $can('profile_professional'))
                            <li class="nav-item {{ request()->is('admin/profile*') ? 'active' : '' }}">
                                <a href="{{ route('admin.profile.edit') }}"
                                    class="nav-link {{ request()->is('admin/profile*') ? 'active' : '' }}">
                                    <i class="ri-user-2-line nav-icon"></i>
                                    <span class="nav-text">Profile</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                @endif

                @if($can('appointments'))
                    <div class="nav-section">
                        <div class="section-label">Appointments</div>
                        <ul class="nav-list">
                            <li
                                class="nav-item {{ request()->is('admin/appointments*') && !request()->is('admin/appointments-calendar*') && !request()->is('admin/appointment/online*') ? 'active' : '' }}">
                                <a href="{{ url('admin/appointments') }}"
                                    class="nav-link {{ request()->is('admin/appointments*') && !request()->is('admin/appointments-calendar*') && !request()->is('admin/appointment/online*') ? 'active' : '' }}">
                                    <i class="ri-calendar-line nav-icon"></i>
                                    <span class="nav-text">Appointments</span>
                                </a>
                            </li>
                            @if($can('appointment_booking'))
                            <li class="nav-item {{ request()->is('admin/appointment/online*') ? 'active' : '' }}">
                                <a href="{{ route('admin.appointment.online') }}"
                                    class="nav-link {{ request()->is('admin/appointment/online*') ? 'active' : '' }}">
                                    <i class="ri-computer-line nav-icon"></i>
                                    <span class="nav-text">Online Booking</span>
                                </a>
                            </li>
                            @endif
                            <li class="nav-item {{ request()->is('admin/appointments-calendar*') ? 'active' : '' }}">
                                <a href="{{ url('admin/appointments-calendar') }}"
                                    class="nav-link {{ request()->is('admin/appointments-calendar*') ? 'active' : '' }}">
                                    <i class="ri-calendar-line nav-icon"></i>
                                    <span class="nav-text">Calendar</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                @if($can('patients'))
                    <div class="nav-section">
                        <div class="section-label">Patients</div>
                        <ul class="nav-list">
                            <li
                                class="nav-item {{ request()->is('admin/patients*') && !request()->is('admin/patients/prescriptions*') ? 'active' : '' }}">
                                <a href="{{ route('admin.patients.index') }}"
                                    class="nav-link {{ request()->is('admin/patients*') && !request()->is('admin/patients/prescriptions*') ? 'active' : '' }}">
                                    <i class="ri-user-heart-line nav-icon"></i>
                                    <span class="nav-text">Patients</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('admin/patients/prescriptions*') ? 'active' : '' }}">
                                <a href="{{ route('admin.patients.prescriptions.index') }}"
                                    class="nav-link {{ request()->is('admin/patients/prescriptions*') ? 'active' : '' }}">
                                    <i class="ri-file-list-3-line nav-icon"></i>
                                    <span class="nav-text">Prescriptions</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('admin/messages*') ? 'active' : '' }}">
                                <a href="{{ url('admin/messages') }}"
                                    class="nav-link {{ request()->is('admin/messages*') ? 'active' : '' }}">
                                    <i class="ri-message-3-line nav-icon"></i>
                                    <span class="nav-text">Messages</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                @if($can('services') || $can('appointment_booking') || $can('online_payments') || $can('analytics_basic') || $can('analytics_advanced'))
                    <div class="nav-section">
                        <div class="section-label">Services</div>
                        <ul class="nav-list">
                            @if($can('services'))
                            <li class="nav-item {{ request()->is('admin/chambers*') ? 'active' : '' }}">
                                <a href="{{ url('admin/chambers') }}"
                                    class="nav-link {{ request()->is('admin/chambers*') ? 'active' : '' }}">
                                    <i class="ri-hospital-line nav-icon"></i>
                                    <span class="nav-text">Clinics</span>
                                </a>
                            </li>
                            @endif
                            @if($can('appointment_booking'))
                            <li class="nav-item {{ request()->is('admin/settings/online-schedule*') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.online-schedule') }}"
                                    class="nav-link {{ request()->is('admin/settings/online-schedule*') ? 'active' : '' }}">
                                    <i class="ri-calendar-check-line nav-icon"></i>
                                    <span class="nav-text">Online Schedule</span>
                                </a>
                            </li>
                            @endif
                            @if($can('services'))
                            <li class="nav-item {{ request()->is('admin/telemedicine*') ? 'active' : '' }}">
                                <a href="{{ route('admin.telemedicine.index') }}"
                                    class="nav-link {{ request()->is('admin/telemedicine*') ? 'active' : '' }}">
                                    <i class="ri-video-chat-line nav-icon"></i>
                                    <span class="nav-text">Telemedicine</span>
                                </a>
                            </li>
                            @endif
                            @if($can('online_payments'))
                            <li class="nav-item {{ request()->is('admin/billing*') ? 'active' : '' }}">
                                <a href="{{ route('admin.billing.index') }}"
                                    class="nav-link {{ request()->is('admin/billing*') ? 'active' : '' }}">
                                    <i class="ri-bill-line nav-icon"></i>
                                    <span class="nav-text">Billing</span>
                                </a>
                            </li>
                            @endif
                            @if($can('analytics_basic') || $can('analytics_advanced'))
                            <li class="nav-item {{ request()->is('admin/billing/report*') ? 'active' : '' }}">
                                <a href="{{ route('admin.billing.report') }}"
                                    class="nav-link {{ request()->is('admin/billing/report*') ? 'active' : '' }}">
                                    <i class="ri-bar-chart-2-line nav-icon"></i>
                                    <span class="nav-text">Reports</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                @endif

                @if($can('content') || $can('basic_meta_tags') || $can('advanced_seo') || $can('managed_seo') || $can('themes_multiple') || $can('template_basic'))
                    <div class="nav-section">
                        <div class="section-label">{{ $can('content') ? 'Content' : 'Settings' }}</div>
                        <ul class="nav-list">
                            @if($can('content'))
                            <li class="nav-item {{ request()->is('admin/testimonials*') ? 'active' : '' }}">
                                <a href="{{ route('admin.testimonials.index') }}"
                                    class="nav-link {{ request()->is('admin/testimonials*') ? 'active' : '' }}">
                                    <i class="ri-star-line nav-icon"></i>
                                    <span class="nav-text">Reviews</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('admin/faqs*') ? 'active' : '' }}">
                                <a href="{{ route('admin.faqs.index') }}"
                                    class="nav-link {{ request()->is('admin/faqs*') ? 'active' : '' }}">
                                    <i class="ri-question-line nav-icon"></i>
                                    <span class="nav-text">FAQ</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('admin/posts*') ? 'active' : '' }}">
                                <a href="{{ url('admin/posts') }}"
                                    class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}">
                                    <i class="ri-article-line nav-icon"></i>
                                    <span class="nav-text">Articles</span>
                                </a>
                            </li>
                            @endif

                            @if($can('basic_meta_tags') || $can('advanced_seo') || $can('managed_seo') || $can('template_basic') || $can('themes_multiple'))
                            <li
                                class="nav-item has-dropdown
                                {{ request()->is('admin/setting-page*') ||
                                request()->is('admin/categories*') ||
                                request()->is('admin/post-types*') ||
                                request()->is('admin/medicine-templates*') ||
                                request()->is('admin/prescriptions-template*') ||
                                request()->is('admin/tests*') ||
                                request()->is('admin/investigations*') ||
                                request()->is('admin/sliders*') ||
                                request()->is('admin/galleries*') ||
                                request()->is('admin/events*') ||
                                request()->is('admin/medicine-companies*') ||
                                request()->is('admin/comorbidities*') ||
                                request()->is('admin/plan-templates*') ||
                                request()->is('admin/follow-up-templates*')
                                    ? 'active'
                                    : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="ri-settings-3-line nav-icon"></i>
                                <span class="nav-text">Settings</span>
                                <i class="ri-arrow-down-s-line dropdown-icon"></i>
                            </a>
                            <ul class="dropdown-menu">
                                @if($can('basic_meta_tags') || $can('advanced_seo') || $can('managed_seo'))
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/setting-page*') ? 'active' : '' }}"
                                        href="/admin/setting-page">
                                        <i class="ri-settings-2-line"></i> General
                                    </a>
                                </li>
                                @endif
                                @if($can('template_basic') || $can('themes_multiple'))
                                <li>
                                    <a class="dropdown-item {{ request()->is('tenantadmin/templates*') ? 'active' : '' }}"
                                        href="{{ route('tenantadmin.templates.index') }}">
                                        <i class="ri-layout-3-line"></i> Templates
                                    </a>
                                </li>
                                @endif
                                @if($can('content'))
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/categories*') ? 'active' : '' }}"
                                        href="/admin/categories">
                                        <i class="ri-folder-2-line"></i> Categories
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/post-types*') ? 'active' : '' }}"
                                        href="/admin/post-types">
                                        <i class="ri-price-tag-3-line"></i> Types
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/medicine-templates*') ? 'active' : '' }}"
                                        href="/admin/medicine-templates">
                                        <i class="ri-medicine-bottle-line"></i> Medicine Templates
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/prescriptions-template*') ? 'active' : '' }}"
                                        href="/admin/prescriptions-template">
                                        <i class="ri-file-text-line"></i> Prescriptions Templates
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/plan-templates*') ? 'active' : '' }}"
                                        href="/admin/plan-templates">
                                        <i class="ri-file-list-2-line"></i> Plan Templates
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/follow-up-templates*') ? 'active' : '' }}"
                                        href="/admin/follow-up-templates">
                                        <i class="ri-refresh-line"></i> Follow Up Templates
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/tests*') ? 'active' : '' }}"
                                        href="/admin/tests">
                                        <i class="ri-microscope-line"></i> Tests
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/investigations*') ? 'active' : '' }}"
                                        href="/admin/investigations">
                                        <i class="ri-search-line"></i> Investigations
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/sliders*') ? 'active' : '' }}"
                                        href="/admin/sliders">
                                        <i class="ri-slideshow-line"></i> Sliders
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/galleries*') ? 'active' : '' }}"
                                        href="/admin/galleries">
                                        <i class="ri-image-line"></i> Galleries
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/events*') ? 'active' : '' }}"
                                        href="/admin/events">
                                        <i class="ri-calendar-event-line"></i> Events
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/medicine-companies*') ? 'active' : '' }}"
                                        href="/admin/medicine-companies">
                                        <i class="ri-building-line"></i> Medicine Companies
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/comorbidities*') ? 'active' : '' }}"
                                        href="/admin/comorbidities">
                                        <i class="ri-heart-pulse-line"></i> Comorbidities
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h4>Dr. {{ auth()->user()->name }}</h4>
                        <p>{{ auth()->user()->specializationLabel('Medical Professional') }}</p>
                    </div>
                    <a href="{{ route('logout') }}" class="logout-btn"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-logout-box-r-line"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                        style="display: none;">
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
               <div class=" d-flex d-md-none">
                    <img alt="Doctor Directory Logo" class="mob-logo"
                        src="https://doctorsprofile.xyz/uploads/settings/logo_HsNogj4w3l.png" height="50">
                </div>

                

                <div class="top-bar-actions">
                    {{-- <div class="search-box">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="header-search-input"
                            placeholder="Search patients, appointments...">
                    </div> --}}

                    <div class="quick-actions">
                        {{-- <button class="quick-action-btn"
                            onclick="openBookingModal({{ $doctor->id ?? 'null' }}, {{ json_encode($chambers ?? []) }})">
                            <i class="ri-add-line"></i>
                            New Appointment
                        </button> --}}
                        @if($can('patients'))
                        <a href="/admin/add-new-prescriptions">
                            <button class="quick-action-btn">
                                <i class="ri-user-add-line"></i>
                               <span class="d-none d-md-inline">  Add  Prescription</span>
                            </button>
                        </a>
                        @endif
                    </div>


                    @if($can('email_notifications') || $can('sms_reminders') || $can('appointments'))
                    <button class="notification-btn" id="notificationBtn">
                        <i class="ri-notification-3-line"></i>
                        <span class="notification-badge" id="notificationCount">0</span>
                    </button>
                    @endif

                    <div class="profile-dropdown" id="profileDropdown">
                        <button class="profile-btn" id="profileBtn">
                            <img src="{{ auth()->user()->photo ? url(auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=318069&color=fff' }}"
                                alt="Profile" class="profile-img">
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="profile-dropdown-menu" id="profileMenu">
                            <div class="profile-dropdown-header">
                                <h4>Dr. {{ auth()->user()->name }}</h4>
                                <p>{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('admin.profile.edit') }}" class="profile-dropdown-item">
                                <i class="ri-user-3-line"></i>
                                <span>My Profile</span>
                            </a>
                            {{-- <a href="" class="profile-dropdown-item">
                                <i class="ri-settings-3-line"></i>
                                <span>Settings</span>
                            </a> --}}
                            <div class="profile-dropdown-divider"></div>
                            <button class="profile-dropdown-item" id="logoutBtn">
                                <i class="ri-logout-box-r-line"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    </div>

                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="ri-menu-line"></i>
                </button>
                </div>
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
                                    <i class="ri-user-heart-line"></i>
                                </div>
                                <span class="card-title">Total Patients</span>
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
                                <span class="card-title">Today's Appointments</span>
                            </div>
                            <div class="card-value">8</div>
                            <div class="card-change positive">
                                <i class="ri-arrow-up-line"></i> 8%
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <div class="card-icon warning">
                                    <i class="ri-file-list-3-line"></i>
                                </div>
                                <span class="card-title">Pending Prescriptions</span>
                            </div>
                            <div class="card-value">12</div>
                            <div class="card-change negative">
                                <i class="ri-arrow-down-line"></i> 3%
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <div class="card-icon danger">
                                    <i class="ri-chat-3-line"></i>
                                </div>
                                <span class="card-title">Unread Messages</span>
                            </div>
                            <div class="card-value">5</div>
                            <div class="card-change positive">
                                <i class="ri-arrow-down-line"></i> 5%
                            </div>
                        </div>
                    </div>
                @endif
@php
$centralUser = \App\Models\User::on('mysql')
        ->where('email',auth()->user()->email)
        ->first();

    if (!$centralUser) return null;


    //  $subscription = \App\Models\Subscription::where('tenant_id', tenant('id'))
    //     ->with('package')
    //     ->latest()
    //     ->first();

    // $isActive = $subscription &&
    //             $subscription->status === 'active' &&
    //             \Carbon\Carbon::parse($subscription->ends_at)->isFuture();
    $subscription = \App\Models\Subscription::where('tenant_id', tenant('id'))
        ->where('status','active')
        ->with('package')
        ->latest()
        ->first();

    $isActive = false;
    $daysLeft = 0;

    if ($subscription) {
        $endsAt = \Carbon\Carbon::parse($subscription->ends_at);

        $isActive = $subscription->status === 'active' && $endsAt->isFuture();
        $daysLeft = now()->diffInDays($endsAt, false);
    }

    $currentPackageId = $subscription->package_id ?? null;
    @endphp



@if($isActive)

<div class="alert alert-gray d-flex bg-white justify-content-between align-items-center p-2">
    <small>
        {{ $subscription->package->name ?? 'N/A' }} | Expires: {{ $subscription->ends_at->format('d M Y') }} | {{ max(0, round($daysLeft)) }} day{{ $daysLeft > 1 ? 's' : '' }} left
    </small>
    <a href="{{ route('package.upgrade') }}" class="btn btn-{{ $daysLeft <= 7 ? 'warning' : 'primary' }} btn-sm ms-2">
        {{ $daysLeft <= 7 ? 'Renew' : 'Upgrade' }}
    </a>
</div>

{{-- FULL DASHBOARD CONTENT --}}
@yield('content')
@else

<div class="alert alert-danger text-center">
    <h4 class="mb-3">Subscription Inactive</h4>
    <p>Please renew or upgrade your package to regain access.</p>

    <button class="btn btn-primary mt-2"
            data-bs-toggle="modal"
            data-bs-target="#upgradeModal">
        Upgrade Now
    </button>
</div>
<!-- Upgrade Modal -->
<div class="modal fade" id="upgradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Upgrade Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('package.upgrade.process') }}">
                @csrf

                <div class="modal-body">

                    @if($subscription)
                        <div class="alert alert-info">
                            <strong>Current Plan:</strong>
                            {{ $subscription->package->name ?? 'N/A' }}
                            <br>
                            Expires:
                            {{ \Carbon\Carbon::parse($subscription->ends_at)->format('d M Y') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="fw-bold">Select Package</label>

                        <select name="package_id" class="form-control" required>

                            @foreach(\App\Models\Package::where('is_visible',1)->get() as $pkg)

                                <option value="{{ $pkg->id }}"
                                    {{ $pkg->id == $currentPackageId ? 'disabled' : '' }}>

                                    {{ $pkg->name }}

                                    @if($pkg->id == $currentPackageId)
                                        (Current Plan)
                                    @else
                                        (Monthly: {{ $pkg->price_monthly }}
                                        | Yearly: {{ $pkg->price_yearly }})
                                    @endif

                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Billing Cycle</label>
                        <select name="billing_cycle" class="form-control" required>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Confirm Upgrade
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endif

            </div>

            <!-- Footer -->
            <footer class="footer">
                <p>© {{ date('Y') }} DoctorHub | v2.0.0 | Dr. {{ auth()->user()->name }}</p>
            </footer>
        </main>
    </div>


    <!-- Booking Modal in Layout -->
    <div class="modal fade primary-modal" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content booking-modal">

            <!-- HEADER -->
            <div class="modal-header booking-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-calendar-check-line fs-4"></i>
                    <div>
                        <h5 class="modal-title mb-0">New Appointment</h5>
                        <small class="text-muted">Book your consultation</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="bookingForm" method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <input type="hidden" name="doctor_id" id="bookingDoctorId">

                <div class="modal-body">

                    <!-- CONSULTATION TYPE -->
                    <div class="card form-card mb-3">
                        <label class="form-label fw-semibold">
                            <i class="ri-video-chat-line me-1"></i> Consultation Type
                        </label>
                        <select name="consultation_type" id="consultationType" class="form-select" required>
                            <option value="offline">In-Person Visit</option>
                            <option value="online">Online Consultation</option>
                        </select>
                    </div>

                    <!-- CHAMBER -->
                    <div class="card form-card mb-3" id="chamberWrapper">
                        <label class="form-label fw-semibold">
                            <i class="ri-building-line me-1"></i> Select Chamber
                        </label>
                        <select name="chamber_id" id="chamberSelect" class="form-select">
                            <option value="">Select Chamber</option>
                        </select>
                    </div>

                    <!-- DATE & TIME -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ri-calendar-line me-1"></i> Appointment Date
                            </label>
                            <input type="date" name="appointment_date" id="appointmentDate"
                                   class="form-control" min="{{ now()->toDateString() }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ri-time-line me-1"></i> Available Time
                            </label>
                            <select name="appointment_time" id="slotSelect" class="form-select" required>
                                <option value="">Select Date & Time</option>
                            </select>
                        </div>
                    </div>

                    <!-- PATIENT INFO -->
                    <div class="card form-card mb-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="ri-user-line me-1"></i> Patient Information
                        </h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="patient_first_name" class="form-control"
                                       placeholder="First Name *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="patient_last_name" class="form-control"
                                       placeholder="Last Name *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="patient_phone" class="form-control"
                                       placeholder="Phone Number *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="patient_email" class="form-control"
                                       placeholder="Email Address *" required>
                            </div>
                        </div>
                    </div>

                    <!-- SERVICE -->
                    <div class="card form-card mb-3">
                        <label class="form-label fw-semibold">
                            <i class="ri-stethoscope-line me-1"></i> Service Type
                        </label>
                        <input type="text" name="service_type" class="form-control"
                               placeholder="General Consultation" required>
                    </div>

                    <!-- NOTES -->
                    <div class="card form-card mb-3">
                        <label class="form-label fw-semibold">
                            <i class="ri-file-text-line me-1"></i> Notes (Optional)
                        </label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="Any additional information..."></textarea>
                    </div>

                    <!-- TERMS -->
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="terms_agreed" required>
                        <label class="form-check-label small">
                            I agree to the terms & conditions
                        </label>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer booking-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-calendar-check-line me-1"></i> Confirm Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
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

            // Auto-expand dropdown if it has active child on page load
            document.querySelectorAll('.has-dropdown').forEach(dropdown => {
                const activeChild = dropdown.querySelector('.dropdown-item.active');
                if (activeChild) {
                    dropdown.classList.add('active');
                }
            });

            // Handle dropdown toggle
            document.querySelectorAll('.has-dropdown > .nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                        const parent = this.closest('.has-dropdown');
                        const isActive = parent.classList.contains('active');

                        // Toggle current dropdown
                        parent.classList.toggle('active');
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.has-dropdown')) {
                    document.querySelectorAll('.has-dropdown').forEach(item => {
                        // Don't close if it has active child
                        const hasActiveChild = item.querySelector('.dropdown-item.active');
                        if (!hasActiveChild) {
                            item.classList.remove('active');
                        }
                    });
                }
            });

            // Update badge counts periodically
            function updateBadgeCounts() {
                // Simulate API call
                $.ajax({
                    url: '/api/doctor/stats',
                    method: 'GET',
                    success: function(data) {
                        $('#appointment-badge').text(data.appointments || 0);
                        $('#notificationCount1').text(data.notifications || 0);
                    },
                    error: function() {
                        // Fallback for demo
                        $('#appointment-badge').text(Math.floor(Math.random() * 10));
                        $('#notificationCount1').text(Math.floor(Math.random() * 5));
                    }
                });
            }

            // Initial update
            updateBadgeCounts();

            // Update every 30 seconds
            setInterval(updateBadgeCounts, 30000);
        });
    </script>
    <script>
    // Global function to open booking modal with data
    function openBookingModal(doctorId = null, chambers = null) {

        const bookingModal = new bootstrap.Modal(
            document.getElementById('bookingModal')
        );

        // Set doctor id
        document.getElementById('bookingDoctorId').value =
            doctorId ?? "{{ auth()->id() }}";

        // Populate chambers
        const chamberSelect = document.getElementById('chamberSelect');
        if (chambers && chambers.length > 0) {
            chamberSelect.innerHTML = '<option value="">Select Chamber</option>';
            chambers.forEach(chamber => {
                const option = document.createElement('option');
                option.value = chamber.id;
                option.textContent = chamber.name;
                chamberSelect.appendChild(option);
            });
        }

        // Reset form
        document.getElementById('bookingForm').reset();
        document.getElementById('slotSelect').innerHTML =
            '<option value="">Select Date</option>';
        document.getElementById('appointmentDate').value = '';

        bookingModal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {

        const chamberSelect = document.getElementById('chamberSelect');
        const dateInput = document.getElementById('appointmentDate');
        const slotsSelect = document.getElementById('slotSelect');
        const consultationType = document.getElementById('consultationType');
        const chamberWrapper = document.getElementById('chamberWrapper');
        const doctorIdInput = document.getElementById('bookingDoctorId');

        function resetSlots(message = 'Select Date') {
            slotsSelect.innerHTML = `<option value="">${message}</option>`;
        }

        function loadSlots() {
            const date = dateInput.value;
            const type = consultationType.value;

            if (!date) return;

            let url = '';

            // ONLINE
            if (type === 'online') {
                url = `/doctors/${doctorIdInput.value}/online-slots/${date}`;
            }
            // OFFLINE
            else {
                if (!chamberSelect.value) return;
                url = `/chambers/${chamberSelect.value}/slots/${date}`;
            }

            slotsSelect.innerHTML = '<option>Loading...</option>';

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    slotsSelect.innerHTML = '';

                    if (!data.slots || !data.slots.length) {
                        resetSlots('No slots available');
                        return;
                    }

                    data.slots.forEach(slot => {
                        if (slot.available === false) return;

                        const option = document.createElement('option');
                        option.value = slot.start;
                        option.textContent = slot.label ?? slot.start;
                        slotsSelect.appendChild(option);
                    });
                })
                .catch(() => resetSlots('Error loading slots'));
        }

        // Consultation type toggle
        consultationType.addEventListener('change', function () {
            resetSlots('Select Date');

            if (this.value === 'online') {
                chamberWrapper.style.display = 'none';
                chamberSelect.required = false;
                chamberSelect.value = '';
            } else {
                chamberWrapper.style.display = 'block';
                chamberSelect.required = true;
            }
        });

        // Events
        chamberSelect.addEventListener('change', loadSlots);
        dateInput.addEventListener('change', loadSlots);
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Mobile Sidebar Toggle =====
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        
        if (mobileMenuBtn && sidebar && sidebarBackdrop) {
            // Open sidebar when clicking hamburger menu
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.add('mobile-open');
                sidebarBackdrop.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            });
            
            // Close sidebar when clicking backdrop
            sidebarBackdrop.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                sidebarBackdrop.classList.remove('active');
                document.body.style.overflow = '';
            });
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    sidebarBackdrop.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
            
            // Close sidebar when clicking on a link (optional)
            const sidebarLinks = sidebar.querySelectorAll('.nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        sidebar.classList.remove('mobile-open');
                        sidebarBackdrop.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            });
        }
        
        // ===== Fix for dropdowns in mobile =====
        const dropdownLinks = document.querySelectorAll('.has-dropdown > .nav-link');
        dropdownLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    // On mobile, prevent default and toggle dropdown
                    if (this.getAttribute('href') === '#' || this.getAttribute('href') === '') {
                        e.preventDefault();
                        const parent = this.closest('.has-dropdown');
                        parent.classList.toggle('active');
                        
                        // Close other dropdowns
                        document.querySelectorAll('.has-dropdown').forEach(item => {
                            if (item !== parent && item.classList.contains('active')) {
                                item.classList.remove('active');
                            }
                        });
                    }
                }
            });
        });
        
        // ===== Handle window resize - reset sidebar state on desktop =====
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                // On desktop, ensure sidebar is visible and backdrop is hidden
                if (sidebar) sidebar.classList.remove('mobile-open');
                if (sidebarBackdrop) sidebarBackdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function loadNotificationCount() {
                fetch("{{ route('admin.notifications.count') }}")
                    .then(res => res.json())
                    .then(data => {
                        const badge = document.getElementById('notificationCount');

                        if (data.count > 0) {
                            badge.innerText = data.count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    });
            }

            // Load on page open
            loadNotificationCount();

            // Auto refresh every 15 seconds
            setInterval(loadNotificationCount, 15000);

            document.getElementById('notificationBtn').addEventListener('click', function() {
                window.location.href = "{{ url('/admin/messages') }}";
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const profileBtn = document.getElementById('profileBtn');
            const profileMenu = document.getElementById('profileMenu');

            if (!profileBtn || !profileMenu) return;

            // Toggle dropdown
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('show');
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                    profileMenu.classList.remove('show');
                }
            });

            // Escape key close
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    profileMenu.classList.remove('show');
                }
            });

        });
    </script>
    <script>
        document.getElementById('logoutBtn')?.addEventListener('click', function() {
            document.getElementById('logout-form').submit();
        });
    </script>

    @stack('scripts')
</body>

</html>
