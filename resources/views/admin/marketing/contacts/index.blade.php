@extends('layouts.supperadmin')
@section('title','Marketing Contacts')

@section('content')
<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center py-4">
    <div>
      <h1 class="h4 mb-1 fw-bold">Doctor Marketing Contacts</h1>
      <p class="text-muted mb-0">Filter and manage doctor list for WhatsApp & Email campaigns.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('superadmin.marketing.contacts.create') }}">
      <i class="ri-add-line me-1"></i>Add Contact
    </a>
  </div>

  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-2">
        <div class="col-md-4">
          <input name="search" class="form-control" value="{{ request('search') }}" placeholder="Search name/email/phone/whatsapp/BMDC">
        </div>
        <div class="col-md-2">
          <input name="city" class="form-control" value="{{ request('city') }}" placeholder="City">
        </div>
        <div class="col-md-2">
          <input name="specialty" class="form-control" value="{{ request('specialty') }}" placeholder="Specialty">
        </div>
        <div class="col-md-2">
          <select name="channel" class="form-select">
            <option value="">All</option>
            <option value="email" @selected(request('channel')==='email')>Email Opt-in</option>
            <option value="whatsapp" @selected(request('channel')==='whatsapp')>WhatsApp Opt-in</option>
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button class="btn btn-primary w-100"><i class="ri-filter-line me-1"></i>Filter</button>
          <a class="btn btn-light" href="{{ route('superadmin.marketing.contacts.index') }}"><i class="ri-refresh-line"></i></a>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th>Name</th><th>City</th><th>Specialty</th><th>Email</th><th>WhatsApp</th><th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($contacts as $c)
            <tr>
              <td class="fw-semibold">{{ $c->name }} <div class="small text-muted">BMDC: {{ $c->bmdc_no ?? '—' }}</div></td>
              <td>{{ $c->city ?? '—' }}</td>
              <td>{{ $c->specialty ?? '—' }}</td>
              <td>
                <div class="small">{{ $c->email ?? '—' }}</div>
                <span class="badge {{ $c->opt_in_email ? 'bg-success' : 'bg-secondary' }}">Email</span>
              </td>
              <td>
                <div class="small">{{ $c->whatsapp ?? '—' }}</div>
                <span class="badge {{ $c->opt_in_whatsapp ? 'bg-success' : 'bg-secondary' }}">WhatsApp</span>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('superadmin.marketing.contacts.show',$c) }}"><i class="ri-eye-line"></i></a>
                <form class="d-inline" method="POST" action="{{ route('superadmin.marketing.contacts.destroy',$c) }}" onsubmit="return confirm('Remove?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No contacts found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white border-0">
      {{ $contacts->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
