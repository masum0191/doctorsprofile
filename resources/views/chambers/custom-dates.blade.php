@extends('layouts.admin')
@section('title', 'Manage Custom Dates - ' . $chamber->name)

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.08);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-border: rgba(49, 128, 105, 0.15);
    }

    .info-section {
        background: #fff;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .form-section {
        background: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .table-section {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--primary);
        font-size: 1rem;
    }

    .form-grid {
        display: grid;
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.375rem;
    }

    .form-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
        line-height: 1.3;
    }

    .required-star {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        border: 1.5px solid #d1d5db;
        border-radius: 6px;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: #fff;
        font-family: inherit;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
        font-size: 0.85rem;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
    }

    /* Chamber Info */
    .chamber-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .chamber-info-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }

    .info-item {
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .info-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    /* Table */
    .table-container {
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead {
        background: #f8fafc;
    }

    .custom-table th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .custom-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background: #f9fafb;
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 4px;
        border: 1px solid transparent;
    }

    .badge-success {
        background-color: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .badge-secondary {
        background-color: #f3f4f6;
        color: #374151;
        border-color: #e5e7eb;
    }

    .badge-info {
        background-color: #e0f2fe;
        color: #075985;
        border-color: #bae6fd;
    }

    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    .badge-light {
        background-color: #f9fafb;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.15s ease;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-sm {
        padding: 0.375rem 0.625rem;
        font-size: 0.7rem;
    }

    .btn-icon {
        padding: 0.5rem;
        width: 32px;
        height: 32px;
        justify-content: center;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(49, 128, 105, 0.3);
    }

    .btn-outline-secondary {
        background: white;
        color: #374151;
        border: 1.5px solid #d1d5db;
    }

    .btn-outline-secondary:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-soft);
    }

    .btn-outline-info {
        background: white;
        color: #3b82f6;
        border: 1.5px solid #3b82f6;
    }

    .btn-outline-info:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-outline-success {
        background: white;
        color: #10b981;
        border: 1.5px solid #10b981;
    }

    .btn-outline-success:hover {
        background: #10b981;
        color: white;
    }

    .btn-outline-danger {
        background: white;
        color: #ef4444;
        border: 1.5px solid #ef4444;
    }

    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
    }

    /* Switch */
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    /* Search */
    .search-box {
        max-width: 300px;
    }

    .search-input {
        border-radius: 6px 0 0 6px;
        border-right: none;
    }

    .search-btn {
        border-radius: 0 6px 6px 0;
        background: #f9fafb;
        border: 1.5px solid #d1d5db;
        border-left: none;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: #6b7280;
        font-size: 0.875rem;
        max-width: 400px;
        margin: 0 auto 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .custom-table th,
        .custom-table td {
            padding: 0.75rem 0.5rem;
        }
        
        .action-buttons {
            flex-wrap: wrap;
        }
        
        .search-box {
            max-width: 100%;
        }
    }

    /* Alerts */
    .alert {
        border-radius: 6px;
        border: none;
        margin-bottom: 1rem;
    }

    .alert-success {
        background: linear-gradient(to right, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
        border-left: 3px solid #10b981;
        color: #065f46;
    }

    .alert-danger {
        background: linear-gradient(to right, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
        border-left: 3px solid #ef4444;
        color: #991b1b;
    }
</style>

<div class="pb-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold">Custom Dates</h1>
            <!-- <p class="text-muted small mb-0">Manage custom dates for {{ $chamber->name }}</p> -->
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.chambers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Back to Chambers
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>
                    <h6 class="mb-1">Please fix the following errors:</h6>
                    <ul class="mb-0 ps-3" style="font-size: 0.875rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <div class="form-container">
        <!-- Chamber Info -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-building"></i>
                Chamber Information
            </div>
            <div class="chamber-info-grid">
                <div class="info-item">
                    <div class="info-label">Chamber Name</div>
                    <div class="info-value">{{ $chamber->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Address</div>
                    <div class="info-value">{{ $chamber->address }}, {{ $chamber->city }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Consultation Fees</div>
                    <div class="info-value">৳{{ number_format($chamber->fees, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Add Form Column -->
            <div class="col-lg-4">
                <!-- Add New Date Form -->
                <div class="form-section">
                    <div class="section-title mb-2">
                        <i class="fas fa-plus-circle"></i>
                        Add New Date
                    </div>
                    <form action="{{ route('admin.chambers.custom-dates.store', $chamber) }}" method="POST" id="add-custom-date-form">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Date <span class="required-star">*</span>
                                </label>
                                <input type="date" 
                                       name="date" 
                                       class="form-control @error('date') error @enderror" 
                                       id="date" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       value="{{ old('date') }}" 
                                       required>
                                <div class="form-hint">Select future date</div>
                                @error('date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Start Time <span class="required-star">*</span>
                                </label>
                                <input type="time" 
                                       name="start_time" 
                                       class="form-control @error('start_time') error @enderror" 
                                       id="start_time" 
                                       value="{{ old('start_time', '09:00') }}" 
                                       required>
                                @error('start_time')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    End Time <span class="required-star">*</span>
                                </label>
                                <input type="time" 
                                       name="end_time" 
                                       class="form-control @error('end_time') error @enderror" 
                                       id="end_time" 
                                       value="{{ old('end_time', '17:00') }}" 
                                       required>
                                @error('end_time')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Slot Duration <span class="required-star">*</span>
                                </label>
                                <select name="slot_duration" 
                                        class="form-control @error('slot_duration') error @enderror" 
                                        id="slot_duration" 
                                        required>
                                    <option value="">Select</option>
                                    <option value="15" {{ old('slot_duration') == '15' ? 'selected' : '' }}>15 minutes</option>
                                    <option value="30" {{ old('slot_duration', '30') == '30' ? 'selected' : '' }}>30 minutes</option>
                                    <option value="45" {{ old('slot_duration') == '45' ? 'selected' : '' }}>45 minutes</option>
                                    <option value="60" {{ old('slot_duration') == '60' ? 'selected' : '' }}>60 minutes</option>
                                    <option value="90" {{ old('slot_duration') == '90' ? 'selected' : '' }}>90 minutes</option>
                                    <option value="120" {{ old('slot_duration') == '120' ? 'selected' : '' }}>120 minutes</option>
                                </select>
                                @error('slot_duration')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Max Patients</label>
                                <input type="number" 
                                       name="max_patients" 
                                       class="form-control @error('max_patients') error @enderror" 
                                       id="max_patients" 
                                       value="{{ old('max_patients') }}" 
                                       min="1"
                                       placeholder="Leave empty for unlimited">
                                <div class="form-hint">Optional maximum patients for this date</div>
                                @error('max_patients')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-span-2">
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           class="form-check-input" 
                                           id="is_active" 
                                           value="1"
                                           {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Accepting appointments)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-plus-circle me-2"></i>Add Date
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </form>
                </div>

                <!-- Quick Stats -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-chart-bar"></i>
                        Quick Stats
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">{{ $customDates->count() }}</div>
                            <div class="stat-label">Total Dates</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $customDates->where('is_active', true)->count() }}</div>
                            <div class="stat-label">Active Dates</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $customDates->where('date', '>=', today())->count() }}</div>
                            <div class="stat-label">Upcoming</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Column -->
            <div class="col-lg-8">
                <div class="table-section">
                    <div class="p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="section-title mb-0">
                                <i class="fas fa-calendar-alt"></i>
                                Scheduled Custom Dates
                            </div>
                            <div class="search-box">
                                <div class="input-group input-group-sm">
                                    <input type="text" 
                                           class="form-control search-input" 
                                           placeholder="Search dates..." 
                                           id="search-dates">
                                    <button class="btn search-btn" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-container">
                        @if($customDates->count() > 0)
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Time Slot</th>
                                        <th>Duration</th>
                                        <th>Max Patients</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customDates as $customDate)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 500; color: #374151;">
                                                {{ $customDate->date->format('M d') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light">
                                               {{ strtoupper($customDate->date->format('D')) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.8rem; color: #6b7280;">
                                                {{ \Carbon\Carbon::parse($customDate->start_time)->format('h:i A') }} 
                                                <span class="text-muted">to</span>
                                                {{ \Carbon\Carbon::parse($customDate->end_time)->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $customDate->slot_duration }} min
                                            </span>
                                        </td>
                                        <td>
                                            @if($customDate->max_patients)
                                                <span class="badge badge-warning">
                                                    {{ $customDate->max_patients }}
                                                </span>
                                            @else
                                                <span style="color: #9ca3af; font-size: 0.8rem;">Unlimited</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($customDate->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" 
                                                        class="btn btn-outline-info btn-icon btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editDateModal"
                                                        data-date-id="{{ $customDate->id }}"
                                                        data-date="{{ $customDate->date->format('Y-m-d') }}"
                                                        data-start-time="{{ $customDate->start_time }}"
                                                        data-end-time="{{ $customDate->end_time }}"
                                                        data-slot-duration="{{ $customDate->slot_duration }}"
                                                        data-max-patients="{{ $customDate->max_patients }}"
                                                        data-is-active="{{ $customDate->is_active }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.chambers.custom-dates.update', $customDate) }}"
                                                      method="POST" class="d-inline toggle-form">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="is_active" value="{{ $customDate->is_active ? 0 : 1 }}">
                                                    <button type="submit" 
                                                            class="btn {{ $customDate->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} btn-icon btn-sm"
                                                            title="{{ $customDate->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas {{ $customDate->is_active ? 'fa-times' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.chambers.custom-dates.destroy', $customDate) }}"
                                                      method="POST" class="d-inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger btn-icon btn-sm"
                                                            onclick="return confirmDelete('{{ $customDate->date->format('M d, Y') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <h4 class="empty-state-title">No Custom Dates Added</h4>
                                <p class="empty-state-text">Start by adding your first custom date using the form on the left.</p>
                            </div>
                        @endif
                    </div>

                    @if($customDates->count() > 0)
                    <div class="p-3 border-top">
                        <div class="text-end">
                            <small class="text-muted">
                                Showing {{ $customDates->count() }} custom dates
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Date Modal -->
<div class="modal fade" id="editDateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Custom Date
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDateForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Date</label>
                            <input type="date" 
                                   name="date" 
                                   class="form-control" 
                                   id="edit-date" 
                                   readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Start Time</label>
                            <input type="time" 
                                   name="start_time" 
                                   class="form-control" 
                                   id="edit-start-time" 
                                   required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Time</label>
                            <input type="time" 
                                   name="end_time" 
                                   class="form-control" 
                                   id="edit-end-time" 
                                   required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Slot Duration</label>
                            <select name="slot_duration" 
                                    class="form-control" 
                                    id="edit-slot-duration" 
                                    required>
                                <option value="">Select</option>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">60 minutes</option>
                                <option value="90">90 minutes</option>
                                <option value="120">120 minutes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max Patients</label>
                            <input type="number" 
                                   name="max_patients" 
                                   class="form-control" 
                                   id="edit-max-patients" 
                                   min="1" 
                                   placeholder="Leave empty for unlimited">
                        </div>
                        <div class="form-group col-span-2">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       name="is_active" 
                                       class="form-check-input" 
                                       id="edit-is-active" 
                                       value="1">
                                <label class="form-check-label" for="edit-is-active">
                                    Active (Accepting appointments)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Date</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to tomorrow for add form
    const dateInput = document.getElementById('date');
    if (dateInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];

        // Set default value to tomorrow if empty
        if (!dateInput.value) {
            dateInput.value = tomorrow.toISOString().split('T')[0];
        }
    }

    // Set default slot duration if empty
    const slotDuration = document.getElementById('slot_duration');
    if (slotDuration && !slotDuration.value) {
        slotDuration.value = '30';
    }

    // Edit Date Modal
    const editDateModal = document.getElementById('editDateModal');
    if (editDateModal) {
        editDateModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const dateId = button.getAttribute('data-date-id');
            const date = button.getAttribute('data-date');
            const startTime = button.getAttribute('data-start-time');
            const endTime = button.getAttribute('data-end-time');
            const slotDuration = button.getAttribute('data-slot-duration');
            const maxPatients = button.getAttribute('data-max-patients');
            const isActive = button.getAttribute('data-is-active');

            const modal = this;
            modal.querySelector('#edit-date').value = date;
            modal.querySelector('#edit-start-time').value = startTime;
            modal.querySelector('#edit-end-time').value = endTime;
            modal.querySelector('#edit-slot-duration').value = slotDuration;
            modal.querySelector('#edit-max-patients').value = maxPatients || '';
            modal.querySelector('#edit-is-active').checked = isActive === '1';

            // Update form action
            modal.querySelector('#editDateForm').action = `/admin/chambers/custom-dates/${dateId}`;
        });
    }

    // Form validation for add form
    const addForm = document.getElementById('add-custom-date-form');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            const startTime = this.querySelector('#start_time').value;
            const endTime = this.querySelector('#end_time').value;
            const slotDuration = this.querySelector('#slot_duration').value;

            if (!startTime) {
                e.preventDefault();
                alert('Please select a start time.');
                return false;
            }

            if (!endTime) {
                e.preventDefault();
                alert('Please select an end time.');
                return false;
            }

            if (!slotDuration) {
                e.preventDefault();
                alert('Please select a slot duration.');
                return false;
            }

            if (startTime >= endTime) {
                e.preventDefault();
                alert('End time must be after start time.');
                return false;
            }
        });
    }

    // Search functionality
    const searchInput = document.getElementById('search-dates');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    }

    // Toggle active status with confirmation
    document.querySelectorAll('.toggle-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const isActive = this.querySelector('input[name="is_active"]').value;
            const action = isActive == 1 ? 'activate' : 'deactivate';

            if (confirm(`Are you sure you want to ${action} this date?`)) {
                this.submit();
            }
        });
    });

    // Delete confirmation
    window.confirmDelete = function(dateStr) {
        return confirm(`Are you sure you want to delete the date: ${dateStr}?`);
    };

    // Reset form
    const resetBtn = document.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            const form = this.closest('form');
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            if (form.querySelector('#date')) {
                form.querySelector('#date').value = tomorrow.toISOString().split('T')[0];
            }
            form.querySelector('#start_time').value = '09:00';
            form.querySelector('#end_time').value = '17:00';
            form.querySelector('#slot_duration').value = '30';
            form.querySelector('#max_patients').value = '';
            form.querySelector('#is_active').checked = true;
        });
    }

    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection