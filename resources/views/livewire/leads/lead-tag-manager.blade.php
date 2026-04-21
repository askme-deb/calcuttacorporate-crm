<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Tags</h5>
        <form wire:submit.prevent="addTag" class="row g-2 align-items-center mb-3">
            <div class="col-auto flex-grow-1">
                <input type="text" wire:model="newTag" placeholder="Add tag" class="form-control" required />
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
        <div class="d-flex flex-wrap gap-2">
            @foreach($tags as $tag)
                <span class="badge bg-info text-dark fs-6 px-3 py-2" style="cursor:pointer;" wire:click="removeTag('{{ $tag }}')">
                    {{ $tag }} <span class="ms-1">&times;</span>
                </span>
            @endforeach
        </div>
    </div>
</div>
