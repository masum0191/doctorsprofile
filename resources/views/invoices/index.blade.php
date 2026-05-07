@extends('layouts.admin')
@section('title','Invoices')

@section('content')

{{-- PAGE HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Invoice Management</h4>
        <small class="text-muted">Create and manage patient invoices</small>
    </div>
</div>

{{-- CREATE INVOICE --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light fw-semibold">
        <i class="ri-file-add-line me-1"></i> Create New Invoice
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.invoices.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Doctor</label>
                    <select name="doctor_id" class="form-select" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Patient</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Amount</label>
                    <input type="number"
                           step="0.01"
                           name="amount"
                           class="form-control"
                           placeholder="৳ 0.00"
                           required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Date</label>
                    <input type="date"
                           name="date"
                           class="form-control"
                           value="{{ now()->format('Y-m-d') }}"
                           required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Purpose</label>
                    <input type="text"
                           name="purpose"
                           class="form-control"
                           placeholder="Consultation">
                </div>

                <div class="col-md-12 text-end mt-2">
                    <button class="btn btn-primary px-4">
                        <i class="ri-save-line me-1"></i> Save Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- INVOICE LIST --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-light fw-semibold">
        <i class="ri-file-list-3-line me-1"></i> Invoice List
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Patient</th>
                    <th>Purpose</th>
                    <th class="text-end">Amount</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ optional($invoice->date)->format('d M Y') }}</td>
                    <td>{{ $invoice->doctor?->name }}</td>
                    <td>{{ $invoice->patient?->name }}</td>
                    <td>{{ $invoice->purpose ?? '—' }}</td>
                    <td class="text-end fw-semibold">
                        ৳ {{ number_format($invoice->amount,2) }}
                    </td>
                    <td class="text-end">

                        <a href="{{ route('admin.invoices.show',$invoice->id) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-info">
                            <i class="ri-eye-line"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.invoices.destroy',$invoice->id) }}"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this invoice?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        No invoices found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
