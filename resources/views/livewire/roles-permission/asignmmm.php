<?php

namespace App\Livewire\RolesPermission;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class AsignPermission extends Component
{
    public $permissions;
    public $selectedPermissions = [];

    public function mount()
    {
        $this->permissions = Permission::orderBy('group_name')->get();
    }


    public function toggleGroup($groupName)
    {
        $groupPermissions = $this->permissions->where('group_name', $groupName)->pluck('id')->toArray();
        if ($this->hasAllGroupPermissions($groupPermissions)) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, $groupPermissions);
        } else {
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $groupPermissions));
        }
    }


    public function hasAllGroupPermissions($groupPermissions)
    {
        return count(array_intersect($groupPermissions, $this->selectedPermissions)) === count($groupPermissions);
    }


    public function render()
    {
        return view('livewire.roles-permission.asign-permission', [
            'groupedPermissions' => $this->permissions->groupBy('group_name'),
        ]);
    }




}




@foreach ($groupedPermissions as $groupName => $permissions)
    <div class="col-12 mt-3">
        <h6 class="text-primary">{{ $groupName }}
            <div class="form-check form-switch form-switch-success d-inline-block ms-2">
                <input
                    id="checkAll{{ Str::slug($groupName) }}"
                    type="checkbox"
                    class="form-check-input"
                    wire:click="toggleGroup('{{ $groupName }}')"
                    {{ $this->hasAllGroupPermissions($permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                <label class="form-check-label" for="checkAll{{ Str::slug($groupName) }}">
                    Check All
                </label>
            </div>
        </h6>
    </div>

    @foreach ($permissions as $permission)
        <div class="col-sm-2 mt-3">
            <div class="form-check form-switch form-switch-success">
                <input
                    id="permission{{ $permission->id }}"
                    type="checkbox"
                    class="form-check-input"
                    wire:model="selectedPermissions"
                    value="{{ $permission->id }}">
                <label class="form-check-label" for="permission{{ $permission->id }}">
                    {{ $permission->name }}
                </label>
            </div>
        </div>
    @endforeach
@endforeach
