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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}" wire:navigate>Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('users')}}" wire:navigate>Users</a></li>
                                <li class="breadcrumb-item active">Edit User</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit User </h4>
                    </div>
                </div>
            </div>
           
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            Edit User
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <form wire:submit.prevent="updateUser">
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-sm-2 col-form-label text-end">Name</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" wire:model="data.name" type="text" id="example-text-input">
                                                @error('data.name') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
        
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-email-input" class="col-sm-2 col-form-label text-end">Email</label>
                                            <div class="col-sm-10">
                                                <input class="form-control"  wire:model="data.email" type="email"  id="example-email-input">
                                                @error('data.email') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
                                            </div>
                                        </div> 
                           
                                        <div class="mb-3 row">
                                            <label for="example-password-input" class="col-sm-2 col-form-label text-end">Password</label>
                                            <div class="col-sm-10 position-relative" x-data="{ showPassword: false }">
                                                <input 
                                                    class="form-control" 
                                                    :type="showPassword ? 'text' : 'password'" 
                                                    wire:model="password" 
                                                    id="example-password-input"
                                                >
                                                <span 
                                                    class="position-absolute end-0 top-50 translate-middle-y me-2 cursor-pointer" 
                                                    @click="showPassword = !showPassword"
                                                >
                                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" style="margin-right: 9px;color: #0b51b7;"></i>
                                                </span>
                                                @error('password') 
                                                    <span class="text-danger">{{ $message }}</span> 
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3 row" wire:ignore>
                                            <label for="example-email-input" class="col-sm-2 col-form-label text-end">Role</label>
                                            <div class="col-sm-10" x-data x-init="$nextTick(() => { $('.js-example-basic-multiple').select2(); })">
                                              <div wire:ignore>
                                                <select id="rol" class="form-select " multiple="multiple" wire:model="selectedRoles">
                                                    @foreach ($roles as $role)
                                                    <option value="{{ $role }}" >{{ $role }}</option>
                                                    @endforeach
                                                </select> 
                                              </div>
                                                @error('selectedRoles') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror

                                            </div>
                                        </div> 
                                        <div class="mb-3 float-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm">Cancel</button>
                                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                                <span wire:loading wire:target="updateUser">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                                </span>
                                                <span wire:loading.remove wire:target="updateUser">
                                                    Save changes
                                                </span>
                                            </button>
                                        </div> 
                                   </form>
                                </div>


                                <div class="col-lg-4">                                       
                                    
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->                               
                </div> <!-- end col -->                    
            </div> <!-- end row -->
        </div>
        <!-- Footer Start -->
        <livewire:layout.footer />  
        <!-- end Footer -->                
      
    </div>
    <!-- end page content -->
</div>

@script()
<script>
$(document).ready(function() {
    $('#rol').select2();
    $('#rol').on('change', function(e) 
        {
            let data = $(this).val();
            console.log(data)
            $wire.set('selectedRoles' ,data)
            $wire.selectedRoles = data;
        });
});




</script>
@endscript