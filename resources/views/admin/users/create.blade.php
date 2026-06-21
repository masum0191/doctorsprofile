@extends('layouts.supperadmin')

@section('title', 'Create User')
@section('page-title', 'Create User')
@section('page-subtitle', 'Add a central account and assign its access role.')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">New User</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.users.store') }}" method="POST">
                @csrf
                @include('admin.users._form')
            </form>
        </div>
    </div>
@endsection
