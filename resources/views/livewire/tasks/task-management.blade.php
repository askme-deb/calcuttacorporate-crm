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
                                <li class="breadcrumb-item"><a href="{{ route('worksheet') }}">Worksheet</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">Tasks</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Tasks</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row mb-3">
                <div class="col-lg-6">
                    <h5 class="d-inline-block me-2">{{ $projectTitle }}</h5>

                    <div class="img-group d-inline-block">
                        @if ($project->projectTeamMembers->isNotEmpty())
                            @foreach ($project->projectTeamMembers->take(5) as $member)
                                <a class="user-avatar {{ $loop->first ? '' : 'ms-n3' }}" href="#">
                                    <img src="{{ empProfilePicture($member->user_id) }}" alt="user"
                                        class="thumb-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="{{ auth()->id() == $member->user_id ? 'It’s You!' : getUserNameById($member->user_id) }}">
                                </a>
                            @endforeach

                            {{-- Show "+X" button if there are more than 3 members --}}
                            @if ($project->projectTeamMembers->count() > 5)
                                <a href="javascript:;" class="user-avatar" id="{{ $project->id }}"
                                    onclick="toggleHiddenMembers('{{ $project->id }}')">
                                    <span
                                        class="thumb-xs justify-content-center d-flex align-items-center bg-soft-info rounded-circle fw-semibold">
                                        +{{ $project->projectTeamMembers->count() - 5 }}
                                    </span>
                                </a>
                                {{-- Hidden members --}}
                                <div id="hidden-members" class="d-none">
                                    @foreach ($project->projectTeamMembers->skip(3) as $member)
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

                </div><!--end col-->

                <div class="col-lg-6 text-end align-self-center">
                    @can('Create Task')
                        <button class="btn btn-primary btn-sm" wire:click="addTask()" type="button">
                            <span wire:loading wire:target="addTask">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </span>
                            <span wire:loading.remove wire:target="addTask">
                                <i class="fas fa-plus"></i> Add New Task
                            </span>
                        </button>
                    @endcan
                </div>
                <!--end col-->
            </div><!--end row-->

            <div class="row">
                @if ($alltasks->isNotEmpty())
                    @foreach ($alltasks as $task)
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="task-box">
                                        <div class="task-priority-icon"><i class="fas fa-circle text-info"></i></div>
                                        <p class="text-muted float-right">
                                            <!-- <span class="text-muted">01:33</span> /
                                    <span class="text-muted">9:30</span>
                                    <span class="mx-1">·</span> -->
                                            <span><i class="far fa-fw fa-clock"></i>Start Date:
                                                {{ formatDate($task->start_date) }}</span>
                                            /<span><i class="far fa-fw fa-clock"></i>Due Date:
                                                {{ formatDate($task->due_date) }}</span>

                                            <span class="float-end">
                                                {!! getstatusss(optional($task->taskStatus)->name) !!}
                                            </span>

                                        </p>

                                        <h5 class="mt-0">{{ $task->title }}</h5>
                                        <p class="text-muted mb-1">
                                            {{ $task->description }}
                                        </p>
                                        <p class="text-muted text-end mb-3 mt-3"><span class="text-muted"><i
                                                    class="far fa-fw fa-user"></i>Created By
                                                {{ getUserNameById($task->created_by) }} on <i
                                                    class="far fa-fw fa-clock"></i>{{ formatDate($task->created_at) }}</span>
                                        </p>

                                        <div class="d-flex justify-content-between">
                                            <div class="img-group">
                                                @if ($task->taskTeamMembers->isNotEmpty())
                                                    @foreach ($task->taskTeamMembers->take(5) as $member)
                                                        <a class="user-avatar {{ $loop->first ? '' : 'ms-n3' }}"
                                                            href="#">
                                                            <img src="{{ empProfilePicture($member->assigned_to) }}"
                                                                alt="user" class="thumb-xs rounded-circle"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-custom-class="custom-tooltip"
                                                                data-bs-title="{{ auth()->id() == $member->assigned_to ? 'It’s You!' : getUserNameById($member->assigned_to) }}">
                                                        </a>
                                                    @endforeach

                                                    {{-- Show "+X" button if there are more than 3 members --}}
                                                    @if ($task->taskTeamMembers->count() > 5)
                                                        <a href="javascript:;" class="user-avatar"
                                                            id="{{ $task->id }}"
                                                            onclick="toggleHiddenMembers('{{ $task->id }}')">
                                                            <span
                                                                class="thumb-xs justify-content-center d-flex align-items-center bg-soft-info rounded-circle fw-semibold">
                                                                +{{ $task->taskTeamMembers->count() - 5 }}
                                                            </span>
                                                        </a>

                                                        {{-- Hidden members --}}
                                                        <div id="hidden-members" class="d-none">
                                                            @foreach ($task->taskTeamMembers->skip(3) as $member)
                                                                <a class="user-avatar ms-n3" href="#">
                                                                    <img src="{{ empProfilePicture($member->assigned_to) }}"
                                                                        alt="user" class="thumb-xs rounded-circle"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        data-bs-custom-class="custom-tooltip"
                                                                        data-bs-title="{{ auth()->id() == $member->assigned_to ? 'It’s You!' : getUserNameById($member->assigned_to) }}">
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
                                                        wire:click="openAttachments({{ $task->id }})"
                                                        title="Attachments" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="fas fa-paperclip text-primary font-15"></i>
                                                        <span
                                                            class="text-muted fw-bold">{{ $task->taskAttachments->flatMap->getMedia('task-attachment')->count() }}</span>
                                                    </a>
                                                </li>
                                                <li class="list-item d-inline-block">
                                                    <a class="" href="javascript:;"
                                                        wire:click="openRemarksForm({{ $task->id }})"
                                                        title="Remarks" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="mdi mdi-comment-outline text-primary font-15"></i>
                                                        <span
                                                            class="text-muted fw-bold">{{ $task->task_remarks_count }}</span>
                                                    </a>
                                                </li>
                                                <!-- <li class="list-item d-inline-block">
                                            <a class="" href="#">
                                                <i class="mdi mdi-comment-outline text-primary font-15"></i>
                                                <span class="text-muted fw-bold">0</span>
                                            </a>
                                        </li> -->
                                                @can('Edit Task')
                                                    <li class="list-item d-inline-block">
                                                        <a class="ms-2" href="javascript:;"
                                                            wire:click="edit({{ $task->id }})">
                                                            <i class="mdi mdi-pencil-outline text-muted font-18"></i>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('Delete Task')
                                                    <li class="list-item d-inline-block">
                                                        <a class="" href="javascript:;"
                                                            onclick="confirmDeletion('{{ $task->id }}')">
                                                            <i class="mdi mdi-trash-can-outline text-muted font-18"></i>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div><!--end task-box-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    @endforeach
                @else
                    <div class="alert alert-outline-secondary text-center" role="alert">
                        Currently, there are no tasks to display.
                        @can('Create Task')
                            Please <a href="javascript:;" wire:click="addTask()">create a new task</a> to get started.
                        @endcan
                    </div>
                @endif

            </div><!--end row-->

        </div><!-- container -->



        <!--Start Footer-->
        <livewire:layout.footer />
        <!--end footer-->
        @if ($showModal)
            <div class="modal show d-block" id="exampleModalDefault" data-bs-backdrop="static" role="dialog"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
                style="background: rgba(0, 0, 0, .6);">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title m-0" id="exampleModalDefaultLabel">
                                {{ $modalMode === 'edit' ? 'Edit Task' : 'Add New Task' }}
                            </h6>
                            <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div><!--end modal-header-->
                        @if ($modalMode === 'edit')
                            <form wire:submit.prevent="update" class="needs-validation">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="title">Title<span class="text-danger">*</span></label>
                                                <input class="form-control" wire:model="title" type="text"
                                                    autocomplete="off" id="title">
                                                @error('title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="leadStatus">Status<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" wire:model="status_id" id="leadStatus">
                                                    <option value="">Select Status</option>
                                                    @foreach ($taskStatus as $statusid => $statusname)
                                                        <option value="{{ $statusid }}">{{ $statusname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('status_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="start_date">Start Date<span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" wire:model="start_date"
                                                    id="start_date">
                                                @error('start_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="priority">Priority<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" wire:model="priority_id" id="priority">
                                                    <option value="">Select Priority</option>
                                                    @foreach ($priorities as $priorityid => $priorityname)
                                                        <option value="{{ $priorityid }}">{{ $priorityname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('priority_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="due_date">Due Date<span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" wire:model="due_date"
                                                    id="due_date">
                                                @error('due_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group mb-3">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" rows="5" wire:model="description" id="description" placeholder="Description"></textarea>
                                            </div>
                                        </div>

                                        <div x-data="{ open: false, selectedUsers: @entangle('asign_to') }" class="position-relative">
                                            <label class="form-label mt-2">Assign to</label>

                                            <!-- Display Selected Users -->
                                            <div class="mt-3 mb-3">
                                                <template x-if="selectedUsers.length > 0">
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <template x-for="id in selectedUsers" :key="id">
                                                            <span class="badge badge-soft-primary px-2 py-1">
                                                                <span x-text="$wire.users[id] ?? 'Unknown'"></span>
                                                                <i class="mdi mdi-close-circle ms-1"
                                                                    style="cursor: pointer;"
                                                                    @click="selectedUsers = selectedUsers.filter(userId => userId != id);
                                               $wire.set('asign_to', selectedUsers)">
                                                                </i>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Dropdown Trigger -->
                                            <div class="border p-2 rounded d-flex justify-content-between align-items-center"
                                                @click="open = !open" style="cursor: pointer;">
                                                <span
                                                    x-text="selectedUsers.length > 0 ? selectedUsers.length + ' selected' : 'Select users'"></span>
                                                <i class="mdi mdi-chevron-down"></i>
                                            </div>

                                            <!-- Dropdown Menu -->
                                            <div x-show="open" @click.outside="open = false"
                                                class="user-select-wrapper p-2 border rounded bg-white position-absolute w-100 shadow mt-1"
                                                style="max-height: 200px; overflow-y: auto; z-index: 1000;">
                                                @foreach ($users as $userid => $username)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:model="asign_to" value="{{ $userid }}"
                                                            id="user_{{ $userid }}" x-model="selectedUsers">
                                                        <label class="form-check-label"
                                                            for="user_{{ $userid }}">
                                                            {{ $username }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div><!-- end row -->
                                </div><!-- end modal-body -->

                                <div class="modal-footer">
                                    <button type="button" wire:click="closeModal"
                                        class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                        Close
                                    </button>
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
                            <form wire:submit.prevent="createTask" class="needs-validation">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="title">Title<span class="text-danger">*</span></label>
                                                <input class="form-control" wire:model="title" type="text"
                                                    autocomplete="off" id="title">
                                                @error('title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="leadStatus">Status<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" wire:model="status_id" id="leadStatus">
                                                    <option value="">Select Status</option>
                                                    @foreach ($taskStatus as $statusid => $statusname)
                                                        <option value="{{ $statusid }}">{{ $statusname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('status_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="start_date">Start Date<span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" wire:model="start_date"
                                                    id="start_date">
                                                @error('start_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="priority">Priority<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" wire:model="priority_id" id="priority">
                                                    <option value="">Select Priority</option>
                                                    @foreach ($priorities as $priorityid => $priorityname)
                                                        <option value="{{ $priorityid }}">{{ $priorityname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('priority_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="due_date">Due Date<span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" wire:model="due_date"
                                                    id="due_date">
                                                @error('due_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group mb-3">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" rows="5" wire:model="description" id="description" placeholder="Description"></textarea>
                                            </div>
                                        </div>

                                        <div x-data="{ open: false, selectedUsers: @entangle('asign_to') }" class="position-relative">
                                            <label class="form-label mt-2">Assign to

                                                <!-- Display Selected Users -->
                                                <div class="mt-3 mb-3">
                                                    <template x-if="selectedUsers.length > 0">
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <template x-for="id in selectedUsers"
                                                                :key="id">
                                                                <span class="badge badge-soft-primary px-2 py-1">
                                                                    <span x-text="$wire.users[id]"></span>
                                                                    <i class="mdi mdi-close-circle ms-1"
                                                                        style="cursor: pointer;"
                                                                        @click="selectedUsers = selectedUsers.filter(userId => userId != id); $wire.set('asign_to', selectedUsers)">
                                                                    </i>
                                                                </span>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </label>

                                            <!-- Dropdown Trigger -->
                                            <div class="border p-2 rounded d-flex justify-content-between align-items-center"
                                                @click="open = !open" style="cursor: pointer;">
                                                <span
                                                    x-text="selectedUsers.length > 0 ? selectedUsers.length + ' selected' : 'Select users'"></span>
                                                <i class="mdi mdi-chevron-down"></i>
                                            </div>

                                            <!-- Dropdown Menu -->
                                            <div x-show="open" @click.outside="open = false"
                                                class="user-select-wrapper p-2 border rounded bg-white position-absolute w-100 shadow mt-1"
                                                style="max-height: 200px; overflow-y: auto; z-index: 1000;">
                                                @foreach ($users as $userid => $username)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:model="asign_to" value="{{ $userid }}"
                                                            id="user_{{ $userid }}" x-model="selectedUsers">
                                                        <label class="form-check-label"
                                                            for="user_{{ $userid }}">
                                                            {{ $username }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>


                                        </div>


                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h4 class="card-title">Attachments</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-grid">
                                                    <div class="form-group mt-2 mb-3">
                                                        <input type="text" class="form-control" id=""
                                                            wire:model="attachedTitle"
                                                            placeholder="Enter Title Here..">
                                                    </div>

                                                    <div x-data="{ isDragging: false }"
                                                        x-on:dragover.prevent="isDragging = true"
                                                        x-on:dragleave.prevent="isDragging = false"
                                                        x-on:drop.prevent="isDragging = false; $wire.uploadMultiple('files', $event.dataTransfer.files)"
                                                        class="border border-2 border-border-soft-secondary p-4 rounded text-center bg-light"
                                                        :class="{ 'border-primary bg-primary bg-opacity-10': isDragging }">

                                                        <input type="file" multiple wire:model="files"
                                                            class="d-none" id="fileInput">
                                                        <label for="fileInput" class="d-block">
                                                            <p class="text-muted" x-show="!isDragging"><i
                                                                    class="fas fa-cloud-upload-alt"></i>Drag & Drop
                                                                files Here or
                                                                Click to Upload</p>
                                                            <p class="text-primary fw-bold" x-show="isDragging">Drop
                                                                the files
                                                                here...</p>
                                                        </label>
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

                                                </div>
                                            </div>
                                        </div>


                                    </div><!-- end row -->
                                </div><!-- end modal-body -->

                                <div class="modal-footer">
                                    <button type="button" wire:click="closeModal"
                                        class="btn btn-outline-secondary btn-sm"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <span wire:loading wire:target="createTask">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Loading...
                                        </span>
                                        <span wire:loading.remove wire:target="createTask">
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
                                            <input type="text" class="form-control" wire:model="attachedTitle"
                                                placeholder="Enter Title Here..">
                                        </div>

                                        <!-- File Upload Section -->
                                        <div x-data="{ isDragging: false }" x-on:dragover.prevent="isDragging = true"
                                            x-on:dragleave.prevent="isDragging = false"
                                            x-on:drop.prevent="isDragging = false; $wire.uploadMultiple('files', $event.dataTransfer.files)"
                                            class="border border-2 border-soft-secondary p-4 rounded text-center bg-light"
                                            :class="{ 'border-primary bg-primary bg-opacity-10': isDragging }">

                                            <!-- Show Loading Icon When File is Being Processed -->
                                            <div wire:loading wire:target="files" class="text-center">
                                                <span class="spinner-border spinner-border-lg text-primary"
                                                    role="status" aria-hidden="true"></span>
                                                <p class="text-muted mt-2">Processing Files...</p>
                                            </div>

                                            <!-- Hide File Input and Label While Uploading -->
                                            <div wire:loading.remove wire:target="files">
                                                <input type="file" multiple wire:model="files" class="d-none"
                                                    id="fileInput">
                                                <label for="fileInput" class="d-block">
                                                    <p class="text-muted" x-show="!isDragging"><i
                                                            class="fas fa-cloud-upload-alt"></i> Drag & Drop files Here
                                                        or Click to Upload</p>
                                                    <p class="text-primary fw-bold" x-show="isDragging">Drop the files
                                                        here...</p>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Show Selected Files After Upload -->
                                        @if ($files)
                                            <div class="mt-3" wire:loading.remove wire:target="files">
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
                                                    <i class="fas fa-paperclip"></i> Attach
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
                Livewire.dispatch('deleteTask', {
                    id: itemId
                }); // Dispatch Livewire event
            }
        });
    }
</script>
@script()
    <!-- Place this at the bottom of your layout or main template -->
@endscript
