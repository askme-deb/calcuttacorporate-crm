<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-dark mb-0">Lead Pipeline</h4>
        <div>
            <button wire:click="$set('viewMode', 'kanban')" class="btn btn-outline-primary btn-sm me-1 @if($viewMode==='kanban') active text-white bg-primary border-primary @endif">Kanban</button>
            <button wire:click="$set('viewMode', 'table')" class="btn btn-outline-primary btn-sm @if($viewMode==='table') active text-white bg-primary border-primary @endif">Table</button>
        </div>
    </div>

    @if($viewMode === 'kanban')
        <div class="d-flex flex-row gap-3 overflow-auto pb-3">
            @foreach($statuses as $statusKey => $statusLabel)
                <div class="card shadow-sm flex-shrink-0" style="min-width: 300px; max-width: 320px;">
                    <div class="card-header bg-light fw-semibold text-primary">{{ $statusLabel }}</div>
                    <div class="card-body p-2" style="min-height: 60px;">
                        @foreach($leadsByStatus[$statusKey] as $lead)
                            <div class="card mb-2 border-primary border-1 cursor-move" draggable="true"
                                wire:dragstart="window.livewire.emit('dragStart', {{ $lead->id }}, '{{ $statusKey }}')"
                                wire:drop="updateLeadStatus({{ $lead->id }}, '{{ $statusKey }}')">
                                <div class="card-body py-2 px-3">
                                    <div class="fw-bold text-dark">{{ $lead->name }}</div>
                                    <div class="text-secondary small">{{ $lead->company }}</div>
                                    <div class="text-muted small">Deal: ₹{{ number_format($lead->deal_value) }}</div>
                                    <span class="badge bg-info text-dark mt-1">{{ $statusLabel }}</span>
                                    <div class="text-muted small mt-1">Last: {{ $lead->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Deal Value</th>
                                <th>Status</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statuses as $statusKey => $statusLabel)
                                @foreach($leadsByStatus[$statusKey] as $lead)
                                    <tr>
                                        <td>{{ $lead->name }}</td>
                                        <td>{{ $lead->company }}</td>
                                        <td>₹{{ number_format($lead->deal_value) }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark">{{ $statusLabel }}</span>
                                        </td>
                                        <td>{{ $lead->updated_at->diffForHumans() }}</td>
                                        <td>
                                            @php $proposal = $lead->proposals()->latest()->first(); @endphp
                                            @if($proposal)
                                                <a href="{{ route('proposals.preview', $proposal) }}" target="_blank" class="text-primary text-decoration-underline">Preview</a>
                                                @include('livewire.proposals.proposal-status-badge', ['status' => $proposal->status])
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
