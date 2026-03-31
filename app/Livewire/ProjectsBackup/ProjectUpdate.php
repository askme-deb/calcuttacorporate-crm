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
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Notifications\ProjectAssignedNotification;

class ProjectUpdate extends Component
{
    use WithFileUploads;
    public $worksheets, $worklists, $jobtypes, $leads, $deals, $price_types, $invoicetimes, $priorities, $users, $clients;
    public $jobtype_id, $client_id, $work_id, $lead_id, $deal_id, $cost, $price_type_id, $priority_id, $customer_deadline, $start_date;
    public $deadline, $description, $remarks, $invoice_time_id, $title, $image;
    public $worksheetId, $project, $projectimage, $status_id, $workstatus, $errorMessage, $imagePreview, $attachedTitle;

    public $asign_to  = [];
    public $files = [];
    protected $listeners = ['updateAsignTo'];

    public function updateAsignTo($values)
    {
        $this->asign_to = $values;
        $this->dispatch('asignToUpdated');
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

    public function mount($id)
    {
        try {
            $decryptedId = Crypt::decryptString($id); // Decrypt the ID
            $this->project = Worksheet::findOrFail($decryptedId);   // Fetch the item
            // $this->data = [
            //     'name' => $this->user->name,
            //     'email' => $this->user->email,
            // ];
            $this->worksheetId = $decryptedId;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $this->dispatch('toastMessage', json_encode([
                'type'=>'error',
                'message' => 'Invalid ID'
            ]));
            return redirect()->route('users')->with('error', 'Invalid ID');
        }
        $this->worklists = WorkMaster::pluck('name', 'id')->all();
        $this->jobtypes = JobType::pluck('name', 'id')->all();
        $this->leads = Lead::pluck('name', 'id')->all();
        $this->deals = Deal::pluck('deal_name', 'id')->all();
        $this->clients = Client::pluck('name', 'id')->all();
        $this->price_types = PriceType::pluck('name', 'id')->all();
        $this->invoicetimes = InvoiceTime::pluck('name', 'id')->all();
        $this->priorities = LeadPriority::pluck('name', 'id')->all();
        // $this->users = User::pluck('name', 'id')->all();
        $this->users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->pluck('name', 'id');

        $this->worksheets = Worksheet::with(['client','work'])->get();

        $this->title = $this->project->title;
        $this->jobtype_id = $this->project->jobtype_id;
        $this->work_id = $this->project->work_id;
        $this->lead_id = $this->project->lead_id;
        $this->deal_id = $this->project->deal_id;
         $this->client_id = $this->project->client_id;
        $this->cost = $this->project->cost;
        $this->price_type_id = $this->project->price_type_id;
        $this->priority_id = $this->project->priority_id;
        $this->start_date = $this->project->start_date;
        $this->deadline = $this->project->deadline;
        $this->customer_deadline = $this->project->customer_deadline;
        $this->description = $this->project->description;
        $this->invoice_time_id = $this->project->invoice_time_id;
       // $this->asign_to = $this->project->asign_to;
        $this->status_id = $this->project->status_id;
        $this->projectimage = $this->project->getFirstMediaUrl('project');


        $this->asign_to = ProjectTeamMember::where('project_id', $this->project->id)
    ->pluck('user_id');

        $this->workstatus = WorkStatus::pluck('name', 'id')->all();


    }

    public function updateProject()
    {
        //$this->validate();
         $status = WorkStatus::where('id', $this->status_id)->value('name');
         
        $data = [
            'jobtype_id' => $this->jobtype_id,
            'work_id' => $this->work_id,
            'lead_id' => $this->lead_id,
            'deal_id' => $this->deal_id,
            'client_id' => !empty($this->client_id) ? $this->client_id : null,
            'cost' => $this->cost,
            'price_type_id' => $this->price_type_id,
            'priority_id' => $this->priority_id,
            'start_date' => $this->start_date,
            'deadline' => $this->deadline,
            'customer_deadline' => $this->customer_deadline,
            'description' => $this->description,
            'invoice_time_id' => $this->invoice_time_id,
            'title' => $this->title,
            'status_id' => $this->status_id ?? 1,
        ];

         if (strtolower($status) === 'completed') {

            $data['completed_on'] = now();
            $data['completed_by'] = auth()->id(); // Assuming user authentication is enabled
         }

        $record = Worksheet::findOrFail($this->worksheetId);
        $record->update($data);

        // Handle Image Upload
        if ($this->image) {
            $this->project->clearMediaCollection('project');
            $this->project->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('project');
        }

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
        
        // Reset Image Field
        $this->image = '';


        if (!empty($this->files)) {
            $projectAttachment = ProjectAttachment::create([
                'project_id' => $this->worksheetId,
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


        // Success Toast Message
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Project Updated successfully'
        ]));
        return redirect()->route('worksheet');

    }

    // public function updateProject(){
    //     //$this->validate();

    //     $data = array(
    //         'jobtype_id' => $this->jobtype_id,
    //         'work_id' => $this->work_id,
    //         'lead_id' => $this->lead_id,
    //         'deal_id' => $this->deal_id,
    //         'cost' => $this->cost,
    //         'price_type_id' => $this->price_type_id,
    //         'priority_id' => $this->priority_id,
    //         'start_date' => $this->start_date,
    //         'deadline' => $this->deadline,
    //         'description' => $this->description,
    //         'invoice_time_id' => $this->invoice_time_id,
    //         'title' => $this->title,
    //     );
    //         if(!empty('asign_to')){
    //             $data['asign_to'] = $this->asign_to;
    //             $data['asign_by'] = Auth::user()->id;
    //             $data['asigned_on'] = date('Y-m-d');
    //         }

    //         $record = Worksheet::findOrFail($this->worksheetId);
    //         $record->update($data);

    //     if ($this->image) {
    //         $this->project->addMedia($this->image->getRealPath())
    //     ->usingFileName($this->image->getClientOriginalName())
    //     ->toMediaCollection('project');
    //     }


    //     if (!empty($this->asign_to)) {
    //         foreach ($this->asign_to as $userId) {
    //             ProjectTeamMember::create([
    //                 'project_id' => $this->project->id,
    //                 'user_id' => $userId,
    //                 'asigned_by' => Auth::id(),
    //                 'asigned_on' => now(),
    //             ]);
    //         }
    //     }

    //     $this->image = '';
    //     // $this->dispatch('refreshComponent');
    //     $this->dispatch('toastMessage', json_encode([
    //         'type'=>'success',
    //         'message' => 'Project Updated successfully'
    //     ]));
    // }


    public function render()
    {
        return view('livewire.projects.project-update');
    }
}
