<div class="page-wrapper">

    <style>
        .suggestions {
            list-style-type: none;
            padding: 0;
            border: 1px solid #ddd;
            max-height: 150px;
            overflow-y: auto;
            background: white;
            position: absolute;
            z-index: 1000;
            width: 23%;
        }

        .suggestions li {
            padding: 8px;
            cursor: pointer;
        }

        .suggestions li:hover {
            background: #f0f0f0;
        }
    </style>
    <!-- Page Content-->
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
                                <li class="breadcrumb-item active">Edit Invoice</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit Invoice</h4>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body mb-n3">

                            <!-- Customer & Invoice Info -->
                            <div class="row">

                                <!-- CUSTOMER SELECTION -->
                                <div class="col-lg-4">
                                    <label>Customer Name:</label>
                                    <div wire:ignore>
                                        <select class="form-select js-example-basic-single"
                                            id="rol"
                                            wire:model.live="selectedClient">
                                            <option value="">Choose client</option>
                                            @foreach($clients as $client)
                                            <option value="{{ $client->id }}">
                                                {{ $client->name }}
                                                {{ $client->company_name ? ' ('.$client->company_name.')' : '' }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('selectedClient')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    <!-- Billing Info -->
                                    @if($clientDetails)
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5 class="card-title">Billing Address</h5>
                                        </div>
                                        <div class="card-body pb-0">
                                            <p class="mb-0 text-muted font-13">
                                                {{ $clientDetails->name }}<br>
                                                Address: {{ $clientDetails->address }}<br>
                                                Phone: {{ $clientDetails->phone }}<br>
                                                GSTIN: {{ $clientDetails->gst }}<br>
                                                Place of Supply: {{ $clientDetails->city }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- INVOICE & TERMS -->
                                <div class="col-lg-4">
                                    <label>Invoice Date:</label>
                                    <input type="date" wire:model="invoice_date" readonly class="form-control">

                                    <label class="mt-3">Terms:</label>
                                    <select class="form-select"
                                        wire:model="payment_terms"
                                        wire:change="changeDueDate($event.target.value)">
                                        <option value="Due On Receipt">Due On Receipt</option>
                                        <option value="Due end of the month">Due end of the month</option>
                                        <option value="Due end of next month">Due end of next month</option>
                                    </select>

                                    <!-- ✅ NEW → STATUS FIELD -->
                                    <label class="mt-3">Status:</label>
                                    <select class="form-select" wire:model="status">
                                        <option value="pending">Pending</option>
                                        <option value="paid">Paid</option>
                                        <option value="cancel">Cancelled</option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                                <!-- INVOICE NUMBER & DUE DATE -->
                                <div class="col-lg-4">
                                    <label>Invoice Number:</label>
                                    <input type="text" wire:model="invoice_number" readonly class="form-control">

                                    <label class="mt-3">Due Date:</label>
                                    <input type="date" wire:model="due_date" class="form-control">
                                    @error('due_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    <label class="mt-3">GST Filling Status:</label>
                                    <select class="form-select" wire:model="gst_filing_status">
                                        <option value="1">Filed</option>
                                        <option value="0">Not Filed</option>
                                    </select>
                                    @error('gst_filing_status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>


                            <!-- ITEMS TABLE -->
                            <div class="table-responsive project-invoice mt-4">

                                <h4 class="mt-3">Items</h4>

                                <table class="table table-bordered mb-0">
                                    <tr>
                                        <th>Description</th>
                                        <th width="10%">Qty</th>
                                        <th width="10%">Price</th>
                                        <th width="12%">Subtotal</th>

                                        @if ($customer_state == 'West Bengal')
                                        <th width="10%">CGST</th>
                                        <th width="10%">SGST</th>
                                        @else
                                        <th width="10%">IGST</th>
                                        @endif

                                        <th width="10%">GST Total</th>
                                        <th width="5%">Action</th>
                                    </tr>

                                    @foreach ($items as $index => $item)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control"
                                                wire:model="items.{{ $index }}.description"
                                                wire:keyup="fetchItems({{ $index }})">

                                            <!-- Suggestions -->
                                            @if (!empty($itemSuggestions[$index]))
                                            <ul class="suggestions">
                                                @foreach ($itemSuggestions[$index] as $s)
                                                <li wire:click="selectItem({{ $index }}, {{ $s->id }})">
                                                    {{ $s->name }} (₹{{ number_format($s->price,2) }})
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </td>

                                        <td>
                                            <input type="text" class="form-control"
                                                wire:model="items.{{ $index }}.quantity"
                                                wire:change="calculateTotal">
                                        </td>

                                        <td>
                                            <input type="text" class="form-control"
                                                wire:model="items.{{ $index }}.price"
                                                wire:change="calculateTotal">
                                        </td>

                                        <td>{{ number_format($item['subtotal'], 2) }}</td>

                                        @if ($customer_state == 'West Bengal')
                                        <td>{{ number_format($item['cgst'], 2) }}</td>
                                        <td>{{ number_format($item['sgst'], 2) }}</td>
                                        @else
                                        <td>{{ number_format($item['igst'], 2) }}</td>
                                        @endif

                                        <td>{{ number_format($item['gst'], 2) }}</td>

                                        <td>
                                            <a href="javascript:;" wire:click="removeItem({{ $index }})">
                                                <i class="las la-trash-alt text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </table>

                                <button class="btn btn-success btn-sm mt-2" wire:click="addItem">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>


                            <!-- PAYMENT SECTION -->
                            <div class="row mt-4">

                                <!-- PAYMENT TYPES -->
                                <div class="col-md-8">
                                    <div class="card p-3">
                                        <h5>Payment Details</h5>

                                        <button class="btn btn-primary btn-sm mb-2"
                                            wire:click="addPaymentRow">
                                            <i class="fas fa-plus"></i> Add Payment
                                        </button>

                                        @foreach ($payments as $index => $payment)
                                        <div class="row mb-3">

                                            <div class="col-lg-4">
                                                <label>Bank</label>
                                                <select class="form-select"
                                                    wire:model="payments.{{ $index }}.bank">
                                                    <option value="">Choose Bank</option>
                                                    @foreach($banks as $bank)
                                                    <option value="{{ $bank->id }}">
                                                        {{ $bank->bank_name }} ({{ $bank->account_no }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-4">
                                                <label>Amount</label>
                                                <input type="text" class="form-control"
                                                    wire:model="payments.{{ $index }}.amount">
                                            </div>

                                            <div class="col-lg-4">
                                                <label>Transaction No.</label>
                                                <input type="text" class="form-control"
                                                    wire:model="payments.{{ $index }}.transaction_no">
                                            </div>

                                        </div>
                                        @endforeach

                                    </div>
                                </div>

                                <!-- TOTALS -->
                                <div class="col-md-4">
                                    <div class="card p-3">
                                        <h5>Total Payment</h5>

                                        <table class="table">
                                            <tr>
                                                <td>Subtotal</td>
                                                <td>₹{{ number_format($total, 2) }}</td>
                                            </tr>

                                            @if ($customer_state == 'West Bengal')
                                            <tr>
                                                <td>CGST</td>
                                                <td>₹{{ number_format($cgst,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>SGST</td>
                                                <td>₹{{ number_format($sgst,2) }}</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td>IGST</td>
                                                <td>₹{{ number_format($igst,2) }}</td>
                                            </tr>
                                            @endif

                                            <tr>
                                                <td><strong>Grand Total</strong></td>
                                                <td><strong class="text-dark">
                                                        ₹{{ number_format($grand_total,2) }}
                                                    </strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <!-- SAVE BUTTON -->
                            <button wire:click="saveInvoice"
                                class="btn btn-primary float-end mt-3 mb-3">
                                Save Invoice
                            </button>

                        </div>

                    </div>
                </div>
            </div>

            <livewire:layout.footer />
        </div>
    </div>

    <!-- end page content -->
</div>
@script()
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();

        $('.js-example-basic-single').on('change', function(e) {
            let data = $(this).val();
            // console.log(data)
            $wire.set('selectedClient', data)
            $wire.selectedClient = data;
        });
    });
</script>
@endscript