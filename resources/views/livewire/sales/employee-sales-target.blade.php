<div class="page-wrapper">
    <!-- Page Content-->
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard')}}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Employee Sales Target Report</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Employee Sales Target Report</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <button class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" wire:click="addLeadStatus()" type="button" >
                                <span wire:loading wire:target="addLeadStatus">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span wire:loading.remove wire:target="addLeadStatus">
                                    Add New <i class="fas fa-plus"></i>
                                </span>

                              </button>
                              <div class="mb-4 flex gap-4">
                                <input type="text" wire:model.live="searchEmployee" placeholder="Search Employee"
                                       class="border p-2 rounded w-1/3">

                                <input type="month" wire:model.live="searchMonth" class="border p-2 rounded w-1/3">
                            </div>
                            <a href="{{ route('export.sales.excel') }}" class="btn btn-green">Export Excel</a>
                            <a href="{{ route('export.sales.pdf') }}" class="btn btn-red">Export PDF</a>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Employee</th>
                                            <th>Month</th>
                                            <th>Sale Target</th>
                                            <th>Sales Achieve</th>
                                            <th>Remaining</th>
                                            <th>Sales Excess</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employeeSales as $sale)
                                            <tr>
                                                <td>{{ $sale->user->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($sale->month)->format('F Y') }}</td>
                                                <td>{{ number_format($sale->target) }}</td>
                                                <td>{{ calculateAchievedFromDeals($sale->user->id, '2025-04') }}</td>

                                                <td>{{ max(0, $sale->target - calculateAchievedFromDeals($sale->user->id, '2025-04')) }}</td>
                                                <td>{{ max(0, calculateAchievedFromDeals($sale->user->id, '2025-04') - $sale->target) }}</td>
                                                <td>
                                                    @if(calculateAchievedFromDeals($sale->user->id, '2025-04') >= $sale->target)
                                                    <span class="badge badge-soft-success">Achieved</span>
                                                    @else
                                                    <span class="badge badge-soft-danger">Pending</span>
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



       <!--end footer-->
    </div>
    <!-- end page content -->
</div>




