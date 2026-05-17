@extends('layouts.supperadmin')

@section('title', 'Doctors')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: #e8f5f0;
        --primary-dark: #276854;
        --primary-soft: #f0fdf4;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --text-light: #94a3b8;
        --border: #e2e8f0;
        --border-light: #f1f5f9;
        --bg-gray: #f8fafc;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid var(--border);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card.revenue-stat {
        padding-right: 1.5rem;
        padding-left: 1.5rem;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-light);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        width: 3rem;
        height: 3rem;
        background: var(--primary-light);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon i {
        font-size: 1.25rem;
        color: var(--primary);
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .revenue-stat .stat-value {
        overflow-wrap: anywhere;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-trend {
        font-size: 0.7rem;
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.5rem;
        border-radius: 1rem;
        background: var(--primary-soft);
        color: var(--primary);
    }

    /* Search Section */
    .search-section {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border);
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .filter-select {
        padding: 0.75rem 2rem 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        font-size: 0.85rem;
        background: white;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .search-reset-btn {
        padding: 0.75rem 1.25rem;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        background: white;
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .search-reset-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    /* Table Styles */
    .doctors-table {
        border-radius: 1rem;
        overflow: hidden;
    }

    .doctors-table thead th {
        background: var(--bg-gray);
        color: var(--text-muted);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1rem;
        border-bottom: 1px solid var(--border);
    }

    .doctors-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--border-light);
    }

    .doctors-table tbody tr:hover {
        background: var(--bg-gray);
    }

    .doctors-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: var(--text-dark);
        font-size: 0.85rem;
    }

    /* Doctor Avatar */
    .doctor-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid var(--primary-light);
        transition: all 0.2s ease;
    }

    .doctor-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.2rem;
    }

    .doctor-specialty {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .featured-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.65rem;
        font-weight: 500;
        background: #fef3c7;
        color: #92400e;
        margin-top: 0.35rem;
    }

    /* Payment Info */
    .payment-amount {
        font-weight: 700;
        color: var(--primary);
        font-size: 0.9rem;
    }

    .payment-meta {
        font-size: 0.65rem;
        color: var(--text-light);
        margin-top: 0.2rem;
    }

    /* Domain Info */
    .domain-name {
        font-weight: 500;
        font-size: 0.8rem;
        color: var(--text-dark);
    }

    .domain-type {
        font-size: 0.65rem;
        color: var(--text-light);
    }

    /* Action Buttons */
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .action-view {
        background: var(--primary-light);
        color: var(--primary);
    }

    .action-view:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    .action-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
    }

    /* Mobile Card View */
    .doctor-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid var(--border);
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .doctor-card:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }

    .card-header-info {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .card-avatar {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        object-fit: cover;
        border: 2px solid var(--primary-light);
    }

    .card-name h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--text-dark);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin: 1rem 0;
        padding: 0.75rem 0;
        border-top: 1px solid var(--border-light);
        border-bottom: 1px solid var(--border-light);
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-label {
        font-size: 0.65rem;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-dark);
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 1rem;
        border: 1px solid var(--border);
    }

    .no-results-icon {
        font-size: 3rem;
        color: var(--text-light);
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Delete Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1100;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .modal-container {
        background: white;
        border-radius: 1rem;
        width: 100%;
        max-width: 400px;
        overflow: hidden;
        animation: modalSlideIn 0.2s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid {
            gap: 1rem;
        }
        .stat-value {
            font-size: 1.5rem;
        }
        .stat-card.revenue-stat {
            padding-right: 1.25rem;
            padding-left: 1.25rem;
        }
    }

    @media (max-width: 992px) {
        .desktop-view {
            display: none;
        }
        .mobile-view {
            display: block;
        }
    }

    @media (min-width: 993px) {
        .mobile-view {
            display: none;
        }
        .desktop-view {
            display: block;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .search-section .d-flex {
            flex-direction: column;
        }
        .search-input-wrapper {
            width: 100%;
        }
        .filter-select, .search-reset-btn {
            width: 100%;
        }
        .info-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="pb-3">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 page-header">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Doctor Management</h1>
            <p class="text-muted mb-0 small">Manage doctor accounts, monitor subscriptions, and track payments</p>
        </div>
        <a href="{{ url('doctor/create') }}" class="btn btn-primary px-4 py-2" target="_blank">
            <i class="ri-user-add-line me-2"></i>Add New Doctor
        </a>
    </div>

    <!-- Stats Cards -->
    @php
        $activeDoctors = $users->where('status', 1)->count();
        $featuredDoctors = $users->where('feature', 1)->count();
        $inactiveDoctors = $users->where('status', 0)->count();
        $totalRevenue = $users->sum(function($user) {
            return $user->latest_payment ? (float) $user->latest_payment->amount : 0;
        });
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" id="totalDoctorsStat">{{ number_format($users->count()) }}</div>
            <div class="stat-label">Total Doctors</div>
            <div class="stat-trend">
                <i class="ri-user-add-line"></i> +{{ $users->where('created_at', '>=', now()->subDays(30))->count() }} this month
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="activeDoctorsStat">{{ $activeDoctors }}</div>
            <div class="stat-label">Active Doctors</div>
            <div class="stat-trend">
                <i class="ri-group-line"></i> {{ round(($activeDoctors / max($users->count(), 1)) * 100) }}% of total
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="featuredDoctorsStat">{{ $featuredDoctors }}</div>
            <div class="stat-label">Featured Doctors</div>
            <div class="stat-trend">
                <i class="ri-medal-line"></i> Premium members
            </div>
        </div>
        <div class="stat-card revenue-stat">
            <div class="stat-value">৳ {{ number_format($totalRevenue, 0) }}</div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-trend">
                <i class="ri-arrow-up-line"></i> Lifetime earnings
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <div class="d-flex gap-3 align-items-center flex-wrap">
            <div class="search-input-wrapper flex-grow-1">
                <i class="ri-search-line"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Search by name, email, phone, or specialization...">
            </div>
            <div style="min-width: 150px;">
                <select id="statusFilter" class="filter-select w-100">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div style="min-width: 150px;">
                <select id="featureFilter" class="filter-select w-100">
                    <option value="all">All Doctors</option>
                    <option value="featured">Featured Only</option>
                    <option value="normal">Normal Only</option>
                </select>
            </div>
            <button id="resetSearchBtn" class="search-reset-btn">
                <i class="ri-refresh-line me-1"></i> Reset
            </button>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="desktop-view">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="doctors-table table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Doctor</th>
                                <!-- <th>Contact</th> -->
                                <th>Domain</th>
                                <th>Status</th>
                                <th>Latest Payment</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="doctorsTableBody">
                            @forelse ($users as $user)
                                <tr class="doctor-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-phone="{{ $user->mobile ?? '' }}" data-specialty="{{ strtolower($user->specializationLabel()) }}" data-status="{{ $user->status }}" data-featured="{{ $user->feature }}">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $user->photo ? url($user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=318069&color=fff&length=2&bold=true' }}"
                                                 alt="{{ $user->name }}"
                                                 class="doctor-avatar">
                                            <div>
                                                <div class="doctor-name">{{ $user->name }}</div>
                                                <div class="doctor-specialty">{{ $user->specializationLabel() }}</div>
                                                @if($user->qualification)
                                                    <div class="doctor-specialty text-muted">{{ $user->qualification }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="small">{{ $user->email }}</div>
                                        <div class="small text-muted mt-1">
                                            <i class="ri-phone-line me-1 fs-10"></i>{{ $user->mobile ?? 'No phone' }}
                                        </div>
                                    </td> -->
                                    <td>
                                        @if($user->primary_domain)
                                            <div class="domain-name">{{ $user->primary_domain->domain }}</div>
                                            <div class="domain-type">{{ ucfirst($user->primary_domain->type ?? 'domain') }}</div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="status-badge {{ (int) $user->status === 1 ? 'status-active' : 'status-inactive' }}">
                                            <i class="ri-circle-fill fs-8"></i>
                                            {{ (int) $user->status === 1 ? 'Active' : 'Inactive' }}
                                        </div>
                                        @if((int) $user->feature === 1)
                                            <div class="featured-badge">
                                                <i class="ri-star-fill"></i> Featured
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->latest_payment)
                                            <div class="payment-amount">৳ {{ number_format((float) $user->latest_payment->amount, 2) }}</div>
                                            <div class="payment-meta">
                                                {{ $user->latest_payment->payment_method_text ?? \Illuminate\Support\Str::headline($user->latest_payment->payment_method) }}
                                                · {{ $user->latest_payment->status_text ?? \Illuminate\Support\Str::headline($user->latest_payment->status) }}
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('user.show', $user->id) }}" class="action-btn action-view" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <button type="button" class="action-btn action-delete delete-doctor-btn"
                                                    data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}"
                                                    title="Delete Doctor">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="ri-user-line fs-1 d-block mb-3 opacity-25"></i>
                                        <p class="text-muted mb-0">No doctors found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-view" id="mobileDoctorsContainer">
        @forelse ($users as $user)
            <div class="doctor-card doctor-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-phone="{{ $user->mobile ?? '' }}" data-specialty="{{ strtolower($user->specializationLabel()) }}" data-status="{{ $user->status }}" data-featured="{{ $user->feature }}">
                <div class="card-header-info">
                    <img src="{{ $user->photo ? url($user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=318069&color=fff&length=2&bold=true' }}"
                         alt="{{ $user->name }}"
                         class="card-avatar">
                    <div class="card-name">
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted small mb-0">{{ $user->specializationLabel() }}</p>
                        <p class="text-muted small">{{ $user->qualification ?? '' }}</p>
                    </div>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $user->mobile ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Domain</span>
                        <span class="info-value">{{ $user->primary_domain->domain ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="status-badge {{ (int) $user->status === 1 ? 'status-active' : 'status-inactive' }}" style="display: inline-flex;">
                                <i class="ri-circle-fill fs-8"></i>
                                {{ (int) $user->status === 1 ? 'Active' : 'Inactive' }}
                            </span>
                            @if((int) $user->feature === 1)
                                <span class="featured-badge ms-2">
                                    <i class="ri-star-fill"></i> Featured
                                </span>
                            @endif
                        </span>
                    </div>
                    @if($user->latest_payment)
                    <div class="info-item">
                        <span class="info-label">Latest Payment</span>
                        <span class="info-value payment-amount">৳ {{ number_format((float) $user->latest_payment->amount, 2) }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('user.show', $user->id) }}" class="btn-action btn-view flex-grow-1" style="padding: 0.6rem; border-radius: 0.5rem; text-decoration: none; text-align: center;">
                        <i class="ri-eye-line me-1"></i> View Details
                    </a>
                    <button type="button" class="btn-action btn-delete delete-doctor-btn flex-grow-1" style="padding: 0.6rem; border-radius: 0.5rem; border: none; cursor: pointer;"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}">
                        <i class="ri-delete-bin-line me-1"></i> Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="ri-user-line fs-1 d-block mb-3 opacity-25"></i>
                <p class="text-muted mb-0">No doctors found</p>
            </div>
        @endforelse
    </div>

    <!-- No Results Message -->
    <div id="noResultsMessage" class="no-results" style="display: none;">
        <div class="no-results-icon">
            <i class="ri-search-line"></i>
        </div>
        <h5 class="mb-2">No doctors found</h5>
        <p class="text-muted mb-0">Try adjusting your search or filter criteria</p>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-container">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
            <h5 class="m-0 fw-semibold" style="color: #dc2626;">
                <i class="ri-delete-bin-line me-2"></i>Delete Doctor
            </h5>
            <button class="close-delete-modal border-0 bg-transparent fs-4" style="cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <div class="p-4">
            <p class="mb-0">Are you sure you want to delete <strong id="deleteDoctorName" class="text-dark"></strong>?</p>
            <p class="text-muted small mt-2 mb-0">This action cannot be undone. All doctor data will be permanently removed.</p>
        </div>
        <div class="d-flex justify-content-end gap-3 p-4 border-top">
            <button class="close-delete-modal px-4 py-2 rounded-2 border" style="background: white; cursor: pointer;">Cancel</button>
            <form id="deleteDoctorForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-2 border-0" style="background: #dc2626; color: white; cursor: pointer;">
                    <i class="ri-delete-bin-line me-1"></i> Delete Permanently
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const featureFilter = document.getElementById('featureFilter');
    const resetBtn = document.getElementById('resetSearchBtn');
    const doctorsTableBody = document.getElementById('doctorsTableBody');
    const mobileDoctorsContainer = document.getElementById('mobileDoctorsContainer');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const totalDoctorsStat = document.getElementById('totalDoctorsStat');
    const activeDoctorsStat = document.getElementById('activeDoctorsStat');
    const featuredDoctorsStat = document.getElementById('featuredDoctorsStat');

    // Get all doctor rows (both desktop and mobile)
    function getAllDoctorRows() {
        const desktopRows = document.querySelectorAll('#doctorsTableBody .doctor-row');
        const mobileRows = document.querySelectorAll('#mobileDoctorsContainer .doctor-row');
        
        // Return as array for easier manipulation
        return {
            desktop: Array.from(desktopRows),
            mobile: Array.from(mobileRows),
            all: () => [...Array.from(desktopRows), ...Array.from(mobileRows)]
        };
    }

    // Update stats based on visible doctors
    function updateStats(visibleRows) {
        let totalVisible = visibleRows.length;
        let activeVisible = 0;
        let featuredVisible = 0;
        
        visibleRows.forEach(row => {
            const status = parseInt(row.dataset.status);
            const featured = parseInt(row.dataset.featured);
            
            if (status === 1) activeVisible++;
            if (featured === 1) featuredVisible++;
        });
        
        if (totalDoctorsStat) totalDoctorsStat.textContent = totalVisible.toLocaleString();
        if (activeDoctorsStat) activeDoctorsStat.textContent = activeVisible;
        if (featuredDoctorsStat) featuredDoctorsStat.textContent = featuredVisible;
    }

    // Filter function
    function filterDoctors() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;
        const featureValue = featureFilter.value;
        
        const rows = getAllDoctorRows();
        const visibleRows = [];
        
        // Filter desktop rows
        rows.desktop.forEach(row => {
            let show = true;
            
            // Search filter
            if (searchTerm) {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const phone = row.dataset.phone || '';
                const specialty = row.dataset.specialty || '';
                
                if (!name.includes(searchTerm) && !email.includes(searchTerm) && !phone.includes(searchTerm) && !specialty.includes(searchTerm)) {
                    show = false;
                }
            }
            
            // Status filter
            if (show && statusValue !== 'all') {
                const status = row.dataset.status;
                if (statusValue === 'active' && status !== '1') show = false;
                if (statusValue === 'inactive' && status !== '0') show = false;
            }
            
            // Featured filter
            if (show && featureValue !== 'all') {
                const featured = row.dataset.featured;
                if (featureValue === 'featured' && featured !== '1') show = false;
                if (featureValue === 'normal' && featured !== '0') show = false;
            }
            
            row.style.display = show ? '' : 'none';
            if (show) visibleRows.push(row);
        });
        
        // Filter mobile rows
        rows.mobile.forEach(row => {
            let show = true;
            
            // Search filter
            if (searchTerm) {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const phone = row.dataset.phone || '';
                const specialty = row.dataset.specialty || '';
                
                if (!name.includes(searchTerm) && !email.includes(searchTerm) && !phone.includes(searchTerm) && !specialty.includes(searchTerm)) {
                    show = false;
                }
            }
            
            // Status filter
            if (show && statusValue !== 'all') {
                const status = row.dataset.status;
                if (statusValue === 'active' && status !== '1') show = false;
                if (statusValue === 'inactive' && status !== '0') show = false;
            }
            
            // Featured filter
            if (show && featureValue !== 'all') {
                const featured = row.dataset.featured;
                if (featureValue === 'featured' && featured !== '1') show = false;
                if (featureValue === 'normal' && featured !== '0') show = false;
            }
            
            row.style.display = show ? '' : 'none';
        });
        
        // Update stats
        updateStats(visibleRows);
        
        // Show/hide no results message
        const totalDeskVisible = rows.desktop.filter(r => r.style.display !== 'none').length;
        const totalMobileVisible = rows.mobile.filter(r => r.style.display !== 'none').length;
        
        if (totalDeskVisible === 0 && totalMobileVisible === 0) {
            if (doctorsTableBody) doctorsTableBody.style.display = 'none';
            if (mobileDoctorsContainer) mobileDoctorsContainer.style.display = 'none';
            if (noResultsMessage) noResultsMessage.style.display = 'block';
        } else {
            if (doctorsTableBody) doctorsTableBody.style.display = '';
            if (mobileDoctorsContainer) mobileDoctorsContainer.style.display = '';
            if (noResultsMessage) noResultsMessage.style.display = 'none';
        }
    }

    // Reset filters
    function resetFilters() {
        searchInput.value = '';
        statusFilter.value = 'all';
        featureFilter.value = 'all';
        filterDoctors();
    }

    // Event listeners
    if (searchInput) searchInput.addEventListener('keyup', filterDoctors);
    if (statusFilter) statusFilter.addEventListener('change', filterDoctors);
    if (featureFilter) featureFilter.addEventListener('change', filterDoctors);
    if (resetBtn) resetBtn.addEventListener('click', resetFilters);

    // Delete Modal Logic
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteDoctorForm');
    const deleteDoctorName = document.getElementById('deleteDoctorName');
    let currentDoctorId = null;

    document.querySelectorAll('.delete-doctor-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentDoctorId = this.dataset.id;
            const doctorName = this.dataset.name;
            deleteDoctorName.textContent = doctorName;
            deleteForm.action = @json(route('user.destroy', ['id' => '__DOCTOR_ID__'])).replace('__DOCTOR_ID__', currentDoctorId);
            deleteModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });

    function closeDeleteModal() {
        deleteModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        currentDoctorId = null;
    }

    document.querySelectorAll('.close-delete-modal').forEach(btn => {
        btn.addEventListener('click', closeDeleteModal);
    });

    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) closeDeleteModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && deleteModal.style.display === 'flex') {
            closeDeleteModal();
        }
    });
});
</script>
@endsection
