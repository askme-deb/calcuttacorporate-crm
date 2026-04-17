<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Salary History</h5>

                    <div class="d-flex gap-2">
                        <select wire:model.live="selectedEmployee" class="form-select form-select-sm">
                            <option value="">All Employees</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="month" class="form-select form-select-sm">
                            <option value="">All Months</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>

                        <select wire:model.live="year" class="form-select form-select-sm">
                            @for ($y = 2023; $y <= date('Y'); $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>

                        <button wire:click="exportExcel" wire:loading.attr="disabled" wire:target="exportExcel"
                            class="btn btn-sm btn-success d-flex align-items-center gap-1">
                            <span wire:loading.remove wire:target="exportExcel">
                                <i class="fas fa-file-csv"></i> Export Excel
                            </span>
                            <span wire:loading wire:target="exportExcel">
                                <i class="fas fa-spinner fa-spin"></i> Exporting...
                            </span>
                        </button>

                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle mb-0 shadow-sm">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Gross (₹)</th>
                                    <th>Net (₹)</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                    <th>Payment Mode</th>
                                    <th>Remarks</th>
                                    <th>Month</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $row->employee->full_name }}</td>
                                        <td class="text-success fw-semibold">₹{{ number_format($row->gross_salary, 2) }}
                                        </td>
                                        <td class="text-primary fw-semibold">₹{{ number_format($row->net_salary, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill bg-{{ $row->is_paid ? 'success' : 'danger' }}">
                                                {{ $row->is_paid ? 'Paid' : 'Unpaid' }}
                                            </span>
                                        </td>
                                        <td>{{ $row->payment_date ? \Carbon\Carbon::parse($row->payment_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td>{{ $row->payment_mode ?? '-' }}</td>
                                        <td class="text-muted">{{ $row->remarks ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->month)->format('M, Y') }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-info px-3" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#details{{ $row->id }}">
                                                <i class="fas fa-eye me-1"></i> View
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Details Row -->
                                    <tr class="collapse bg-light" id="details{{ $row->id }}">
                                        <td colspan="10" class="p-3 border-top-0">
                                            <div class="row text-center">
                                                <!-- Basic Salary -->
                                                <div class="col-md-4 mb-2">
                                                    <div class="card border-primary shadow-sm h-100">
                                                        <div class="card-header py-2 bg-primary text-white fw-bold">
                                                            Basic Salary</div>
                                                        <div class="card-body p-2">
                                                            @forelse($row->basic as $basic)
                                                                <div
                                                                    class="d-flex justify-content-between border-bottom py-1">
                                                                    <span>{{ $basic->name }}</span>
                                                                    <span
                                                                        class="fw-semibold">₹{{ number_format($basic->amount, 2) }}</span>
                                                                </div>
                                                            @empty
                                                                <div class="text-muted small">No basic salary</div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Allowances -->
                                                <div class="col-md-4 mb-2">
                                                    <div class="card border-success shadow-sm h-100">
                                                        <div class="card-header py-2 bg-success text-white fw-bold">
                                                            Allowances</div>
                                                        <div class="card-body p-2">
                                                            @php $totalAllowances = $row->allowances->sum('amount'); @endphp
                                                            @forelse($row->allowances as $allowance)
                                                                <div
                                                                    class="d-flex justify-content-between border-bottom py-1">
                                                                    <span>{{ $allowance->name }}</span>
                                                                    <span
                                                                        class="fw-semibold">₹{{ number_format($allowance->amount, 2) }}</span>
                                                                </div>
                                                            @empty
                                                                <div class="text-muted small">No allowances</div>
                                                            @endforelse

                                                            <hr class="my-2">
                                                            <div class="d-flex justify-content-between fw-bold">
                                                                <span>Total</span>
                                                                <span>₹{{ number_format($totalAllowances, 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Deductions -->
                                                <div class="col-md-4 mb-2">
                                                    <div class="card border-danger shadow-sm h-100">
                                                        <div class="card-header py-2 bg-danger text-white fw-bold">
                                                            Deductions</div>
                                                        <div class="card-body p-2">
                                                            @php $totalDeductions = $row->deductions->sum('amount'); @endphp
                                                            @forelse($row->deductions as $deduction)
                                                                <div
                                                                    class="d-flex justify-content-between border-bottom py-1">
                                                                    <span>{{ $deduction->name }}</span>
                                                                    <span
                                                                        class="fw-semibold">₹{{ number_format($deduction->amount, 2) }}</span>
                                                                </div>
                                                            @empty
                                                                <div class="text-muted small">No deductions</div>
                                                            @endforelse

                                                            <hr class="my-2">
                                                            <div class="d-flex justify-content-between fw-bold">
                                                                <span>Total</span>
                                                                <span>₹{{ number_format($totalDeductions, 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Summary Row -->
                                            <div class="row mt-3">
                                                <div class="col-12 text-end fw-bold">
                                                    <span class="me-3 text-success">Gross:
                                                        ₹{{ number_format($row->gross_salary, 2) }}</span>
                                                    <span class="me-3 text-danger">Deductions:
                                                        ₹{{ number_format($totalDeductions, 2) }}</span>
                                                    <span class="text-primary">Net Pay:
                                                        ₹{{ number_format($row->net_salary, 2) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle me-1"></i> No salary history found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>



                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    window.addEventListener('download-csv', event => {
        // Use encodeURIComponent in case of spaces in filename
        window.location.href = event.detail.url;
    });
</script>
