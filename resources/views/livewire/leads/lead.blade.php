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
                                <li class="breadcrumb-item"><a href="#">Metrica</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item"><a href="#">Hospital</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">Appointments</li>
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
                        <div class="card-body mb-n3">
                            {{-- <a href="{{ route('createLead') }}" wire:navigate class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" >Add New <i class="fas fa-plus"></i></a> --}}
                            <button class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" wire:click="addLead()"
                                type="button">
                                <span wire:loading wire:target="addLead">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                                <span wire:loading.remove wire:target="addLead">
                                    Add New <i class="fas fa-plus"></i>
                                </span>
                            </button>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Source</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th>Address</th>
                                            <th>Dated</th>
                                            <th>Action</th>
                                        </tr><!--end tr-->
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>Donald Gardner</td>
                                            <td>36</td>
                                            <td>Orthopedic</td>
                                            <td><img src="assets/images/users/user-1.jpg" alt=""
                                                    class="thumb-sm rounded-circle me-2">Dr.Thomas Fant</td>
                                            <td>18/07/2019</td>
                                            <td>10:15 am</td>
                                            <td>+123456789</td>
                                            <td>
                                                <a href="#"><i class="las la-pen text-secondary font-18"></i></a>
                                                <a href="#"><i
                                                        class="las la-trash-alt text-danger font-18"></i></a>
                                            </td>
                                        </tr><!--end tr-->
                                    </tbody>
                                </table>
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
                                {{ $modalMode === 'edit' ? 'Edit Lead' : 'Add New Lead' }}</h6>
                            <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div><!--end modal-header-->
                        @if ($modalMode === 'edit')
                            <form wire:submit.prevent="update" class="needs-validation">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="doj">Holiday Name</label>
                                                <input class="form-control" wire:model="holidayName" type="text"
                                                    placeholder="" id="example-text-input" autocomplete="off">
                                                @error('holidayName')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">From<span style="color: red;">*</span></label>
                                                <input type="date" class="form-control" wire:model="start_date"
                                                    id="doj" placeholder="">
                                                @error('start_date')
                                                    <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">To<span style="color: red;">*</span></label>
                                                <input type="date" class="form-control" wire:model="end_date"
                                                    id="doj" wire:change="calculateDifference" placeholder="">
                                                @error('apply_end_date')
                                                    <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">No. of Days</label>
                                                <input class="form-control" wire:model="no_of_days" type="text"
                                                    autocomplete="off" readonly>
                                                @error('apply_day')
                                                    <span class="text-danger">{{ $message }}</span>
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
                                                    placeholder="" id="example-text-input" autocomplete="off">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Phone<span style="color: red;">*</span></label>
                                                <input class="form-control" wire:model="phone" type="text"
                                                    placeholder="" id="example-text-input" autocomplete="off">
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Status<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="status_id" id="user">
                                                    <option value="">Select Employee</option>

                                                </select>
                                                @error('status_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Notes</label>
                                                <textarea class="form-control" wire:model="notes" type="number" placeholder="" id="example-text-input"
                                                    autocomplete="off"></textarea>
                                                @error('notes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Company Name</label>
                                                <input class="form-control" wire:model="company	" type="text"
                                                    placeholder="" id="example-text-input" autocomplete="off">
                                                @error('company	')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Budget</label>
                                                <input class="form-control" wire:model="budget" type="text"
                                                    placeholder="" id="example-text-input" autocomplete="off">
                                                @error('budget')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div><!-- end col -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="doj">Email</label>
                                                <input class="form-control" wire:model="email" type="text"
                                                    placeholder="" id="example-text-input" autocomplete="off">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="doj">Source<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="source_id"
                                                    id="leaveType">
                                                    <option value="">Select Leave Type</option>

                                                </select>
                                                @error('source_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Priority</label>
                                                <select class="form-select" wire:model="priority_id"
                                                    id="leaveType">
                                                    <option value="">Select Leave Type</option>

                                                </select>

                                                @error('priority_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Address</label>
                                                <textarea class="form-control" wire:model="address"  placeholder="" id="example-text-input"
                                                    autocomplete="off"></textarea>
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="doj">Position</label>
                                                <input class="form-control" wire:model="position" type="text"
                                                    placeholder="" id="example-text-input" autocomplete="off">
                                                @error('position')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="doj">Follow-Up Date<span style="color: red;">*</span></label>
                                                <input type="date" class="form-control"
                                                    wire:model="next_follwup_date" id="doj"
                                                    wire:change="calculateDifference" placeholder="">
                                                @error('next_follwup_date')
                                                    <small class="error">{{ $message }}</small>
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
