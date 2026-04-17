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
                                <li class="breadcrumb-item active">Leads</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Leads</h4>
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
                                    <h4 class="card-title ">Import Lead from Excel/CSV</h4>
                                    <form wire:submit.prevent="import" class="col-lg-9">
                                        <div class="input-group">
                                            <input type="file" wire:model="file" class="form-control"
                                                id="inputGroupFile04" aria-describedby="inputGroupFileAddon04"
                                                aria-label="Upload">
                                            <button class="btn btn-outline-primary" type="submit"
                                                id="inputGroupFileAddon04">

                                                <span wire:loading wire:target="import">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                </span>
                                                <span wire:loading.remove wire:target="import">
                                                    <i class="fas fa-cloud-upload-alt"></i> Import
                                                </span>
                                            </button>
                                        </div>
                                        @error('file')
                                            <span class="text-danger mb-3">{{ $message }}</span>
                                        @enderror
                                    </form>
                                    <p class="text-muted mb-0 ">Here are examples template of lead<code
                                            class="highlighter-rouge">
                                            <a href="javascript:;" wire:click="download" class="text-success">
                                                <i class="fas fa-cloud-download-alt"></i> Sample Template
                                            </a>

                                        </code>
                                    </p>
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
                                            <li class="list-inline-item">
                                                <button type="button" class="btn btn-primary"><i
                                                        class="fas fa-filter"></i></button>
                                            </li>
                                            <li class="list-inline-item">
                                                <button class="btn btn-primary" wire:click="addLead()" type="button">
                                                    <span wire:loading wire:target="addLead">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                    <span wire:loading.remove wire:target="addLead">
                                                        <i class="fas fa-plus"></i> Add New
                                                    </span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!--end col-->



                            </div>
                        </div>
                        <div class="card-body mb-n3">
                            <div class="table-responsive">

                                <div>
                                    @can('Assign Lead')
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <div class=" row">
                                                    <label for="example-text-input"
                                                        class="col-sm-4 col-form-label text-end">Select User to
                                                        Assign:</label>
                                                    <div class="col-sm-8 mt-2">
                                                        <select wire:model="selectedUser" class="form-control">
                                                            <option value="">-- Select User --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="row">
                                                    <button class="btn btn-primary mb-3 mt-2" wire:click="assignLeads"
                                                        {{ empty($selectedLeads) ? 'disabled' : '' }}>
                                                        Assign Selected Leads
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    @endcan
                                    <table class="table mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                @can('Assign Lead')
                                                    <th>
                                                        <input type="checkbox" id="selectAllCheckbox">
                                                    </th>
                                                @endcan
                                                <th>Sl No.</th>
                                                <th>Name</th>
                                                <th>Source</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Dated</th>
                                                <th>Next Followup</th>
                                                @can('View All Leads')
                                                    <th>Assigned To</th>
                                                @endcan
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($leads as $lead)
                                                <tr>
                                                    @can('Assign Lead')
                                                        <td>
                                                            <input type="checkbox" class="lead-checkbox"
                                                                value="{{ $lead->id }}" wire:model="selectedLeads">
                                                        </td>
                                                    @endcan
                                                    <td>{{ $i++ }}</td>
                                                    <td>
                                                        <a wire:navigate
                                                            href="{{ route('lead.details', ['id' => Crypt::encryptString($lead->id)]) }}"
                                                            class="text-primary">
                                                            {{ $lead->name }}
                                                            {!! getstatusss(optional($lead->leadPriority)->name) !!}
                                                        </a>
                                                    </td>
                                                    <td>{!! getstatusss(optional($lead->leadSource)->name) !!}</td>
                                                    <td>{{ $lead->phone }}</td>
                                                    <td>{!! getstatusss(optional($lead->leadStatus)->name) !!}</td>
                                                    <td>{{ $lead->created_at }}</td>
                                                    <td>{{ $lead->next_followup_date ? formatDate($lead->next_followup_date) : '' }}
                                                    </td>
                                                    @can('View All Leads')
                                                        <td>{{ optional($lead->assignedUser)->name ?? 'Not Assigned' }}
                                                        </td>
                                                    @endcan
                                                    <td>
                                                        <a href="javascript:;" wire:click="edit({{ $lead->id }})">
                                                            <i class="las la-pen text-secondary font-16 text-info"></i>
                                                        </a>
                                                        <a href="javascript:;"
                                                            onclick="confirmDeletion('{{ $lead->id }}')">
                                                            <i
                                                                class="las la-trash-alt text-secondary font-16 text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <script>
                                    // document.addEventListener("DOMContentLoaded", function() {
                                    //     let selectAllCheckbox = document.getElementById("selectAllCheckbox");
                                    //     let checkboxes = document.querySelectorAll(".lead-checkbox");

                                    //     function updateSelectedLeads() {
                                    //         let selectedLeads = Array.from(checkboxes)
                                    //             .filter(checkbox => checkbox.checked)
                                    //             .map(checkbox => checkbox.value);

                                    //         Livewire.dispatch('updateSelectedLeads', {selectedLeads});
                                    //     }

                                    //     selectAllCheckbox.addEventListener("change", function() {
                                    //         checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
                                    //         updateSelectedLeads();
                                    //     });

                                    //     checkboxes.forEach(checkbox => {
                                    //         checkbox.addEventListener("change", function() {
                                    //             selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox
                                    //                 .checked);
                                    //             updateSelectedLeads();
                                    //         });
                                    //     });
                                    // });

                                    // document.addEventListener("DOMContentLoaded", function() {
                                    //     let selectAllCheckbox = document.getElementById("selectAllCheckbox");
                                    //     let checkboxes = document.querySelectorAll(".lead-checkbox");

                                    //     function updateSelectedLeads() {
                                    //         let selectedLeads = Array.from(checkboxes)
                                    //             .filter(checkbox => checkbox.checked)
                                    //             .map(checkbox => checkbox.value);

                                    //         Livewire.dispatch('updateSelectedLeads', {
                                    //             selectedLeads
                                    //         });

                                    //         $wire.set('updateSelectedLeads' ,selectedLeads)
                                    //         $wire.updateSelectedLeads = selectedLeads;
                                    //     }

                                    //     selectAllCheckbox.addEventListener("change", function() {
                                    //         checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
                                    //         updateSelectedLeads();
                                    //     });

                                    //     checkboxes.forEach(checkbox => {
                                    //         checkbox.addEventListener("change", function() {
                                    //             selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                                    //             updateSelectedLeads();
                                    //         });
                                    //     });
                                    // });
                                </script>



                                {{ $leads->links(data: ['scrollTo' => false]) }}
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
        @if ($showModal)
            <div class="modal show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
                style="background: rgba(0, 0, 0, .6);">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title m-0" id="exampleModalDefaultLabel">
                                {{ $modalMode === 'edit' ? 'Edit Lead' : 'Add New Lead' }}
                            </h6>
                            <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div><!--end modal-header-->
                        @if ($modalMode === 'edit')
                            <form wire:submit.prevent="update" class="needs-validation">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="doj">Name<span style="color: red;">*</span></label>
                                                <input class="form-control" wire:model="name" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Phone<span style="color: red;">*</span></label>
                                                <input class="form-control" wire:model="phone" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Status<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="status_id" id="leadStatus">
                                                    <option value="">Select Status</option>
                                                    @foreach ($leadStatus as $statusid => $statusname)
                                                        <option value="{{ $statusid }}">{{ $statusname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('status_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Notes</label>
                                                <textarea class="form-control" wire:model="notes" type="number" placeholder="" autocomplete="off"></textarea>
                                                @error('notes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Company Name</label>
                                                <input class="form-control" wire:model="company" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('company')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Budget</label>
                                                <input class="form-control" wire:model="budget" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('budget')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div><!-- end col -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="doj">Email</label>
                                                <input class="form-control" wire:model="email" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="doj">Source<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="source_id" id="leaveType">
                                                    <option value="">Select Sources</option>
                                                    @foreach ($leadSources as $sourceid => $sourcename)
                                                        <option value="{{ $sourceid }}">{{ $sourcename }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('source_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Priority</label>
                                                <select class="form-select" wire:model="priority_id" id="leaveType">
                                                    <option value="">Select Priority</option>
                                                    @foreach ($leadPriorities as $priorityid => $priorityname)
                                                        <option value="{{ $priorityid }}">{{ $priorityname }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                                @error('priority_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Address</label>
                                                <textarea class="form-control" wire:model="address" placeholder="" autocomplete="off"></textarea>
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Position</label>
                                                <input class="form-control" wire:model="position" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('position')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="doj">Follow-Up Date<span
                                                        style="color: red;">*</span></label>
                                                <input type="date" class="form-control"
                                                    wire:model="next_followup_date" id="doj" placeholder="">
                                                @error('next_followup_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>


                                        </div><!-- end col -->

                                    </div><!-- end row -->
                                </div><!-- end modal-body -->

                                <div class="modal-footer">
                                    <button type="button" wire:click="closeModal"
                                        class="btn btn-outline-secondary btn-sm"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <span wire:loading wire:target="update">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Loading...
                                        </span>
                                        <span wire:loading.remove wire:target="update">
                                            Save changes
                                        </span>
                                    </button>
                                </div><!-- end modal-footer -->
                            </form>
                        @else
                            <form wire:submit.prevent="createLead" class="needs-validation">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="doj">Name<span style="color: red;">*</span></label>
                                                <input class="form-control" wire:model="name" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Phone<span style="color: red;">*</span></label>
                                                <input class="form-control" wire:model="phone" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Status<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="status_id" id="leadStatus">
                                                    <option value="">Select Status</option>
                                                    @foreach ($leadStatus as $statusid => $statusname)
                                                        <option value="{{ $statusid }}">{{ $statusname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('status_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Notes</label>
                                                <textarea class="form-control" wire:model="notes" type="number" placeholder="" autocomplete="off"></textarea>
                                                @error('notes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Company Name</label>
                                                <input class="form-control" wire:model="company" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('company')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Budget</label>
                                                <input class="form-control" wire:model="budget" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('budget')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div><!-- end col -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="doj">Email</label>
                                                <input class="form-control" wire:model="email" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="doj">Source<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="source_id" id="leaveType">
                                                    <option value="">Select Sources</option>
                                                    @foreach ($leadSources as $sourceid => $sourcename)
                                                        <option value="{{ $sourceid }}">{{ $sourcename }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('source_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Priority</label>
                                                <select class="form-select" wire:model="priority_id" id="leaveType">
                                                    <option value="">Select Priority</option>
                                                    @foreach ($leadPriorities as $priorityid => $priorityname)
                                                        <option value="{{ $priorityid }}">{{ $priorityname }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                                @error('priority_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Address</label>
                                                <textarea class="form-control" wire:model="address" placeholder="" autocomplete="off"></textarea>
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Position</label>
                                                <input class="form-control" wire:model="position" type="text"
                                                    placeholder="" autocomplete="off">
                                                @error('position')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="doj">Follow-Up Date<span
                                                        style="color: red;">*</span></label>
                                                <input type="date" class="form-control"
                                                    wire:model="next_followup_date" id="doj" placeholder="">
                                                @error('next_followup_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>


                                        </div><!-- end col -->

                                    </div><!-- end row -->
                                </div><!-- end modal-body -->

                                <div class="modal-footer">
                                    <button type="button" wire:click="closeModal"
                                        class="btn btn-outline-secondary btn-sm"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <span wire:loading wire:target="createLead">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Loading...
                                        </span>
                                        <span wire:loading.remove wire:target="createLead">
                                            Save changes
                                        </span>
                                    </button>
                                </div><!-- end modal-footer -->
                            </form>
                        @endif
                    </div><!--end modal-content-->
                </div><!--end modal-dialog-->
            </div>
        @endif
        <!--end modal-->
        <!-- end Footer -->
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
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize immediately on DOMContentLoaded
    initializeCheckboxes();

    function initializeCheckboxes() {
        let selectAllCheckbox = document.getElementById("selectAllCheckbox");
        let checkboxes = document.querySelectorAll(".lead-checkbox");

        if (!selectAllCheckbox || checkboxes.length === 0) {
            // Try again in a short moment if elements aren't found
            setTimeout(initializeCheckboxes, 100);
            return;
        }

        function updateSelectedLeads() {
            let selectedLeads = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            Livewire.dispatch('updateSelectedLeads', { selectedLeads });
        }

        // Remove previous event listeners if reattaching
        selectAllCheckbox.removeEventListener("change", handleSelectAllChange);

        function handleSelectAllChange() {
            checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
            updateSelectedLeads();
        }

        selectAllCheckbox.addEventListener("change", handleSelectAllChange);

        checkboxes.forEach(checkbox => {
            // Remove previous listener if reattaching
            checkbox.removeEventListener("change", handleCheckboxChange);

            function handleCheckboxChange() {
                selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                updateSelectedLeads();
            }

            checkbox.addEventListener("change", handleCheckboxChange);
        });
    }

    // For Livewire 3
    document.addEventListener("livewire:init", function () {
        initializeCheckboxes();
    });

    // For older Livewire versions
    // document.addEventListener("livewire:load", function () {
    //     initializeCheckboxes();
    // });

    // Watch for changes in the document
    let observer = new MutationObserver(() => {
        initializeCheckboxes();
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // Reinitialize when Livewire navigates
    document.addEventListener("livewire:navigated", function () {
        initializeCheckboxes();
    });
});
</script>
