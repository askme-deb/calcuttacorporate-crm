<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Resignation Management</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Resignation Management</h4>
                         <p class="text-muted">Manage employee resignation requests</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid px-4 py-4">


                                {{-- Statistics Cards --}}
                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <i class="fas fa-users fa-lg text-secondary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="text-muted small fw-medium text-truncate">Total Resignations</div>
                                                        <div class="h5 fw-bold text-dark mb-0">{{ $stats['total'] ?? 0 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <i class="fas fa-clock fa-lg text-warning"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="text-muted small fw-medium text-truncate">Pending</div>
                                                        <div class="h5 fw-bold text-warning mb-0">{{ $stats['pending'] ?? 0 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <i class="fas fa-check-circle fa-lg text-success"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="text-muted small fw-medium text-truncate">Approved</div>
                                                        <div class="h5 fw-bold text-success mb-0">{{ $stats['approved'] ?? 0 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="text-muted small fw-medium text-truncate">Rejected</div>
                                                        <div class="h5 fw-bold text-danger mb-0">{{ $stats['rejected'] ?? 0 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Filters --}}
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <label for="search" class="form-label fw-medium">Search Employee</label>
                                                <input
                                                    type="text"
                                                    id="search"
                                                    wire:model.live.debounce.300ms="searchTerm"
                                                    placeholder="Search by name or email..."
                                                    class="form-control">
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <label for="status" class="form-label fw-medium">Filter by Status</label>
                                                <select
                                                    id="status"
                                                    wire:model.live="statusFilter"
                                                    class="form-select">
                                                    <option value="all">All Status</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="rejected">Rejected</option>
                                                    <option value="withdrawn">Withdrawn</option>
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <label for="date_range" class="form-label fw-medium">Date Range</label>
                                                <select
                                                    id="date_range"
                                                    wire:model.live="dateFilter"
                                                    class="form-select">
                                                    <option value="all">All Time</option>
                                                    <option value="today">Today</option>
                                                    <option value="week">This Week</option>
                                                    <option value="month">This Month</option>
                                                    <option value="quarter">This Quarter</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Resignations Table --}}
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">Resignation Requests</h5>
                                            <div class="text-muted small">
                                                Showing {{ $resignations->count() }} of {{ $resignations->total() }} results
                                            </div>
                                        </div>
                                    </div>

                                    @if ($resignations->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach ($resignations as $resignation)
                                                <div class="list-group-item list-group-item-action border-0">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-3">
                                                                @if($resignation->employee->avatar)
                                                                    <img class="rounded-circle"
                                                                         width="40" height="40"
                                                                         src="{{ asset('storage/' . $resignation->employee->avatar) }}"
                                                                         alt="{{ $resignation->employee->name }}"
                                                                         style="object-fit: cover;">
                                                                @else
                                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                                                         style="width: 40px; height: 40px;">
                                                                        <span class="text-white small fw-medium">
                                                                            {{ strtoupper(substr($resignation->employee->name, 0, 2)) }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                                    <h6 class="mb-0 text-truncate">{{ $resignation->employee->name }}</h6>
                                                                    <span class="badge
                                                                        @switch($resignation->status)
                                                                            @case('pending')
                                                                                bg-warning text-dark
                                                                                @break
                                                                            @case('approved')
                                                                                bg-success
                                                                                @break
                                                                            @case('rejected')
                                                                                bg-danger
                                                                                @break
                                                                            @case('withdrawn')
                                                                                bg-info
                                                                                @break
                                                                            @default
                                                                                bg-secondary
                                                                        @endswitch">
                                                                        {{ ucfirst($resignation->status) }}
                                                                    </span>
                                                                </div>
                                                                <div class="text-muted small d-flex flex-wrap gap-2">
                                                                    <span>{{ $resignation->employee->email }}</span>
                                                                    <span class="text-muted">•</span>
                                                                    <span>Submitted: {{ $resignation->created_at->format('M d, Y') }}</span>
                                                                    <span class="text-muted">•</span>
                                                                    <span>Last Working: {{ $resignation->last_working_date->format('M d, Y') }}</span>
                                                                    @if($resignation->notice_period_days)
                                                                        <span class="text-muted">•</span>
                                                                        <span>Notice: {{ $resignation->notice_period_days }} days</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <button
                                                                wire:click="viewResignation({{ $resignation->id }})"
                                                                class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-eye me-1"></i>
                                                                View Details
                                                            </button>
                                                            @if ($resignation->status === 'approved')
                                                                <a href="{{ route('hr.exit-checklist', $resignation->id) }}"
                                                                   class="btn btn-outline-success btn-sm">
                                                                    <i class="fas fa-list-check me-1"></i>
                                                                    Exit Checklist
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="card-footer bg-light border-0">
                                            {{ $resignations->links() }}
                                        </div>
                                    @else
                                        <div class="card-body text-center py-5">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <h5 class="text-dark">No resignations found</h5>
                                            <p class="text-muted">No resignation requests match your current filters.</p>
                                            @if($searchTerm || $statusFilter !== 'all' || $dateFilter !== 'all')
                                                <button
                                                    wire:click="clearFilters"
                                                    class="btn btn-outline-primary">
                                                    Clear Filters
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @if ($showModal && $selectedResignation)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);"
             wire:click="closeModal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" wire:click.stop>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Resignation Details - {{ $selectedResignation->employee->name }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
  @if ($actionType && $selectedResignation->status === 'pending')
                            <hr>
                            <div class="alert alert-warning" role="alert">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="alert-heading">Confirm Action</h6>
                                        <p class="mb-3">
                                            Are you sure you want to <strong>{{ $actionType }}</strong> this resignation request?
                                            This action cannot be undone.
                                        </p>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button
                                                wire:click="setAction('')"
                                                class="btn btn-outline-secondary btn-sm">
                                                Cancel
                                            </button>
                                            <button
                                                wire:click="processResignation"
                                                class="btn btn-primary btn-sm">
                                                Confirm {{ ucfirst($actionType) }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    <div class="modal-body">

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-muted">Employee</label>
                                <p class="mb-0">{{ $selectedResignation->employee->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-muted">Email</label>
                                <p class="mb-0">{{ $selectedResignation->employee->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-muted">Department</label>
                                <p class="mb-0">{{ $selectedResignation->employee->department ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-muted">Position</label>
                                <p class="mb-0">{{ $selectedResignation->employee->position ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-muted">Resignation Date</label>
                                <p class="mb-0">{{ $selectedResignation->resignation_date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-muted">Last Working Date</label>
                                <p class="mb-0">{{ $selectedResignation->last_working_date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-muted">Notice Period</label>
                                <p class="mb-0">
                                    {{ $selectedResignation->notice_period_days ?? 'N/A' }}
                                    @if($selectedResignation->notice_period_days) days @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-muted">Status</label>
                                <div>
                                    <span class="badge fs-6
                                        @switch($selectedResignation->status)
                                            @case('pending')
                                                bg-warning text-dark
                                                @break
                                            @case('approved')
                                                bg-success
                                                @break
                                            @case('rejected')
                                                bg-danger
                                                @break
                                            @case('withdrawn')
                                                bg-info
                                                @break
                                            @default
                                                bg-secondary
                                        @endswitch">
                                        {{ ucfirst($selectedResignation->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-muted">Reason for Resignation</label>
                            <div class="p-3 bg-light border rounded">
                                {{ $selectedResignation->reason }}
                            </div>
                        </div>

                        @if ($selectedResignation->additional_comments)
                            <div class="mb-4">
                                <label class="form-label fw-medium text-muted">Additional Comments</label>
                                <div class="p-3 bg-light border rounded">
                                    {{ $selectedResignation->additional_comments }}
                                </div>
                            </div>
                        @endif

                        @if ($selectedResignation->status === 'pending')
                            <hr>
                            <div class="mb-3">
                                <label for="hr_comments" class="form-label fw-medium">HR Comments</label>
                                <textarea
                                    wire:model="hr_comments"
                                    id="hr_comments"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Add your comments here..."></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button
                                    wire:click="setAction('reject')"
                                    class="btn btn-outline-danger">
                                    <i class="fas fa-times me-1"></i>
                                    Reject
                                </button>
                                <button
                                    wire:click="setAction('approve')"
                                    class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>
                                    Approve
                                </button>
                            </div>
                        @endif



                        @if ($selectedResignation->hr_comments && $selectedResignation->status !== 'pending')
                            <hr>
                            <div class="mb-3">
                                <label class="form-label fw-medium text-muted">HR Comments</label>
                                <div class="p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded">
                                    {{ $selectedResignation->hr_comments }}
                                </div>
                                @if($selectedResignation->processed_at)
                                    <small class="text-muted">
                                        Processed on {{ $selectedResignation->processed_at->format('M d, Y \a\t g:i A') }}
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
