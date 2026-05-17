@extends('layouts.supperadmin')

@section('title', 'Doctor Details')

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
    }

    /* Profile Card */
    .profile-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 2rem 1.5rem 1.5rem;
        text-align: center;
        position: relative;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 1.5rem;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: var(--shadow-lg);
        margin-bottom: 1rem;
    }

    .profile-name {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .profile-specialty {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
    }

    /* Info Sections */
    .info-section {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-light);
    }

    .info-section:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-light);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-dark);
        word-break: break-word;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .form-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg-gray);
    }

    .form-card-header h3 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-card-body {
        padding: 1.25rem;
    }

    /* Form Controls */
    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-control, .form-select {
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        padding: 0.6rem 0.85rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
        outline: none;
    }

    /* Table Styles */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--bg-gray);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--border);
    }

    .data-table tbody td {
        padding: 0.85rem 1rem;
        font-size: 0.8rem;
        color: var(--text-dark);
        border-bottom: 1px solid var(--border-light);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: var(--bg-gray);
    }

    /* Badges */
    .badge-status {
        padding: 0.25rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-failed {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Stat Box */
    .stat-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--bg-gray);
        border-radius: 0.75rem;
    }

    .stat-box-icon {
        width: 2.5rem;
        height: 2.5rem;
        background: var(--primary-light);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-box-icon i {
        font-size: 1.1rem;
        color: var(--primary);
    }

    .stat-box-info .stat-label {
        font-size: 0.65rem;
        color: var(--text-light);
        text-transform: uppercase;
    }

    .stat-box-info .stat-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            padding: 1.5rem 1rem 1rem;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
        }
        .profile-name {
            font-size: 1.25rem;
        }
        .info-section {
            padding: 1rem;
        }
        .form-card-body {
            padding: 1rem;
        }
        .data-table thead {
            display: none;
        }
        .data-table tbody tr {
            display: block;
            margin-bottom: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 0.75rem;
        }
        .data-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px solid var(--border-light);
        }
        .data-table tbody td:last-child {
            border-bottom: none;
        }
        .data-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.7rem;
            text-transform: uppercase;
        }
    }
</style>

<div class="pb-3">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Doctor Details</h1>
            <p class="text-muted mb-0 small">Review doctor profile, domain details, and payment history</p>
        </div>
        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary px-4">
            <i class="ri-arrow-left-line me-2"></i>Back to List
        </a>
    </div>

    <div class="row g-4">
        <!-- Left Column - Profile Card -->
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-header">
                    <img src="{{ $user->photo ? url($user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=318069&color=fff&size=120&bold=true' }}"
                         alt="{{ $user->name }}"
                         class="profile-avatar">
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <p class="profile-specialty">{{ $user->specializationLabel() }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge-status {{ (int) $user->status === 1 ? 'badge-active' : 'badge-inactive' }}">
                            <i class="ri-circle-fill fs-8"></i>
                            {{ (int) $user->status === 1 ? 'Active' : 'Inactive' }}
                        </span>
                        @if((int) $user->feature === 1)
                            <span class="badge-status" style="background: #fef3c7; color: #92400e;">
                                <i class="ri-star-fill"></i> Featured
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">
                        <i class="ri-mail-line"></i> Email Address
                    </div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">
                        <i class="ri-phone-line"></i> Phone Number
                    </div>
                    <div class="info-value">{{ $user->mobile ?? 'Not provided' }}</div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">
                        <i class="ri-graduation-cap-line"></i> Qualification
                    </div>
                    <div class="info-value">{{ $user->qualification ?? 'Not specified' }}</div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">
                        <i class="ri-id-card-line"></i> Registration No
                    </div>
                    <div class="info-value">{{ $user->reg_no ?? 'Not registered' }}</div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">
                        <i class="ri-map-pin-line"></i> Location
                    </div>
                    <div class="info-value">
                        {{ $user->country ?? 'N/A' }}{{ $user->city ? ' / ' . $user->city : '' }}
                    </div>
                </div>
                
                <div class="info-section">
                    <div class="stat-box">
                        <div class="stat-box-icon">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <div class="stat-box-info">
                            <div class="stat-label">Member Since</div>
                            <div class="stat-value">{{ $user->created_at->format('F d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            
            <!-- Update Doctor Form -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3>
                        <i class="ri-edit-line text-primary"></i>
                        Edit Doctor Information
                    </h3>
                </div>
                <div class="form-card-body">
                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Qualification</label>
                                <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $user->qualification) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $user->specializationLabel('')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Registration No</label>
                                <input type="text" name="reg_no" class="form-control" value="{{ old('reg_no', $user->reg_no) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country', $user->country) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Status</label>
                                <select name="status" class="form-select">
                                    <option value="1" @selected((string) old('status', $user->status) === '1')>Active</option>
                                    <option value="0" @selected((string) old('status', $user->status) === '0')>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="feature" name="feature" value="1" @checked(old('feature', $user->feature)) style="cursor: pointer;">
                                    <label class="form-check-label ms-2" for="feature" style="cursor: pointer;">Featured Doctor</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ri-save-line me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Domains Section -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3>
                        <i class="ri-global-line text-primary"></i>
                        Doctor Domains
                    </h3>
                </div>
                <div class="form-card-body p-0">
                    @if($domains->isNotEmpty())
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Domain Name</th>
                                        <th>Type</th>
                                        <th>Fee</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($domains as $domain)
                                        <tr>
                                            <td data-label="Domain Name">
                                                <span class="fw-medium">{{ $domain->domain }}</span>
                                            </td>
                                            <td data-label="Type">{{ ucfirst($domain->type ?? 'domain') }}</td>
                                            <td data-label="Fee">৳ {{ number_format((float) ($domain->registration_fee ?? 0), 2) }}</td>
                                            <td data-label="Status">
                                                <span class="badge-status {{ (int) $domain->status === 1 ? 'badge-active' : 'badge-inactive' }}">
                                                    <i class="ri-circle-fill fs-8"></i>
                                                    {{ (int) $domain->status === 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="ri-global-line fs-1 d-block mb-2 opacity-25"></i>
                            <p class="mb-0">No domains found for this doctor</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment History Section -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3>
                        <i class="ri-history-line text-primary"></i>
                        Payment History
                    </h3>
                </div>
                <div class="form-card-body p-0">
                    @if($payments->isNotEmpty())
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Package</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Transaction ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td data-label="Date">
                                                <div class="fw-medium">{{ optional($payment->payment_date)->format('d M Y') ?? optional($payment->created_at)->format('d M Y') }}</div>
                                                <div class="text-muted small">{{ optional($payment->payment_date)->format('h:i A') ?? optional($payment->created_at)->format('h:i A') }}</div>
                                            </td>
                                            <td data-label="Package">{{ $payment->package->name ?? 'N/A' }}</td>
                                            <td data-label="Method">{{ $payment->payment_method_text ?? \Illuminate\Support\Str::headline($payment->payment_method) }}</td>
                                            <td data-label="Amount" class="fw-semibold text-primary">৳ {{ number_format((float) $payment->amount, 2) }}</td>
                                            <td data-label="Status">
                                                <span class="badge-status 
                                                    @if($payment->status === 'completed') badge-active
                                                    @elseif($payment->status === 'pending') badge-pending
                                                    @elseif($payment->status === 'failed') badge-failed
                                                    @endif">
                                                    {{ $payment->status_text ?? \Illuminate\Support\Str::headline($payment->status) }}
                                                </span>
                                            </td>
                                            <td data-label="Transaction ID">
                                                <span class="font-monospace small">{{ $payment->transaction_id ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="ri-bank-card-line fs-1 d-block mb-2 opacity-25"></i>
                            <p class="mb-0">No payment history found for this doctor</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tenant Snapshot -->
            @if($tenantSummary)
                <div class="form-card">
                    <div class="form-card-header">
                        <h3>
                            <i class="ri-building-line text-primary"></i>
                            Tenant Information
                        </h3>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="stat-box">
                                    <div class="stat-box-icon">
                                        <i class="ri-building-line"></i>
                                    </div>
                                    <div class="stat-box-info">
                                        <div class="stat-label">Site Name</div>
                                        <div class="stat-value">{{ $tenantSummary['site_name'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-box">
                                    <div class="stat-box-icon">
                                        <i class="ri-mail-line"></i>
                                    </div>
                                    <div class="stat-box-info">
                                        <div class="stat-label">Site Email</div>
                                        <div class="stat-value">{{ $tenantSummary['site_email'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-box">
                                    <div class="stat-box-icon">
                                        <i class="ri-phone-line"></i>
                                    </div>
                                    <div class="stat-box-info">
                                        <div class="stat-label">Site Phone</div>
                                        <div class="stat-value">{{ $tenantSummary['site_phone'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-box">
                                    <div class="stat-box-icon">
                                        <i class="ri-refresh-line"></i>
                                    </div>
                                    <div class="stat-box-info">
                                        <div class="stat-label">Billing Cycle</div>
                                        <div class="stat-value">{{ ucfirst($tenantSummary['billing_cycle'] ?? 'N/A') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-box">
                                    <div class="stat-box-icon">
                                        <i class="ri-user-line"></i>
                                    </div>
                                    <div class="stat-box-info">
                                        <div class="stat-label">Tenant Admin</div>
                                        <div class="stat-value">{{ $tenantSummary['tenant_admin_name'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-box">
                                    <div class="stat-box-icon">
                                        <i class="ri-mail-line"></i>
                                    </div>
                                    <div class="stat-box-info">
                                        <div class="stat-label">Admin Email</div>
                                        <div class="stat-value">{{ $tenantSummary['tenant_admin_email'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
