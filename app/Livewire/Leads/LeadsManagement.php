<?php

namespace App\Livewire\Leads;

use App\Imports\LeadsImport;
use App\Models\Lead;
use App\Models\LeadLog;
use App\Models\LeadPriority;
use App\Models\LeadsFollowup;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\Url;

class LeadsManagement extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $showModal = false;
    public $modalMode = '';
    public  $leadStatus, $leadSources, $leadPriorities, $leadId;
    public $name, $phone, $email, $address, $status_id, $source_id, $priority_id, $notes, $company, $position, $budget, $next_followup_date, $created_by;
    public $leadData;
    public $search = '';

    public $selectedLeads = [];
    public $selectedUser;
    public $selectAll = false;
    public $users;
    public $sectors;
    use WithFileUploads;

    public $file;

    protected $listeners = [
        'updateSelectedLeads' => 'updateSelectedLeads',
        'deleteItem',
        'refreshComponent' => 'loadLeads'
    ];

    public function updateSelectedLeads($selectedLeads)
    {

        $this->selectedLeads = is_array($selectedLeads) ? $selectedLeads : [];
        //dd($this->selectedLeads);
    }



   public function assignLeads()
   {
       if (!$this->selectedUser || empty($this->selectedLeads)) {
           $this->dispatch('toastMessage', json_encode([
               'type' => 'error',
               'message' => 'Please select leads and a user to assign.'
           ]));
           return;
       }

       foreach ($this->selectedLeads as $leadId) {
           $lead = Lead::find($leadId);

           // Assign new values
           $lead->assigned_to = $this->selectedUser;
           $lead->assigned_by = auth()->id();
           $lead->assigned_on = now();

           // Save only if 'assigned_to' has changed
           if ($lead->isDirty('assigned_to')) {
               $lead->save(); // This will trigger the model's event to log automatically
           }
       }

       $this->dispatch('toastMessage', json_encode([
           'type' => 'success',
           'message' => 'Leads assigned successfully!'
       ]));

       $this->selectedLeads = [];
   }


//    public function assignLeads()
//    {
//        if (!$this->selectedUser || empty($this->selectedLeads)) {
//            $this->dispatch('toastMessage', json_encode([
//                'type' => 'error',
//                'message' => 'Please select leads and a user to assign.'
//            ]));
//            return;
//        }

//        Lead::whereIn('id', $this->selectedLeads)->update([
//            'assigned_to' => $this->selectedUser,
//            'assigned_by' => auth()->id(), // Store the user who assigned the lead
//            'assigned_on' => now(), // Store the current timestamp
//        ]);

//        $this->dispatch('toastMessage', json_encode([
//            'type' => 'success',
//            'message' => 'Leads assigned successfully!'
//        ]));

