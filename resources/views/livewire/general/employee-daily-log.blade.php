<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">📅 Daily Log</h4>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->

    <div class="card-body">
        <!-- Add Log Form -->
        <div class="mb-3">
            <input type="date" wire:model="log_date" class="form-control mb-2">
            @error('log_date') <span class="text-danger">{{ $message }}</span> @enderror

            <textarea wire:model="task_summary" class="form-control mb-2" placeholder="Task Summary"></textarea>
            @error('task_summary') <span class="text-danger">{{ $message }}</span> @enderror

            <input type="number" wire:model="hours_worked" class="form-control mb-2" placeholder="Hours Worked">
            @error('hours_worked') <span class="text-danger">{{ $message }}</span> @enderror

            <textarea wire:model="remarks" class="form-control mb-2" placeholder="Remarks (Optional)"></textarea>
            @error('remarks') <span class="text-danger">{{ $message }}</span> @enderror

            <button class="btn btn-primary" wire:click="addLog" wire:loading.attr="disabled">
                Add Log
            </button>

            <!-- Loading indicator -->
            <div wire:loading wire:target="addLog" class="text-primary mt-1 mb-2">
                <i class="fas fa-spinner fa-spin"></i> Adding log...
            </div>
        </div>

        <!-- Log Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Task Summary</th>
                    <th>Hours Worked</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->log_date)->format('d M, Y') }}</td>
                        <td>{{ $log->task_summary }}</td>
                        <td>{{ $log->hours_worked }} hrs</td>
                        <td>{{ $log->remarks ?? 'N/A' }}</td>
                        <td class="text-center">
                            <a class="p-2" style="cursor: pointer;" wire:click="deleteLog({{ $log->id }})"
                               wire:click.confirm="Are you sure you want to delete this log?">
                                <i class="far fa-trash-alt text-danger"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No logs available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div><!--end card-body-->
</div>
