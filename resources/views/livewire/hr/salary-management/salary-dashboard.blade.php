<div>
    <div class="page-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid py-4" wire:poll.30s="loadDashboard">

                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Salary Management Dashboard</h2>
                        <p class="text-muted mb-0">Professional Payroll Analytics & Insights</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                    </div>
                </div>

                @php
                    $total = $totalPaid + $totalUnpaid;
                    $percentage = $total > 0 ? round(($totalPaid / $total) * 100, 1) : 0;
                @endphp

                <!-- KPI Cards -->
                <div class="row g-4 mb-5">
                    <!-- Total Paid -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-3">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-success bg-opacity-10 me-3 p-3 rounded-circle shadow-sm">
                                        <i class="fas fa-check-circle text-success fs-4"></i>
                                    </div>
                                    <span class="text-success fw-semibold small text-uppercase">Total Paid</span>
                                </div>
                                <h3 class="fw-bold text-dark mb-1">₹{{ number_format($totalPaid, 2) }}</h3>
                                <p class="text-muted small mb-2">Completed payments</p>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Unpaid -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-3">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-danger bg-opacity-10 me-3 p-3 rounded-circle shadow-sm">
                                        <i class="fas fa-clock text-danger fs-4"></i>
                                    </div>
                                    <span class="text-danger fw-semibold small text-uppercase">Total Unpaid</span>
                                </div>
                                <h3 class="fw-bold text-dark mb-1">₹{{ number_format($totalUnpaid, 2) }}</h3>
                                <p class="text-muted small mb-2">Pending payments</p>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-danger" style="width: {{ 100 - $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-3">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-primary bg-opacity-10 me-3 p-3 rounded-circle shadow-sm">
                                        <i class="fas fa-calculator text-primary fs-4"></i>
                                    </div>
                                    <span class="text-primary fw-semibold small text-uppercase">Total Amount</span>
                                </div>
                                <h3 class="fw-bold text-dark mb-1">₹{{ number_format($total, 2) }}</h3>
                                <p class="text-muted small mb-2">Overall payroll</p>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Rate -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-3">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-info bg-opacity-10 me-3 p-3 rounded-circle shadow-sm">
                                        <i class="fas fa-percentage text-info fs-4"></i>
                                    </div>
                                    <span class="text-info fw-semibold small text-uppercase">Payment Rate</span>
                                </div>
                                <h3 class="fw-bold text-dark mb-1">{{ $percentage }}%</h3>
                                <p class="text-muted small mb-2">Completion rate</p>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-info" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row g-4 mb-4">
                    <!-- Salary Trend -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow-sm border-0 rounded-3" wire:ignore>
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 text-dark">Salary Trend Analysis</h5>
                                <small class="text-muted">6-Month Payment Overview</small>
                            </div>
                            <div class="card-body p-3">
                                <div id="trendChart-{{ uniqid() }}" class="trend-chart" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Distribution -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow-sm border-0 rounded-3" wire:ignore>
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 text-dark">Payment Distribution</h5>
                                <small class="text-muted">Paid vs Unpaid</small>
                            </div>
                            <div class="card-body p-3 text-center">
                                <div id="pieChart-{{ uniqid() }}" class="pie-chart" style="height: 300px;"></div>
                                <div class="mt-3 d-flex justify-content-around">
                                    <div>
                                        <h6 class="fw-bold text-success mb-1">{{ $percentage }}%</h6>
                                        <small class="text-muted">Paid</small>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-danger mb-1">{{ 100 - $percentage }}%</h6>
                                        <small class="text-muted">Unpaid</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <livewire:layout.footer />
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Global chart management
    window.salaryCharts = window.salaryCharts || {
        instances: {},
        data: {
            monthlyTrends: @json($monthlyTrends),
            paidVsUnpaid: @json($paidVsUnpaid)
        }
    };

    function destroyAllCharts() {
        Object.keys(window.salaryCharts.instances).forEach(key => {
            if (window.salaryCharts.instances[key]) {
                try {
                    window.salaryCharts.instances[key].destroy();
                } catch (e) {
                    console.warn('Error destroying chart:', e);
                }
                delete window.salaryCharts.instances[key];
            }
        });
    }

    function initSalaryCharts() {
        // Destroy existing charts
        destroyAllCharts();

        // Find chart containers
        const trendContainer = document.querySelector(".trend-chart");
        const pieContainer = document.querySelector(".pie-chart");

        if (!trendContainer || !pieContainer) {
            console.warn('Chart containers not found, retrying...');
            setTimeout(initSalaryCharts, 200);
            return;
        }

        // Clear containers
        trendContainer.innerHTML = '';
        pieContainer.innerHTML = '';

        const { monthlyTrends, paidVsUnpaid } = window.salaryCharts.data;

        // Prepare data
        const months = monthlyTrends.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month - 1).toLocaleDateString('en-US', { month: 'short', year: '2-digit' });
        });
        const paidData = monthlyTrends.map(item => item.paid);
        const unpaidData = monthlyTrends.map(item => item.unpaid);

        // Create Trend Chart
