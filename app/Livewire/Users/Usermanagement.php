<?php
namespace App\Livewire\Users;
use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Usermanagement extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $user;
    public $userId;
    public $data = [];
    public $name;
    public $email;
    public $password;
    public $userRoles;
    public $roles;
    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadUsers'];
    
    public function mount()
    {
        // $this->users = User::with('media')->get();
        // $this->roles = Role::pluck('name','name')->all();
    }



    public function render()
    {
        // $users = User::paginate(10);
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->paginate(10);
        
        return view('livewire.users.usermanagement', [
            'roles' => $this->roles,
            'users' => $users
        ]);
    }


    public function deleteItem($id)
    {
        $item = User::find($id);
        if ($item) {
            $item->delete();
            $this->dispatch('refreshComponent');
            $this->dispatch('swal:success', json_encode([
                'title' => 'Item Deleted',
                'text' => 'The item has been successfully deleted.',
                'icon' => 'success',
            ]));
        }
    }

    public function loadUsers()
    {
        // $this->users = User::all();
        // $this->roles = Role::pluck('name','name')->all();
    }
}
