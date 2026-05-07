@extends('layouts.admin')
@section('title','Medical Records')

@section('content')
<h5 class="mb-3">Medical Records – {{ $patient->name }}</h5>

@forelse($prescriptions as $p)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between">
        <span>
            <strong>Date:</strong> {{ optional($p->prescribed_date)->format('d M Y') }}
        </span>
        <span>
            <strong>Doctor:</strong> {{ $p->doctor->name }}
        </span>
    </div>

    <div class="card-body">
        <p><strong>Diagnosis:</strong> {{ $p->diagnosis ?? '-' }}</p>
        <p><strong>Instructions:</strong> {{ $p->instructions ?? '-' }}</p>

        {{-- MEDICINES --}}
        <h6>Medicines</h6>
        
        @if($p->medicines->count())
            <ul>
                @foreach($p->medicines as $m)
                    <li>{{ $m->medicine_name }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No medicines</p>
        @endif

        {{-- TESTS --}}
        <h6>Tests</h6>
        @if($p->tests->count())
            <ul>
                @foreach($p->tests as $t)
                    <li>{{ $t->test_name }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No tests</p>
        @endif
    </div>
</div>
@empty
<p class="text-muted">No medical records found.</p>
@endforelse
@endsection
