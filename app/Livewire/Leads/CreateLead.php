<?php

namespace App\Livewire\Leads;

use App\Models\Lead;
use App\Models\LeadPriority;
use App\Models\LeadsFollowup;
use App\Models\LeadSector;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class CreateLead extends Component
{
    public $leadStatus = [];
    public $leadSources = [];
    public $leadPriorities = [];
    public $sectors = [];
    public $users = [];

    public $name;
    public $phone;
    public $email;
    public $company;
    public $deal_value;
    public $address;
    public $position;
    public $budget;
    public $notes;
    public $status_id;
    public $source_id;
    public $priority_id;
    public $sector_id;
    public $assigned_to;
    public $next_followup_date;

    public function mount(): void
    {
        $this->leadStatus = LeadStatus::pluck('name', 'id')->all();
        $this->leadSources = LeadSource::pluck('name', 'id')->all();
        $this->leadPriorities = LeadPriority::pluck('name', 'id')->all();
        $this->sectors = LeadSector::pluck('name', 'id')->all();
        $this->users = User::role('Sales')->get();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'deal_value' => 'nullable|numeric|min:0',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status_id' => 'required|exists:lead_status,id',
            'source_id' => 'required|exists:lead_sources,id',
            'priority_id' => 'nullable|exists:lead_priorities,id',
            'sector_id' => 'nullable|exists:lead_sectors,id',
            'assigned_to' => 'nullable|exists:users,id',
            'next_followup_date' => 'nullable|date',
        ];
    }

    public function createLead()
    {
        $validated = $this->validate();

        $lead = Lead::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'company' => $validated['company'] ?? null,
            'deal_value' => $validated['deal_value'] ?? null,
            'address' => $validated['address'] ?? null,
            'position' => $validated['position'] ?? null,
            'budget' => $validated['budget'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status_id' => $validated['status_id'],
            'source_id' => $validated['source_id'],
            'priority_id' => $validated['priority_id'] ?? null,
            'sector_id' => $validated['sector_id'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'next_followup_date' => $validated['next_followup_date'] ?? null,
            'created_by' => Auth::id(),
            'status' => LeadStatus::find($validated['status_id'])?->name ?? 'new',
            'source' => LeadSource::find($validated['source_id'])?->name,
        ]);

        LeadsFollowup::create([
            'status_id' => $lead->status_id,
            'lead_id' => $lead->id,
            'notes' => $lead->notes,
            'next_followup_date' => $lead->next_followup_date,
            'followup_by' => Auth::id(),
            'created_at' => now(),
        ]);

        return redirect()->route('lead.details', ['id' => Crypt::encryptString((string) $lead->id)]);
    }

    public function resetForm(): void
    {
        $this->reset([
            'name',
            'phone',
            'email',
            'company',
            'deal_value',
            'address',
            'position',
            'budget',
            'notes',
            'status_id',
            'source_id',
            'priority_id',
            'sector_id',
            'assigned_to',
            'next_followup_date',
        ]);
    }

    public function render()
    {
        return view('livewire.leads.create-lead');
    }
}
