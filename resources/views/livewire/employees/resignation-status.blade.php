<div>
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -37px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px #dee2e6;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: -31px;
            top: 6px;
            bottom: -6px;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-title {
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 600;
        }

        .timeline-text {
            margin: 0;
            font-size: 12px;
            color: #6c757d;
        }
    </style>

    <div class="page-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="float-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Resignation Status</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Resignation Status</h4>
                        </div>
                    </div>
                </div>

                {{-- Flash Messages --}}
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if ($resignation)
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5 class="card-title mb-4">Resignation Details</h5>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold" style="width: 200px;">Resignation Date:</td>
                                                            <td>{{ \Carbon\Carbon::parse($resignation->resignation_date)->format('F j, Y') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Last Working Date:</td>
                                                            <td>{{ \Carbon\Carbon::parse($resignation->last_working_date)->format('F j, Y') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Notice Period:</td>
                                                            <td>{{ $resignation->notice_period_days }} days</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Status:</td>
                                                            <td>
                                                                @if($resignation->status === 'pending')
                                                                    <span class="badge bg-warning text-dark">{{ ucfirst($resignation->status) }}</span>
                                                                @elseif($resignation->status === 'approved')
                                                                    <span class="badge bg-success">{{ ucfirst($resignation->status) }}</span>
                                                                @elseif($resignation->status === 'withdrawn')
                                                                    <span class="badge bg-secondary">{{ ucfirst($resignation->status) }}</span>
                                                                @elseif($resignation->status === 'rejected')
                                                                    <span class="badge bg-danger">{{ ucfirst($resignation->status) }}</span>
                                                                @else
                                                                    <span class="badge bg-info">{{ ucfirst($resignation->status) }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Reason:</td>
                                                            <td>{{ $resignation->reason }}</td>
                                                        </tr>
                                                        @if($resignation->additional_comments)
                                                        <tr>
                                                            <td class="fw-bold">Additional Comments:</td>
                                                            <td>{{ $resignation->additional_comments }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($resignation->approver)
                                                        <tr>
                                                            <td class="fw-bold">Reviewed By:</td>
                                                            <td>{{ $resignation->approver->name ?? '-' }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($resignation->approved_at)
                                                        <tr>
                                                            <td class="fw-bold">Reviewed Date:</td>
                                                            <td>{{ \Carbon\Carbon::parse($resignation->approved_at)->format('F j, Y g:i A') }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($resignation->hr_comments)
                                                        <tr>
                                                            <td class="fw-bold">HR Comments:</td>
                                                            <td>{{ $resignation->hr_comments }}</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Actions</h5>
                                                </div>
                                                <div class="card-body">
                                          
                                                    
                                                    @if($resignation->status === 'pending')
                                                        <div class="d-grid gap-2">
                                                            <button type="button" 
                                                                    wire:click="withdrawResignation" 
                                                                    class="btn btn-outline-danger"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="withdrawResignation">
                                                                <span wire:loading wire:target="withdrawResignation">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    Processing...
                                                                </span>
                                                                <span wire:loading.remove wire:target="withdrawResignation">
                                                                    <i class="fas fa-times me-1"></i> Withdraw Resignation
                                                                </span>
                                                            </button>
                                                            <small class="text-muted">
                                                                You can withdraw your resignation while it's pending review.
                                                            </small>
                                                        </div>
                                                    @elseif($resignation->status === 'approved')
                                                        <div class="alert alert-success mb-0">
                                                            <i class="fas fa-check-circle me-2"></i>
                                                            Your resignation has been approved.
                                                        </div>
                                                    @elseif($resignation->status === 'rejected')
                                                        <div class="alert alert-danger mb-0">
                                                            <i class="fas fa-times-circle me-2"></i>
                                                            Your resignation has been rejected. You may submit a new one if needed.
                                                        </div>
                                                    @elseif($resignation->status === 'withdrawn')
                                                        <div class="alert alert-secondary mb-0">
                                                            <i class="fas fa-undo me-2"></i>
                                                            You have withdrawn this resignation.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            {{-- Timeline --}}
                                            @if($resignation->created_at)
                                            <div class="card mt-3">
                                                <div class="card-header">
                                                    <h6 class="card-title mb-0">Timeline</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="timeline">
                                                        <div class="timeline-item">
                                                            <div class="timeline-marker bg-primary"></div>
                                                            <div class="timeline-content">
                                                                <h6 class="timeline-title">Submitted</h6>
                                                                <p class="timeline-text">{{ \Carbon\Carbon::parse($resignation->created_at)->format('M j, Y g:i A') }}</p>
                                                            </div>
                                                        </div>
                                                        
                                                        @if($resignation->approved_at)
                                                        <div class="timeline-item">
                                                            <div class="timeline-marker bg-success"></div>
                                                            <div class="timeline-content">
                                                                <h6 class="timeline-title">{{ ucfirst($resignation->status) }}</h6>
                                                                <p class="timeline-text">{{ \Carbon\Carbon::parse($resignation->approved_at)->format('M j, Y g:i A') }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="mb-4">
                                            <i class="fas fa-file-alt fa-3x text-muted"></i>
                                        </div>
                                        <h5 class="text-muted">No Resignation Found</h5>
                                        <p class="text-muted mb-4">You haven't submitted any resignation request yet.</p>
                                        <a href="{{ route('employee.resignation.submit') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Submit Resignation
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Withdraw Confirmation Modal --}}
            @if ($showWithdrawConfirmation)
                <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    Confirm Withdrawal
                                </h5>
                            </div>
                            <div class="modal-body">
                                <p class="mb-3">Are you sure you want to withdraw your resignation request?</p>
                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Once withdrawn, you can submit a new resignation request if needed.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="cancelWithdraw">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                                <button type="button" class="btn btn-danger" wire:click="confirmWithdraw" wire:loading.attr="disabled">
                                    <span wire:loading wire:target="confirmWithdraw">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Withdrawing...
                                    </span>
                                    <span wire:loading.remove wire:target="confirmWithdraw">
                                        <i class="fas fa-check me-1"></i> Confirm Withdrawal
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>