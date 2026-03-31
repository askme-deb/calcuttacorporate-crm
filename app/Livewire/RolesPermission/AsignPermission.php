<?php

namespace App\Livewire\RolesPermission;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class AsignPermission extends Component
{
    public $roleId;
    public $role;
    public $permissions = [];
    public $rolePermissions = [];
    public $selectedPermissions = [];

    public function mount($roleId)
    {
        $this->roleId = $roleId;
        $this->role = Role::findOrFail($this->roleId);
        $this->permissions = Permission::all();
        $this->rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $this->role->id)
            ->pluck('permission_id')
            ->toArray();
        $this->selectedPermissions = $this->rolePermissions;
       // $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
    }

    public function updatePermissions()
    {
        $role = Role::findOrFail($this->roleId);

        // Sync the selected permissions with the role
        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
        $role->syncPermissions($permissions);
        $this->rolePermissions = $this->selectedPermissions;
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Permissions Asigned successfully'
        ]));
    }

    public function render()
    {
        return view('livewire.roles-permission.asign-permission', [
            'role' => $this->role,
            'permissions' => $this->permissions,
            'rolePermissions' => $this->rolePermissions,
        ]);
    }
}
