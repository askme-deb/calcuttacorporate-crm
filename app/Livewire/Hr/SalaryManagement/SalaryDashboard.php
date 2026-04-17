<?php

namespace App\Livewire\Hr\SalaryManagement;

use Livewire\Component;
use App\Models\Payroll;
use Carbon\Carbon;

class SalaryDashboard extends Component
{
    public $totalPaid = 0;
    public $totalUnpaid = 0;
    public $monthlyTrends = [];
    public $paidVsUnpaid = [];
    private $isInitialLoad = true;

    public function mount()
    {
        $this->loadDashboard();
        $this->isInitialLoad = false;
    }

    public function loadDashboard()
    {
        $this->totalPaid = Payroll::where('is_paid', true)->sum('net_salary');
        $this->totalUnpaid = Payroll::where('is_paid', false)->sum('net_salary');

        // Monthly salary trend for last 6 months
        $months = collect(range(0, 5))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->reverse()
            ->values();

        $this->monthlyTrends = $months->map(fn($month) => [
            'month' => $month,
            'paid' => (float) Payroll::where('month', $month)->where('is_paid', true)->sum('net_salary'),
            'unpaid' => (float) Payroll::where('month', $month)->where('is_paid', false)->sum('net_salary'),
        ])->toArray();

        $this->paidVsUnpaid = [
            'paid' => (float) $this->totalPaid,
            'unpaid' => (float) $this->totalUnpaid,
        ];

        // Only dispatch after initial load (for polling updates)
        if (!$this->isInitialLoad) {
            $this->dispatch('update-charts', [
                'monthlyTrends' => $this->monthlyTrends,
                'paidVsUnpaid' => $this->paidVsUnpaid,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.hr.salary-management.salary-dashboard');
    }
}
