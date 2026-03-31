<?php

namespace App\Livewire\RolesPermission;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{
    public $permissions;
    public $showModal = false;
    public $modalMode = '';
    public $permissionName, $groupName;
    public $permissionId;
    public $data = [];
   // public $selectedRole;
    public $permissionsForRole = [];

    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadPermissions'];
    protected $rules = [
        'permissionsForRole' => 'array',
    ];

    public function rules()
    {
        return [
            'permissionName' => $this->modalMode === 'create'
            ? 'required|string|min:3|unique:roles,name'
            : 'nullable',
            'data.name' => $this->modalMode === 'edit'
            ? 'required|string|min:3|unique:roles,name,' . $this->permissionId
                : 'nullable',
            'permissionsForRole' => 'array',
        ];
    }

    public function addPermission()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
    }

    public function createPermission()
    {
        $this->validate();
        Permission::create(['name' => $this->permissionName, 'group_name'=>$this->groupName]);
        $this->permissions = Permission::all();
        $this->permissionName = '';
        $this->groupName = '';
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Permission Created successfully'
        ]));

    }

    public function closeModal()
    {
        $this->reset(['showModal', 'permissionName', 'groupName']);
    }

    public function edit($id)
    {
        $this->showModal = true;
        $this->modalMode = 'edit';
        $this->permissionId = $id;
        $this->data = Permission::findOrFail($id)->toArray();
       // dd($this->data);
    }

    public function update()
    {
        $this->validate();
        $record = Permission::findOrFail($this->permissionId);
        $record->update($this->data);
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Permission Updated successfully'
        ]));

    }

    public function deleteItem($id)
    {
        $item = Permission::find($id);
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
    public function mount()
    {
        $this->permissions =  Permission::where('guard_name', 'web')
        ->orderBy('group_name')
        ->get();
    }

    public function render()
    {
        return view('livewire.roles-permission.permission', [
            'permissions' => $this->permissions,
        ]);
    }

    public function loadPermissions()
    {
        $this->permissions =  Permission::where('guard_name', 'web')
        ->orderBy('group_name')
        ->get();
    }

}
