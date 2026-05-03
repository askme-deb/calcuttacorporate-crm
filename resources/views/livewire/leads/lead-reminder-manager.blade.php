<div class="mt-3">
        <form wire:submit.prevent="addReminder" class="row g-2 align-items-center mb-3">
            <div class="col-md-12">
                <input type="text" wire:model="title" placeholder="Title" class="form-control" required />
            </div>
            <div class="col-md-12">
                <input type="datetime-local" wire:model="remind_at" class="form-control" required />
            </div>
            <div class="col-md-12">
                <input type="text" wire:model="description" placeholder="Description" class="form-control" />
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-outline-danger btn-sm">Add Reminder</button>
            </div>
        </form>
        <ul class="list-group">
           @forelse($reminders as $reminder)
    <li class="list-group-item border-0 shadow-sm rounded-3 mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">

            <!-- Left Content -->
            <div class="flex-grow-1">
                <h6 class="mb-1 fw-bold text-dark">
                    {{ $reminder->title }}
                </h6>

                <p class="mb-1 text-muted small">
                    {{ $reminder->description ?? 'No description provided' }}
                </p>

                <span class="badge bg-light text-secondary border small">
                    <i class="fas fa-clock me-1"></i>
                    {{ $reminder->remind_at->format('d M Y • h:i A') }}
                </span>
            </div>

            <!-- Right Side (Optional Action Area) -->
            <div class="text-end">
                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                    Reminder
                </span>
            </div>

        </div>
    </li>
@empty
    <li class="list-group-item text-center text-muted py-4">
        <div class="d-flex flex-column align-items-center">
            <i class="fas fa-bell-slash fa-2x mb-2 opacity-50"></i>
            <span class="fw-medium">No reminders available</span>
            <small class="text-muted">You're all caught up!</small>
        </div>
    </li>
@endforelse
        </ul>

</div>
