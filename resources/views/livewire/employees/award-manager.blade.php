<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Awards Management</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Awards Management</h4>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                                        placeholder="Search by employee name...">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <button class="btn btn-primary float-end" wire:click="showForm"
                                        wire:loading.attr="disabled" wire:target="showForm">
                                        <span wire:loading wire:target="showForm"
                                            class="spinner-border spinner-border-sm me-1" role="status"
                                            aria-hidden="true"></span>
                                        <i class="fas fa-plus me-1"></i> Add Award
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-semibold">#</th>
                                            <th class="fw-semibold">Employee</th>
                                            <th class="fw-semibold">Title</th>
                                            <th class="fw-semibold">Type</th>
                                            <th class="fw-semibold">Date</th>
                                            <th class="fw-semibold">Description</th>
                                            <th class="fw-semibold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($awards as $index => $award)
                                            <tr>
                                                <td>{{ $awards->firstItem() + $loop->index }}</td>
                                                <td>{{ $award->employee->name }}</td>
                                                <td>{{ $award->title }}</td>
                                                <td>{{ $award->type }}</td>
                                                <td>{{ \Carbon\Carbon::parse($award->award_date)->format('d M Y') }}
                                                </td>
                                                <td>{{ Str::limit($award->description, 50) }}</td>
                                                <td>
                                                    <button wire:click="edit({{ $award->id }})"
                                                        class="btn btn-sm btn-primary"
                                                        wire:loading.attr="disabled"
                                                        wire:target="edit({{ $award->id }})">
                                                        <span wire:loading wire:target="edit({{ $award->id }})"
                                                            class="spinner-border spinner-border-sm me-1" role="status"
                                                            aria-hidden="true"></span>
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    @if($search)
                                                        No awards found for "{{ $search }}".
                                                    @else
                                                        No awards found.
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                @if($awards->hasPages())
                                    <div class="d-flex justify-content-center">
                                        {{ $awards->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        @if($showFormModal)
            <div class="modal fade show d-block" tabindex="-1"
                style="display:block; background: rgba(0,0,0,0.5);" role="dialog"
                wire:ignore.self>
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $isEdit ? 'Edit Award' : 'Create Award' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeForm"></button>
                        </div>

                        <form wire:submit.prevent="save">
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                                        <select wire:model="employee_id" class="form-select @error('employee_id') is-invalid @enderror">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="Enter award title">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Type <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="type"
                                            class="form-control @error('type') is-invalid @enderror"
                                            placeholder="Enter award type">
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Award Date <span class="text-danger">*</span></label>
                                        <input type="date" wire:model="award_date"
                                            class="form-control @error('award_date') is-invalid @enderror">
                                        @error('award_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea wire:model="description"
                                            class="form-control @error('description') is-invalid @enderror"
                                            rows="3"
                                            placeholder="Enter award description"></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <!-- Submit Button with Loading -->
                                <button type="submit" class="btn btn-primary"
                                    wire:loading.attr="disabled" wire:target="save">
                                    <span wire:loading wire:target="save"
                                        class="spinner-border spinner-border-sm me-1"
                                        role="status" aria-hidden="true"></span>
                                    {{ $isEdit ? 'Update Award' : 'Create Award' }}
                                </button>

                                <!-- Close Button -->
                                <button type="button" wire:click="closeForm"
                                    class="btn btn-secondary"
                                    wire:loading.attr="disabled" wire:target="closeForm">
                                    <span wire:loading wire:target="closeForm"
                                        class="spinner-border spinner-border-sm me-1"
                                        role="status" aria-hidden="true"></span>
                                    Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <livewire:layout.footer />
    </div>
</div>
