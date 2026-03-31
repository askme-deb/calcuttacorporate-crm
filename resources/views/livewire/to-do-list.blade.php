<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">📝 To-Do List</h4>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body">
        <div class="p-3" style="height: 430px; overflow-y: auto;" id="taskListContainer" data-simplebar>
            <!-- Add Task -->
            <div class="input-group mb-3">
                <input type="text" wire:model="taskName" class="form-control" placeholder="Enter task">
                <button class="btn btn-primary" wire:click="addTask" wire:loading.attr="disabled">
                    Add
                </button>
            </div>

            <!-- Loading indicator -->
            <div wire:loading wire:target="addTask" class="text-primary mt-1 mb-2">
                <i class="fas fa-spinner fa-spin"></i> Adding task...
            </div>

            @error('taskName')
                <span class="text-danger">{{ $message }}</span>
            @enderror

            <!-- Task List -->
            <ul class="list-group" id="taskList">
                @forelse ($tasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="{{ $task->completed ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $task->name }}
                        </span>
                        <div>
                            <a style="cursor: pointer;" wire:click="toggleTask({{ $task->id }})">
                                @if ($task->completed)
                                    <i class="fas fa-undo-alt text-secondary"></i>
                                @else
                                    <i class="fas fa-check text-success"></i>
                                @endif
                            </a>

                            {{-- <a class="p-2" style="cursor: pointer;" wire:click="deleteTask({{ $task->id }})">
                                <i class="far fa-trash-alt text-danger"></i>
                            </a> --}}
                            <a class="p-2" style="cursor: pointer;" x-data
                                @click="if(confirm('Are you sure you want to delete this task?')) $wire.deleteTask({{ $task->id }})">
                                <i class="far fa-trash-alt text-danger"></i>
                            </a>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted">No tasks available.</li>
                @endforelse
            </ul>
        </div>
    </div><!--end card-body-->
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('message.processed', (message, component) => {
            let container = document.querySelector('#taskListContainer .simplebar-content-wrapper');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    });
</script>
