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
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('invoices') }}">Invoices</a></li>
                                <li class="breadcrumb-item active">Create New Invoice</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Non GST Invoice </h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label>Customer Name:</label>
                                    <div wire:ignore>
                                        <select class="form-select js-example-basic-single" id="rol" wire:model.live="selectedClient">
                                            <option value="">Choose client</option>
                                            @foreach($clients as $client)
                                            <option value="{{ $client->id }}">
                                                {{ $client->name }}{{ $client->company_name ? ' (' . $client->company_name . ')' : '' }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('selectedClient') <span class="text-danger">{{ $message }}</span> @enderror

                                    @if($clientDetails)
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h4 class="card-title">Billing Address</h4>
                                        </div>
                                        <div class="card-body pb-0">
                                            <p class="mb-0 text-muted font-13">
                                                {{ $clientDetails->name }}<br>
                                                Address: {{ $clientDetails->address }}<br>
                                                PHONE: {{ $clientDetails->phone }}<br>
                                                GSTIN: {{ $clientDetails->gst }}<br>
                                                Place of Supply: {{ $clientDetails->city }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <label>Invoice Date:</label>
                                    <input type="date" wire:model="invoice_date" readonly class="form-control">
                                    @error('invoice_date') <span class="text-danger">{{ $message }}</span> @enderror
                                    <label class="mt-3">Terms:</label>
                                    <select class="form-select" wire:model="payment_terms" wire:change="changeDueDate($event.target.value)">
                                        <option value="Due On Receipt">Due On Receipt</option>
                                        <option value="Due end of the month">Due end of the month</option>
                                        <option value="Due end of next month">Due end of next month</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Invoice Number:</label>
                                    <input type="text" wire:model="invoice_number" readonly class="form-control">
                                    @error('invoice_number') <span class="text-danger">{{ $message }}</span> @enderror

                                    <label class="mt-3">Due Date:</label>
                                    <input type="date" wire:model="due_date" class="form-control">
                                    @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <h3 class="mt-3 font-semibold">Items</h3>
                            <table class="table table-bordered mb-0">
                                <tr>
                                    <th style="width: 25%;">Description</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 10%;">Price</th>
                                    <th style="width: 12%;">Subtotal</th>
                                    <!-- <th style="width: 10%;">Total</th> -->
                                    <th style="width: 5%;">Action</th>
                                </tr>

                                @foreach ($items as $index => $item)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control"
                                            wire:model="items.{{ $index }}.description"
                                            wire:keyup="fetchItems({{ $index }})">
                                        <!-- Display the item suggestions -->
                                        @if (!empty($itemSuggestions[$index]))
                                        <ul class="suggestions">
                                            @foreach ($itemSuggestions[$index] as $suggestion)
                                            <li wire:click="selectItem({{ $index }}, {{ $suggestion->id }})">
                                                {{ $suggestion->name }} (₹{{ number_format($suggestion->price, 2) }})
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                        @error("items.{$index}.description") <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td><input type="text" class="form-control"
                                            wire:model="items.{{ $index }}.quantity"
                                            wire:change="calculateTotal">
                                        @error("items.{$index}.quantity") <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td><input type="text" class="form-control"
                                            wire:model="items.{{ $index }}.price"
                                            wire:change="calculateTotal">
                                        @error("items.{$index}.price") <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>

                                    <td>{{ number_format($items[$index]['subtotal'], 2) }}</td>
                                    <!-- <td></td> -->
                                    <td>
                                        <a href="javascript:;" wire:click="removeItem({{ $index }})"><i class="las la-trash-alt text-secondary font-16 text-danger"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            <button class="btn btn-de-success btn-sm mt-2" wire:click="addItem"><i class="fas fa-plus"></i> Add Item</button>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card p-3 mt-4">
                                    <div class="card-header">
                                        <h4 class="card-title">Payment Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-de-primary btn-sm" wire:click="addPaymentRow"><i class="fas fa-plus"></i> Payment Type </button>
                                        @foreach ($payments as $index => $payment)
                                        <div class="row mt-3">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label>Bank Name</label>
                                                    <select class="form-select" wire:model="payments.{{ $index }}.bank">
                                                        <option value="">Choose Bank</option>
                                                        @foreach($banks as $bank)
                                                        <option value="{{ $bank->id }}">
                                                            {{ $bank->bank_name }}{{ $bank->bank_name ? ' (' . $bank->account_no . ')' : '' }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label>Amount</label>
                                                    <input type="text" class="form-control" wire:model="payments.{{ $index }}.amount">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label>Transaction No.</label>
                                                    <input type="text" class="form-control" wire:model="payments.{{ $index }}.transaction_no">
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="total-payment p-3 mt-4">
                                    <h4 class="header-title">Total Payment</h4>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td class="fw-semibold">Subtotal</td>
                                                <td>₹{{ number_format($this->total, 2) }}</td>
                                            </tr>

                                            <!-- Advance Payment Input -->
                                            <tr>
                                                <td class="fw-semibold">Advance Payment</td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm" wire:model.lazy="advance_payment">
                                                </td>
                                            </tr>

                                            <!-- Final Payable Amount -->
                                            <tr>
                                                <td class="border-bottom-0">Payable Amount</td>
                                                <td class="text-dark border-bottom-0">
                                                    <strong>₹{{ number_format($this->payable_amount, 2) }}</strong>
                                                </td>
                                            </tr>


                                            <!-- <tr>
                                                <td class="border-bottom-0">Grand Total</td>
                                                <td class="text-dark border-bottom-0"><strong>₹{{ number_format($grand_total, 2) }}</strong></td>
                                            </tr>
                                             -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <button wire:click="saveInvoice" class="btn btn-block btn-primary float-end mt-3 mb-3">Save Invoice</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>