// Create Trend Chart
try {
    const trendChart = new ApexCharts(trendContainer, {
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false },
            animations: { easing: 'easeinout', speed: 800 },
            redrawOnParentResize: true,
            redrawOnWindowResize: true
        },
        stroke: { curve: 'smooth', width: 3 },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "vertical",
                shadeIntensity: 0.3,
                opacityFrom: 0.7,
                opacityTo: 0.1
            }
        },
        series: [
            { name: 'Paid', data: paidData },
            { name: 'Unpaid', data: unpaidData }
        ],
        colors: ['#22c55e', '#f43f5e'],
        xaxis: {
            categories: months,
            labels: {
                rotate: -30,
                style: { colors: '#374151', fontWeight: 600 } // Dark gray font
            }
        },
        yaxis: {
            labels: {
                formatter: val => '₹' + val.toLocaleString(),
                style: { colors: '#374151', fontWeight: 600 }
            }
        },
        tooltip: {
            theme: 'light',
            style: { fontSize: '14px', color: '#fff' },
            y: { formatter: val => '₹' + val.toLocaleString() }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            labels: { colors: '#ffffff' } // Legend font color
        },
        grid: { borderColor: '#e5e7eb', strokeDashArray: 4 }
    });

    trendChart.render();
    window.salaryCharts.instances.trendChart = trendChart;
} catch (e) {
    console.error('Error creating trend chart:', e);
}

// Create Pie Chart
try {
    const pieChart = new ApexCharts(pieContainer, {
        chart: {
            type: 'donut',
            height: 300,
            redrawOnParentResize: true,
            redrawOnWindowResize: true
        },
        series: [paidVsUnpaid.paid, paidVsUnpaid.unpaid],
        labels: ['Paid', 'Unpaid'],
        colors: ['#22c55e', '#f43f5e'],
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        value: { color: '#374151', fontWeight: 600 },
                        total: { color: '#374151', fontWeight: 600 }
                    }
                }
            }
        },
        dataLabels: {
            formatter: val => val.toFixed(1) + '%',
            style: { colors: ['#374151'], fontWeight: 600 }
        },
        stroke: { width: 0 },
        tooltip: {
            theme: 'light',
            style: { fontSize: '14px', color: '#fff' },
            y: { formatter: val => '₹' + val.toLocaleString() }
        },
        legend: {
            show: true,
            labels: { colors: ['#374151', '#374151'] } // Legend font colors
        }
    });

    pieChart.render();
    window.salaryCharts.instances.pieChart = pieChart;
} catch (e) {
    console.error('Error creating pie chart:', e);
}

    }

    // Force initialization on various events
    function forceChartInit() {
        setTimeout(initSalaryCharts, 100);
    }

    // Observer to watch for DOM changes
    function setupChartObserver() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    const hasChartContainer = document.querySelector('.trend-chart') || document.querySelector('.pie-chart');
                    if (hasChartContainer && Object.keys(window.salaryCharts.instances).length === 0) {
                        console.log('Chart containers detected, initializing...');
                        forceChartInit();
                    }
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        return observer;
    }

    // Initialize charts when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', forceChartInit);
    } else {
        forceChartInit();
    }

    // Setup observer
    setupChartObserver();

    // Multiple event listeners for different scenarios
    document.addEventListener('livewire:navigated', forceChartInit);
    document.addEventListener('livewire:load', forceChartInit);
    document.addEventListener('livewire:update', forceChartInit);

    // For Livewire v3
    document.addEventListener('livewire:init', forceChartInit);
    document.addEventListener('livewire:commit', forceChartInit);

    // Browser navigation events
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) forceChartInit();
    });

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) forceChartInit();
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', destroyAllCharts);

    // Additional fallback - check every 2 seconds if charts are missing
    setInterval(() => {
        const hasContainers = document.querySelector('.trend-chart') && document.querySelector('.pie-chart');
        const hasCharts = Object.keys(window.salaryCharts.instances).length > 0;

        if (hasContainers && !hasCharts) {
            console.log('Charts missing, reinitializing...');
            forceChartInit();
        }
    }, 2000);

    // Global function to manually trigger chart initialization
    window.initSalaryCharts = initSalaryCharts;
</script>
