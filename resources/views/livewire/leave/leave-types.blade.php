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
                               
                                <li class="breadcrumb-item active">Leave Types</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Leave Types</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                        @can('Create Leave Type')
                            <button class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" wire:click="addLeaveType()" type="button" >
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
                                            <th>Leave Type</th>
                                            <th>No. of Days</th>
                                            <th>Created At</th>
                                           
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i=1;
                                        @endphp
                                       
                                        @foreach ($leaveTypes as $lt)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td> {{ $lt->type_name }}</td>
                                                <td> {{ $lt->number_of_days }}</td>
                                                <td> {{ $lt->created_at }}</td>
                                                
                                                <td class="text-end">
                                                @can('Edit Leave Type')
                                                    <a href="javascript:;" wire:click="edit({{ $lt->id }})"><i class="las la-pen text-secondary font-16 text-info"></i></a>
                                                    @endcan  
                                                    @can('Delete Leave Type')   
                                                    <a href="javascript:;" onclick="confirmDeletion({{ $lt->id }})"><i class="las la-trash-alt text-secondary font-16 text-danger"></i></a>
                                                 @endcan 
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
                    <h6 class="modal-title m-0" id="exampleModalDefaultLabel"> {{ $modalMode === 'edit' ? 'Edit Leave Type' : 'Cretae Leave Type' }}</h6>
                    <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                @if ($modalMode === 'edit')
                <form wire:submit.prevent="update" class="needs-validation">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5>Edit Leave Type</h5>
                                <div class="mb-3">
                                    <input 
                                        class="form-control" 
                                        wire:model="data.type_name" 
                                        type="text" 
                                        placeholder="Type Leave Type Here..." 
                                        id="example-text-input" 
                                        autocomplete="off">
                                    @error('data.type_name') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div> 
                                <div class="mb-3">
                                    <input 
                                        class="form-control" 
                                        wire:model="data.number_of_days" 
                                        type="number" 
                                        placeholder="Type Number of Days Here..." 
                                        id="example-text-input" 
                                        autocomplete="off">
                                    @error('data.number_of_days') 
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
                <form wire:submit.prevent="createLeaveType" class="needs-validation">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5>Add New Leave Type</h5>
                                <div class="mb-3">
                                    <input 
                                        class="form-control" 
                                        wire:model="leaveTypeName" 
                                        type="text" 
                                        placeholder="Type Leave Type Here..." 
                                        id="example-text-input" 
                                        autocomplete="off">
                                    @error('leaveTypeName') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <input 
                                        class="form-control" 
                                        wire:model="numberOfDays" 
                                        type="number" 
                                        placeholder="Type Number of Days Here..." 
                                        id="example-text-input" 
                                        autocomplete="off">
                                    @error('numberOfDays') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end modal-body -->
        
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <span wire:loading wire:target="createLeaveType">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                            </span>
                            <span wire:loading.remove wire:target="createLeaveType">
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