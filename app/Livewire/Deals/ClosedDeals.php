<?php

namespace App\Livewire\Deals;

use App\Models\Deal;
use Livewire\Component;

class ClosedDeals extends Component
{

        public $search ="";

    public function mount()
    {
        // $this->leadStatus = LeadStatus::pluck('name', 'id')->all();
        // $this->leadSources = LeadSource::pluck('name', 'id')->all();
        // $this->leadPriorities = LeadPriority::pluck('name', 'id')->all();
        // $this->dealstatus = DealStatus::pluck('name', 'id')->all();
    }


    public function render()
    {
        // $deals = Deal::with(['lead','dealStatus'])->get();
        $query = Deal::with(['lead', 'dealStatus']) ->whereHas('dealStatus', function ($query) {
            $query->where('name', 'Closed Won');
        })
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

        return view('livewire.deals.closed-deals', [
            'deals' => $deals
        ]);
    }



 
}
