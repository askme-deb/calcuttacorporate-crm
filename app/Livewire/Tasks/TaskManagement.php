<?php

namespace App\Livewire\Tasks;

use App\Models\LeadPriority;
use App\Models\ProjectTask;
use App\Models\TaskAssignment;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\Worksheet;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class TaskManagement extends Component
{
    public $showModal = false;
    public $modalMode = '';
    public  $taskStatus, $priorities, $project, $projectTitle, $users, $alltasks, $taskData, $taskId;
    public  $status_id, $title, $description, $priority_id, $start_date, $due_date, $project_id ;
    public $asign_to  = [];
    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadTasks', 'updateAsignTo'];

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

    
    public function mount($id)
    {
        try {
            $decryptedId = Crypt::decryptString($id); // Decrypt the ID
          //  $this->project = Worksheet::findOrFail($decryptedId);  
          $this->project = Worksheet::with([
            'client',
            'work',
            'projectTeamMembers',
            'projectAttachments.media'
        ])
        ->withCount('projectRemarks')
        ->findOrFail($decryptedId); // Find the project after including relationships
    
            $this->project_id = $decryptedId;
        } catch (DecryptException $e) {
            $this->dispatch('toastMessage', json_encode([
                'type'=>'error',
                'message' => 'Invalid ID'
            ]));
            return redirect()->route('worksheet')->with('error', 'Invalid ID');
        }

        $this->taskStatus = TaskStatus::pluck('name', 'id')->all();
        $this->projectTitle = $this->project->title;
        $this->priorities = LeadPriority::pluck('name', 'id')->all();
        
        $this->loadTasks();
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
    }


    public function loadTasks()
    {
        $user = auth()->user();
    
        if ($user->can('View Projects')) {
            $this->alltasks = ProjectTask::with([
                'taskTeamMembers',
                //'projectAttachments.media'
            ])
            ->where('project_id', $this->project_id)
            ->get();
        } else {
            $this->alltasks = ProjectTask::whereHas('taskTeamMembers', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })
            ->with([
                'taskTeamMembers',
                //'projectAttachments.media'
            ])
            ->where('project_id', $this->project_id)
            ->get();
        }
    }
    }
