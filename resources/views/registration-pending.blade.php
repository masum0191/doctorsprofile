@extends('layouts.sass')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-2xl">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-yellow-500 text-white px-6 py-4 text-lg font-semibold">
                {{ __('Registration Pending') }}
            </div>

            <div class="px-6 py-6 text-gray-700 space-y-4">
                <p>
                    Your registration is currently pending approval. We will notify
                    you via email once your account has been reviewed and activated.
                </p>
                <p class="font-medium">
                    Thank you for your patience!
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
