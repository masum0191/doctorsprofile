@extends('layouts.supperadmin')
@section('title','Marketing Contacts')
@section('content')
<style>
    /* Stats Cards - Matching Dashboard */
    .stats-card {
        border-radius: 12px;
        transition: transform 0.2s ease;
        overflow: hidden;
        position: relative;
    }

    .stats-card:hover {
        transform: translateY(-3px);
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .bg-white-20 {
        background: rgb(220 219 219 / 33%);
        backdrop-filter: blur(10px);
    }

    /* Gradient Backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #318069 0%, #2a6d5a 100%) !important;
        color: white;
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;
        color: white;
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%) !important;
        color: white;
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #3B82F6 0%, #2563eb 100%) !important;
        color: white;
    }

    /* Enhanced Table */
    .table {
        --bs-table-bg: transparent;
        --bs-table-striped-bg: #f8f9fa;
        --bs-table-hover-bg: #f1f5f9;
        margin-bottom: 0;
    }

    .table thead th {
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .table tbody td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Contact Info */
    .contact-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: white;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Badges */
    .badge {
        padding: 0.35rem 0.75rem;
        font-weight: 500;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .channel-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }

        .stats-card .h3 {
            font-size: 1.5rem;
        }

        .contact-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .contact-avatar {
            display: none;
        }
    }
</style>

<div class="pb-3">
    <!-- Enhanced Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm bg-gradient-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Total Contacts</div>
                            <div class="h3 mb-0 fw-bold">{{ $contacts->total() }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-users me-1"></i> All doctors
                            </div>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fas fa-address-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm bg-gradient-success">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Email Opt-ins</div>
                            <div class="h3 mb-0 fw-bold">{{ $contacts->where('opt_in_email', true)->count() }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-envelope me-1"></i> {{ number_format($contacts->where('opt_in_email', true)->count() / max($contacts->count(), 1) * 100, 1) }}% opted in
                            </div>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm bg-gradient-warning">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">WhatsApp Opt-ins</div>
                            <div class="h3 mb-0 fw-bold">{{ $contacts->where('opt_in_whatsapp', true)->count() }}</div>
                            <div class="small mt-2">
                                <i class="fab fa-whatsapp me-1"></i> {{ number_format($contacts->where('opt_in_whatsapp', true)->count() / max($contacts->count(), 1) * 100, 1) }}% opted in
                            </div>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm bg-gradient-info">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Active Campaigns</div>
                            <div class="h3 mb-0 fw-bold">12</div>
                            <div class="small mt-2">
                                <i class="fas fa-chart-line me-1"></i> 8% growth
                            </div>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold">Doctor Marketing Contacts</h1>
            <p class="text-muted mb-0">Filter and manage doctor list for WhatsApp & Email campaigns</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.marketing.contacts.create') }}" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-plus me-2"></i>Add Contact
            </a>
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                {{-- <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i> Export</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                </ul> --}}
            </div>
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($contacts->isEmpty())
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-address-book fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-600 mb-2">No Contacts Found</h5>
                        <p class="text-gray-400 mb-3">Start by adding your first marketing contact</p>
                        <a href="{{ route('superadmin.marketing.contacts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Contact
                        </a>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">CONTACT</th>
                                <th>LOCATION</th>
                                <th>SPECIALTY</th>
                                <th>EMAIL</th>
                                <th>WHATSAPP</th>
                                <th class="text-end pe-4">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr class="border-bottom">
                                    <td class="ps-3">
                                        <div class="contact-info">
                                            <div class="contact-avatar" style="background: linear-gradient(135deg, #318069 0%, #2a6d5a 100%);">
                                                {{ strtoupper(substr($contact->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold mb-1">{{ $contact->name }}</div>
                                                <small class="text-muted">
                                                    @if($contact->bmdc_no)
                                                        BMDC: {{ $contact->bmdc_no }}
                                                    @else
                                                        BMDC: —
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($contact->city)
                                            <div class="fw-medium">{{ $contact->city }}</div>
                                            <small class="text-muted">{{ $contact->country ?? 'Bangladesh' }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($contact->specialty)
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                {{ $contact->specialty }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small mb-1">
                                            @if($contact->email)
                                                {{ $contact->email }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                        <div>
                                            @if($contact->opt_in_email)
                                                <span class="channel-badge bg-success bg-opacity-10 text-success">
                                                    <i class="fas fa-check-circle"></i> Opted In
                                                </span>
                                            @else
                                                <span class="channel-badge bg-secondary bg-opacity-10 text-secondary">
                                                    <i class="fas fa-times-circle"></i> Not Opted
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small mb-1">
                                            @if($contact->whatsapp)
                                                {{ $contact->whatsapp }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                        <div>
                                            @if($contact->opt_in_whatsapp)
                                                <span class="channel-badge bg-success bg-opacity-10 text-success">
                                                    <i class="fas fa-check-circle"></i> Opted In
                                                </span>
                                            @else
                                                <span class="channel-badge bg-secondary bg-opacity-10 text-secondary">
                                                    <i class="fas fa-times-circle"></i> Not Opted
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('superadmin.marketing.contacts.show', $contact) }}"
                                               class="act-btn upload-btn"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- <a href=""
                                               class="act-btn edit-btn"
                                               title="Edit Contact">
                                                <i class="fas fa-pen"></i>
                                            </a> --}}
                                            <button type="button"
                                                    class="act-btn delete-btn"
                                                    onclick="confirmContactDelete('{{ $contact->id }}', '{{ addslashes($contact->name) }}')"
                                                    title="Delete Contact">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if(!$contacts->isEmpty())
        <div class="card-footer border-top bg-white py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">{{ $contacts->firstItem() }}–{{ $contacts->lastItem() }}</span>
                    of <span class="fw-semibold">{{ $contacts->total() }}</span> contacts
                </div>
                @if($contacts->hasPages())
                <div>
                    {{ $contacts->links('vendor.pagination.bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom p-3">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-filter me-2"></i>Filter Contacts
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('superadmin.marketing.contacts.index') }}">
                <div class="modal-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Search</label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   value="{{ request('search') }}"
                                   placeholder="Name, email, phone, BMDC...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">City</label>
                            <input type="text"
                                   name="city"
                                   class="form-control"
                                   value="{{ request('city') }}"
                                   placeholder="e.g., Dhaka, Chittagong...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Specialty</label>
                            <input type="text"
                                   name="specialty"
                                   class="form-control"
                                   value="{{ request('specialty') }}"
                                   placeholder="e.g., Cardiology, Neurology...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Channel Opt-in</label>
                            <select name="channel" class="form-select">
                                <option value="">All Channels</option>
                                <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email Opt-in Only</option>
                                <option value="whatsapp" {{ request('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp Opt-in Only</option>
                                <option value="both" {{ request('channel') == 'both' ? 'selected' : '' }}>Both Opt-in</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="deleteContactForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" id="actualContactId">
                <div class="modal-header border-bottom p-3">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Delete Contact
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="alert alert-warning border-0 py-2 mb-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        This action cannot be undone.
                    </div>

                    <p id="deleteContactText" class="mb-3"></p>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            Type <code id="contactNameDisplay" class="text-danger">CONTACT-NAME</code> to confirm:
                        </label>
                        <input type="text"
                               id="contactNameInput"
                               class="form-control"
                               placeholder="Enter contact name"
                               autocomplete="off">
                        <div class="invalid-feedback small" id="contactError">Contact name does not match.</div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-danger"
                            id="deleteContactButton"
                            disabled>
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    const deleteContactModal = new bootstrap.Modal(document.getElementById('deleteContactModal'));
    const filterModal = new bootstrap.Modal(document.getElementById('filterModal'));

    // Delete confirmation functionality
    const contactNameInput = document.getElementById('contactNameInput');
    const deleteContactButton = document.getElementById('deleteContactButton');
    const contactError = document.getElementById('contactError');
    const contactNameDisplay = document.getElementById('contactNameDisplay');

    // Global confirmContactDelete function
    window.confirmContactDelete = function(id, name) {
        const form = document.getElementById('deleteContactForm');
        form.setAttribute('action', `/superadmin/marketing/contacts/${id}`);
        document.getElementById('actualContactId').value = id;

        // Update modal text
        document.getElementById('deleteContactText').innerHTML =
            `Are you sure you want to delete contact <strong>${name}</strong>?`;
        contactNameDisplay.textContent = name;

        // Reset inputs
        contactNameInput.value = '';
        contactNameInput.classList.remove('is-invalid', 'is-valid');
        deleteContactButton.disabled = true;
        contactError.style.display = 'none';

        // Show modal
        deleteContactModal.show();

        // Focus on name input
        setTimeout(() => {
            contactNameInput.focus();
        }, 300);
    };

    // Validate contact name input
    function validateContactDeleteForm() {
        const enteredName = contactNameInput.value.trim();
        const actualName = contactNameDisplay.textContent.trim();

        let isValid = true;
        let errorMessage = '';

        if (enteredName === '') {
            contactNameInput.classList.remove('is-invalid', 'is-valid');
            isValid = false;
        } else if (enteredName !== actualName) {
            contactNameInput.classList.remove('is-valid');
            contactNameInput.classList.add('is-invalid');
            errorMessage = `Name must be exactly "${actualName}"`;
            isValid = false;
        } else {
            contactNameInput.classList.remove('is-invalid');
            contactNameInput.classList.add('is-valid');
        }

        if (errorMessage) {
            contactError.textContent = errorMessage;
            contactError.style.display = 'block';
        } else {
            contactError.style.display = 'none';
        }

        deleteContactButton.disabled = !isValid;
        return isValid;
    }

    // Event listeners
    contactNameInput.addEventListener('input', validateContactDeleteForm);

    document.getElementById('deleteContactForm').addEventListener('submit', function(e) {
        const enteredName = contactNameInput.value.trim();
        const actualName = contactNameDisplay.textContent.trim();

        if (enteredName !== actualName) {
            e.preventDefault();
            validateContactDeleteForm();
            contactNameInput.focus();
        }
    });

    // Quick filter from stats cards (optional feature)
    document.querySelectorAll('.stats-card').forEach(card => {
        card.addEventListener('click', function() {
            const cardText = this.querySelector('.small.mb-1').textContent.trim();

            if (cardText.includes('Email')) {
                window.location.href = "{{ route('superadmin.marketing.contacts.index') }}?channel=email";
            } else if (cardText.includes('WhatsApp')) {
                window.location.href = "{{ route('superadmin.marketing.contacts.index') }}?channel=whatsapp";
            }
        });
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + F to open filter modal
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            filterModal.show();
        }

        // Escape to close modals
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                const modal = bootstrap.Modal.getInstance(openModal);
                modal.hide();
            }
        }
    });
});
</script>
@endsection
