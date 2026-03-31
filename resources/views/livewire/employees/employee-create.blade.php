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
                               <li class="breadcrumb-item"> <a wire:navigate href="{{ route('dashboard')}}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('employee')}}">Employee</a></li>
                                <li class="breadcrumb-item active">Create New</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Add a New Employee</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <form wire:submit.prevent="addEmployee" id="form-validation-2" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 class="card-title text-light">Enter Employee Details Below</h4>
                            <p class="text-muted mb-0 text-light">Provide the necessary details to onboard a new team member seamlessly.
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="employeeCodeInput">Employee Code<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" id="employeeCodeInput"  wire:model="emp_code" placeholder="">
                                                @error('emp_code')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="typeInput">Type<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="emp_type" id="inlineFormSelectPref">
                                                    <option selected>Choose...</option>
                                                     @foreach ($emptypes as $etid => $etname)
                                                        <option value="{{$etid }}">{{$etname }}</option>
                                                     @endforeach
                                                </select>
                                                @error('emp_type')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                           <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="institute">Institute<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="emp_institute" id="inlineFormSelectPref">
                                                    <option selected>Choose...</option>
                                                    @foreach ($institute as $instid => $instname)
                                                        <option value="{{$instid }}">{{$instname }}</option>
                                                     @endforeach
                                                </select>
                                                @error('emp_institute')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>    
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="appellationInput">Appellation<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="emp_appellation">
                                                    <option value="" selected>Choose...</option>
                                                    @foreach ($appellation as $applid => $applname)
                                                        <option value="{{$applid }}">{{$applname }}</option>
                                                     @endforeach
                                                </select>
                                                @error('emp_appellation')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="nameInput">Name<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" id="nameInput" wire:model="emp_first_name" placeholder="">
                                                @error('emp_first_name')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="institute">Designation<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="emp_designation" id="inlineFormSelectPref">
                                                    <option selected>Choose...</option>
                                                    @foreach ($designations as $desigid => $designame)
                                                        <option value="{{$desigid }}">{{$designame }}</option>
                                                     @endforeach
                                                </select>
                                                @error('emp_designation')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="dob">Date of Birth<span style="color: red;">*</span></label>
                                                <input class="form-control" type="date" wire:model="emp_dob" value="" id="dob" placeholder="Enter date of birth">
                                                @error('emp_dob')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="sex">Sex<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="emp_sex" id="sex">
                                                        <option value="">Select sex</option>
                                                    @foreach ($gender as $gndrid => $gndrname)
                                                        <option value="{{$gndrid }}">{{$gndrname }}</option>
                                                     @endforeach
                                                 </select>
                                                 @error('emp_sex')
                                                 <small class="error">{{ $message }}</small>
                                                 @enderror
                                             </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="doj">Date of Joining<span style="color: red;">*</span></label>
                                                <input type="date" class="form-control" wire:model="emp_date_of_joining" id="doj" placeholder="">
                                                @error('emp_date_of_joining')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="aadharCard">Aadhar No.</label>
                                                <input type="text" class="form-control" wire:model="emp_aadhar" id="aadharCard" aria-describedby="aadharHelp" placeholder="Enter Aadhar Card">
                                                @error('emp_aadhar')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="aadharFile">Aadhar Card</label>
                                                <input type="file" wire:model="aadhar_card" class="form-control" id="aadharFile">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="panNumber">PAN No.</label>
                                                <input type="text" class="form-control" wire:model="emp_pan" id="panNumber" aria-describedby="engagementHelp" placeholder="Enter PAN Number">
                                                @error('emp_pan')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="panFile">PAN Card</label>
                                                <input type="file" wire:model="emp_pancard" class="form-control" id="panFile">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="engagementLetter">Engagement Letter</label>
                                                <input type="file" wire:model="eng_letter" class="form-control" id="panFile">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="appointedOrganisation">Appointed Organisation<span style="color: red;">*</span></label>
                                                <select class="form-select" wire:model="emp_appointed_organisation" id="inlineFormSelectPref">
                                                        <option selected>Choose...</option>
                                                        @foreach ($appointedorganization as $orgid => $orgname)
                                                            <option value="{{$orgid }}">{{$orgname }}</option>
                                                        @endforeach
                                                </select>
                                                @error('emp_appointed_organisation')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="contactNo">Contact No.<span style="color: red;">*</span></label>
                                                <input type="text" wire:model="emp_contact_no" class="form-control" id="contactNo" aria-describedby="contactHelp" placeholder="">
                                                @error('emp_contact_no')
                                                <small class="error">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="emergencyContactNo">Emergency Contact No.</label>
                                                <input type="text" class="form-control" wire:model="emp_emergency_contact_no" id="emergencyContactNo" aria-describedby="emergencyHelp" placeholder="Enter Emergency Contact No.">
                                            </div>
                                        </div>
                                    </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label for="udin">Email<span style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" wire:model="emp_email" id="udin" aria-describedby="udinHelp" placeholder="" >
                                                    @error('emp_email')
                                                    <small class="error">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label for="udin">UDIN</label>
                                                    <input type="text" class="form-control" wire:model="emp_udin" id="udin" aria-describedby="udinHelp" placeholder="" >

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label for="address">Address<span style="color: red;">*</span></label>
                                                    <textarea class="form-control" rows="5" wire:model="emp_address" id="address" ></textarea>
                                                    @error('emp_address')
                                                    <small class="error">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->

                <div class="col-lg-3">
                    <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Image</h4>
                            </div>
                            <div class="card-body">
                                <div class="d-grid">
                                    <div wire:loading wire:target="image" wire:key="image"><i class="spinner-border spinner-border-sm mt-2 ml-2"></i> Uploading...</div>
                                    @if ($image)
                                    <div class="preview-box d-block justify-content-center rounded shadow overflow-hidden bg-light p-1">
                                        <img class="rounded d-block" src="{{ $image->temporaryUrl() }}" style="height: 172px;width: 100%;">
                                    </div>
                                    @endif
                                    <input type="file" id="input-file" wire:model="image" accept="image/*"  hidden />
                                    <label class="btn-upload btn btn-outline-secondary mt-4" for="input-file"><i class="fas fa-cloud-upload-alt"></i> Browse Image</label>
                                    @error('image')
                                    <span class="text-danger">{{ $image }}</span>
                                @enderror
                                </div>
                            </div>
                    </div>

                    <div class="card">
                            <div class="card-header  ">
                                <h4 class="card-title ">Login Credentials</h4>
                            </div>
                            <div class="card-body">
                            <div class="mb-3 ">
                                <div class="col-sm-12">
                                    <input
                                        class="form-control"
                                        type="text"
                                        wire:model="password"
                                        id="example-password-input"
                                        placeholder="Enter Password Here"
                                    >

                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <span class="badge badge-outline-success mt-2 float-end" style="cursor:pointer"  wire:click="generatePassword">Generate Password</span>

                            </div>

                            </div>
                    </div>

                    <div class="card">
                            <div class="card-header  ">
                                <h4 class="card-title ">Publish</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 float-end">
                                    <button type="button" class="btn btn-de-danger">Cancel</button>
                                    <button type="submit" class="btn btn-primary ">
                                        <span wire:loading wire:target="addEmployee">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                        </span>
                                        <span wire:loading.remove wire:target="addEmployee">
                                            Save & Publish
                                        </span>
                                    </button>
                                </div>
                            </div>
                    </div>
                </div><!--end row-->
            </div><!-- container -->
            </form>
        <!--Start Footer-->
        <livewire:layout.footer />
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>
</div>
