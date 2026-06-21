@extends('layouts.supperadmin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update account information, role, status, or password.')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-1 fw-bold">{{ $managedUser->name }}</h5>
                <div class="text-muted small">{{ $managedUser->email }}</div>
            </div>
            <span class="badge {{ (int) $managedUser->status === 1 ? 'bg-success' : 'bg-secondary' }}">
                {{ (int) $managedUser->status === 1 ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.users.update', $managedUser) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.users._form')
            </form>
        </div>
    </div>
@endsection
