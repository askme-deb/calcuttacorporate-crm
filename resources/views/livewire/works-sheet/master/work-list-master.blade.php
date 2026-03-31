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

                                <li class="breadcrumb-item active">Work List</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Work List</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <button class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" wire:click="addLeadSource()" type="button" >
                                <span wire:loading wire:target="addLeadSource">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span wire:loading.remove wire:target="addLeadSource">
                                    Add New <i class="fas fa-plus"></i>
                                </span>

                              </button>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Source Name</th>
                                            <th>Visibility Status</th>
                                            <th>Created At</th>

                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i=1;
                                        @endphp

                                        @foreach ($worklist as $wl)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td> {{ $wl->name }}</td>
                                                <td> {!! getStatus($wl->is_visible) !!}</td>
                                                <td> {{ $wl->created_at }}</td>

                                                <td class="text-end">
                                                    <a href="javascript:;" wire:click="edit({{ $wl->id }})"><i class="las la-pen text-secondary font-16 text-info"></i></a>
                                                    <a href="javascript:;" onclick="confirmDeletion({{ $wl->id }})"><i class="las la-trash-alt text-secondary font-16 text-danger"></i></a>
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
       <div class="modal fade  show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"  style="background: rgba(0, 0, 0, .6);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="exampleModalDefaultLabel"> {{ $modalMode === 'edit' ? 'Edit Work List' : 'Cretae Work List' }}</h6>
                    <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                @if ($modalMode === 'edit')
                <form wire:submit.prevent="update" class="needs-validation">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="mb-3">
                                    <label for="doj" class="mb-2">Source Name<span style="color: red;">*</span></label>
                                    <input class="form-control" wire:model="name" type="text"
                                        placeholder=""  autocomplete="off">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="isVisibles" class="mb-2">Visiblity Status</label>
                                    <div class="form-check form-switch form-switch-success">
                                        <input
                                        id="isVisible"
                                        type="checkbox"
                                        class="form-check-input"
                                        wire:model="selectedIsVisible"
                                        value=""
                                        >
                                        <label class="form-check-label" for="isVisible">Visible</label>
                                    </div>
                                        @error('selectedIsVisible')
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
                <form wire:submit.prevent="createLeadSource" class="needs-validation">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="mb-3">
                                    <label for="doj" class="mb-2">Source Name<span style="color: red;">*</span></label>
                                    <input class="form-control" wire:model="name" type="text"
                                        placeholder=""  autocomplete="off">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="isVisiblef" class="mb-2">Visiblity Status</label>
                                    <div class="form-check form-switch form-switch-success">
                                        <input
                                        id="isVisible"
                                        type="checkbox"
                                        class="form-check-input"
                                        wire:model="is_visible"
                                        value=""
                                        >
                                        <label class="form-check-label" for="isVisible">Visible</label>
                                    </div>
                                        @error('isVisible')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end modal-body -->

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <span wire:loading wire:target="createLeadSource">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                            </span>
                            <span wire:loading.remove wire:target="createLeadSource">
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
                Livewire.dispatch('deleteItem', { id: itemId}); // Dispatch Livewire event
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
