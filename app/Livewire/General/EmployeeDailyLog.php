<?php
namespace App\Livewire\General;

use Livewire\Component;
use App\Models\DailyLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeDailyLog extends Component
{
    public $logs;
    public $task_summary, $hours_worked, $remarks;
    public $log_date;

    public function mount()
    {
        $this->log_date = Carbon::today()->toDateString();
        $this->fetchLogs();
    }

    public function fetchLogs()
    {
        $this->logs = DailyLog::where('user_id', Auth::id())
            ->orderBy('log_date', 'desc')
            ->get();
    }

    public function addLog()
    {
        $this->validate([
            'task_summary' => 'required|string|max:2000',
            'hours_worked' => 'required|integer|min:1|max:24',
            'log_date' => 'required|date'
        ]);

        DailyLog::create([
            'user_id' => Auth::id(),
            'log_date' => $this->log_date,
            'task_summary' => $this->task_summary,
            'hours_worked' => $this->hours_worked,
            'remarks' => $this->remarks,
        ]);

        $this->reset(['task_summary', 'hours_worked', 'remarks']);
        $this->fetchLogs();
    }

    public function deleteLog($logId)
    {
        DailyLog::where('id', $logId)->where('user_id', Auth::id())->delete();
        $this->fetchLogs();
    }

    public function render()
    {
        return view('livewire.general.employee-daily-log');
    }
}