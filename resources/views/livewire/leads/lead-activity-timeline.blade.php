<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Activity Timeline</h5>
        <ul class="list-group list-group-flush">
            @forelse($activities as $activity)
                <li class="list-group-item d-flex align-items-start gap-3">
                    <span class="badge rounded-pill mt-1"
                        style="width:18px;height:18px;"
                        @php
                            $color = match($activity->type) {
                                'call' => 'bg-primary',
                                'email' => 'bg-success',
                                'note' => 'bg-warning text-dark',
                                default => 'bg-secondary',
                            };
                        @endphp
                        class="{{ $color }}">
                        &nbsp;
                    </span>
                    <div>
                        <div class="fw-semibold">{{ ucfirst($activity->type) }}</div>
                        <div class="text-secondary">{{ $activity->description }}</div>
                        <div class="small text-muted mt-1">{{ $activity->activity_at ? $activity->activity_at->diffForHumans() : $activity->created_at->diffForHumans() }}</div>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted">No activities yet.</li>
            @endforelse
        </ul>
    </div>
</div>
