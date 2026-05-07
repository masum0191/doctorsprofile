@extends('layouts.supperadmin')
@section('title', 'Medical Specialties')

@section('content')

<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #276854;
        --primary-soft: rgba(49, 128, 105, 0.05);
    }

    /* Table Styles */
    .data-table {
        border-radius: 12px;
        overflow: hidden;
    }

    .data-table thead th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 1rem;
        border-bottom: 2px solid var(--primary-light);
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #e5e7eb;
    }

    .data-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .data-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #1f2937;
        font-size: 0.875rem;
    }

    /* Icon Display */
    .specialty-icon {
        width: 35px;
        height: 35px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.2s ease;
    }

    .specialty-icon:hover {
        transform: scale(1.1);
    }

    /* Description Text */
    .description-text {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
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
        border: 1px solid transparent;
    }

    .action-btn.edit {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .action-btn.edit:hover {
        background: #f59e0b;
        color: white;
        transform: translateY(-2px);
    }

    .action-btn.delete {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .action-btn.delete:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    /* Modal Enhancement */
    .modal-content {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-radius: 0 0 16px 16px;
    }

    /* Form Enhancement */
    .form-label {
        font-weight: 600;
        font-size: 0.8rem;
        color: #374151;
        margin-bottom: 0.375rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    input[type="color"] {
        height: 48px;
        padding: 0.25rem;
        cursor: pointer;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2.5rem;
        color: var(--primary);
    }

    /* ID Badge */
    .id-badge {
        background: #f3f4f6;
        color: #6b7280;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .data-table thead {
            display: none;
        }
        
        .data-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
        }
        
        .data-table tbody td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px dashed #e5e7eb;
        }
        
        .data-table tbody td:last-child {
            border-bottom: none;
        }
        
        .data-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--primary);
            margin-right: 1rem;
            font-size: 0.8rem;
        }
        
        .description-text {
            max-width: 200px;
        }
    }
</style>

