@extends('layouts.admin')
@section('title','Patient Profile')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ $patient->name }}</h5>
    </div>
    <div class="card-body">
        <p><strong>Email:</strong> {{ $patient->email ?? '-' }}</p>
        <p><strong>Mobile:</strong> {{ $patient->mobile ?? '-' }}</p>
        <p><strong>Joined:</strong> {{ optional($patient->created_at)->format('d M Y') }}</p>
    </div>
</div>
@endsection
