<?php

namespace App\Livewire\Leads;

use App\Models\Lead;
use Livewire\Component;

class ConvertedLeads extends Component
{

    public function mount()
    {
    }



    public function render()
    {
        $query = Lead::with(['leadStatus', 'leadSource', 'leadPriority'])
            ->whereIn('id', function ($query) {
                $query->select('lead_id')->from('deals');
            })
            ->orderBy('id', 'desc');

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

        $leadss = $query->paginate(20);

        return view('livewire.leads.converted-leads', [
            'leads' => $leadss,
        ]);
        return view('livewire.leads.converted-leads');
    }  

}
