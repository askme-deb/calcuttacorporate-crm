<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Reminders</h5>
        <form wire:submit.prevent="addReminder" class="row g-2 align-items-center mb-3">
            <div class="col-md-3">
                <input type="text" wire:model="title" placeholder="Title" class="form-control" required />
            </div>
            <div class="col-md-3">
                <input type="datetime-local" wire:model="remind_at" class="form-control" required />
            </div>
            <div class="col-md-4">
                <input type="text" wire:model="description" placeholder="Description" class="form-control" />
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Add</button>
            </div>
        </form>
        <ul class="list-group">
            @forelse($reminders as $reminder)
                <li class="list-group-item d-flex flex-column flex-md-row align-items-md-center justify-content-between py-2">
                    <div class="fw-semibold">{{ $reminder->title }}</div>
                    <div class="text-muted small">{{ $reminder->remind_at->format('d M Y H:i') }}</div>
                    <div class="text-secondary small">{{ $reminder->description }}</div>
                </li>
            @empty
                <li class="list-group-item text-muted">No reminders set.</li>
            @endforelse
        </ul>
    </div>
</div>
