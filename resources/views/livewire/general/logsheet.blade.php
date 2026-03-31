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

                                <li class="breadcrumb-item active">Logs Sheet</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Daily Logs Sheet</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="col-lg-6 text-start">
                <div class="">

                    <ul class="list-inline">


                        <li class="list-inline-item">
                            <label for="">Filter by Date</label>
                           <input type="date" class="form-control" wire:model="log_date" wire:change="changeLogDate">
                        </li>


                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">


                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                             <th>Sl. No</th>
                                            <th>Employee</th>
                                            <th>Date</th>
                                            <th>Task Summary</th>
                                            <th>Hours Worked</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($logs as $index => $log)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $log->user->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($log->log_date)->format('d M, Y') }}</td>
                                                <td>{{ $log->task_summary }}</td>
                                                <td>{{ $log->hours_worked }} hrs</td>
                                                <td>{{ $log->remarks ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <a class="p-2" style="cursor: pointer;" wire:click="deleteLog({{ $log->id }})"
                                                       wire:click.confirm="Are you sure you want to delete this log?">
                                                        <i class="far fa-trash-alt text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No logs available</td>
                                            </tr>
                                        @endforelse
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

        <!--end modal-->

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
    <div class="toast d-flex align-items-center text-white position-absolute bg-{{ session('toast_type') }} border-0 p-2 top-0 end-0"
        role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 999;">
        <div class="toast-body">
            {{ session('toast_message') }}
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast"
            aria-label="Close"></button>
    </div>
@endif
