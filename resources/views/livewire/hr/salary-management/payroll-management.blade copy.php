<div class="page-wrapper">
    <!-- Page Content-->
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <div class="float-end">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Payroll Management</li>
                            </ol>
                        </div>
                        <h4 class="page-title mb-0">Payroll Management</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex gap-2 mb-4">
                                <input type="month" wire:model="month" class="form-control w-auto">
                                <button wire:click="generatePreview" class="btn btn-primary">
                                    Generate Payroll Preview
                                </button>
                            </div>

                            @if($payrollPreview)
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Employee</th>
                                                <th>Gross</th>
                                                <th>Net</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payrollPreview as $row)
                                                <tr>
                                                    <td>{{ $row['name'] }}</td>
                                                    <td>{{ number_format($row['gross'],2) }}</td>
                                                    <td>{{ number_format($row['net'],2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <button wire:click="finalizePayroll" class="btn btn-success">
                                    Finalize Payroll
                                </button>
                            @endif

                            <h3 class="h5 fw-bold mt-5 mb-3">
                                Existing Payroll ({{ $month }})
                            </h3>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Employee</th>
                                            <th>Gross</th>
                                            <th>Net</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($existingPayrolls as $payroll)
                                            <tr>
                                                <td>{{ $payroll->employee->full_name  }}</td>
                                                <td>{{ $payroll->gross_salary }}</td>
                                                <td>{{ $payroll->net_salary }}</td>
                                                <td>
                                                    @if($payroll->is_paid)
                                                        <span class="badge bg-success">Paid</span>
                                                    @else
                                                        <span class="badge bg-danger">Unpaid</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!$payroll->is_paid)
                                                        <button wire:click="markPaid({{ $payroll->id }})"
                                                                class="btn btn-sm btn-success">
                                                            Mark Paid
                                                        </button>
                                                        @else
                                                        <a href="{{ route('payslip.download', $payroll->id) }}" class="btn btn-sm btn-primary">
                                                                Download
                                                            </a>
                                                            <a href="{{ route('payslip.email', $payroll->id) }}" class="btn btn-sm btn-secondary">
                                                                Email
                                                            </a>
                                                    @endif
                                                </td>



                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->
        </div><!-- container -->

        <!--Start Footer-->
        <livewire:layout.footer />
    </div>
    <!-- end page content -->
</div>
