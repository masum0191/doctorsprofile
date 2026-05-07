@extends('layouts.admin')
@section('title','Company Profile Update')
@section('content')
<div class="container mt-4">
    <h2>Company Settings</h2>

 
    <form action="{{ route('admin.company.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Company Name</label>
            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $setting->company_name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Tagline</label>
            <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $setting->tagline ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $setting->email ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $setting->phone ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address', $setting->address ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>About Us</label>
            <textarea name="about" class="form-control" rows="4">{{ old('about', $setting->about ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Logo</label><br>
            @if(!empty($setting->logo))
                <img src="{{ asset($setting->logo) }}" height="50" class="mb-2">
            @endif
            <input type="file" name="logo" class="form-control">
        </div>

        <div class="mb-3">
            <label>Favicon</label><br>
            @if(!empty($setting->favicon))
                <img src="{{ asset($setting->favicon) }}" height="30" class="mb-2">
            @endif
            <input type="file" name="favicon" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@endsection
