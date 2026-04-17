<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-sm-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0"><i class="bi bi-envelope-fill me-2 text-primary"></i> Manage Email Accounts</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Appellation</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Flash Message -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Email Account Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i> {{ $emailAccountId ? 'Edit' : 'Add' }} Email Account</h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" wire:click="resetForm">
                        <i class="bi bi-arrow-repeat"></i> Reset
                    </button>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save" class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" wire:model="email_address" placeholder="you@example.com">
                            @error('email_address') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" wire:model="name" placeholder="Display Name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">IMAP Host</label>
                            <input type="text" class="form-control" wire:model="imap_host" placeholder="imap.example.com">
                            @error('imap_host') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IMAP Port</label>
                            <input type="number" class="form-control" wire:model="imap_port">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IMAP Encryption</label>
                            <select class="form-select" wire:model="imap_encryption">
                                <option value="ssl">SSL</option>
                                <option value="tls">TLS</option>
                                <option value="">None</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">SMTP Host</label>
                            <input type="text" class="form-control" wire:model="smtp_host" placeholder="smtp.example.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" wire:model="smtp_port">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SMTP Encryption</label>
                            <select class="form-select" wire:model="smtp_encryption">
                                <option value="ssl">SSL</option>
                                <option value="tls">TLS</option>
                                <option value="">None</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">SMTP Username</label>
                            <input type="text" class="form-control" wire:model="smtp_username">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Password</label>
                            <input type="password" class="form-control" wire:model="smtp_password">
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="active" value="1" id="activeCheck">
                                <label class="form-check-label" for="activeCheck">Active</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> {{ $emailAccountId ? 'Update' : 'Add' }} Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Accounts Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i> Saved Accounts</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>IMAP</th>
                                <th>SMTP</th>
                                <th>Status</th>
                                <th class="text-center" width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($accounts as $acc)
                                <tr>
                                    <td>{{ $acc->id }}</td>
                                    <td>{{ $acc->email_address }}</td>
                                    <td>{{ $acc->name }}</td>
                                    <td><span class="text-muted">{{ $acc->imap_host }}:{{ $acc->imap_port }} ({{ $acc->imap_encryption }})</span></td>
                                    <td><span class="text-muted">{{ $acc->smtp_host }}:{{ $acc->smtp_port }} ({{ $acc->smtp_encryption }})</span></td>
                                    <td>
                                        @if ($acc->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="testConnection({{ $acc->id }})" class="btn btn-sm btn-success me-1">
                                            <i class="bi bi-wifi"></i> Test
                                        </button>
                                        <button wire:click="edit({{ $acc->id }})" class="btn btn-sm btn-info me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button wire:click="delete({{ $acc->id }})" onclick="return confirm('Delete this account?')" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No accounts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <livewire:layout.footer />
    </div>
</div>
