<div class="page-wrapper">
    <style>
        /* Hide edit & delete icons by default */
        .action-icons {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        /* Show icons only when hovering over the card */
        .deal-card:hover .action-icons {
            opacity: 1;
        }
    </style>
    <!-- Page Content-->
    <div class="page-content-tab">

        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}" wire:navigate>Dashboard</a>
                                </li><!--end nav-item-->

                                <li class="breadcrumb-item active">Deals</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Customers</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-5">

                            </div>
                            <div class="col-lg-7 mt-3 text-end">
                                <div class="text-end">
                                    <ul class="list-inline">
                                        <li class="list-inline-item" style="width: 60%;">
                                            <div class="input-group">
                                                <input type="text" id="example-input1-group2"
                                                    wire:model.live="search" class="form-control form-control-sm"
                                                    placeholder="Search">
                                                <button type="button" class="btn btn-primary"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </li>
                                        <li class="list-inline-item">
                                            <button type="button" class="btn btn-primary"><i
                                                    class="fas fa-filter"></i></button>
                                        </li>
                                        <li class="list-inline-item">
                                            <button class="btn btn-primary" wire:click="addDeal()" type="button">
                                                <span wire:loading wire:target="addDeal">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                </span>
                                                <span wire:loading.remove wire:target="addDeal">
                                                    <i class="fas fa-plus"></i> Add New
                                                </span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div><!--end col-->
                        </div>
                    </div>
                    <div class="card-body mb-n3">
                        <div class="row">
                            @foreach ($deals as $deal)
                                <div class="col-lg-3">
                                    <div class="card deal-card">
                                        <div class="card-body">
                                            <div class="media mb-3">
                                                <img src="assets/images/users/male.png"
                                                    class="me-3 thumb-lg align-self-center rounded-circle"
                                                    alt="...">
                                                <!-- Action icons container -->
                                                <div class="action-icons position-absolute top-0 end-0 p-2">
                                                    <!-- Edit icon -->
                                                    <a href="javascript:;" class="btn-sm edit-icon me-2"
                                                        wire:click="edit({{ $deal->id }})">
                                                        <i class="las la-pen text-secondary font-16 text-info"></i>
                                                    </a>

                                                    <!-- Delete icon -->
                                                    <a href="javascript:;" class="btn-sm delete-icon"
                                                        onclick="confirmDeletion({{ $deal->id }})">
                                                        <i
                                                            class="las la-trash-alt text-secondary font-16 text-danger"></i>
                                                    </a>
                                                </div>

                                                <div class="media-body align-self-center">


                                                    <h4 class="mt-0 mb-0 font-16">{{ optional($deal->lead)->name }}
                                                        {!! getstatusss(optional($deal->dealStatus)->name) !!}
                                                    </h4>
                                                    <p class="text-muted mb-0 font-12">
                                                        {{ optional($deal->lead)->address }}</p>
                                                </div><!--end media body-->
                                            </div> <!--end media-->
                                            <ul class="list-unstyled mb-2">
                                                <li class=""><i data-feather="calendar"
                                                        class="align-self-center icon-xs icon-dual me-1"></i> <b>Created
                                                        on</b> : {{ formatDate($deal->created_at) }}</li>
                                                <li class="mt-2"><i data-feather="phone"
                                                        class="align-self-center icon-xs icon-dual me-1"></i> <b> phone
                                                    </b> : {{ optional($deal->lead)->phone }}</li>
                                                <li class="mt-2"><i data-feather="mail"
                                                        class="align-self-center icon-xs icon-dual me-1"></i> <b> Email
                                                    </b> : {{ optional($deal->lead)->email }}</li>
                                            </ul>
                                            <p class="text-muted  mt-3">
                                                <span class="text-dark font-weight-semibold">Last Message :</span>
                                                {{ $deal->lead->notes }}
                                            </p>
                                            {{-- <div>
                                                <button type="button" class="btn btn-sm btn-de-primary">Send
                                                    SMS</button>
                                                <button type="button" class="btn btn-sm btn-de-primary">Send
                                                    Email</button>
                                            </div> --}}
                                        </div><!--end card-body-->
                                    </div> <!--end card-->
                                </div>
                            @endforeach

                          {{ $deals->links(data: ['scrollTo' => false]) }}
                        </div><!--end row-->
                    </div>
                </div>
            </div>

        </div><!-- container -->


        <!--end Rightbar-->

        <!-- Footer Start -->
        <livewire:layout.footer />
        <!-- end Footer -->

        @if ($showModal)
            @if ($showModal)
                <div class="modal show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
                    style="background: rgba(0, 0, 0, .6);">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title m-0" id="exampleModalDefaultLabel">
                                    {{ $modalMode === 'edit' ? 'Edit Deal' : 'Add New Deal' }}
                                </h6>
                                <button type="button" wire:click="closeModal" class="btn-close"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                            </div><!--end modal-header-->
                            @if ($modalMode === 'edit')
                                <form wire:submit.prevent="update" class="needs-validation">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="doj">Name<span
                                                            style="color: red;">*</span></label>
                                                    <input class="form-control" wire:model="name" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Phone<span
                                                            style="color: red;">*</span></label>
                                                    <input class="form-control" wire:model="phone" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Status<span
                                                            style="color: red;">*</span></label>
                                                    <select class="form-select" wire:model="status_id"
                                                        id="leadStatus">
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
                                                <div class="mb-3">
                                                    <label for="doj">Notes</label>
                                                    <textarea class="form-control" wire:model="notes" type="number" placeholder="" autocomplete="off"></textarea>
                                                    @error('notes')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Company Name</label>
                                                    <input class="form-control" wire:model="company" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('company')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Budget</label>
                                                    <input class="form-control" wire:model="budget" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('budget')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Deal Name<span
                                                        style="color: red;">*</span></label>
                                                    <input class="form-control" wire:model="dealName" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('dealName')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Stage<span
                                                            style="color: red;">*</span></label>
                                                    <select class="form-select" wire:model="stage_id"
                                                        id="stage_id">
                                                        <option value="">Choose</option>
                                                        @foreach ($dealstatus as $dsid => $dsname)
                                                            <option value="{{ $dsid }}">
                                                                {{ $dsname }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('stage_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                            </div><!-- end col -->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="doj">Email</label>
                                                    <input class="form-control" wire:model="email" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="doj">Source<span
                                                            style="color: red;">*</span></label>
                                                    <select class="form-select" wire:model="source_id"
                                                        id="leaveType">
                                                        <option value="">Select Sources</option>
                                                        @foreach ($leadSources as $sourceid => $sourcename)
                                                            <option value="{{ $sourceid }}">{{ $sourcename }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('source_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Priority</label>
                                                    <select class="form-select" wire:model="priority_id"
                                                        id="leaveType">
                                                        <option value="">Select Priority</option>
                                                        @foreach ($leadPriorities as $priorityid => $priorityname)
                                                            <option value="{{ $priorityid }}">{{ $priorityname }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                    @error('priority_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Address</label>
                                                    <textarea class="form-control" wire:model="address" placeholder="" autocomplete="off"></textarea>
                                                    @error('address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Position</label>
                                                    <input class="form-control" wire:model="position" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('position')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="doj">Follow-Up Date<span
                                                            style="color: red;">*</span></label>
                                                    <input type="date" class="form-control"
                                                        wire:model="next_followup_date" id="doj"
                                                        placeholder="">
                                                    @error('next_followup_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="doj">Closing Date<span
                                                            style="color: red;">*</span></label>
                                                    <input type="date" class="form-control"
                                                        wire:model="closing_date" id="doj" placeholder="">
                                                    @error('closing_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>


                                            </div><!-- end col -->

                                        </div><!-- end row -->
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
                                <form wire:submit.prevent="createDeal" class="needs-validation">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="doj">Name<span
                                                            style="color: red;">*</span></label>
                                                    <input class="form-control" wire:model="name" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Phone<span
                                                            style="color: red;">*</span></label>
                                                    <input class="form-control" wire:model="phone" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Status<span
                                                            style="color: red;">*</span></label>
                                                    <select class="form-select" wire:model="status_id"
                                                        id="leadStatus">
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
                                                <div class="mb-3">
                                                    <label for="doj">Notes</label>
                                                    <textarea class="form-control" wire:model="notes" type="number" placeholder="" autocomplete="off"></textarea>
                                                    @error('notes')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Company Name</label>
                                                    <input class="form-control" wire:model="company" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('company')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Budget</label>
                                                    <input class="form-control" wire:model="budget" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('budget')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Deal Name<span
                                                        style="color: red;">*</span></label>
                                                    <input class="form-control" wire:model="dealName" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('dealName')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Stage<span
                                                            style="color: red;">*</span></label>
                                                    <select class="form-select" wire:model="stage_id"
                                                        id="stage_id">
                                                        <option value="">Choose</option>
                                                        @foreach ($dealstatus as $dsid => $dsname)
                                                            <option value="{{ $dsid }}">
                                                                {{ $dsname }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('stage_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="doj">Email</label>
                                                    <input class="form-control" wire:model="email" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="doj">Source<span
                                                            style="color: red;">*</span></label>
                                                    <select class="form-select" wire:model="source_id"
                                                        id="leaveType">
                                                        <option value="">Select Sources</option>
                                                        @foreach ($leadSources as $sourceid => $sourcename)
                                                            <option value="{{ $sourceid }}">{{ $sourcename }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('source_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Priority</label>
                                                    <select class="form-select" wire:model="priority_id"
                                                        id="leaveType">
                                                        <option value="">Select Priority</option>
                                                        @foreach ($leadPriorities as $priorityid => $priorityname)
                                                            <option value="{{ $priorityid }}">{{ $priorityname }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                    @error('priority_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Address</label>
                                                    <textarea class="form-control" wire:model="address" placeholder="" autocomplete="off"></textarea>
                                                    @error('address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="doj">Position</label>
                                                    <input class="form-control" wire:model="position" type="text"
                                                        placeholder="" autocomplete="off">
                                                    @error('position')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="doj">Follow-Up Date<span
                                                            style="color: red;">*</span></label>
                                                    <input type="date" class="form-control"
                                                        wire:model="next_followup_date" id="doj"
                                                        placeholder="">
                                                    @error('next_followup_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="doj">Closing Date<span
                                                            style="color: red;">*</span></label>
                                                    <input type="date" class="form-control"
                                                        wire:model="closing_date" id="doj" placeholder="">
                                                    @error('closing_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>


                                            </div><!-- end col -->

                                        </div><!-- end row -->
                                    </div><!-- end modal-body -->

                                    <div class="modal-footer">
                                        <button type="button" wire:click="closeModal"
                                            class="btn btn-outline-secondary btn-sm"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            <span wire:loading wire:target="createDeal">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span> Loading...
                                            </span>
                                            <span wire:loading.remove wire:target="createDeal">
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
        @endif

    </div>
    <!-- end page content -->
</div>
