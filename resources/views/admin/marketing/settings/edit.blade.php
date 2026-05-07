@extends('layouts.admin')
@section('title','Marketing Settings')

@section('content')
<div class="container-fluid px-4">
  <div class="py-4">
    <h1 class="h4 fw-bold mb-1">Marketing Settings</h1>
    <p class="text-muted mb-0">Configure Email and WhatsApp sending and limits.</p>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.marketing.settings.update') }}">
        @csrf
        <div class="row g-3">

          <div class="col-12"><h6 class="fw-bold mb-0">Sending Limits</h6></div>
          <div class="col-md-3">
            <label class="form-label">Email per hour</label>
            <input name="email_per_hour" class="form-control" value="{{ data_get($setting->sending_limits,'email_per_hour',50) }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">WhatsApp per hour</label>
            <input name="whatsapp_per_hour" class="form-control" value="{{ data_get($setting->sending_limits,'whatsapp_per_hour',30) }}">
          </div>

          <div class="col-12 mt-4"><h6 class="fw-bold mb-0">WhatsApp API</h6></div>
          <div class="col-md-4">
            <label class="form-label">Provider</label>
            <input name="whatsapp_provider" class="form-control" value="{{ $setting->whatsapp_provider }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Phone Number ID</label>
            <input name="whatsapp_phone_number_id" class="form-control" value="{{ $setting->whatsapp_phone_number_id }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Business Account ID</label>
            <input name="whatsapp_business_account_id" class="form-control" value="{{ $setting->whatsapp_business_account_id }}">
          </div>
          <div class="col-12">
            <label class="form-label">WhatsApp Token</label>
            <textarea name="whatsapp_token" class="form-control" rows="3">{{ $setting->whatsapp_token }}</textarea>
          </div>

          <div class="col-12 mt-4"><h6 class="fw-bold mb-0">Email (SMTP)</h6></div>
          <div class="col-md-4">
            <label class="form-label">From Name</label>
            <input name="email_from_name" class="form-control" value="{{ $setting->email_from_name }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">From Address</label>
            <input name="email_from_address" class="form-control" value="{{ $setting->email_from_address }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">SMTP Host</label>
            <input name="smtp_host" class="form-control" value="{{ $setting->smtp_host }}">
          </div>
          <div class="col-md-2">
            <label class="form-label">Port</label>
            <input name="smtp_port" class="form-control" value="{{ $setting->smtp_port }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">User</label>
            <input name="smtp_user" class="form-control" value="{{ $setting->smtp_user }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Password</label>
            <input name="smtp_pass" class="form-control" value="{{ $setting->smtp_pass }}">
          </div>
          <div class="col-md-2">
            <label class="form-label">Encryption</label>
            <input name="smtp_encryption" class="form-control" value="{{ $setting->smtp_encryption }}">
          </div>

          <div class="col-12">
            <button class="btn btn-primary"><i class="ri-save-line me-1"></i>Save</button>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>
@endsection
