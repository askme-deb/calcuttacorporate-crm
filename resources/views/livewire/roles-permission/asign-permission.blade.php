<div class="page-wrapper">

    <!-- Page Content-->
    <div class="page-content-tab">

        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard')}}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Roles</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Add Permission for {{ $role->name}}</h4>

                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <div class="card-header bg-primary">
                                <h4 class="card-title text-white">Permissions</h4>
                            </div>
                            <form wire:submit.prevent="updatePermissions">
                                <div class="form-group row mt-3">
                                    @foreach ($permissions->groupBy('group_name') as $groupName => $groupedPermissions)
                                    <div class="col-12 mt-3">
                                        <h5 class="text-primary">{{ $groupName }}</h5> <!-- Group Name Header -->
                                    </div>

                                    @foreach ($groupedPermissions as $permission)
                                        <div class="col-sm-2 mt-3">
                                            <div class="form-check form-switch form-switch-success">
                                                <input
                                                    id="remember{{ $permission->id }}"
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    wire:model="selectedPermissions"
                                                    value="{{ $permission->id }}">
                                                <label class="form-check-label" for="remember{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach

                                </div>

                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3">
                                        <span wire:loading wire:target="updatePermissions">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                        </span>
                                        <span wire:loading.remove wire:target="updatePermissions">
                                            Asign Permissions
                                        </span>
                                    </button>
                                </div>


                            </form>

                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->

        </div><!-- container -->

       <!--Start Footer-->
       <livewire:layout.footer />

    </div>
    <!-- end page content -->
</div>

