<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Worksheets</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Worksheets - {{ ucfirst($status) }}</h4>
                    </div>
                </div>
            </div>
            <!-- Filters -->
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold small">Filters</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Title</label>
                            <input type="text" wire:model.live="search" class="form-control" placeholder="Search Title...">
                        </div>
                        <!-- Status filter removed -->
                        <!-- Status filter fully removed -->
                        <div class="col-md-2">
                            <label class="form-label small">Work</label>
                            <select wire:model.live="work_id" class="form-select">
                                <option value="">-- Select Work --</option>
                                @foreach ($works ?? [] as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Employee</label>
                            <select wire:model.live="employee_id" class="form-select">
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees ?? [] as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Start Date From</label>
                            <input type="date" wire:model.live="date_from" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Start Date To</label>
                            <input type="date" wire:model.live="date_to" class="form-control">
                        </div>
                        <!-- Overdue / Status filter removed -->
                        <div class="col-md-2">
                            <label class="form-label small">Client</label>
                            <select wire:model.live="client_id" class="form-select">
                                <option value="">-- Select Client --</option>
                                @foreach ($clients ?? [] as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                 
                        <div class="col-md-2">
                            <label class="form-label small">Deadline From</label>
                            <input type="date" wire:model.live="deadline_from" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Deadline To</label>
                            <input type="date" wire:model.live="deadline_to" class="form-control">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button wire:click="resetFilters" class="btn btn-secondary w-100" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-undo me-1"></i> Refresh
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin me-1"></i> Refreshing...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse($projects as $work)
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media mb-3">
                                <a href="{{ route('project.details', ['id' => Crypt::encryptString($work->id)]) }}" wire:navigate>
                                    <img src="{{ $work->getFirstMediaUrl('project') ?: asset('assets/images/users/user-8.jpg') }}" alt="Project Image" class="thumb-md rounded-circle">
                                    <br />
                                    {!! getstatusss(optional($work->priorty)->name) !!}
                                </a>
                                <div class="media-body align-self-center text-truncate ms-2">
                                    <h4 class="m-0 fw-semibold text-dark font-16">{{ $work->title }}</h4>
                                    <p class="text-muted  mb-0 font-13"><span class="text-dark">Work :</span> {{ optional($work->work)->name }}</p>
                                    <p class="text-muted  mb-0 font-13"><span class="text-dark">Client :</span> {{ optional($work->client)->name }}</p>
                                </div>
                                <a href="javascript:;" wire:click="openStatusForm({{ $work->id }})">
                                    {!! getstatusss(optional($work->projectStatus)->name) !!}
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-semibold">Start : <span class="text-muted font-weight-normal">{{ formatDate($work->start_date) }}</span></h6>
                                <h6 class="fw-semibold">Deadline : <span class="text-muted font-weight-normal">{{ formatDate($work->deadline) }}</span></h6>
                            </div>
                            @can('View Price')
                            <div class="mt-3">
                                <h5 class="font-18 m-0"><i class="fas fa-rupee-sign"></i>{{ $work->cost }}</h5>
                                <p class="mb-0 fw-semibold">Total Budget</p>
                            </div>
                            @elseif(auth()->user()->id === $work->created_by)
                            <div class="mt-3">
                                <h5 class="font-18 m-0"><i class="fas fa-rupee-sign"></i>{{ $work->cost }}</h5>
                                <p class="mb-0 fw-semibold">Total Budget</p>
                            </div>
                            @endif
                            @can('View Client Deadline')
                            <h6 class="fw-semibold">Client's Deadline <span class="text-muted font-weight-normal">{{ formatDate($work->customer_deadline) }}</span></h6>
                            @endcan
                            <div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-semibold">Asigned On <span class="text-muted font-weight-normal">{{ formatDate($work->asigned_on) }}</span></h6>
                                    <h6 class="fw-semibold">{!! dateDifferenceFromCurrent($work->deadline, $work->completed_on) !!}</h6>
                                </div>
                                <p class="text-muted text-end mb-1">{{ calculateProgress($work->project_tasks_count, $work->completed_tasks_count, optional($work->projectStatus)->name) }}% Complete</p>
                                <div class="progress mb-4" style="height: 4px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ calculateProgress($work->project_tasks_count, $work->completed_tasks_count, optional($work->projectStatus)->name) }}%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="img-group">
                                        @if ($work->projectTeamMembers->isNotEmpty())
                                            @foreach ($work->projectTeamMembers->take(5) as $member)
                                                <a class="user-avatar {{ $loop->first ? '' : 'ms-n3' }}" href="#">
                                                    <img src="{{ empProfilePicture($member->user_id) }}" alt="user" class="thumb-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="{{ auth()->id() == $member->user_id ? 'It’s You!' : getUserNameById($member->user_id) }}">
                                                </a>
                                            @endforeach
                                            @if ($work->projectTeamMembers->count() > 5)
                                                <a href="javascript:;" class="user-avatar" id="{{ $work->id }}" onclick="toggleHiddenMembers('{{ $work->id }}')">
                                                    <span class="thumb-xs justify-content-center d-flex align-items-center bg-soft-info rounded-circle fw-semibold">+{{ $work->projectTeamMembers->count() - 5 }}</span>
                                                </a>
                                                <div id="hidden-members" class="d-none">
                                                    @foreach ($work->projectTeamMembers->skip(3) as $member)
                                                        <a class="user-avatar ms-n3" href="#">
                                                            <img src="{{ empProfilePicture($member->user_id) }}" alt="user" class="thumb-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="{{ auth()->id() == $member->user_id ? 'It’s You!' : getUserNameById($member->user_id) }}">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Not Assigned Yet</span>
                                        @endif
                                    </div>
                                    <ul class="list-inline mb-0 align-self-center">
                                        <li class="list-item d-inline-block">
                                            <a class="" href="javascript:;" wire:click="openAttachments({{ $work->id }})" title="Attachments" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i class="fas fa-paperclip text-primary font-15"></i>
                                                <span class="text-muted fw-bold">{{ $work->projectAttachments->flatMap->getMedia('project-attachment')->count() }}</span>
                                            </a>
                                        </li>
                                        <li class="list-item d-inline-block me-2">
                                            <a class="" href="{{ route('tasks', ['id' => Crypt::encryptString($work->id)]) }}" wire:navigate>
                                                <i class="mdi mdi-format-list-bulleted text-success font-15"></i>
                                                <span class="text-muted fw-bold">{{ $work->completed_tasks_count }}/{{ $work->project_tasks_count }}</span>
                                            </a>
                                        </li>
                                        <li class="list-item d-inline-block">
                                            <a class="" href="javascript:;" wire:click="openRemarksForm({{ $work->id }})" title="Remarks" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i class="mdi mdi-comment-outline text-primary font-15"></i>
                                                <span class="text-muted fw-bold">{{ $work->project_remarks_count }}</span>
                                            </a>
                                        </li>
                                        @can('Edit Project')
                                        <li class="list-item d-inline-block">
                                            <a class="ms-2" href="{{ route('project.edit', ['id' => Crypt::encryptString($work->id)]) }}" wire:navigate title="Project Edit" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i class="mdi mdi-pencil-outline text-muted font-18"></i>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('Delete Project')
                                        <li class="list-item d-inline-block">
                                            <a class="" href="javascript:;" onclick="confirmDeletion('{{ $work->id }}')" title="Project Delete" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i class="mdi mdi-trash-can-outline text-muted font-18"></i>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning">No worksheets found for this status.</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
