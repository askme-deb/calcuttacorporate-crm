<?php

namespace App\Livewire\Hr;

use App\Livewire\Leave\LeaveManagement;
use App\Models\Attendance;
use App\Models\BirthdayWish;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Dashboard extends Component
{

    public $totalEmployees;
    public $activeEmployees;
    public $newHires;
    public $attrition;
    public $payroll;
    public $pendingLeaves;

    public $holidays = [];
    public $birthdays = [];

    public $selectedEmployee = null;
    public $showModal = false;
    public $message = '';
    public $recentWishes = [];

    public $leavesToday = [];
    public $absentees = [];
    public $showHistoryModal = false;
    public $presentCount = 0;
    public $absentCount = 0;
    public $leaveCount = 0;

    public function mount()
    {
        $this->loadKpis();
        $this->loadHolidays();

        $this->loadBirthdays();
        $this->loadRecentWishes();
        $this->loadLeaves();
        $this->loadAbsentees();
        $this->loadAttendanceStats();
    }


public function loadAttendanceStats()
{
    $today = Carbon::today()->toDateString();


    $presentCount = Attendance::whereDate('dated', $today)->count();


    $leaveCount = LeaveApplication::where('status', 2)
        ->whereDate('apply_strt_date', '<=', $today)
        ->whereDate('apply_end_date', '>=', $today)
        ->count();

    $excludedRoles = ['Super Admin', 'Director', 'Manager', 'System User'];
    $totalEmployees = User::where('status', 1)
        ->whereDoesntHave('roles', function ($q) use ($excludedRoles) {
            $q->whereIn('name', $excludedRoles);
        })
        ->count();

    $absentCount = max(0, $totalEmployees - ($presentCount + $leaveCount));

    $this->presentCount = $presentCount;
    $this->absentCount = $absentCount;
    $this->leaveCount = $leaveCount;
}


    public function loadBirthdays()
    {
        $today = Carbon::today();

        $this->birthdays = Employee::all()
            ->map(function ($user) use ($today) {
                $dob = Carbon::parse($user->emp_dob);
                $nextBirthday = $dob->copy()->year($today->year);
                if ($nextBirthday->lt($today)) $nextBirthday->addYear();

                return [
                    'id' => $user->id,
                    'name' => $user->emp_first_name . ' ' . $user->emp_last_name,
                    'day' => $nextBirthday->format('d'),
                    'month' => $nextBirthday->format('M'),
                    'date' => $nextBirthday,
                    'email' => $user->email,
                ];
            })
            ->sortBy('date')
            ->take(5)
            ->values()
            ->toArray();
    }

    public function loadRecentWishes()
    {
        $this->recentWishes = BirthdayWish::latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    public function loadKpis()
    {
        $this->totalEmployees = Employee::count();
        $this->activeEmployees = Employee::where('status', 'active')->count();
        $this->newHires = Employee::whereDate('emp_date_of_joining', '>=', now()->subMonth())->count();
        //$this->attrition = Employee::where('status', 'left')->whereDate('leaving_date', '>=', now()->subMonth())->count();
        // $this->payroll = Payroll::whereMonth('month', now()->month)->sum('amount');
        $this->pendingLeaves = LeaveApplication::where('status', 'pending')->count();
    }

    public function loadHolidays()
    {

        $this->holidays = Holiday::whereNotNull('start_date')
            ->whereDate('start_date', '>=', Carbon::today())
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();
    }

    // public function render()
    // {
    //     return view('livewire.hr.dashboard');
    // }

    public function render()
    {
        $attrition = $this->getAttritionTrend();

        return view('livewire.hr.dashboard', [
            'attritionMonths' => $attrition['months'],
            'attritionData' => $attrition['data'],
        ]);
    }

    public function openModal($employeeId)
    {
        $this->selectedEmployee = Employee::find($employeeId);
        $this->message = "Happy Birthday, {$this->selectedEmployee->emp_first_name}! 🎉"; // default message
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedEmployee = null;
        $this->message = '';
    }

    public function sendBirthdayWish()
    {
        if (!$this->selectedEmployee) return;

        // Send email (or internal messaging)
        // Mail::raw($this->message, function($mail) {
        //     $mail->to($this->selectedEmployee->email)
        //          ->subject("Happy Birthday!");
        // });

        // Log the sent wish
        $currentEmployee = Employee::where('user_id', auth()->id())->first();

        BirthdayWish::create([
            'employee_id' => $this->selectedEmployee->id,  // receiver
            'sent_by'     => $currentEmployee->id,    // sender
            'message'     => $this->message,
            'sent_at'     => now(),
        ]);


        session()->flash('success', 'Birthday wish sent successfully!');

        $this->closeModal();
        $this->loadRecentWishes();
    }


    public function openHistoryModal()
    {
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
    }

    public function loadLeaves()
    {
        $today = Carbon::today();
        $this->leavesToday = LeaveApplication::with('user')
            ->where('status', 2)
            ->whereDate('apply_strt_date', '<=', $today)
            ->whereDate('apply_end_date', '>=', $today)
            ->get();
    }

    public function loadAbsentees()
    {
        $today = Carbon::today()->toDateString();

        $presentUserIds = Attendance::whereDate('dated', $today)
            ->pluck('user_id')
            ->toArray();

        $onLeaveUserIds = LeaveApplication::where('status', 2)
            ->whereDate('apply_strt_date', '<=', $today)
            ->whereDate('apply_end_date', '>=', $today)
            ->pluck('user_id')
            ->toArray();

        $excludedUserIds = array_merge($presentUserIds, $onLeaveUserIds);

        $excludedRoles = ['Super Admin', 'Director', 'Manager', 'System User'];


        $this->absentees = User::where('status', 1)
            ->whereNotIn('id', $excludedUserIds)
            ->whereDoesntHave('roles', function ($q) use ($excludedRoles) {
                $q->whereIn('name', $excludedRoles);
            })
            ->get();
    }

    public function getAttritionTrend()
    {
        $months = collect();
        $attritionData = collect();

        // Last 6 months including current month
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('M');
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();

            // Total employees at the start of the month
            $totalAtStart = Employee::where('emp_date_of_joining', '<=', $monthStart)
                ->count();

            // Employees who left during this month
            $leftCount = Employee::where('status', 'resigned')
                ->whereBetween('updated_at', [$monthStart, $monthEnd])
                ->count();

            // Attrition rate (%)
            $attritionRate = $totalAtStart > 0 ? round(($leftCount / $totalAtStart) * 100, 2) : 0;

            $months->push($month);
            $attritionData->push($attritionRate);
        }

       // dd($attritionData);
        return [
            'months' => $months,
            'data' => $attritionData,
        ];
    }


}

