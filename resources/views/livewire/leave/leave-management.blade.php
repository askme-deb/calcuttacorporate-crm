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

                                <li class="breadcrumb-item active">Leave Applications</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Leave Applications</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                        @can('Create Leave')
                            <button class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" wire:click="addLeaveType()" type="button">
                                <span wire:loading wire:target="addLeaveType">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span wire:loading.remove wire:target="addLeaveType">
                                    Add New <i class="fas fa-plus"></i>
                                </span>

                            </button>
                        @endcan
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Employee Name</th>
                                            <th>Leave Type</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Applied On</th>
                                            <th>Joining Date</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i=1;
                                        @endphp

                                        @foreach ($leaveApplications as $application)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td> {{ $application->user->name }}</td>
                                            <td> {{ $application->leaveType->type_name }}</td>
                                            <td> {{ $application->apply_strt_date }}</td>
                                            <td> {{ $application->apply_end_date }}</td>
                                            <td> {{ $application->created_at }}</td>
                                            <td> {{ $application->join_date }}</td>
                                            <td>
                                                @if($application->status == 0)
                                                {!! getstatusss('Pending')!!}
                                                @elseif($application->status == 1)
                                                {!! getstatusss('Pending for Approval')!!}
                                                @elseif($application->status == 2)
                                                {!! getstatusss('Approved')!!}
                                                @elseif($application->status == 3)
                                                {!! getstatusss('Rejected')!!}
                                                @else
                                                {!! getstatusss('Unknown')!!}
                                                @endif
                                            </td>

                                            <td class="text-end">
                                                @can('Approve Leave')
                                                <a href="javascript:;" wire:click="approveForm({{ $application->id }})"><i class="las la-check-circle text-success font-16 text-info"></i></a>
                                                @endcan
                                                @if($application->status == 0 || auth()->user()->hasRole('Super Admin'))
                                                @can('Edit Leave')
                                                <a href="javascript:;" wire:click="edit({{ $application->id }})">
                                                    <i class="las la-pen text-secondary font-16 text-info"></i>
                                                </a>
                                                @endcan
                                                @can('Delete Leave')
                                                <a href="javascript:;" onclick="confirmDeletion({{ $application->id }})">
                                                    <i class="las la-trash-alt text-secondary font-16 text-danger"></i>
                                                </a>
                                                @endcan
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
        @if($showModal)
        <div class="modal show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="background: rgba(0, 0, 0, .6);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0" id="exampleModalDefaultLabel"> {{ $modalMode === 'edit' ? 'Edit Leave Application' : 'Apply New Leave' }}</h6>
                        <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div><!--end modal-header-->
                    @if ($modalMode === 'edit')
                    <form wire:submit.prevent="update" class="needs-validation">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="mb-3">
                                        <label for="doj">Employee<span style="color: red;">*</span></label>
                                        <select class="form-select" wire:model="selectedemployee" id="user">
                                            @can('Create Leave for Others')
                                            <option value="">Select Employee</option>
                                            @endcan
                                            @foreach ($users as $userid => $username)
                                            <option value="{{$userid }}">{{$username }}</option>
                                            @endforeach
                                        </select>

                                        @error('selectedemployee')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">From<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" wire:model="apply_strt_date" placeholder="">
                                        @error('apply_strt_date')
                                        <small class="error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">No. of Days for Apply</label>
                                        <input
                                            class="form-control"
                                            wire:model="apply_day"
                                            type="text"
                                            autocomplete="off" readonly>
                                        @error('apply_day')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Replace Person</label>
                                        <input
                                            class="form-control"
                                            wire:model="data.replace_person"
                                            type="text"
                                            placeholder=""
                                            id="example-text-input"
                                            autocomplete="off">
                                        @error('replace_person')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end col -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="doj">Leave Type<span style="color: red;">*</span></label>
                                        <select class="form-select" wire:model="selectedleaveType" id="leaveType">
                                            <option value="">Select Leave Type</option>
                                            @foreach ($leaveTypes as $typeid => $typename)
                                            <option value="{{$typeid }}">{{$typename }}</option>
                                            @endforeach
                                        </select>

                                        @error('selectedleaveType')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if ($remainingLeaves !== null)
                                        <p class="mt-2 text-primary">Remaining Leaves: <strong>{{ $remainingLeaves }}</strong></p>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">To<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" wire:model="apply_end_date" wire:change="calculateDifference" placeholder="">
                                        @error('apply_end_date')
                                        <small class="error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Join Date<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" wire:model="join_date" id="doj" placeholder="">
                                        @error('join_date')
                                        <small class="error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Reason</label>
                                        <textarea
                                            class="form-control"
                                            wire:model="data.reason"
                                            type="number"
                                            placeholder=""
                                            id="example-text-input"
                                            autocomplete="off"></textarea>
                                        @error('reason')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end col -->

                            </div><!-- end row -->
                        </div><!-- end modal-body -->

                        <div class="modal-footer">
                            <button type="button" wire:click="closeModal" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <span wire:loading wire:target="update">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                </span>
                                <span wire:loading.remove wire:target="update">
                                    Save changes
                                </span>
                            </button>
                        </div><!-- end modal-footer -->
                    </form>
                    @else
                    <form wire:submit.prevent="createLeaveApplication" class="needs-validation">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="mb-3">
                                        <label for="doj">Employee<span style="color: red;">*</span></label>


                                        <select class="form-select" wire:model="employee_id" id="employee" wire:change="changeLeavetype">
                                            @can('Create Leave for Others')
                                            <option value="">Select Employee</option>
                                            @endcan
                                            @foreach ($users as $userid => $username)
                                            <option value="{{$userid }}">{{$username }}</option>
                                            @endforeach
                                        </select>

                                        @error('employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">From<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" wire:model="apply_strt_date" id="doj" placeholder="">
                                        @error('apply_strt_date')
                                        <small class="error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">No. of Days for Apply</label>
                                        <input
                                            class="form-control"
                                            wire:model="apply_day"
                                            type="text"
                                            autocomplete="off" readonly>
                                        @error('apply_day')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Replace Person</label>
                                        <input
                                            class="form-control"
                                            wire:model="replace_person"
                                            type="text"
                                            placeholder=""
                                            id="example-text-input"
                                            autocomplete="off">
                                        @error('replace_person')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end col -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="doj">Leave Type<span style="color: red;">*</span></label>
                                        <select class="form-select" wire:model="leave_type_id" id="leaveType" wire:change="updateLeaveType($event.target.value)">
                                            <option value="">Select Leave Type</option>
                                            @foreach ($leaveTypes as $typeid => $typename)
                                            <option value="{{ $typeid }}">{{ $typename }}</option>
                                            @endforeach
                                        </select>


                                        @error('leave_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if ($remainingLeaves !== null)
                                        <p class="mt-2 "><span class="text-danger">Enjoyed Leaves: <strong>{{ $enjoyedLeaves }}</strong></span>

                                            <span class="text-primary">Remaining Leaves:<strong>{{ $remainingLeaves }}</strong></span>
                                        </p>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">To<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" wire:model="apply_end_date" id="doj" wire:change="calculateDifference" placeholder="">
                                        @error('apply_end_date')
                                        <small class="error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Join Date<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" wire:model="join_date" id="doj" placeholder="">
                                        @error('join_date')
                                        <small class="error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="doj">Reason</label>
                                        <textarea
                                            class="form-control"
                                            wire:model="reason"
                                            type="number"
                                            placeholder=""
                                            id="example-text-input"
                                            autocomplete="off"></textarea>
                                        @error('reason')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div><!-- end col -->

                            </div><!-- end row -->
                        </div><!-- end modal-body -->

                        <div class="modal-footer">
                            <button type="button" wire:click="closeModal" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <span wire:loading wire:target="createLeaveApplication">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                </span>
                                <span wire:loading.remove wire:target="createLeaveApplication">
                                    Save changes
                                </span>
                            </button>
                        </div><!-- end modal-footer -->
                    </form>
                    @endif
                </div><!--end modal-content-->
            </div><!--end modal-dialog-->
        </div>

        <script>
            document.addEventListener("livewire:init", function() {
                document.getElementById('leaveType').addEventListener('change', function() {
                    let selectedValue = this.value;
                    console.log("Selected Leave Type:", selectedValue); // Debugging log
                    Livewire.dispatch('updateLeaveType', selectedValue);
                });
            });
        </script>
        @endif
        <!--end modal-->

        @if($approvedModal)
        <div class="modal show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="background: rgba(0, 0, 0, .6);">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0" id="exampleModalDefaultLabel"> Approved</h6>
                        <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div><!--end modal-header-->


                    <form wire:submit.prevent="saveApprovedStatus" class="needs-validation">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <div class="mb-3">
                                        <label for="doj">Approved Status<span style="color: red;">*</span></label>


                                        <select class="form-select" wire:model="approved_status" id="employee">

                                            <option value="">Choose Status</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Pending for Approval</option>
                                            <option value="2">Approved</option>
                                            <option value="3">Rejected</option>

                                        </select>

                                        @error('approved_status')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div><!-- end col -->


                            </div><!-- end row -->
                        </div><!-- end modal-body -->

                        <div class="modal-footer">
                            <button type="button" wire:click="closeModal" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <span wire:loading wire:target="saveApprovedStatus">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                </span>
                                <span wire:loading.remove wire:target="saveApprovedStatus">
                                    Save changes
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
@if (session()->has('toast_message'))
<div class="toast d-flex align-items-center text-white position-absolute bg-{{ session('toast_type') }} border-0 p-2 top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 999;">
    <div class="toast-body">
        {{ session('toast_message') }}
    </div>
    <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
</div>
@endif