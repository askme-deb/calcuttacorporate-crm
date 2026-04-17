<div class="page-wrapper">
    <!-- Page Content-->
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
                                <li class="breadcrumb-item active">Payroll Management</li>
                            </ol>
                        </div>
                        <h4 class="page-title mb-0">Payroll Management</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Month Selector & Generate Button -->
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                                <div class="d-flex gap-2">
                                    <input type="month" wire:model="month" class="form-control w-auto">
                                    <button wire:click="generatePreview" class="btn btn-primary">
                                        <i class="bi bi-eye"></i> Generate Payroll Preview
                                    </button>
                                </div>
                                <h2 class="h5 fw-bold mt-3 mt-md-0 text-md-end">
                                    Payroll: {{ \Carbon\Carbon::parse($month)->format('F, Y') }}
                                </h2>
                            </div>

                            <!-- Flash Message -->
                            @if (session()->has('message'))
                            <div class="alert alert-success mb-3">{{ session('message') }}</div>
                            @endif

                            <!-- Payroll Preview Table -->
                            @if($payrollPreview)
                            <h3 class="h6 fw-semibold mb-2">Preview Payroll</h3>
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Employee</th>
                                            <th class="text-end">Basic</th>
                                            <th class="text-end">Allowances</th>
                                            <th class="text-end">Bonuses</th>
                                            <th class="text-end">Deductions</th>
                                            <th class="text-end">Gross</th>
                                            <th class="text-end">Net</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payrollPreview as $row)
                                        <tr>
                                            <td>{{ $row['name'] }}</td>
                                            <td class="text-end">{{ number_format($row['basic'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['allowances'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['bonuses'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['deductions'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['gross'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['net'], 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button wire:click="finalizePayroll" class="btn btn-primary">
                                Finalize Payroll
                            </button>
                            @endif

                            <!-- Payroll Summary -->
                            @if($existingPayrolls->count())
                            <div class="card shadow-sm my-4">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">
                                            Payroll Summary for {{ \Carbon\Carbon::parse($month)->format('F, Y') }}
                                        </h5>
                                        <span class="badge fs-6 {{ $this->payrollSummary['unpaid'] > 0 ? 'bg-danger' : 'bg-success' }}">
                                            {{ $this->payrollSummary['unpaid'] > 0 
                                        ? $this->payrollSummary['unpaid'].' Unpaid' 
                                        : 'All Paid' }}
                                        </span>
                                    </div>

                                    <p class="mb-2">
                                        Paid: <strong>{{ $this->payrollSummary['paid'] }}</strong> /
                                        Total: <strong>{{ $this->payrollSummary['total'] }}</strong>
                                        ({{ $this->payrollSummary['percent'] }}%)
                                    </p>

                                    <div class="progress mb-3" style="height: 12px;">
                                        <div class="progress-bar 
                                    {{ $this->payrollSummary['percent'] < 50 ? 'bg-danger' : 
                                       ($this->payrollSummary['percent'] < 100 ? 'bg-warning' : 'bg-success') }}"
                                            style="width: {{ $this->payrollSummary['percent'] }}%"
                                            aria-valuenow="{{ $this->payrollSummary['percent'] }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ $this->payrollSummary['percent'] }}%
                                        </div>
                                    </div>

                                    <!-- Bulk Actions -->
                                    <div class="d-flex flex-wrap justify-content-between gap-2">
                                        <button wire:click="generatePreview" class="btn btn-primary flex-fill">
                                            <i class="bi bi-eye"></i> Generate Preview
                                        </button>
                                        <button wire:click="downloadAllPayslips" class="btn btn-success flex-fill">
                                            <i class="bi bi-download"></i> Download All (ZIP)
                                        </button>
                                        <button wire:click="emailAllPayslips" class="btn btn-warning text-white flex-fill">
                                            <i class="bi bi-envelope-fill"></i> Email All
                                        </button>
                                        @if($this->hasUnpaidPayrolls)
                                        <button wire:click="markAllPaid" class="btn btn-info text-white flex-fill">
                                            <i class="bi bi-check2-all"></i> Mark All Paid
                                        </button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            @endif

                            <!-- Existing Payrolls List -->
                            <div class="row">
                                @forelse($existingPayrolls as $payroll)
                                <div class="col-lg-6 col-md-6 mb-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-body">

                                            <!-- Header -->
                                            <div class="d-flex justify-content-between mb-3">
                                                <div>
                                                    <strong>{{ $payroll->employee->full_name }}</strong>
                                                    <span class="text-muted small ms-2">
                                                        (Code: {{ $payroll->employee->emp_code ?? 'N/A' }})
                                                    </span>
                                                    <br>
                                                    <small class="text-secondary">
                                                        {{ \Carbon\Carbon::parse($payroll->month)->format('F, Y') }}
                                                    </small>
                                                    <span class="badge ms-2 {{ $payroll->is_paid ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $payroll->is_paid ? 'Paid' : 'Unpaid' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex gap-1">

                                                    {{-- Download PDF --}}
                                                    <a wire:click="downloadPayslip({{ $payroll->id }})"
                                                        class="btn btn-sm btn-primary">
                                                        <span wire:loading.remove wire:target="downloadPayslip">
                                                            <i class="far fa-file-pdf"></i>
                                                        </span>
                                                        <span wire:loading wire:target="downloadPayslip">
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                        </span>
                                                    </a>

                                                    {{-- Send Email --}}
                                                    <a wire:click="sendEmail({{ $payroll->id }})"
                                                        class="btn btn-sm btn-warning text-white">
                                                        <span wire:loading.remove wire:target="sendEmail">
                                                            <i class="far fa-envelope"></i>
                                                        </span>
                                                        <span wire:loading wire:target="sendEmail">
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                        </span>
                                                    </a>

                                                    {{-- Mark Paid --}}
                                                    <button wire:click="markPaid({{ $payroll->id }})"
                                                        class="btn btn-sm btn-success">
                                                        <span wire:loading.remove wire:target="markPaid">
                                                            <i class="far fa-bookmark"></i>
                                                        </span>
                                                        <span wire:loading wire:target="markPaid">
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                        </span>
                                                    </button>

                                                </div>

                                            </div>

                                            <!-- Breakdown Table -->
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Type</th>
                                                            <th>Component</th>
                                                            <th class="text-end">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($payroll->items as $item)
                                                        <tr>
                                                            <td class="text-capitalize">{{ $item->type }}</td>
                                                            <td>{{ $item->name }}</td>
                                                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                                        </tr>
                                                        @endforeach
                                                        <tr class="fw-bold">
                                                            <td colspan="2" class="text-end">Gross</td>
                                                            <td class="text-end">{{ number_format($payroll->gross_salary, 2) }}</td>
                                                        </tr>
                                                        <tr class="fw-bold">
                                                            <td colspan="2" class="text-end">Net</td>
                                                            <td class="text-end">{{ number_format($payroll->net_salary, 2) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Preview Button -->
                                            <div class="mt-2 text-end">
                                                <button wire:click="previewPayslip({{ $payroll->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="btn btn-info btn-sm mt-2">
                                                    <span wire:loading.remove wire:target="previewPayslip({{ $payroll->id }})">
                                                        <i class="fas fa-eye"></i> Preview
                                                    </span>
                                                    <span wire:loading wire:target="previewPayslip({{ $payroll->id }})">
                                                        <i class="fas fa-spinner fa-spin"></i> Loading...
                                                    </span>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted mb-0">No payroll generated for this month.</p>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Footer -->
        <livewire:layout.footer />

        <!-- Payslip Preview Modal -->
        @if($showPayslip && $selectedPayroll)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0 rounded-3">
                    <!-- Header -->
                    <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <!-- Left: Title + Employee Badge with Tooltip -->
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="modal-title mb-0 d-flex align-items-center gap-2">
                                <i class="fas fa-file-invoice-dollar"></i>
                                Payslip <small class="fw-light"> ({{ \Carbon\Carbon::parse($selectedPayroll->month)->format('F, Y') }})</small>
                            </h5>

                            <!-- Employee Badge with Tooltip -->
                            <span class="badge bg-light text-dark px-3 py-2"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="ID: {{ $selectedPayroll->employee->emp_code ?? 'N/A' }}
Department: {{ $selectedPayroll->employee->department->name ?? 'N/A' }}
Designation: {{ $selectedPayroll->employee->designation->name ?? 'N/A' }}">
                                {{ strtoupper($selectedPayroll->employee->full_name) }}
                            </span>
                        </div>

                        <!-- Right: Buttons -->
                        <div class="d-flex gap-2">
                            <!-- Prev Button -->
                            <button wire:click="navigatePayslip('prev')"
                                class="btn btn-outline-light btn-sm px-3 d-flex align-items-center gap-1"
                                style="min-width: 90px"
                                @if($currentIndex>= $employeePayrolls->count()-1) disabled @endif>
                                <i class="fas fa-chevron-left"></i> Prev
                            </button>

                            <!-- Next Button -->
                            <button wire:click="navigatePayslip('next')"
                                class="btn btn-outline-light btn-sm px-3 d-flex align-items-center gap-1"
                                style="min-width: 90px"
                                @if($currentIndex <=0) disabled @endif>
                                Next <i class="fas fa-chevron-right"></i>
                            </button>

                            <!-- Close Button -->
                            <button type="button" class="btn btn-light btn-sm px-3 d-flex align-items-center gap-1"
                                wire:click="closePayslipModal"
                                style="min-width: 90px">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                    </div>



                    <!-- Body -->
                    <div class="modal-body bg-light">
                        <div class="table-responsive shadow-sm rounded-3 overflow-hidden mb-4">
                            <table class="table table-bordered table-hover mb-0 align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 20%">Type</th>
                                        <th>Component</th>
                                        <th class="text-end" style="width: 20%">Amount (₹)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @php
                                    $gross = $selectedPayroll->items->where('type','!=','deduction')->sum('amount');
                                    $totalDeductions = $selectedPayroll->items->where('type','deduction')->sum('amount');
                                    @endphp
                                    @foreach($selectedPayroll->items as $item)
                                    <tr>
                                        <td class="text-capitalize">{{ $item->type }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-info fw-bold">
                                        <td colspan="2" class="text-end">Gross Salary</td>
                                        <td class="text-end">{{ number_format($gross, 2) }}</td>
                                    </tr>
                                    <tr class="table-warning fw-bold">
                                        <td colspan="2" class="text-end">Total Deductions</td>
                                        <td class="text-end">{{ number_format($totalDeductions, 2) }}</td>
                                    </tr>
                                    <tr class="table-success fw-bold">
                                        <td colspan="2" class="text-end">Net Salary</td>
                                        <td class="text-end">{{ number_format($gross - $totalDeductions, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-end mt-3">
                            <div class="btn-group" role="group">
                                <a href="{{ route('payslip.preview', $selectedPayroll->id) }}"
                                    class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-eye me-1"></i> Preview
                                </a>
                                <!-- <a href="{{ route('payslip.download', $selectedPayroll->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-file-download me-1"></i> Download PDF
                                </a> -->
                                <a wire:click="downloadPayslip({{ $selectedPayroll->id }})"
                                    wire:loading.attr="disabled"
                                    class="btn btn-primary btn-sm">
                                    <span wire:loading.remove wire:target="downloadPayslip">
                                        <i class="fas fa-file-download me-1"></i> Download PDF
                                    </span>
                                    <span wire:loading wire:target="downloadPayslip">
                                        <i class="fas fa-spinner fa-spin me-1"></i> Generating...
                                    </span>
                                </a>
                                <a wire:click="sendEmail({{ $selectedPayroll->id }})"
                                    wire:loading.attr="disabled"
                                    class="btn btn-warning btn-sm text-white">
                                    <span wire:loading.remove wire:target="sendEmail">
                                        <i class="fas fa-envelope me-1"></i> Send Email
                                    </span>
                                    <span wire:loading wire:target="sendEmail">
                                        <i class="fas fa-spinner fa-spin me-1"></i> Sending...
                                    </span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })
    });
</script>
@endpush

<script>
    window.addEventListener('download-payslip', event => {
        window.location.href = event.detail.url;
    });

    window.addEventListener('email-payslip', event => {
        window.location.href = event.detail.url;
    });
</script>