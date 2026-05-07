@extends('layouts.admin')

@section('title','Message List')

@section('content')
<h4 class="mb-3">Message List</h4>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Last Message</th>
                    <th>Sender</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $key => $msg)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $msg->patient->name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($msg->message, 50) }}</td>
                        <td>{{ ucfirst($msg->sender) }}</td>
                        <td>{{ $msg->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No messages found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
