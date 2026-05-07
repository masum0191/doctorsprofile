@extends('layouts.admin')
@section('title','FAQs')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--primary);
    }

    .header-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .header-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Flash Message */
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #065f46;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success i {
        font-size: 1.25rem;
    }

    /* Filter Button */
    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(49, 128, 105, 0.15);
        border-color: var(--primary);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        border-radius: 4px 0 0 4px;
    }

    .stat-title {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .stat-subtitle {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    /* Table Container */
    .table-container {
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .table-header {
        background: var(--primary-soft);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--primary-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-title {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-count {
        font-size: 0.875rem;
        color: #6b7280;
        background: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        border: 1px solid var(--primary-light);
    }

    /* Table Styles */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead th {
        background: var(--primary-soft);
        color: #374151;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1rem;
        border-bottom: 1px solid var(--primary-light);
        text-align: left;
    }

    .custom-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .custom-table tbody tr:last-child {
        border-bottom: none;
    }

    .custom-table tbody td {
        padding: 1rem 1rem;
        color: #374151;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    /* FAQ Item Styles */
    .faq-question {
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .faq-answer {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0;
    }

    .faq-excerpt {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 500px;
    }

    .faq-order {
        background: var(--primary-light);
        color: var(--primary);
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        margin: 0 auto;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.75rem;
        background: white;
        border: 1px solid #e5e7eb;
    }

    .btn-icon:hover {
        background: #f9fafb;
    }

    .btn-edit:hover {
        color: var(--primary);
        border-color: var(--primary);
    }

    .btn-delete:hover {
        color: #dc2626;
        border-color: #dc2626;
    }

    .btn-view:hover {
        color: #2563eb;
        border-color: #2563eb;
    }

    /* Buttons */
    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-add {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-add:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--primary);
        font-size: 1.5rem;
    }

    .empty-state h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 0.875rem;
        max-width: 300px;
        margin: 0 auto 1.5rem;
    }

    /* Pagination */
    .pagination-container {
        padding: 1.5rem;
        border-top: 1px solid #f3f4f6;
        background: white;
        display: flex;
        justify-content: center;
    }

    /* Modal Styling */
    .filter-modal .modal-dialog {
        max-width: 400px;
    }

    .filter-modal .modal-content {
        border-radius: 12px;
        border: 1px solid rgba(49, 128, 105, 0.15);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .filter-modal .modal-header {
        background: var(--primary);
        color: white;
        border-radius: 12px 12px 0 0;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .filter-modal .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-modal .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .filter-modal .btn-close:hover {
        opacity: 1;
    }

    .filter-modal .modal-body {
        padding: 1.5rem;
    }

    .filter-modal .modal-footer {
        border-top: 1px solid #f3f4f6;
        padding: 1rem 1.5rem;
        background: var(--primary-soft);
    }

    /* View FAQ Modal */
    .view-modal .modal-dialog {
        max-width: 600px;
    }

    .view-modal .modal-content {
        border-radius: 12px;
        border: 1px solid rgba(49, 128, 105, 0.15);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .view-modal .modal-header {
        background: var(--primary);
        color: white;
        border-radius: 12px 12px 0 0;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .view-modal .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-modal .modal-body {
        padding: 1.5rem;
    }

    .view-modal .modal-footer {
        border-top: 1px solid #f3f4f6;
        padding: 1rem 1.5rem;
        background: var(--primary-soft);
    }

    /* FAQ Content in Modal */
    .faq-modal-question {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .faq-modal-answer {
        color: #374151;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #111827;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    /* Active Filters Indicator */
    .active-filters {
        background: var(--primary-soft);
        border: 1px solid var(--primary-light);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-size: 0.875rem;
    }

    .active-filters i {
        color: var(--primary);
    }

    .filter-tag {
        background: white;
        border: 1px solid var(--primary-light);
        border-radius: 16px;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .filter-tag .remove {
        cursor: pointer;
        margin-left: 0.25rem;
    }

    .filter-tag .remove:hover {
        color: #dc2626;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .custom-table {
            min-width: 800px;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: flex-end;
        }
        
        .faq-excerpt {
            max-width: 300px;
        }
    }
</style>

<div class="">
    <!-- Flash Message -->
    @if(session('ok'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('ok') }}
    </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-question-circle me-2"></i>
                    Frequently Asked Questions
                </h1>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn-filter" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i>
                    Filter FAQs
                </button>
                @if(auth()->user()->role_id === 1)
                    <a href="{{ route('admin.faqs.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Add New FAQ
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @php
        $totalFAQs = $faqs->total();
        $activeFAQs = $faqs->where('is_active', true)->count();
        $inactiveFAQs = $totalFAQs - $activeFAQs;
        $recentFAQs = $faqs->where('created_at', '>=', now()->subDays(30))->count();
    @endphp

  

    <!-- Active Filters Indicator -->
    @if(request()->has('q'))
    <div class="active-filters">
        <i class="fas fa-filter"></i>
        <span class="me-2">Active filter:</span>
        
        @if(request('q'))
            <span class="filter-tag">
                Search: "{{ request('q') }}"
                <a href="{{ route('admin.faqs.index', array_merge(request()->except('q'), ['page' => 1])) }}" class="remove">
                    <i class="fas fa-times"></i>
                </a>
            </span>
        @endif
        
        <a href="{{ route('admin.faqs.index') }}" class="ms-auto text-primary text-decoration-none">
            Clear all filters
        </a>
    </div>
    @endif

    <!-- FAQs Table -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-list me-2"></i>
                FAQs List
            </h3>
            <div class="table-count">
                {{ $faqs->total() }} Total • {{ $faqs->count() }} Showing
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="text-center">Order</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                    <tr>
                        <td>
                            <div class="faq-order">
                                {{ $faq->order_column }}
                            </div>
                        </td>
                        
                        <td>
                            <div class="faq-question">
                                {{ Str::limit($faq->question, 80) }}
                            </div>
                        </td>
                        
                        <td>
                            <div class="faq-answer faq-excerpt">
                                {{ Str::limit($faq->answer, 120) }}
                            </div>
                            @if(strlen($faq->answer) > 120)
                                <button class="btn btn-link btn-sm p-0 text-primary" 
                                        onclick="viewFullFAQ('{{ addslashes($faq->question) }}', '{{ addslashes($faq->answer) }}')">
                                    Read more
                                </button>
                            @endif
                        </td>
                        
                        <td>
                            <div class="action-buttons">
                                @if(auth()->user()->role_id === 1)
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" 
                                       class="btn-icon btn-edit" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                
                                <button class="btn-icon btn-view" 
                                        onclick="viewFullFAQ('{{ addslashes($faq->question) }}', '{{ addslashes($faq->answer) }}')"
                                        title="View Full">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if(auth()->user()->role_id === 1)
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <h5>No FAQs found</h5>
                                <p>
                                    @if(request()->has('q'))
                                        Try adjusting your search or clear filters to see all FAQs
                                    @else
                                        Start by adding your first frequently asked question
                                    @endif
                                </p>
                                @if(auth()->user()->role_id === 1 && !request()->has('q'))
                                    <a href="{{ route('admin.faqs.create') }}" class="btn-primary">
                                        <i class="fas fa-plus me-2"></i>
                                        Add FAQ
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($faqs->hasPages())
        <div class="pagination-container">
            {{ $faqs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade filter-modal" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filter FAQs
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.faqs.index') }}">
                <input type="hidden" name="page" value="1">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Search</label>
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}" 
                               class="form-control" 
                               placeholder="Search in questions or answers...">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="order" {{ request('sort') == 'order' || !request('sort') ? 'selected' : '' }}>Display Order</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="question_asc" {{ request('sort') == 'question_asc' ? 'selected' : '' }}>Question (A-Z)</option>
                            <option value="question_desc" {{ request('sort') == 'question_desc' ? 'selected' : '' }}>Question (Z-A)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn-secondary">
                        Reset
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-filter me-2"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Full FAQ Modal -->
<div class="modal fade view-modal" id="viewFAQModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle me-2"></i>
                    FAQ Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="faq-modal-question" id="modalFAQQuestion"></div>
                <div class="faq-modal-answer" id="modalFAQAnswer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modals
        const filterModal = new bootstrap.Modal(document.getElementById('filterModal'));
        const viewModal = new bootstrap.Modal(document.getElementById('viewFAQModal'));
        
        // Function to view full FAQ
        window.viewFullFAQ = function(question, answer) {
            document.getElementById('modalFAQQuestion').textContent = question;
            document.getElementById('modalFAQAnswer').textContent = answer;
            viewModal.show();
        };
        
        // Confirm delete
        window.confirmDelete = function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this FAQ? This action cannot be undone.')) {
                event.target.closest('form').submit();
            }
            return false;
        };
        
        // Keyboard shortcut for filter modal (Ctrl/Cmd + F)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                filterModal.show();
            }
        });
        
        // Animate table rows on load
        const tableRows = document.querySelectorAll('.custom-table tbody tr');
        tableRows.forEach((row, index) => {
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
        
        // Set default styles for animation
        document.querySelectorAll('.custom-table tbody tr').forEach(row => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            row.style.transition = 'all 0.3s ease';
        });
        
        // Animate stats cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        document.querySelectorAll('.stat-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.3s ease';
        });
    });
</script>
@endsection