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
                               
                                <li class="breadcrumb-item active">Roles</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Roles</h4>
                       
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <button class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" wire:click="addRole()" type="button" >
                                <span wire:loading wire:target="addRole">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span wire:loading.remove wire:target="addRole">
                                    Add New <i class="fas fa-plus"></i> 
                                </span>
                                
                              </button>
                           
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Role Name</th>
                                            <th>Asign Permission to Role</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i=1;
                                        @endphp
                                       
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td> {{ $role->name }}</td>
                                                <td>
                                                    <a href="{{ route('permissions.asign', $role->id) }}" class="btn btn-outline-success" wire:navigate><i class="fas fa-tasks"></i> Asign Permission</a>
                                                </td>
                                                <td class="text-end">
                                                    <a href="javascript:;" wire:click="edit({{ $role->id }})"><i class="las la-pen text-secondary font-16 text-info"></i></a>
                                                    <a href="javascript:;" onclick="confirmDeletion({{ $role->id }})"><i class="las la-trash-alt text-secondary font-16 text-danger"></i></a>
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
                    <h6 class="modal-title m-0" id="exampleModalDefaultLabel"> {{ $modalMode === 'edit' ? 'Edit Role' : 'Cretae Role' }}</h6>
                    <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                @if ($modalMode === 'edit')
                <form wire:submit.prevent="update" class="needs-validation">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5>Edit Role</h5>
                                <div class="mb-3">
                                    <input 
                                        class="form-control" 
                                        wire:model="data.name" 
                                        type="text" 
                                        placeholder="Type Role Name Here..." 
                                        id="example-text-input" 
                                        autocomplete="off">
                                    @error('data.name') 
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
                <form wire:submit.prevent="createRole" class="needs-validation">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5>Add New Role</h5>
                                <div class="mb-3">
                                    <input 
                                        class="form-control" 
                                        wire:model="roleName" 
                                        type="text" 
                                        placeholder="Type Role Name Here..." 
                                        id="example-text-input" 
                                        autocomplete="off">
                                    @error('roleName') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end modal-body -->
        
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <span wire:loading wire:target="createRole">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                            </span>
                            <span wire:loading.remove wire:target="createRole">
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
//     document.addEventListener('livewire:load', function () {
//     // Listen for Livewire browser events
//     Livewire.dispatch('show-toast', event => {
//         console.log(event.detail.message);
//         var toastElement = document.querySelector('.toast');
//         if (toastElement) {
//             var toast = new bootstrap.Toast(toastElement);
//             toast.show();
//         }
//     });
// });


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
