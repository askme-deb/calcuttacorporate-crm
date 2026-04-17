<div class="page-wrapper">
    <style>
        /* KPI Cards */
        .kpi-card {
            text-align: center;
            padding: 20px;
            border-radius: 14px;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .kpi-card:hover {
            transform: translateY(-5px);
        }

        .kpi-primary {
            background: linear-gradient(135deg, #0d6efd, #3d8bfd);
        }

        .kpi-success {
            background: linear-gradient(135deg, #198754, #4cc99a);
        }

        .kpi-warning {
            background: linear-gradient(135deg, #ffc107, #ffda6a);
            color: #333;
        }

        .kpi-danger {
            background: linear-gradient(135deg, #dc3545, #f86c75);
        }

        .kpi-info {
            background: linear-gradient(135deg, #0dcaf0, #66d9e8);
            color: #333;
        }

        .kpi-secondary {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
        }

        /* Dashboard Cards */
        .dashboard-card {
            border-radius: 14px;
            padding: 18px;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }



        /* Chart Height */
        canvas {
            max-height: 230px;
        }



        .calendar-box {
            width: 45px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .calendar-box .month {
            font-weight: bold;
            padding: 2px 0;
            font-size: 11px;
            color: #fff;
            text-transform: uppercase;
        }

        .calendar-box .day {
            font-size: 14px;
            font-weight: bold;
            padding: 4px 0;
            background: #fff;
            color: #333;
            line-height: 1;
        }

        .holiday-name {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        /* Month colors */
        .calendar-box.sep .month {
            background: #ff6b6b;
        }

        .calendar-box.oct .month {
            background: #4dabf7;
        }

        .calendar-box.dec .month {
            background: #51cf66;
        }

        .dot-today,
        .dot-tomorrow {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.5s infinite;
            cursor: pointer;
        }

        .dot-today {
            background-color: #28a745;
        }

        .dot-tomorrow {
            background-color: #17a2b8;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.4);
                opacity: 0.6;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 0.85rem;
        }

        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
            transition: 0.2s ease-in-out;
        }

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');


        .modal-content {
            border: none !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
            border-radius: 25px !important;
            overflow: visible !important;
            position: relative;
            background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
        }

        .modal-header {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24, #ff9ff3, #54a0ff) !important;
            background-size: 400% 400% !important;
            animation: gradientShift 3s ease infinite;
            border: none !important;
            border-radius: 25px 25px 0 0 !important;
            padding: 2rem 2rem 1.5rem 2rem !important;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: shine 4s linear infinite;
        }

        @keyframes shine {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }

        /* Enhanced Balloons */
        .balloon {
            position: absolute;
            font-size: 2.5rem;
            z-index: 10;
            filter: drop-shadow(3px 3px 6px rgba(0, 0, 0, 0.3));
        }

        .balloon1 {
            top: -30px;
            right: 30px;
            animation: float1 4s ease-in-out infinite;
            color: #ff6b6b;
        }

        .balloon2 {
            top: -25px;
            right: 80px;
            animation: float2 3.5s ease-in-out infinite 0.5s;
            color: #4ecdc4;
        }

        .balloon3 {
            top: -35px;
            right: 130px;
            animation: float3 4.2s ease-in-out infinite 1s;
            color: #45b7d1;
        }

        .balloon4 {
            top: -20px;
            left: 30px;
            animation: float1 3.8s ease-in-out infinite 1.5s;
            color: #f9ca24;
        }

        .balloon5 {
            top: -30px;
            left: 80px;
            animation: float2 4.1s ease-in-out infinite 0.8s;
            color: #6c5ce7;
        }

        @keyframes float1 {

            0%,
            100% {
                transform: translateY(0px) rotate(-2deg);
            }

            50% {
                transform: translateY(-15px) rotate(2deg);
            }
        }

        @keyframes float2 {

            0%,
            100% {
                transform: translateY(0px) rotate(1deg);
            }

            50% {
                transform: translateY(-20px) rotate(-1deg);
            }
        }

        @keyframes float3 {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-12px) rotate(1deg);
            }
        }

        /* Confetti Animation */
        .confetti-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: confettiFall 3s linear infinite;
        }

        .confetti:nth-child(odd) {
            background: #ff6b6b;
            animation-delay: 0s;
        }

        .confetti:nth-child(even) {
            background: #4ecdc4;
            animation-delay: 0.5s;
        }

        .confetti:nth-child(3n) {
            background: #45b7d1;
            animation-delay: 1s;
            border-radius: 0;
            transform: rotate(45deg);
        }

        .confetti:nth-child(4n) {
            background: #f9ca24;
            animation-delay: 1.5s;
        }

        .confetti:nth-child(5n) {
            background: #6c5ce7;
            animation-delay: 2s;
            width: 6px;
            height: 12px;
            border-radius: 0;
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Sparkles Enhancement */
        .sparkle-wrapper {
            position: relative;
        }

        .sparkle {
            position: absolute;
            font-size: 1.2rem;
            animation: sparkleAnimation 2s linear infinite;
        }

        .sparkle1 {
            top: -10px;
            left: 20%;
            animation-delay: 0s;
        }

        .sparkle2 {
            top: -15px;
            right: 20%;
            animation-delay: 0.5s;
        }

        .sparkle3 {
            bottom: -10px;
            left: 30%;
            animation-delay: 1s;
        }

        .sparkle4 {
            bottom: -15px;
            right: 30%;
            animation-delay: 1.5s;
        }

        .sparkle::before {
            content: '✨';
        }

        @keyframes sparkleAnimation {

            0%,
            100% {
                opacity: 0;
                transform: scale(0.5) rotate(0deg);
            }

            50% {
                opacity: 1;
                transform: scale(1) rotate(180deg);
            }
        }

        /* Modal Body Enhancements */
        .modal-body {
            background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
            position: relative;
            padding: 2rem !important;
        }

        .birthday-cake-container {
            position: relative;
            display: inline-block;
        }

        .birthday-cake {
            font-size: 4rem;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.2));
            animation: cakeWobble 2s ease-in-out infinite;
        }

        @keyframes cakeWobble {

            0%,
            100% {
                transform: rotate(-2deg);
            }

            50% {
                transform: rotate(2deg);
            }
        }

        .candle-flame {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1rem;
            animation: flicker 1s ease-in-out infinite alternate;
        }

        .candle-flame::before {
            content: '🔥';
        }

        @keyframes flicker {
            0% {
                opacity: 0.8;
                transform: translateX(-50%) scale(0.9);
            }

            100% {
                opacity: 1;
                transform: translateX(-50%) scale(1.1);
            }
        }

        /* Form Styling */
        .form-control {
            border: 2px solid #e3e6f0 !important;
            border-radius: 15px !important;
            padding: 15px 20px !important;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: #ff6b6b !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25) !important;
            background: white;
        }

        .form-label {
            font-weight: 600 !important;
            color: #2c3e50 !important;
            margin-bottom: 0.8rem !important;
        }

        /* Button Styling */
        .btn {
            border-radius: 50px !important;
            padding: 12px 30px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            border: none !important;
            transition: all 0.3s ease !important;
            position: relative;
            overflow: hidden;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24) !important;
            color: white !important;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4) !important;
        }

        .btn-danger:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.6) !important;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #74b9ff, #0984e3) !important;
            color: white !important;
            box-shadow: 0 5px 15px rgba(116, 185, 255, 0.4) !important;
        }

        .btn-secondary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 25px rgba(116, 185, 255, 0.6) !important;
        }

        /* Footer Styling */
        .modal-footer {
            background: linear-gradient(90deg, #74b9ff, #0984e3) !important;
            border: none !important;
            border-radius: 0 0 25px 25px !important;
            padding: 1.5rem 2rem !important;
        }

        .modal-footer small {
            font-weight: 500 !important;
            font-size: 1rem !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Close button */
        .btn-close {
            background-size: 1.2rem;
            opacity: 0.8;
            filter: invert(1);
        }

        .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Employee name highlight */
        .employee-name {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700 !important;
            font-size: 1.4rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-title {
                font-size: 1.5rem;
            }

            .balloon {
                font-size: 2rem;
            }

            .birthday-cake {
                font-size: 3rem;
            }

            .employee-name {
                font-size: 1.2rem;
            }
        }


        .leave-item {
            transition: all 0.2s ease-in-out;
            border-radius: 8px;
        }

        .leave-item:hover {
            background: #f5f7fa;
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        }

        /* Avatar Colors */
        .avatar-sick {
            background: linear-gradient(135deg, #ff6b6b, #ff8787);
        }

        .avatar-casual {
            background: linear-gradient(135deg, #4dabf7, #228be6);
        }

        .avatar-earned {
            background: linear-gradient(135deg, #51cf66, #40c057);
        }

        .avatar-maternity {
            background: linear-gradient(135deg, #fcc419, #fab005);
        }

        .avatar-default {
            background: linear-gradient(135deg, #868e96, #495057);
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <div class="float-end">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Device Information</li>
                            </ol>
                        </div>
                        <h4 class="page-title mb-0">Device Information</h4>
                    </div>
                </div>
            </div>



            <div class="row mb-4 g-3">
                <div class="col-md-2">
                    <div class="card text-center shadow border-0 rounded-3 h-100">
                        <div class="card-body">
                            <div class="text-primary mb-2 fs-3"><i class="bi bi-people-fill"></i></div>
                            <h6 class="text-muted">Total Employees</h6>
                            <h3 class="fw-bold">{{ $totalEmployees }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card text-center shadow border-0 rounded-3 h-100">
                        <div class="card-body">
                            <div class="text-success mb-2 fs-3"><i class="bi bi-person-check-fill"></i></div>
                            <h6 class="text-muted">Active</h6>
                            <h3 class="fw-bold">{{ $activeEmployees }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card text-center shadow border-0 rounded-3 h-100">
                        <div class="card-body">
                            <div class="text-warning mb-2 fs-3"><i class="bi bi-person-plus-fill"></i></div>
                            <h6 class="text-muted">New Hires</h6>
                            <h3 class="fw-bold">{{ $newHires }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card text-center shadow border-0 rounded-3 h-100">
                        <div class="card-body">
                            <div class="text-danger mb-2 fs-3"><i class="bi bi-arrow-down-circle-fill"></i></div>
                            <h6 class="text-muted">Attrition</h6>
                            <h3 class="fw-bold">{{ $attrition }}%</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card text-center shadow border-0 rounded-3 h-100">
                        <div class="card-body">
                            <div class="text-info mb-2 fs-3"><i class="bi bi-cash-stack"></i></div>
                            <h6 class="text-muted">Payroll</h6>
                            <h3 class="fw-bold">₹{{ number_format($payroll / 100000, 1) }}L</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card text-center shadow border-0 rounded-3 h-100">
                        <div class="card-body">
                            <div class="text-secondary mb-2 fs-3"><i class="bi bi-calendar-x-fill"></i></div>
                            <h6 class="text-muted">Pending Leaves</h6>
                            <h3 class="fw-bold">{{ $pendingLeaves }}</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-4 d-flex flex-column gap-3">

                    <div class="dashboard-card shadow p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="section-title fw-semibold mb-0">🎂 Upcoming Birthdays</h5>
                            @if (session()->has('success'))
                                <span class="text-success">{{ session('success') }}</span>
                            @endif
                        </div>

                        @php
                            use Carbon\Carbon;

                            // Group birthdays by month name (e.g. January, February)
                            $groupedBirthdays = collect($birthdays)->groupBy(function ($bday) {
                                return Carbon::parse($bday['date'])->format('F'); // Month name
                            });

                            $today = Carbon::today();
                            $tomorrow = Carbon::tomorrow();
                        @endphp

                        @forelse($groupedBirthdays as $month => $birthdaysInMonth)
                            <h5 class="fw-bold mt-3 mb-2 text-primary border-bottom pb-1">{{ $month }}</h5>

                            <ul class="list-group list-group-flush mb-3">
                                @foreach ($birthdaysInMonth as $birthday)
                                    @php
                                        $birthdayDate = \Carbon\Carbon::parse($birthday['date']);
                                        $monthName = $birthdayDate->format('F');
                                        $badgeClass = 'bg-secondary text-light';
                                        $icon = '';
                                        $bgColor = '#fff';
                                        $isToday = false;

                                        // Month color palette (feel free to adjust!)
                                        $monthColors = [
                                            'January' => '#1abc9c',
                                            'February' => '#9b59b6',
                                            'March' => '#3498db',
                                            'April' => '#2ecc71',
                                            'May' => '#f1c40f',
                                            'June' => '#e67e22',
                                            'July' => '#e74c3c',
                                            'August' => '#16a085',
                                            'September' => '#2980b9',
                                            'October' => '#8e44ad',
                                            'November' => '#d35400',
                                            'December' => '#c0392b',
                                        ];

                                        $calendarColor = $monthColors[$monthName] ?? '#6c757d';

                                        if ($birthdayDate->isSameDay($today)) {
                                            $badgeClass = 'bg-success text-light';
                                            $icon = '🎂';
                                            $bgColor = '#d4edda';
                                            $isToday = true;
                                        } elseif ($birthdayDate->isSameDay($tomorrow)) {
                                            $badgeClass = 'bg-info text-light';
                                            $icon = '✨';
                                            $bgColor = '#d1ecf1';
                                        }
                                    @endphp

                                    <li class="d-flex align-items-center mb-1 p-1 rounded shadow-sm flex-wrap
                                            {{ $isToday ? 'clickable' : '' }}"
                                        style="background-color: {{ $bgColor }}; {{ $isToday ? 'cursor: pointer;' : '' }}"
                                        @if ($isToday) wire:click="openModal({{ $birthday['id'] }})" @endif>

                                        <div class="calendar-box text-center me-3 mb-2 mb-sm-0"
                                            style="background: linear-gradient(145deg, {{ $calendarColor }}, #fff); border-radius: 8px; padding: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            <div class="text-uppercase small fw-bold text-light px-2 py-1 rounded"
                                                style="background-color: {{ $calendarColor }};">
                                                {{ $birthdayDate->format('M') }}
                                            </div>
                                            <div
                                                class="day fw-bold fs-5 d-flex align-items-center justify-content-center text-dark mt-1">
                                                {{ $birthdayDate->format('d') }}
                                                @if ($icon)
                                                    <span class="ms-1">{{ $icon }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span
                                                    class="holiday-name fw-semibold d-block">{{ $birthday['name'] }}</span>
                                            </div>

                                            @if ($birthdayDate->isSameDay($today))
                                                <span class="badge bg-success ms-2">Today</span>
                                            @elseif($birthdayDate->isSameDay($tomorrow))
                                                <span class="badge bg-info ms-2">Tomorrow</span>
                                            @endif
                                        </div>
                                    </li>

                                    {{-- @php
                                        $birthdayDate = Carbon::parse($birthday['date']);
                                        $badgeClass = 'bg-secondary text-light';
                                        $icon = '';
                                        $bgColor = '#fff';
                                        $isToday = false;

                                        if ($birthdayDate->isSameDay($today)) {
                                            $badgeClass = 'bg-success text-light';
                                            $icon = '🎂';
                                            $bgColor = '#d4edda';
                                            $isToday = true;
                                        } elseif ($birthdayDate->isSameDay($tomorrow)) {
                                            $badgeClass = 'bg-info text-light';
                                            $icon = '✨';
                                            $bgColor = '#d1ecf1';
                                        }
                                    @endphp

                                    <li class="d-flex align-items-center mb-1 p-1 rounded shadow-sm flex-wrap
                                        {{ $isToday ? 'clickable' : '' }}"
                                        style="background-color: {{ $bgColor }}; {{ $isToday ? 'cursor: pointer;' : '' }}"
                                        @if ($isToday) wire:click="openModal({{ $birthday['id'] }})" @endif>

                                        <div class="calendar-box text-center me-3 mb-2 mb-sm-0">
                                            <div class="{{ $badgeClass }} py-1 px-2 rounded text-uppercase small">
                                                {{ $birthdayDate->format('M') }}
                                            </div>
                                            <div class="day fw-bold fs-5 d-flex align-items-center justify-content-center">
                                                {{ $birthdayDate->format('d') }}
                                                @if ($icon)
                                                    <span class="ms-1">{{ $icon }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="holiday-name fw-semibold d-block">{{ $birthday['name'] }}</span>
                                            </div>

                                            @if ($birthdayDate->isSameDay($today))
                                                <span class="badge bg-success ms-2">Today</span>
                                            @elseif($birthdayDate->isSameDay($tomorrow))
                                                <span class="badge bg-info ms-2">Tomorrow</span>
                                            @endif
                                        </div>
                                    </li> --}}
                                @endforeach
                            </ul>
                        @empty
                            <li class="list-group-item text-center">No upcoming birthdays.</li>
                        @endforelse

                        <!-- Recent Wishes Section -->
                        <div class="section-title fw-semibold mb-2 d-flex justify-content-between align-items-center">
                            <span>📝 Recently Sent Birthday Wishes</span>
                            @if (count($recentWishes))
                                <button class="btn btn-sm btn-link" wire:click="openHistoryModal">View All</button>
                            @endif
                        </div>

                        @if (count($recentWishes))
                            <ul class="list-group list-group-flush mb-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($recentWishes as $wish)
                                    @php
                                        $receiver = \App\Models\Employee::find($wish['employee_id']);
                                        $sender = \App\Models\Employee::find($wish['sent_by']);
                                    @endphp
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $receiver->emp_first_name }}
                                                {{ $receiver->emp_last_name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Sent by: {{ $sender->emp_first_name }} {{ $sender->emp_last_name }}
                                                on {{ \Carbon\Carbon::parse($wish['sent_at'])->format('d M Y H:i') }}
                                            </small>
                                            <br>
                                            <small>{{ $wish['message'] }}</small>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-muted">No birthday wishes sent yet.</p>
                        @endif


                    </div>



                </div>

                <!-- Middle Column (Charts) -->
                <div class="col-md-5 d-flex flex-column gap-3">
                    <div class="dashboard-card">
                        <div class="section-title">📈 Attendance Overview</div>
                        <div x-data="{
                            present: @entangle('presentCount'),
                            absent: @entangle('absentCount'),
                            leave: @entangle('leaveCount'),
                            chart: null,
                            init() {
                                this.renderChart();
                                Livewire.on('refreshAttendanceChart', () => {
                                    this.updateChart();
                                });
                            },
                            renderChart() {
                                const ctx = document.getElementById('attendanceChart');
                                this.chart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Present', 'Absent', 'Leave'],
                                        datasets: [{
                                            data: [this.present, this.absent, this.leave],
                                            backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        plugins: { legend: { position: 'bottom' } }
                                    }
                                });
                            },
                            updateChart() {
                                this.chart.data.datasets[0].data = [this.present, this.absent, this.leave];
                                this.chart.update();
                            }
                        }">
                            <canvas id="attendanceChart"></canvas>
                        </div>


                        {{-- <canvas id="attendanceChart"></canvas> --}}
                    </div>
                    <!-- <div class="dashboard-card"><div class="section-title">💰 Payroll Trend</div><canvas id="payrollChart"></canvas></div> -->
                    <div class="dashboard-card">
                        <div class="section-title">📉 Attrition Trend</div><canvas id="attritionChart"></canvas>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-3 d-flex flex-column gap-3">

                    <div class="dashboard-card shadow rounded p-3"
                        style="background: linear-gradient(135deg, #f9fafc, #ffffff);">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <h6 class="mb-0 fw-semibold text-dark d-flex align-items-center">
                                🌴 <span class="ms-2">On Leave Today</span>
                            </h6>
                            <span class="badge bg-light text-dark border shadow-sm">
                                {{ $leavesToday->count() }} {{ Str::plural('Employee', $leavesToday->count()) }}
                            </span>
                        </div>

                        <ul class="list-group list-group-flush small">
                            @forelse($leavesToday as $leave)
                                <li
                                    class="list-group-item leave-item px-0 py-3 d-flex justify-content-between align-items-center border-0">
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar Circle with Dynamic Color -->
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm
                                            @if ($leave->leaveType->type_name == 'Medical Leave') avatar-sick
                                            @elseif($leave->leaveType->type_name == 'Casual Leave') avatar-casual
                                            @else avatar-default @endif"
                                            style="width: 40px; height: 40px; font-size: 14px; font-weight: 600; color: #fff;">
                                            {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $leave->user->name ?? 'N/A' }}</div>
                                            <small
                                                class="text-muted">{{ $leave->user->designation ?? 'Employee' }}</small>
                                        </div>
                                    </div>

                                    <!-- Badge with Tooltip -->
                                    <span @class([
                                        'badge  ms-2',
                                        'bg-danger' => $leave->leaveType->type_name == 'Medical Leave',
                                        'bg-primary' => $leave->leaveType->type_name == 'Casual Leave',
                                        'bg-secondary' => !in_array($leave->leaveType->type_name, [
                                            'Medical Leave',
                                            'Casual Leave',
                                            'Earned Leave',
                                            'Maternity Leave',
                                        ]),
                                    ]) data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="From {{ \Carbon\Carbon::parse($leave->apply_strt_date)->format('d M Y') }}
                                    to {{ \Carbon\Carbon::parse($leave->apply_end_date)->format('d M Y') }}">
                                        {{ $leave->leaveType->type_name }}
                                    </span>

                                </li>
                            @empty
                                <li class="list-group-item px-0 py-3 text-center text-muted border-0">
                                    No employees on leave today 🎉
                                </li>
                            @endforelse
                        </ul>
                    </div>





                    <div class="dashboard-card shadow-sm rounded-3 p-3 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold text-dark d-flex align-items-center">
                                <i class="bi bi-person-dash text-danger me-2"></i> Absent Today
                            </h6>
                            <span class="badge rounded-pill bg-light text-dark px-3 py-2 shadow-sm">
                                {{ $absentees->count() }} {{ Str::plural('Employee', $absentees->count()) }}
                            </span>
                        </div>

                        @if ($absentees->isEmpty())
                            <div class="text-center text-muted small fst-italic py-3">
                                🎉 No absentees today
                            </div>
                        @else
                            <ul class="list-unstyled mb-0">
                                @foreach ($absentees as $employee)
                                    <li
                                        class="d-flex align-items-center justify-content-between py-2 px-2 rounded-2 mb-1 hover-bg-light">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-danger-subtle text-danger fw-bold me-2">
                                                {{ Str::substr($employee->name, 0, 1) }}
                                            </div>
                                            <span class="fw-medium text-dark">{{ $employee->name }}</span>
                                        </div>
                                        <span class="badge bg-light text-muted small">
                                            {{ $employee->department ?? '—' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="dashboard-card p-3 rounded shadow bg-white">
                        <div class="section-title mb-3 fw-bold fs-6">📅 Upcoming Holidays</div>
                        <ul class="list-unstyled m-0">
                            @forelse($holidays as $holiday)
                                <li class="d-flex align-items-center mb-2">
                                    <div
                                        class="calendar-box text-center me-2 {{ strtolower($holiday->start_date->format('M')) }}">
                                        <div class="month">{{ strtoupper($holiday->start_date->format('M')) }}</div>
                                        <div class="day">{{ $holiday->start_date->format('d') }}</div>
                                    </div>
                                    <span class="holiday-name">{{ $holiday->name }}</span>
                                </li>
                            @empty
                                <li>No upcoming holidays</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="dashboard-card shadow">
                        <div class="section-title">📌 Upcoming Events</div>
                        {{-- <ul class="list-group list-group-flush">
                            <li class="d-flex align-items-center mb-2">
                                <div class="calendar-box text-center me-2 sep">
                                    <div class="month">SEP</div>
                                    <div class="day">15</div>
                                </div>
                                <span class="holiday-name">Independence Day</span>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <div class="calendar-box text-center me-2 sep">
                                    <div class="month">SEP</div>
                                    <div class="day">15</div>
                                </div>
                                <span class="holiday-name">Independence Day</span>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <div class="calendar-box text-center me-2 sep">
                                    <div class="month">SEP</div>
                                    <div class="day">15</div>
                                </div>
                                <span class="holiday-name">Independence Day</span>
                            </li>
                        </ul> --}}
                        No upcoming events found!
                    </div>

                    {{-- <div class="dashboard-card shadow-sm rounded p-3" style="background:#fff;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold text-dark">🔔 Notifications</h6>
                            <span class="badge bg-secondary-subtle text-dark fw-normal">0</span>
                        </div>

                        <!-- If no notifications -->
                        <div class="text-muted small fst-italic">No new notifications 🎉</div>

                        <!-- Example notifications (show only when there are items) -->

                        <div class="small">
                            <div class="py-2 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-dark">Payroll for Aug processed</span>
                            </div>
                            <div class="py-2 d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                <span class="text-dark">5 Pending Leave Requests</span>
                            </div>
                            <div class="py-2 d-flex align-items-center">
                                <i class="bi bi-pin-angle-fill text-primary me-2"></i>
                                <span class="text-dark">Interview scheduled</span>
                            </div>
                        </div>

                    </div> --}}

                </div>
            </div>

        </div>





        <!-- Full History Modal -->
        @if ($showHistoryModal)
            <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">📜 Full Birthday Wishes History</h5>
                            <button type="button" class="btn-close" wire:click="closeHistoryModal"></button>
                        </div>
                        <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                            @if (count($recentWishes))
                                <ul class="list-group list-group-flush">
                                    @foreach ($recentWishes as $wish)
                                        @php
                                            $receiver = \App\Models\Employee::find($wish['employee_id']);
                                            $sender = \App\Models\Employee::find($wish['sent_by']);
                                        @endphp
                                        <li class="list-group-item">
                                            <strong>{{ $receiver->emp_first_name }}
                                                {{ $receiver->emp_last_name }}</strong>
                                            <small class="text-muted">
                                                Sent by: {{ $sender->emp_first_name }}
                                                {{ $sender->emp_last_name }}
                                                ({{ \Carbon\Carbon::parse($wish['sent_at'])->format('d M Y H:i') }})
                                            </small>
                                            <br>
                                            {{ $wish['message'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-center text-muted">No birthday wishes sent yet.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" wire:click="closeHistoryModal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal -->
        @if ($showModal && $selectedEmployee)
            <div class="modal fade show d-block" tabindex="-1"
                style="background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden position-relative">

                        <!-- Confetti Layer -->
                        <div class="confetti-container"></div>

                        <!-- Realistic Floating Balloons -->
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="balloon balloon{{ $i }}">
                                <div class="balloon-body">
                                    <div class="balloon-highlight"></div>
                                </div>
                                <div class="balloon-knot"></div>
                                <div class="balloon-string"></div>
                            </div>
                        @endfor

                        <!-- Modal Header -->
                        <div class="modal-header p-4 text-white"
                            style="background: linear-gradient(135deg, #FF6FD8, #FF9472);">
                            <h5 class="modal-title fw-bold">
                                🎉 Happy Birthday, {{ $selectedEmployee->emp_first_name }}!
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeModal"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body p-4">
                            <div class="text-center mb-4">
                                <div class="birthday-cake-container">
                                    <div class="birthday-cake">🎂</div>
                                    <div class="candle-flame"></div>
                                </div>
                            </div>

                            <!-- Employee Name with Sparkles -->
                            <p class="text-center mb-4 fw-semibold text-dark position-relative sparkle-wrapper">
                                🎈 <span class="employee-name">{{ $selectedEmployee->emp_first_name }}
                                    {{ $selectedEmployee->emp_last_name }}</span> 🎈
                                <span class="sparkle sparkle1"></span>
                                <span class="sparkle sparkle2"></span>
                                <span class="sparkle sparkle3"></span>
                                <span class="sparkle sparkle4"></span>
                            </p>

                            <!-- Birthday Message Input -->
                            <div class="mb-4">
                                <label for="birthdayMessage" class="form-label">Your Birthday Message 💌</label>
                                <textarea id="birthdayMessage" class="form-control border-1 shadow-sm" wire:model.defer="message" rows="4"
                                    placeholder="Share your heartfelt birthday wishes here... 🎁✨"></textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="closeModal">Close</button>
                                <button type="button" class="btn btn-danger" wire:click="sendBirthdayWish">Send
                                    Birthday Wish 🎁</button>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer justify-content-center"
                            style="background: linear-gradient(90deg, #FFD3B6, #FFAAA5);">
                            <small class="text-white fst-italic fw-bold">✨ Make their day absolutely magical!
                                🎉✨</small>
                        </div>
                    </div>
                </div>
            </div>


            <!-- <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">🎉 {{ $selectedEmployee->emp_first_name }}'s Birthday
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> {{ $selectedEmployee->emp_first_name }}
                            {{ $selectedEmployee->emp_last_name }}
                        </p>
                        <p><strong>Date of Birth:</strong>
                            {{ \Carbon\Carbon::parse($selectedEmployee->emp_dob)->format('d M Y') }}
                        </p>

                        <label for="birthdayMessage" class="form-label"><strong>Birthday
                                Message:</strong></label>
                        <textarea id="birthdayMessage" class="form-control" wire:model.defer="message" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeModal">Close</button>
                        <button class="btn btn-primary" wire:click="sendBirthdayWish">Send
                            Wish</button>
                    </div>
                </div>
            </div>
        </div> -->
        @endif

<pre>
{{-- {{ print_r($attritionMonths, true) }}
{{ print_r($attritionData, true) }} --}}
</pre>

        <livewire:layout.footer />
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Attendance Chart


    // Payroll Chart
    new Chart(document.getElementById("payrollChart"), {
        type: 'line',
        data: {
            labels: ["Mar", "Apr", "May", "Jun", "Jul", "Aug"],
            datasets: [{
                label: "Payroll (₹ L)",
                data: [16, 16.5, 17, 17.5, 18, 18.2],
                borderColor: "#0d6efd",
                backgroundColor: "rgba(13,110,253,0.2)",
                fill: true,
                tension: 0.3
            }]
        }
    });

    // Attrition Chart
    new Chart(document.getElementById("attritionChart"), {
        type: 'line',
        data: {
            labels: @json($attritionMonths),
            datasets: [{
                label: "Attrition %",
                data: @json($attritionData),
                borderColor: "#dc3545",
                backgroundColor: "rgba(220,53,69,0.15)",
                fill: true,
                tension: 0.3
            }]
        }
    });

    // Dark mode toggle
    document.getElementById("toggleMode").addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
    });



    // Generate confetti pieces
    function createConfetti() {
        const confettiContainer = document.querySelector('.confetti-container');
        const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#6c5ce7', '#a29bfe', '#fd79a8'];

        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.animationDelay = Math.random() * 3 + 's';
            confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';

            confettiContainer.appendChild(confetti);
        }
    }

    // Initialize confetti
    createConfetti();
</script>


