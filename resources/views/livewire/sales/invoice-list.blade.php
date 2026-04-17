<div class="page-wrapper">

    <style>
        /* show pointer and a subtle hover effect */
        .clickable-badge {
            cursor: pointer;
            /* pointer cursor */
            user-select: none;
            /* avoid text selection while clicking */
            transition: transform .08s ease, box-shadow .08s ease;
        }

        .clickable-badge:focus,
        .clickable-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            outline: none;
        }

        .clickable-badge:active {
            transform: translateY(0);
        }




        /* Activity Wrapper */
        .activity-wrapper {
            max-height: 500px;
            overflow-y: auto;
            padding: 10px 20px;
            position: relative;
        }

        .activity-wrapper::before {
            content: "";
            position: absolute;
            left: 45px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        /* Activity Item */
        .activity-item {
            position: relative;
            padding-left: 90px;
            margin-bottom: 35px;
        }

        /* Avatar / Icon */
        .activity-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #edf2f7;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-size: 18px;
            font-weight: 600;
            position: absolute;
            left: 20px;
            top: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            border: 2px solid #fff;
        }

        /* Avatar Image */
        .activity-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Avatar Letter Fallback */
        .activity-avatar span {
            display: block;
        }

        /* ✅ STATUS COLORS */

        /* Paid */
        .status-paid {
            background: #d1fae5 !important;
            color: #059669 !important;
        }

        /* Pending */
        .status-pending {
            background: #fef9c3 !important;
            color: #b45309 !important;
        }

        /* Cancelled */
        .status-cancelled {
            background: #fee2e2 !important;
            color: #dc2626 !important;
        }

        /* ✅ GST COLORS */

        /* GST Filed */
        .gst-1 {
            background: #dbeafe !important;
            color: #1d4ed8 !important;
        }

        /* GST Not Filed */
        .gst-0 {
            background: #f3f4f6 !important;
            color: #6b7280 !important;
        }

        /* Content Box */
        .activity-content {
            background: white;
            padding: 12px 18px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        /* Header */
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0;
        }

        /* Time */
        .activity-time {
            font-size: 13px;
            color: #6b7280;
        }

        /* Description */
        .activity-description {
            font-size: 13px;
            margin-top: 8px;
            color: #4b5563;
            line-height: 1.55;
        }
    </style>
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('invoices') }}">Invoices</a>
                                </li>
                            </ol>
                        </div>
                        <h4 class="page-title">All Invoice</h4>
                    </div>
                </div>
            </div>

            <!-- Filters & Summary -->
            <div class="card">
                <div class="card-header pb-2">

                    <!-- Add Button -->
                    <div class="row align-items-center mb-2">
                        <div class="col-lg-12 text-end">
                            @can('Create Invoice')
                            <a wire:navigate href="{{ route('invoice.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New
                            </a>
                            @endcan
                        </div>
                    </div>

                    <!-- Filter Card -->
                    <div class="p-2 border rounded bg-light">

                        <!-- Row 1 -->
                        <div class="row g-2 mb-2">

                            <div class="col-md-2">
                                <label class="form-label mb-0 small text-muted">Status</label>
                                <select wire:model.live="status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="cancel">Cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-0 small text-muted">Invoice Type</label>
                                <select wire:model.live="invoice_type" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="gst">GST</option>
                                    <option value="nongst">Non-GST</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-0 small text-muted">GST Filing Status</label>
                                <select wire:model.live="gst_filing_status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1">Filed</option>
                                    <option value="0">Not Filed</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-0 small text-muted">Client</label>
                                <select wire:model.live="client_id" class="form-select form-select-sm">
                                    <option value="">All Clients</option>
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-0 small text-muted">Month</label>
                                <input type="month" wire:model.live="month" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-0 small text-muted">From</label>
                                <input type="date" wire:model.live="from_date" class="form-control form-control-sm">
                            </div>

                        </div>

                        <!-- Row 2 -->
                        <div class="row g-2">

                            <div class="col-md-2">
                                <label class="form-label mb-0 small text-muted">To</label>
                                <input type="date" wire:model.live="to_date" class="form-control form-control-sm">
                            </div>

                            <!-- Search Bar (Aligned Right) -->
                            <div class="col-md-6 offset-md-4">
                                <label class="form-label mb-0 small text-muted">Search</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" wire:model.live="search" class="form-control"
                                        placeholder="Search Invoice...">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Summary Badges -->
                    <div class="row text-center mt-3 g-2">

                        <div class="col-md-2">
                            <span class="badge bg-primary w-100 py-2">
                                Total: {{ $summary['total'] }}
                            </span>
                        </div>

                        <div class="col-md-2">
                            <span class="badge bg-warning w-100 py-2 text-dark">
                                Pending: {{ $summary['pending'] }}
                            </span>
                        </div>

                        <div class="col-md-2">
                            <span class="badge bg-success w-100 py-2">
                                Paid: {{ $summary['paid'] }}
                            </span>
                        </div>

                        <div class="col-md-2">
                            <span class="badge bg-danger w-100 py-2">
                                Cancelled: {{ $summary['cancel'] }}
                            </span>
                        </div>

                        <div class="col-md-2">
                            <span class="badge bg-info w-100 py-2">
                                GST Filed: {{ $summary['gst_filed'] }}
                            </span>
                        </div>

                        <div class="col-md-2">
                            <span class="badge bg-secondary w-100 py-2">
                                Not Filed: {{ $summary['gst_not_filed'] }}
                            </span>
                        </div>

                    </div>

                </div>



                <!-- Table -->
                <style>
                    /* ✅ 2. Sticky header */
                    .sticky-header thead th {
                        position: sticky;
                        top: 0;
                        z-index: 10;
                        background: #f8f9fa !important;
                    }

                    /* ✅ 7. Compact row height (ERP style) */
                    .erp-row td,
                    .erp-row th {
                        padding: 6px 8px !important;
                    }

                    /* ✅ 1. Status Row Highlight */
                    .row-paid {
                        background: #f8fff8 !important;
                    }

                    .row-pending {
                        background: #ffffff !important;
                    }

                    .row-cancelled {
                        background: #fce3e3 !important;
                    }
                </style>


                <div class="card-body mb-n3">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle sticky-header">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th style="width: 150px;">Invoice No.</th>
                                    <th style="width: 110px;">Date</th>
                                    <th>Client</th>
                                    <th style="width: 120px;" class="text-end">Amount</th>
                                    <th style="width: 110px;">Status</th>
                                    <th style="width: 110px;">GST Filing</th>
                                    <th style="width: 110px;">Due Date</th>
                                    <th style="width: 120px;" class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @php $i = 1; @endphp

                                @forelse ($invoices as $invoice)
                                <!--  1. ROW COLOR BASED ON STATUS -->
                                @php
                                $status = strtolower($invoice->status);
                                $rowClass = $status === 'paid'
                                ? 'row-paid'
                                : ($status === 'pending' ? 'row-pending' : 'row-cancelled');
                                @endphp


                                <tr class="erp-row {{ $rowClass }}">


                                    <!-- SL No -->
                                    <td>{{ $i++ }} </td>

                                    <!-- Invoice Number + Type Badge -->
                                    <td>
                                        <a href="javascript:;" wire:click="openHistoryModal({{ $invoice->id }})"
                                            class="text-primary fw-semibold">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                        <div>
                                            @if ($invoice->invoice_type === 'gst')
                                            <span class="badge bg-success mt-1">GST</span>
                                            @else
                                            <span class="badge bg-info mt-1">Non-GST</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Date -->
                                    <td>{{ formatDate($invoice->invoice_date) }}</td>

                                    <!-- Client -->
                                    <td>{{ optional($invoice->client)->name ?? '-' }}</td>

                                    <!--  5. Amount Right Aligned + format -->
                                    <td class="text-end fw-semibold">
                                        ₹{{ number_format($invoice->grand_total, 2) }}
                                    </td>

                                    <td>
                                        <span role="button" tabindex="0"
                                            class="badge clickable-badge {{ $invoice->status == 'paid' ? 'bg-success' : ($invoice->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}"
                                            @can('Edit Status')
                                            wire:click="openStatusModal({{ $invoice->id }})"
                                            wire:keydown.enter="openStatusModal({{ $invoice->id }})"
                                            @endcan
                                            aria-label="Change invoice status for {{ $invoice->invoice_number }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($invoice->invoice_type === 'gst')
                                        <span role="button" tabindex="0"
                                            class="badge clickable-badge {{ $invoice->gst_filing_status ? 'bg-primary' : 'bg-secondary' }}"
                                            @can('Edit Gst Status')
                                            wire:click="openGstModal({{ $invoice->id }})"
                                            wire:keydown.enter="openGstModal({{ $invoice->id }})"
                                            @endcan
                                            aria-label="Change GST filing status for {{ $invoice->invoice_number }}">
                                            {{ $invoice->gst_filing_status ? 'Filed' : 'Not Filed' }}
                                        </span>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>


                                    <!-- Due Date -->
                                    <td>{{ $invoice->due_date ? formatDate($invoice->due_date) : '-' }}</td>

                                    <!-- Actions -->
                                    <td class="text-center">

                                        <!--  3. Tooltip: Download -->
                                        @if ($loadingInvoiceId === $invoice->id)
                                        <i class="fas fa-spinner fa-spin text-secondary"></i>
                                        @else
                                        <a href="javascript:;"
                                            wire:click="downloadPdf({{ $invoice->id }}, '{{ $invoice->invoice_type }}')"
                                            class="text-dark me-2" data-bs-toggle="tooltip"
                                            title="Download PDF">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </a>
                                        @endif

                                        <!--  Tooltip: Print -->
                                        <a href="javascript:;"
                                            wire:click="{{ $invoice->invoice_type == 'gst' ? 'printGST' : 'printNonGST' }}({{ $invoice->id }})"
                                            class="text-success me-2" data-bs-toggle="tooltip"
                                            title="Print Invoice">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        <!-- Tooltip: Edit -->
                                        @can('Edit Invoice')
                                        <a wire:navigate.click
                                            href="{{ $invoice->invoice_type === 'nongst'
                                                        ? route('non-gst-invoice.edit', $invoice->id)
                                                        : route('invoice.edit', $invoice->id) }}"
                                            class="text-info me-2" data-bs-toggle="tooltip" title="Edit Invoice">
                                            <i class="las la-pen"></i>
                                        </a>
                                        @endcan

                                        <!--  Tooltip: Delete -->
                                        @can('Delete Invoice')
                                        <a href="javascript:;" onclick="confirmDeletion('{{ $invoice->id }}')"
                                            class="text-danger" data-bs-toggle="tooltip" title="Delete Invoice">
                                            <i class="las la-trash-alt"></i>
                                        </a>
                                        @endcan

                                    </td>
                                </tr>

                                @empty

                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">
                                        No invoices found.
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-2">
                            {{ $invoices->links(data: ['scrollTo' => false]) }}
                        </div>
                    </div>




                    @if ($showStatusModal)
                    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Update Invoice Status</h5>
                                    <button type="button" class="btn-close"
                                        wire:click="$set('showStatusModal', false)"></button>
                                </div>

                                <div class="modal-body">
                                    <label>Status</label>
                                    <select class="form-select" wire:model="selectedStatus">
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                        <option value="cancel">Cancelled</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary"
                                        wire:click="$set('showStatusModal', false)">Close</button>
                                    <button class="btn btn-primary" wire:click="updateStatus">Update</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif



                    @if ($showGstModal)
                    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Update GST Filing Status</h5>
                                    <button type="button" class="btn-close"
                                        wire:click="$set('showGstModal', false)"></button>
                                </div>

                                <div class="modal-body">
                                    <label>GST Filing</label>
                                    <select class="form-select" wire:model="selectedGstStatus">
                                        <option value="1">Filed</option>
                                        <option value="0">Not Filed</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary"
                                        wire:click="$set('showGstModal', false)">Close</button>
                                    <button class="btn btn-primary" wire:click="updateGstStatus">Update</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif





                    @if ($historyModal)
                    <div class="modal fade show d-block bg-dark bg-opacity-50">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header bg-primary">
                                    <h5 class="text-white mb-0">Invoice Activity History</h5>
                                    <button class="btn-close btn-close-white"
                                        wire:click="$set('historyModal', false)"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="card-body">

                                        {{-- ✅ TIMELINE WRAPPER --}}
                                        <div class="border-start border-3 ps-4">

                                            @forelse ($historyLogs as $log)
                                            {{-- ✅ TIMELINE ITEM --}}
                                            <div class="position-relative pb-4 mb-4">

                                                {{-- ✅ TIMELINE DOT --}}
                                                <span
                                                    class="position-absolute top-0 start-0 translate-middle
                                    bg-primary rounded-circle border border-white"
                                                    style="width:12px; height:12px;"></span>

                                                {{-- ✅ HEADER --}}
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="fw-semibold text-capitalize mb-1">
                                                        {{ str_replace('_', ' ', $log->field) }}
                                                    </h6>

                                                    <span class="small text-muted">
                                                        {{ $log->created_at->format('d M Y h:i A') }}
                                                        <br>
                                                        <small>({{ $log->created_at->diffForHumans() }})</small>
                                                    </span>
                                                </div>

                                                {{-- ✅ DESCRIPTION --}}
                                                <div class="text-muted">

                                                    {{-- Invoice Created --}}
                                                    @if ($log->field === 'invoice_created')
                                                    <span class="text-success fw-bold">
                                                        {{ $log->description }}
                                                    </span>

                                                    {{-- Status Update --}}
                                                    @elseif($log->field === 'status')
                                                    Status updated from
                                                    <span class="badge bg-light text-dark px-2">
                                                        {{ ucfirst($log->old_value) }}
                                                    </span>
                                                    to
                                                    <span class="badge bg-primary text-white px-2">
                                                        {{ ucfirst($log->new_value) }}
                                                    </span>

                                                    {{-- GST Filing Status --}}
                                                    @elseif($log->field === 'gst_filing_status')
                                                    GST Filing changed from
                                                    <span class="badge bg-light text-dark px-2">
                                                        {{ $log->old_value ? 'Filed' : 'Not Filed' }}
                                                    </span>
                                                    to
                                                    <span class="badge bg-info text-white px-2">
                                                        {{ $log->new_value ? 'Filed' : 'Not Filed' }}
                                                    </span>

                                                    {{-- Item Added --}}
                                                    @elseif($log->field === 'item_added')
                                                    <span class="text-success fw-semibold">
                                                        Item added: {{ $log->new_value }}
                                                    </span>

                                                    {{-- Item Removed --}}
                                                    @elseif($log->field === 'item_removed')
                                                    <span class="text-danger fw-semibold">
                                                        Item removed: {{ $log->old_value }}
                                                    </span>

                                                    {{-- Total Updated --}}
                                                    @elseif($log->field === 'grand_total')
                                                    Invoice total updated from
                                                    <span class="badge bg-light text-dark px-2">
                                                        ₹{{ number_format($log->old_value, 2) }}
                                                    </span>
                                                    to
                                                    <span class="badge bg-warning text-dark px-2">
                                                        ₹{{ number_format($log->new_value, 2) }}
                                                    </span>

                                                    {{-- Fallback --}}
                                                    @else
                                                    {{ $log->description ?? 'Activity logged.' }}
                                                    @endif

                                                    {{-- USER --}}
                                                    <div class="mt-2 small">
                                                        <i class="bi bi-person-fill me-1"></i>
                                                        <strong>{{ optional($log->user)->name ?? 'System' }}</strong>
                                                    </div>

                                                </div>

                                            </div>

                                            @empty
                                            <div class="text-center py-5">
                                                <i class="bi bi-inbox display-5 text-muted"></i>
                                                <p class="text-muted">No history found.</p>
                                            </div>
                                            @endforelse

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    @endif






                </div>

                <!-- ✅ Bootstrp tooltip init -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                        tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl)
                        })
                    });
                </script>

            </div>

            <livewire:layout.footer />

        </div>
    </div>
</div>

<!-- Delete Confirmation -->
<script>
    function confirmDeletion(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be reversed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteItem', {
                    id
                });
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
