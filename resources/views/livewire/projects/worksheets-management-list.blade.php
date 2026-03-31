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
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">Projects</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Projects</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-lg-6">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <h5 class="mt-0">A total of <span class="text-primary">{{ $worksheets->count() }}</span> projects are listed below.
                                <span class="badge badge-pink">6</span>
                            </h5>
                        </li>
                    </ul>
                </div><!--end col-->

                <!-- Filters -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-bold small">Filters</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Search Title -->
                            <div class="col-md-3">
                                <label class="form-label small">Title</label>
                                <input type="text" wire:model.live="search" class="form-control" placeholder="Search Title...">
                            </div>
                            <!-- Employee Filter -->
                            <div class="col-md-3">
                                <label class="form-label small">Employee</label>
                                <select wire:model.live="employee_id" class="form-select">
                                    <option value="">-- Select Employee --</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Status Filter -->
                            <div class="col-md-2">
                                <label class="form-label small">Status</label>
                                <select wire:model.live="status_id" class="form-select">
                                    <option value="">-- Select Status --</option>
                                    @foreach ($statuses as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Start Date Filter -->
                            <div class="col-md-2">
                                <label class="form-label small">Start Date From</label>
                                <input type="date" wire:model.live="date_from" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Start Date To</label>
                                <input type="date" wire:model.live="date_to" class="form-control">
                            </div>
                            <!-- Work Filter -->
                            <div class="col-md-2">
                                <label class="form-label small">Work</label>
                                <select wire:model.live="work_id" class="form-select">
                                    <option value="">-- Select Work --</option>
                                    @foreach ($works as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Client Filter -->
                            <div class="col-md-2">
                                <label class="form-label small">Client</label>
                                <select wire:model.live="client_id" class="form-select">
                                    <option value="">-- Select Client --</option>
                                    @foreach ($clients as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Overdue / Status Filter -->
                            <div class="col-md-2">
                                <label class="form-label small">Overdue / Status</label>
                                <select wire:model.live="overdue_filter" class="form-select">
                                    <option value="">-- Select --</option>
                                    <option value="overdue">Overdue</option>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <!-- Deadline Filter -->
                            <div class="col-md-2">
                                <label class="form-label small">Deadline From</label>
                                <input type="date" wire:model.live="deadline_from" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Deadline To</label>
                                <input type="date" wire:model.live="deadline_to" class="form-control">
                            </div>
                            <!-- Refresh Button -->
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
                <!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Project Name</th>
                                <th>Client Name</th>
                                <th>Start Date</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Team Members</th>
                                <th>About Project</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($worksheets as $work)
                            <tr>
                                <td><img src="{{ $work->getFirstMediaUrl('project') ?: asset('assets/images/users/user-8.jpg') }}" alt="" class="thumb-sm rounded me-2">{{ $work->title }}

                                    <br />
                                    <small class="float-end ms-2 pt-1 font-10">{{calculateProgress($work->project_tasks_count, $work->completed_tasks_count,optional($work->projectStatus)->name)}}%</small>
                                    <div class="progress mt-2" style="height:3px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{calculateProgress($work->project_tasks_count, $work->completed_tasks_count,optional($work->projectStatus)->name)}}%;" aria-valuenow="{{calculateProgress($work->project_tasks_count, $work->completed_tasks_count,optional($work->projectStatus)->name)}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td>{{ optional($work->client)->name }} </td>
                                <td>{{ formatDate($work->start_date) }}</td>
                                <td>{{ formatDate($work->deadline) }}<br />
                                    {!! dateDifferenceFromCurrent($work->deadline, $work->completed_on) !!}
                                </td>
                                <td>
                                    <a href="javascript:;" wire:click="openStatusForm({{$work->id}})">
                                        {!! getstatusss(optional($work->projectStatus)->name) !!}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between">

                                        <div class="img-group">
                                            @if ($work->projectTeamMembers->isNotEmpty())
                                            @foreach ($work->projectTeamMembers->take(5) as $member)
                                            <a class="user-avatar {{ $loop->first ? '' : 'ms-n3' }}"
                                                href="#">
                                                <img src="{{ empProfilePicture($member->user_id) }}"
                                                    alt="user" class="thumb-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="{{ auth()->id() == $member->user_id ? 'It’s You!' : getUserNameById($member->user_id) }}">
                                            </a>
                                            @endforeach

                                            {{-- Show "+X" button if there are more than 3 members --}}
                                            @if ($work->projectTeamMembers->count() > 5)
                                            <a href="javascript:;" class="user-avatar" id="{{ $work->id }}"
                                                onclick="toggleHiddenMembers('{{ $work->id }}')">
                                                <span
                                                    class="thumb-xs justify-content-center d-flex align-items-center bg-soft-info rounded-circle fw-semibold">
                                                    +{{ $work->projectTeamMembers->count() - 5 }}
                                                </span>
                                            </a>

                                            {{-- Hidden members --}}
                                            <div id="hidden-members" class="d-none">
                                                @foreach ($work->projectTeamMembers->skip(3) as $member)
                                                <a class="user-avatar ms-n3" href="#">
                                                    <img src="{{ empProfilePicture($member->user_id) }}"
                                                        alt="user" class="thumb-xs rounded-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="custom-tooltip"
                                                        data-bs-title="{{ auth()->id() == $member->user_id ? 'It’s You!' : getUserNameById($member->user_id) }}">
                                                </a>
                                                @endforeach
                                            </div>
                                            @endif
                                            @else
                                            <span class="badge bg-secondary">Not Assigned Yet</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <ul class="list-inline mb-0 align-self-center">
                                        <li class="list-item d-inline-block">
                                            <a class="" href="javascript:;"
                                                wire:click="openAttachments({{ $work->id }})"
                                                title="Attachments" data-bs-toggle="tooltip"
                                                data-bs-placement="top">
                                                <i class="fas fa-paperclip text-primary font-15"></i>
                                                <span
                                                    class="text-muted fw-bold">{{ $work->projectAttachments->flatMap->getMedia('project-attachment')->count() }}</span>
                                            </a>
                                        </li>

                                        <li class="list-item d-inline-block me-2">
                                            <a class="" href="{{ route('tasks', ['id' => Crypt::encryptString($work->id)]) }}" wire:navigate>
                                                <i class="mdi mdi-format-list-bulleted text-success font-15"></i>
                                                <span class="text-muted fw-bold">{{ $work->completed_tasks_count }}/{{ $work->project_tasks_count }}</span>
                                            </a>
                                        </li>
                                        <li class="list-item d-inline-block">
                                            <a class="" href="javascript:;"
                                                wire:click="openRemarksForm({{ $work->id }})" title="Remarks"
                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i class="mdi mdi-comment-outline text-primary font-15"></i>
                                                <span
                                                    class="text-muted fw-bold">{{ $work->project_remarks_count }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    @can('Edit Project')

                                    <a class="ms-2"
                                        href="{{ route('project.edit', ['id' => Crypt::encryptString($work->id)]) }}"
                                       title="Project Edit" data-bs-toggle="tooltip"
                                        data-bs-placement="top">
                                        <i class="mdi mdi-pencil-outline text-muted font-18"></i>
                                    </a>

                                    @endcan

                                    @can('Delete Project')

                                    <a class="" href="javascript:;"
                                        onclick="confirmDeletion('{{ $work->id }}')" title="Project Delete"
                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="mdi mdi-trash-can-outline text-muted font-18"></i>
                                    </a>

                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>



        </div><!-- container -->

        <!--Start Footer-->
        <livewire:layout.footer />
        <!--end footer-->

        @if ($remarksModal)
        <div class="modal fade  show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
            style="background: rgba(0, 0, 0, .6);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0" id="exampleModalDefaultLabel"> Remarks</h6>
                        <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div><!--end modal-header-->

                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col">
                                    <p class="text-dark fw-semibold mb-0">Comments ({{ $totalRemarksCount }})</p>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div><!--end card-body-->
                        <div class="card-body border-bottom-dashed">
                            <ul class="list-unstyled mb-0">
                                @foreach ($allremarks as $remark)
                                <li class="mt-3">
                                    <div class="row">
                                        <div class="col-auto">
                                            <img src="{{ empProfilePicture($remark->user_id) }}"
                                                alt="" class="thumb-md rounded-circle">
                                        </div>
                                        <div class="col">
                                            <div class="comment-body ms-n2 bg-light-alt p-3">
                                                <div class="row">
                                                    <div class="col">
                                                        <p class="text-dark fw-semibold mb-2">
                                                            {{ $remark->commenter->name }}
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="text-muted"><i
                                                                class="far fa-clock me-1"></i>{{ $remark->time_ago }}</span>
                                                    </div>
                                                </div>
                                                <p>{{ $remark->remarks }}</p>

                                            </div>
                                        </div>
                                    </div>

                                </li>
                                @endforeach
                            </ul>
                        </div><!--end card-body-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="text-dark fw-semibold mb-0">Leave a comment</p>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div><!--end card-body-->
                        <div class="card-body pt-0">
                            <form wire:submit.prevent="saveRemarks" class="needs-validation">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" rows="5" required wire:model="remarks" id="leave_comment"
                                        placeholder="Message"></textarea>
                                </div>
                                <div class="row">

                                    {{-- <div class="col-sm-7 text-start">
                                        <div class="row">
                                            <label for="doj" class="mb-2">Status<span style="color: red;">*</span></label>
                                            <select id="status_id" wire:model="status_id" class="w-full p-2 border border-gray-300 rounded" required="">
                                                <option value="">Choose</option>
                                                @foreach ($workstatus as $statusid => $statusname)
                                                <option value="{{ $statusid }}">{{ $statusname }}
                                    </option>
                                    @endforeach
                                    </select>
                                    @error('status_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                        </div> --}}
                        <div class="col-sm-12 mt-5 text-end">
                            <button type="submit" class="btn btn-de-primary px-4 text-end">
                                <span wire:loading wire:target="saveRemarks">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span> Loading...
                                </span>
                                <span wire:loading.remove wire:target="saveRemarks">
                                    Send Message
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
@endif



@if ($attachmentsModal)
<div class="modal fade  show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
    style="background: rgba(0, 0, 0, .6);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="exampleModalDefaultLabel">Attachments</h6>
                <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div><!--end modal-header-->

            <div class="card">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col">
                            <p class="text-dark fw-semibold mb-0">Attachments
                                ({{ $totalAttachmentsCount }})</p>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
                <div class="card-body border-bottom-dashed">

                    @foreach ($allAttachments as $attachment)
                    <div class="row">
                        <div>
                            <label class="form-label mt-2" for="pro-end-date">{{$attachment['title']}}</label>
                            <div class="col-auto text-end">
                                <span class="text-muted">
                                    Attached By {{ getUserNameById($attachment['attached_by'] ) }}
                                    <i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($attachment['attached_on'])->format('d M Y') }}</span>
                            </div>
                        </div>

                        @foreach ($attachment['media'] as $media)
                        @php
                        $extension = pathinfo($media['name'], PATHINFO_EXTENSION);
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']; // Define image extensions

                        @endphp

                        <div class="col-auto">
                            <div class="card">
                                @if (in_array(strtolower($extension), $imageExtensions))
                                <!-- Display as Image -->
                                <img class="" src="{{ $media['url'] }}" alt="Image"
                                    style="width: 100px; height: 75px;"
                                    title="{{ $media['name'] }} ">
                                @else
                                <!-- Display as File Icon -->
                                <div class="p-3 text-center mx-auto">
                                    @if ($extension === 'pdf')
                                    @php
                                    $icon = 'fa-file-pdf text-danger';
                                    @endphp
                                    @elseif($extension === 'docx' || $extension === 'doc')
                                    @php
                                    $icon = 'fa-file-word text-primary';
                                    @endphp
                                    @elseif($extension === 'xlsx' || $extension === 'xls')
                                    @php
                                    $icon = 'fa-file-excel text-success';
                                    @endphp
                                    @elseif($extension === 'pptx' || $extension === 'ppt')
                                    @php
                                    $icon = 'fa-file-powerpoint text-warning';
                                    @endphp
                                    @elseif($extension === 'mp3' || $extension === 'wav' || $extension === 'ogg')
                                    @php
                                    $icon = 'fa-file-audio text-info';
                                    @endphp
                                    @elseif($extension === 'mp4' || $extension === 'avi' || $extension === 'mov')
                                    @php
                                    $icon = 'fa-file-video text-primary';
                                    @endphp
                                    @else
                                    @php
                                    $icon = 'fa-file text-primary';
                                    @endphp
                                    @endif
                                    <i class="far {{ $icon }} fa-3x"></i>
                                </div>
                                @endif
                                <div class="py-1 p-2 text-center">
                                    <a href="javascript:;"
                                        wire:click.prevent="download('{{ $media['id'] }}', '{{ $media['name'] }}')"
                                        x-data="{ loading: false }" x-on:click="loading = true"
                                        x-on:download-complete.window="loading = false"
                                        class="text-muted">
                                        <span x-show="!loading">Download <i
                                                class="dripicons-download ms-1"></i></span>
                                        <span x-show="loading">
                                            <i class="fas fa-spinner fa-spin"></i> Downloading...
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @endforeach

                    </div>
                    @endforeach


                    <div class="row mt-3">
                        <form wire:submit.prevent="newAttachment" class="">
                            <label class="form-label mt-2" for="pro-end-date">Attach More Files</label>

                            <div class="form-group mt-2 mb-3">
                                <input type="text" class="form-control" id=""
                                    wire:model="attachedTitle"
                                    placeholder="Enter Title Here..">

                            </div>

                            <div x-data="{ isDragging: false }" x-on:dragover.prevent="isDragging = true"
                                x-on:dragleave.prevent="isDragging = false"
                                x-on:drop.prevent="isDragging = false; $wire.uploadMultiple('files', $event.dataTransfer.files)"
                                class="border border-2 border-soft-secondary p-4 rounded text-center bg-light"
                                :class="{ 'border-primary bg-primary bg-opacity-10': isDragging }">

                                <!-- Show Loading Icon When File is Being Processed -->
                                <div wire:loading wire:target="files" class="text-center">
                                    <span class="spinner-border spinner-border-lg text-primary" role="status" aria-hidden="true"></span>
                                    <p class="text-muted mt-2">Processing Files...</p>
                                </div>

                                <!-- Hide File Input and Label While Uploading -->
                                <div wire:loading.remove wire:target="files">
                                    <input type="file" multiple wire:model="files" class="d-none" id="fileInput">
                                    <label for="fileInput" class="d-block">
                                        <p class="text-muted" x-show="!isDragging"><i class="fas fa-cloud-upload-alt"></i> Drag & Drop files Here or Click to Upload</p>
                                        <p class="text-primary fw-bold" x-show="isDragging">Drop the files here...</p>
                                    </label>
                                </div>
                            </div>
                            <!-- Show selected file names -->
                            @if ($files)
                            <div class="mt-3">
                                <ul class="list-group">
                                    @foreach ($files as $file)
                                    <li class="list-group-item text-success">
                                        {{ $file->getClientOriginalName() }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="p-2 text-start mt-3 float-end">
                                <button type="submit" class="btn btn-primary float-end">
                                    <span wire:loading wire:target="newAttachment">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span> Loading...
                                    </span>
                                    <span wire:loading.remove wire:target="newAttachment">
                                        <i class="fas fa-paperclip "></i> Attach
                                    </span>
                                </button>

                            </div>
                        </form>
                    </div>

                </div><!--end card-body-->


            </div>
        </div>
    </div>
</div>
@endif


@if ($openStatusModal)
<div class="modal fade  show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
    style="background: rgba(0, 0, 0, .6);">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="exampleModalDefaultLabel"> Status</h6>
                <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div><!--end modal-header-->

            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <p class="text-dark fw-semibold mb-0">Change Status</p>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
                <div class="card-body pt-0">
                    <form wire:submit.prevent="saveStatus" class="needs-validation">

                        <div class="row">

                            <div class="col-sm-12 text-start">
                                <div class="row">
                                    <label for="doj" class="mb-2">Status<span style="color: red;">*</span></label>
                                    <select id="status_id" wire:model="status_id" class="w-full p-2 border border-gray-300 rounded" required="">
                                        <option value="">Choose</option>
                                        @foreach ($workstatus as $statusid => $statusname)
                                        <option value="{{ $statusid }}">{{ $statusname }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 mt-5 text-end">
                                <button type="submit" class="btn btn-de-primary px-4 text-end">
                                    <span wire:loading wire:target="saveStatus">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span> Loading...
                                    </span>
                                    <span wire:loading.remove wire:target="saveStatus">
                                        Save Status
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
@endif



</div>
<!-- end page content -->
</div>

<script>
    function toggleHiddenMembers(id) {

        document.getElementById('hidden-members').classList.toggle('d-none');
        document.getElementById(id).style.display = "none";
    }
</script>
<script>
    function confirmDeletion(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteProject', {
                    id: itemId
                }); // Dispatch Livewire event
            }
        });
    }
</script>