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

                                <li class="breadcrumb-item active">Invoice</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Invoice</h4>

                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <button class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" wire:click="addPermission()" type="button">
                                <span wire:loading wire:target="addPermission">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span>
                                    Add New <i class="fas fa-plus"></i>
                                </span>

                            </button>

                            <div class="table-responsive">
                                <div>
                                    <label>Invoice Number:</label>
                                    <input type="text" wire:model="invoice_number" class="form-control">
                                    @error('invoice_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label>Customer Name:</label>
                                    <input type="text" wire:model="customer_name" class="form-control">
                                    @error('customer_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <h3 class="mt-3 font-semibold">Invoice Items</h3>

                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $index => $item)
                                        <tr>
                                            <td><input type="text" wire:model="items.{{ $index }}.description" class="form-control">
                                            @error("items.{$index}.description") <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                            <td><input type="number" wire:model="items.{{ $index }}.quantity" class="form-control" wire:change="calculateTotal"></td>
                                            <td><input type="number" wire:model="items.{{ $index }}.price" class="form-control" wire:change="calculateTotal"></td>
                                            <td>{{ number_format($items[$index]['quantity'] * $items[$index]['price'], 2) }}</td>
                                            <td><button wire:click="removeItem({{ $index }})" class="btn btn-danger">Remove</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button wire:click="addItem" class="btn btn-primary mt-2">Add Item</button>

                                <div class="mt-3">
                                    <h3>Total: {{ number_format($total, 2) }}</h3>
                                </div>

                                <button wire:click="saveInvoice" class="btn btn-success mt-2">Save Invoice</button>
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->






        </div><!-- container -->

        <!--Start Footer-->
        <livewire:layout.footer />

        <!--end modal-->

        <!--end footer-->
    </div>
    <!-- end page content -->
</div>