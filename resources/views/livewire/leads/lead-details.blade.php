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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" wire:navigate>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('leads') }}" wire:navigate>Leads</a></li>
                                <li class="breadcrumb-item active">Lead Details</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Lead Details</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header  ">
                                            <h4 class="card-title ">Customer Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class=" row">
                                                <label for="example-text-input"
                                                    class="col-sm-3 col-form-label text-end">Name:</label>
                                                <div class="col-sm-9 mt-2">
                                                    {{ $lead->name }}
                                                </div>
                                            </div>
                                            <div class=" row">
                                                <label for="example-email-input"
                                                    class="col-sm-3 col-form-label text-end">Email:</label>
                                                <div class="col-sm-9 mt-2">
                                                    {{ $lead->email }}
                                                </div>
                                            </div>
                                            <div class=" row">
                                                <label for="example-phone-input"
                                                    class="col-sm-3 col-form-label text-end">Phone:</label>
                                                <div class="col-sm-9 mt-2">
                                                    {{ $lead->phone }}
                                                </div>
                                            </div>
                                            <div class=" row">
                                                <label for="example-phone-input"
                                                    class="col-sm-3 col-form-label text-end">Source:</label>
                                                <div class="col-sm-9 mt-2">
                                                    {!! getstatusss(optional($lead->leadSource)->name) !!}
                                                </div>
                                            </div>
                                            <div class=" row">
                                                <label for="example-phone-input"
                                                    class="col-sm-3 col-form-label text-end">Status:</label>
                                                <div class="col-sm-9 mt-2">
                                                    {!! getstatusss(optional($lead->leadStatus)->name) !!}
                                                </div>
                                            </div>
                                            {{-- <div class=" row">
                                            <label for="example-phone-input" class="col-sm-3 col-form-label text-end">Follow-Up Date:
                                            </label>
                                            <div class="col-sm-9 mt-2">
                                                <span class="btn btn-outline-primary btn-square btn-skew">{{ $lead->next_followup_date}}</span>
                                        </div>
                                    </div> --}}

                                    <div class=" row">
                                        <label for="example-text-input"
                                            class="col-sm-3 col-form-label text-end">Address:</label>
                                        <div class="col-sm-9 mt-2">
                                            {{ $lead->address }}
                                        </div>
                                    </div>
                                    {{-- <div class=" row" >
                                            <label for="example-email-input" class="col-sm-3 col-form-label text-end">Notes:</label>
                                            <div class="col-sm-9 mt-2">
                                                {{ $lead->notes}}
                                </div>
                            </div> --}}
                            <div class=" row">
                                <label for="example-text-input"
                                    class="col-sm-3 col-form-label text-end">Company:</label>
                                <div class="col-sm-9 mt-2">
                                    {{ $lead->company }}
                                </div>
                            </div>
                            <div class=" row">
                                <label for="example-text-input"
                                    class="col-sm-3 col-form-label text-end">Position:</label>
                                <div class="col-sm-9 mt-2">
                                    {{ $lead->position }}
                                </div>
                            </div>
                            <div class=" row">
                                <label for="example-text-input"
                                    class="col-sm-3 col-form-label text-end">Budget:</label>
                                <div class="col-sm-9 mt-2">
                                    {{ $lead->budget }}
                                </div>
                            </div>
                            <div class=" row">
                                <label for="example-phone-input"
                                    class="col-sm-3 col-form-label text-end">Priority:</label>
                                <div class="col-sm-9 mt-2">
                                    {{ optional($lead->leadPriority)->name }}
                                </div>
                            </div>
                            {{-- <div class=" row">
                                            <label for="example-phone-input" class="col-sm-2 col-form-label text-end">Assigned To
                                            </label>
                                            <div class="col-sm-10">
                                               {{ $lead->user->name}}
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="card">
                <div class="card-header  ">
                    <h4 class="card-title ">Lead History</h4>
                </div>
                <div class="card-body">
                    <table class="table mb-0">
                        <thead class="thead-light">
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
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title "> Followup Details
                        <span
                            class="btn btn-primary btn-square btn-skew float-end">{{ formatDate($lead->next_followup_date) }}</span><span
                            class="float-end">Next Followup Date</span>
                    </h4>
                </div><!--end card-header-->
                <div class="card-body">
                    <div class="">
                        <div class="activity">
                            @foreach ($followups as $followup)
                            <div class="activity-info">
                                <div class="icon-info-activity">
                                    <i class="las la-check-circle bg-soft-primary"></i>
                                </div>
                                <div class="activity-info-text">
                                    <div
                                        class="d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 w-75">
                                            {{ $followup->leadStatus->name }}
                                        </h6>
                                        <span
                                            class="text-muted d-block">{{ $followup->created_at->format('jS M, Y') }}</span>
                                    </div>
                                    <p class="text-muted mt-3">{{ $followup->notes }}
                                        <br />
                                        Followup By <a href="#"
                                            class="text-info">{{ $followup->followupdBy->name }}</a>
                                    </p>
                                </div>
                            </div>
                            @endforeach
                            <a href="javascript:;" wire:click="followupDetailsPopup()"
                                class="btn btn-outline-info float-end mt-3">Change Followup
                                Status</a>
                        </div><!--end activity-->
                    </div><!--end activity-scroll-->
                </div> <!--end card-body-->
            </div><!--end card-->

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title "> Lead Details Contact <span
                            class="btn btn-outline-primary btn-square btn-skew">{{ $lead->name }}</span>
                        <h4>
                </div>
                <div class="card-body">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="InlineCheckbox"
                            wire:change="createNewDealForm($event.target.checked)">
                        <label class="custom-control-label" for="InlineCheckbox"> Create a New
                            Deal for this Account.</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input"
                            id="InlineCheckbox11"
                            wire:change="createNewWorkForm($event.target.checked)">
                        <label class="custom-control-label" for="InlineCheckbox11"> Create New
                            Work</label>
                    </div>

                    @if ($loading)
                    <div class="spinner-border text-primary" role="status"></div>
                    @endif
                    @if ($dealForm)
                    <form wire:submit.prevent="createDeal" class="needs-validation">
                        <fieldset class="border rounded-3">
                            <legend>Deal Details</legend>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="employeeCodeInput">Amount</label>
                                        <input type="text" class="form-control"
                                            id="" wire:model="amount"
                                            placeholder="Amount ">
                                        @error('amount')
                                        <span
                                            class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="employeeCodeInput">Deal Name</label>
                                        <input type="text" class="form-control"
                                            id="" wire:model="dealName"
                                            placeholder="Deal Name ">
                                        @error('dealName')
                                        <span
                                            class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="employeeCodeInput">Closing Date
                                        </label>
                                        <input type="date" class="form-control"
                                            id="" wire:model="closing_date"
                                            placeholder="Closing Date ">
                                        @error('closing_date')
                                        <span
                                            class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="employeeCodeInput">Stage </label>
                                        <select class="form-select" wire:model="status_id"
                                            id="inlineFormSelectPref">
                                            <option value="">Choose...</option>
                                            @foreach ($dealstatus as $dsid => $dsname)
                                            <option value="{{ $dsid }}">
                                                {{ $dsname }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                        <span
                                            class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        @if ($workForm)
                        <fieldset class="border rounded-3 mt-4">
                            <legend>Work Details</legend>
                            <div class="mb-3">
                                <label for="employeeCodeInput">Job Type </label>
                                <select class="form-select" wire:model="jobtype_id"
                                    id="inlineFormSelectPref">
                                    <option value="">Choose...</option>
                                    @foreach ($jobtypes as $jobid => $jobname)
                                    <option value="{{ $jobid }}">
                                        {{ $jobname }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                <span
                                    class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="employeeCodeInput">Work </label>
                                <select class="form-select" wire:model="work_id"
                                    id="inlineFormSelectPref">
                                    <option value="">Choose...</option>
                                    @foreach ($worklists as $workid => $workname)
                                    <option value="{{ $workid }}">
                                        {{ $workname }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                <span
                                    class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="employeeCodeInput">Customer's Deadline
                                </label>
                                <input type="date" class="form-control"
                                    id="" wire:model="customer_deadline"
                                    placeholder="Closing Date ">
                                @error('closing_date')
                                <span
                                    class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </fieldset>
                        @endif

                        <div class="mb-3 float-end mt-2">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading wire:target="createDeal">
                                    <span class="spinner-border spinner-border-sm"
                                        role="status" aria-hidden="true"></span>
                                    Loading...
                                </span>
                                <span wire:loading.remove wire:target="createDeal">
                                    Convert
                                </span>
                            </button>
                        </div>

                    </form>
                    @endif
                </div>
            </div>


        </div>

    </div>

</div>
</div> <!-- end card -->
</div> <!-- end col -->
</div> <!-- end row -->

</div>
<!-- Footer Start -->
<livewire:layout.footer />

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
<!-- end Footer -->

</div>
<!-- end page content -->
</div>