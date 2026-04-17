<?php

namespace App\Livewire\Tasks;

use App\Models\LeadPriority;
use App\Models\ProjectTask;
use App\Models\TaskAssignment;
use App\Models\TaskAttachment;
use App\Models\TaskRemark;
use App\Models\TaskRemarks;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\Worksheet;
use App\Notifications\MediaAttachmentNotification;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskRemarkNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TaskManagement extends Component
{
    use WithFileUploads;
    public $showModal = false;
    public $modalMode = '';
    public  $taskStatus, $priorities, $project, $projectTitle, $users, $alltasks, $taskData, $taskId;
    public  $status_id, $title, $description, $priority_id, $start_date, $due_date, $project_id, $attachedTitle, $allAttachments, $totalAttachmentsCount;
    public $asign_to  = [];
    public $files = [];
    public $attachmentsModal = false, $remarksModal=false;
    public $workstatus, $remarks, $allremarks, $totalRemarksCount;
    protected $listeners = ['deleteTask', 'refreshComponent' => 'loadTasks', 'updateAsignTo'];

    public function updateAsignTo($values)
    {
        $this->asign_to = $values;
    }
    public function rules()
    {
        $rules = [];

        if ($this->modalMode === 'create') {
            $rules = [
                'title' => 'required|string|max:255',
                'status_id' => 'required|integer',
                'priority_id' => 'required|integer',
                'start_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:start_date',
                'asign_to' => 'nullable|array',
                'asign_to.*' => 'exists:users,id',
                ];
        } elseif ($this->modalMode === 'edit') {
            $rules = [
                'title' => 'required|string|max:255',
                'status_id' => 'required|integer',
                'priority_id' => 'required|integer',
                'start_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:start_date',
                'asign_to' => 'nullable|array',
                'asign_to.*' => 'exists:users,id',
            ];
        }

        return $rules;
    }


    public function addTask()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
        $this->users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->pluck('name', 'id');

        // Dispatch event to notify JavaScript to initialize Select2
        $this->dispatch('show-modal');
    }


    public function createTask()
    {
        $this->validate();
        $task_Id = ProjectTask::create(
            [
                'project_id' => $this->project_id,
                'title' => $this->pull('title'),
                'description' => $this->pull('description'),
                'status_id' => $this->pull('status_id'),
                'priority_id' => $this->pull('priority_id'),
                'start_date' => $this->pull('start_date'),
                'due_date' => $this->pull('due_date'),
                'created_by' => Auth::user()->id
            ]
        );

        // if (!empty($this->asign_to)) {
        //     foreach ($this->asign_to as $userId) {
        //         TaskAssignment::create([
        //             'task_id' => $task_Id->id,
        //             'assigned_to' => $userId,
        //             'assigned_by' => Auth::id(),
        //             'assigned_on' => now(),
        //         ]);
        //     }
        // }

        // Sync assigned users in the pivot table with pivot data
        if (!empty($this->asign_to)) {
            $userIds = collect($this->asign_to)->mapWithKeys(function ($id) {
                return [(int) $id => ['assigned_by' => auth()->id(), 'assigned_on' => now()]];
            })->toArray();

            // Ensure we're using the correct $task object
            $task_Id->teamMembers()->sync($userIds);

            // Send notification to each assigned user
            $assignedUsers = User::whereIn('id', array_keys($userIds))->get();
            foreach ($assignedUsers as $user) {
                $user->notify(new TaskAssignedNotification($task_Id));
            }
            $this->dispatch('notificationAdded');
        } else {
            $task_Id->teamMembers()->detach(); // Remove all assignments if empty
        }



        $projectAttachment = TaskAttachment::create([
            'task_id' => $task_Id->id,
            'title' => $this->pull('attachedTitle'),
            'attached_by' => Auth::id(),
            'attached_on' => now(),
        ]);

        foreach ($this->files as $file) {
            $projectAttachment->addMedia($file->getPathname()) // Use getPathname() instead of getRealPath()
                ->usingFileName($file->getClientOriginalName()) // Keep original file name
                ->toMediaCollection('task-attachment'); // Store in 'uploads' collection
        }

        $this->reset('files'); // Clear input after upload

        $this->dispatch('refreshComponent');
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Task Created successfully'
        ]));
    }

    public function edit($id)
    {
        $this->showModal = true;
        $this->modalMode = 'edit';
        $this->taskId = $id;
        $this->taskData = ProjectTask::findOrFail($id);

        $this->title = $this->taskData->title;
        $this->description = $this->taskData->description;
        $this->status_id = $this->taskData->status_id;
        $this->priority_id = $this->taskData->priority_id;
        $this->start_date = $this->taskData->start_date;
        $this->due_date = $this->taskData->due_date;

        // Fetch assigned users for the task
        $this->asign_to = TaskAssignment::where('task_id', $this->taskId)->pluck('assigned_to')->toArray();

        // Fetch all users excluding 'Super Admin'
        $this->users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->pluck('name', 'id')->toArray();
    }

    public function update()
    {
        $this->validate();

        // Find the task and update its attributes
        $task = ProjectTask::findOrFail($this->taskId);
        $task->update([
            'title' => $this->title,
            'description' => $this->description,
            'status_id' => $this->status_id,
            'priority_id' => $this->priority_id,
            'start_date' => $this->start_date,
            'due_date' => $this->due_date,
        ]);

        // Sync assigned users in the pivot table with pivot data
        if (!empty($this->asign_to)) {
            $userIds = collect($this->asign_to)->mapWithKeys(function ($id) {
                return [(int) $id => ['assigned_by' => auth()->id(), 'assigned_on' => now()]];
            })->toArray();

            // Ensure we're using the correct $task object
            $task->teamMembers()->sync($userIds);

            // Send notification to each assigned user
            $assignedUsers = User::whereIn('id', array_keys($userIds))->get();
            foreach ($assignedUsers as $user) {
                $user->notify(new TaskAssignedNotification($task));
            }
            $this->dispatch('notificationAdded');
        } else {
            $task->teamMembers()->detach(); // Remove all assignments if empty
        }

        // Refresh the component
        $this->dispatch('refreshComponent');

        // Close the modal
        $this->closeModal();

        // Show success toast message
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Task updated successfully'
        ]));
    }


    // public function mount($id)
    // {
    //     try {
    //         $decryptedId = Crypt::decryptString($id); // Decrypt the ID
    //         $this->loadTasks();
    //     } catch (DecryptException $e) {
    //         $this->dispatch('toastMessage', json_encode([
    //             'type'=>'error',
    //             'message' => 'Invalid ID'
    //         ]));
    //         return redirect()->route('worksheet')->with('error', 'Invalid ID');
    //     }

    //     $this->taskStatus = TaskStatus::pluck('name', 'id')->all();
    //     $this->projectTitle = $this->project->title;
    //     $this->priorities = LeadPriority::pluck('name', 'id')->all();

    //    // $this->loadTasks();
    // }
    public function mount($id)
    {
        try {
            $decryptedId = Crypt::decryptString($id); // Decrypt the ID
            $this->project = Worksheet::findOrFail($decryptedId); // Fetch project
            $this->project_id = $this->project->id; // Assign project ID
        } catch (DecryptException $e) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => 'Invalid ID'
            ]));
            return redirect()->route('worksheet')->with('error', 'Invalid ID');
        }
    
        $this->taskStatus = TaskStatus::pluck('name', 'id')->all();
        $this->projectTitle = $this->project->title;
        $this->priorities = LeadPriority::pluck('name', 'id')->all();
    
        $this->loadTasks(); // Load tasks after setting project_id
    }

    public function render()
    {
        return view('livewire.tasks.task-management');
    }


    public function closeModal()
    {

        $this->reset('title');
        $this->reset('status_id');
        $this->reset('description');
        $this->reset('priority_id');
        $this->reset('start_date');
        $this->reset('due_date');
        $this->reset('showModal');
        $this->reset('asign_to');
        $this->reset('remarksModal');
        $this->reset('attachmentsModal');

    }

    public function openAttachments($tid)
    {
        $this->taskId = $tid;
       // dd($this->taskId);
        $this->attachmentsModal = true;

        // Fetch attachments with media and attachedBy relation
        $attachments = TaskAttachment::with('attachedBy')
            ->where('task_id', $this->taskId)
            ->latest()
            ->get();

        // Transform attachments
        $this->allAttachments = $attachments->map(function ($attachment) {
            return [
                'id' => $attachment->id,
                'title' => $attachment->title,
                'attached_by' => $attachment->attached_by,
                'attached_on' => $attachment->attached_on,
                'media' => $attachment->getMedia('task-attachment')->map(function ($media) {
                    return [
                        'url' => $media->getUrl(),
                        'name' => $media->file_name,
                        'id' => $media->id,
                    ];
                })->toArray(),
            ];
        });

        // Get total count of all media across all attachments
        $this->totalAttachmentsCount = $this->allAttachments->sum(fn($attachment) => count($attachment['media']));
    }

    public function download($mediaId, $fileName)
    {
        $media = Media::find($mediaId);
        if (!$media) {
            abort(404, 'Media not found');
        }
        $path = $media->getPath();
        if (!file_exists($path)) {
            abort(404, 'File not found on disk');
        }

        $this->dispatch('download-complete');
        return response()->download($path, $fileName);
    }


    public function newAttachment(){
      //  dd($this->projectId);
        $projectAttachment = TaskAttachment::create([
            'task_id' => $this->taskId,
            'title' => $this->pull('attachedTitle'),
            'attached_by' => Auth::id(),
            'attached_on' => now(),
        ]);

        foreach ($this->files as $file) {
            $projectAttachment->addMedia($file->getPathname()) // Use getPathname() instead of getRealPath()
                ->usingFileName($file->getClientOriginalName()) // Keep original file name
                ->toMediaCollection('task-attachment'); // Store in 'uploads' collection
        }

        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Uploaded successfully'
        ]));
        $this->reset('files');

        $project = ProjectTask::findOrFail($this->taskId);
        $userIds = TaskAssignment::where('task_id', $this->taskId)
            ->pluck('assigned_to')
            ->toArray(); // Fetch user IDs as an array
        // Fetch assigned users
        $assignedUsers = User::whereIn('id', $userIds)->get();
        // Send notification to each assigned user
        foreach ($assignedUsers as $user) {
            $user->notify(new MediaAttachmentNotification($project));
        }
        // Dispatch event for notification update
        $this->dispatch('notificationAdded');
        $this->openAttachments($this->taskId);
    }


    public function openRemarksForm($taskid){
        $this->taskId = $taskid;
        $this->remarksModal = true;
        $this->allremarks = TaskRemark::where('task_id', $this->taskId)
            ->latest()
            ->with(['commenter'])
            ->tap(function ($query) {
                $this->totalRemarksCount = $query->count();
            })
            ->get()
            ->map(function ($remark) {
                $remark->time_ago = Carbon::parse($remark->created_at)->diffForHumans();
                return $remark;
            });
    }

    
    public function saveRemarks(){
        TaskRemark::create(
            [
                'user_id' => Auth::user()->id,
               // 'status_id' => $this->status_id,
                'task_id' => $this->taskId,
                'remarks' => $this->pull('remarks'),
                'is_visible' => 1,
            ]
        );

        // Fetch the task
        $task = ProjectTask::findOrFail($this->taskId);
        // Roles that should receive notifications
        $roles = ['Manager', 'Super Admin', 'Team Lead']; // Add any other roles here

        // Fetch users who have any of the specified roles
        $recipients = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();

        // Notify all recipients
        foreach ($recipients as $recipient) {
            $recipient->notify(new TaskRemarkNotification($task, Auth::user()));
        }

        $this->dispatch('refreshComponent')->to(self::class);
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Remarks Posted successfully'
        ]));
       // redirect()->route('worksheet');
      // return redirect()->to(url()->previous());
       //return redirect()->to(url()->previous());
    }



    
    public function loadTasks()
    {
        $user = auth()->user();
    
        if ($user->can('View Tasks')) {
            $this->alltasks = ProjectTask::with([
                'taskTeamMembers',
                'taskAttachments.media'
            ])
            ->where('project_id', $this->project_id)
            ->withCount('taskRemarks')
            ->get();
        } else {
            $this->alltasks = ProjectTask::whereHas('taskTeamMembers', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })
            ->with([
                'taskTeamMembers',
                'taskAttachments.media'
            ])
            ->where('project_id', $this->project_id)
            ->withCount('taskRemarks')
            ->get();
        }
    }



    public function deleteTask($id)
    {
        $user = auth()->user();

        $task = ProjectTask::with([
            'taskTeamMembers',
            'taskAttachments.media',
            'taskRemarks'
        ])->findOrFail($id);


        if (! $user->can('Delete Tasks')) {
            $isTeamMember = $task->taskTeamMembers->contains('assigned_to', $user->id);

            if (! $isTeamMember) {
                abort(403, 'You are not authorized to delete this task.');
            }
        }

        $task->taskTeamMembers()->delete();
        foreach ($task->taskAttachments as $attachment) {
            if ($attachment->hasMedia()) {
                $attachment->clearMediaCollection(); 
            }
            $attachment->delete();
        }
        $task->taskRemarks()->delete();
        $task->delete();
        $this->dispatch('refreshComponent');
        $this->dispatch('swal:success', json_encode([
            'title' => 'Item Deleted',
            'text' => 'Task and related data deleted successfully.',
            'icon' => 'success',
        ]));
    }


    }
