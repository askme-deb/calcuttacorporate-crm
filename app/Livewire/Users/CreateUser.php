<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

class CreateUser extends Component
{
    use WithFileUploads;
    public $image;
    public $name;
    public $email;
    public $password;
    public $roles = [];
    public $selectedRoles  = [];
    public $user;
    protected $listeners = ['selectedRoles' => 'HandleselectedRoles'];

 
    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
        'selectedRoles' => 'required',
        // 'image' => 'required'
    ];
    public function updated($fields)
    {
        // $this->validateOnly($fields,[
        //   //  'image' => 'required',
        // ]);
    }
    public function HandleselectedRoles($Roles)
    {
        $this->selectedRoles = $Roles;
    }
    public function mount()
    {
        $this->roles = Role::pluck('name', 'name')->toArray();
    }

    public function addUser()
    {
        // Validate form data
        $this->validate();
        //dd($this->selectedRoles);
        // Create the new user
        $model = $this->user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        if ($this->image) {
        $model->addMedia($this->image->getRealPath())
        ->usingFileName($this->image->getClientOriginalName())
        ->toMediaCollection('user');
        }
        // Assign selected roles to the user if any
        if (!empty($this->selectedRoles)) {
            $this->user->syncRoles($this->selectedRoles); 
        }

        // Reset form fields
        $this->reset(['name', 'email', 'password', 'selectedRoles']);
        $this->image = '';
        $this->dispatch('select2Reset'); 
       // $this->selectedRoles = [];
        // Dispatch a success toast message to the frontend
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'User Created successfully'
        ]));
        
    }
    

    public function render()
    { //dump($this->selectedRoles);
        return view('livewire.users.create-user');
    }
}
