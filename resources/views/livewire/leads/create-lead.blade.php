<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" wire:navigate>Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('leads') }}" wire:navigate>Leads</a></li>
                                <li class="breadcrumb-item active">Create Lead</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Create New Lead</h4>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <span class="h5 mb-0">Lead Information</span>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="createLead" autocomplete="off">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" wire:model.defer="name" required>
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" wire:model.defer="email">
                                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" wire:model.defer="phone" required>
                                        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Company</label>
                                        <input type="text" class="form-control" wire:model.defer="company">
                                        @error('company') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Deal Value</label>
                                        <input type="number" class="form-control" wire:model.defer="deal_value" min="0">
                                        @error('deal_value') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" wire:model.defer="status" required>
                                            <option value="">Select Status</option>
                                            <option value="new">New Lead</option>
                                            <option value="contacted">Contacted</option>
                                            <option value="qualified">Qualified</option>
                                            <option value="proposal_sent">Proposal/Quotation Sent</option>
                                            <option value="negotiation">Negotiation</option>
                                            <option value="won">Won</option>
                                            <option value="lost">Lost</option>
                                        </select>
                                        @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Source</label>
                                        <input type="text" class="form-control" wire:model.defer="source">
                                        @error('source') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Assigned To</label>
                                        <select class="form-select" wire:model.defer="assigned_to">
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" wire:model.defer="notes" rows="3"></textarea>
                                        @error('notes') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">Save Lead</button>
                                    <button type="button" class="btn btn-outline-secondary" wire:click="resetForm">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

