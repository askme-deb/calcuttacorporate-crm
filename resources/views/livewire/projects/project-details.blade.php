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
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item"><a href="{{ route('worksheet') }}" wire:navigate>Projects</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">{{ $project->title }}</li>
                            </ol>
                        </div>
                        <h4 class="page-title mt-3">{{ $project->title }}</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Description</h4>
                                </div><!--end col-->
                            </div> <!--end row-->
                        </div><!--end card-header-->

                        <div class="card-body">
                           {!! $project->description !!}
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->

          
            </div><!--end row-->


            <div class="row mb-3">
                <div class="col-lg-6">
                    <h5 class="d-inline-block me-2">Availabel team members.</h5>
                    <div class="img-group d-inline-block">
                        @if ($project->projectTeamMembers->isNotEmpty())
                            @foreach ($project->projectTeamMembers->take(5) as $member)
                                <a class="user-avatar {{ $loop->first ? '' : 'ms-n3' }}" href="#">
                                    <img src="{{ empProfilePicture($member->user_id) }}" alt="user"
                                        class="thumb-xs rounded-circle" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
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

                    {{-- <div class="img-group d-inline-block">
                        <a class="user-avatar" href="#">
                            <img src="assets/images/users/user-8.jpg" alt="user" class="thumb-xs rounded-circle">
                        </a>
                        <a class="user-avatar ms-n3" href="#">
                            <img src="assets/images/users/user-5.jpg" alt="user" class="thumb-xs rounded-circle">
                        </a>
                        <a class="user-avatar ms-n3" href="#">
                            <img src="assets/images/users/user-4.jpg" alt="user" class="thumb-xs rounded-circle">
                        </a>
                        <a class="user-avatar ms-n3" href="#">
                            <img src="assets/images/users/user-6.jpg" alt="user" class="thumb-xs rounded-circle">
                        </a>
                        <a href="" class="user-avatar">
                            <span class="thumb-xs justify-content-center d-flex align-items-center bg-soft-info rounded-circle fw-semibold">+6</span>
                        </a>
                    </div> --}}

                </div><!--end col-->

                <div class="col-lg-6 text-end align-self-center">
                    <button type="button" class="btn btn-sm btn-primary">Add New Task</button>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row mb-3">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Activity</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <div class="" style="height: 660px;" data-simplebar>
                                <div class="activity">
                                    @foreach ($projectLogs as $log)
                                        <div class="activity-info">
                                            <div class="icon-info-activity">
                                                <i class="las la-check-circle bg-soft-primary"></i>
                                            </div>
                                            <div class="activity-info-text">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0 w-75">{{ ucfirst($log->action) }}</h6>
                                                    <span
                                                        class="text-muted d-block">{{ \Carbon\Carbon::parse($log->action_time)->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-muted mt-3">{{ $log->notes }}
                                                    {{-- <a href="#" class="text-info">[more info]</a> --}}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach

                                </div><!--end activity-->
                            </div><!--end activity-scroll-->
                        </div> <!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col">
                                    <p class="text-dark fw-semibold mb-0">Comments
                                        ({{ $project->project_remarks_count }})</p>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div><!--end card-body-->
                        <div class="card-body border-bottom-dashed">
                            <div class="p-3" style="height: 430px;" data-simplebar>
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
                            </div>
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

                        </div><!--end card-body-->
                    </div> <!--end card-->

                </div>
            </div>


            <div class="row">
                <h5 class="d-inline-block me-2 mb-3">All Tasks</h5>
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
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
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

    </div>
    <!-- end page content -->
</div>
