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
                                    <a wire:navigate href="{{ route('dashboard')}}">Dashboard</a>
                                </li>
                               
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Users</h4>
                       
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <a class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3"  href="{{ route('createUser')}}" wire:navigate>
                               
                                    Add New <i class="fas fa-plus"></i> 
                                
                                
                            </a>
                             
                              
                            <div class="table-responsive">

                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i=1;
                                        @endphp
                                       
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td> <img src="{{ $user->getFirstMediaUrl('user') }}" alt="" class="thumb-sm rounded-circle me-2"> {{ $user->name }} 
                                                  
                                                   
                                                </td>
                                                <td> {{ $user->email }}</td>
                                                <td>
                                                    @if (!empty($user->getRoleNames()))
                                                        @foreach ($user->getRoleNames() as $role)
                                                        <span class="badge badge-outline-primary">{{ $role }}</span>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('user.edit', ['id' => Crypt::encryptString($user->id)]) }}" wire:navigate><i class="las la-pen text-secondary font-16 text-info"></i></a>
                                                    <a href="javascript:;" onclick="confirmDeletion({{ $user->id }})"><i class="las la-trash-alt text-secondary font-16 text-danger"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                </table>           
                {{ $users->links() }}         
                            </div>                                         
                        </div><!--end card-body--> 
                    </div><!--end card--> 
                </div> <!--end col-->                               
            </div><!--end row--> 

 

           


        </div><!-- container -->

       <!--Start Footer-->
       <livewire:layout.footer />    
   
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
 