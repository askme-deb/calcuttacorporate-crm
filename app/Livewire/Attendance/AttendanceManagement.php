<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceManagement extends Component
{
    public $user_id, $in_time, $out_time, $dated, $status=1, $attendance_id;
    public $filterMonth, $filterUser;

    public $isEditing = false, $showModal = false;

    public $attendances = [];


    protected $listeners = ['deleteItem'];
    // protected $rules = [
    //     'user_id' => 'required|exists:users,id',
    //     'in_time' => $this->isEditing ? 'nullable' : 'required',
    //     'out_time' => $this->isEditing ? 'required' : 'nullable',
    //     // 'in_time' => 'nullable|date_format:H:i',
    //     // 'out_time' => 'nullable|date_format:H:i',
    //     'dated' => 'required|date|before_or_equal:today',
    //     //'status' => 'required|integer',
    // ];
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'dated' => 'required|date|before_or_equal:today',
            'in_time' => $this->isEditing ? 'nullable' : 'required', // Required only when creating
            'out_time' => $this->isEditing ? 'required' : 'nullable', // Required only when updating
        ];
    }
public function mount()
    {
        $this->filterMonth = now()->format('Y-m'); // Default to current month
        $this->filterUser = '';
        $this->dated = now()->format('Y-m-d');
        $this->in_time = now()->format('H:i');
    }

    public function updatedFilterMonth()
    {
        $this->loadAttendances();
    }

    public function updatedFilterUser()
    {
       
        $this->loadAttendances();
    }

    public function resetFilter()
    {
        $this->filterMonth = now()->format('Y-m');
        $this->filterUser = '';
        $this->loadAttendances();
    }

  public function loadAttendances()
{
    $query = Attendance::with('user');

    if (!empty($this->filterMonth)) {
        $year = substr($this->filterMonth, 0, 4);
        $month = substr($this->filterMonth, 5, 2);
        $query->whereYear('dated', $year)
              ->whereMonth('dated', $month);
    }

    if (!empty($this->filterUser)) {
        $query->where('user_id', $this->filterUser);
    }

    // Sort by user_id ASC and dated DESC
    $this->attendances = $query
        ->orderBy('dated', 'desc')
        ->orderBy('user_id', 'asc')
        ->get();
}



    public function render()
    {
        $this->loadAttendances(); // always apply filters

        return view('livewire.attendance.attendance-management', [
            'users' => User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Director', 'Manager']);
            })->get(),
        ]);
    }


    public function addAttendance()
    {
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function create()
    {
        $this->resetFields();
        $this->isEditing = false;

    }

    public function store()
    {
        $this->validate();
        // Check if the attendance for the user on the given date already exists
        $existingAttendance = Attendance::where('user_id', $this->user_id)
            ->whereDate('dated', $this->dated)
            ->first();

        if ($existingAttendance) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => 'Attendance for this user on this date already exists!'
            ]));
            return;
        }
        // Create new attendance record
        Attendance::create([
            'user_id' => $this->user_id,
            'in_time' => $this->in_time,
           // 'out_time' => $this->out_time,
            'dated' => $this->dated,
            'status' => 1,
        ]);
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Attendance Added Successfully'
        ]));
        $this->loadAttendances();
        $this->resetFields();
        $this->closeModal();
    }


    public function edit($id)
    {
        $this->showModal = true;
        $attendance = Attendance::findOrFail($id);
        $this->attendance_id = $attendance->id;
        $this->user_id = $attendance->user_id;
        $this->in_time = $attendance->in_time;
        $this->out_time = $attendance->out_time;
        $this->dated = $attendance->dated;
        $this->status = $attendance->status;

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->attendance_id) {
            $attendance = Attendance::findOrFail($this->attendance_id);
            $attendance->update([
                'user_id' => $this->user_id,
                'in_time' => $this->in_time,
                'out_time' => $this->out_time,
                'dated' => $this->dated,
                'status' => $this->status,
            ]);


            $this->dispatch('toastMessage', json_encode([
                'type' => 'success',
                'message' => 'Attendance Updated Successfully'
            ]));
            $this->loadAttendances();
            $this->resetFields();
            $this->closeModal();
        }
    }

    public function deleteItem($id)
    {
        Attendance::findOrFail($id)->delete();
        $this->dispatch('swal:success', json_encode([
            'title' => 'Item Deleted',
            'text' => 'Attendance Deleted Successfully',
            'icon' => 'success',
        ]));
        $this->loadAttendances();
    }

    private function resetFields()
    {
        $this->user_id = '';
        $this->in_time = '';
        $this->out_time = '';
        $this->dated = '';
        $this->status = '';
        $this->attendance_id = null;
        $this->isEditing = false;
       // $this->reset('showModal');
    }

    //  public function mount(){
    //     $this->dated = now()->format('Y-m-d');
    //     $this->in_time = now()->format('H:i');
    //  }



    public function closeModal()
    {
        $this->reset('showModal');
        $this->user_id = '';
        $this->in_time = '';
        $this->out_time = '';
        $this->dated = '';
        $this->status = '';
        $this->attendance_id = null;
        $this->isEditing = false;
    }





}
