@extends('layouts.admin')
@section('title', 'Manage Chambers')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.08);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.12);
        --primary-border: rgba(49, 128, 105, 0.15);
    }

    /* Enhanced Chamber Stats */
    .chamber-stats {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(49, 128, 105, 0.1);
    }

    .chamber-stats .vr {
        opacity: 0.2;
    }

    .chamber-stats .h4 {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .chamber-stats .text-muted {
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .quick-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    /* Professional chamber cards */
    .chamber-card {
        border: 1px solid rgba(49, 128, 105, 0.1);
        border-radius: 20px;
        background: white;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.03);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .chamber-card:hover {
        transform: translateY(-6px);
        border-color: rgba(49, 128, 105, 0.3);
        box-shadow: 0 16px 40px rgba(49, 128, 105, 0.12);
    }

    .chamber-card .chamber-card-header {
        background: linear-gradient(135deg, var(--primary-soft), rgba(49, 128, 105, 0.03));
        border-bottom: 1px solid var(--primary-border);
        padding: 1.2rem;
        position: relative;
    }

    .chamber-card .chamber-card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--primary), transparent);
        opacity: 0.3;
    }

    .chamber-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.3;
    }

    /* Elegant Status Indicators */
    .status-indicator {
        padding: 0.4rem .6rem;
        border-radius: 35px;
        font-size: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        letter-spacing: 0.2px;
        text-transform: uppercase;
        position: relative;
        overflow: hidden;
    }

    .status-indicator::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: currentColor;
        opacity: 0.1;
        border-radius: 12px;
    }

    .status-active {
        color: #059669;
        border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .status-inactive {
        color: #dc2626;
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    /* Information Grid */
    .info-grid {
        display: grid;
        gap: 1rem;
        padding: 1rem;
    }

    .info-item {
        display: flex;
        gap: .7rem;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary-soft), transparent);
        border-radius: 12px;
        border: 1px solid rgba(49, 128, 105, 0.1);
        transition: all 0.3s ease;
    }

    .info-item:hover {
        border-color: var(--primary-border);
        background: linear-gradient(135deg, var(--primary-light), transparent);
        transform: translateX(4px);
    }

    .info-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: var(--primary);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.1);
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

   .info-value {
        font-size: 13px;
        color: #1e293b;
        font-weight: 400;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Enhanced Schedule Display */
    .schedule-section {
        padding: 1rem;
        border-top: 1px solid rgba(49, 128, 105, 0.08);
        background: linear-gradient(135deg, rgba(49, 128, 105, 0.02), transparent);
    }

    .schedule-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .schedule-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-soft));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1rem;
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .schedule-item {
        display: flex;
        align-items: center;
        flex-direction: column;
        gap: .5rem;
        padding: 0.6rem .8rem;
        background: white;
        border-radius: 10px;
        border: 1px solid rgba(49, 128, 105, 0.1);
        transition: all 0.2s ease;
    }

    .schedule-item:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
        transform: translateX(3px);
    }

    .day-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-soft));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--primary);
        flex-shrink: 0;
    }

    .time-display {
        flex: 1;
        font-size: 0.8rem;
        color: #475569;
        font-weight: 500;
    }

    /* Additional schedule items (hidden by default) */
    .schedule-more-items {
        display: none;
        grid-column: 1 / -1;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px dashed rgba(49, 128, 105, 0.15);
    }

    .schedule-more-items.show {
        display: grid;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Action Buttons */
    .action-bar {
        padding: 1.2rem;
        border-top: 1px solid rgba(49, 128, 105, 0.1);
        background: white;
        border-radius: 0 0 20px 20px;
    }

    .action-buttons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
        gap: 0.75rem;
    }

    .action-btn {
        border: none;
        border-radius: 12px;
        padding: 0.65rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: currentColor;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .action-btn:hover::before {
        opacity: 0.1;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-soft));
        color: var(--primary);
        border: 1px solid var(--primary-border);
    }

    .btn-dates {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .btn-delete {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    /* Schedule More Button */
    .btn-schedule-more {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.05));
        color: #6366f1;
        border: 1px solid rgba(99, 102, 241, 0.2);
        grid-column: 1 / -1;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-schedule-more i {
        transition: transform 0.3s ease;
    }

    .btn-schedule-more.expanded i {
        transform: rotate(180deg);
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, var(--primary-soft), transparent);
        border-radius: 20px;
        border: 2px dashed var(--primary-border);
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-soft));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--primary);
        font-size: 2.5rem;
        box-shadow: 0 8px 32px rgba(49, 128, 105, 0.12);
    }

    /* Floating Add Button */
    .floating-add-btn {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 8px 30px rgba(49, 128, 105, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        border: none;
    }

    .floating-add-btn:hover {
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 12px 40px rgba(49, 128, 105, 0.4);
    }

    /* Page Header */
    .page-header-section {
        margin-bottom: 1rem;
    }

    .page-header-section h1 {
        font-size: 1.5rem;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .chamber-stats .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .chamber-stats .vr {
            display: none;
        }
        
        .chamber-stats .col-md-8,
        .chamber-stats .col-md-4 {
            width: 100%;
            text-align: center;
        }
        
        .quick-actions {
            justify-content: center;
            margin-top: 1rem;
        }
        
        .action-buttons-grid {
            grid-template-columns: 1fr;
        }
        
        .schedule-grid {
            grid-template-columns: 1fr;
        }
        
        .schedule-more-items {
            grid-template-columns: 1fr;
        }
        
        .floating-add-btn {
            bottom: 1.5rem;
            right: 1.5rem;
            width: 56px;
            height: 56px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <h1>Chamber Management</h1>
</div>

<!-- Chamber Stats Section -->
<div class="chamber-stats">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-4">
                <div>
                    <div class="text-muted small mb-1">Total Chambers</div>
                    <div class="h4 fw-bold mb-0">{{ $chambers->count() }}</div>
                </div>
                <div class="vr"></div>
                <div>
                    <div class="text-muted small mb-1">Active</div>
                    <div class="h4 fw-bold mb-0 text-success">{{ $chambers->where('is_active', true)->count() }}</div>
                </div>
                <div class="vr"></div>
                <div>
                    <div class="text-muted small mb-1">Inactive</div>
                    <div class="h4 fw-bold mb-0 text-warning">{{ $chambers->where('is_active', false)->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="quick-actions justify-content-end">
                <a href="{{ route('admin.chambers.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Add New
                </a>
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i> Export</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chambers Grid -->
<div class="row g-4" id="chambersContainer">
    @forelse($chambers as $chamber)
    <div class="col-xl-4 col-lg-6 col-md-6 chamber-card-item">
        <div class="chamber-card">
            <!-- Chamber Header -->
            <div class="chamber-card-header">
                <div class="d-flex justify-content-between align-items-start">
                    <h3 class="chamber-title">{{ $chamber->name }}</h3>
                    <span class="status-indicator status-{{ $chamber->is_active ? 'active' : 'inactive' }}">
                        <i class="fas fa-circle" style="font-size: 8px;"></i>
                        {{ $chamber->is_active ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        Type: {{ ucfirst($chamber->type) }}
                    </span>
                </div>
            </div>

            <!-- Chamber Information -->
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Location</div>
                        <div class="info-value">{{ $chamber->address }}, {{ $chamber->city }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Consultation Fee</div>
                        <div class="info-value text-success">৳{{ number_format($chamber->fees) }}</div>
                    </div>
                </div>

                @if($chamber->phone)
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Contact</div>
                        <div class="info-value">{{ $chamber->phone }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Schedule Preview -->
            @if($chamber->type === 'fixed' && isset($chamber->schedule))
            <div class="schedule-section">
                <div class="schedule-header">
                    <div class="schedule-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="info-label">Weekly Schedule</div>
                        <small class="text-muted">Regular operating hours</small>
                    </div>
                </div>
                
                @php
                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    $activeDays = collect($days)
                        ->filter(fn($day) => $chamber->schedule[$day]['enabled'] ?? false);
                    $initialDays = $activeDays->take(2);
                    $remainingDays = $activeDays->slice(2);
                @endphp

                <div class="schedule-grid">
                    @foreach($initialDays as $day)
                    <div class="schedule-item">
                        <div class="day-circle">
                            {{ substr(strtoupper($day), 0, 3) }}
                        </div>
                        <div class="time-display">
                            {{ $chamber->schedule[$day]['start_time'] }} - {{ $chamber->schedule[$day]['end_time'] }}
                        </div>
                    </div>
                    @endforeach

                    @if($remainingDays->count() > 0)
                        <!-- Hidden additional days -->
                        <div class="schedule-more-items" id="moreDays{{ $chamber->id }}">
                            @foreach($remainingDays as $day)
                            <div class="schedule-item">
                                <div class="day-circle">
                                    {{ substr(strtoupper($day), 0, 3) }}
                                </div>
                                <div class="time-display">
                                    {{ $chamber->schedule[$day]['start_time'] }} - {{ $chamber->schedule[$day]['end_time'] }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Show More Button - ALWAYS at the END of schedule-grid -->
                        <button class="action-btn btn-schedule-more" onclick="toggleScheduleDays({{ $chamber->id }})" id="scheduleBtn{{ $chamber->id }}">
                            <i class="fas fa-chevron-down"></i>
                            <span>Show {{ $remainingDays->count() }} more days</span>
                        </button>
                    @endif
                </div>
            </div>
            @elseif($chamber->type === 'custom')
            <div class="schedule-section">
                <div class="schedule-header">
                    <div class="schedule-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <div class="info-label">Custom Schedule</div>
                        <small class="text-muted">Special appointment dates</small>
                    </div>
                </div>
                <div class="text-center py-3">
                    <span class="badge bg-info bg-opacity-10 text-info border-1 border-info border-opacity-25 px-3 py-2 rounded-pill">
                        <i class="fas fa-calendar-check me-1"></i>
                        Custom dates configured
                    </span>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-bar">
                <div class="action-buttons-grid">
                    <a href="{{ route('admin.chambers.edit', $chamber) }}"
                       class="action-btn btn-edit">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </a>

                    @if($chamber->type === 'custom')
                    <a href="{{ route('admin.chambers.custom-dates', $chamber) }}"
                       class="action-btn btn-dates">
                        <i class="fas fa-calendar-day"></i>
                        <span>Dates</span>
                    </a>
                    @endif

                    <button type="button"
                            class="action-btn btn-delete"
                            onclick="confirmDelete({{ $chamber->id }}, '{{ addslashes($chamber->name) }}')">
                        <i class="fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="col-12">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-building"></i>
            </div>
            <h3 class="fw-bold mb-3">No Chambers Found</h3>
            <p class="text-muted mb-4 px-4">Start by adding your first clinic chamber to manage appointments and schedules</p>
            <a href="{{ route('admin.chambers.create') }}" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold">
                <i class="fas fa-plus me-2"></i>Add Your First Chamber
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Floating Add Button (Mobile) -->
<a href="{{ route('admin.chambers.create') }}" class="floating-add-btn d-lg-none">
    <i class="fas fa-plus"></i>
</a>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom p-3">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-filter me-2"></i>Filter Chambers
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="filterForm" method="GET" action="{{ request()->url() }}">
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select class="form-select" name="status" id="filterStatus">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Schedule Type</label>
                        <select class="form-select" name="type" id="filterType">
                            <option value="">All Types</option>
                            <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed Schedule</option>
                            <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom Schedule</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">City</label>
                        <select class="form-select" name="city" id="filterCity">
                            <option value="">All Cities</option>
                            @php
                                $uniqueCities = $chambers->pluck('city')->unique()->filter()->values();
                            @endphp
                            @foreach($uniqueCities as $city)
                                @if($city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">Clear</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Chamber
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-trash-alt display-4 text-danger mb-3"></i>
                <h5 class="fw-semibold mb-3">Delete <span id="chamberName" class="text-danger"></span>?</h5>
                <p class="text-muted mb-4">This action cannot be undone. All associated appointments and schedules will be permanently deleted.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-3 px-4">
                        <i class="fas fa-trash me-1"></i>Delete Chamber
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Delete Confirmation
function confirmDelete(chamberId, chamberName) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/chambers/${chamberId}`;
    document.getElementById('chamberName').textContent = chamberName;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Toggle schedule days visibility - FIXED VERSION
function toggleScheduleDays(chamberId) {
    const moreItems = document.getElementById(`moreDays${chamberId}`);
    const button = document.getElementById(`scheduleBtn${chamberId}`);
    
    if (!moreItems || !button) return;
    
    if (moreItems.classList.contains('show')) {
        moreItems.classList.remove('show');
        button.innerHTML = '<i class="fas fa-chevron-down"></i><span>Show ' + moreItems.children.length + ' more days</span>';
        button.classList.remove('expanded');
    } else {
        moreItems.classList.add('show');
        button.innerHTML = '<i class="fas fa-chevron-up"></i><span>Show less</span>';
        button.classList.add('expanded');
    }
}

// Filter functionality
function applyFilters() {
    const status = document.getElementById('filterStatus').value;
    const type = document.getElementById('filterType').value;
    const city = document.getElementById('filterCity').value;
    
    // Get all chamber cards
    const chamberCards = document.querySelectorAll('.chamber-card-item');
    let visibleCount = 0;
    
    chamberCards.forEach(card => {
        let show = true;
        
        // Get chamber data from the card
        const chamberStatus = card.querySelector('.status-indicator').classList.contains('status-active') ? 'active' : 'inactive';
        const chamberType = card.querySelector('.text-muted').textContent.includes('Fixed') ? 'fixed' : 'custom';
        const chamberCity = card.querySelector('.info-value').textContent.split(',').pop().trim();
        
        // Apply filters
        if (status && chamberStatus !== status) show = false;
        if (type && chamberType !== type) show = false;
        if (city && chamberCity !== city) show = false;
        
        // Show/hide card
        card.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    // Show/hide empty state
    const emptyState = document.querySelector('.empty-state');
    if (emptyState) {
        emptyState.closest('.col-12').style.display = visibleCount === 0 ? '' : 'none';
    }
    
    // Update stats
    updateFilteredStats();
    
    // Close modal
    const filterModal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
    filterModal.hide();
}

function clearFilters() {
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterCity').value = '';
    
    // Show all cards
    const chamberCards = document.querySelectorAll('.chamber-card-item');
    chamberCards.forEach(card => {
        card.style.display = '';
    });
    
    // Hide empty state if it exists
    const emptyState = document.querySelector('.empty-state');
    if (emptyState && emptyState.closest('.col-12')) {
        emptyState.closest('.col-12').style.display = 'none';
    }
    
    // Close modal
    const filterModal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
    filterModal.hide();
    
    // Reset URL without filters
    window.history.pushState({}, document.title, window.location.pathname);
}

function updateFilteredStats() {
    // Count visible chambers
    const visibleChambers = document.querySelectorAll('.chamber-card-item[style*="display: none"]');
    const totalVisible = document.querySelectorAll('.chamber-card-item').length - visibleChambers.length;
    const activeVisible = Array.from(document.querySelectorAll('.chamber-card-item:not([style*="display: none"])'))
        .filter(card => card.querySelector('.status-indicator').classList.contains('status-active')).length;
    const inactiveVisible = totalVisible - activeVisible;
    
    // Update stats display
    document.querySelector('.chamber-stats .h4:nth-child(1)').textContent = totalVisible;
    document.querySelector('.chamber-stats .h4:nth-child(2)').textContent = activeVisible;
    document.querySelector('.chamber-stats .h4:nth-child(3)').textContent = inactiveVisible;
}

// Enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add animation to chamber cards on load
    const chamberCards = document.querySelectorAll('.chamber-card');
    chamberCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add hover effect to stats section
    const statsSection = document.querySelector('.chamber-stats');
    if (statsSection) {
        statsSection.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px rgba(49, 128, 105, 0.12)';
        });
        
        statsSection.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.04)';
        });
    }

    // Handle filter form submission
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
            
            // Update URL with filter parameters
            const params = new URLSearchParams();
            if (document.getElementById('filterStatus').value) {
                params.set('status', document.getElementById('filterStatus').value);
            }
            if (document.getElementById('filterType').value) {
                params.set('type', document.getElementById('filterType').value);
            }
            if (document.getElementById('filterCity').value) {
                params.set('city', document.getElementById('filterCity').value);
            }
            
            const queryString = params.toString();
            const newUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
            window.history.pushState({}, document.title, newUrl);
        });
    }

    // Apply existing filters from URL on page load
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('status') || urlParams.has('type') || urlParams.has('city')) {
        setTimeout(() => {
            if (urlParams.get('status')) {
                document.getElementById('filterStatus').value = urlParams.get('status');
            }
            if (urlParams.get('type')) {
                document.getElementById('filterType').value = urlParams.get('type');
            }
            if (urlParams.get('city')) {
                document.getElementById('filterCity').value = urlParams.get('city');
            }
            applyFilters();
        }, 100);
    }
});
</script>
@endsection