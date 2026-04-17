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
                                <!-- <li class="breadcrumb-item"><a href="#">Advanced UI</a></li> -->
                                <li class="breadcrumb-item active">Banks</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Banks</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <!-- Bank Accounts List -->
                <div class="col-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Bank Accounts</h4>
                                </div>
                                <div class="col-auto" wire:ignore>
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-sm btn-outline-light dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-horizontal text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="javascript:;"
                                                wire:click="newAccountPopup()">Create New Account</a>
                                            <a class="dropdown-item" href="#">Bank Transfer</a>
                                            <a class="dropdown-item" href="#">Bank Adjustment</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="files-nav">
                                <div class="nav flex-column nav-pills">
                                    @foreach ($bankAccounts as $account)
                                    <div
                                        class="d-flex justify-content-between align-items-center nav-link @if ($selectedAccount && $selectedAccount->id === $account->id) active @endif">
                                        <a href="javascript:;" wire:click="selectAccount({{ $account->id }})"
                                            class="d-flex align-items-center text-decoration-none">
                                            <i
                                                class="far @if ($selectedAccount && $selectedAccount->id === $account->id) fa-folder-open @else fa-folder @endif align-self-center me-2"></i>
                                            <div class="d-inline-block">
                                                <h5 class="m-0">{{ $account->bank_name }}</h5>
                                                <small>{{ $account->account_no }}</small>
                                                @if ($account->is_default)
                                                <span class="badge bg-success text-white">Default</span>
                                                @endif

                                            </div>
                                        </a>

                                        <!-- Dropdown Menu -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-light dropdown-toggle"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-horizontal text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="javascript:;"
                                                        wire:click="editAccount({{ $account->id }})">
                                                        <i class="mdi mdi-pencil me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="javascript:;"
                                                        onclick="confirmDeletion({{ $account->id }})">
                                                        <i class="mdi mdi-delete me-2"></i> Delete
                                                    </a>
                                                </li>
                                                @if ($account->is_default != 1)
                                                <li>
                                                    <a class="dropdown-item" href="javascript:;"
                                                        wire:click="setAsDefaultAccount({{ $account->id }})">
                                                        <i class="mdi mdi-check-circle-outline me-2"></i>
                                                        Set as Default
                                                    </a>
                                                </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                    @endforeach


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Bank Account Details -->
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            @if ($selectedAccount)
                            <div class="card shadow-sm border-0 rounded-lg">
                                <div
                                    class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0 text-light"><i
                                            class="fas fa-file-invoice-dollar me-2"></i>
                                        Account Details</h4>
                                    <span
                                        class="badge bg-light text-dark">{{ ucfirst($selectedAccount->account_type) }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-2">
                                                <strong><i class="fas fa-user me-2"></i> Holder Name:</strong>
                                                <span
                                                    id="accountHolder">{{ $selectedAccount->account_holder_name }}</span>

                                            </p>
                                            <p class="mb-2">
                                                <strong><i class="fas fa-university me-2"></i> Bank Name:</strong>
                                                <span id="bankName">{{ $selectedAccount->bank_name }}</span>

                                            </p>
                                            <p class="mb-2">
                                                <strong><i class="fas fa-map-marker-alt me-2"></i> Branch:</strong>
                                                <span id="branchName">{{ $selectedAccount->branch_name }}</span>

                                            </p>
                                            <p class="mb-2 position-relative">
                                                <strong><i class="fas fa-credit-card me-2"></i> Account No:</strong>
                                                <span id="accountNo">{{ $selectedAccount->account_no }}</span>

                                            </p>
                                            <p class="mb-2">
                                                <strong><i class="fas fa-code me-2"></i> IFSC Code:</strong>
                                                <span id="ifscCode">{{ $selectedAccount->ifsc_code }}</span>

                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-2">
                                                <strong><i class="fas fa-list-alt me-2"></i> Type:</strong>
                                                <span
                                                    id="accountType">{{ ucfirst($selectedAccount->account_type) }}</span>

                                            </p>
                                            <p class="mb-2">
                                                <strong><i class="fas fa-balance-scale me-2"></i> Opening
                                                    Status:</strong>
                                                <span
                                                    class="badge {{ $selectedAccount->opening_status ? 'bg-success' : 'bg-danger' }}">
                                                    <span
                                                        id="openingStatus">{{ $selectedAccount->opening_status ? 'Positive' : 'Negative' }}</span>

                                                </span>
                                            </p>
                                            <p class="mb-2">
                                                <strong><i class="fas fa-wallet me-2"></i> Opening Balance:</strong>
                                                <span class="text-primary fw-bold">₹<span
                                                        id="openingBalance">{{ number_format($selectedAccount->opening_balance, 2) }}</span>

                                                </span>
                                            </p>
                                            <p class="mb-2">
                                                <strong><i class="fas fa-calendar-alt me-2"></i> Opening
                                                    Date:</strong>
                                                <span id="openingDate">
                                                    {{ \Carbon\Carbon::parse($selectedAccount->opening_date)->format('d M Y') }}</span>

                                            </p>
                                            <p class="mb-2 position-relative">
                                                <strong><i class="fab fa-cc-apple-pay me-2"></i> UPI ID:</strong>
                                                <span
                                                    id="upiId">{{ $selectedAccount->upi_id ?? 'N/A' }}</span>
                                                @if ($selectedAccount->upi_id)
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            @if ($selectedAccount && $selectedAccount->getFirstMediaUrl('bank-qr'))
                                            @php
                                            $qrUrl = $selectedAccount->getFirstMediaUrl('bank-qr');
                                            $whatsappShareLink = 'https://wa.me/?text=' . urlencode("Here is the bank QR code:\n" . $qrUrl);
                                            @endphp

                                            <div class="position-relative mb-2">
                                                <img src="{{ $qrUrl }}" class="img-fluid rounded border" alt="QR Code">
                                            </div>

                                            <a href="{{ $whatsappShareLink }}" target="_blank" class="btn btn-success">
                                                <i class="fab fa-whatsapp"></i> Share via WhatsApp
                                            </a>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer bg-light text-end">
                                    <button class="btn btn-outline-secondary btn-sm"
                                        onclick="downloadAccountDetails()">
                                        <i class="fas fa-download me-1"></i> Download
                                    </button>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i> Select an account to view details.
                            </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>

        </div><!-- container -->


        <!--Start Footer-->
        <livewire:layout.footer />



        @if ($accountFormModal)
        <div class="modal show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
            style="background: rgba(0, 0, 0, .6);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0" id="exampleModalDefaultLabel">
                            {{ $isEditing ? 'Edit' : 'Add' }} Account
                        </h6>
                        <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div><!--end modal-header-->

                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'saveAccount' }}"
                        class="needs-validation">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="doj">Account Holder Name<span
                                                style="color: red;">*</span></label>
                                        <input class="form-control" wire:model="account_holder_name"
                                            type="text" placeholder="" autocomplete="off">
                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Branch Name<span
                                                style="color: red;">*</span></label>
                                        <input class="form-control" wire:model="branch_name" type="text"
                                            placeholder="" autocomplete="off">

                                        @error('branch_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">IFSC Code<span style="color: red;">*</span></label>
                                        <input class="form-control" wire:model="ifsc_code" type="text"
                                            placeholder="" autocomplete="off">
                                        @error('ifsc_code')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Opening Balance<span
                                                style="color: red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="radio"
                                                    wire:model="opening_status" value="1" id="positive">
                                                <label for="positive" class="ms-1 me-2">Positive</label>

                                                <input class="form-check-input mt-0" type="radio"
                                                    wire:model="opening_status" value="0" id="negative">
                                                <label for="negative" class="ms-1">Negative</label>
                                            </div>
                                            <input type="text" class="form-control" placeholder=""
                                                wire:model="opening_balance"
                                                aria-label="Text input with radio button">
                                        </div>

                                        @error('notes')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">UPI Id</label>
                                        <input class="form-control" wire:model="upi_id" type="text"
                                            placeholder="" autocomplete="off">
                                        @error('upi_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end col -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="doj">Bank Name<span style="color: red;">*</span></label>
                                        <input class="form-control" wire:model="bank_name" type="text"
                                            placeholder="" autocomplete="off">
                                        @error('bank_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="doj">Account Number<span
                                                style="color: red;">*</span></label>
                                        <input class="form-control" wire:model="account_no" type="text"
                                            placeholder="" autocomplete="off">
                                        @error('account_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Account Type<span
                                                style="color: red;">*</span></label>
                                        <select class="form-control" wire:model="account_type">
                                            <option value="">Select A/C Type</option>
                                            <option value="savings">Savings</option>
                                            <option value="current">Current</option>
                                        </select>
                                        @error('account_type')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Opening Date<span
                                                style="color: red;">*</span></label>
                                        <input class="form-control" wire:model="opening_date" type="date"
                                            placeholder="" autocomplete="off">
                                        @error('opening_date')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">QR Code<span
                                                style="color: red;">*</span></label>
                                        <div x-data="{ isDragging: false }" x-on:dragover.prevent="isDragging = true"
                                            x-on:dragleave.prevent="isDragging = false"
                                            x-on:drop.prevent="isDragging = false; $wire.uploadMultiple('file', $event.dataTransfer.files)"
                                            class="border border-2 border-border-soft-secondary p-4 rounded text-center bg-light"
                                            :class="{ 'border-primary bg-primary bg-opacity-10': isDragging }">

                                            <!-- Show Loading Icon When File is Being Processed -->
                                            <div wire:loading wire:target="file" class="text-center">
                                                <span class="spinner-border spinner-border-lg text-primary" role="status" aria-hidden="true"></span>
                                                <p class="text-muted mt-2">Processing File...</p>
                                            </div>

                                            <!-- Hide File Input and Label While Uploading -->
                                            <div wire:loading.remove wire:target="files">
                                                <input type="file" wire:model="file" accept="image/*" class="d-none" id="fileInput">
                                                <label for="fileInput" class="d-block">
                                                    <p class="text-muted" x-show="!isDragging"><i class="fas fa-cloud-upload-alt"></i> Drag & Drop files Here or Click to Upload</p>
                                                    <p class="text-primary fw-bold" x-show="isDragging">Drop the file here...</p>
                                                </label>
                                                @error('files.*')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Show selected file names -->
                                            @if ($file)
                                            <div class="mt-3">
                                                <img src="{{ $file->temporaryUrl() }}" class="img-fluid" alt="Uploaded QR Code">
                                            </div>
                                            @elseif ($qr)
                                            <div class="mt-3 position-relative">
                                                <p class="fw-bold">Existing QR Code:</p>
                                                <img src="{{ $qr }}" class="img-fluid" alt="QR Code">
                                            </div>
                                            @else
                                            <div class="mt-3 text-muted">

                                            </div>
                                            @endif


                                        </div>
                                    </div>



                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end modal-body -->

                        <div class="modal-footer">
                            <button type="button" wire:click="closeModal"
                                class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <span wire:loading wire:target="saveAccount">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span> Loading...
                                </span>
                                <span wire:loading.remove wire:target="saveAccount">
                                    {{ $isEditing ? 'Update' : 'Save' }}
                                </span>
                            </button>
                        </div><!-- end modal-footer -->
                    </form>

                </div><!--end modal-content-->
            </div><!--end modal-dialog-->
        </div>
        @endif
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Enable Bootstrap tooltips
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Copy functionality
        document.querySelectorAll(".copy-icon").forEach(icon => {
            icon.addEventListener("click", function() {
                let copyText = document.getElementById(this.getAttribute("data-copy"))
                    .innerText;
                navigator.clipboard.writeText(copyText).then(() => {
                    this.setAttribute("title", "Copied!");
                    bootstrap.Tooltip.getInstance(this).show();
                    setTimeout(() => {
                        this.setAttribute("title", "Copy");
                        bootstrap.Tooltip.getInstance(this).hide();
                    }, 1000);
                });
            });
        });
    });

    // Download all account details
    function downloadAccountDetails() {
        let holderName = document.getElementById("accountHolder")?.innerText || "N/A";
        let bankName = document.getElementById("bankName")?.innerText || "N/A";
        let branch = document.getElementById("branchName")?.innerText || "N/A";
        let accountNo = document.getElementById("accountNo")?.innerText || "N/A";
        let ifscCode = document.getElementById("ifscCode")?.innerText || "N/A";
        let accountType = document.getElementById("accountType")?.innerText || "N/A";
        let openingStatus = document.getElementById("openingStatus")?.innerText || "N/A";
        let openingBalance = document.getElementById("openingBalance")?.innerText || "N/A";
        let openingDate = document.getElementById("openingDate")?.innerText || "N/A";
        let upiId = document.getElementById("upiId")?.innerText || "N/A";

        let details = `Account Details:
    -------------------------------
    Account Holder: ${holderName}
    Bank Name: ${bankName}
    Branch: ${branch}
    Account No: ${accountNo}
    IFSC Code: ${ifscCode}
    Account Type: ${accountType}
    UPI ID: ${upiId}
    -------------------------------`;
        //  Opening Status: ${openingStatus}
        //  Opening Balance: ₹${openingBalance}
        //  Opening Date: ${openingDate}

        let blob = new Blob([details], {
            type: "text/plain"
        });
        let a = document.createElement("a");
        a.href = URL.createObjectURL(blob);
        a.download = "Account_Details.txt";
        a.click();
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Livewire.hook('message.processed', (message, component) => {
            var dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            dropdownElements.forEach(function(dropdownElement) {
                new bootstrap.Dropdown(dropdownElement);
            });
        });
    });
</script>
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
</script>