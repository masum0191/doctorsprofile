@extends('layouts.supperadmin')
@section('title','Create Marketing Contact')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between py-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Create Marketing Contact</h1>
            <p class="text-muted mb-0">
                Add a new contact for marketing campaigns and segmentation.
            </p>
        </div>
        <a href="{{ route('superadmin.marketing.contacts.index') }}"
           class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i>
            Back to Contacts
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Card --}}
    <div class="card border-0 shadow-sm">

        {{-- Card Header --}}
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-user-add-line text-primary me-1"></i>
                Contact Information
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body">
            <form action="{{ route('superadmin.marketing.contacts.store') }}" method="POST">
                @csrf

                <div class="row g-4">

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Specialty --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Specialty</label>
                        <input type="text"
                               name="specialty"
                               class="form-control @error('specialty') is-invalid @enderror"
                               value="{{ old('specialty') }}">
                        @error('specialty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone / WhatsApp</label>
                        <input type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Company --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Company / Organization</label>
                        <input type="text"
                               name="company"
                               class="form-control @error('company') is-invalid @enderror"
                               value="{{ old('company') }}">
                        @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- City --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text"
                               name="city"
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city') }}">
                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Position --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Position / Designation</label>
                        <input type="text"
                               name="position"
                               class="form-control @error('position') is-invalid @enderror"
                               value="{{ old('position') }}">
                        @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status','active') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Notes --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes"
                                  rows="4"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Additional information about this contact">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('superadmin.marketing.contacts.index') }}"
                       class="btn btn-light">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Create Contact
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
