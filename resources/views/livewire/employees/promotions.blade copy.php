<div>
    <style>
        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
        }

        .avatar-lg {
            width: 4rem;
            height: 4rem;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .badge {
            font-size: 0.75em;
        }
    </style>
    <div class="page-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="row mb-4">

                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}"
                                        class="text-decoration-none">
                                        <i class="fas fa-home me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Promotions Management</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Promotions Management</h4>
                        <p class="text-muted">Manage employee promotions requests</p>
                    </div>
                </div>

                <!-- Promotion Form Card -->


                <!-- Promotion History Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header">
                                <div
                                    class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-history text-success"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0 fw-semibold">Promotion History</h5>
                                            <p class="card-text text-muted mb-0 small">Complete record of employee
                                                promotions and awards</p>
                                        </div>
                                    </div>
                                    <div
                                        class="d-flex flex-column flex-sm-row gap-2 align-items-stretch align-items-sm-center">
                                        <div class="search-input position-relative" style="min-width: 280px;">
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-end-0">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                                <input type="text"
                                                    class="form-control border-start-0 ps-0"
                                                    placeholder="Search employees, positions, or dates..."
                                                    wire:model.live="search">
                                                @if($search)
                                                <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    wire:click="clearSearch"
                                                    title="Clear search">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#promotionModal">
                                            <i class="fas fa-plus me-1"></i>New Promotion
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                @if ($promotionList && count($promotionList) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0 fw-semibold">
                                                    <i class="fas fa-user me-1 text-muted"></i>Employee
                                                </th>
                                                <th class="border-0 fw-semibold">
                                                    <i class="fas fa-arrow-right me-1 text-muted"></i>Position
                                                    Change
                                                </th>
                                                <th class="border-0 fw-semibold">
                                                    <i class="fas fa-chart-line me-1 text-muted"></i>Salary Change
                                                </th>
                                                <th class="border-0 fw-semibold">
                                                    <i class="fas fa-calendar me-1 text-muted"></i>Promotion Date
                                                </th>
                                                <th class="border-0 fw-semibold">
                                                    <i class="fas fa-file me-1 text-muted"></i>Documents
                                                </th>
                                                <th class="border-0 fw-semibold text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($promotionList as $promo)
                                            <tr>
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                            <span
                                                                class="text-primary fw-semibold">{{ strtoupper(substr($promo->employee->name, 0, 1)) }}</span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold">
                                                                {{ $promo->employee->name }}
                                                            </h6>
                                                            <small class="text-muted">ID:
                                                                {{ $promo->employee->emp_code }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center">
                                                        @if ($promo->previousDesignation->name)
                                                        <span
                                                            class="badge bg-light text-dark me-2">{{ $promo->previousDesignation->name }}</span>
                                                        <i class="fas fa-arrow-right text-muted mx-1"></i>
                                                        @endif
                                                        <span
                                                            class="badge bg-primary">{{ $promo->newDesignation->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    @if ($promo->previous_salary && $promo->new_salary)
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="text-muted me-2">₹{{ number_format($promo->previous_salary, 2) }}</span>
                                                        <i class="fas fa-arrow-right text-success mx-1"></i>
                                                        <span
                                                            class="fw-semibold text-success">₹{{ number_format($promo->new_salary, 2) }}</span>
                                                    </div>
                                                    <small class="text-success">
                                                        <i class="fas fa-arrow-up me-1"></i>
                                                        +₹{{ number_format($promo->new_salary - $promo->previous_salary, 2) }}
                                                    </small>
                                                    @else
                                                    <span
                                                        class="fw-semibold">₹{{ number_format($promo->new_salary, 2) }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                        <span
                                                            class="fw-semibold">{{ \Carbon\Carbon::parse($promo->promotion_date)->format('d M Y') }}</span>
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($promo->promotion_date)->diffForHumans() }}</small>
                                                </td>
                                                <td class="py-3">


                                                    @if ($promo->getFirstMediaUrl('promotion_letters'))
                                                    <a href="{{ $promo->getFirstMediaUrl('promotion_letters') }}"
                                                        class="btn btn-outline-primary btn-sm"
                                                        target="_blank">
                                                        <i class="fas fa-file-pdf me-1"></i>View Letter
                                                    </a>
                                                    @else
                                                    <span class="text-muted small">
                                                        <i class="fas fa-minus me-1"></i>No document
                                                    </span>
                                                    @endif
                                                </td>
                                                <td class="py-3 text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    wire:click.prevent="view({{ $promo->id }})">
                                                                    <i class="fas fa-eye me-2"></i>View Details
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    wire:click.prevent="edit({{ $promo->id }})">
                                                                    <i class="fas fa-edit me-2"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger"
                                                                    href="#" onclick="confirmDeletion({{ $promo->id }})">
                                                                    <i class="fas fa-trash me-2"></i>Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <div
                                        class="avatar-lg bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                        <i class="fas fa-trophy text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">No Promotion Records Found</h5>
                                    <p class="text-muted mb-0">Start by creating your first employee promotion
                                        record
                                        above.</p>
                                </div>
                                @endif
                            </div>
                            @if ($promotionList && count($promotionList) > 0)
                            <div class="card-footer bg-light border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Showing {{ count($promotionList) }} promotion records
                                    </small>
                                    <nav aria-label="Promotion records pagination">
                                        <!-- Add pagination here if needed -->
                                        <small class="text-muted">Page 1 of 1</small>
                                    </nav>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>



            <!-- Promotion Modal -->
            <div class="modal fade" id="promotionModal" tabindex="-1" aria-labelledby="promotionModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content shadow">
                        <form wire:submit.prevent="save">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title fw-bold" id="promotionModalLabel">
                                    <i class="fas fa-trophy me-2 text-light"></i>Employee Promotion Form
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <p class="text-muted small mb-4">
                                    Fill in the details below to create a new promotion record.
                                </p>

                                <div class="row g-3">

                                    <!-- Employee -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-user me-1 text-muted"></i>Employee *
                                        </label>
                                        <select wire:model="employee_id" wire:change="handleEmployeeChange" class="form-select" required>
                                            <option value="">Choose employee...</option>
                                            @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Promotion Date -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-1 text-muted"></i>Promotion Date *
                                        </label>
                                        <input type="date" wire:model="promotion_date" class="form-control"
                                            required>
                                        @error('promotion_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Previous Designation -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-briefcase me-1 text-muted"></i>Previous Designation
                                        </label>

                                        <select wire:model="previous_designation_id"
                                            wire:key="designation-select-{{ $employee_id }}"
                                            class="form-select">
                                            <option value="">Choose designation...</option>
                                            @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}"
                                                {{ $designation->id == $previous_designation_id ? 'selected' : '' }}
                                                {{ $designation->id != $previous_designation_id ? 'disabled' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                            @endforeach
                                        </select>

                                        @error('previous_designation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- New Designation -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-star me-1 text-muted"></i>New Designation *
                                        </label>
                                        <select wire:model="new_designation_id" class="form-select">
                                            @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('new_designation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Previous Salary -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-rupee-sign me-1 text-muted"></i>Previous Salary
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" wire:model="previous_salary"
                                                class="form-control" placeholder="0.00">
                                        </div>
                                        @error('previous_salary')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- New Salary -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-rupee-sign me-1 text-muted"></i>New Salary *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" wire:model="new_salary"
                                                class="form-control" placeholder="0.00" required>
                                        </div>
                                        @error('new_salary')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Promotion Letter -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-file-pdf me-1 text-muted"></i>Promotion Letter (PDF)
                                        </label>
                                        <input type="file" wire:model="promotion_letter" class="form-control"
                                            accept=".pdf">
                                        <div class="form-text">Upload PDF only (Max: 5MB)</div>
                                        @error('promotion_letter')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Remarks -->
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-comment me-1 text-muted"></i>Remarks
                                        </label>
                                        <textarea wire:model="remarks" class="form-control" rows="3"
                                            placeholder="Enter any additional notes or remarks..."></textarea>
                                        @error('remarks')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer bg-light">
                                <div class="text-muted small me-auto">
                                    <i class="fas fa-info-circle me-1"></i>Fields marked with * are required.
                                </div>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Save Promotion
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            @if ($selectedPromotion && !$editMode)
            <div class="modal fade show" style="display: block;" tabindex="-1" aria-modal="true"
                role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fas fa-user-tag me-2"></i>Promotion Details</h5>
                            <button type="button" class="btn-close" wire:click="resetFields"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="fw-semibold">Employee Name</label>
                                    <div>{{ $selectedPromotion->employee->name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Employee ID</label>
                                    <div>{{ $selectedPromotion->employee->id }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Previous Designation</label>
                                    <div>{{ $selectedPromotion->previousDesignation->name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">New Designation</label>
                                    <div>{{ $selectedPromotion->newDesignation->name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Previous Salary</label>
                                    <div>₹{{ number_format($selectedPromotion->previous_salary, 2) }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">New Salary</label>
                                    <div>₹{{ number_format($selectedPromotion->new_salary, 2) }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Promotion Date</label>
                                    <div>
                                        {{ \Carbon\Carbon::parse($selectedPromotion->promotion_date)->format('d M, Y') }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="fw-semibold">Remarks</label>
                                    <div>{{ $selectedPromotion->remarks ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="fw-semibold">Promotion Letter</label>
                                    <div>
                                        @if ($selectedPromotion->getFirstMediaUrl('promotion_letters'))
                                        <a href="{{ $selectedPromotion->getFirstMediaUrl('promotion_letters') }}"
                                            class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="fas fa-file-pdf me-1"></i>View Letter
                                        </a>
                                        @else
                                        <span class="text-muted">No document uploaded</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" wire:click="resetFields" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Backdrop -->
            <div class="modal-backdrop fade show"></div>
            @endif

            @if ($selectedPromotion && $editMode)
            <div class="modal fade show" style="display: block;" tabindex="-1" aria-modal="true"
                role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content shadow">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Promotion</h5>
                            <button type="button" class="btn-close" wire:click="resetFields"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="update">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Employee</label>
                                        <select class="form-select" wire:model="employee_id">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Promotion Date</label>
                                        <input type="date" class="form-control" wire:model="promotion_date">
                                        @error('promotion_date')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Previous Designation</label>
                                        <select wire:model="previous_designation_id" class="form-select">
                                            @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">New Designation</label>
                                        <select wire:model="new_designation_id" class="form-select">
                                            @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Previous Salary</label>
                                        <input type="number" step="0.01" class="form-control"
                                            wire:model="previous_salary">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">New Salary</label>
                                        <input type="number" step="0.01" class="form-control"
                                            wire:model="new_salary">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Remarks</label>
                                        <textarea class="form-control" wire:model="remarks" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Replace Promotion Letter (PDF)</label>
                                        <input type="file" class="form-control" wire:model="promotion_letter"
                                            accept="application/pdf">
                                        @error('promotion_letter')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2"
                                        wire:click="resetFields">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Promotion</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal backdrop -->
            <div class="modal-backdrop fade show"></div>
            @endif

            <livewire:layout.footer />
        </div>
    </div>
</div>

<script>
    // Add this script to your Blade template
    document.addEventListener('livewire:init', function() {
        Livewire.on('close-modal', function() {
            // Close the promotion modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('promotionModal'));
            if (modal) {
                modal.hide();
            }
        });
    });


    function confirmDeletion(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('delete', {
                    id: itemId
                }); // Dispatch Livewire event
            }
        });
    }
</script>