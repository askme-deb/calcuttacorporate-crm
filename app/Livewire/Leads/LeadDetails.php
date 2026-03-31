<?php

namespace App\Livewire\Leads;

use App\Models\Client;
use App\Models\Deal;
use App\Models\DealStatus;
use App\Models\JobType;
use App\Models\Lead;
use App\Models\LeadLog;
use App\Models\LeadsFollowup;
use App\Models\LeadStatus;
use App\Models\WorkMaster;
use App\Models\Worksheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class LeadDetails extends Component
{
    public $lead, $leadId, $lead_id, $dealName, $amount, $closing_date, $deal_status, $jobtypes, $deal, $leadLogs;
    public $dealForm = false;
    public $workForm = false;
    public $loading = false;
    public $showModal = false;
    public $modalMode = '';
    public $leadStatus, $notes, $next_followup_date, $status_id, $followups, $dealstatus, $worklists,$work_id, $jobtype_id, $customer_deadline;

    protected $listeners = ['deleteItem', 'refreshFollowups' => 'loadFollowups', 'refreshComponent' => '$refresh'];


    public function rules()
    {
        $rules = [];

        // Mode-specific rules
        if ($this->modalMode === 'create') {
            $rules = [
                'status_id' => 'required|numeric',
                'next_followup_date' => 'required',
                'notes' => 'required',
            ];
        } elseif ($this->modalMode === 'edit') {
            $rules = [
                'status_id' => 'required|numeric',
                'next_followup_date' => 'required',
                'notes' => 'required',
            ];
        }

        // Additional rules for workForm
        if ($this->workForm === true) {
            $rules = array_merge($rules, [
                'jobtype_id' => 'required|numeric',
                'work_id' => 'required|numeric',
                'customer_deadline' => 'required',
            ]);
        }

        // Default rules when none of the above apply
        if ($this->dealForm === true) {
            $rules = [
                'amount' => 'required|numeric',
                'dealName' => 'required',
                'status_id' => 'required|numeric',
                'closing_date' => 'required',
            ];
        }

        return $rules;
    }

    public function createNewDealForm($checked)
    {
        if ($checked) {
            $this->loading = true;
            $this->dealForm = true;
            $this->loading = false;
        } else {
            $this->dealForm = false;
        }
    }
    
    public function mount($id)
    {
        try {
            $decryptedId = Crypt::decryptString($id); // Decrypt the ID
            $this->lead = Lead::findOrFail($decryptedId);   // Fetch the item
            // $this->data = [
            //     'name' => $this->user->name,
            //     'email' => $this->user->email,
            // ];
            $this->leadId = $decryptedId;
            $this->leadStatus = LeadStatus::pluck('name', 'id')->all();
            $this->dealstatus = DealStatus::pluck('name', 'id')->all();
            $this->dealName = $this->lead->company;
            $this->worklists = WorkMaster::pluck('name', 'id')->all();
            $this->jobtypes = JobType::pluck('name', 'id')->all();

            $this->followups = LeadsFollowup::where('lead_id', $this->leadId)
            ->with(['leadStatus'])
            ->orderBy('id', 'desc')
            ->get();

            $this->leadLogs = LeadLog::with(['lead', 'user'])
                ->where('lead_id', $this->leadId) // Filter by lead_id
                ->latest()
                ->get();
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => 'Invalid ID'
            ]));
            return redirect()->route('leads')->with('error', 'Invalid ID');
        }

        // $this->roles = Role::pluck('name', 'name')->toArray();
        //$this->selectedRoles = $this->user->roles->pluck('name')->toArray();
    }

    public function render()
    {
        return view('livewire.leads.lead-details');
    }

    public function closeModal()
    {
        $this->reset('status_id');
        $this->reset('notes');
        $this->reset('next_followup_date');
        $this->reset('showModal');
        $this->dispatch('refreshComponent');
    }

    public function followupDetailsPopup()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
    }


    public function createFollowup()
    {
        $this->validate();
        $leadFollowup = LeadsFollowup::create(
            [
                'status_id' => $this->pull('status_id'),
                'lead_id' => $this->leadId,
                'notes' => $this->notes,
                'next_followup_date' => $this->pull('next_followup_date'),
                'followup_by' => Auth::user()->id,
                'created_at' => now()
            ]
        );

            // Find the specific Lead record and update it
            $lead = Lead::find($this->leadId);


            LeadLog::create([
                'lead_id' => $this->leadId,
                'user_id' => auth()->id(),
                'action' => 'followed_up',
                'notes' => $this->notes,
            ]);

            $this->notes = '';

            if ($lead) {
                $lead->update([
                    'status_id' => $leadFollowup->status_id,
                    'notes' => $leadFollowup->notes,
                    'next_followup_date' => $leadFollowup->next_followup_date,
                    'updated_at' => now(),
                ]);
            }

            if($lead->status_id===9){
                $deal = Deal::create(
                    [
                        'status_id' => 7,
                        'lead_id' => $this->leadId,
                        'amount' => $lead->budget,
                        'deal_name' => $lead->name,
                        'closing_date' => now(),
                        'closed_by' => Auth::user()->id,
                    ]
                );

                $client = Client::create(
                    [
                        'name' => $lead->name,
                        'address' => $lead->address,
                        'phone' => $lead->phone,
                        'email' => $lead->email,
                        'company_name' => $lead->company_name,
                        'created_by' => Auth::user()->id,
                    ]
                );


            }


        $this->dispatch('refreshFollowups');
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Updated successfully'
        ]));
        $this->dispatch('refreshComponent');
    }


    public function loadFollowups()
    {
        $this->followups = LeadsFollowup::where('lead_id', $this->leadId)
            ->with(['leadStatus'])
            ->orderBy('id', 'desc')
            ->get();
    }

    public function createDeal(){
       $this->validate();

       $deal_statusData = DealStatus::where('id', $this->status_id)->first();
     
         $deal = Deal::create(
            [
                'status_id' => $this->pull('status_id'),
                'lead_id' => $this->leadId,
                'amount' => $this->pull('amount'),
                'deal_name' => $this->pull('dealName'),
                'closing_date' => $this->pull('closing_date'),
                'closed_by' => Auth::user()->id,
            ]
        );

        if($deal){
            $lead = Lead::find($this->leadId);
            $lead->update([
                'status_id' => 9,
                'updated_at' => now()
            ]);
        }
        //Create a new Client
        if ($deal_statusData->name == 'Closed Won') {

            $client = Client::create(
                [
                    'name' => $this->lead->name,
                    'address' => $this->lead->address,
                    'phone' => $this->lead->phone,
                    'email' => $this->lead->email,
                    'company_name' => $this->lead->company_name,
                    'created_by' => Auth::user()->id,
                ]
            );
        }

        if(!empty($this->jobtype_id)){

            Worksheet::create(
            [
                'jobtype_id' => $this->pull('jobtype_id'),
                'cost' => $deal->amount,
                'lead_id' => $this->leadId,
                'client_id' => $client->id,
                'deal_id' => $deal->id,
                'work_id' => $this->pull('work_id'),
                'customer_deadline' => $this->customer_deadline,
                'start_date' => $deal->closing_date,
                'deadline' => $this->pull('customer_deadline'),
                'created_by' => Auth::user()->id
            ]);
        }




        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Deal Created successfully'
        ]));
        return redirect()->route('leads');

    }


    public function createNewWorkForm($checked){
        if ($checked) {

            $this->dealForm = true; // Show the form
            $this->workForm = true; // Show the form

            $this->loading = false; // Stop loading
        } else {
            $this->dealForm = false; // Hide the form
            $this->workForm = false; // Hide the form
        }
    }
}
