<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <h4 class="page-title">Permissions</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Permissions</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Permissions Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-outline-primary btn-sm px-4 mb-3" wire:click="addPermission()" type="button">
                                <span wire:loading wire:target="addPermission">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span wire:loading.remove wire:target="addPermission">
                                    Add New Permission <i class="fas fa-plus ms-1"></i>
                                </span>
                            </button>

                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Permission Name</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions->groupBy('group_name') as $group => $groupedPermissions)
                                            <tr>
                                                <td colspan="3" class="fw-bold bg-light text-dark">{{ $group }}</td>
                                            </tr>
                                            @foreach ($groupedPermissions as $index => $permission)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $permission->name }}</td>
                                                    <td class="text-end">
                                                        <a href="javascript:;" wire:click="edit({{ $permission->id }})" class="me-2 text-info">
                                                            <i class="las la-pen font-16"></i>
                                                        </a>
                                                        <a href="javascript:;" onclick="confirmDeletion({{ $permission->id }})" class="text-danger">
                                                            <i class="las la-trash-alt font-16"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div>
            </div> <!-- end row -->

        </div> <!-- end container -->

        <!-- Footer -->
        <livewire:layout.footer />

        <!-- Modal -->
        @if($showModal)
            <div class="modal fade show d-block" style="background: rgba(0, 0, 0, .6);" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">
                                {{ $modalMode === 'edit' ? 'Edit Permission' : 'Create Permission' }}
                            </h6>
                            <button type="button" wire:click="closeModal" class="btn-close" aria-label="Close"></button>
                        </div>

                        <form wire:submit.prevent="{{ $modalMode === 'edit' ? 'update' : 'createPermission' }}" class="needs-validation">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Group Name</label>
                                    <input type="text" class="form-control"
                                           wire:model="{{ $modalMode === 'edit' ? 'data.group_name' : 'groupName' }}"
                                           placeholder="Enter group/module name..." autocomplete="off">
                                    @error($modalMode === 'edit' ? 'data.group_name' : 'groupName')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Permission Name</label>
                                    <input type="text" class="form-control"
                                           wire:model="{{ $modalMode === 'edit' ? 'data.name' : 'permissionName' }}"
                                           placeholder="Enter permission name..." autocomplete="off">
                                    @error($modalMode === 'edit' ? 'data.name' : 'permissionName')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" wire:click="closeModal" class="btn btn-outline-secondary btn-sm">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <span wire:loading wire:target="{{ $modalMode === 'edit' ? 'update' : 'createPermission' }}">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Saving...
                                    </span>
                                    <span wire:loading.remove wire:target="{{ $modalMode === 'edit' ? 'update' : 'createPermission' }}">
                                        Save Changes
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div> <!-- end modal-content -->
                </div> <!-- end modal-dialog -->
            </div> <!-- end modal -->
        @endif
    </div> <!-- end page-content-tab -->
</div> <!-- end page-wrapper -->

<!-- Delete Confirmation -->
<script>
    function confirmDeletion(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteItem', { id: itemId });
            }
        });
    }
</script>

<!-- Toast Notification -->
@if (session()->has('toast_message'))
    <div class="toast align-items-center text-white bg-{{ session('toast_type', 'success') }} position-fixed top-0 end-0 m-3 border-0 show"
         role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1055;">
        <div class="d-flex">
            <div class="toast-body">
                {{ session('toast_message') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif
