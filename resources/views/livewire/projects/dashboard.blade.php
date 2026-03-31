<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Worksheet Dashboard</h1>
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

            <!-- KPI Cards -->
           <!-- KPI Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-primary">{{ $dashboard['total'] }}</h3>
                            <p class="mb-0 text-muted">Total Works</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-success">{{ $dashboard['completed'] }}</h3>
                            <p class="mb-0 text-muted">Completed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-warning">{{ $dashboard['inprogress'] }}</h3>
                            <p class="mb-0 text-muted">In Progress</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-danger">{{ $dashboard['pending'] }}</h3>
                            <p class="mb-0 text-muted">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-secondary">{{ $dashboard['nostarted'] }}</h3>
                            <p class="mb-0 text-muted">Not Started</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-info">{{ $dashboard['reqquirementsgathering'] }}</h3>
                            <p class="mb-0 text-muted">Requirements Gathering</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-dark">{{ $dashboard['onhold'] }}</h3>
                            <p class="mb-0 text-muted">On Hold</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-warning">{{ $dashboard['pendingapproval'] }}</h3>
                            <p class="mb-0 text-muted">Pending Approval</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-danger">{{ $dashboard['cancelled'] }}</h3>
                            <p class="mb-0 text-muted">Cancelled</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-warning">{{ $dashboard['delayed'] }}</h3>
                            <p class="mb-0 text-muted">Delayed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-primary">{{ $dashboard['planning'] }}</h3>
                            <p class="mb-0 text-muted">Planning</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="text-muted">{{ $dashboard['archived'] }}</h3>
                            <p class="mb-0 text-muted">Archived</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Charts Row 1 -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header small fw-bold">Status Distribution</div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:500px; width:100%">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-header small fw-bold">Works Per Month</div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:500px; width:100%">
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="card shadow-sm h-100">
                <div class="card-header small fw-bold">Top 10 Employee Workload</div>
                <div class="card-body" style="position: relative; height:500px; width:100%">
                    <div class="chart-container"
                        style="position: relative; width:100%; height: {{ count($dashboard['employee_names']) * 40 }}px;">
                        <canvas id="employeeChart"></canvas>
                    </div>
                </div>
            </div>




        </div>
        <livewire:layout.footer />
    </div>
</div>

<!-- Charts JavaScript -->
<script>
    let statusChart = null;
    let employeeChart = null;
    let monthlyChart = null;

    function initializeCharts() {
        // Status Distribution
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            if (statusChart instanceof Chart) statusChart.destroy();
            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($dashboard['status_labels']),
                    datasets: [{
                        data: @json($dashboard['status_counts']),
                        backgroundColor: [
                            '#28a745', '#ffc107', '#dc3545', '#17a2b8',
                            '#6c757d', '#007bff', '#6610f2', '#fd7e14'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

   // Monthly Works Chart
const monthlyCtx = document.getElementById('monthlyChart');
if (monthlyCtx) {
    if (monthlyChart instanceof Chart) monthlyChart.destroy();

    monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: @json($dashboard['months']),
            datasets: [
                {
                    label: 'Completed',
                    data: @json($dashboard['works_completed']),
                    backgroundColor: 'rgba(40, 167, 69, 0.7)'
                },
                {
                    label: 'In Progress',
                    data: @json($dashboard['works_inprogress']),
                    backgroundColor: 'rgba(255, 193, 7, 0.7)' // yellow
                },
                {
                    label: 'Not Started',
                    data: @json($dashboard['works_pending']),
                    backgroundColor: 'rgba(220, 53, 69, 0.7)'
                },
                {
                    label: 'Total',
                    data: @json($dashboard['works_total']),
                    type: 'line',
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: false
                },
                x: {
                    stacked: false
                }
            },
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
}

        // Employee Workload
        const employeeCtx = document.getElementById('employeeChart');
        if (employeeCtx) {
            if (employeeChart instanceof Chart) employeeChart.destroy();
            employeeChart = new Chart(employeeCtx, {
                type: 'bar',
                data: {
                    labels: @json($dashboard['employee_names']),
                    datasets: [{
                        label: 'Works',
                        data: @json($dashboard['employee_works']),
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        barThickness: 15, // slimmer bars
                        maxBarThickness: 20 // prevents bars from growing too large
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false, // important to fill the card
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            ticks: {
                                autoSkip: false,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

    }

    document.addEventListener('DOMContentLoaded', initializeCharts);
    document.addEventListener('livewire:navigated', () => {
        setTimeout(initializeCharts, 100);
    });
</script>
