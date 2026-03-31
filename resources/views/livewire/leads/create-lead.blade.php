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
                                <li class="breadcrumb-item active">Create New</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Create New User</h4>
                    </div>
                </div>
            </div>
           
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            Create User
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="addUser" enctype="multipart/form-data">
                            <div class="row">
                                
                                <div class="col-lg-9">
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-sm-2 col-form-label text-end">Name</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" wire:model="name" type="text" value="" id="example-text-input">
                                                @error('name') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
        
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-email-input" class="col-sm-2 col-form-label text-end">Email</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" wire:model="email" type="email" value="" id="example-email-input">
                                                @error('email') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
                                            </div>
                                        </div> 
                                        <div class="mb-3 row">
                                            <label for="example-phone-input" class="col-sm-2 col-form-label text-end">phone</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" type="phone" wire:model="phone" value="" id="example-phone-input">
                                                @error('phone') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror

                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-phone-input" class="col-sm-2 col-form-label text-end">Source</label>
                                            <div class="col-sm-10">
                                                <select id="source" name="source" class="w-full p-2 border border-gray-300 rounded" required="">
                                                    <option value="website">Website</option>
                                                    <option value="social_media">Social Media</option>
                                                    <option value="referrals">Referrals</option>
                                                    <option value="email_campaigns">Email Campaigns</option>
                                                    <option value="events">Events</option>
                                                    <option value="others">Others</option>
                                                </select>
                                                @error('phone') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror

                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-phone-input" class="col-sm-2 col-form-label text-end">Status</label>
                                            <div class="col-sm-10">
                                                <select id="source" name="source" class="w-full p-2 border border-gray-300 rounded" required="">
                                                    <option value="new">New</option>
                                                    <option value="converted">Converted</option>
                                                    <option value="pending">Pending</option>
                                                </select>
                                                @error('phone') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror

                                            </div>
                                        </div>
                                        <div class="mb-3 row" >
                                            <label for="example-email-input" class="col-sm-2 col-form-label text-end">Notes</label>
                                            <div class="col-sm-10" wire:ignore>
                                               <textarea name="" id=""  class="form-control"    ></textarea>
                                                @error('selectedRoles') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
                                            </div>
                                        </div> 
                                        
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-sm-2 col-form-label text-end">Address</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" wire:model="name" type="text" value="" id="example-text-input">
                                                @error('name') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
        
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-sm-2 col-form-label text-end">Company</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" wire:model="name" type="text" value="" id="example-text-input">
                                                @error('name') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
        
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-sm-2 col-form-label text-end">Position</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" wire:model="name" type="text" value="" id="example-text-input">
                                                @error('name') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
        
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-sm-2 col-form-label text-end">Budget</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" wire:model="name" type="text" value="" id="example-text-input">
                                                @error('name') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror
        
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label for="example-phone-input" class="col-sm-2 col-form-label text-end">Priority</label>
                                            <div class="col-sm-10">
                                                <select id="source" name="source" class="w-full p-2 border border-gray-300 rounded" required="">
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                </select>
                                                @error('phone') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror

                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-phone-input" class="col-sm-2 col-form-label text-end">Assigned To
                                            </label>
                                            <div class="col-sm-10">
                                                <select id="source" name="source" class="w-full p-2 border border-gray-300 rounded" required="">
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                </select>
                                                @error('phone') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror

                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-phone-input" class="col-sm-2 col-form-label text-end">Follow-Up Date

                                            </label>
                                            <div class="col-sm-10">
                                                <input type="date" name="" class="form-control" id="">
                                                @error('phone') 
                                                <span class="text-danger">{{ $message }}</span> 
                                               @enderror

                                            </div>
                                        </div>
                                </div>

                                <div class="col-lg-3">                                       
                                
                                   <div class="card">
                                    <div class="card-header  ">
                                        <h4 class="card-title ">Publish</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3 float-end">
                                          
                                            <button type="button" class="btn btn-de-danger">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <span wire:loading wire:target="addUser">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                                </span>
                                                <span wire:loading.remove wire:target="addUser">
                                                    Save & Publish
                                                </span>
                                            </button>
                                        </div> 
                                    </div>
                                </div>
            
                                  
                                </div>
                            
                            </div>
                        </form>
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

