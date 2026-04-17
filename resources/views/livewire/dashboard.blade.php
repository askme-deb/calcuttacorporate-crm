<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">

                        </div>
                        <h4 class="page-title">Dashboard</h4>
                        <!-- <button wire:click="$dispatch('lockScreen')">Lock Screen</button>
                        <button wire:click="performAction"  class="btn btn-primary">Click Me</button>
                        <button type="button" class="btn btn-de-primary btn-sm" onclick="executeExample('handleDismiss')">Click me</button> -->


                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Deals Statistics</h4>
                                    </div>
                                    <div class="col-auto">
                                        <!-- <div class="dropdown">
                                        <a href="#" class="btn btn-sm btn-outline-light dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            This Month<i class="las la-angle-down ms-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Today</a>
                                            <a class="dropdown-item" href="#">Last Week</a>
                                            <a class="dropdown-item" href="#">Last Month</a>
                                            <a class="dropdown-item" href="#">This Year</a>
                                        </div>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col col-md">
                                        <div class="media">
                                            <!-- <i data-feather="phone" class="align-self-center icon-lg text-secondary"></i> -->
                                            <div class="media-body align-self-center ms-2">
                                                <h6 class="mt-0 mb-1 font-16">{{ $dealsuccessRate }}% Deals Successfull <i
                                                        class="fas fa-check text-success"></i></h6>
                                                <!-- <p class="text-muted mb-0">This is a simple hero unit, a simple
                                                jumbotron-style component.</p> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <button type="button" class="btn btn-sm btn-de-secondary px-3 mt-2">More
                                            details</button>
                                    </div>
                                </div>
                                <div wire:ignore>
                                    <div id="crm-dash" class="apex-charts"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col text-center">
                                                <span class="h4">{{$convertedLeads}}</span>
                                                <h6 class="text-uppercase text-muted mt-2 m-0">Converted Leads</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col text-center">
                                                <span class="h4">{{$ProposalSent}}</span>
                                                <h6 class="text-uppercase text-muted mt-2 m-0">Proposal Sent</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col text-center">
                                                <span class="h4">{{$junkLeads}}</span>
                                                <h6 class="text-uppercase text-muted mt-2 m-0">Junk Leads</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col text-center">
                                                <span class="h4">{{ $totalLeads }}</span>
                                                <h6 class="text-uppercase text-muted mt-2 m-0">Total Leads</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col text-center">
                                                <span class="h4">{{$closedDeal}}</span>
                                                <h6 class="text-uppercase text-muted mt-2 m-0">Closed Deals</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col text-center">
                                                <span class="h4">{{$closedDeal}}</span>
                                                <h6 class="text-uppercase text-muted mt-2 m-0">Pending Deals</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col text-center">
                                                <span class="h4">{{$lostDeals}}</span>
                                                <h6 class="text-uppercase text-muted mt-2 m-0">Lost Deal</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col text-center">
                                                <span class="h4">{{ $totaldeals }}</span>
                                                <h6 class="text-uppercase text-muted mt-2 m-0">Total Deals</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Lead Source Breakdown</h4>
                                    </div><!--end col-->
                                </div> <!--end row-->
                            </div><!--end card-header-->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xxl-6">
                                        <div id="email_report" class="apex-charts"></div>
                                    </div><!--end col-->
                                    <div class="col-xxl-6 align-self-center">
                                        <ul class="list-unstyled">
                                            @php
                                            // Define a fixed set of colors for lead sources
                                            $colorMapping = [
                                            'Google Ads' => '#FF5733',
                                            'Facebook' => '#4267B2',
                                            'LinkedIn' => '#0077B5',
                                            'Email Campaign' => '#FFCC00',
                                            'Referral' => '#28A745',
                                            'Organic Search' => '#6C757D',
                                            'Direct' => '#17A2B8',
                                            'Others' => '#E83E8C',
                                            'Personal Reference' => '#4172eb',
                                            'Justdial' => '#ff9000',
                                            'Upwork' => '#078d25',
                                            ];

                                            // Assign colors based on lead source or a default color
                                            $colors = array_map(fn($source) => $colorMapping[$source] ?? '#000000', $leadSources);
                                            @endphp
                                            @foreach($leadSources as $index => $source)
                                            <li class="list-item mb-2" style="color: {{ $colors[$index] }}">
                                                <i class="fas fa-play me-2" style="color: {{ $colors[$index] }}"></i> {{ $source }}
                                            </li>
                                            @endforeach
                                        </ul>

                                    </div><!--end col-->
                                </div> <!--end row-->

                            </div><!--end card-body-->
                        </div><!--end  card-->
                        <livewire:to-do-list />
                        <livewire:general.employee-daily-log />

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Activity</h4>
                                    </div>
                                    <div class="col-auto">
                                        <div class="dropdown">
                                            {{-- <a href="#" class="btn btn-sm btn-outline-light dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            All<i class="las la-angle-down ms-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Purchases</a>
                                            <a class="dropdown-item" href="#">Emails</a>
                                        </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="p-3" style="height: 430px;" data-simplebar>
                                    <ul class="list-unstyled ">
                                        @foreach ($activities as $activity)
                                        <li class="d-flex align-items-start mb-4 {{ $activity->description ==='Failed login attempt' ? 'text-danger' : '' }} ">
                                            <div class="icon-container me-3">
                                                <i class="bi bi-clock-history text-primary fs-3"></i>
                                            </div>
                                            <div class="log-content w-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-1 fw-bold {{ $activity->description ==='Failed login attempt' ? 'text-danger' : '' }}">{{ $activity->description }}</h6>
                                                    <small class="text-muted">{{ $activity->created_at->format('d M Y, h:i A') }}</small>
                                                </div>
                                                <p class="text-secondary mb-1 {{ $activity->description ==='Failed login attempt' ? 'text-danger' : '' }}">
                                                    <strong>By:</strong> {{ $activity->causer?->name ?? 'Unknown' }} |
                                                    <strong>Log:</strong> {{ ucfirst($activity->log_name) }}
                                                </p>
                                                @if($activity->properties->isNotEmpty())
                                                <div class="bg-light p-2 rounded small">
                                                    <ul class="mb-0 ps-3 list-group list-group-flush">
                                                        @foreach($activity->properties as $key => $value)
                                                        <li class="list-group-item">
                                                            <i class="la la-angle-right text-info me-2"></i>
                                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            @if(is_array($value))
                                                            {{ implode(', ', $value) }}
                                                            @else
                                                            {{ $value }}
                                                            @endif
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif

                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Upcomming Follow-Up List</h4>
                                    </div>
                                    <div class="col-auto">

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Lead Name</th>
                                                <th>Follow-up Date</th>
                                                <th>Contact</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upcomingFollowups as $lead)
                                            <tr>
                                                <td>{{ $lead->name }}</td>
                                                <td>
                                                    @php
                                                    $followUpDate = \Carbon\Carbon::parse($lead->next_followup_date);
                                                    @endphp

                                                    @if ($followUpDate->isToday())
                                                    <span class="badge badge-soft-danger">Today</span>
                                                    @elseif ($followUpDate->isTomorrow())
                                                    <span class="badge badge-soft-warning">Tomorrow</span>
                                                    @else
                                                    {{ $followUpDate->format('d M, Y') }}
                                                    @endif
                                                </td>
                                                <td>{{ $lead->phone }}</td>
                                                <td>
                                                    <a wire:navigate class="btn btn-primary btn-sm" href="{{ route('lead.details', ['id' => Crypt::encryptString($lead->id)]) }}">
                                                        Follow Up
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <livewire:layout.footer />

            <script>
                document.addEventListener("livewire:init", function() {
                    var chart = null; // Store chart instance

                    function renderChart() {
                        try {
                            console.log("Lead Sources:", @json($leadSources));
                            console.log("Lead Source Counts:", @json($leadSourceCounts));

                            var leadSources = @json($leadSources);
                            var leadSourceCounts = @json($leadSourceCounts);
                            var leadColors = @json($colors); // Pass the PHP-generated colors to JavaScript

                            if (!leadSources || !leadSourceCounts) {
                                console.error("Lead sources or counts are undefined");
                                return;
                            }

                            if (leadSources.length !== leadSourceCounts.length) {
                                console.error("Mismatch in lead sources and counts lengths",
                                    "Sources:", leadSources.length,
                                    "Counts:", leadSourceCounts.length);
                                return;
                            }

                            leadSourceCounts = leadSourceCounts.map(Number);

                            var chartElement = document.querySelector("#email_report");
                            if (!chartElement) {
                                console.error("Chart element #email_report not found");
                                return;
                            }

                            // Destroy previous chart instance before re-creating
                            if (chart) {
                                chart.destroy();
                            }

                            var leadOptions = {
                                chart: {
                                    height: 205,
                                    type: "donut"
                                },
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: "85%"
                                        }
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    show: true,
                                    width: 2,
                                    colors: ["transparent"]
                                },
                                series: leadSourceCounts,
                                labels: leadSources,
                                colors: leadColors, // Use dynamic colors
                                responsive: [{
                                    breakpoint: 600,
                                    options: {
                                        plotOptions: {
                                            donut: {
                                                customScale: 0.2
                                            }
                                        },
                                        chart: {
                                            height: 200
                                        },
                                        legend: {
                                            show: false
                                        }
                                    }
                                }],
                                tooltip: {
                                    y: {
                                        formatter: function(e) {
                                            return e + " %";
                                        }
                                    }
                                }
                            };

                            chart = new ApexCharts(chartElement, leadOptions);
                            chart.render();

                            console.log("Chart rendered successfully with dynamic colors:", leadColors);
                        } catch (error) {
                            console.error("Error rendering chart:", error);
                        }
                    }

                    // Initial chart render
                    renderChart();

                    // Fix for wire:navigate issue: Reinitialize chart after navigation
                    document.addEventListener("livewire:navigated", function() {
                        setTimeout(renderChart, 500); // Delay ensures Livewire updates DOM first
                    });

                    // Also handle dynamic updates when any Livewire component updates
                    document.addEventListener("livewire:updated", function() {
                        setTimeout(renderChart, 500);
                    });
                });



                document.addEventListener("livewire:init", function() {
                    function renderChart(dealsData) {
                        // Extract months and deal counts
                        var totalDeals = Array(12).fill(0);
                        var closedDeals = Array(12).fill(0);

                        Object.keys(dealsData).forEach(month => {
                            var index = parseInt(month) - 1; // Convert month (1-12) to array index (0-11)
                            totalDeals[index] = dealsData[month].total;
                            closedDeals[index] = dealsData[month].closed;
                        });

                        var options = {
                            chart: {
                                height: 320,
                                type: "area",
                                width: "100%",
                                stacked: true,
                                toolbar: {
                                    show: false,
                                    autoSelected: "zoom"
                                }
                            },
                            colors: ["#2a77f4", "#a5c2f1"],
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: "smooth",
                                width: [1.5, 1.5],
                                dashArray: [0, 4],
                                lineCap: "round"
                            },
                            grid: {
                                padding: {
                                    left: 0,
                                    right: 0
                                },
                                strokeDashArray: 3
                            },
                            markers: {
                                size: 0,
                                hover: {
                                    size: 0
                                }
                            },
                            series: [{
                                    name: "New Deals",
                                    data: totalDeals
                                },
                                {
                                    name: "Closed Deals",
                                    data: closedDeals
                                }
                            ],
                            xaxis: {
                                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                                axisBorder: {
                                    show: true
                                },
                                axisTicks: {
                                    show: true
                                }
                            },
                            fill: {
                                type: "gradient",
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.4,
                                    opacityTo: 0.3,
                                    stops: [0, 90, 100]
                                }
                            },
                            tooltip: {
                                x: {
                                    format: "dd/MM/yy HH:mm"
                                }
                            },
                            legend: {
                                position: "top",
                                horizontalAlign: "right"
                            }
                        };

                        var chartContainer = document.querySelector("#crm-dash");
                        if (chartContainer) {
                            chartContainer.innerHTML = ""; // Clear previous chart before re-rendering
                            var chart = new ApexCharts(chartContainer, options);
                            chart.render();

                            // Update chart dynamically when Livewire sends updated data
                            Livewire.on("dealsUpdated", function(updatedData) {
                                var updatedTotalDeals = Array(12).fill(0);
                                var updatedClosedDeals = Array(12).fill(0);

                                Object.keys(updatedData).forEach(month => {
                                    var index = parseInt(month) - 1;
                                    updatedTotalDeals[index] = updatedData[month].total;
                                    updatedClosedDeals[index] = updatedData[month].closed;
                                });

                                chart.updateSeries([{
                                        name: "New Deals",
                                        data: updatedTotalDeals
                                    },
                                    {
                                        name: "Closed Deals",
                                        data: updatedClosedDeals
                                    }
                                ]);
                            });
                        }
                    }

                    // Initial render
                    var dealsData = @json($dealsData);
                    renderChart(dealsData);

                    // Reinitialize chart after Livewire navigation
                    document.addEventListener("livewire:navigated", function() {
                        var newDealsData = @json($dealsData); // Fetch latest data again
                        renderChart(newDealsData);
                    });
                });
            </script>

        </div>
    </div>
