<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <h4 class="page-title">Roles</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Roles</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Roles Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-outline-primary btn-sm px-4 mb-3" wire:click="addRole()" type="button">
                                <span wire:loading wire:target="addRole">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span wire:loading.remove wire:target="addRole">
                                    Add New Role <i class="fas fa-plus"></i>
                                </span>
                            </button>

                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Role Name</th>
                                            <th>Assign Permission</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    <a href="{{ route('permissions.asign', $role->id) }}" wire:navigate class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-tasks"></i> Assign Permission
                                                    </a>
                                                </td>
                                                <td class="text-end">
                                                    <a href="javascript:;" wire:click="edit({{ $role->id }})" class="me-2 text-info">
                                                        <i class="las la-pen font-16"></i>
                                                    </a>
                                                    <a href="javascript:;" onclick="confirmDeletion({{ $role->id }})" class="text-danger">
                                                        <i class="las la-trash-alt font-16"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div>
            </div><!-- end row -->

        </div><!-- end container -->

        <!-- Footer -->
        <livewire:layout.footer />

        <!-- Modal -->
        @if($showModal)
            <div class="modal fade show d-block" style="background: rgba(0, 0, 0, .6);" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">{{ $modalMode === 'edit' ? 'Edit Role' : 'Create Role' }}</h6>
                            <button type="button" wire:click="closeModal" class="btn-close" aria-label="Close"></button>
                        </div>

                        <form wire:submit.prevent="{{ $modalMode === 'edit' ? 'update' : 'createRole' }}" class="needs-validation">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input
                                        class="form-control"
                                        type="text"
                                        placeholder="Enter Role Name..."
                                        wire:model="{{ $modalMode === 'edit' ? 'data.name' : 'roleName' }}"
                                        autocomplete="off"
                                    >
                                    @error($modalMode === 'edit' ? 'data.name' : 'roleName')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" wire:click="closeModal" class="btn btn-outline-secondary btn-sm">Close</button>
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <span wire:loading wire:target="{{ $modalMode === 'edit' ? 'update' : 'createRole' }}">
                                        <span class="spinner-border spinner-border-sm" role="status"></span> Saving...
                                    </span>
                                    <span wire:loading.remove wire:target="{{ $modalMode === 'edit' ? 'update' : 'createRole' }}">
                                        Save Changes
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        <!-- End Modal -->
    </div>
</div>

<!-- JS -->
<script>
    function confirmDeletion(roleId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteItem', { id: roleId });
            }
        });
    }
</script>
