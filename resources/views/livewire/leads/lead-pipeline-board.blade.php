<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h4 class="page-title mb-0 fw-semibold">Lead Pipeline</h4>
                </div>

                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <ol class="breadcrumb justify-content-md-end mb-2">
                        <li class="breadcrumb-item">
                            <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a wire:navigate href="{{ route('leads') }}">Leads</a>
                        </li>
                        <li class="breadcrumb-item active">Pipeline</li>
                    </ol>

                    <!-- View Toggle -->
                    <div class="btn-group shadow-sm">
                        <button wire:click="$set('viewMode', 'kanban')"
                            class="btn btn-sm {{ $viewMode==='kanban' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-columns me-1"></i> Kanban
                        </button>

                        <button wire:click="$set('viewMode', 'table')"
                            class="btn btn-sm {{ $viewMode==='table' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-table me-1"></i> Table
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-4">

                @if($viewMode === 'kanban')

                    <!-- Kanban Board -->
                    <div class="d-flex gap-3 overflow-auto pb-3">

                        @foreach($statuses as $statusKey => $statusLabel)
                            <div class="card border-0 shadow-sm flex-shrink-0"
                                 style="min-width: 300px; max-width: 320px;">

                                <!-- Column Header -->
                                <div class="card-header bg-white border-bottom fw-semibold text-primary">
                                    {{ $statusLabel }}
                                    <span class="badge bg-light text-dark ms-2">
                                        {{ count($leadsByStatus[$statusKey]) }}
                                    </span>
                                </div>

                                <!-- Column Body -->
                                <div class="card-body p-2" style="min-height: 80px;">

                                    @foreach($leadsByStatus[$statusKey] as $lead)
                                        <div class="card border bg-white mb-2 lead-card"
     draggable="true"
     wire:dragstart="window.livewire.emit('dragStart', {{ $lead->id }}, '{{ $statusKey }}')"
     wire:drop="updateLeadStatus({{ $lead->id }}, '{{ $statusKey }}')">

    <div class="card-body p-3">

        <!-- Top: Name + optional actions -->
        <div class="d-flex justify-content-between align-items-start">

            <div class="flex-grow-1 min-w-0">
                <div class="fw-semibold text-truncate">
                    {{ $lead->name }}
                </div>

                <div class="text-muted small text-truncate">
                    {{ $lead->company }}
                </div>
            </div>

        </div>

        <!-- Deal Value -->
        <div class="mt-2 fw-semibold text-dark">
            ₹{{ number_format($lead->deal_value) }}
        </div>

        <!-- Footer -->
        <div class="d-flex justify-content-between align-items-center mt-2">

            <span class="badge badge-status">
                {{ $statusLabel }}
            </span>

            <small class="text-muted">
                {{ $lead->updated_at->diffForHumans() }}
            </small>

        </div>

    </div>
</div>
                                    @endforeach

                                </div>
                            </div>
                        @endforeach

                    </div>

                @else

                    <!-- Table View -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">

                                    <thead class="table-light">
                                        <tr class="text-muted small text-uppercase">
                                            <th>Name</th>
                                            <th>Company</th>
                                            <th>Deal</th>
                                            <th>Status</th>
                                            <th>Last Activity</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($statuses as $statusKey => $statusLabel)
                                            @foreach($leadsByStatus[$statusKey] as $lead)
                                                <tr>

                                                    <td class="fw-semibold">
                                                        {{ $lead->name }}
                                                    </td>

                                                    <td class="text-muted">
                                                        {{ $lead->company }}
                                                    </td>

                                                    <td class="fw-bold text-success">
                                                        ₹{{ number_format($lead->deal_value) }}
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-info-subtle text-info">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </td>

                                                    <td class="text-muted small">
                                                        {{ $lead->updated_at->diffForHumans() }}
                                                    </td>

                                                    <td class="text-end">
                                                        @php
                                                            $proposal = $lead->proposals()->latest()->first();
                                                        @endphp

                                                        @if($proposal)
                                                            <a href="{{ route('proposals.preview', $proposal) }}"
                                                               target="_blank"
                                                               class="btn btn-sm btn-light-primary me-1">
                                                                Preview
                                                            </a>

                                                            @include('livewire.proposals.proposal-status-badge', ['status' => $proposal->status])
                                                        @else
                                                            <span class="text-muted small">No Proposal</span>
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

        </div>
    </div>
</div>
