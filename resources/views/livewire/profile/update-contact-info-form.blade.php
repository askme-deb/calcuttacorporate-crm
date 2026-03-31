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