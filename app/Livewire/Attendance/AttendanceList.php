<?php

namespace App\Livewire\Attendance;

use App\Models\Employee;
use App\Models\User;
use Livewire\Component;

class AttendanceList extends Component
{
    public $empployee, $month, $year, $daysOfWeek, $monthNumber, $totalDays, $firstDayOfWeek, $changemonthNumber;

    public $months = [
        '01' => "January", '02' => "February", '03' => "March", '04' => "April",
        '05' => "May", '06' => "June", '07' => "July", '08' => "August",
        '09' => "September", '10' => "October", '11' => "November", '12' => "December"
    ];

    public function mount()
    {
        $this->daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $this->changemonthNumber = date('m');
        $this->year = date('Y');
        $this->updateMonthDetails();
        $this->loadEmployees();
    }

    public function setView($view)
    {
        $this->dispatch('closeDropdown');
        $this->changemonthNumber = $view;
        $this->updateMonthDetails();
    }

    public function updateMonthDetails()
    {
        $this->monthNumber = $this->changemonthNumber;
        $this->month = $this->months[$this->monthNumber];
        $this->totalDays = cal_days_in_month(CAL_GREGORIAN, $this->monthNumber, $this->year);
        $this->firstDayOfWeek = date('w', mktime(0, 0, 0, $this->monthNumber, 1, $this->year));
    }

    public function loadEmployees()
    {
        $excludedRoles = ['Super Admin', 'Admin', 'Manager', 'Director'];

        if (auth()->user()->can('View All Attendance')) {
            $userIds = User::whereDoesntHave('roles', function ($query) use ($excludedRoles) {
                $query->whereIn('name', $excludedRoles);
            })->pluck('id');

            $this->empployee = Employee::whereIn('user_id', $userIds)->get();
        } else {
            $this->empployee = Employee::where('user_id', auth()->id())->get();
        }
    }

    public function render()
    {
        return view('livewire.attendance.attendance-list');
    }
}
