<?php

namespace App\Livewire\Profile;
use App\Models\Appellation;
use App\Models\Appointedorganization;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Emptype;
use App\Models\Gender;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;
    public $name, $email, $phone, $address, $image;

    public $firstName, $lastName, $fullName;
    public $eng_letter;
    public $emp_pancard;
    public $aadhar_card;
    public $gender;
    public $emptypes;
    public $designation;
    public $appointedorganization;
    public $appellation;
    public $emp_code;
    public $emp_type;
    public $emp_appellation;
    public $emp_first_name;
    //public $emp_middle_name;
    //public $emp_last_name;
    public $emp_designation;
    public $emp_dob;
    public $emp_sex;
    public $emp_date_of_joining;
    public $emp_aadhar;
    public $emp_pan;
    public $emp_appointed_organisation;
    public $emp_contact_no;
    public $emp_emergency_contact_no;
    public $emp_email;
    public $emp_udin;
    public $emp_address;
    public $emp_status = 1;
    public $employee;
    public $password;
    public $employeeCode;
    public $empId;
    public $user;
    public $userimage;
    public $designations;
    public $emp_last_name;
    protected $listeners = ['contactInfoUpdated'];

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;

        $this->employee = Employee::with(['user', 'designation', 'empType'])
        ->where('user_id', Auth::id())
        ->first();
        $this->empId = $this->employee->id;
      //  dd($this->employee);
        $this->splitName($this->employee->emp_first_name);
        $this->emp_code = $this->employee->emp_code;
        $this->emp_type = $this->employee->emp_type;
        $this->emp_appellation = $this->employee->emp_appellation;
        $this->emp_first_name = $this->employee->emp_first_name;
        $this->emp_last_name = $this->employee->emp_last_name;
        $this->emp_designation = $this->employee->emp_designation;
        $this->emp_dob = $this->employee->emp_dob;
        $this->emp_sex = $this->employee->emp_sex;
        $this->emp_date_of_joining = $this->employee->emp_date_of_joining;
        $this->emp_aadhar = $this->employee->emp_aadhar;
        $this->emp_pan = $this->employee->emp_pan;
        $this->emp_appointed_organisation = $this->employee->emp_appointed_organisation;
        $this->emp_contact_no = $this->employee->emp_contact_no;
        $this->emp_emergency_contact_no = $this->employee->emp_emergency_contact_no;
        $this->emp_email = $this->employee->user->email;
        $this->emp_udin = $this->employee->emp_udin;
        $this->emp_address = $this->employee->emp_address;

        ///dd($this->emp_code);

        $this->gender = Gender::pluck('name','id')->all();
        $this->emptypes = Emptype::pluck('name','id')->all();
        $this->designations = Designation::pluck('name','id')->all();
        $this->appointedorganization = Appointedorganization::pluck('name','id')->all();
        $this->appellation = Appellation::pluck('name','id')->all();
    }

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'image' => 'required',
        ]);
    }


    public function render()
    {
        $user = Employee::with(['user', 'designation', 'empType'])
        ->where('user_id', Auth::id())
        ->first();

        // dd($user);
        return view('profile');
        //return view('profile', compact('user'));
    }

    public function updatePersonalInfo(){
        $this->employee = Employee::findOrFail($this->empId);
        $this->updateFullName();
        $this->employee->update([
            'emp_code' => $this->emp_code,
            'emp_first_name' => $this->emp_first_name,
            'emp_last_name' => $this->emp_last_name,
            'emp_type' => $this->emp_type,
            'emp_appellation' => $this->emp_appellation,
            'emp_designation' => $this->emp_designation,
            'emp_dob' => $this->emp_dob,
            'emp_sex' => $this->emp_sex,
            'emp_date_of_joining' => $this->emp_date_of_joining,
        ]);

        $this->user = User::findOrFail($this->employee->user_id);
        $this->user->update([
            'name' => $this->fullName,
        ]);
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Profile Updated successfully'
        ]));
    }

    
     public function updateContactInfo(){
        $this->employee = Employee::findOrFail($this->empId);
        $this->employee->update([
            'emp_contact_no' => $this->emp_contact_no,
            'emp_emergency_contact_no' => $this->emp_emergency_contact_no,
            'emp_address' => $this->emp_address
        ]);

        $this->user = User::findOrFail($this->employee->user_id);
        $this->user->update([
            'email' => $this->emp_email,
        ]);
        $this->dispatch('contactInfoUpdated');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Contact Information Updated successfully'
        ]));
    }




    private function splitName($name)
    {
        $parts = explode(' ', trim($name), 2);
        $this->firstName = $parts[0] ?? '';
        $this->lastName = $parts[1] ?? '';
    }

    private function updateFullName()
    {
        $this->fullName = trim($this->emp_first_name . ' ' . $this->emp_last_name);
    }
}
