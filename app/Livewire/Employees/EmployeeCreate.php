<?php

namespace App\Livewire\Employees;

use App\Models\Appellation;
use App\Models\Appointedorganization;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Emptype;
use App\Models\Gender;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Spatie\Activitylog\Facades\Activity;

class EmployeeCreate extends Component
{
    use WithFileUploads;
    public $image;
    public $eng_letter;
    public $emp_pancard;
    public $aadhar_card;
    public $gender;
    public $emptypes;
    public $institute;
    public $appointedorganization;
    public $appellation;
    public $emp_code;
    public $emp_type;
    public $emp_appellation;
    public $emp_first_name;
    //public $emp_middle_name;
    //public $emp_last_name;
    public $emp_institute;
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
    public $designations;
    public $emp_designation;
    public $firstName;
    public $lastName;
  
  
    protected $rules = [
        'emp_code' => 'required',
        'emp_type' => 'required',
        'emp_appellation' => 'required',
        'emp_first_name' => 'required|string|min:3',
        //'emp_designation' => 'required',
        'emp_institute' => 'required',
        'emp_dob' => 'required',
        'emp_sex' => 'required',
        'emp_date_of_joining' => 'required',
        // 'emp_aadhar' => 'required',
        // 'emp_pan' => 'required',
        'emp_appointed_organisation' => 'required',
        'emp_contact_no' => 'required',
        'emp_address' => 'required',
        'emp_email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'nullable|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
        // 'selectedRoles' => 'required',
        //  'image' => 'required'
    ];
    public function generateCode()
    {
        $lastEmployee = Employee::latest()->first();

       // $timestamp = now()->format('YmdHis'); // Unique timestamp
        $serialNumber = isset($lastEmployee) ? $lastEmployee->id + 1 : 1; // Auto-increment based on ID
        $serialNumberPadded = str_pad($serialNumber, 3, '0', STR_PAD_LEFT);
        $year = now()->format('Y');
        $initials = strtoupper(substr('CC', 0, 3)); // Change 'CC' to relevant initials
        return "{$initials}-{$serialNumberPadded}";
        // $timestamp = now()->format('YmdHis'); // Unique timestamp
        // $randomNumber = str_pad(rand(1, 9), 2, '0', STR_PAD_LEFT); // Random 3-digit number
        // $initials = strtoupper(substr('CC' ?? '', 0, 2)); // First two initials of the name
        // return"{$initials}-{$timestamp}{$randomNumber}";
    }

    public function generatePassword()
    {
        $length = 12;
        $alphanumeric = str()->random($length - 4);
        $specialCharacters = '!@#$%^&*()';
        $randomSpecials = substr(str_shuffle($specialCharacters), 0, 4);
        $this->password = str_shuffle($alphanumeric . $randomSpecials);
    }

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'image' => 'required',
        ]);
    }

    public function addEmployee()
    {
        // Validate form data
        $this->validate();

        $this->splitName($this->emp_first_name);
        // Create the new user
        $user = User::create([
            'name' => $this->emp_first_name,
            'email' => $this->emp_email,

        ]);
        $user->syncRoles(['Employee']);

        if($this->password){
            $user->update([
                'password' => Hash::make($this->pull('password')),
            ]);
        }

      //  dd($user->id);
         $this->employee = Employee::create([
            'emp_code' => $this->emp_code,
            'emp_first_name' => $this->firstName,
            'emp_last_name' => $this->lastName,
            'user_id' => $user->id,
            'emp_type' => $this->emp_type,
            'emp_appellation' => $this->emp_appellation,
            'emp_institute' => $this->emp_institute,
            'emp_designation' => $this->emp_designation,
            'emp_dob' => $this->emp_dob,
            'emp_sex' => $this->emp_sex,
            'emp_date_of_joining' => $this->emp_date_of_joining,
            'emp_aadhar' => $this->emp_aadhar,
            'emp_pan' => $this->emp_pan,
            'emp_appointed_organisation' => $this->emp_appointed_organisation,
            'emp_contact_no' => $this->emp_contact_no,
            'emp_emergency_contact_no' => $this->emp_emergency_contact_no,
            'emp_udin' => $this->emp_udin,
            'emp_address' => $this->emp_address,
            'emp_status' => $this->emp_status
        ]);
        if ($this->image) {
            $this->employee->addMedia($this->image->getRealPath())
        ->usingFileName($this->image->getClientOriginalName())
        ->toMediaCollection('employee');
        }

        if ($this->aadhar_card) {
            $this->employee->addMedia($this->aadhar_card->getRealPath())
        ->usingFileName($this->aadhar_card->getClientOriginalName())
        ->toMediaCollection('aadhar');
        }

        if ($this->emp_pancard) {
            $this->employee->addMedia($this->emp_pancard->getRealPath())
        ->usingFileName($this->emp_pancard->getClientOriginalName())
        ->toMediaCollection('pan');
        }

        if ($this->eng_letter) {
            $this->employee->addMedia($this->eng_letter->getRealPath())
        ->usingFileName($this->eng_letter->getClientOriginalName())
        ->toMediaCollection('engagementLetter');
        }


        // **Log activity**
        Activity::causedBy(Auth::user()) // The user who performed the action
            ->performedOn($this->employee) // The model that was affected
            ->withProperties([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->emp_first_name . ' ' . $this->employee->emp_last_name,
                'created_by' => Auth::user()->name,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Employee Created");

        // Reset form fields
        $this->reset(
            ['emp_code', 'emp_type', 'emp_appellation', 'emp_first_name',
            'emp_institute','emp_dob','emp_sex','emp_date_of_joining',
            'emp_aadhar','emp_pan', 'emp_appointed_organisation','emp_contact_no',
            'emp_emergency_contact_no','emp_email','emp_udin','emp_address','emp_status',
            ]);
        $this->image = '';
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Employee Created successfully'
        ]));
        $this->emp_code = $this->generateCode();
    }

    public function mount()
    {
        $this->checkPermission('Create Employee');

        $this->emp_code = $this->generateCode();
        $this->gender = Gender::pluck('name','id')->all();
        $this->emptypes = Emptype::pluck('name','id')->all();
        $this->designations = Designation::pluck('name','id')->all();
        $this->appointedorganization = Appointedorganization::pluck('name','id')->all();
        $this->appellation = Appellation::pluck('name','id')->all();
        $this->institute = Institute::pluck('name','id')->all();
    }


    public function render()
    {
        return view('livewire.employees.employee-create');
    }

    protected function checkPermission($permission)
    {
        if (!Gate::allows($permission)) {
            abort(403, 'Unauthorized action.');
        }
    }


    
    private function splitName($name)
    {
        $parts = explode(' ', trim($name), 2);
        $this->firstName = $parts[0] ?? '';
        $this->lastName = $parts[1] ?? '';
    }
}