//        $this->selectedLeads = [];
//    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new LeadsImport, $this->file->getRealPath());

        $this->dispatch('refreshComponent');
        $this->reset('file');
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Lead Imported successfully'
        ]));
    }


    public function rules()
    {
        $rules = [];

        if ($this->modalMode === 'create') {
            $rules = [
                'name' => 'required',
                'phone' => 'required|numeric',
                'source_id' => 'required|numeric',
                'status_id' => 'required|numeric',
                //'next_followup_date' => 'required',
            ];
        } elseif ($this->modalMode === 'edit') {
            $rules = [
                'name' => 'required',
                'phone' => 'required|numeric',
                'source_id' => 'required|numeric',
                'status_id' => 'required|numeric',
                //'next_followup_date' => 'required',
            ];
        }

        return $rules;
    }



    public function addLead()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
    }

    public function createLead()
    {
        $this->validate();
        $lead_Id = Lead::create(
            [
                'name' => $this->pull('name'),
                'email' => $this->pull('email'),
                'phone' => $this->pull('phone'),
                'source_id' => $this->pull('source_id'),
                'status_id' => $this->pull('status_id'),
                'notes' => $this->pull('notes'),
                'address' => $this->pull('address'),
                'company' => $this->pull('company'),
                'position' => $this->pull('position'),
                'budget' => $this->pull('budget'),
                'priority_id' => $this->pull('priority_id'),
                'next_followup_date' => $this->pull('next_followup_date'),
                'created_by' => Auth::user()->id
            ]
        );

        LeadsFollowup::create(
            [
                'status_id' => $lead_Id->status_id,
                'lead_id' => $lead_Id->id,
                'notes' => $lead_Id->notes,
                'next_followup_date' => $lead_Id->next_followup_date,
                'followup_by' => Auth::user()->id,
                'created_at' => now()
            ]
        );

        $this->dispatch('refreshComponent');
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Lead Created successfully'
        ]));
    }


    public function edit($id)
    {
        $this->showModal = true;
        $this->modalMode = 'edit';
        $this->leadId = $id;
        $this->leadData = Lead::findOrFail($id);

        $this->source_id = $this->leadData->source_id;
        $this->status_id = $this->leadData->status_id;
        $this->priority_id = $this->leadData->priority_id;
        $this->name = $this->leadData->name;
        $this->email = $this->leadData->email;
        $this->phone = $this->leadData->phone;
        $this->notes = $this->leadData->notes;
        $this->address = $this->leadData->address;
        $this->company = $this->leadData->company;
        $this->position = $this->leadData->position;
        $this->budget = $this->leadData->budget;
        $this->next_followup_date = $this->leadData->next_followup_date;
        $this->created_by = $this->leadData->created_by;
    }

    public function update()
    {
        $this->validate();
        $record = Lead::findOrFail($this->leadId);
        $record->update([
            'name' => $this->pull('name'),
            'email' => $this->pull('email'),
            'phone' => $this->pull('phone'),
            'source_id' => $this->pull('source_id'),
            'status_id' => $this->pull('status_id'),
            'notes' => $this->pull('notes'),
            'address' => $this->pull('address'),
            'company' => $this->pull('company'),
            'position' => $this->pull('position'),
            'budget' => $this->pull('budget'),
            'priority_id' => $this->pull('priority_id'),
            'next_followup_date' => $this->pull('next_followup_date'),
        ]);
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Data Updated successfully'
        ]));
    }


    public function mount()
    {
        $this->users = User::role('Sales')->get(); // Fetch users with 'Sales' role using Spatie
        $this->leadStatus = LeadStatus::pluck('name', 'id')->all();
        $this->leadSources = LeadSource::pluck('name', 'id')->all();
        $this->leadPriorities = LeadPriority::pluck('name', 'id')->all();
        $this->sectors = \App\Models\LeadSector::pluck('name', 'id')->all();
        // $this->loadLeads();
    }



    public function render()
    {
        $user = auth()->user();

        $query = Lead::with(['leadStatus', 'leadSource', 'leadPriority'])
            ->whereNotIn('id', function ($query) {
                $query->select('lead_id')->from('deals');
            });

        // Apply condition only if the user cannot view all leads
        if ($user->cannot('View All Leads')) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhere('assigned_to', $user->id);
            });
        }

        // Apply search filters
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('leadSource', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('leadStatus', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Apply ordering
        $query->orderBy('id', 'desc');

        // Paginate results
        $leads = $query->paginate(20);

        return view('livewire.leads.leads-management', [
            'leads' => $leads,
        ]);
    }






    public function loadLeads()
    {
        //    return Lead::with(['leadStatus', 'leadSource', 'leadPriority'])
        //     ->orderBy('id', 'desc')
        //     ->get();
    }

    public function deleteItem($id)
    {
        $item = Lead::find($id);
        if ($item) {
            $item->delete();
            $this->dispatch('refreshComponent');
            $this->dispatch('swal:success', json_encode([
                'title' => 'Item Deleted',
                'text' => 'The Data has been successfully deleted.',
                'icon' => 'success',
            ]));
        }
    }

    public function closeModal()
    {
        $this->reset('name');
        $this->reset('email');
        $this->reset('phone');
        $this->reset('source_id');
        $this->reset('status_id');
        $this->reset('notes');
        $this->reset('address');
        $this->reset('company');
        $this->reset('position');
        $this->reset('budget');
        $this->reset('priority_id');
        $this->reset('next_followup_date');
        $this->reset('showModal');
    }

    public function download()
    {
        $filePath = public_path('sample/leads.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        session()->flash('error', 'File not found.');
    }
}
