@extends('layouts.admin')
@section('title','Visit History')

@section('content')
<h5 class="mb-3">Visit History – {{ $patient->name }}</h5>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @forelse($appointments as $appt)
        <tr>
            <td>{{ optional($appt->appointment_date)->format('d M Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
            <td>{{ ucfirst($appt->status) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center text-muted">No visit history</td>
        </tr>
    @endforelse
    </tbody>
</table>
@endsection
