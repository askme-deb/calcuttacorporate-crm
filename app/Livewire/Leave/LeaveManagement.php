<?php

namespace App\Livewire\Leave;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LeaveManagement extends Component
{
    public $difference;
    public $leaveTypes;
    public $users;
    public $leaveApplications;
    public $user_id;
    public $leave_type_id;
    public $apply_strt_date;
    public $apply_end_date;
    public $apply_day;
    public $reason;
    public $replace_person;
    public $join_date;
    public $selectedleaveType = null;
    public $selectedemployee = null;
  //  public $leaveTypeName;
  //  public $numberOfDays;
    public $showModal = false;
    public $modalMode = '';
    public $leaveApplicationId;
    public $data = [];
    public $applicationData;
    public $employee_id;
    public $approvedModal = false;
    public $approved_status = false;
    public $remainingLeaves, $enjoyedLeaves;
    public function rules()
    {
        $rules = [
            'leave_type_id' => $this->modalMode === 'create' ? 'required|numeric' : 'nullable',
            'apply_strt_date' => $this->modalMode === 'create' ? 'required' : 'nullable',
            'apply_end_date' => $this->modalMode === 'create' ? 'required' : 'nullable',
            'apply_day' => $this->modalMode === 'create' ? 'required|numeric' : 'nullable',
            'reason' => $this->modalMode === 'create' ? 'required' : 'nullable',
            'join_date' => $this->modalMode === 'create' ? 'required' : 'nullable',
        ];

        if (auth()->user()->can('Create Leave for Others')) {
            $rules['employee_id'] = $this->modalMode === 'create' ? 'required' : 'nullable';
        }

        return $rules;
    }

    protected $listeners = ['deleteItem', 'updateLeaveType','refreshComponent' => 'loadLeaveApply'];


    public function updateLeaveType($value)
    {
        if (!$value) {
            $this->remainingLeaves = null;
            return;
        }

        if (auth()->user()->can('Create Leave for Others')) {
            $userId = $this->employee_id;
        }else{
            $userId = auth()->user()->id;
        }
        $leavedEnjoyed = LeaveApplication::where('user_id', $userId)
        ->where('leave_type_id', $value)
        ->where('status', 2)
        ->sum('apply_day'); 
       // dd($leavedEnjoyed);
        $this->remainingLeaves = 12 - $leavedEnjoyed;
        $this->enjoyedLeaves = $leavedEnjoyed;
    
    }

