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
                                        <select class="form-select" wire:model.defer="status_id" required>
                                            <option value="">Select Status</option>
                                            @foreach($leadStatus as $statusId => $statusName)
                                                <option value="{{ $statusId }}">{{ $statusName }}</option>
                                            @endforeach
                                        </select>
                                        @error('status_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Source <span class="text-danger">*</span></label>
                                        <select class="form-select" wire:model.defer="source_id" required>
                                            <option value="">Select Source</option>
                                            @foreach($leadSources as $sourceId => $sourceName)
                                                <option value="{{ $sourceId }}">{{ $sourceName }}</option>
                                            @endforeach
                                        </select>
                                        @error('source_id') <span class="text-danger small">{{ $message }}</span> @enderror
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
                                    <div class="col-md-6">
                                        <label class="form-label">Priority</label>
                                        <select class="form-select" wire:model.defer="priority_id">
                                            <option value="">Select Priority</option>
                                            @foreach($leadPriorities as $priorityId => $priorityName)
                                                <option value="{{ $priorityId }}">{{ $priorityName }}</option>
                                            @endforeach
                                        </select>
                                        @error('priority_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Sector</label>
                                        <select class="form-select" wire:model.defer="sector_id">
                                            <option value="">Select Sector</option>
                                            @foreach($sectors as $sectorId => $sectorName)
                                                <option value="{{ $sectorId }}">{{ $sectorName }}</option>
                                            @endforeach
                                        </select>
                                        @error('sector_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" wire:model.defer="address" rows="3"></textarea>
                                        @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Position</label>
                                        <input type="text" class="form-control" wire:model.defer="position">
                                        @error('position') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Budget</label>
                                        <input type="text" class="form-control" wire:model.defer="budget">
                                        @error('budget') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Follow-Up Date</label>
                                        <input type="date" class="form-control" wire:model.defer="next_followup_date">
                                        @error('next_followup_date') <span class="text-danger small">{{ $message }}</span> @enderror
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

