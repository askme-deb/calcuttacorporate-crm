<div class="page-wrapper">

    <!-- Page Content-->
    <div class="page-content-tab">

        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">{{ $month }}</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Attendance  Sheet for the Month of {{ $month }}</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">


                <div class="col-lg-6 text-start">
                    <div class="">

                        <ul class="list-inline">


                            <li class="list-inline-item" x-data="{ open: false }" x-init="$wire.on('closeDropdown', () => { open = false })">
                                <!-- Button that toggles the dropdown -->
                                <button type="button" class="btn btn-primary btn-sm" @click="open = !open">
                                    <i class="fas fa-filter me-1"></i> Filter by Month
                                </button>

                                <!-- Dropdown menu (visible when 'open' is true) -->
                                <div x-show="open" @click.away="open = false" class="dropdown-menu show"
                                    style="position: absolute;">
                                    @php
                                    $currentMonth = now()->format('m');
                                @endphp

                                @foreach ($months as $k => $v)
                                    <a href="javascript:;"
                                       class="dropdown-item {{ $k > $currentMonth ? 'disabled text-muted' : '' }}"
                                       @if ($k <= $currentMonth)
                                           wire:click="setView('{{ $k }}')"
                                       @endif
                                    >
                                        {{ $v }}
                                    </a>
                                @endforeach

                                </div>
                            </li>


                        </ul>
                    </div>
                </div>
                <!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 table-centered">
                                    <thead>
                                        <tr>
                                            <th colspan="2">{!! $month . ' / ' . $year !!}
                                            </th>
                                            @foreach (range(1, $totalDays) as $day)
                                                <th>{{ $day }}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <th>ID</th>
                                            @foreach (range(1, $totalDays) as $day)
                                                <th
                                                    class="{{ $daysOfWeek[($firstDayOfWeek + $day - 1) % 7] == 'Sun' ? 'weekoff' : '' }}">
                                                    {{ $daysOfWeek[($firstDayOfWeek + $day - 1) % 7] }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($empployee as $emp)
                                            <tr>
                                                <td>{{ $emp->emp_first_name }}</td>
                                                <td>{{ $emp->emp_code }}</td>
                                                @php $attendance = generateEmployeeAttendance($totalDays, $emp->user_id, $monthNumber, $year) @endphp
                                                @foreach ($attendance as $status)
                                                    <td>{!! $status !!}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table><!--end /table-->
                            </div><!--end /tableresponsive-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div>



        </div><!-- container -->

        <!--Start Footer-->
        <livewire:layout.footer />
        <!--end footer-->

    </div>
    <!-- end page content -->
</div>
