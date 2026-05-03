<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title and Breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('leads') }}">Leads</a>
                                </li>
                                <li class="breadcrumb-item active">Lead Details</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Lead Details</h4>
                    </div>
                </div>
            </div>
            <!-- Lead Pipeline Board -->

            <!-- Main Card -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white  align-items-center justify-content-between">
                            <span class="h5 mb-0 text-white">Lead Information</span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Name:</strong> {{ $lead->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Company:</strong> {{ $lead->company }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Email:</strong> {{ $lead->email }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Phone:</strong> {{ $lead->phone }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Deal Value:</strong> ₹{{ number_format($lead->deal_value) }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($lead->status) }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Source:</strong> {{ $lead->source }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Assigned To:</strong> {{ optional($lead->assignedUser)->name }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong>Notes:</strong>
                                <div class="text-muted">{{ $lead->notes }}</div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-warning btn-sm" wire:click="sendProposal" type="button">
                                    <i class="fas fa-file-signature"></i> Create Proposal
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Lead History -->
                    {{-- <div class="card mt-4">
                        <div class="card-header bg-secondary text-white">
                            <span class="h6 mb-0">Lead History</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Lead</th>
                                            <th>Action</th>
                                            <th>Performed By</th>
                                            <th>Notes</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leadLogs as $log)
                                            <tr>
                                                <td>{{ $log->lead->name }}</td>
                                                <td>{{ ucfirst($log->action) }}</td>
                                                <td>{{ optional($log->user)->name ?? 'N/A' }}</td>
                                                <td>{{ $log->notes }}</td>
                                                <td>{{ formatDate($log->action_time,'d M Y h:i A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}
                    <!-- CRM Widgets -->


                    @if($showProposalEditor)
                    @livewire('proposals.proposal-editor', ['leadId' => $lead->id], key('proposal-'.$lead->id))
                    @endif

                    @php
                        $existingProposal = \App\Models\Proposal::where('lead_id', $lead->id)->latest()->first();
                    @endphp
                    @if($existingProposal)
                        @include('livewire.proposals.proposal-preview', ['proposal' => $existingProposal])
                    @endif

                </div>
                <!-- Sidebar: Followup & Actions -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <span class="h6 mb-0 text-white">Followup Details</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Next Followup Date:</strong>
                                <span class="badge bg-light text-primary">{{ formatDate($lead->next_followup_date) }}</span>
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach ($followups as $followup)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold text-primary">{{ $followup->leadStatus->name }}</span>
                                            <span class="text-muted small">{{ $followup->created_at->format('jS M, Y') }}</span>
                                        </div>
                                        <div class="text-muted">{{ $followup->notes }}</div>
                                        <div class="small">Followup By <span class="text-primary">{{ $followup->followupdBy->name }}</span></div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="javascript:;" wire:click="followupDetailsPopup()" class="btn btn-outline-info btn-sm mt-3">Change Followup Status</a>
                        </div>
                    </div>
                     <div class="col-md-12 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">Attachments</div>
                                <div class="card-body p-2">
                                    <livewire:leads.lead-attachment-manager :leadId="$lead->id" />
                                    {{-- @livewire('leads.lead-attachment-manager', ['leadId' => $lead->id], key('attach-'.$lead->id)) --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-warning text-white">Tags</div>
                                <div class="card-body p-2">
                                    @livewire('leads.lead-tag-manager', ['leadId' => $lead->id], key('tag-'.$lead->id))
                                </div>
                            </div>
                        </div>
                             <div class="col-md-12 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-danger text-white">Reminders</div>
                                <div class="card-body p-2">
                                    @livewire('leads.lead-reminder-manager', ['leadId' => $lead->id], key('reminder-'.$lead->id))
                                </div>
                            </div>
                        </div>
                    <div class="card">
                        <div class="card-header bg-light">
                            <span class="h6 mb-0">Lead Details Contact</span>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="InlineCheckbox" wire:change="createNewDealForm($event.target.checked)">
                                <label for="InlineCheckbox" class="form-check-label">Create a New Deal for this Account.</label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="InlineCheckbox11" wire:change="createNewWorkForm($event.target.checked)">
                                <label for="InlineCheckbox11" class="form-check-label">Create New Work</label>
                            </div>
                            @if ($loading)
                                <div class="d-flex align-items-center gap-2 mt-2"><span class="spinner-border text-primary" role="status"></span> Loading...</div>
                            @endif
                            @if ($dealForm)
                                <form wire:submit.prevent="createDeal" class="needs-validation mt-4">
                                    <fieldset class="border rounded-3 p-3 mb-3">
                                        <legend class="font-semibold">Deal Details</legend>
                                        <div class="mb-2">
                                            <label class="form-label">Amount</label>
                                            <input type="text" class="form-control" wire:model="amount" placeholder="Amount ">
                                            @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Deal Name</label>
                                            <input type="text" class="form-control" wire:model="dealName" placeholder="Deal Name ">
                                            @error('dealName') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Closing Date</label>
                                            <input type="date" class="form-control" wire:model="closing_date" placeholder="Closing Date ">
                                            @error('closing_date') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Stage</label>
                                            <select class="form-select" wire:model="status_id">
                                                <option value="">Choose...</option>
                                                @foreach ($dealstatus as $dsid => $dsname)
                                                    <option value="{{ $dsid }}">{{ $dsname }}</option>
                                                @endforeach
                                            </select>
                                            @error('status_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </fieldset>
                                    @if ($workForm)
                                    <fieldset class="border rounded-3 p-3 mb-3">
                                        <legend class="font-semibold">Work Details</legend>
                                        <div class="mb-2">
                                            <label class="form-label">Job Type</label>
                                            <select class="form-select" wire:model="jobtype_id">
                                                <option value="">Choose...</option>
                                                @foreach ($jobtypes as $jobid => $jobname)
                                                    <option value="{{ $jobid }}">{{ $jobname }}</option>
                                                @endforeach
                                            </select>
                                            @error('status_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Work</label>
                                            <select class="form-select" wire:model="work_id">
                                                <option value="">Choose...</option>
                                                @foreach ($worklists as $workid => $workname)
                                                    <option value="{{ $workid }}">{{ $workname }}</option>
                                                @endforeach
                                            </select>
                                            @error('status_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Customer's Deadline</label>
                                            <input type="date" class="form-control" wire:model="customer_deadline" placeholder="Closing Date ">
                                            @error('closing_date') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </fieldset>
                                    @endif
                                    <div class="d-flex gap-2 mt-3 justify-content-end">
                                        <button type="button" class="btn btn-secondary">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Convert</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                      <div class="row mt-4">
                        <div class="col-md-12 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">Activity Timeline</div>
                                <div class="card-body p-2">
                                    @livewire('leads.lead-activity-timeline', ['leadId' => $lead->id], key('activity-'.$lead->id))
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@if ($showModal)
<div class="modal show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
    style="background: rgba(0, 0, 0, .6);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="exampleModalDefaultLabel">
                    {{ $modalMode === 'edit' ? 'Edit Lead' : 'Follow-up Details' }}
                </h6>
                <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div><!--end modal-header-->
            @if ($modalMode === 'edit')
            <form wire:submit.prevent="update" class="needs-validation">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="doj">Status<span style="color: red;">*</span></label>
                                <select class="form-select" wire:model="status_id" id="leadStatus">
                                    <option value="">Select Status</option>
                                    @foreach ($leadStatus as $statusid => $statusname)
                                    <option value="{{ $statusid }}">{{ $statusname }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div><!-- end col -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="doj">Follow-Up Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control"
                                    wire:model="next_followup_date" id="doj" placeholder="">
                                @error('next_followup_date')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="doj">Notes</label>
                                <textarea class="form-control" wire:model="notes" type="number" placeholder="" autocomplete="off"></textarea>
                                @error('notes')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div><!-- end modal-body -->

                <div class="modal-footer">
                    <button type="button" wire:click="closeModal"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <span wire:loading wire:target="update">
                            <span class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span> Loading...
                        </span>
                        <span wire:loading.remove wire:target="update">
                            Save changes
                        </span>
                    </button>
                </div><!-- end modal-footer -->
            </form>
            @else
            <form wire:submit.prevent="createFollowup" class="needs-validation">
                <input type="hidden" wire:model="leadId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="doj">Status<span style="color: red;">*</span></label>
                                <select class="form-select" wire:model="status_id" id="leadStatus">
                                    <option value="">Select Status</option>
                                    @foreach ($leadStatus as $statusid => $statusname)
                                    <option value="{{ $statusid }}">{{ $statusname }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div><!-- end col -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="doj">Next Follow-Up Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control"
                                    wire:model="next_followup_date" id="doj" placeholder="">
                                @error('next_followup_date')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="doj">Notes<span
                                        style="color: red;">*</span></label></label>
                                <textarea class="form-control" wire:model="notes" type="number" placeholder="" autocomplete="off" rows="5"></textarea>
                                @error('notes')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div><!-- end modal-body -->

                <div class="modal-footer">
                    <button type="button" wire:click="closeModal"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <span wire:loading wire:target="createFollowup">
                            <span class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span> Loading...
                        </span>
                        <span wire:loading.remove wire:target="createFollowup">
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
</div>