    public function changeLeavetype(){
        $this->remainingLeaves = null; 
        $this->leave_type_id = null; 
    }
    public function addLeaveType()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
    }

    public function createLeaveApplication()
    {
       // dd($this->user_id);
        if (auth()->user()->can('Create Leave for Others')) {
            $this->user_id = $this->employee_id;
        }else{
            $this->user_id = auth()->user()->id;
        }


       $this->validate();
        LeaveApplication::create(
            [
                'user_id' => $this->user_id,
                'leave_type_id' => $this->pull('leave_type_id'),
                'apply_strt_date' => $this->pull('apply_strt_date'),
                'apply_end_date' => $this->pull('apply_end_date'),
                'apply_day' => $this->pull('apply_day'),
                'reason' => $this->pull('reason'),
                'replace_person' => $this->pull('replace_person'),
                'join_date' => $this->pull('join_date'),
                'status' => 0,
            ]
        );
        $this->user_id = '';
        $this->dispatch('refreshComponent');
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Leave Applied successfully'
        ]));

    }

    public function edit($id)
    {
        $this->showModal = true;
        $this->modalMode = 'edit';
        $this->leaveApplicationId = $id;
        $this->applicationData = LeaveApplication::findOrFail($id);

        $this->data = [
            'reason' => $this->applicationData->reason,
            'replace_person' => $this->applicationData->replace_person,
        ];
        $this->selectedleaveType = $this->applicationData->leave_type_id;
        $this->selectedemployee = $this->applicationData->user_id;
        $this->apply_day = $this->applicationData->apply_day;
        $this->apply_strt_date = date('Y-m-d', strtotime($this->applicationData->apply_strt_date));
        $this->apply_end_date = date('Y-m-d', strtotime($this->applicationData->apply_end_date));
        $this->join_date = date('Y-m-d', strtotime($this->applicationData->join_date));
    }

    public function update()
    {
       // $this->validate();
        $record = LeaveApplication::findOrFail($this->leaveApplicationId);
        $record->update([
            'user_id' => $this->selectedemployee,
            'leave_type_id' => $this->selectedleaveType,
            'apply_strt_date' => $this->apply_strt_date,
            'apply_end_date' => $this->apply_end_date,
            'apply_day' => $this->apply_day,
            'reason' => $this->data['reason'] ?? null,
            'replace_person' => $this->data['replace_person'] ?? null,
            'join_date' => $this->join_date,
        ]);
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Data Updated successfully'
        ]));

    }

    public function mount()
    {
        $this->leaveTypes = LeaveType::pluck('type_name','id')->all();

      

        if (auth()->user()->can('Create Leave for Others')) {
            $this->users = User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Director', 'Manager']);
            })->pluck('name', 'id');
        } else {
            $this->users = User::where('id', auth()->id())->pluck('name', 'id')->toArray();
        }
        $this->user_id = auth()->id();

        
        if (auth()->user()->can('View Other Leaves')) {
            $query = LeaveApplication::with(['leaveType', 'user']);
        } else {
            $query = LeaveApplication::with(['leaveType', 'user'])
                ->where('user_id', auth()->id());
        }
        
        $this->leaveApplications = $query->orderBy('id', 'desc')->get();
        
    }

    public function render()
    {
        return view('livewire.leave.leave-management');
    }


    public function closeModal()
    {
        $this->reset('showModal');
        $this->reset('approvedModal');
    }


    public function deleteItem($id)
    {
        $item = LeaveApplication::find($id);
        if ($item) {
            $item->delete();
            $this->dispatch('refreshComponent');
            $this->dispatch('swal:success', json_encode([
                'title' => 'Item Deleted',
                'text' => 'The Data has been successfully deleted.',
                'icon' => 'success',
            ]));
        }
    }
    public function loadLeaveApply()
    {

   
        if (auth()->user()->can('View Other Leaves')) {
            $query = LeaveApplication::with(['leaveType', 'user']);
        } else {
            $query = LeaveApplication::with(['leaveType', 'user'])
                ->where('user_id', auth()->id());
        }
        
        $this->leaveApplications = $query->orderBy('id', 'desc')->get();
        // $this->leaveApplications = LeaveApplication::orderBy('id', 'desc')
        // ->get();
    }


    public function calculateDifference()
    {
        if ($this->apply_strt_date && $this->apply_end_date) {
            $start = Carbon::parse($this->apply_strt_date);
            $end = Carbon::parse($this->apply_end_date);
            if ($start > $end) {
                $temp = $start;
                $start = $end;
                $end = $temp;
            }
            $this->apply_day = $start->diffInDays($end) + 1;
        }
    }



    public function approveForm($id)
    {
        $this->approvedModal = true;
        $this->leaveApplicationId = $id;
        $this->applicationData = LeaveApplication::findOrFail($id);
       // dd($this->applicationData->status);
        $this->approved_status = $this->applicationData->status;
        // $this->data = [
        //     'reason' => $this->applicationData->reason,
        //     'replace_person' => $this->applicationData->replace_person,
        // ];
        // $this->selectedleaveType = $this->applicationData->leave_type_id;
        // $this->selectedemployee = $this->applicationData->user_id;
        // $this->apply_day = $this->applicationData->apply_day;
        // $this->apply_strt_date = date('Y-m-d', strtotime($this->applicationData->apply_strt_date));
        // $this->apply_end_date = date('Y-m-d', strtotime($this->applicationData->apply_end_date));
        // $this->join_date = date('Y-m-d', strtotime($this->applicationData->join_date));
    }



    public function saveApprovedStatus()
    {
       // $this->validate();
     //  dd($this->leaveApplicationId);
        $record = LeaveApplication::findOrFail($this->leaveApplicationId);
        $record->update([
            'status' => $this->approved_status,
            'approved_by' => Auth::user()->id,
            'approve_date' => now()
            
        ]);
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Status Updated successfully'
        ]));

    }
    

}
