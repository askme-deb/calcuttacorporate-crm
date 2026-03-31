<?php

namespace App\Livewire\Deals;

use App\Models\Client;
use App\Models\Deal;
use App\Models\DealStatus;
use App\Models\Lead;
use App\Models\LeadPriority;
use App\Models\LeadsFollowup;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DealsManagement extends Component
{
    public $showModal = false;
    public $modalMode = '';
    public  $leadStatus, $leadSources, $leadPriorities, $dealstatus;
    public $name, $phone, $email, $address, $status_id, $source_id, $priority_id, $notes, $company, $position, $budget, $next_followup_date, $created_by;
    public $stage_id, $dealName, $closing_date, $dealId, $dealdData;
    public $search = '';

    public function rules()
    {
        $rules = [];

        if ($this->modalMode === 'create') {
            $rules = [
                'name' => 'required',
                'phone' => 'required|numeric',
                'source_id' => 'required|numeric',
                'status_id' => 'required|numeric',
                'next_followup_date' => 'required',
                'stage_id' => 'required',
                'dealName' => 'required',
                'closing_date' => 'required',
            ];
        } elseif ($this->modalMode === 'edit') {
            $rules = [
                'name' => 'required',
                'phone' => 'required|numeric',
                'source_id' => 'required|numeric',
                'status_id' => 'required|numeric',
                'next_followup_date' => 'required',
                'stage_id' => 'required',
                'dealName' => 'required',
                'closing_date' => 'required',
            ];
        }

        return $rules;
    }

    public function addDeal()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
    }

    public function createDeal()
    {
        $this->validate();

        $dealstatus = DealStatus::where('id', $this->stage_id)->first();

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

        //Create Lead Followup
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

        // Create Deal
        $deal = Deal::create(
            [
                'status_id' => $this->pull('stage_id'),
                'lead_id' => $lead_Id->id,
                'amount' => $this->pull('budget'),
                'deal_name' => $this->pull('dealName'),
                'closing_date' => $this->pull('closing_date'),
                'closed_by' => Auth::user()->id,
            ]
        );


        //Create a new Client
        if ($dealstatus->name === 'Closed Won') {
            $client = Client::create(
                [
                    'name' => $lead_Id->name,
                    'address' => $lead_Id->address,
                    'phone' => $lead_Id->phone,
                    'email' => $lead_Id->email,
                    'company_name' => $lead_Id->company_name,
                    'created_by' => Auth::user()->id,
                ]
            );
        }


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
        $this->dealId = $id;

        // Retrieve deal data with related lead and deal status
        $this->dealdData = Deal::with(['lead', 'dealStatus'])->findOrFail($id);

        $this->closing_date = $this->dealdData->closing_date;
        $this->dealName = $this->dealdData->deal_name;
        $this->stage_id = $this->dealdData->status_id;
        // Ensure lead exists before accessing its properties
        if ($this->dealdData->lead) {
            $this->source_id = $this->dealdData->lead->source_id;
            $this->status_id = $this->dealdData->lead->status_id;
            $this->priority_id = $this->dealdData->lead->priority_id;
            $this->name = $this->dealdData->lead->name;
            $this->email = $this->dealdData->lead->email;
            $this->phone = $this->dealdData->lead->phone;
            $this->notes = $this->dealdData->lead->notes;
            $this->address = $this->dealdData->lead->address;
            $this->company = $this->dealdData->lead->company;
            $this->position = $this->dealdData->lead->position;
            $this->budget = $this->dealdData->lead->budget;
            $this->next_followup_date = date('Y-m-d', strtotime($this->dealdData->lead->next_followup_date));
            $this->created_by = $this->dealdData->lead->created_by;
        }

    }

    public function update()
    {
        $this->validate();
        $dealstatus = DealStatus::where('id', $this->stage_id)->first();
        $record = Deal::findOrFail($this->dealId);
        $record->update([
            'deal_name' => $this->pull('dealName'),
            'closing_date' => $this->pull('closing_date'),
            'status_id' => $this->pull('stage_id'),
            'amount' => $this->budget,
            'updated_at' => now(),
        ]);

        //Create a new Client
        if ($dealstatus->name === 'Closed Won') {
            $client = Client::create(
                [
                    'name' => $this->name,
                    'address' => $this->address,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'company_name' => $this->company,
                    'created_by' => Auth::user()->id,
                ]
            );
        }

        $leadrecord = Lead::findOrFail($record->lead_id);
        $leadrecord->update([
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
        $this->leadStatus = LeadStatus::pluck('name', 'id')->all();
        $this->leadSources = LeadSource::pluck('name', 'id')->all();
        $this->leadPriorities = LeadPriority::pluck('name', 'id')->all();
        $this->dealstatus = DealStatus::pluck('name', 'id')->all();
    }


    public function render()
    {
        // $deals = Deal::with(['lead','dealStatus'])->get();
        $query = Deal::with(['lead', 'dealStatus'])
        // ->whereNotIn('id', function ($query) {
        //     $query->select('lead_id')->from('deals');
        // })
        ->orderBy('id', 'desc');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('deal_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('lead', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('lead', function ($query) {
                        $query->where('phone', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('lead', function ($query) {
                        $query->where('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('dealStatus', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $deals = $query->paginate(20);

        return view('livewire.deals.deals-management', [
            'deals' => $deals
        ]);
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
        $this->reset('stage_id');
        $this->reset('dealName');
        $this->reset('closing_date');
        $this->reset('showModal');

    }

}

