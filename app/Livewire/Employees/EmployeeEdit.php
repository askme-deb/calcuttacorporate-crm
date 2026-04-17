<?php

namespace App\Livewire\Employees;

use App\Models\Appellation;
use App\Models\Appointedorganization;
use App\Models\Employee;
use App\Models\Emptype;
use App\Models\Gender;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeEdit extends Component
{
    use WithFileUploads;
    public $image;
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
    public $fullName;
    public $firstName;
    public $lastName;
    protected $rules = [
        'emp_code' => 'required',
        'emp_type' => 'required',
        'emp_appellation' => 'required',
        'emp_first_name' => 'required|string|min:3',
        'emp_designation' => 'required',
        'emp_dob' => 'required',
        'emp_sex' => 'required',
        'emp_date_of_joining' => 'required',
        'emp_appointed_organisation' => 'required',
        'emp_contact_no' => 'required',
        'emp_address' => 'required',
      //  'emp_email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'nullable|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
        // 'selectedRoles' => 'required',
        //
    ];

    public function generatePassword()
    {
        $length = 12;
        $alphanumeric = str()->random($length - 4);
        $specialCharacters = '!@#$%^&*()';
        $randomSpecials = substr(str_shuffle($specialCharacters), 0, 4);
        $this->password = str_shuffle($alphanumeric . $randomSpecials);
    }
    public function updateEmployee()
    {
        $this->validate();
        $this->employee = Employee::findOrFail($this->empId);
        $this->splitName($this->emp_first_name);
        $this->employee->update([
            'emp_code' => $this->emp_code,
            'emp_first_name' => $this->firstName,
            'emp_last_name' => $this->lastName,
            'emp_type' => $this->emp_type,
            'emp_appellation' => $this->emp_appellation,
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
            //  'emp_status' => $this->emp_status
        ]);
        $this->user = User::findOrFail($this->employee->user_id);
        $this->user->update([
            'name' => $this->fullName,
        ]);
        if($this->password){
            $this->user = User::findOrFail($this->employee->user_id);
            $this->user->update([
                'password' => Hash::make($this->pull('password')),
            ]);
        }

        if ($this->image) {
            $this->employee->clearMediaCollection('employee');
            $this->employee->addMedia($this->image->getRealPath())
        ->usingFileName($this->image->getClientOriginalName())
        ->toMediaCollection('employee');
        }

        if ($this->aadhar_card) {
            $this->employee->clearMediaCollection('aadhar');
            $this->employee->addMedia($this->aadhar_card->getRealPath())
        ->usingFileName($this->aadhar_card->getClientOriginalName())
        ->toMediaCollection('aadhar');
        }

        if ($this->emp_pancard) {
            $this->employee->clearMediaCollection('pan');
            $this->employee->addMedia($this->emp_pancard->getRealPath())
        ->usingFileName($this->emp_pancard->getClientOriginalName())
        ->toMediaCollection('pan');
        }

        if ($this->eng_letter) {
            $this->employee->clearMediaCollection('engagementLetter');
            $this->employee->addMedia($this->eng_letter->getRealPath())
        ->usingFileName($this->eng_letter->getClientOriginalName())
        ->toMediaCollection('engagementLetter');
        }
        // Reset form fields
        $this->reset(
            ['emp_code', 'emp_type', 'emp_appellation', 'emp_first_name',
            'emp_designation','emp_dob','emp_sex','emp_date_of_joining',
            'emp_aadhar','emp_pan', 'emp_appointed_organisation','emp_contact_no',
            'emp_emergency_contact_no','emp_email','emp_udin','emp_address','emp_status',
            ]);
        $this->image = '';
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Employee Updated successfully'
        ]));

        return redirect()->route('employee');

    }



    public function mount($id)
    {
        try {
            $decryptedId = Crypt::decryptString($id); // Decrypt the ID
            $this->employee = Employee::with(['user', 'designation', 'empType'])
            ->findOrFail($decryptedId);
            $this->updateFullName($this->employee->emp_first_name, $this->employee->emp_last_name);
            $this->emp_code = $this->employee->emp_code;
            $this->emp_type = $this->employee->emp_type;
            $this->emp_appellation = $this->employee->emp_appellation;
            $this->emp_first_name = $this->fullName;
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
            $this->userimage = $this->employee->getFirstMediaUrl('employee');
            $this->empId = $decryptedId;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $this->dispatch('toastMessage', json_encode([
                'type'=>'error',
                'message' => 'Invalid ID'
            ]));
            return redirect()->route('employee')->with('error', 'Invalid ID');
        }

        $this->gender = Gender::pluck('name','id')->all();
        $this->emptypes = Emptype::pluck('name','id')->all();
        $this->designations = Designation::pluck('name','id')->all();
        $this->appointedorganization = Appointedorganization::pluck('name','id')->all();
        $this->appellation = Appellation::pluck('name','id')->all();
    }
    public function render()
    {
        return view('livewire.employees.employee-edit');
    }

    private function updateFullName($firstName, $lastName)
    {
        $this->fullName = trim($firstName . ' ' . $lastName);
    }

    private function splitName($name)
    {
        $parts = explode(' ', trim($name), 2);
        $this->firstName = $parts[0] ?? '';
        $this->lastName = $parts[1] ?? '';
    }
}
