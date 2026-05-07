@extends('layouts.admin')
@section('title', 'Create New Page')
@section('content')

<style>
    .card {
        border: none;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #007bff 0%, #224abe 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-bottom: none;
    }

    .card-header h3 {
        font-weight: 600;
        margin-bottom: 0;
    }

    .detail-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
        border: 1px solid #e3e6f0;
    }

    .detail-section h5 {
        color: #007bff;
        border-bottom: 1px solid #e3e6f0;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .detail-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fc;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 35%;
        font-weight: 600;
        color: #5a5c69;
    }

    .detail-value {
        width: 65%;
        color: #212529;
    }

    .btn-submit {
        background: linear-gradient(135deg, #007bff 0%, #224abe 100%);
        border: none;
        padding: 0.5rem 2rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .btn-cancel {
        background: #f8f9fa;
        border: 1px solid #e3e6f0;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .detail-row {
            flex-direction: column;
        }
        
        .detail-label,
        .detail-value {
            width: 100%;
        }
        
        .detail-label {
            margin-bottom: 0.25rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Create New Page</h5>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-light margin-end">
                <i class="fas fa-arrow-left mr-2"></i> View All Pages
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Include the form partial -->
                @include('admin.pages.form')
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-submit me-3">
                        <i class="fas fa-save me-2"></i> Save Page
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll to the first error field
        const firstErrorField = document.querySelector('.is-invalid');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });
</script>
@endif
@endsection