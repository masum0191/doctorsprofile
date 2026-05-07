@extends('layouts.admin')
@section('title', 'Medicine')
@section('content')
    {{-- CREATE --}}
    <form method="POST" action="{{ route('admin.medicines.store') }}" class="card card-body mb-4">
        @csrf
        <div class="row g-2">
            <div class="col-md-3">
                <input name="name" class="form-control" placeholder="Medicine name (e.g. Paracetamol)" required>
            </div>

            <div class="col-md-2">
                <input name="type" class="form-control" placeholder="Type (Tablet / Syrup)">
            </div>

            <div class="col-md-2">
                <input name="dosage" class="form-control" placeholder="Dosage (500mg)">
            </div>

            <div class="col-md-2">
                <input name="frequency" class="form-control" placeholder="Frequency (1+0+1)">
            </div>

            <div class="col-md-2">
                <input name="duration" class="form-control" placeholder="Duration (5 days)">
            </div>

            <div class="col-md-12">
                <textarea name="instruction" class="form-control" placeholder="Special instruction (After meal, Before sleep)"
                    rows="2"></textarea>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary">Add Medicine</button>
            </div>
        </div>
    </form>

    {{-- READ + UPDATE + DELETE --}}
    <table class="table table-bordered table-sm align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Type</th>
                <th>Dosage</th>
                <th>Frequency</th>
                <th>Duration</th>
                <th width="22%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($medicines as $item)
                <tr>
                    <td>{{ $item->id }}</td>

                    <form method="POST" action="{{ route('admin.medicines.update', $item->id) }}">
                        @csrf
                        @method('PUT')

                        <td>
                            <input name="name" value="{{ $item->name }}" class="form-control form-control-sm" required>
                        </td>

                        <td>
                            <input name="type" value="{{ $item->type }}" class="form-control form-control-sm">
                        </td>

                        <td>
                            <input name="dosage" value="{{ $item->dosage }}" class="form-control form-control-sm">
                        </td>

                        <td>
                            <input name="frequency" value="{{ $item->frequency }}" class="form-control form-control-sm">
                        </td>

                        <td>
                            <input name="duration" value="{{ $item->duration }}" class="form-control form-control-sm">
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning">Update</button>
                    </form>

                    <form method="POST" action="{{ route('admin.medicines.destroy', $item->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this medicine?')">
                            Delete
                        </button>
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
