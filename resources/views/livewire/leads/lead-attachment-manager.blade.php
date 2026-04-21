<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Attachments</h5>
        <form wire:submit.prevent="upload" class="row g-2 align-items-center mb-3" enctype="multipart/form-data">
            <div class="col-md-8">
                <input type="file" wire:model="file" class="form-control" />
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Upload</button>
            </div>
        </form>
        <ul class="list-group">
            @forelse($attachments as $attachment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-primary text-decoration-underline">{{ $attachment->file_name }}</a>
                    <span class="text-muted small">({{ $attachment->created_at->diffForHumans() }})</span>
                </li>
            @empty
                <li class="list-group-item text-muted">No attachments uploaded.</li>
            @endforelse
        </ul>
    </div>
</div>
