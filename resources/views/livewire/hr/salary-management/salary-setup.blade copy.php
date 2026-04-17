<div class="page-wrapper">
    <style>
        /* Bounce animation */
        .animate-bounce {
            animation: bounce 0.6s;
        }
        @keyframes bounce {
            0% { transform: scale(1); }
            30% { transform: scale(1.2); }
            60% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }

        /* Highlight rows on change */
        .highlight-change {
            background-color: #d1fae5 !important;
            transition: background-color 0.8s ease;
        }
        .highlight-change.fade-out {
            background-color: transparent !important;
        }
    </style>

    <!-- Page Content -->
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <div class="float-end">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Salary Configuration</li>
                            </ol>
                        </div>
                        <h4 class="page-title mb-0">Salary Configuration</h4>
                    </div>
                </div>
            </div>

            <!-- Salary Configuration Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Month Selection & Bulk Copy -->
                            <div class="mb-4 d-flex gap-2 align-items-end">
                                <div>
                                    <label class="form-label fw-bold">Select Month</label>
                                    <input type="month" wire:model="selectedMonth" class="form-control w-auto">
                                </div>

                                <button wire:click="copyPreviousMonth"
                                        class="btn btn-warning"
                                        @disabled(!$selectedEmployeeId)
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="copyPreviousMonth">
                                        Copy Previous Month (Selected)
                                    </span>
                                    <span wire:loading wire:target="copyPreviousMonth">
                                        <i class="spinner-border spinner-border-sm"></i> Copying...
                                    </span>
                                </button>

                                <button wire:click="confirmBulkCopy"
                                        class="btn btn-danger"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="confirmBulkCopy">
                                        Bulk Copy Previous Month (All)
                                    </span>
                                    <span wire:loading wire:target="confirmBulkCopy">
                                        <i class="spinner-border spinner-border-sm"></i> Checking...
                                    </span>
                                </button>
                            </div>

                            <!-- Employee Selection -->
                            <div class="mb-4">
                                <select wire:model="selectedEmployeeId"
                                        wire:change="selectEmployee($event.target.value)"
                                        class="form-select w-auto">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if($selectedEmployeeId)
                                <!-- Basic Salary -->
                                <div class="mb-3">
                                    <label>Basic Salary</label>
                                    <input type="number" class="form-control" wire:model.lazy="basic_salary">
                                </div>

                                <!-- Allowances & Deductions -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Allowances & Deductions</h5>

                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Name</th>
                                                <th>Amount</th>
                                                <th>%</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($components as $index => $component)
                                                <tr class="{{ $rowHighlight[$index] ?? '' }}">
                                                    <td>
                                                        <select wire:model.lazy="components.{{ $index }}.type" class="form-control">
                                                            <option value="allowance">Allowance</option>
                                                            <option value="deduction">Deduction</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" wire:model.lazy="components.{{ $index }}.name" class="form-control" />
                                                    </td>
                                                    <td>
                                                        <input type="number" wire:model.lazy="components.{{ $index }}.amount" class="form-control" />
                                                    </td>
                                                    <td>
                                                        <input type="number" wire:model.lazy="components.{{ $index }}.percentage" class="form-control" />
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                                wire:click="removeComponent({{ $index }})">
                                                            X
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <button wire:click="addComponent" class="btn btn-primary mt-2" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="addComponent">+ Add Component</span>
                                        <span wire:loading wire:target="addComponent">
                                            <i class="spinner-border spinner-border-sm"></i> Adding...
                                        </span>
                                    </button>

                                    <div class="mt-3">
                                        <h5>Total Allowance: ₹{{ number_format($totalAllowance, 2) }}</h5>
                                        <h5>Total Deduction: ₹{{ number_format($totalDeduction, 2) }}</h5>
                                        <h4 class="{{ $netSalaryClass }} transition-all duration-500">
                                            Net Salary: ₹{{ number_format($netSalary, 2) }}
                                        </h4>
                                    </div>
                                </div>

                                <!-- Save Configuration -->
                                <button wire:click="save" class="btn btn-success" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">Save Configuration</span>
                                    <span wire:loading wire:target="save">
                                        <i class="spinner-border spinner-border-sm"></i> Saving...
                                    </span>
                                </button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div><!-- container-fluid -->

        <!-- Footer -->
        <livewire:layout.footer />

        <!-- Confirm Bulk Copy Modal -->
        <div class="modal fade @if($showConfirmBulkCopy) show d-block @endif" tabindex="-1"
             style="{{ !$showConfirmBulkCopy ? 'display:none;' : '' }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Confirm Bulk Copy</h5>
                        <button type="button" class="btn-close" wire:click="$set('showConfirmBulkCopy', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            You are about to copy <strong>{{ $bulkCopySummary['totalPrev'] }}</strong> employees’
                            salary setups from the previous month to <strong>{{ $selectedMonth }}</strong>.
                        </p>
                        <ul>
                            <li>Total employees with previous data: <strong>{{ $bulkCopySummary['totalPrev'] }}</strong></li>
                            <li>Already have data in this month:
                                <strong class="text-danger">{{ $bulkCopySummary['alreadyExists'] }}</strong>
                            </li>
                            <li>Will be copied: <strong class="text-success">{{ $bulkCopySummary['willBeCopied'] }}</strong></li>
                        </ul>
                        <p class="text-danger mb-0">⚠ Existing data for this month will be overwritten.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showConfirmBulkCopy', false)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="bulkCopyPreviousMonthConfirmed" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="bulkCopyPreviousMonthConfirmed">Yes, Copy</span>
                            <span wire:loading wire:target="bulkCopyPreviousMonthConfirmed">
                                <i class="spinner-border spinner-border-sm"></i> Copying...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if($showConfirmBulkCopy)
            <div class="modal-backdrop fade show"></div>
        @endif

    </div><!-- page-content-tab -->
</div><!-- page-wrapper -->

<!-- Scripts -->
<script>
    Livewire.on('resetNetSalaryClass', () => {
        setTimeout(() => Livewire.dispatch('clearNetSalaryClass'), 600);
    });

    Livewire.on('resetRowHighlight', index => {
        setTimeout(() => Livewire.dispatch('clearRowHighlight', { index }), 800);
    });
</script>
