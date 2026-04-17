<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="row mb-4 align-items-center">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold text-primary mb-0">
                        <i class="fas fa-user-slash me-2 text-danger"></i>Employee Termination Management
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 bg-light rounded px-3 py-2 shadow-sm border">
                            <li class="breadcrumb-item">
                                <a wire:navigate href="{{ route('dashboard') }}" class="text-decoration-none text-muted">
                                    <i class="fas fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Employee Termination</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Flash Message -->
            @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Dashboard Stats -->
            <div class="row g-4 mb-4">
                <!-- Total Terminations -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-users fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-primary text-uppercase mb-1">
                                                Total Requests
                                            </div>
                                            <div class="h5 fw-bold text-gray-800 mb-0">
                                                {{ $terminations->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-warning text-uppercase mb-1">
                                                Pending Review
                                            </div>
                                            <div class="h5 fw-bold text-gray-800 mb-0">
                                                {{ $terminations->where('status', 'pending')->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approved Requests -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-success text-uppercase mb-1">
                                                Approved
                                            </div>
                                            <div class="h5 fw-bold text-gray-800 mb-0">
                                                {{ $terminations->where('status', 'approved')->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejected Requests -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-danger text-uppercase mb-1">
                                                Rejected
                                            </div>
                                            <div class="h5 fw-bold text-gray-800 mb-0">
                                                {{ $terminations->where('status', 'rejected')->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="row g-4 mb-4">
                <!-- This Month Terminations -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-info text-uppercase mb-1">
                                                This Month
                                            </div>
                                            <div class="h5 fw-bold text-gray-800 mb-0">
                                                {{ $terminations->where('termination_date', '>=', now()->startOfMonth())->count() }}
                                            </div>
                                            <div class="text-xs text-muted">
                                                Scheduled terminations
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approval Rate -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-percentage fa-2x text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-success text-uppercase mb-1">
                                                Approval Rate
                                            </div>
                                            <div class="h5 fw-bold text-gray-800 mb-0">
                                                @php
                                                $total = $terminations->whereIn('status', ['approved', 'rejected'])->count();
                                                $approved = $terminations->where('status', 'approved')->count();
                                                $rate = $total > 0 ? round(($approved / $total) * 100, 1) : 0;
                                                @endphp
                                                {{ $rate }}%
                                            </div>
                                            <div class="text-xs text-muted">
                                                Of processed requests
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Processing Time -->
                <div class="col-xl-4 col-md-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-secondary bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-stopwatch fa-2x text-secondary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-secondary text-uppercase mb-1">
                                                Pending Actions
                                            </div>
                                            <div class="h5 fw-bold text-gray-800 mb-0">
                                                {{ $terminations->where('status', 'pending')->count() }}
                                            </div>
                                            <div class="text-xs text-muted">
                                                Require immediate attention
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card shadow border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0 text-dark fw-semibold">
                                <i class="fas fa-list-alt me-2 text-primary"></i>Termination Requests
                            </h5>
                            <p class="text-muted small mb-0">Manage employee termination requests and approvals</p>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#terminationModal">
                                <i class="fas fa-plus me-2"></i>New Termination Request
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Filters Section -->
                    <div class="row g-3 mb-4 p-3 bg-light rounded border">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">
                                <i class="fas fa-user me-1"></i>Filter by Employee
                            </label>
                            <select wire:model.live="filterEmployee" class="form-select shadow-sm border-secondary">
                                <option value="">All Employees</option>
                                @foreach ($allEmployees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">
                                <i class="fas fa-filter me-1"></i>Filter by Status
                            </label>
                            <select wire:model.live="filterStatus" class="form-select shadow-sm border-secondary">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle ">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold">
                                        <i class="fas fa-user me-2"></i>Employee
                                    </th>
                                    <th class="fw-semibold">
                                        <i class="fas fa-calendar me-2"></i>Termination Date
                                    </th>
                                    <th class="fw-semibold">
                                        <i class="fas fa-comment-alt me-2"></i>Reason
                                    </th>
                                    <th class="fw-semibold text-center">
                                        <i class="fas fa-info-circle me-2"></i>Status
                                    </th>
                                    <th class="fw-semibold text-center">
                                        <i class="fas fa-cogs me-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($terminations as $term)
                                @php
                                $employee = getEmployeeDetailsByUserId($term->employee_id); // Preferably move this to the component if reused often
                                @endphp
                                <tr class="border-light align-middle">
                                    <!-- Employee Column -->
                                    <td class="fw-medium text-dark">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $term->employee->name }}</div>
                                                <small class="text-muted">ID: {{ $employee['emp_code'] ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Termination Date -->
                                    <td class="text-dark">
                                        <div class="fw-medium">{{ \Carbon\Carbon::parse($term->termination_date)->format('d M Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($term->termination_date)->format('l') }}</small>
                                    </td>

                                    <!-- Reason -->
                                    <td class="text-dark">
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $term->reason }}">
                                            {{ $term->reason }}
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="text-center">
                                        @switch($term->status)
                                        @case('approved')
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            <i class="fas fa-check me-1"></i>Approved
                                        </span>
                                        @break
                                        @case('rejected')
                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                            <i class="fas fa-times me-1"></i>Rejected
                                        </span>
                                        @break
                                        @default
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                        @endswitch
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center">
                                        <!-- View Button -->
                                        <button wire:click="viewDetails({{ $term->id }})"
                                            class="btn btn-info btn-sm shadow-sm me-1"
                                            title="View Termination Details"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewDetailsModal"
                                            wire:loading.attr="disabled"
                                            wire:target="viewDetails({{ $term->id }})">
                                            <span wire:loading.remove wire:target="viewDetails({{ $term->id }})">
                                                <i class="fas fa-eye me-1"></i>View
                                            </span>
                                            <span wire:loading wire:target="viewDetails({{ $term->id }})">
                                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </span>
                                        </button>

                                        <!-- Approve/Reject Buttons -->
                                        @if ($term->status === 'pending')
                                        <div class="btn-group mt-2" role="group">
                                            <button wire:click="confirmApprove({{ $term->id }})"
                                                class="btn btn-success btn-sm shadow-sm"
                                                title="Approve Request"
                                                wire:loading.attr="disabled"
                                                wire:target="confirmApprove">
                                                <span wire:loading.remove wire:target="confirmApprove">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </span>
                                                <span wire:loading wire:target="confirmApprove">
                                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </span>
                                            </button>

                                            <button wire:click="confirmReject({{ $term->id }})"
                                                class="btn btn-danger btn-sm shadow-sm"
                                                title="Reject Request"
                                                wire:loading.attr="disabled"
                                                wire:target="confirmReject">
                                                <span wire:loading.remove wire:target="confirmReject">
                                                    <i class="fas fa-times me-1"></i>Reject
                                                </span>
                                                <span wire:loading wire:target="confirmReject">
                                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </span>
                                            </button>
                                        </div>
                                        @else
                                        <span class="text-muted fst-italic d-block mt-2">
                                            <i class="fas fa-ban me-1"></i>No actions available
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <div class="mb-3">
                                            <i class="fas fa-inbox fa-3x text-muted opacity-50"></i>
                                        </div>
                                        <h6 class="text-muted">No termination requests found</h6>
                                        <p class="small text-muted mb-0">Create a new termination request to get started</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- Termination Modal -->
            <div class="modal fade" id="terminationModal" tabindex="-1" aria-labelledby="terminationModalLabel" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content shadow border-0">
                        <div class="modal-header bg-danger text-white border-0">
                            <h5 class="modal-title fw-semibold" id="terminationModalLabel">
                                <i class="fas fa-user-times me-2"></i>New Termination Request
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="submit">
                            <div class="modal-body p-4">
                                <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Important:</strong> Please ensure all information is accurate before submitting the termination request.
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="employee_id" class="form-label fw-semibold">
                                            <i class="fas fa-user me-1 text-primary"></i>
                                            Select Employee <span class="text-danger">*</span>
                                        </label>
                                        <select wire:model="employee_id" id="employee_id" class="form-select shadow-sm border-secondary">
                                            <option value="">Choose an employee...</option>
                                            @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="termination_date" class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-1 text-primary"></i>
                                            Termination Date <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" wire:model="termination_date" id="termination_date"
                                            class="form-control shadow-sm border-secondary">
                                        @error('termination_date')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="reason" class="form-label fw-semibold">
                                            <i class="fas fa-comment-alt me-1 text-primary"></i>
                                            Termination Reason <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" wire:model="reason" id="reason"
                                            class="form-control shadow-sm border-secondary"
                                            placeholder="Enter the primary reason for termination">
                                        @error('reason')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="remarks" class="form-label fw-semibold">
                                            <i class="fas fa-sticky-note me-1 text-primary"></i>
                                            Additional Remarks <span class="text-muted">(Optional)</span>
                                        </label>
                                        <textarea wire:model="remarks" id="remarks"
                                            class="form-control shadow-sm border-secondary"
                                            rows="4"
                                            placeholder="Add any additional notes or context regarding this termination..."></textarea>
                                        @error('remarks')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 bg-light p-4">
                                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-danger shadow-sm" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-paper-plane me-2"></i>Submit Request
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Submitting...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Approval Confirmation Modal -->
            @if($showConfirmApprove)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow border-0">
                        <div class="modal-header bg-success text-white border-0">
                            <h5 class="modal-title fw-semibold">
                                <i class="fas fa-check-circle me-2"></i>Confirm Approval
                            </h5>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="mb-4">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-check fa-2x text-success"></i>
                                </div>
                            </div>
                            <h6 class="fw-semibold mb-3">Approve Termination Request</h6>
                            <p class="text-muted mb-0">
                                Are you sure you want to approve this termination request? This action cannot be undone and the employee will be notified immediately.
                            </p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button wire:click="approve" class="btn btn-success shadow-sm px-4" wire:loading.attr="disabled" wire:target="approve">
                                <span wire:loading.remove wire:target="approve">
                                    <i class="fas fa-check me-2"></i>Yes, Approve
                                </span>
                                <span wire:loading wire:target="approve">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Approving...
                                </span>
                            </button>
                            <button wire:click="cancelConfirmation" class="btn btn-secondary shadow-sm px-4" wire:loading.attr="disabled" wire:target="approve">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Rejection Confirmation Modal -->
            @if($showConfirmReject)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow border-0">
                        <div class="modal-header bg-danger text-white border-0">
                            <h5 class="modal-title fw-semibold">
                                <i class="fas fa-times-circle me-2"></i>Confirm Rejection
                            </h5>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="mb-4">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-times fa-2x text-danger"></i>
                                </div>
                            </div>
                            <h6 class="fw-semibold mb-3">Reject Termination Request</h6>
                            <p class="text-muted mb-0">
                                Are you sure you want to reject this termination request? The employee will be notified and can submit a new request if needed.
                            </p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button wire:click="reject" class="btn btn-danger shadow-sm px-4" wire:loading.attr="disabled" wire:target="reject">
                                <span wire:loading.remove wire:target="reject">
                                    <i class="fas fa-times me-2"></i>Yes, Reject
                                </span>
                                <span wire:loading wire:target="reject">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Rejecting...
                                </span>
                            </button>
                            <button wire:click="cancelConfirmation" class="btn btn-secondary shadow-sm px-4" wire:loading.attr="disabled" wire:target="reject">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- View Details Modal -->
        <div wire:ignore.self class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white border-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-times me-2 fs-5"></i>
                            <h5 class="modal-title mb-0 fw-semibold" id="viewDetailsModalLabel">
                                Employee Termination Details
                            </h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-4">
                        @if($selectedTermination)
                        <div class="row g-4">
                            <!-- Employee Information Card -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-primary mb-3 fw-semibold">
                                            <i class="fas fa-user me-2"></i>Employee Information
                                        </h6>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <span class="text-muted small">Employee Name</span>
                                            </div>
                                            <div class="col-sm-8">
                                                <span class="fw-medium">{{ $selectedTermination->employee->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Termination Details -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-danger mb-3 fw-semibold">
                                            <i class="fas fa-calendar-times me-2"></i>Termination Information
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="border-start border-3 border-danger ps-3">
                                                    <span class="text-muted small d-block">Termination Date</span>
                                                    <span class="fw-medium">
                                                        {{ \Carbon\Carbon::parse($selectedTermination->termination_date)->format('F d, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="border-start border-3 border-warning ps-3">
                                                    <span class="text-muted small d-block">Status</span>
                                                    <span class="badge rounded-pill 
                                                    @if($selectedTermination->status === 'completed') 
                                                        bg-success
                                                    @elseif($selectedTermination->status === 'pending') 
                                                        bg-warning text-dark
                                                    @else 
                                                        bg-secondary
                                                    @endif
                                                ">
                                                        {{ ucfirst($selectedTermination->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reason Section -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-warning mb-3 fw-semibold">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Reason for Termination
                                        </h6>
                                        <div class="bg-white p-3 rounded border-start border-4 border-warning">
                                            <p class="mb-0 text-dark">{{ $selectedTermination->reason }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks Section -->
                            @if($selectedTermination->remarks)
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-info mb-3 fw-semibold">
                                            <i class="fas fa-sticky-note me-2"></i>Additional Remarks
                                        </h6>
                                        <div class="bg-white p-3 rounded border-start border-4 border-info">
                                            <p class="mb-0 text-dark">{{ $selectedTermination->remarks }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @else
                        <!-- Loading State -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mb-0">Loading termination details...</p>
                        </div>
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer bg-light border-0 d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Confidential HR Information
                        </small>


                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button> -->
                    </div>
                </div>
            </div>
        </div>
        <livewire:layout.footer />
    </div>
</div>

<!-- Scripts -->
<script>
    // Enhanced modal handling with Bootstrap 5
    document.addEventListener('livewire:initialized', () => {
        // Listen for the close-modal event from Livewire
        Livewire.on('close-modal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('terminationModal'));
            if (modal) {
                modal.hide();
            }
        });

        // Reset form when modal is closed
        document.getElementById('terminationModal').addEventListener('hidden.bs.modal', function() {
            Livewire.dispatch('resetForm');
        });

        // Handle ESC key for confirmation modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                @this.call('cancelConfirmation');
            }
        });
    });
</script>