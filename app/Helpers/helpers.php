<?php

use App\Models\Attendance;
use App\Models\DocumentWorkMaster;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'jS M, Y')
    {
        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }
}




if (!function_exists('datediff')) {
    function datediff($start, $end)
    {
        try {
            $date1 = Carbon::parse($start); // Start date
            $date2 = Carbon::parse($end);   // End date

            return $date1->diffInDays($date2);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}

if (!function_exists('getUserNameById')) {
    function getUserNameById($userId)
    {
        return User::find($userId)?->name ?? 'Unknown';
    }
}

// if (!function_exists('dateDifferenceFromCurrent')) {
//     function dateDifferenceFromCurrent($workdeadline) {

//         $today = strtotime(date('Y-m-d'));
//         $deadline = strtotime($workdeadline);
//         $difference = ($deadline - $today) / (60 * 60 * 24); // Calculate the difference in days

//         if ($difference < 0) {
//             // Overdue case
//             $overdueDays = abs($difference); // Convert negative to positive
//             $color = 'badge-soft-danger';
//             $status = "Overdue by $overdueDays days";
//         } elseif ($difference <= 5) {
//             $color = 'badge-soft-danger';
//             $status = 'Due soon';
//         } elseif ($difference > 5 && $difference <= 10) {
//             $color = 'badge-soft-warning';
//             $status = 'Upcoming';
//         } else {
//             $color = 'badge-soft-success';
//             $status = 'On track';
//         }
//         return '<span class="badge '. $color .' fw-semibold ms-2"><i class="far fa-fw fa-clock"></i>'. $status .'</span>';
//         }
// }



if (!function_exists('dateDifferenceFromCurrent')) {
    function dateDifferenceFromCurrent($deadline, $completed_on = null)
    {

        if(!empty($completed_on)){
            return '<span class="badge bg-success ms-2"><i class="far fa-fw fa-clock"></i> Completed on ' . formatDate($completed_on) . '</span>';
        }else{
                   // Get today's date and parse the deadline
        $today = Carbon::today(); // This ensures no time component is considered
        $deadlineDate = Carbon::parse($deadline)->startOfDay(); // Normalize the deadline to start of the day

        // Calculate the signed difference in days
        $difference = $today->diffInDays($deadlineDate, false); // `false` ensures signed difference

        if ($difference < 0) { // Deadline has passed (overdue)
            $overdueDays = abs($difference); // Absolute value for overdue days
            return '<span class="badge badge-soft-danger fw-semibold ms-2"><i class="far fa-fw fa-clock"></i> Overdue by ' . $overdueDays . ' days</span>';
        } elseif ($difference == 0) { // Deadline is today
            return '<span class="badge bg-warning ms-2"><i class="far fa-fw fa-clock"></i> Due today</span>';
        } else { // Deadline is in the future
            $color = $difference <= 5 ? 'badge-soft-pink' : ($difference <= 10 ? 'badge-soft-warning' : 'badge-soft-success');
            $status = $difference <= 5 ? 'Due soon' : ($difference <= 10 ? 'Upcoming' : 'On track');

            return '<span class="badge ' . $color . ' fw-semibold ms-2"><i class="far fa-fw fa-clock"></i> ' . $status . ' (' . $difference . ' days left)</span>';
        }

        }
    }
}




if (!function_exists('getStatus')) {
    function getStatus($val)
    {
        if ($val == 1) {
            return '<span class="badge badge-soft-success">Active</span>';
        } else {
            return '<span class="badge badge-soft-secondary">Inactive</span>';
        }
    }
}


function getAttendanceStatus($day, $user_id, $monthNumber)
{
    // Get the current date
    $currentDate = now()->setDay($day)->toDateString();
    $currentDayOfWeek = now()->setDay($day)->dayOfWeek; // Get the day of the week (0 = Sunday, 6 = Saturday)
    $currentMonth = now()->setDay($day)->month;
    // Check if the given day is in the future
    if ($day > now()->day) {
        return ''; // Return blank if the day is in the future
    }

    // Check if the day is a weekend
    if ($currentDayOfWeek == 0) { // Sunday
        return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="Weekly Off">-</span>'; // Sunday is not marked absent
    }

    // Handle alternate Saturdays off dynamically
    if ($currentDayOfWeek == 6) { // Saturday
        $saturdayCount = now()->setDay($day)->copy()->startOfMonth()
            ->daysUntil(now()->setDay($day))
            ->filter(fn($date) => $date->isSaturday())
            ->count();

        // Fetch dynamic off-Saturday settings from database or configuration
        $offSaturdays = [2, 4]; // Example: First and third Saturdays are off, this can be replaced with DB logic.

        if (in_array($saturdayCount, $offSaturdays)) {
            return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="Weekly Off">-</span>'; // Mark alternate Saturdays off
        }
    }

    // Retrieve a single attendance record for the given day and user
    $employee = Attendance::whereDate('dated', $currentDate)
        ->where('user_id', $user_id)
        ->whereMonth('dated', $monthNumber)
        ->first();

    // Retrieve a holiday record for the given day
    $holiday = Holiday::whereDate('start_date', '<=', $currentDate)
        ->whereDate('end_date', '>=', $currentDate) // Assuming holidays can span multiple days
        ->whereMonth('start_date', $monthNumber)
        ->whereMonth('end_date', $monthNumber)
        ->first();

    // Retrieve a leave application for the given day and user
    $leaveApplication = LeaveApplication::where('user_id', $user_id)
        ->whereDate('apply_strt_date', '<=', $currentDate)
        ->whereDate('apply_end_date', '>=', $currentDate)
        ->whereMonth('apply_strt_date', $monthNumber)
        ->whereMonth('apply_end_date', $monthNumber)
        ->where('status', 1) // Ensure leave status is 1 (approved)
        ->first();

    // Determine attendance status
    if ($employee) {
        if ($employee->status == 1) {
            $entryExit = $employee->in_time . ' to ' . $employee->out_time;
            if(!empty($employee->in_time) && !empty($employee->out_time)){
             return '<i class="mdi mdi-check text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $entryExit . '"></i>'; // Present
            }else{
               return '<i class="mdi mdi-check text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $employee->in_time . '"></i>'; // Present
            }
        } else {
            return '<i class="mdi mdi-close text-danger"></i>'; // Absent
        }
    } else {
        if ($leaveApplication) {
            $leaveReason = $leaveApplication->reason;
            return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' . $leaveReason . '">L</span>'; // Leave
        } elseif ($holiday) {
            $holidayName = $holiday->name;
            return '<span  data-bs-toggle="tooltip" data-bs-placement="top" title="' . $holidayName . '">H</span>'; // Holiday
        } else {
            return '<i class="mdi mdi-close text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Absent"></i>'; // Absent (default case)
        }
    }
}


// Generate the attendance for each employee
function generateEmployeeAttendance($totalDays, $user_id, $monthNumber)
{
    $attendance = [];
    for ($day = 1; $day <= $totalDays; $day++) {
        $attendance[$day] = getAttendanceStatus($day, $user_id, $monthNumber);
    }
    return $attendance;
}



if (!function_exists('empProfilePicture')) {
    function empProfilePicture($userId)
    {
        $employee = Employee::where('user_id', $userId)->first();

        // Default images based on gender
        $defaultMaleImage = asset('assets/images/users/male.png');
        $defaultFemaleImage = asset('assets/images/users/female.png');

        if ($employee) {
            // Determine default image based on employee gender
            $defaultImageUrl = ($employee->emp_sex == 1) ? $defaultMaleImage : $defaultFemaleImage;

            // Check if employee has uploaded a profile picture
            $media = $employee->getMedia('employee')->first();
            return $media ? $media->getUrl() : $defaultImageUrl;
        }

        // Return a general default image if no employee found
        return $defaultMaleImage;
    }
}


if (!function_exists('getInitials')) {
    function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = array_map(function ($word) {
            return strtoupper($word[0]);
        }, $words);
        return implode('', $initials);
    }
}


if (!function_exists('getstatusss')) {
    function getstatusss($status)
    {
        $statusClasses = [
            'Pending' => 'badge badge-soft-warning',
            'Pending for Approval' => 'badge badge-soft-warning',
            'Pending Approval' => 'badge badge-soft-warning',
            'Negotiation/Review' => 'badge badge-soft-warning',
            'Not Contacted' => 'badge badge-soft-warning',
            'Justdial' => 'badge badge-soft-warning',

            'In Progress' => 'badge badge-soft-info',
            'Testing/Review' => 'badge badge-soft-info',
            'Needs Analysis' => 'badge badge-soft-info',
            'Not Qualified' => 'badge badge-soft-info',
            'Google' => 'badge badge-soft-info',
            'Negotiation' => 'badge badge-soft-info',

            'Completed' => 'badge badge-soft-success',
            'Approved' => 'badge badge-soft-success',
            'Closed Won' => 'badge badge-soft-success',
            'Converted' => 'badge badge-soft-success',
            'Upwork' => 'badge badge-soft-success',
            'Client Review Pending' => 'badge badge-soft-success',

            'Cancelled' => 'badge badge-soft-danger',
            'Rejected' => 'badge badge-soft-danger',
            'Junk Lead' => 'badge badge-soft-danger',
            'Not Qualified' => 'badge badge-soft-danger',
            'Lost Lead' => 'badge badge-soft-danger',
            'Closed Lost' => 'badge badge-soft-danger',
            'Closed Lost to Competition' => 'badge badge-soft-danger',
            'Youtube Promotion' => 'badge badge-soft-danger',

            'On Hold' => 'badge badge-soft-pink',
            'Delayed' => 'badge badge-soft-pink',
            'Instagram' => 'badge badge-soft-pink',
            'Proposal Sent' => 'badge badge-soft-pink',
            'Follow-Up Required' => 'badge badge-soft-pink',

            //'On Hold' => 'badge badge-soft-secondary',
            'Identify Decision Makers' => 'badge badge-soft-secondary',
            'Contact in Future' => 'badge badge-soft-secondary',

            'Qualification' => 'badge badge-soft-primary',
            'Contacted' => 'badge badge-soft-primary',
            'Facebook' => 'badge badge-soft-primary',
            'Freelancer' => 'badge badge-soft-primary',
            'Resources Allocated' => 'badge badge-soft-primary',
            'Revisions Required' => 'badge badge-soft-primary',
            'Verbal Agreement' => 'badge badge-soft-primary',

            'Value Proposition' => 'badge badge-soft-dark',
            'Attempted to Contact' => 'badge badge-soft-dark',
            'Requirements Gathering' => 'badge badge-soft-dark',
            'Reference From Client' => 'badge badge-soft-dark',

            'Proposal/Price Quote' => 'badge badge-soft-purple',
            'Pre-Qualified' => 'badge badge-soft-purple',
            'Planning' => 'badge badge-soft-purple',

            'High' => 'badge bg-danger',
            'Medium' => 'badge bg-success',
            'Low' => 'badge bg-info',

            // 'Contact in Future' => 'badge badge-soft-purple',
        ];

        $badgeClass = $statusClasses[$status] ?? 'badge badge-soft-secondary';

        return '<span class="'.$badgeClass.'">'.$status.'</span>';
    }
}


function calculateProgress($totalTasks, $completedTasks, $projectStatus = null)
{
   
    if ($projectStatus == 'Completed') { 
        return 100;
    } else {
        return $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    }
}


function getListOfDocuments($workId)
{
    return DocumentWorkMaster::where('work_master_id', $workId)
        ->with('document.children')
        ->get()
        ->pluck('document')
        ->filter(); // Removes any null entries
}


