<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Title and Breadcrumb -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="page-title mb-1">All Employees</h4>
                            <p class="text-muted mb-0">Manage employee information, view statuses, and perform actions.</p>
                        </div>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Employee</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats Cards -->
            <div class="row g-3 mb-4">
                <x-stat-card title="Total Employees" value="{{ $totalEmployees }}" />
                <x-stat-card title="Active Employees" value="{{ $activeEmployees }}" textColor="text-success" />
                <x-stat-card title="Resigned" value="{{ $resignedEmployees }}" textColor="text-warning" />
                <x-stat-card title="Terminated" value="{{ $terminatedEmployees }}" textColor="text-danger" />
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h5 class="mb-0 float-strat">Employee List</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3 float-end">

                        @can('Create Employee')
                        <a href="{{ route('create-employee') }}" wire:navigate class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i> Add New Employee
                        </a>
                        @endcan
                    </div>

                </div>
            </div>

            <!-- Employee Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body">



                    <div class="table-responsive">
                        <table class="table  table-hover align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Employee Code</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Contact No</th>
                                    <th>Date of Joining</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $index => $emp)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $emp->emp_code }}</td>
                                    <td class="text-start d-flex align-items-center">
                                        <img src="{{ empProfilePicture($emp->user_id) }}" class="rounded-circle me-2" width="36" height="36" alt="Profile">
                                        <div>
                                            <div>{{ $emp->emp_first_name }} {{ $emp->emp_last_name }}</div>
                                            @if($emp->status)
                                            @php
                                            $status = strtolower($emp->status);
                                            $badgeClass = match ($status) {
                                            'active' => 'bg-success text-white',
                                            'resigned' => 'bg-warning text-dark',
                                            'terminated' => 'bg-danger text-white',
                                            default => 'bg-secondary text-white',
                                            };
                                            @endphp

                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                            @endif

                                        </div>
                                    </td>
                                    <td>{{ $emp->designation->name }}</td>
                                    <td>{{ $emp->user->email }}</td>
                                    <td>{{ $emp->emp_contact_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($emp->emp_date_of_joining)->format('d M, Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('Edit Employee')
                                            <a href="{{ route('edit-employee', ['id' => Crypt::encryptString($emp->id)]) }}" wire:navigate class="text-info" title="Edit">
                                                <i class="las la-pen font-18"></i>
                                            </a>
                                            @endcan
                                            @can('Delete Employee')
                                            <a href="javascript:;" onclick="confirmDeletion({{ $emp->id }})" class="text-danger" title="Delete">
                                                <i class="las la-trash-alt font-18"></i>
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-muted text-center">No employees found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    {{-- {{ $employees->links() }} --}}
                </div>
            </div>

        </div>

        <!-- Footer -->
        <livewire:layout.footer />
    </div>
</div>