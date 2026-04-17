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
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item"><a href="{{ route('invoices') }}" wire:navigate>Invoices</a>
                                </li>

                            </ol>
                        </div>
                        <h4 class="page-title">All Invoice</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-6">


                                </div>
                                <div class="col-lg-6 mt-3 text-end">
                                    <div class="text-end">
                                        <ul class="list-inline">
                                            <li class="list-inline-item" style="width: 60%;">
                                                <div class="input-group">
                                                    <input type="text" id="example-input1-group2"
                                                        wire:model.live="search" class="form-control form-control-sm"
                                                        placeholder="Search">
                                                    <button type="button" class="btn btn-primary"><i
                                                            class="fas fa-search"></i></button>
                                                </div>
                                            </li>
                                            <!-- <li class="list-inline-item">
                                                <button type="button" class="btn btn-primary"><i
                                                        class="fas fa-filter"></i></button>
                                            </li> -->
                                              @can('Create Invoice')
                                            <li class="list-inline-item">
                                                <a href="{{ route('invoice.create') }}" class="btn btn-primary"
                                                    wire:navigate type="button">
                                                    <span wire:loading wire:target="addLead">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                    <span wire:loading.remove wire:target="addLead">
                                                        <i class="fas fa-plus"></i> Add New
                                                    </span>
                                                </a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div><!--end col-->



                            </div>
                        </div>
                        <div class="card-body mb-n3">
                            <div class="table-responsive">

                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Invoice No.</th>
                                             <!-- <th>Type</th> -->
                                            <th>Dated</th>
                                            <th>Client</th>
                                            <th>Total Amount</th>
                                            {{-- <th>Address</th> --}}
                                            <!-- <th>Notes</th> -->
                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr><!--end tr-->
                                    </thead>

                                    <tbody>
                                        @php
                                        $i = 1;
                                        @endphp
                                        @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td><a wire:navigate
                                                    href="{{ route('lead.details', ['id' => Crypt::encryptString($invoice->id)]) }}"
                                                    class="text-primary">{{ $invoice->invoice_number }}</a>

                                                    @if($invoice->invoice_type=='gst')
                                                    <span class="badge bg-success">{{ $invoice->invoice_type }}</span>
                                                    @else
                                                    <span class="badge bg-info">{{ $invoice->invoice_type }}</span>
                                                    @endif
                                            </td>
                                            <!-- <td> {{ $invoice->invoice_type }}</td> -->
                                            <td> {{ $invoice->invoice_date }}</td>
                                            <!-- <td>{{ $invoice->phone }}</td> -->
                                            <td>{{ optional($invoice->client)->name }}
                                            </td>
                                            <td>{{ $invoice->grand_total }}</td>
                                            <!-- <td>{{ $invoice->notes }}</td> -->
                                            <!-- <td>{{ $invoice->created_at }}</td> -->
                                            <td>{{ $invoice->due_date ? formatDate($invoice->due_date) : '' }}
                                            </td>
                                            <td>
                                                @if($loadingInvoiceId === $invoice->id)
                                                <div class="text-center d-inline-block me-2">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </div>
                                                @else
                                                <a href="javascript:;"
                                                    wire:click="downloadPdf({{ $invoice->id }}, '{{ $invoice->invoice_type }}')"
                                                    class="text-dark mx-1">
                                                    <i class="fas fa-cloud-download-alt"></i>
                                                </a>
                                                @endif
                                                <a href="javascript:;"
                                                    wire:click="{{ $invoice->invoice_type == 'gst' ? 'printGST' : 'printNonGST' }}({{ $invoice->id }})"
                                                    class="text-primary">
                                                    <i class="fas fa-print font-16 text-success"></i>
                                                </a>
                                                 @can('Edit Invoice')
                                                <a href="{{ $invoice->invoice_type === 'nongst'
                                                        ? route('non-gst-invoice.edit', $invoice->id)
                                                        : route('invoice.edit', $invoice->id) }}"
                                                    wire:navigate.click>
                                                    <i class="las la-pen text-secondary font-16 text-info"></i>
                                                </a>
                                                @endcan
                                                @can('Delete Invoice')
                                                <a href="javascript:;"
                                                    onclick="confirmDeletion('{{ $invoice->id }}')">
                                                    <i
                                                        class="las la-trash-alt text-secondary font-16 text-danger"></i>
                                                </a>
                                                @endcan
                                            </td>
                                        </tr><!--end tr-->
                                        @endforeach
                                    </tbody>
                                </table>

                                {{ $invoices->links(data: ['scrollTo' => false]) }}
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->
        </div><!-- container -->

        <!--Start Rightbar-->


        <!--Start Footer-->

        <!-- Footer Start -->
        <livewire:layout.footer />

        <!--end footer-->
    </div>
    <!-- end page content -->
</div>
<script>
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
                Livewire.dispatch('deleteItem', {
                    id: itemId
                }); // Dispatch Livewire event
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('openPrintTab', function(url) {
            window.open(url, '_blank');
        });
    });

    window.addEventListener('pdf-downloaded', () => {
        Livewire.dispatch('resetLoading');
    });
</script>