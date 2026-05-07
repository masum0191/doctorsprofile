@extends('layouts.admin')
@section('title', 'যোগাযোগ তালিকা')
@section('content')
<style>
    .card {
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        top: 10px;
    }
    .card-body{
        padding: 0;
    }
    .card-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 0.8rem 1.5rem;
        border-bottom: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-header h5 {
        margin-bottom: 0;
        color: white;
        font-weight: 600;
    }
    
    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    .table thead th {
        background-color: #f8f9fc;
        color: #4e73df;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-top: 1px solid #e3e6f0;
    }
    
    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.03);
    }
    
    .message-preview {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .action-btns {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-sm {
        padding: 0.35rem 0.75rem;
        font-size: 0.825rem;
        border-radius: 0.375rem;
    }
    
    .view-link {
        color: #4e73df;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .view-link:hover {
        color: #224abe;
        text-decoration: underline;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 0;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #d1d3e2;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .action-btns {
            flex-direction: column;
        }
        
        .btn-sm {
            width: 100%;
        }
        
        .message-preview {
            max-width: 100px;
        }
    }
</style>

<div class="container-fluid contact-management">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">যোগাযোগ ফর্মের বার্তাসমূহ</h5>
            <span class="badge bg-light text-dark margin-end">
                মোট বার্তা: {{ $contacts->total() }}
            </span>
        </div>
        
        <div class="card-body">
            @if($contacts->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>নাম</th>
                                <th>ইমেইল</th>
                                <th>ফোন</th>
                                <th>বার্তা</th>
                                <th>তারিখ</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $key => $contact)
                            <tr>
                                <td>{{ $contacts->firstItem() + $key }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td class="message-preview" title="{{ $contact->message }}">
                                    {{ Str::limit($contact->message, 100) }}
                                </td>
                                <td>{{ $contact->created_at->format('d M, Y h:i A') }}</td>
                                <td>
                                    <div class="d-flex justify-content-end action-btns">
                                        <a href="{{ url('complain/'.$contact->id) }}" 
                                           class="btn btn-sm btn-primary text-white view-link"
                                           title="দেখুন">
                                           <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-button" title="মুছুন">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $contacts->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-envelope"></i>
                    <h5>কোনো বার্তা পাওয়া যায়নি</h5>
                    <p class="text-muted">যখন নতুন বার্তা আসবে, তা এখানে দেখা যাবে</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Delete confirmation
        $(document).on('click', '.delete-button', function () {
            let form = $(this).closest('form');
            let row = $(this).closest('tr');

            Swal.fire({
                title: 'আপনি কি নিশ্চিত?',
                text: "একবার মুছে ফেললে আর ফেরত আনা যাবে না!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'হ্যাঁ, মুছুন!',
                cancelButtonText: 'বাতিল করুন'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function (response) {
                            row.fadeOut(500, function () {
                                $(this).remove();
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'মুছে ফেলা হয়েছে!',
                                text: 'বার্তা সফলভাবে মুছে ফেলা হয়েছে।',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'ত্রুটি!',
                                text: 'বার্তা মুছে ফেলা যায়নি।',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection