<div class="mt-3">
        <form wire:submit.prevent="addTag" class="row g-2 align-items-center mb-3">
            <div class="col-auto flex-grow-1" style="position:relative;">
                <input type="text"
                    wire:model.live="newTag"
                    wire:focus="loadSuggestions"
                    wire:blur="hideSuggestions"
                    placeholder="Add tag"
                    class="form-control"
                    autocomplete="off" />
                @error('newTag') <span class="text-danger small">{{ $message }}</span> @enderror
                @if($showSuggestions && !empty($suggestions))
                    <ul class="list-group position-absolute w-100 shadow-sm" style="z-index:1050;top:100%;left:0;max-height:200px;overflow-y:auto;">
                        @foreach($suggestions as $suggestion)
                            <li class="list-group-item list-group-item-action py-1 px-2" style="cursor:pointer;"
                                wire:mousedown.prevent="selectSuggestion('{{ $suggestion }}')">
                                {{ $suggestion }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-warning btn-sm">Add</button>
            </div>
        </form>
        <div class="d-flex flex-wrap gap-2">
            @foreach($tags as $tag)
                <span class="badge bg-warning text-white fs-6 px-3 py-2" style="cursor:pointer;" wire:click="removeTag('{{ $tag }}')">
                    {{ $tag }} <span class="ms-1">&times;</span>
                </span>
            @endforeach
        </div>

    </div>
