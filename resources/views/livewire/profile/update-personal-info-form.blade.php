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