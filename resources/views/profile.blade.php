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
                                <li class="breadcrumb-item"><a href="#">Metrica</a></li>
                                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Profile</h4>
                    </div>
                    <!--end page-title-box-->
                </div>

            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="met-profile">
                                <div class="row">
                                    <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                        <div class="met-profile-main">

                                            <!-- <div class="met-profile-main-pic">
                                                <img src="{{ isset($image) ? $image->temporaryUrl() : empProfilePicture(auth()->id()) }}"
                                                    alt="Profile Picture" height="110" class="rounded-circle">

                                                <span class="met-profile_main-pic-change" style="cursor:pointer;">
                                                    <label for="input-file">
                                                        <input type="file" id="input-file" wire:model="image" accept="image/*" hidden />
                                                        <i class="fas fa-camera" style="cursor:pointer;"></i>
                                                    </label>
                                                </span>
                                            </div> -->
                                            <div x-data="{ preview: @entangle('image').defer }" class="met-profile-main-pic relative">
                                                <!-- Profile Image with Blur Effect During Upload -->
                                                <img :class="{ 'blur-md': preview }" {{-- Blur effect while uploading --}}
                                                    src="{{ isset($image) ? $image->temporaryUrl() : empProfilePicture(auth()->id()) }}"
                                                    alt="Profile Picture" height="110"
                                                    class="rounded-circle transition-all duration-300 ease-in-out">

                                                <!-- Loading Spinner (Livewire) -->
                                                <div wire:loading wire:target="image"
                                                    class="position-absolute top-0 start-0 w-100 h-100 align-items-center justify-content-center bg-dark bg-opacity-50 rounded-circle">
                                                    <!-- <div class="spinner-border text-light position-absolute top-20 start-15" role="status">
                                                        <span class="visually-hidden">Uploading...</span>
                                                    </div> -->
                                                </div>
                                                <!-- File Upload Button -->
                                                <span class="met-profile_main-pic-change cursor-pointer">
                                                    <label for="input-file">
                                                        <input type="file" id="input-file" wire:model="image"
                                                            accept="image/*" hidden @change="preview = true">
                                                        <i class="fas fa-camera cursor-wait"></i>
                                                    </label>
                                                </span>
                                            </div>


                                            <div class="met-profile_user-detail">
                                                <h5 class="met-user-name">{{ $name }}
                                                    @unless (Auth::user()->hasRole('Super Admin'))
                                                    <p class="mb-0 met-user-name-post">
                                                        {{ $emp_code }}
                                                    </p>
                                                    @endunless
                                                    @unless (Auth::user()->hasRole('Super Admin'))
                                                    <p class="mb-0 met-user-name-post">
                                                        {{$employee->designation->name}}
                                                    </p>
                                                    @endunless
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 ms-auto align-self-center">
                                        <ul class="list-unstyled personal-detail mb-0">
                                            @unless (Auth::user()->hasRole('Super Admin'))
                                            <li class=""><i
                                                    class="las la-phone mr-2 text-secondary font-22 align-middle"></i>
                                                <b> phone </b> :
                                            </li>
                                            @endunless
                                            <li class="mt-2"><i
                                                    class="las la-envelope text-secondary font-22 align-middle mr-2"></i>
                                                <b> Email </b> : {{ $email }}
                                            </li>
                                            {{-- <li class="mt-2"><i
                                                    class="las la-globe text-secondary font-22 align-middle mr-2"></i>
                                                <b> Website </b> :
                                                <a href="https://mannatthemes.com/"
                                                    class="font-14 text-primary">https://mannatthemes.com/</a>
                                            </li> --}}
                                        </ul>

                                    </div>
                                    <div class="col-lg-4 align-self-center">
                                        <div class="row">
                                            <div class="col-auto text-end border-end">
                                                <button type="button"
                                                    class="btn btn-soft-primary btn-icon-circle btn-icon-circle-sm mb-2">
                                                    <i class="fab fa-facebook-f"></i>
                                                </button>
                                                <p class="mb-0 fw-semibold">Facebook</p>
                                                <h4 class="m-0 fw-bold">25k <span
                                                        class="text-muted font-12 fw-normal">Followers</span></h4>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button"
                                                    class="btn btn-soft-info btn-icon-circle btn-icon-circle-sm mb-2">
                                                    <i class="fab fa-twitter"></i>
                                                </button>
                                                <p class="mb-0 fw-semibold">Twitter</p>
                                                <h4 class="m-0 fw-bold">58k <span
                                                        class="text-muted font-12 fw-normal">Followers</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end f_profile-->
                        </div><!--end card-body-->




                        <div class="card-body p-0" wire:ignore>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#Settings" role="tab"
                                        aria-selected="false">Personal Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#contactDetails" role="tab"
                                        aria-selected="false">Contact Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab"
                                        aria-selected="false">Change Password</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content" wire:ignore>
                                <div class="tab-pane p-3 active" id="Settings" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-12 col-xl-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h4 class="card-title">Personal Information</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <form action="" wire:submit.prevent="updatePersonalInfo">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-xl-6">
                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Employee Code</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <input class="form-control" wire:model="emp_code"
                                                                            type="text" value="">
                                                                        @error('emp_code')
                                                                        <small class="error">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">First
                                                                        Name</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <input class="form-control" wire:model="emp_first_name"
                                                                            type="text" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Appellation</label>
                                                                    <div class="col-lg-9 col-xl-8">
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
                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Designation</label>
                                                                    <div class="col-lg-9 col-xl-8">
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

                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Date of Joining</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i
                                                                                    class="far fa-calendar-alt"></i></span>
                                                                            <input type="date" class="form-control"
                                                                                wire:model="emp_date_of_joining"
                                                                                placeholder="Phone"
                                                                                aria-describedby="basic-addon1">
                                                                            @error('emp_date_of_joining')
                                                                            <small class="error">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="col-lg-6 col-xl-6">
                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Type</label>
                                                                    <div class="col-lg-9 col-xl-8">
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



                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Last
                                                                        Name</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <input class="form-control" wire:model="emp_last_name"
                                                                            type="text" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Gender</label>
                                                                    <div class="col-lg-9 col-xl-8">
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

                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Date of Birth</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i
                                                                                    class="far fa-calendar-alt"></i></span>
                                                                            <input type="date" class="form-control"
                                                                                wire:model="emp_dob"
                                                                                placeholder="Date of Birth"
                                                                                aria-describedby="basic-addon1">
                                                                            @error('emp_dob')
                                                                            <small class="error">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="col-lg-12 ">
                                                                <button type="submit" class="btn btn-primary  float-end">
                                                                    <span wire:loading wire:target="updatePersonalInfo">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                                                    </span>
                                                                    <span wire:loading.remove wire:target="updatePersonalInfo">
                                                                        Save Changes
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-3" id="contactDetails" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-12 col-xl-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h4 class="card-title">Contact Information</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <form action="" wire:submit.prevent="updateContactInfo">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-xl-6">
                                                                <div class="form-group mb-3 row">
                                                                    <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Contact Phone</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i class="las la-phone"></i></span>
                                                                            <input type="text" class="form-control" wire:model="emp_contact_no" placeholder="Phone" aria-describedby="basic-addon1">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mb-3 row">
                                                                    <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Email Address</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i class="las la-at"></i></span>
                                                                            <input type="text" wire:model="emp_email" class="form-control" value="" placeholder="Email" aria-describedby="basic-addon1">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-xl-6">
                                                                <div class="form-group mb-3 row">
                                                                    <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Emergency Contact No.</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i class="las la-phone"></i></span>
                                                                            <input type="text" class="form-control" wire:model="emp_emergency_contact_no" placeholder="Phone" aria-describedby="basic-addon1">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mb-3 row">
                                                                    <label
                                                                        class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Address</label>
                                                                    <div class="col-lg-9 col-xl-8">
                                                                        <textarea rows="5" class="form-control" wire:model="emp_address"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 ">
                                                                <button type="submit" class="btn btn-primary  float-end">
                                                                    <span wire:loading wire:target="updateContactInfo">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                                                    </span>
                                                                    <span wire:loading.remove wire:target="updateContactInfo">
                                                                        Save Changes
                                                                    </span>
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-3" id="changePassword" role="tabpanel">
                                    <div class="row">

                                        <div class="col-lg-6 col-xl-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Change Password</h4>
                                                </div><!--end card-header-->
                                                <div class="card-body">
                                                    <livewire:profile.update-password-form />
                                                </div><!--end card-body-->
                                            </div><!--end card-->
                                            <!-- <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Other Settings</h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="" id="Email_Notifications" checked>
                                                        <label class="form-check-label" for="Email_Notifications">
                                                            Email Notifications
                                                        </label>
                                                        <span class="form-text text-muted font-12 mt-0">Do you need
                                                            them?</span>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="" id="API_Access">
                                                        <label class="form-check-label" for="API_Access">
                                                            API Access
                                                        </label>
                                                        <span class="form-text text-muted font-12 mt-0">Enable/Disable
                                                            access</span>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!--end card-body-->
                    </div><!--end card-->
                </div>
            </div>

        </div><!-- container -->
    </div>
    <!-- end page content -->
</div>