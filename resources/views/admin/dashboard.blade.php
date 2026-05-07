@extends('layouts.tenantadmin')
@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">
    <style>
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border-color: #318069;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .stat-icon.primary {
            background: #e8f5f0;
            color: #318069;
        }
        
        .stat-icon.success {
            background: #d1fae5;
            color: #10b981;
        }
        
        .stat-icon.warning {
            background: #fef3c7;
            color: #f59e0b;
        }
        
        .domain-table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .domain-table thead th {
            background: #f8fafc;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .domain-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            font-size: 0.85rem;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .domain-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .alert-warning-custom {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 1rem;
        }
        
        @media (max-width: 768px) {
            .stat-card {
                padding: 1rem;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>

    {{-- Page Header --}}
    <div class="mb-3">
        <h1 class="h4 fw-semibold text-dark">Dashboard</h1>
    </div>

    {{-- NS Warning --}}
    @php
        $expectedNs = ['ns1.doctorsprofile.xyz', 'ns2.doctorsprofile.xyz'];
        $tenant = isset($tenants) && $tenants ? $tenants->first() : null;
    @endphp

    @if($type === 'domain' && (empty($ns) || isset($ns['error']) || $ns !== $expectedNs))
        <div class="alert-warning-custom d-flex align-items-start gap-3 mb-4">
            <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
            <div>
                <div class="fw-semibold text-dark mb-1">Action Required: Nameserver Setup</div>
                <div class="small text-muted">
                    Please update your domain nameservers to
                    <strong class="text-dark">ns1.doctorsprofile.xyz</strong> and
                    <strong class="text-dark">ns2.doctorsprofile.xyz</strong>
                </div>
            </div>
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="stat-card d-flex  justify-content-between">
                <div>
                    <div class="text-muted small mb-1">Account Holder</div>
                    <div class="fw-semibold text-dark">{{ auth()->user()->name ?? 'N/A' }}</div>
                    <div class="small text-muted mt-1">{{ auth()->user()->email ?? 'N/A' }}</div>
                </div>
                <div>
                <div class="stat-icon primary">
                    <i class="ri-user-line"></i>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="stat-card d-flex  justify-content-between">
                <div>
                    <div class="text-muted small mb-1">Tenant ID</div>
                    <div class="fw-semibold text-dark">{{ auth()->user()->tenant_id ?? '—' }}</div>
                    <div class="small text-muted mt-1">Unique Identifier</div>
                </div>
                <div>
                <div class="stat-icon primary">
                    <i class="ri-building-line"></i>
                </div>
                </div>
                
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="stat-card d-flex  justify-content-between">
                <div>
                    <div class="text-muted small mb-1">Account Status</div>
                    @if($tenant && $tenant->status == 1)
                        <span class="status-badge status-active mt-1">
                            <i class="ri-checkbox-circle-line"></i> Active
                        </span>
                    @else
                        <span class="status-badge status-inactive mt-1">
                            <i class="ri-close-circle-line"></i> Inactive
                        </span>
                    @endif
                </div>
                <div>
                <div class="stat-icon {{ $tenant && $tenant->status == 1 ? 'success' : 'warning' }}">
                    <i class="ri-shield-check-line"></i>
                </div>
                </div>
                
            </div>
        </div>
    </div>

    {{-- Domains Section --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <div class="fw-semibold text-dark">Domains</div>
                </div>
                @if($tenant && $tenant->status == 1 && $tenants && $tenants->isNotEmpty())
                    <div class="text-muted small">
                        <i class="ri-global-line"></i> {{ $tenants->sum(fn($t) => $t->domains->count()) }} domain(s)
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            @if($tenant && $tenant->status == 1 && $tenants && $tenants->isNotEmpty())
                <div class="table-responsive">
                    <table class="domain-table table mb-0">
                        <thead>
                            <tr>
                                <th>Domain Name</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tenants as $t)
                                @foreach($t->domains ?? [] as $domain)
                                    <tr>
                                        <td class="fw-semibold">
                                            <i class="ri-global-line text-muted me-2"></i>
                                            {{ $domain->domain }}
                                        </td>
                                        <td>
                                            <span class="status-badge status-active">
                                                <i class="ri-checkbox-circle-line"></i> Connected
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <a href="http://{{ $domain->domain }}" target="_blank"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-external-link-line me-1"></i> Visit
                                                </a>
                                                <a href="http://{{ $domain->domain }}/login" target="_blank"
                                                   class="btn btn-sm btn-outline-secondary">
                                                    <i class="ri-login-box-line me-1"></i> Admin
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-global-line text-muted fs-1 mb-3 d-block"></i>
                    <div class="text-muted">Domain information will be available once your account is active.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection