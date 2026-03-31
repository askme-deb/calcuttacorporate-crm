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

                        <!-- Search Title -->
                        <div class="col-md-3">
                            <label class="form-label small">Title</label>
                            <input type="text" wire:model.live="search" class="form-control"
                                placeholder="Search Title...">
                        </div>

                        <!-- Employee Filter -->
                        <div class="col-md-3">
                            <label class="form-label small">Employee</label>
                            <select wire:model.live="employee_id" class="form-select">
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label small">Status</label>
                            <select wire:model.live="status_id" class="form-select">
                                <option value="">-- Select Status --</option>
                                @foreach ($statuses as $st)
                                    <option value="{{ $st->id }}">{{ $st->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Start Date Filter -->
                        <div class="col-md-2">
                            <label class="form-label small">Start Date From</label>
                            <input type="date" wire:model.live="date_from" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Start Date To</label>
                            <input type="date" wire:model.live="date_to" class="form-control">
                        </div>

                        <!-- Work Filter -->
                        <div class="col-md-2">
                            <label class="form-label small">Work</label>
                            <select wire:model.live="work_id" class="form-select">
                                <option value="">-- Select Work --</option>
                                @foreach ($works as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Client Filter -->
                        <div class="col-md-2">
                            <label class="form-label small">Client</label>
                            <select wire:model.live="client_id" class="form-select">
                                <option value="">-- Select Client --</option>
                                @foreach ($clients as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Overdue / Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label small">Overdue / Status</label>
                            <select wire:model.live="overdue_filter" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="overdue">Overdue</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <!-- Deadline Filter -->
                        <div class="col-md-2">
                            <label class="form-label small">Deadline From</label>
                            <input type="date" wire:model.live="deadline_from" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Deadline To</label>
                            <input type="date" wire:model.live="deadline_to" class="form-control">
                        </div>

                        <!-- Refresh Button -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button wire:click="resetFilters" class="btn btn-secondary w-100"
                                wire:loading.attr="disabled">
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
                        <div class="col-md-10">
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
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="w-100">
    {{-- Export Excel --}}
    <button 
        wire:click="exportExcel" 
        wire:loading.attr="disabled" 
        wire:target="exportExcel" 
        class="btn btn-success me-2 w-100 mb-2 position-relative"
    >
        <span wire:loading.remove wire:target="exportExcel">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </span>
        <span wire:loading.inline wire:target="exportExcel">
            <i class="fas fa-spinner fa-spin me-1"></i> Exporting...
        </span>
    </button>

    {{-- Export PDF --}}
    <button 
        wire:click="exportPdf" 
        wire:loading.attr="disabled" 
        wire:target="exportPdf" 
        class="btn btn-warning w-100 position-relative"
    >
        <span wire:loading.remove wire:target="exportPdf">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </span>
        <span wire:loading.inline wire:target="exportPdf">
            <i class="fas fa-spinner fa-spin me-1"></i> Exporting...
        </span>
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
                                <th width="12%">Manager</th>
                                <th width="20%">Assigned To</th>
                                <th width="10%">Start</th>
                                <th width="10%">Deadline</th>
                                <th width="10%">Status</th>
                                <th width="23%">Latest Remarks</th>
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

                                    <!-- Project Manager Column -->
                                    <td>
                                        @php
                                            // Get all Project Manager team members and sort by assigned_on
                                            $allPMTeamMembers = $ws->teamMembers
                                                ->filter(function ($tm) {
                                                    return $tm->user && $tm->user->hasRole('Project Manager');
                                                })
                                                ->sortBy(function ($tm) {
                                                    return $tm->pivot->assigned_on ?? now();
                                                });

                                            // Get the first PM (earliest assigned)
                                            $primaryPM = $allPMTeamMembers->first();

                                            // Also check assignedBy if they are PM
                                            $pmAssignedBy = optional($ws->teamMembers->first())->assignedBy;
                                            $showAssignedByPM = false;

                                            if ($pmAssignedBy && $pmAssignedBy->hasRole('Project Manager')) {
                                                // If no team member PM or assignedBy is earlier than first team member PM
                                                if (
                                                    !$primaryPM ||
                                                    ($primaryPM && $pmAssignedBy->id != $primaryPM->user->id)
                                                ) {
                                                    $showAssignedByPM = true;
                                                }
                                            }
                                        @endphp

                                        @if ($showAssignedByPM)
                                            <div class="d-flex flex-column mb-1">
                                                <button wire:click="showEmployeeDetails({{ $pmAssignedBy->id }})"
                                                    class="btn btn-link p-0 text-decoration-none small text-start">
                                                    <i class="fas fa-user me-1"></i>{{ $pmAssignedBy->name }}
                                                    <span class="badge bg-primary ms-1">PM</span>
                                                </button>
                                                <small class="text-muted">Primary Manager</small>
                                            </div>
                                        @elseif($primaryPM)
                                            <div class="d-flex flex-column mb-1">
                                                <button wire:click="showEmployeeDetails({{ $primaryPM->user->id }})"
                                                    class="btn btn-link p-0 text-decoration-none small text-start">
                                                    <i class="fas fa-user me-1"></i>{{ $primaryPM->user->name }}
                                                    <span class="badge bg-primary ms-1">PM</span>
                                                </button>
                                                <small class="text-muted">
                                                    Assigned on:
                                                    {{ \Carbon\Carbon::parse($primaryPM->pivot->assigned_on ?? now())->format('M d, Y') }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    <!-- Assigned To Column (including additional PMs and non-PMs) -->
                                    <td>
                                        @php
                                            // Get all non-PM team members
                                            $nonPMAssignees = $ws->teamMembers->filter(function ($tm) {
                                                return $tm->user && !$tm->user->hasRole('Project Manager');
                                            });

                                            // Get additional PM team members (excluding the primary one shown in Manager column)
                                            $additionalPMs = collect();
                                            if ($allPMTeamMembers->count() > 1) {
                                                $additionalPMs = $allPMTeamMembers->skip(1); // Skip the first PM
                                            }

                                            // If assignedBy PM is shown in Manager column, don't show any team member PMs here
                                            // If assignedBy PM is not shown, show all team member PMs except the first one
                                            if ($showAssignedByPM) {
                                                $additionalPMs = $allPMTeamMembers; // Show all team member PMs
                                            }

                                            // Combine additional PMs with non-PM assignees
                                            $allAssignees = $nonPMAssignees
                                                ->concat($additionalPMs)
                                                ->sortBy(function ($tm) {
                                                    return $tm->pivot->assigned_on ?? now();
                                                });
                                        @endphp

                                        @forelse($allAssignees as $teamMember)
                                            <div class="d-flex flex-column mb-1">
                                                <button wire:click="showEmployeeDetails({{ $teamMember->user->id }})"
                                                    class="btn btn-link p-0 text-decoration-none small text-start">
                                                    <i class="fas fa-user me-1"></i>{{ $teamMember->user->name }}
                                                    @if ($teamMember->user->hasRole('Project Manager'))
                                                        <span class="badge bg-secondary ms-1">Additional PM</span>
                                                    @endif
                                                </button>
                                                <small class="text-muted">
                                                    Assigned by: {{ optional($teamMember->assignedBy)->name ?? '-' }}
                                                    on
                                                    {{ \Carbon\Carbon::parse($teamMember->assigned_on ?? now())->format('M d, Y') }}
                                                </small>
                                            </div>
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

                                    <!-- Status -->
                                    <td>
                                        @if ($ws->status)
                                            <span
                                                class="badge
                                                    @if ($ws->status->name == 'Completed') bg-success
                                                    @elseif($ws->status->name == 'Pending') bg-danger
                                                    @elseif($ws->status->name == 'In Progress') bg-warning text-dark
                                                    @else bg-secondary @endif">
                                                {{ $ws->status->name }}
                                            </span>

                                            {{-- Completed/Overdue Info --}}
                                            @if ($ws->status->name == 'Completed' && $ws->completed_on)
                                                <div class="small text-muted mt-1">
                                                    <i class="fas fa-calendar-check me-1"></i>
                                                    on:
                                                    {{ \Carbon\Carbon::parse($ws->completed_on)->format('M d, Y') }}
                                                </div>
                                            @elseif(!in_array($ws->status->name, ['Completed']) && $ws->deadline)
                                                @php
                                                    $today = \Carbon\Carbon::today();
                                                    $deadline = \Carbon\Carbon::parse($ws->deadline);
                                                    $remaining = $today->diffInDays($deadline, false);
                                                @endphp
                                                <div
                                                    class="small mt-1
            @if ($remaining < 0) text-danger
            @elseif($remaining > 0) text-info
            @else text-warning @endif">
                                                    <i class="fas fa-clock me-1"></i>
                                                    @if ($remaining < 0)
                                                        Overdue by {{ abs($remaining) }} day(s)
                                                    @elseif($remaining > 0)
                                                        {{ $remaining }} day(s) left
                                                    @else
                                                        Deadline today
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>

                                    <!-- Latest Remarks -->
                                    <td>
                                        @php
                                            $latestRemark = $ws->latestRemark;
                                            $totalRemarks = $ws->remarks?->count() ?? 0;
                                        @endphp

                                        @if ($latestRemark)
                                            <div class="d-flex flex-column gap-1">
                                                <div class="text-dark" style="font-size: 0.9rem;">
                                                    {!! $latestRemark->remarks !!}
                                                </div>
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    Remark by: <i
                                                        class="fas fa-user me-1"></i>{{ $latestRemark->user->name ?? 'Unknown' }}
                                                    &nbsp;&nbsp;
                                                    <i
                                                        class="fas fa-clock me-1"></i>{{ $latestRemark->created_at?->format('M d, Y H:i A') ?? '' }}
                                                </div>
                                                @if ($totalRemarks > 1)
                                                    <div class="text-primary" style="font-size: 0.75rem;">
                                                        <i class="fas fa-comments me-1"></i>{{ $totalRemarks - 1 }}
                                                        more comment(s)
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
                                    <td colspan="8" class="text-center text-muted py-4">
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
                                            <div class="card-body  align-items-center justify-content-center">
                                                @if (count($employeeWorks) > 0)
                                                    <!-- Fixed container for Chart.js -->
                                                    <div class="chart-container"
                                                        style="position: relative;height: 250px;width: 516px;">
                                                        <canvas id="employeeChartModal"></canvas>
                                                    </div>
                                                @else
                                                    <div class="text-center text-muted">
                                                        <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                                        <p>No work data available</p>
                                                    </div>
                                                @endif
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
                                    {{-- <button wire:click="exportEmployeePdf" class="btn btn-danger">
                                        <i class="fas fa-file-pdf me-1"></i>Export PDF
                                    </button> --}}
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
                                                            <div><strong>Start:</strong>
                                                                {{ \Carbon\Carbon::parse($work['start_date'])->format('M d, Y') }}
                                                            </div>
                                                        @endif
                                                        @if ($work['deadline'])
                                                            <div><strong>End:</strong>
                                                                {{ \Carbon\Carbon::parse($work['deadline'])->format('M d, Y') }}
                                                            </div>
                                                        @endif
                                                        @if ($work['completed_on'])
                                                            <div class="text-success"><strong>Completed:</strong>
                                                                {{ \Carbon\Carbon::parse($work['completed_on'])->format('M d, Y') }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="small">
                                                            @if ($work['remarks'] && $work['remarks'] !== 'No comments yet')
                                                                {!! $work['remarks'] !!}
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

<script>
    let employeeChart = null;
    let chartInitialized = false;

    // Debug function
    function debugLog(message, data = null) {
        console.log(`[CHART DEBUG] ${message}`, data || '');
    }

    // Multiple initialization strategies
    function initializeChart() {
        debugLog('Attempting to initialize chart...');

        const canvas = document.getElementById('employeeChartModal');
        if (!canvas) {
            debugLog('ERROR: Canvas element not found');
            return false;
        }
        // Set fixed canvas size
        canvas.style.position = 'relative';
        canvas.style.height = '250px';
        canvas.style.width = '516px';
        debugLog('Canvas found, checking for existing chart...');

        // Destroy existing chart
        if (employeeChart) {
            debugLog('Destroying existing chart');
            employeeChart.destroy();
            employeeChart = null;
        }

        // Get chart data directly from Livewire component
        if (typeof @this !== 'undefined') {
            debugLog('Livewire component found, getting chart data...');

            @this.call('getEmployeeChartData').then(chartData => {
                debugLog('Chart data received:', chartData);

                if (!chartData || !chartData.labels || !chartData.data) {
                    debugLog('ERROR: Invalid chart data structure');
                    showNoDataMessage();
                    return;
                }

                if (chartData.labels.length === 0 || chartData.data.length === 0) {
                    debugLog('WARNING: Empty chart data arrays');
                    showNoDataMessage();
                    return;
                }

                createChart(canvas, chartData);

            }).catch(error => {
                debugLog('ERROR: Failed to get chart data', error);
            });
        } else {
            debugLog('ERROR: Livewire component not available');
        }
    }

    function createChart(canvas, chartData) {
        debugLog('Creating chart with data:', chartData);

        try {
         employeeChart = new Chart(canvas, {
    type: 'doughnut',
    data: {
        labels: chartData.labels,
        datasets: [{
            data: chartData.data,
            backgroundColor: [
                '#28a745', '#ffc107', '#dc3545', '#6c757d',
                '#17a2b8', '#fd7e14', '#e83e8c', '#20c997'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,          // Chart adapts to container width
        maintainAspectRatio: false, // Allows custom height
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 15,
                    font: { size: 11 }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percent = total ? ((context.parsed / total) * 100).toFixed(1) : 0;
                        return `${context.label}: ${context.parsed} (${percent}%)`;
                    }
                }
            }
        }
    }
});


            chartInitialized = true;
            debugLog('Chart created successfully!');

        } catch (error) {
            debugLog('ERROR: Chart creation failed', error);
        }
    }

    function showNoDataMessage() {
        const canvas = document.getElementById('employeeChartModal');
        if (canvas) {
            const parent = canvas.parentElement;
            parent.innerHTML = `
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-chart-pie fa-2x mb-2"></i>
                                <p class="mb-0">No data available for chart</p>
                            </div>
                        `;
        }
    }

    // Multiple event listeners for different scenarios
    document.addEventListener('DOMContentLoaded', function() {
        debugLog('DOM Content Loaded');
    });

    // Livewire specific events
    document.addEventListener('livewire:init', () => {
        debugLog('Livewire initialized');

        Livewire.on('employee-modal-opened', () => {
            debugLog('Employee modal opened event received');
            chartInitialized = false;

            // Multiple attempts with different delays
            setTimeout(() => initializeChart(), 100);
            setTimeout(() => {
                if (!chartInitialized) {
                    debugLog('Retry attempt 1...');
                    initializeChart();
                }
            }, 300);
            setTimeout(() => {
                if (!chartInitialized) {
                    debugLog('Retry attempt 2...');
                    initializeChart();
                }
            }, 500);
        });

        Livewire.on('employee-modal-closed', () => {
            debugLog('Employee modal closed event received');
            if (employeeChart) {
                employeeChart.destroy();
                employeeChart = null;
            }
            chartInitialized = false;
        });
    });

    // Backup initialization on window load
    window.addEventListener('load', function() {
        debugLog('Window loaded');

        // Check if modal is already open and chart not initialized
        const modal = document.querySelector('.modal.show');
        const canvas = document.getElementById('employeeChartModal');

        if (modal && canvas && !chartInitialized) {
            debugLog('Found open modal with canvas, initializing chart...');
            setTimeout(() => initializeChart(), 100);
        }
    });

    // Manual trigger function (you can call this from browser console for testing)
    window.triggerChartInit = function() {
        debugLog('Manual chart initialization triggered');
        initializeChart();
    };
</script>
