<?php

namespace App\Livewire\RolesPermission;

use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagement extends Component
{
    public $roles;
    public $permissions;
    public $roleName;
    public $selectedRole;
    public $permissionsForRole = [];
    public $showModal = false;
    public $roleId;
    public $data = [];
    public $modalMode = '';
    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadRoles'];
    protected $rules = [
        'permissionsForRole' => 'array',
    ];

    public function rules()
    {
        return [
            'roleName' => $this->modalMode === 'create'
            ? 'required|string|min:3|unique:roles,name'
            : 'nullable',
            'data.name' => $this->modalMode === 'edit'
            ? 'required|string|min:3|unique:roles,name,' . $this->roleId
                : 'nullable',
            'permissionsForRole' => 'array',
        ];
    }


    protected function authorizeAction()
{
    $routeName = request()->route()->getName(); // Get the name of the current route

    $permissions = [
        'addRole' => 'Create Role',
        'createRole' => 'Create Role',
    ];

    // Loop through the permissions to check if the current route matches an action
    foreach ($permissions as $action => $permission) {
        if (str_contains($routeName, $action)) { // Check if the route name contains the action
            if (!FacadesGate::allows($permission)) { // If the user lacks the required permission
                abort(403, 'Unauthorized'); // Stop execution and show a 403 error
            }
        }
    }
}

    public function mount()
    {
        $this->authorizeAction();
        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    public function addRole()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
    }

    public function createRole()
    {
        $this->validate();
        Role::create(['name' => $this->roleName]);
        $this->roles = Role::all();
        $this->roleName = '';
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Role Created successfully'
        ]));

    }

    public function closeModal()
    {
        $this->reset(['showModal', 'roleName']);
    }


    public function edit($id)
    {
        $this->showModal = true;
        $this->modalMode = 'edit';
        $this->roleId = $id;
        $this->data = Role::findOrFail($id)->toArray();
    }

    public function update()
    {
        $this->validate();
        $record = Role::findOrFail($this->roleId);
        $record->update($this->data);
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Role Updated successfully'
        ]));

    }

    public function deleteItem($id)
    {
        $item = Role::find($id);
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

    // public function selectRole($roleId)
    // {
    //     $this->selectedRole = Role::find($roleId);
    //     $this->permissionsForRole = $this->selectedRole->permissions->pluck('name')->toArray();
    // }

    // public function updateRolePermissions()
    // {
    //     if ($this->selectedRole) {
    //         $this->selectedRole->syncPermissions($this->permissionsForRole);
    //         session()->flash('success', 'Permissions updated successfully.');
    //     }
    // }

    public function render()
    {
        return view('livewire.roles-permission.role-management', [
            'roles' => $this->roles,
        ]);
    }

    public function loadRoles()
    {
        $this->roles = Role::all();
    }



    
}
