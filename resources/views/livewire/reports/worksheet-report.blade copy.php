<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Worksheet Report</h1>
                    <small class="text-muted">Overview of all tasks, statuses, and employees</small>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('worksheet') }}">Worksheet</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tasks</li>
                    </ol>
                </nav>
            </div>
<!-- Only visible while Livewire is loading -->
<!-- Full-page loader for actions -->




            <!-- Filters -->
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold small">Filters</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <input type="text" wire:model.live="search" class="form-control"
                                placeholder="Search Title...">
                        </div>
                        <div class="col-md-2">
                            <select wire:model.live="employee_id" class="form-select">
                                <option value="">-- Employee --</option>
                                @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select wire:model.live="status_id" class="form-select">
                                <option value="">-- Status --</option>
                                @foreach ($statuses as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" wire:model.live="date_from" class="form-control"
                                placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" wire:model.live="date_to" class="form-control" placeholder="To Date">
                        </div>
                        <div class="col-md-2">
                            <button wire:click="resetFilters" class="btn btn-secondary w-100" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-undo me-1"></i> Refresh
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin me-1"></i> Refreshing...
                                </span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold small">Export Options</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label small">Select Columns to Export:</label>
                            <div class="row">
                                @foreach ($availableColumns as $key => $label)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="selectedColumns"
                                            value="{{ $key }}" id="col_{{ $key }}">
                                        <label class="form-check-label small" for="col_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="w-100">
                                <button wire:click="exportExcel" class="btn btn-success me-2 w-100 mb-2">
                                    <i class="fas fa-file-excel me-1"></i>Export Excel
                                </button>
                                <button wire:click="exportPdf" class="btn btn-danger w-100">
                                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="mb-3">
                <small class="text-muted">
                    Showing {{ $worksheets->count() }} of {{ $worksheets->total() }} results
                </small>
            </div>

            <!-- Table -->
            <div class="card shadow-sm">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="15%">Title</th>
                                <th width="12%">Work</th>
                                <th width="15%">Assigned To</th>
                                <th width="10%">Start</th>
                                <th width="10%">Deadline</th>
                                <th width="10%">Status</th>
                                <th width="28%">Latest Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($worksheets as $ws)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $ws->title }}</div>
                                    @if ($ws->client)
                                    <small class="text-muted">Client: {{ $ws->client->name }}</small>
                                    @endif
                                </td>
                                <td>{{ $ws->work->name ?? '-' }}</td>
                                <td>
                                    @forelse($ws->teamMembers ?? [] as $tm)
                                    <button wire:click="showEmployeeDetails({{ $tm->user->id }})"
                                        class="btn btn-link p-0 text-decoration-none small d-block text-start">
                                        <i class="fas fa-user me-1"></i>{{ $tm->user->name }}
                                    </button>
                                    @empty
                                    <span class="text-muted small">Not assigned</span>
                                    @endforelse
                                </td>
                                <td>
                                    <small>{{ $ws->start_date ? \Carbon\Carbon::parse($ws->start_date)->format('M d, Y') : '-' }}</small>
                                </td>
                                <td>
                                    <small>{{ $ws->deadline ? \Carbon\Carbon::parse($ws->deadline)->format('M d, Y') : '-' }}</small>
                                </td>
                                <td>
                                    @if ($ws->status)
                                    {{-- Status Badge --}}
                                    <span
                                        class="badge
                                                @if ($ws->status->name == 'Completed') bg-success
                                                @elseif($ws->status->name == 'Pending') bg-danger
                                                @elseif($ws->status->name == 'In Progress') bg-warning text-dark
                                                @else bg-secondary @endif">
                                        {{ $ws->status->name }}
                                    </span>

                                    {{-- ✅ Completed Case --}}
                                    @if ($ws->status->name == 'Completed' && !empty($ws->completed_on))
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        on: {{ \Carbon\Carbon::parse($ws->completed_on)->format('M d, Y') }}
                                    </div>

                                    @if (!empty($ws->completedBy))
                                    <div class="small text-muted">
                                        <i class="fas fa-user-check me-1"></i>
                                        by: {{ $ws->completedBy->name }}
                                    </div>
                                    @endif

                                    {{-- Delay / Early Info (only if deadline exists) --}}
                                    @if (!empty($ws->deadline))
                                    @php
                                    $deadline = \Carbon\Carbon::parse($ws->deadline);
                                    $completed = \Carbon\Carbon::parse($ws->completed_on);
                                    $diff = $completed->diffInDays($deadline, false); // negative if late
                                    @endphp
                                    <div class="small mt-1
                    @if($diff < 0) text-danger
                    @elseif($diff > 0) text-success
                    @else text-secondary @endif">
                                        <i class="fas fa-hourglass-end me-1"></i>
                                        @if($diff < 0)
                                            Delayed by {{ abs($diff) }} day(s)
                                            @elseif($diff> 0)
                                            Completed {{ $diff }} day(s) early
                                            @else
                                            Completed on time
                                            @endif
                                    </div>
                                    @endif
                                    @endif

                                    {{-- ✅ Pending / In Progress Case --}}
                                    @if(in_array($ws->status->name, ['Pending', 'In Progress']) && !empty($ws->deadline))
                                    @php
                                    $deadline = \Carbon\Carbon::parse($ws->deadline);
                                    $today = \Carbon\Carbon::today();
                                    $remaining = $today->diffInDays($deadline, false); // negative if overdue
                                    @endphp
                                    <div class="small mt-1
                @if($remaining < 0) text-danger
                @elseif($remaining > 0) text-info
                @else text-warning @endif">
                                        <i class="fas fa-clock me-1"></i>
                                        @if($remaining < 0)
                                            Overdue by {{ abs($remaining) }} day(s)
                                            @elseif($remaining> 0)
                                            {{ $remaining }} day(s) left
                                            @else
                                            Deadline today
                                            @endif
                                    </div>
                                    @endif

                                    {{-- ✅ Any Other Status (fallback to updated_at) --}}
                                    @if(!in_array($ws->status->name, ['Completed', 'Pending', 'In Progress']))
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-history me-1"></i>
                                        Last updated on: {{ $ws->updated_at?->format('M d, Y H:i A') }}
                                    </div>
                                    @endif
                                    @else
                                    <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>


                                <td>
                                    @php
                                    $latestRemark = $ws->latestRemark;
                                    $totalRemarks = $ws->remarks?->count() ?? 0;
                                    @endphp

                                    @if ($latestRemark)
                                    <div class="d-flex flex-column gap-1">
                                        {{-- Remark text --}}
                                        <div class="text-dark" style="font-size: 0.9rem;">
                                            {!! $latestRemark->remarks !!}
                                        </div>

                                        {{-- User and timestamp --}}
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            Remark by - <i class="fas fa-user me-1"></i>{{ $latestRemark->user->name ?? 'Unknown' }}
                                            &nbsp;&nbsp;
                                            <i class="fas fa-clock me-1"></i>{{ $latestRemark->created_at?->format('M d, Y H:i A') ?? '' }}
                                        </div>

                                        {{-- More comments --}}
                                        @if($totalRemarks > 1)
                                        <div class="text-primary" style="font-size: 0.75rem;">
                                            <i class="fas fa-comments me-1"></i>{{ $totalRemarks - 1 }} more comment(s)
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted small">
                                        <i class="fas fa-comment-slash me-1"></i>No comments yet
                                    </span>
                                    @endif
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <div>No worksheets found</div>
                                    <small>Try adjusting your filters</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $worksheets->links(data: ['scrollTo' => false]) }}
                </div>
            </div>

            <!-- Employee Modal -->
            @if ($showEmployeeModal && $selectedEmployee)
            <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content shadow">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user-circle me-2"></i>
                                Works of {{ $selectedEmployee->name }}
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeEmployeeModal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Chart Section -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h6 class="mb-0">Work Status Distribution</h6>
                                        </div>
                                        <div class="card-body">
                                            <div style="height:250px;">
                                                <canvas id="employeeChartModal" wire:ignore></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h6 class="mb-0">Quick Stats</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <div class="border rounded p-3">
                                                        <div class="h4 text-primary mb-1">
                                                            {{ count($employeeWorks) }}
                                                        </div>
                                                        <small class="text-muted">Total Works</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="border rounded p-3">
                                                        <div class="h4 text-success mb-1">
                                                            {{ collect($employeeWorks)->where('status', 'Completed')->count() }}
                                                        </div>
                                                        <small class="text-muted">Completed</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="border rounded p-3">
                                                        <div class="h4 text-warning mb-1">
                                                            {{ collect($employeeWorks)->where('status', 'In Progress')->count() }}
                                                        </div>
                                                        <small class="text-muted">In Progress</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div class="mb-3">
                                <button wire:click="exportEmployeeExcel" class="btn btn-success me-2">
                                    <i class="fas fa-file-excel me-1"></i>Export Excel
                                </button>
                                <button wire:click="exportEmployeePdf" class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                                </button>
                            </div>

                            <!-- Works Table -->
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th width="25%">Title</th>
                                            <th width="15%">Work</th>
                                            <th width="10%">Status</th>
                                            <th width="15%">Dates</th>
                                            <th width="35%">Latest Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employeeWorks as $work)
                                        <tr>
                                            <td>
                                                <div class="fw-medium">{{ $work['title'] }}</div>
                                            </td>
                                            <td>{{ $work['work'] }}</td>
                                            <td>
                                                <span
                                                    class="badge
                                                            @if ($work['status'] == 'Completed') bg-success
                                                            @elseif($work['status'] == 'Pending') bg-danger
                                                            @elseif($work['status'] == 'In Progress') bg-warning text-dark
                                                            @else bg-secondary @endif">
                                                    {{ $work['status'] }}
                                                </span>
                                            </td>
                                            <td class="small">
                                                @if ($work['start_date'])
                                                <div><strong>Start:</strong> {{ $work['start_date'] }}
                                                </div>
                                                @endif
                                                @if ($work['end_date'])
                                                <div><strong>End:</strong> {{ $work['end_date'] }}</div>
                                                @endif
                                                @if ($work['completed_on'])
                                                <div class="text-success"><strong>Completed:</strong>
                                                    {{ $work['completed_on'] }}
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="small">
                                                    @if ($work['remarks'] && $work['remarks'] !== 'No comments yet')
                                                    {!! Purifier::clean($work['remarks']) !!}
                                                    @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-comment-slash me-1"></i>No
                                                        comments yet
                                                    </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeEmployeeModal">
                                <i class="fas fa-times me-1"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
        <livewire:layout.footer />
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        let employeeChart = null;

        // Initialize chart when modal opens
        Livewire.on('employee-modal-opened', () => {
            setTimeout(() => {
                initEmployeeChart();
            }, 100);
        });

        // Clean up chart when modal closes
        Livewire.on('employee-modal-closed', () => {
            if (employeeChart) {
                employeeChart.destroy();
                employeeChart = null;
            }
        });

        function initEmployeeChart() {
            const ctx = document.getElementById('employeeChartModal');
            if (!ctx) return;

            if (employeeChart) {
                employeeChart.destroy();
            }

            const chartData = @this.getEmployeeChartData();

            employeeChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.data,
                        backgroundColor: [
                            '#28a745',
                            '#ffc107',
                            '#dc3545',
                            '#6c757d',
                            '#17a2b8'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' +
                                        percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush