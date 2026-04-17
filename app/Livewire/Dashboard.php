<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Activitylog\Models\Activity;

class Dashboard extends Component
{
    public $activities, $dealsuccessRate, $totaldeals, $closedDeal, $lostDeals, $totalLeads, $convertedLeads, $junkLeads, $ProposalSent;

    public $visitsData = [];
    public $uniqueVisitsData = [];
    public $leadSources = [];
    public $leadSourceCounts = [];
    public $dealsData = [];
    public $upcomingFollowups = [];
    protected $listeners = ['lockScreen' => 'handleLockScreen'];

    public function handleLockScreen()
    {
        // Handle the lock screen event
        session()->put('locked', true); // Lock the screen in session
        return redirect()->route('lock-screen'); // Redirect to the lock screen page
    }

    public function performAction()
    {
        // Perform your logic here
        $this->dispatch('toastMessage', json_encode([
            'type' => 'info',
            'message' => 'User created successfully'
        ]));
    }

    public function mount()
    {

        $user = Auth::user();
        if ($user->can('view all logs')) {
            // Show all logs if the user has permission
            $this->activities = Activity::latest()->limit(50)->get();
        } else {
            // Show only current user's logs
            $this->activities = Activity::latest()->where('causer_id', $user->id)->limit(50)->get();
        }

        $this->calculateDealSuccessRate();
        $this->leadStatistics();
        $this->fetchChartData();
        $this->fetchUpcomingFollowups();
    }

    public function calculateDealSuccessRate()
    {
        $totalDeals = Deal::count();
        $this->totaldeals = $totalDeals;
        $successfulDeals = Deal::where('status_id', 7)->count();
        $this->closedDeal = $successfulDeals;
        $this->dealsuccessRate = ($totalDeals > 0) ? round(($successfulDeals / $totalDeals) * 100) : 0;
        $this->lostDeals = Deal::where('status_id', 8)->count();
    }

    public function leadStatistics()
    {
        $this->totalLeads = Lead::count();
        // $this->totaldeals = $totalDeals;
        $this->convertedLeads = Lead::where('status_id', 9)->count();
        $this->junkLeads = Lead::where('status_id', 11)->count();
        $this->ProposalSent = Lead::where('status_id', 6)->count();
        // $this->dealsuccessRate = ($totalDeals > 0) ? round(($successfulDeals / $totalDeals) * 100) : 0;
        // $this->lostDeals = Deal::where('status_id', 8)->count();
    }


    public function fetchChartData()
    {
        $this->dealsData = Deal::selectRaw(
            'MONTH(created_at) as month, COUNT(*) as total_deals')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn($item) => [$item->month => ['total' => $item->total_deals, 'closed' => 0]])
            ->toArray();

        // Fetch closed deals separately
        $closedDeals = Deal::selectRaw(
            'MONTH(closing_date) as month, SUM(CASE WHEN status_id = 7 THEN 1 ELSE 0 END) as closed_deals')
            ->whereNotNull('closing_date')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Merge closed deals into the main array
        foreach ($closedDeals as $deal) {
            $month = $deal->month;
            if (isset($this->dealsData[$month])) {
                $this->dealsData[$month]['closed'] = $deal->closed_deals;
            } else {
                $this->dealsData[$month] = [
                    'total' => 0,
                    'closed' => $deal->closed_deals,
                ];
            }
        }

        // Fetching lead source data
        $leadData = Lead::with('leadSource')
            ->select('source_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('source_id')
            ->get();

        // Prepare lead sources names
        $this->leadSources = $leadData->map(function ($lead) {
            return $lead->leadSource ? $lead->leadSource->name : 'Unknown';
        })->toArray();

        // Prepare lead source counts
        $this->leadSourceCounts = $leadData->pluck('count')->toArray();
    }

    public function updateChart()
    {
        $this->dealsData = Deal::selectRaw(
            'MONTH(created_at) as month, COUNT(*) as total_deals, SUM(CASE WHEN status_id = 7 THEN 1 ELSE 0 END) as closed_deals'
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->month => [
                    'total' => $item->total_deals,
                    'closed' => $item->closed_deals,
                ],
            ])
            ->toArray();
        $this->dispatch('dealsUpdated', $this->dealsData);
    }

    public function fetchUpcomingFollowups()
    {
        $user = auth()->user();

        // Start the query
        $query = Lead::whereBetween('next_followup_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('next_followup_date', 'asc');
        
        // Apply condition only if the user cannot view all leads
        if ($user->cannot('View All Leads')) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhere('assigned_to', $user->id);
            });
        }
        
        // Get the results
        $this->upcomingFollowups = $query->get();
        // $this->upcomingFollowups = Lead::whereBetween('next_followup_date', [Carbon::today(), Carbon::today()->addDays(7)])
        //     ->orderBy('next_followup_date', 'asc')
        //     ->get();
    }
    public function render()
    {
        return view('livewire.dashboard');
    }
}
