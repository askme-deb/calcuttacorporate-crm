<div class="mt-3">

       <ul class="list-group list-group-flush">
    @forelse($activities as $activity)
        @php
            $meta = match($activity->type) {
                'call' => ['color' => 'bg-primary', 'label' => 'Call', 'icon' => 'fa-phone'],
                'email' => ['color' => 'bg-success', 'label' => 'Email', 'icon' => 'fa-envelope'],
                'note' => ['color' => 'bg-warning', 'label' => 'Note', 'icon' => 'fa-note-sticky'],
                'followup' => ['color' => 'bg-info', 'label' => 'Follow-up', 'icon' => 'fa-calendar-check'],
                'deal' => ['color' => 'bg-danger', 'label' => 'Deal', 'icon' => 'fa-handshake'],
                'proposal' => ['color' => 'bg-dark', 'label' => 'Proposal', 'icon' => 'fa-file-signature'],
                'attachment' => ['color' => 'bg-secondary', 'label' => 'Attachment', 'icon' => 'fa-paperclip'],
                default => ['color' => 'bg-secondary', 'label' => ucfirst((string) $activity->type), 'icon' => 'fa-clock'],
            };

            $activityAt = $activity->activity_at ? \Illuminate\Support\Carbon::parse($activity->activity_at) : null;
            $createdAt = $activity->created_at ? \Illuminate\Support\Carbon::parse($activity->created_at) : null;
            $time = $activityAt ? $activityAt->diffForHumans() : ($createdAt ? $createdAt->diffForHumans() : '');
        @endphp

        <li class="list-group-item px-3 py-3 border-0 border-bottom">
            <div class="d-flex gap-3">

                <!-- Icon Dot -->
                <div class="flex-shrink-0 d-flex align-items-start">
                    <span class="rounded-circle {{ $meta['color'] }} text-white d-inline-flex align-items-center justify-content-center"
                          style="width:22px; height:22px; margin-top:2px; font-size:10px;">
                        <i class="fas {{ $meta['icon'] }}"></i>
                    </span>
                </div>

                <!-- Content -->
                <div class="flex-grow-1">

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="mb-1 fw-semibold text-dark">
                            {{ $meta['label'] }}
                        </h6>
                        <small class="text-muted">
                            {{ $time }}
                        </small>
                    </div>

                    <!-- Description -->
                    <p class="mb-1 text-secondary small">
                        {{ $activity->description }}
                    </p>

                    <!-- Done by -->
                    @if($activity->user)
                        <small class="text-muted">
                            <i class="fas fa-user-circle me-1"></i>{{ $activity->user->name }}
                        </small>
                    @endif

                </div>
            </div>
        </li>

    @empty
        <li class="list-group-item text-center py-4 text-muted">
            <i class="fas fa-inbox me-2"></i> No activities yet.
        </li>
    @endforelse
</ul>

</div>
