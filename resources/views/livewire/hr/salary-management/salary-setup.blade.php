<div class="page-wrapper">
    <style>
        /* Bounce animation for Net Salary highlight */
        .animate-bounce {
            animation: bounce 0.6s;
        }
        @keyframes bounce {
            0% { transform: scale(1); }
            30% { transform: scale(1.15); }
            60% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }

        /* Row highlight fade */
        .highlight-change {
            background-color: #d1fae5 !important;
            transition: background-color 0.8s ease;
        }
        .highlight-change.fade-out {
            background-color: transparent !important;
        }
    </style>

    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="page-title mb-0 fw-bold">💼 Salary Configuration</h4>
                            <ol class="breadcrumb mb-0 small text-muted">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Salary Configuration</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Month Selection & Bulk Copy Buttons -->
            <div class="row mb-4 g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label fw-bold">Select Month</label>
                    <input type="month" wire:model="selectedMonth" class="form-control">
                </div>
                <div class="col-auto">
                    <button wire:click="copyPreviousMonth" class="btn btn-warning"
                            @disabled(!$selectedEmployeeId) wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="copyPreviousMonth">
                            Copy Previous Month (Selected)
                        </span>
                        <span wire:loading wire:target="copyPreviousMonth">
                            <i class="spinner-border spinner-border-sm"></i> Copying...
                        </span>
                    </button>
                </div>
                <div class="col-auto">
                    <button wire:click="confirmBulkCopy" class="btn btn-danger" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="confirmBulkCopy">
                            Bulk Copy Previous Month (All)
                        </span>
                        <span wire:loading wire:target="confirmBulkCopy">
                            <i class="spinner-border spinner-border-sm"></i> Checking...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Employee Selection -->
            <div class="mb-4">
                <label class="form-label fw-bold">Select Employee</label>
                <select wire:model="selectedEmployeeId"
                        wire:change="selectEmployee($event.target.value)" class="form-select w-auto">
                    <option value="">Select Employee</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
            </div>

            @if ($selectedEmployeeId)
                <!-- Basic Salary -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Basic Salary</label>
                    <input type="number" class="form-control w-auto" wire:model.lazy="basic_salary">
                </div>

                <!-- Professional Summary Cards Inline -->
                <div class="row g-3 mb-4">

                    <!-- Attendance Summary -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 border-start border-4 border-info">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-info mb-2">Attendance Summary</h6>
                                <div class="small text-muted">
                                    <p class="mb-1">Total Calendar Days: <span class="fw-bold text-dark">{{ $totalCalendarDays }}</span></p>
                                    <p class="mb-1">Non-Working Days: <span class="fw-bold text-dark">{{ $nonWorkingDaysCount }}</span></p>
                                    <p class="mb-1">Holiday Days: <span class="fw-bold text-dark">{{ $holidayDaysCount }}</span></p>
                                    <p class="mb-1">Working Days: <span class="fw-bold text-dark">{{ $workingDays }}</span></p>
                                    <p class="mb-0">Present Days: <span class="fw-bold text-dark">{{ $presentDays }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yearly Leave Summary -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 border-start border-4 border-success">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-success mb-2">Yearly Leave Summary</h6>
                                <div class="small text-muted">
                                    <p class="mb-1">Allocated: <span class="fw-bold text-dark">{{ $totalCasualLeavesForYear }}</span></p>
                                    <p class="mb-1">Used: <span class="fw-bold text-dark">{{ $totalCasualLeavesUsedThisYear }}</span></p>
                                    <p class="mb-1">Balance: <span class="fw-bold text-dark">{{ $casualLeaveBalance }}</span></p>
                                </div>

                                @php
                                    $leaveUsagePercent = $totalCasualLeavesForYear > 0 
                                        ? ($totalCasualLeavesUsedThisYear / $totalCasualLeavesForYear) * 100 
                                        : 0;
                                @endphp

                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                        style="width: {{ $leaveUsagePercent }}%" 
                                        aria-valuenow="{{ $leaveUsagePercent }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">{{ round($leaveUsagePercent,1) }}% Used</small>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Leave Summary -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 border-start border-4 border-warning">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-warning mb-2">Monthly Leave Summary</h6>
                                <div class="small text-muted">
                                    <p class="mb-1">Casual: <span class="fw-bold text-dark">{{ $casualLeavesUsedThisMonth }}</span></p>
                                    <p class="mb-1">Medical: <span class="fw-bold text-dark">{{ $medicalLeavesUsedThisMonth }}</span></p>
                                    <p class="mb-0">Half-Day: <span class="fw-bold text-dark">{{ $halfDayLeavesUsedThisMonth }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LOP & Deduction Summary -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 border-start border-4 border-danger">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-danger mb-2">LOP & Deduction</h6>
                                <div class="small text-muted">
                                    <p class="mb-1">Absent Days: <span class="fw-bold text-dark">{{ $absentDays }}</span></p>
                                    <p class="mb-1">Late Count: <span class="fw-bold text-dark">{{ $lateCount }}</span> ({{ floor($lateCount / 3) }} Days Deducted)</p>
                                    <p class="mb-1">Deductible Absences: <span class="fw-bold text-dark">{{ max(0, $absentDays - 2) }}</span></p>

                                    @php
                                        $perDaySalary = $basic_salary > 0 && $workingDays > 0 
                                            ? $basic_salary / $workingDays 
                                            : 0;
                                        $lateDeduction = floor($lateCount / 3) * $perDaySalary;
                                        $absentDeduction = max(0, $absentDays - 2) * $perDaySalary;
                                        $totalDeduction = $lateDeduction + $absentDeduction;
                                    @endphp

                                    <p class="mb-1">Late Deduction: <span class="fw-bold text-dark">₹{{ round($lateDeduction) }}</span></p>
                                    <p class="mb-1">Absent Deduction: <span class="fw-bold text-dark">₹{{ round($absentDeduction) }}</span></p>
                                </div>
                            </div>
                            <div class="card-footer bg-light fw-bold text-danger py-2">
                                Total Deduction: ₹{{ round($totalDeduction) }}
                            </div>
                        </div>
                    </div>

                </div><!-- End Summary Cards Row -->

                <!-- Professional Allowances & Deductions Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Allowances & Deductions</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 120px;">Type</th>
                                        <th>Name</th>
                                        <th style="width: 120px;" class="text-end">Amount (₹)</th>
                                        <th style="width: 80px;" class="text-end">%</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($components as $index => $component)
                                        <tr class="{{ $rowHighlight[$index] ?? '' }}">
                                            <td>
                                                <select wire:model.lazy="components.{{ $index }}.type" class="form-select form-select-sm">
                                                    <option value="allowance">Allowance</option>
                                                    <option value="deduction">Deduction</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select wire:model.lazy="components.{{ $index }}.name" class="form-select form-select-sm">
                                                    <option value="">Select Component</option>
                                                    @foreach ($masterComponents as $mc)
                                                        <option value="{{ $mc->name }}">{{ $mc->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       wire:model.lazy="components.{{ $index }}.amount"
                                                       class="form-control form-control-sm text-end"
                                                       @if ($component['name'] === 'Late Penalty') readonly @endif />
                                            </td>
                                            <td>
                                                <input type="number"
                                                       wire:model.lazy="components.{{ $index }}.percentage"
                                                       class="form-control form-control-sm text-end"
                                                       @if ($component['name'] === 'Late Penalty') readonly @endif />
                                            </td>
                                            <td class="text-center">
                                                @if ($component['name'] !== 'Late Penalty')
                                                    <button type="button" class="btn btn-outline-danger btn-sm px-2 py-0"
                                                            wire:click="removeComponent({{ $index }})">
                                                        ✕
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button wire:click="addComponent" class="btn btn-sm btn-primary mt-2" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="addComponent">+ Add Component</span>
                            <span wire:loading wire:target="addComponent">
                                <i class="spinner-border spinner-border-sm"></i> Adding...
                            </span>
                        </button>

                        <!-- Payroll Summary -->
                        <div class="row mt-4 g-3">
                            <div class="col-md-4">
                                <div class="alert alert-success py-2 mb-0 text-center">
                                    <strong>Total Allowance:</strong> ₹{{ number_format($totalAllowance, 2) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-danger py-2 mb-0 text-center">
                                    <strong>Total Deduction:</strong> ₹{{ number_format($totalDeduction, 2) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-primary py-2 mb-0 animate-bounce fw-bold fs-5 text-center">
                                    Net Salary: ₹{{ number_format($netSalary, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="mt-4 text-end">
                            <button wire:click="save" class="btn btn-success px-4" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">💾 Save Configuration</span>
                                <span wire:loading wire:target="save">
                                    <i class="spinner-border spinner-border-sm"></i> Saving...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div><!-- container-fluid -->

        <!-- Footer -->
        <livewire:layout.footer />

        <!-- Confirm Bulk Copy Modal -->
        <div class="modal fade @if ($showConfirmBulkCopy) show d-block @endif" tabindex="-1"
            style="{{ !$showConfirmBulkCopy ? 'display:none;' : '' }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Confirm Bulk Copy</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showConfirmBulkCopy', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            You are about to copy <strong>{{ $bulkCopySummary['totalPrev'] }}</strong> employees’
                            salary setups from the previous month to <strong>{{ $selectedMonth }}</strong>.
                        </p>
                        <ul>
                            <li>Total employees with previous data:
                                <strong>{{ $bulkCopySummary['totalPrev'] }}</strong></li>
                            <li>Already have data in this month:
                                <strong class="text-danger">{{ $bulkCopySummary['alreadyExists'] }}</strong>
                            </li>
                            <li>Will be copied: <strong
                                    class="text-success">{{ $bulkCopySummary['willBeCopied'] }}</strong></li>
                        </ul>
                        <p class="text-danger mb-0">⚠ Existing data for this month will be overwritten.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showConfirmBulkCopy', false)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="bulkCopyPreviousMonthConfirmed"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="bulkCopyPreviousMonthConfirmed">Yes, Copy</span>
                            <span wire:loading wire:target="bulkCopyPreviousMonthConfirmed">
                                <i class="spinner-border spinner-border-sm"></i> Copying...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if ($showConfirmBulkCopy)
            <div class="modal-backdrop fade show"></div>
        @endif

    </div><!-- page-content-tab -->
</div><!-- page-wrapper -->

<script>
    Livewire.on('resetNetSalaryClass', () => {
        setTimeout(() => Livewire.dispatch('clearNetSalaryClass'), 600);
    });
    Livewire.on('resetRowHighlight', index => {
        setTimeout(() => Livewire.dispatch('clearRowHighlight', { index }), 800);
    });
</script>
