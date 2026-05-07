@extends('layouts.supperadmin')
@section('title','Contact Details')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between py-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Contact Details</h1>
            <p class="text-muted mb-0">
                View complete information of the selected contact.
            </p>
        </div>
        <a href="{{ url()->previous() }}"
           class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i>
            Back
        </a>
    </div>

    {{-- Card --}}
    <div class="card border-0 shadow-sm">

        {{-- Card Header --}}
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-user-line text-primary me-1"></i>
                Contact Information
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-0">

            <table class="table table-bordered table-striped mb-0">
                <tbody>

                    <tr>
                        <th class="bg-light" style="width: 25%">Name</th>
                        <td>{{ $contact->name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">Email</th>
                        <td>{{ $contact->email ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">Phone</th>
                        <td>{{ $contact->phone ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">Subject</th>
                        <td>{{ $contact->subject ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light align-top">Message</th>
                        <td style="white-space: pre-wrap;">
                            {{ $contact->message ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="bg-light">Created At</th>
                        <td>
                            {{ optional($contact->created_at)->format('Y-m-d H:i') ?? '—' }}
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
