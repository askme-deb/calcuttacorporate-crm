<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard')}}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Submit Resignation</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Submit Resignation</h4>
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
                        <div class="card-body mb-n3">
                            @if ($existingResignation)
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading">Resignation Already Submitted</h4>
                                    <p>You already have a resignation request that is {{ $existingResignation->status }}.</p>
                                    <hr>
                                    <p class="mb-0">
                                        <a href="{{ route('employee.resignation.status') }}" class="btn btn-warning">
                                            View Status
                                        </a>
                                    </p>
                                </div>
                            @else
                                <form wire:submit.prevent="submitResignation">
                                    <div class="row">
                                        <div class="col-lg-9">
                                            <div class="mb-3 row">
                                                <label for="resignation_date" class="col-sm-3 col-form-label text-end">Resignation Date <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <input class="form-control @error('resignation_date') is-invalid @enderror"
                                                           wire:model.live="resignation_date"
                                                           type="date"
                                                           id="resignation_date"
                                                           min="{{ date('Y-m-d') }}" />
                                                    @error('resignation_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="notice_period_days" class="col-sm-3 col-form-label text-end">Notice Period (Days) <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <input class="form-control @error('notice_period_days') is-invalid @enderror"
                                                           wire:model.live="notice_period_days"
                                                           type="number"
                                                           id="notice_period_days"
                                                           min="1"
                                                           max="90">
                                                    @error('notice_period_days')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="last_working_date" class="col-sm-3 col-form-label text-end">Last Working Date</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control"
                                                           type="date"
                                                           wire:model="last_working_date"
                                                           id="last_working_date"
                                                           readonly />
                                                    @error('last_working_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Automatically calculated based on resignation date and notice period</small>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="reason" class="col-sm-3 col-form-label text-end">Reason <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control @error('reason') is-invalid @enderror"
                                                              rows="4"
                                                              wire:model.blur="reason"
                                                              id="reason"
                                                              placeholder="Please provide your reason for resignation (minimum 10 characters)"></textarea>
                                                    @error('reason')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">{{ strlen($reason ?? '') }}/1000 characters</small>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="additional_comments" class="col-sm-3 col-form-label text-end">Additional Comments</label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control @error('additional_comments') is-invalid @enderror"
                                                              rows="4"
                                                              wire:model.blur="additional_comments"
                                                              id="additional_comments"
                                                              placeholder="Any additional comments (optional)"></textarea>
                                                    @error('additional_comments')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">{{ strlen($additional_comments ?? '') }}/1000 characters</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Actions</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-grid gap-2">
                                                        <button type="button" class="btn btn-secondary" wire:click="cancel">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <span wire:loading wire:target="submitResignation">
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                Loading...
                                                            </span>
                                                            <span wire:loading.remove wire:target="submitResignation">
                                                                Submit Resignation
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Confirmation Modal --}}
            @if($showConfirmation)
                <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Resignation Submission</h5>
                            </div>
                            <div class="modal-body">
                                <p><strong>Are you sure you want to submit your resignation?</strong></p>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <p><strong>Resignation Date:</strong> {{ \Carbon\Carbon::parse($resignation_date)->format('M d, Y') }}</p>
                                        <p><strong>Last Working Date:</strong> {{ \Carbon\Carbon::parse($last_working_date)->format('M d, Y') }}</p>
                                        <p><strong>Notice Period:</strong> {{ $notice_period_days }} days</p>
                                        <p><strong>Reason:</strong> {{ Str::limit($reason, 100) }}</p>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <small>Once submitted, you cannot modify or cancel this resignation request.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="cancelConfirmation">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-danger" wire:click="confirmSubmission">
                                    <span wire:loading wire:target="confirmSubmission">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Submitting...
                                    </span>
                                    <span wire:loading.remove wire:target="confirmSubmission">
                                        Confirm Submission
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <livewire:layout.footer />
    </div>
</div>
