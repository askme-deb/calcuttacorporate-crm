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
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li>

                                <li class="breadcrumb-item active">{{ $isEditing ? 'Edit' : 'Add' }} Attendance</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Attendance Records</h4>

                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <div class="text-end">
                                <ul class="list-inline">

                                    @can('Create Attendance')
                                    <li class="list-inline-item">
                                        <button class="btn btn-primary  px-4 mt-0 mb-3" wire:click="addAttendance()"
                                            type="button">
                                            <span wire:loading wire:target="addAttendance">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                            </span>
                                            <span wire:loading.remove wire:target="addAttendance">
                                                Add New <i class="fas fa-plus"></i>
                                            </span>

                                        </button>
                                    </li>
                                    @endcan
                                </ul>
                            </div>




                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Month</label>
                                    <input type="month" class="form-control" wire:model.lazy="filterMonth">
                                </div>
                                <div class="col-md-3">
                                    <label>Employee</label>
                                    <select class="form-control" wire:model.lazy="filterUser">
                                        <option value="">All Employees</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-secondary" wire:click="resetFilter">Reset Filter</button>
                                </div>
                            </div>





                            <div class="table-responsive">
                                <table class="table" border="1">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>In Time</th>
                                            <th>Out Time</th>
                                            <th>Date</th>
                                            <th>Total Hours</th>
                                            <th>Late By</th>
                                            <th>Early Exit</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($attendances as $attendance)
                                        @php
                                        $metrics = calculateMetrics($attendance);

                                        // Late by in minutes
                                        $lateParts = explode(':', $metrics['lateBy']);
                                        $lateMinutes = (isset($lateParts[1])) ? ($lateParts[0] * 60 + $lateParts[1]) : 0;

                                        // Early exit in minutes
                                        $earlyParts = explode(':', $metrics['earlyExit']);
                                        $earlyMinutes = (isset($earlyParts[1])) ? ($earlyParts[0] * 60 + $earlyParts[1]) : 0;

                                        // In Time class
                                        $inTimeClass = '';
                                        if ($lateMinutes > 0 && $lateMinutes < 15) {
                                            $inTimeClass .=' text-warning fw-bold' ;
                                            } elseif ($lateMinutes> 15) {
                                            $inTimeClass .= ' text-danger fw-bold';
                                            }

                                            // Out Time class
                                            $outTimeClass = '';
                                            if ($earlyMinutes > 0 && $earlyMinutes < 15) {
                                                $outTimeClass .=' text-warning fw-bold' ;
                                                } elseif ($earlyMinutes> 15) {
                                                $outTimeClass .= ' text-danger fw-bold';
                                                }
                                                @endphp

                                                <tr>
                                                    <td>{{ $attendance->user->name }}</td>
                                                    <td class="{{ $inTimeClass }}">
                                                        {{ \Carbon\Carbon::parse($attendance->in_time)->format('h:i A') }}
                                                    </td>
                                                    <td class="{{ $outTimeClass }}">
                                                        {{ \Carbon\Carbon::parse($attendance->out_time)->format('h:i A') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($attendance->dated)->format('l, d F Y') }}</td>
                                                    <td>{{ $metrics['worked'] }}</td>
                                                    <td>{{ $metrics['lateBy'] }}</td>
                                                    <td>{{ $metrics['earlyExit'] }}</td>
                                                    <td>{{ $attendance->status ? 'Present' : 'Absent' }}</td>
                                                    <td>
                                                        <a href="javascript:;" wire:click="edit({{ $attendance->id }})"><i class="las la-pen"></i></a>
                                                        <a href="javascript:;" onclick="confirmDeletion({{ $attendance->id }})"><i class="las la-trash-alt"></i></a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="9">No records found.</td>
                                                </tr>
                                                @endforelse

                                    </tbody>
                                </table>

                                {{-- <table class="table" border="1">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>In Time</th>
                                            <th>Out Time</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->user->name }}</td>
                                <td>{{ $attendance->in_time }}</td>
                                <td>{{ $attendance->out_time }}</td>
                                <td>{{ $attendance->dated }}</td>
                                <td>{{ $attendance->status ? 'Present' : 'Absent' }}</td>
                                <td>
                                    <a href="javascript:;" class="btn-sm edit-icon me-2"
                                        wire:click="edit({{ $attendance->id }})">
                                        <i class="las la-pen text-secondary font-16 text-info"></i>
                                    </a>
                                    <a href="javascript:;" class="btn-sm delete-icon"
                                        onclick="confirmDeletion({{ $attendance->id }})">
                                        <i
                                            class="las la-trash-alt text-secondary font-16 text-danger"></i>
                                    </a>
                                </td>
                                </tr>
                                @endforeach
                                </tbody>
                                </table> --}}
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->
        </div><!-- container -->

        <!--Start Footer-->
        <livewire:layout.footer />

        @if ($showModal)
        <div class="modal fade  show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
            style="background: rgba(0, 0, 0, .6);">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0" id="exampleModalDefaultLabel">
                            {{ $isEditing ? 'Edit' : 'Add' }} Attendance
                        </h6>
                        <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div><!--end modal-header-->
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="card p-4 shadow">

                        <div class="mb-3">
                            <label class="form-label">User</label>
                            <select wire:model="user_id" class="form-select">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" wire:model="dated" class="form-control"
                                    max="{{ now()->format('Y-m-d') }}">
                                @error('dated')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <div class="col-md-3">
                                <label class="form-label">In Time</label>
                                <input type="time" wire:model="in_time" class="form-control">
                                @error('in_time')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Out Time</label>
                                <input type="time" wire:model="out_time"
                                    {{ $this->isEditing ? '' : 'disabled' }} class="form-control">
                                @error('out_time')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>



                        {{-- <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select wire:model="status" class="form-select">
                                    <option value="1">Present</option>
                                    <option value="0">Absent</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                        @enderror
                </div> --}}

                <div class="d-flex gap-2">
                    <button type="submit"
                        class="btn btn-primary">{{ $isEditing ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="closeModal"
                        class="btn btn-secondary">Cancel</button>
                </div>
                </form>

            </div><!--end modal-content-->
        </div><!--end modal-dialog-->
    </div>
    @endif
    <!-- Add Attendance Form -->


    <!-- Attendance List -->

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