{{-- Page Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-gray-800">
            <i class="ri-stethoscope-line text-primary me-2"></i>
            Medical Specialties
        </h4>
        <p class="text-muted mb-0 small">Manage doctor specialties and classifications</p>
    </div>
    <button class="btn btn-primary d-inline-flex align-items-center gap-2 px-4" 
            data-bs-toggle="modal" 
            data-bs-target="#createSpecialtyModal">
        <i class="ri-add-line"></i>
        <span>New Specialty</span>
    </button>
</div>

{{-- Specialties List Card --}}
<div class="card border-0 shadow-sm">
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table table mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px"># ID</th>
                        <th style="width: 200px">Specialty Name</th>
                        <th style="width: 80px">Icon</th>
                        <th>Description</th>
                        <th style="width: 100px" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($specialties as $specialty)
                    <tr>
                        <td data-label="# ID">
                            <span class="id-badge">#{{ $specialty->id }}</span>
                        </td>
                        <td data-label="Specialty Name">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: var(--primary-light);">
                                    <i class="ri-stethoscope-line text-primary" style="font-size: 0.9rem;"></i>
                                </div>
                                <span class="fw-semibold text-gray-800">{{ $specialty->name }}</span>
                            </div>
                        </td>
                        <td data-label="Icon">
                            <div class="specialty-icon" style="background: {{ $specialty->color ?? '#e8f5e9' }}; color: {{ $specialty->color ? '#fff' : '#318069' }}">
                                <i class="ri-{{ $specialty->icon }}-line"></i>
                            </div>
                        </td>
                        <td data-label="Description">
                            <span class="description-text text-muted">
                                {{ $specialty->description ?? '—' }}
                            </span>
                        </td>
                        <td data-label="Actions" class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="action-btn edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSpecialtyModal{{ $specialty->id }}"
                                        title="Edit Specialty">
                                    <i class="ri-edit-line"></i>
                                </button>

                                <form method="POST"
                                      action="{{ route('superadmin.specialties.destroy', $specialty->id) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn delete border-0"
                                            onclick="return confirm('Are you sure you want to delete "{{ $specialty->name }}" specialty?')"
                                            title="Delete Specialty">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- EDIT MODAL --}}
                    <div class="modal fade" id="editSpecialtyModal{{ $specialty->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('superadmin.specialties.update', $specialty->id) }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="modal-header">
                                        <div>
                                            <h5 class="modal-title mb-0">Edit Specialty</h5>
                                            <small class="opacity-75">Update specialty information</small>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-stethoscope-line me-1"></i> Specialty Name
                                                </label>
                                                <input type="text" name="name" value="{{ $specialty->name }}" 
                                                       class="form-control" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-icons-line me-1"></i> Icon
                                                </label>
                                                <select name="icon" class="form-select">
                                                    <option value="">Select Icon</option>
                                                    <optgroup label="Medical Icons">
                                                        <option value="stethoscope" @selected($specialty->icon == 'stethoscope')>🩺 Stethoscope</option>
                                                        <option value="heart" @selected($specialty->icon == 'heart')>❤️ Heart</option>
                                                        <option value="brain" @selected($specialty->icon == 'brain')>🧠 Brain</option>
                                                        <option value="bone" @selected($specialty->icon == 'bone')>🦴 Bone</option>
                                                        <option value="lungs" @selected($specialty->icon == 'lungs')>🫁 Lungs</option>
                                                        <option value="tooth" @selected($specialty->icon == 'tooth')>🦷 Tooth</option>
                                                        <option value="eye" @selected($specialty->icon == 'eye')>👁️ Eye</option>
                                                        <option value="syringe" @selected($specialty->icon == 'syringe')>💉 Syringe</option>
                                                        <option value="pill" @selected($specialty->icon == 'pill')>💊 Pill</option>
                                                        <option value="bandage" @selected($specialty->icon == 'bandage')>🩹 Bandage</option>
                                                        <option value="thermometer" @selected($specialty->icon == 'thermometer')>🌡️ Thermometer</option>
                                                        <option value="microscope" @selected($specialty->icon == 'microscope')>🔬 Microscope</option>
                                                    </optgroup>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-palette-line me-1"></i> Color
                                                </label>
                                                <input type="color" name="color" class="form-control" 
                                                       value="{{ $specialty->color ?? '#318069' }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-hashtag-line me-1"></i> Display Order
                                                </label>
                                                <input type="number" name="order" value="{{ $specialty->order ?? 0 }}" 
                                                       class="form-control" placeholder="0">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">
                                                    <i class="ri-file-text-line me-1"></i> Description
                                                </label>
                                                <textarea name="description" rows="3" class="form-control" 
                                                          placeholder="Describe this medical specialty...">{{ $specialty->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="ri-save-3-line me-1"></i>
                                            Update Specialty
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="ri-stethoscope-line"></i>
                                </div>
                                <h5 class="fw-semibold text-gray-800 mb-2">No Specialties Found</h5>
                                <p class="text-muted mb-4">Get started by creating your first medical specialty</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSpecialtyModal">
                                    <i class="ri-add-line me-1"></i> Create Specialty
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($specialties->total() > 0)
    <div class="card-footer bg-white border-top p-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Showing {{ $specialties->firstItem() ?? 0 }} to {{ $specialties->lastItem() ?? 0 }} of {{ $specialties->total() }} specialties
            </div>
            <div>
                {{ $specialties->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif
</div>

{{-- CREATE MODAL --}}
<div class="modal fade" id="createSpecialtyModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('superadmin.specialties.store') }}">
                @csrf
                
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Create New Specialty</h5>
                        <small class="opacity-75">Add a new medical specialty</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-stethoscope-line me-1"></i> Specialty Name
                            </label>
                            <input type="text" name="name" class="form-control" 
                                   placeholder="e.g., Cardiology, Neurology" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-icons-line me-1"></i> Icon
                            </label>
                            <select name="icon" class="form-select">
                                <option value="">Select Icon</option>
                                <optgroup label="Medical Icons">
                                    <option value="stethoscope">🩺 Stethoscope</option>
                                    <option value="heart">❤️ Heart</option>
                                    <option value="brain">🧠 Brain</option>
                                    <option value="bone">🦴 Bone</option>
                                    <option value="lungs">🫁 Lungs</option>
                                    <option value="tooth">🦷 Tooth</option>
                                    <option value="eye">👁️ Eye</option>
                                    <option value="syringe">💉 Syringe</option>
                                    <option value="pill">💊 Pill</option>
                                    <option value="bandage">🩹 Bandage</option>
                                    <option value="thermometer">🌡️ Thermometer</option>
                                    <option value="microscope">🔬 Microscope</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-palette-line me-1"></i> Color
                            </label>
                            <input type="color" name="color" class="form-control" value="#318069">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-hashtag-line me-1"></i> Display Order
                            </label>
                            <input type="number" name="order" class="form-control" value="0" placeholder="0">
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                <i class="ri-file-text-line me-1"></i> Description
                            </label>
                            <textarea name="description" rows="3" class="form-control" 
                                      placeholder="Describe this medical specialty..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>
                        Add Specialty
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Add data-label attributes for responsive table
    document.addEventListener('DOMContentLoaded', function() {
        const tableHeaders = document.querySelectorAll('.data-table thead th');
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (tableHeaders[index]) {
                    const headerText = tableHeaders[index].textContent.trim();
                    cell.setAttribute('data-label', headerText);
                }
            });
        });
    });
</script>
@endpush