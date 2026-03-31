<?php

namespace App\Livewire\Projects;

use App\Models\Client;
use App\Models\Deal;
use App\Models\InvoiceTime;
use App\Models\JobType;
use App\Models\Lead;
use App\Models\LeadPriority;
use App\Models\PriceType;
use App\Models\ProjectAttachment;
use App\Models\ProjectTeamMember;
use App\Models\User;
use App\Models\WorkMaster;
use App\Models\Worksheet;
use App\Models\WorkStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Notifications\ProjectAssignedNotification;

class ProjectCreate extends Component
{
    use WithFileUploads;

    public $worksheets, $worklists, $jobtypes, $leads, $deals, $price_types, $invoicetimes, $priorities, $users, $clients;
    public $jobtype_id, $client_id, $work_id, $lead_id, $deal_id, $cost, $price_type_id, $priority_id, $customer_deadline, $start_date;
    public $deadline, $description, $remarks, $invoice_time_id, $title, $image, $project, $workstatus,$status_id, $errorMessage, $imagePreview, $attachedTitle;
    public $asign_to  = [];
    public $files = [];
    protected $listeners = ['updateAsignTo'];
    public function updateAsignTo($values)
    {
        $this->asign_to = $values;
    }
    public function updatedImage()
    {
        try {
            $this->validateOnly('image', [
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            // If validation passes, store the image in a temporary preview
            $this->imagePreview = $this->image->temporaryUrl();
            $this->errorMessage = null;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->imagePreview = null;
            $this->errorMessage = $e->validator->errors()->first('image');
        }
    }
    // public function updated($fields)
    // {
    //     $this->validateOnly('image', [
    //         'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    // }
    public function createNewProject()
    {
       // $this->validate();
     $status = WorkStatus::where('id', $this->status_id)->value('name');
  
        $data = array(
            'jobtype_id' => $this->pull('jobtype_id'),
            'work_id' => $this->pull('work_id'),
            'lead_id' => $this->pull('lead_id'),
            'deal_id' => $this->pull('deal_id'),
            'client_id' => $this->pull('client_id'),
            'cost' => $this->pull('cost'),
            'price_type_id' => $this->pull('price_type_id'),
            'priority_id' => $this->pull('priority_id'),
            'start_date' => $this->pull('start_date'),
            'deadline' => $this->pull('deadline'),
            'customer_deadline' => $this->pull('customer_deadline'),
            'description' => $this->pull('description'),
            'invoice_time_id' => $this->pull('invoice_time_id'),
            'title' => $this->pull('title'),
            'status_id' => $this->pull('status_id') ?? 1,
            'created_by' => Auth::user()->id,
        );

        if (strtolower($status) === 'completed') {
            $data['completed_on'] = now();
            $data['completed_by'] = auth()->id(); // Assuming user authentication is enabled
        }

        $this->project = Worksheet::create($data);

        // Assign Team Members
        // if (!empty($this->asign_to)) {
        //     foreach ($this->asign_to as $userId) {
        //         ProjectTeamMember::create([
        //             'project_id' => $this->project->id,
        //             'user_id' => $userId,
        //             'assigned_by' => Auth::id(),
        //             'assigned_on' => now(),
        //         ]);
        //     }

        //     // Send notification to each assigned user
        //     $assignedUsers = User::whereIn('id', array_keys($this->asign_to))->get();
        //     //dd($this->asign_to);
        //     foreach ($this->asign_to as $user) {
        //         $user->notify(new ProjectAssignedNotification($this->project));
        //     }
        //     $this->dispatch('notificationAdded');
        // }
        if (auth()->user()->can('Asign Project')) {
            if (!empty($this->asign_to)) {
                $userIds = collect($this->asign_to)->mapWithKeys(function ($id) {
                    return [(int) $id => ['assigned_by' => auth()->id(), 'assigned_on' => now()]];
                })->toArray();
        
                // Sync team members with pivot data
                $this->project->teamMembers()->sync($userIds);
        
                // Send notification to each assigned user
                $assignedUsers = User::whereIn('id', array_keys($userIds))->get();
                foreach ($assignedUsers as $user) {
                    $user->notify(new ProjectAssignedNotification($this->project));
                }
                $this->dispatch('notificationAdded'); 
            }
        }
        
        if ($this->image) {
            $this->project->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('project');
        }

        $this->image = '';

        if (!empty($this->files)) {
            $projectAttachment = ProjectAttachment::create([
                'project_id' => $this->project->id,
                'title' => $this->pull('attachedTitle'),
                'attached_by' => Auth::id(),
                'attached_on' => now(),
            ]);

            foreach ($this->files as $file) {
                $projectAttachment->addMedia($file->getPathname()) // Use getPathname() instead of getRealPath()
                    ->usingFileName($file->getClientOriginalName()) // Keep original file name
                    ->toMediaCollection('project-attachment'); // Store in 'uploads' collection
            }

            $this->reset('files'); // Clear input after upload
        }

        // $this->dispatch('refreshComponent');
        $this->dispatch('select2Reset');
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Project Created successfully'
        ]));
    }



    public function mount()
    {
        $this->worklists = WorkMaster::pluck('name', 'id')->all();
        $this->jobtypes = JobType::pluck('name', 'id')->all();
        $this->leads = Lead::pluck('name', 'id')->all();
        $this->deals = Deal::pluck('deal_name', 'id')->all();
         $this->clients = Client::pluck('name', 'id')->all();
        $this->price_types = PriceType::pluck('name', 'id')->all();
        $this->invoicetimes = InvoiceTime::pluck('name', 'id')->all();
        $this->priorities = LeadPriority::pluck('name', 'id')->all();
        $this->users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->pluck('name', 'id');
        $this->worksheets = Worksheet::with(['client', 'work'])->get();
        $this->workstatus = WorkStatus::pluck('name', 'id')->all();
    }

    public function render()
    {
        return view('livewire.projects.project-create');
    }
}
