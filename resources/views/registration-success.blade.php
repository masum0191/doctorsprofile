@extends('layouts.sass')

@section('title', 'Registration Successful')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-2xl">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-green-600 text-white px-6 py-4 text-lg font-semibold">
                {{ __('Registration Successful') }}
            </div>

            <div class="px-6 py-6 text-gray-700 space-y-4">
                <p>Your registration has been successfully completed!</p>
                <p>
                    We will review your information and notify you via email once
                    your account is activated.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
