<?php

namespace App\Livewire\Hr\SalaryManagement;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\Attendance;
use App\Models\SalaryComponentMaster;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalarySetup extends Component
{
    public $employees;
    public $masterComponents = []; // <- Added for dropdown
    public $basic_salary = 0;
    public $components = [];
    public $selectedMonth;
    public $selectedEmployeeId = null;

    // Salary Summary
    public $totalAllowance = 0;
    public $totalDeduction = 0;
    public $netSalary = 0;
    public $netSalaryClass = '';
    public $rowHighlight = [];

    // Attendance
    public $workingDays = 0;
    public $lateCount = 0;
public $showConfirmBulkCopy = false;
public $absentDays = 0;

public $bulkCopySummary = [
    'totalPrev' => 0,
    'alreadyExists' => 0,
    'willBeCopied' => 0,
];

public $totalCalendarDays = 0;
public $nonWorkingDaysCount = 0;
public $holidayDaysCount = 0;

public $presentDays = 0;

public $totalCasualLeavesForYear = 0;
public $totalCasualLeavesUsedThisYear = 0;
public $casualLeaveBalance = 0;

public $casualLeavesUsedThisMonth = 0;
public $medicalLeavesUsedThisMonth = 0;
public $halfDayLeavesUsedThisMonth = 0;


    public function mount()
    {
        $this->employees = Employee::all();
        $this->masterComponents = SalaryComponentMaster::where('is_active', 1)->get();
        $this->selectedMonth = now()->format('Y-m');
        $this->calculateTotals();
    }

    public function selectEmployee($id)
    {
        $this->selectedEmployeeId = $id;

        $salary = Salary::with('components')
            ->where('employee_id', $id)
            ->where('month', $this->selectedMonth)
            ->first();

        $this->basic_salary = $salary->basic_salary ?? 0;
        $this->components = $salary?->components->toArray() ?? [];

        // Calculate attendance & late penalty
        $this->updateAttendancePenalty();
    }

    public function updatedSelectedMonth()
    {
        if ($this->selectedEmployeeId) {
            $this->selectEmployee($this->selectedEmployeeId);
        }
    }

    public function updatedBasicSalary()
    {
        $this->recalculatePercentages();
        $this->updateAttendancePenalty();
    }

    public function updatedComponents()
    {
        $this->recalculatePercentages();
        $this->updateAttendancePenalty();
    }

    /**
     * Calculate working days & auto add late penalty deduction
     */
    // private function updateAttendancePenalty()
    // {
    //     if (!$this->selectedEmployeeId || !$this->selectedMonth) {
    //         $this->calculateTotals();
    //         return;
    //     }

    //     $employee = Employee::find($this->selectedEmployeeId);
    //     if (!$employee) return;

    //     [$year, $month] = explode('-', $this->selectedMonth);

    //     $attendance = Attendance::where('user_id', $employee->user_id)
    //         ->whereYear('dated', $year)
    //         ->whereMonth('dated', $month)
    //         ->get();

    //     // Working days = all non-absent
    //     $this->workingDays = $attendance->where('status', '!=', 'absent')->count();
    //     $this->lateCount = $attendance->where('status', 'late')->count();

    //     $latePenaltyDays = floor($this->lateCount / 3);

    //     $perDaySalary = $this->basic_salary > 0 && $this->workingDays > 0
    //         ? $this->basic_salary / $this->workingDays
    //         : 0;

    //     $lateDeduction = $latePenaltyDays * $perDaySalary;

    //     // Add/Update "Late Penalty" deduction
    //     $key = array_search('Late Penalty', array_column($this->components, 'name'));

    //     if ($lateDeduction > 0) {
    //         if ($key === false) {
    //             $this->components[] = [
    //                 'type' => 'deduction',
    //                 'name' => 'Late Penalty',
    //                 'amount' => $lateDeduction,
    //                 'percentage' => null
    //             ];
    //         } else {
    //             $this->components[$key]['amount'] = $lateDeduction;
    //         }
    //     } elseif ($key !== false) {
    //         unset($this->components[$key]);
    //         $this->components = array_values($this->components);
    //     }

    //     $this->calculateTotals();
    // }
private function updateAttendancePenalty()
{
    if (!$this->selectedEmployeeId || !$this->selectedMonth) {
        $this->calculateTotals();
        return;
    }

    $employee = Employee::find($this->selectedEmployeeId);
    if (!$employee) return;

    [$year, $month] = explode('-', $this->selectedMonth);
    $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endDate = $startDate->copy()->endOfMonth();

    /** -----------------------
     * 1. Generate ALL calendar dates for the month
     * ----------------------*/
    $allDates = [];
    $date = $startDate->copy();
    while ($date->lte($endDate)) {
        $allDates[] = $date->toDateString();
        $date->addDay();
    }

    /** -----------------------
     * 2. Identify NON-working days (Sundays + 2nd/4th Saturdays)
     * ----------------------*/
    $nonWorkingDays = [];
    $saturdays = [];
    
    $date = $startDate->copy();
    while ($date->lte($endDate)) {
        if ($date->isSunday()) {
            $nonWorkingDays[] = $date->toDateString();
        } elseif ($date->isSaturday()) {
            $saturdays[] = $date->toDateString();
        }
        $date->addDay();
    }
    
    // Mark 2nd and 4th Saturdays as non-working days
    // Index 1 = 2nd Saturday, Index 3 = 4th Saturday
    foreach ($saturdays as $index => $saturdayDate) {
        if (in_array($index, [1, 3])) { // 0-based index: 1=2nd, 3=4th
            $nonWorkingDays[] = $saturdayDate;
        }
    }

    /** -----------------------
     * 3. Get holiday dates
     * ----------------------*/
    $holidayDates = [];
    $holidays = \DB::table('holidays')
        ->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
              ->orWhereBetween('end_date', [$startDate->toDateString(), $endDate->toDateString()])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate->toDateString())
                     ->where('end_date', '>=', $endDate->toDateString());
              });
        })
        ->get();

    foreach ($holidays as $holiday) {
        $hStart = Carbon::parse($holiday->start_date);
        $hEnd = Carbon::parse($holiday->end_date ?? $holiday->start_date);

        while ($hStart->lte($hEnd)) {
            if ($hStart->between($startDate, $endDate)) {
                $holidayDates[] = $hStart->toDateString();
            }
            $hStart->addDay();
        }
    }

    /** -----------------------
     * 4. Calculate actual working days
     * ----------------------*/
    $workingDays = array_diff($allDates, $nonWorkingDays, $holidayDates);
    $workingDays = array_values($workingDays); // Re-index array

    /** -----------------------
     * 5. Check employee eligibility and calculate leave balance
     * ----------------------*/
    $joiningDate = Carbon::parse($employee->emp_date_of_joining);
    $currentDate = Carbon::now();
    $isEligibleForLeave = $joiningDate->diffInMonths($currentDate) >= 6;
    
    // Calculate leave eligibility start date (6 months after joining)
    $leaveEligibilityDate = $joiningDate->copy()->addMonths(6);
    
    // Adjust eligibility based on date within month
    $actualLeaveStartDate = $leaveEligibilityDate->copy();
    if ($leaveEligibilityDate->day > 10) {
        // If eligibility date is after 10th, move to next month
        $actualLeaveStartDate = $leaveEligibilityDate->copy()->addMonth()->startOfMonth();
    } else {
        // If eligibility date is on or before 10th, can start from same month
        $actualLeaveStartDate = $leaveEligibilityDate->copy()->startOfMonth();
    }
    
    // Check if current month is eligible for leaves
    $isCurrentMonthEligible = $startDate->gte($actualLeaveStartDate);
    
    // Current calendar year
    $currentYear = $currentDate->year;
    $selectedYear = $startDate->year;

    /** -----------------------
     * 6. Calculate casual leave balance for the year
     * ----------------------*/
    $casualLeaveBalance = 0;
    $totalCasualLeavesForYear = 0;
    
    if ($isEligibleForLeave && $isCurrentMonthEligible) {
        // Leave balance resets every year - use current year's dates
        $currentYearStart = Carbon::createFromDate($selectedYear, 1, 1);
        $actualLeaveStartThisYear = $actualLeaveStartDate->year == $selectedYear 
            ? $actualLeaveStartDate 
            : $currentYearStart;
        
        $currentMonthEnd = $endDate; // Current selected month end
        
        // Count eligible months from leave start to current month IN THIS YEAR
        $eligibleMonthsCount = 0;
        $tempDate = $actualLeaveStartThisYear->copy()->startOfMonth();
        while ($tempDate->lte($currentMonthEnd)) {
            $eligibleMonthsCount++;
            $tempDate->addMonth();
        }
        
        // Total casual leaves allocated for this year (1 per eligible month)
        $totalCasualLeavesForYear = $eligibleMonthsCount;
        
        // Start with full allocation for this year
        $casualLeaveBalance = $totalCasualLeavesForYear;
    }

    /** -----------------------
     * 7. Calculate used leaves for this year (including previous months)
     * ----------------------*/
    $totalCasualLeavesUsedThisYear = 0;
    $totalHalfDaysUsedThisYear = 0;
    
    if ($isEligibleForLeave && $isCurrentMonthEligible) {
        // Get all approved leaves for this year (from year start or eligibility start)
        $yearStart = Carbon::createFromDate($selectedYear, 1, 1);
        $yearEnd = Carbon::createFromDate($selectedYear, 12, 31);
        $searchStartDate = $actualLeaveStartThisYear->gt($yearStart) ? $actualLeaveStartThisYear : $yearStart;
        
        $allLeavesThisYear = \DB::table('leave_applications')
            ->where('user_id', $employee->user_id)
            ->where('status', 2) // Approved
            ->whereIn('leave_type_id', [2, 3]) // Casual Leave and Half Day
            ->whereBetween('apply_strt_date', [$searchStartDate->toDateString(), $endDate->toDateString()])
            ->get();
        
        foreach ($allLeavesThisYear as $leave) {
            $leaveStart = Carbon::parse($leave->apply_strt_date);
            $leaveEnd = Carbon::parse($leave->apply_end_date ?? $leave->apply_strt_date);
            $leaveDays = $leaveStart->diffInDays($leaveEnd) + 1;
            
            if ($leave->leave_type_id == 2) {
                // Casual Leave
                $totalCasualLeavesUsedThisYear += $leaveDays;
            } elseif ($leave->leave_type_id == 3) {
                // Half Day Leave
                $totalHalfDaysUsedThisYear += $leaveDays;
            }
        }
        
        // Convert half days to full days (2 half days = 1 full day)
        $halfDaysAsFullDays = floor($totalHalfDaysUsedThisYear / 2);
        $totalCasualLeavesUsedThisYear += $halfDaysAsFullDays;
        
        // Calculate remaining balance
        $casualLeaveBalance = max(0, $totalCasualLeavesForYear - $totalCasualLeavesUsedThisYear);
    }

    /** -----------------------
     * 8. Get approved leave applications for the month
     * ----------------------*/
    $approvedLeaves = \DB::table('leave_applications')
        ->where('user_id', $employee->user_id)
        ->where('status', 2) // Approved
        ->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('apply_strt_date', [$startDate->toDateString(), $endDate->toDateString()])
              ->orWhereBetween('apply_end_date', [$startDate->toDateString(), $endDate->toDateString()])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('apply_strt_date', '<=', $startDate->toDateString())
                     ->where('apply_end_date', '>=', $endDate->toDateString());
              });
        })
        ->get();

    /** -----------------------
     * 9. Process leave applications and categorize
     * ----------------------*/
    $validLeaveDates = [];
    $lopDates = []; // Loss of Pay dates
    $casualLeavesUsedThisMonth = 0;
    $medicalLeavesUsedThisMonth = 0;
    $halfDayLeavesUsedThisMonth = 0;
    $tempCasualBalance = $casualLeaveBalance; // Temporary balance for this month's processing

    foreach ($approvedLeaves as $leave) {
        $lStart = Carbon::parse($leave->apply_strt_date);
        $lEnd = Carbon::parse($leave->apply_end_date ?? $leave->apply_strt_date);
        $leaveTypeId = $leave->leave_type_id;
        
        // Calculate leave days in current month
        $leaveDaysInMonth = [];
        while ($lStart->lte($lEnd)) {
            $dateStr = $lStart->toDateString();
            if ($lStart->between($startDate, $endDate) && in_array($dateStr, $workingDays)) {
                $leaveDaysInMonth[] = $dateStr;
            }
            $lStart->addDay();
        }
        
        if (empty($leaveDaysInMonth)) {
            continue;
        }
        
        // Check eligibility and apply leave rules
        if (!$isEligibleForLeave || !$isCurrentMonthEligible) {
            // Employee not eligible for leaves - all as LOP
            $lopDates = array_merge($lopDates, $leaveDaysInMonth);
        } else {
            switch ($leaveTypeId) {
                case 1: // Medical Leave - unlimited per month
                    $validLeaveDates = array_merge($validLeaveDates, $leaveDaysInMonth);
                    $medicalLeavesUsedThisMonth += count($leaveDaysInMonth);
                    break;
                    
                case 2: // Casual Leave - 1 per month with yearly balance
                    $casualDaysNeeded = count($leaveDaysInMonth);
                    
                    // Check monthly limit (1 per month)
                    $monthlyLimit = 1;
                    $canTakeThisMonth = max(0, $monthlyLimit - $casualLeavesUsedThisMonth);
                    
                    // Check yearly balance
                    $availableFromBalance = $tempCasualBalance;
                    
                    // Take minimum of monthly limit and available balance
                    $allowedCasualDays = min($canTakeThisMonth, $availableFromBalance, $casualDaysNeeded);
                    
                    if ($allowedCasualDays > 0) {
                        // Take the first N days as valid leave
                        $allowedDates = array_slice($leaveDaysInMonth, 0, $allowedCasualDays);
                        $validLeaveDates = array_merge($validLeaveDates, $allowedDates);
                        $casualLeavesUsedThisMonth += $allowedCasualDays;
                        $tempCasualBalance -= $allowedCasualDays;
                    }
                    
                    // Remaining days as LOP
                    $lopDays = $casualDaysNeeded - $allowedCasualDays;
                    if ($lopDays > 0) {
                        $lopDates = array_merge($lopDates, array_slice($leaveDaysInMonth, $allowedCasualDays));
                    }
                    break;
                    
                case 3: // Half Day Leave - convert to casual leave (2 half = 1 full)
                    $halfDaysNeeded = count($leaveDaysInMonth);
                    $halfDayLeavesUsedThisMonth += $halfDaysNeeded;
                    
                    // Calculate how many full casual leaves this represents
                    $fullDaysEquivalent = floor($halfDaysNeeded / 2);
                    $remainingHalfDays = $halfDaysNeeded % 2;
                    
                    // Check if we have balance for full day equivalents
                    if ($fullDaysEquivalent > 0 && $tempCasualBalance >= $fullDaysEquivalent) {
                        // Deduct from casual balance for the full day equivalents
                        $tempCasualBalance -= $fullDaysEquivalent;
                        
                        // All half days are valid (will be counted as partial casual leave usage)
                        $validLeaveDates = array_merge($validLeaveDates, $leaveDaysInMonth);
                    } else {
                        // Not enough balance - treat as LOP
                        $lopDates = array_merge($lopDates, $leaveDaysInMonth);
                    }
                    break;
                    
                default:
                    // Unknown leave type - treat as LOP
                    $lopDates = array_merge($lopDates, $leaveDaysInMonth);
                    break;
            }
        }
    }

    // Remove duplicates and get final leave dates
    $leaveDates = array_unique($validLeaveDates);
    $lopDates = array_unique($lopDates);

    /** -----------------------
     * 10. Get attendance records
     * ----------------------*/
    $attendanceRecords = Attendance::where('user_id', $employee->user_id)
        ->whereBetween('dated', [$startDate->toDateString(), $endDate->toDateString()])
        ->get()
        ->mapWithKeys(function ($att) {
            return [Carbon::parse($att->dated)->toDateString() => $att];
        });

    /** -----------------------
     * 11. Process each working day and categorize
     * ----------------------*/
    $presentDays = 0;
    $absentDays = 0;
    $lateCount = 0;
    $absentDates = [];
    $presentDates = [];
    $lopDays = count($lopDates);

    foreach ($workingDays as $workingDay) {
        // Skip if this day is on approved leave (not LOP)
        if (in_array($workingDay, $leaveDates)) {
            continue; // Don't count as present or absent
        }
        
        // Check if attendance record exists for this working day
        if (isset($attendanceRecords[$workingDay]) && !empty($attendanceRecords[$workingDay]->in_time)) {
            // Present
            $presentDays++;
            $presentDates[] = $workingDay;

            // Check for lateness
            $inTime = Carbon::parse($workingDay . ' ' . $attendanceRecords[$workingDay]->in_time);
            $lateThreshold = Carbon::parse($workingDay . ' 10:30:00');
            
            if ($inTime->gt($lateThreshold)) {
                $lateCount++;
            }
        } else {
            // Absent (including LOP days)
            $absentDays++;
            $absentDates[] = $workingDay;
        }
    }

    /** -----------------------
     * 12. Verification: Working Days = Present + Absent + Leave
     * ----------------------*/
    $totalWorkingDays = count($workingDays);
    $totalLeaveDays = count($leaveDates);
    $calculatedTotal = $presentDays + $absentDays + $totalLeaveDays;

    // This should always be true
    if ($calculatedTotal !== $totalWorkingDays) {
        \Log::warning("Attendance calculation mismatch", [
            'employee_id' => $employee->id,
            'working_days' => $totalWorkingDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'leave_days' => $totalLeaveDays,
            'lop_days' => $lopDays,
            'calculated_total' => $calculatedTotal,
            'difference' => $totalWorkingDays - $calculatedTotal
        ]);
    }

    /** -----------------------
     * 13. Apply monthly free leaves and calculate deductions
     * ----------------------*/
    $monthlyAllowedLeaves = 2;
    $deductibleAbsences = max(0, $absentDays - $monthlyAllowedLeaves);

    $perDaySalary = $this->basic_salary > 0 && $totalWorkingDays > 0
        ? $this->basic_salary / $totalWorkingDays
        : 0;

    $latePenaltyDays = floor($lateCount / 3);
    $lateDeduction = $latePenaltyDays * $perDaySalary;
    $absentDeduction = $deductibleAbsences * $perDaySalary;

    // Update component values
    $this->updateComponent('Late Penalty', round($lateDeduction));
    $this->updateComponent('Absent Deduction', round($absentDeduction));

    // Set properties
    $this->workingDays = $totalWorkingDays;
    $this->absentDays = $absentDays;
    $this->lateCount = $lateCount;

    $this->calculateTotals();

    /** -----------------------
     * 14. Enhanced Debug Logging
     * ----------------------*/

        $this->totalCalendarDays = count($allDates);
        $this->nonWorkingDaysCount = count($nonWorkingDays);
        $this->holidayDaysCount = count($holidayDates);
        $this->workingDays = $totalWorkingDays;
        $this->presentDays = $presentDays;

        $this->totalCasualLeavesForYear = $totalCasualLeavesForYear;
        $this->totalCasualLeavesUsedThisYear = $totalCasualLeavesUsedThisYear;
        $this->casualLeaveBalance = $casualLeaveBalance;

        $this->casualLeavesUsedThisMonth = $casualLeavesUsedThisMonth;
        $this->medicalLeavesUsedThisMonth = $medicalLeavesUsedThisMonth;
        $this->halfDayLeavesUsedThisMonth = $halfDayLeavesUsedThisMonth;

    \Log::info("Attendance Penalty Calculation", [
        'employee_id' => $employee->id,
        'user_id' => $employee->user_id,
        'month' => $this->selectedMonth,
        'date_range' => [$startDate->toDateString(), $endDate->toDateString()],
        
        // Employee eligibility
        'joining_date' => $joiningDate->toDateString(),
        'is_eligible_for_leave' => $isEligibleForLeave,
        'leave_eligibility_date' => $leaveEligibilityDate->toDateString(),
        'actual_leave_start_date' => $actualLeaveStartDate->toDateString(),
        'is_current_month_eligible' => $isCurrentMonthEligible,
        'months_since_joining' => $joiningDate->diffInMonths($currentDate),
        
        // Leave balance information
        'leave_balance_calculation' => [
            'year_being_calculated' => $selectedYear,
             'actual_leave_start_this_year' => isset($actualLeaveStartThisYear) ? $actualLeaveStartThisYear->toDateString() : 'N/A',
            'current_month_end' => isset($currentMonthEnd) ? $currentMonthEnd->toDateString() : 'N/A',
            'eligible_months_count' => $totalCasualLeavesForYear,
            'total_casual_leaves_allocated_this_year' => $totalCasualLeavesForYear,
            'casual_leaves_used_this_year' => $totalCasualLeavesUsedThisYear,
            'half_days_used_this_year' => $totalHalfDaysUsedThisYear,
            'half_days_as_full_days' => floor($totalHalfDaysUsedThisYear / 2),
            'casual_leave_balance' => $casualLeaveBalance,
        ],
        'casual_leaves_used_this_month' => $casualLeavesUsedThisMonth,
        'medical_leaves_used_this_month' => $medicalLeavesUsedThisMonth,
        'half_day_leaves_used_this_month' => $halfDayLeavesUsedThisMonth,
        
        // Day categorization
        'total_calendar_days' => count($allDates),
        'non_working_days' => count($nonWorkingDays),
        'holiday_days' => count($holidayDates),
        'working_days' => $totalWorkingDays,
        
        // Attendance breakdown
        'present_days' => $presentDays,
        'absent_days' => $absentDays,
        'valid_leave_days' => count($leaveDates),
        'lop_days' => $lopDays,
        'late_count' => $lateCount,
        
        // Verification
        'formula_check' => [
            'working_days' => $totalWorkingDays,
            'present_absent_leave_sum' => $calculatedTotal,
            'matches' => ($calculatedTotal === $totalWorkingDays)
        ],
        
        // Detailed arrays for debugging
        'all_saturdays' => $saturdays,
        'saturdays_breakdown' => array_map(function($satDate, $index) {
            return [
                'date' => $satDate,
                'position' => ($index + 1) . 'st/nd/rd/th',
                'is_working' => !in_array($index, [1, 3]) ? 'YES' : 'NO'
            ];
        }, $saturdays, array_keys($saturdays)),
        'non_working_dates' => $nonWorkingDays,
        'holiday_dates' => $holidayDates,
        'valid_leave_dates' => $leaveDates,
        'lop_dates' => $lopDates,
        'present_dates' => $presentDates,
        'absent_dates' => $absentDates,
        
        // Financial calculations
        'monthly_allowed_leaves' => $monthlyAllowedLeaves,
        'deductible_absences' => $deductibleAbsences,
        'per_day_salary' => round($perDaySalary, 2),
        'late_penalty_days' => $latePenaltyDays,
        'late_deduction' => round($lateDeduction, 2),
        'absent_deduction' => round($absentDeduction, 2)
    ]);
}
private function updateComponent($name, $amount)
{
    $key = array_search($name, array_column($this->components, 'name'));

    if ($amount > 0) {
        if ($key === false) {
            $this->components[] = [
                'type' => 'deduction',
                'name' => $name,
                'amount' => $amount,
                'percentage' => null
            ];
        } else {
            $this->components[$key]['amount'] = $amount;
        }
    } elseif ($key !== false) {
        unset($this->components[$key]);
        $this->components = array_values($this->components);
    }
}
private function handlePercentageComponents()
{
    foreach ($this->components as &$component) {
        if (!empty($component['percentage']) && is_numeric($component['percentage'])) {
            $component['amount'] = ($this->basic_salary * $component['percentage']) / 100;
        }
    }
}
    private function recalculatePercentages()
    {
        foreach ($this->components as &$component) {
            if (!empty($component['percentage']) && $this->basic_salary > 0) {
                $component['amount'] = ($this->basic_salary * $component['percentage']) / 100;
            }
        }
    }

