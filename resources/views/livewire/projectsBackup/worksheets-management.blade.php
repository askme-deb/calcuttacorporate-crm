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
                            <h5 class="mt-0">A total of <span class="text-primary">{{ $worksheets->count() }}</span>
                                projects are listed below.
                                <span class="badge badge-pink">6</span>
                            </h5>
                        </li>
                    </ul>
                </div><!--end col-->

                <div class="col-lg-6 text-end">
                    <div class="text-end">

                        <ul class="list-inline">
                            <li class="list-inline-item" style="width: 50%;">
                                <div class="input-group">
                                    <input type="text" id="example-input1-group2" wire:model.live="search"
                                        class="form-control form-control-sm" placeholder="Search">
                                    <button type="button" class="btn btn-primary btn-sm"><i
                                            class="fas fa-search"></i></button>
                                </div>
                            </li>
                            <li class="list-inline-item" x-data="{ open: false }" x-init="$wire.on('closeDropdown', () => { open = false })">
                                <!-- Button that toggles the dropdown -->
                                <button type="button" class="btn btn-primary btn-sm" @click="open = !open">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <!-- Dropdown menu (visible when 'open' is true) -->
                                <div x-show="open" @click.away="open = false" class="dropdown-menu show"
                                    style="position: absolute;">
                                    <a href="javascript:;" class="dropdown-item" wire:click="setProjectType('')">All</a>
                                    @foreach ($jobtypes as $jobid => $jobname)
                                        <a href="javascript:;" class="dropdown-item"
                                            wire:click="setProjectType({{ $jobid }})">{{ $jobname }}</a>
                                    @endforeach
                                </div>
                            </li>
                            <li class="list-inline-item" x-data="{ open: false }" x-init="$wire.on('closeDropdown', () => { open = false })">
                                <!-- Button that toggles the dropdown -->
                                <button type="button" class="btn btn-primary btn-sm" @click="open = !open">
                                    <!-- <i class="fas fa-filter"></i> -->
                                    <i class="fas fa-list"></i>
                                </button>

                                <!-- Dropdown menu (visible when 'open' is true) -->
                                <div x-show="open" @click.away="open = false" class="dropdown-menu show"
                                    style="position: absolute;">
                                    <a href="javascript:;" class="dropdown-item" wire:click="setView('list')">List
                                        View</a>
                                    <a href="javascript:;" class="dropdown-item" wire:click="setView('grid')">Grid
                                        View</a>
                                </div>
                            </li>

                            @can('Create Project')
                                <li class="list-inline-item">
                                    <a href="{{ route('project.create') }}" wire:navigate class="btn btn-primary btn-sm"><i
                                            class="fas fa-plus"></i> Add New Project</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
                <!--end col-->
            </div><!--end row-->

            <div class="row">
                @foreach ($worksheets as $work)
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="media mb-3">
                                    <a href="{{ route('project.details', ['id' => Crypt::encryptString($work->id)]) }}"
                                        wire:navigate>
                                        <img src="{{ $work->getFirstMediaUrl('project') ?: asset('assets/images/users/user-8.jpg') }}"
                                            alt="Project Image" class="thumb-md rounded-circle">
                                    </a>
                                    <div class="media-body align-self-center text-truncate ms-2">
                                        <h4 class="m-0 fw-semibold text-dark font-16">{{ $work->title }}
                                        </h4>
                                        <p class="text-muted  mb-0 font-13"><span class="text-dark">Work :
                                            </span>{{ optional($work->work)->name }}</p>
                                        <p class="text-muted  mb-0 font-13"><span class="text-dark">Client :
                                            </span>{{ optional($work->client)->name }}</p>
                                    </div>
                                    <a href="javascript:;" wire:click="openStatusForm({{ $work->id }})">
                                        {!! getstatusss(optional($work->projectStatus)->name) !!}
                                    </a>

                                </div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-semibold">Start : <span class="text-muted font-weight-normal">
                                            {{ formatDate($work->start_date) }}</span></h6>
                                    <h6 class="fw-semibold">Deadline : <span class="text-muted font-weight-normal">
                                            {{ formatDate($work->deadline) }}</span></h6>
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
                        <h6 class="fw-semibold">Client's Deadline <span class="text-muted font-weight-normal">
                                {{ formatDate($work->customer_deadline) }}</span></h6>
                    @endcan
                    <div>
                        <!-- <p class="text-muted mt-4 mb-1">{{ $work->description }}
                                                </p> -->
                        <div class="d-flex justify-content-between">

                            <h6 class="fw-semibold">Asigned On <span class="text-muted font-weight-normal">
                                    {{ formatDate($work->asigned_on) }}</span></h6>
                            <h6 class="fw-semibold">
                                {!! dateDifferenceFromCurrent($work->deadline, $work->completed_on) !!}

                            </h6>
                        </div>
                        <p class="text-muted text-end mb-1">
                            {{ calculateProgress($work->project_tasks_count, $work->completed_tasks_count, optional($work->projectStatus)->name) }}%
                            Complete</p>
                        <div class="progress mb-4" style="height: 4px;">
                            <div class="progress-bar bg-info" role="progressbar"
                                style="width: {{ calculateProgress($work->project_tasks_count, $work->completed_tasks_count, optional($work->projectStatus)->name) }}%;"
                                aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">

                            <div class="img-group">
                                @if ($work->projectTeamMembers->isNotEmpty())
                                    @foreach ($work->projectTeamMembers->take(5) as $member)
                                        <a class="user-avatar {{ $loop->first ? '' : 'ms-n3' }}" href="#">
                                            <img src="{{ empProfilePicture($member->user_id) }}" alt="user"
                                                class="thumb-xs rounded-circle" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-custom-class="custom-tooltip"
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
                                                    <img src="{{ empProfilePicture($member->user_id) }}" alt="user"
                                                        class="thumb-xs rounded-circle" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                        data-bs-title="{{ auth()->id() == $member->user_id ? 'It’s You!' : getUserNameById($member->user_id) }}">
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
                                    <a class="" href="javascript:;"
                                        wire:click="openAttachments({{ $work->id }})" title="Attachments"
                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="fas fa-paperclip text-primary font-15"></i>
                                        <span
                                            class="text-muted fw-bold">{{ $work->projectAttachments->flatMap->getMedia('project-attachment')->count() }}</span>
                                    </a>
                                </li>

                                <li class="list-item d-inline-block me-2">
                                    <a class=""
                                        href="{{ route('tasks', ['id' => Crypt::encryptString($work->id)]) }}"
                                        wire:navigate>
                                        <i class="mdi mdi-format-list-bulleted text-success font-15"></i>
                                        <span
                                            class="text-muted fw-bold">{{ $work->completed_tasks_count }}/{{ $work->project_tasks_count }}</span>
                                    </a>
                                </li>
                                <li class="list-item d-inline-block">
                                    <a class="" href="javascript:;"
                                        wire:click="openRemarksForm({{ $work->id }})" title="Remarks"
                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="mdi mdi-comment-outline text-primary font-15"></i>
                                        <span class="text-muted fw-bold">{{ $work->project_remarks_count }}</span>
                                    </a>
                                </li>

                                @can('Edit Project')
                                    <li class="list-item d-inline-block">
                                        <a class="ms-2"
                                            href="{{ route('project.edit', ['id' => Crypt::encryptString($work->id)]) }}"
                                            wire:navigate title="Project Edit" data-bs-toggle="tooltip"
                                            data-bs-placement="top">
                                            <i class="mdi mdi-pencil-outline text-muted font-18"></i>
                                        </a>
                                    </li>
                                @endcan

                                @can('Delete Project')
                                    <li class="list-item d-inline-block">
                                        <a class="" href="javascript:;"
                                            onclick="confirmDeletion('{{ $work->id }}')" title="Project Delete"
                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                            <i class="mdi mdi-trash-can-outline text-muted font-18"></i>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </div><!--end task-box-->
                </div><!--end card-body-->
            </div><!--end card-->
        </div>
        @endforeach
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
                                                <img src="{{ empProfilePicture($remark->user_id) }}" alt=""
                                                    class="thumb-md rounded-circle">
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
                                        <label class="form-label mt-2"
                                            for="pro-end-date">{{ $attachment['title'] }}</label>
                                        <div class="col-auto text-end">
                                            <span class="text-muted">
                                                Attached By {{ getUserNameById($attachment['attached_by']) }}
                                                <i
                                                    class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($attachment['attached_on'])->format('d M Y') }}</span>
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
                                            wire:model="attachedTitle" placeholder="Enter Title Here..">

                                    </div>

                                    <div x-data="{ isDragging: false }" x-on:dragover.prevent="isDragging = true"
                                        x-on:dragleave.prevent="isDragging = false"
                                        x-on:drop.prevent="isDragging = false; $wire.uploadMultiple('files', $event.dataTransfer.files)"
                                        class="border border-2 border-soft-secondary p-4 rounded text-center bg-light"
                                        :class="{ 'border-primary bg-primary bg-opacity-10': isDragging }">

                                        <!-- Show Loading Icon When File is Being Processed -->
                                        <div wire:loading wire:target="files" class="text-center">
                                            <span class="spinner-border spinner-border-lg text-primary" role="status"
                                                aria-hidden="true"></span>
                                            <p class="text-muted mt-2">Processing Files...</p>
                                        </div>

                                        <!-- Hide File Input and Label While Uploading -->
                                        <div wire:loading.remove wire:target="files">
                                            <input type="file" multiple wire:model="files" class="d-none"
                                                id="fileInput">
                                            <label for="fileInput" class="d-block">
                                                <p class="text-muted" x-show="!isDragging"><i
                                                        class="fas fa-cloud-upload-alt"></i> Drag & Drop files Here or
                                                    Click to Upload</p>
                                                <p class="text-primary fw-bold" x-show="isDragging">Drop the files here...
                                                </p>
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
                                            <label for="doj" class="mb-2">Status<span
                                                    style="color: red;">*</span></label>
                                            <select id="status_id" wire:model="status_id"
                                                class="w-full p-2 border border-gray-300 rounded" required="">
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
