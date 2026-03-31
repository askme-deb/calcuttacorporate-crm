<?php

namespace App\Livewire\General;

use Livewire\Component;
use App\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class Logsheet extends Component
{
    public $logs;
    public $task_summary, $hours_worked, $remarks;
    public $log_date;

    public function mount()
    {
        $this->log_date = Carbon::today()->toDateString();
        $this->fetchLogs();
    }

    public function changeLogDate()
    {
     //   $this->log_date = Carbon::parse($this->log_date);
        $this->log_date = $this->log_date;
        $this->fetchLogs();
    }



    public function fetchLogs()
    {
        $this->logs = DailyLog::whereNotNull('log_date')
            ->where('log_date', $this->log_date)
            ->orderBy('log_date', 'desc')
            ->with('user')
            ->get();
    }



    public function deleteLog($logId)
    {
        DailyLog::where('id', $logId)->where('user_id', Auth::id())->delete();
        $this->fetchLogs();
    }
    public function render()
    {
        return view('livewire.general.logsheet');
    }
}