private function calculateTotals()
{
    $oldNetSalary = $this->netSalary;

    $this->totalAllowance = 0;
    $this->totalDeduction = 0;

    foreach ($this->components as $component) {
        $amount = (float)($component['amount'] ?? 0);
        if ($component['type'] === 'allowance') {
            $this->totalAllowance += $amount;
        } elseif ($component['type'] === 'deduction') {
            $this->totalDeduction += $amount;
        }
    }

    $this->netSalary = (float)$this->basic_salary + $this->totalAllowance - $this->totalDeduction;

    // Highlight changes
    if ($oldNetSalary && $oldNetSalary != $this->netSalary) {
        $this->netSalaryClass = $this->netSalary > $oldNetSalary
            ? 'text-success animate-bounce'
            : 'text-danger animate-bounce';
        $this->dispatch('resetNetSalaryClass');
    }
}

    public function addComponent()
    {
        $this->components[] = [
            'type' => 'allowance',
            'name' => '',
            'amount' => 0,
            'percentage' => null,
        ];
    }

    public function removeComponent($index)
    {
        unset($this->components[$index]);
        $this->components = array_values($this->components);
        $this->updateAttendancePenalty();
    }

    public function save()
    {
        if (!$this->selectedEmployeeId) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => 'Please select an employee first.'
            ]));
            return;
        }

        $this->recalculatePercentages();
        $this->updateAttendancePenalty();

        $salary = Salary::updateOrCreate(
            [
                'employee_id' => $this->selectedEmployeeId,
                'month' => $this->selectedMonth,
            ],
            ['basic_salary' => $this->basic_salary]
        );

        $salary->components()->delete();
        foreach ($this->components as $component) {
            $salary->components()->create($component);
        }

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => "Salary for {$this->selectedMonth} saved successfully!"
        ]));
    }

    public function render()
    {
        return view('livewire.hr.salary-management.salary-setup');
    }
    /**
     * Helper: Get previous month of the selected month
     */
    private function getPreviousMonth()
    {
        return Carbon::parse("{$this->selectedMonth}-01")
            ->subMonth()
            ->format('Y-m');
    }
   public function confirmBulkCopy()
    {
        $prevMonth = $this->getPreviousMonth();

        $totalPrev = Salary::where('month', $prevMonth)->count();
        $alreadyExists = Salary::where('month', $this->selectedMonth)->count();

        $this->bulkCopySummary = [
            'totalPrev' => $totalPrev,
            'alreadyExists' => $alreadyExists,
            'willBeCopied' => max($totalPrev - $alreadyExists, 0),
        ];

        if ($totalPrev === 0) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => "No salary data found for previous month ($prevMonth)."
            ]));
            return;
        }

        $this->showConfirmBulkCopy = true;
    }
        /**
     * Confirm & Execute bulk copy
     */
    public function bulkCopyPreviousMonthConfirmed()
    {
        $prevMonth = $this->getPreviousMonth();

        $previousSalaries = Salary::with('components')
            ->where('month', $prevMonth)
            ->get();

        if ($previousSalaries->isEmpty()) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => "No data found for previous month ($prevMonth)."
            ]));
            return;
        }

        DB::transaction(function () use ($previousSalaries) {
            foreach ($previousSalaries as $prevSalary) {
                $salary = Salary::updateOrCreate(
                    [
                        'employee_id' => $prevSalary->employee_id,
                        'month' => $this->selectedMonth,
                    ],
                    ['basic_salary' => $prevSalary->basic_salary]
                );

                $salary->components()->delete();
                foreach ($prevSalary->components as $component) {
                    $salary->components()->create($component->only(['type', 'name', 'amount', 'percentage']));
                }
            }
        });

        $this->showConfirmBulkCopy = false;

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => "All salaries copied from $prevMonth to {$this->selectedMonth} successfully!"
        ]));
    }

}
