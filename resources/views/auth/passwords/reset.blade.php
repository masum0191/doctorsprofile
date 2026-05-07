@extends('layouts.sass')
@section('title', 'Reset Password')

@section('content')
<div class="min-h-[80vh] grid place-items-center bg-gradient-to-br from-sky-50 via-white to-emerald-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-900">
  <div class="w-full max-w-md">
    <div class="text-center mb-6">
       
      <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">Set a new password</h1>
      <p class="text-sm text-slate-600 dark:text-slate-400">Choose a strong password to secure your account.</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-xl p-6 dark:border-slate-800 dark:bg-slate-900">
      <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
          <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required
            class="mt-1 block w-full h-11 rounded-xl border border-slate-300 bg-white px-4 text-slate-900 placeholder-slate-400 shadow-sm outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-sky-500 dark:focus:ring-sky-800">
          @error('email') <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">New password</label>
          <input id="password" type="password" name="password" required autocomplete="new-password"
            class="mt-1 block w-full h-11 rounded-xl border border-slate-300 bg-white px-4 text-slate-900 placeholder-slate-400 shadow-sm outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-sky-500 dark:focus:ring-sky-800" placeholder="••••••••">
          @error('password') <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirm password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
            class="mt-1 block w-full h-11 rounded-xl border border-slate-300 bg-white px-4 text-slate-900 placeholder-slate-400 shadow-sm outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-sky-500 dark:focus:ring-sky-800" placeholder="••••••••">
        </div>

        <button type="submit"
          class="w-full inline-flex items-center justify-center rounded-xl bg-slate-900 text-white h-11 px-4 font-medium shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:bg-slate-700 dark:hover:bg-slate-600">
          Reset password
        </button>
      </form>

      <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-2">
        <a href="{{ route('admin.login') }}" class="text-sky-700 hover:underline dark:text-sky-400">Back to login</a>
      </p>
    </div>
  </div>
</div>
@endsection