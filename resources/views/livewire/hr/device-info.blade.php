<div class="page-wrapper">
    <style>
        .brand-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }

        .brand-logo img {
            max-height: 40px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .device-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            max-width: 480px;
            width: 100%;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        .device-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
        }

        .device-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .device-icon {
            width: 64px;
            height: 64px;
            background: var(--theme-gradient, linear-gradient(135deg, #4facfe 0%, #00f2fe 100%));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            animation: glowPulse 2.5s infinite ease-in-out;
            transition: transform 0.2s ease;
            position: relative;
            cursor: pointer;
        }

        .device-icon:hover {
            transform: scale(1.08);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.6), 0 0 40px rgba(255, 255, 255, 0.4);
        }

        .device-icon img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .device-icon::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: -32px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            opacity: 0;
            pointer-events: none;
            white-space: nowrap;
            transition: opacity 0.3s ease;
        }

        .device-icon.show-tooltip::after {
            opacity: 1;
        }

        .device-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .device-subtitle {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .device-info {
            list-style: none;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            transition: background-color 0.2s ease;
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item:hover {
            background-color: rgba(79, 172, 254, 0.04);
            border-radius: 8px;
            margin: 0 -8px;
            padding: 16px 8px;
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .info-label::before {
            content: '';
            width: 8px;
            height: 8px;
            background: var(--theme-gradient, linear-gradient(135deg, #4facfe 0%, #00f2fe 100%));
            border-radius: 50%;
            margin-right: 12px;
        }

        .info-value {
            font-weight: 700;
            color: #1a1a1a;
            font-size: 14px;
            text-align: right;
            font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
            background: rgba(79, 172, 254, 0.08);
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid rgba(79, 172, 254, 0.15);
        }

        .uuid-value {
            font-size: 11px;
            word-break: break-all;
            max-width: 180px;
        }

        .footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            text-align: center;
        }

        .timestamp {
            color: #9ca3af;
            font-size: 12px;
            font-weight: 500;
        }

        .employee-icon {
            width: 64px;
            height: 64px;
            /* background: var(--theme-gradient, linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)); */
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            animation: glowPulse 2.5s infinite ease-in-out;
            transition: transform 0.2s ease;
            position: relative;
            cursor: pointer;
        }

        .employee-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            border-radius: 50%;
        }

        @media (max-width: 480px) {
            .device-card {
                padding: 24px;
                margin: 10px;
            }

            .device-title {
                font-size: 20px;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .info-value {
                width: 100%;
                text-align: left;
            }

            .uuid-value {
                max-width: 100%;
                font-size: 10px;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glowPulse {
            0% {
                box-shadow: 0 0 8px rgba(255, 255, 255, 0.3), 0 0 16px rgba(255, 255, 255, 0.2);
            }

            50% {
                box-shadow: 0 0 16px rgba(255, 255, 255, 0.5), 0 0 32px rgba(255, 255, 255, 0.3);
            }

            100% {
                box-shadow: 0 0 8px rgba(255, 255, 255, 0.3), 0 0 16px rgba(255, 255, 255, 0.2);
            }
        }

        @keyframes spinOnce {
            from {
                transform: rotate(0deg) scale(1);
            }

            to {
                transform: rotate(360deg) scale(1);
            }
        }

        /* Manufacturer Themes using CSS Variables */
        .theme-motorola {
            --theme-gradient: linear-gradient(135deg, #0091D0 0%, #005B84 100%);
            --theme-text: #ffffff;
        }

        .theme-xiaomi {
            --theme-gradient: linear-gradient(135deg, #FF6900 0%, #E85D00 100%);
            --theme-text: #ffffff;
        }

        .theme-samsung {
            --theme-gradient: linear-gradient(135deg, #034ea2 0%, #034ea2 100%);
            --theme-text: #ffffff;
        }

        .theme-apple {
            --theme-gradient: linear-gradient(135deg, #000000 0%, #434343 100%);
            --theme-text: #ffffff;
        }

        .theme-oneplus {
            --theme-gradient: linear-gradient(135deg, #EB0028 0%, #C00020 100%);
            --theme-text: #ffffff;
        }

        .theme-oppo {
            --theme-gradient: linear-gradient(135deg, #00C853 0%, #009624 100%);
            --theme-text: #ffffff;
        }

        .theme-realme {
            --theme-gradient: linear-gradient(135deg, #FDD835 0%, #FBC02D 100%);
            --theme-text: #1a1a1a;
        }

        .theme-vivo {
            --theme-gradient: linear-gradient(135deg, #1E88E5 0%, #1565C0 100%);
            --theme-text: #ffffff;
        }
    </style>
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

            <!-- Device Card -->
            <div class="row">
                <div class="col-12">
                    <div class="col-6 mb-3" wire:ignore>
                        <label for="userSelect" class="form-label">Select User:</label>
                        <select wire:model.live="selectedUserId" class="form-select js-example-basic-single">
                            <option value="">-- Select User --</option>
                            @foreach ($users as $user)
                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @if($selectedUser)
                                <div class="col-lg-6">
                                    <div class="device-card {{ $this->getThemeClass($manufacturer) }}">
                                        <div class="device-header">
                                            <!-- Brand Logo -->
                                            @if($selectedUser)
                                            <div class="employee-icon">
                                                <img src="{{ empProfilePicture($selectedUser->id) }}" alt="">
                                            </div>
                                            @endif

                                            <h1 class="device-title">{{ $selectedUser->name ?? ''; }}</h1>
                                            <p class="device-subtitle">Employee Information</p>
                                        </div>

                                        {{-- USER INFO SECTION --}}
                                        @if($selectedUser)
                                        <h5 class="mb-2 text-primary" style="font-weight: 600;">Employee Details</h5>
                                        <ul class="device-info">
                                            <li class="info-item">
                                                <span class="info-label">Employee Id</span>
                                                <span class="info-value">{{ $selectedUser->employee->emp_code }}</span>
                                            </li>
                                            <li class="info-item">
                                                <span class="info-label">Email</span>
                                                <span class="info-value">{{ $selectedUser->email }}</span>
                                            </li>
                                            <li class="info-item">
                                                <span class="info-label">Phone</span>
                                                <span class="info-value">{{ $selectedUser->employee->emp_contact_no ?? '-' }}</span>
                                            </li>
                                            <li class="info-item">
                                                <span class="info-label">Role</span>
                                                <span class="info-value">
                                                    @foreach($selectedUser->roles as $role)
                                                    <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                                    @endforeach
                                                </span>

                                            </li>
                                        </ul>
                                        <hr style="margin: 20px 0; border-top: 1px solid rgba(0,0,0,0.06);">
                                        @endif

                                    </div>
                                </div>
                                @endif
                                @if($uuid)
                                <div class="col-lg-6">
                                    <div class="device-card {{ $this->getThemeClass($manufacturer) }}">
                                        <div class="device-header">
                                            <!-- Brand Logo -->
                                            <div class="brand-logo">
                                                <img src="{{ $this->getBrandLogo($manufacturer) }}" alt="Brand Logo">
                                            </div>

                                            <!-- OS Icon -->
                                            <div class="device-icon" data-tooltip="{{ $operatingSystem }}">
                                                <img src="{{ $this->getOsIcon($operatingSystem) }}" alt="OS Icon">
                                            </div>

                                            <h1 class="device-title">Device Information</h1>
                                            <p class="device-subtitle">Registered Device Details</p>
                                        </div>

                                        {{-- DEVICE INFO SECTION --}}
                                        <h5 class="mb-2 text-primary" style="font-weight: 600;">Device Details</h5>
                                        <ul class="device-info">
                                            <li class="info-item">
                                                <span class="info-label">Model</span>
                                                <span class="info-value">{{ $model }}</span>
                                            </li>
                                            <li class="info-item">
                                                <span class="info-label">Operating System</span>
                                                <span class="info-value">{{ $operatingSystem }}</span>
                                            </li>
                                            <li class="info-item">
                                                <span class="info-label">OS Version</span>
                                                <span class="info-value">{{ $osVersion }}</span>
                                            </li>
                                            <li class="info-item">
                                                <span class="info-label">Manufacturer</span>
                                                <span class="info-value">{{ $manufacturer }}</span>
                                            </li>
                                            <li class="info-item">
                                                <span class="info-label">Device UUID</span>
                                                <span class="info-value uuid-value">{{ $uuid }}</span>
                                            </li>
                                        </ul>

                                        <div class="footer mt-3">
                                            <p class="timestamp">Generated on {{ $timestamp }}</p>
                                            @if(!is_null($uuid) && $uuid !== '-')
                                            @can(['Deregister Device'])
                                            <div class="mt-3 text-center">
                                                <button
                                                    onclick="confirmDeletion({{ $selectedUserId }})"
                                                    class="btn btn-danger btn-sm rounded-3 shadow-sm align-items-center justify-content-center"
                                                    style="transition: transform 0.2s;"
                                                    onmouseover="this.style.transform='scale(1.05)';"
                                                    onmouseout="this.style.transform='scale(1)';"
                                                    title="Deregister the device and reset all associated information">
                                                    <i class="fas fa-sync-alt me-2"></i> Deregister Device
                                                </button>
                                            </div>
                                            @endcan
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <livewire:layout.footer />
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deviceIconEl = document.querySelector(".device-icon");
        const osIconEl = deviceIconEl.querySelector("img");

        // Spin + tooltip on click
        deviceIconEl.addEventListener("click", () => {
            osIconEl.style.animation = "spinOnce 0.6s ease forwards";
            deviceIconEl.classList.add("show-tooltip");
            setTimeout(() => osIconEl.style.animation = "", 600);
            setTimeout(() => deviceIconEl.classList.remove("show-tooltip"), 1500);
        });
    });
</script>
@script()
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();

        $('.js-example-basic-single').on('change', function(e) {
            let data = $(this).val();
             console.log(data)
            $wire.set('selectedUserId', data)
            $wire.selectedUserId = data;
        });
    });
</script>
@endscript

<script>
    function confirmDeletion(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('resetDeviceInfo', {
                    id: itemId
                }); // Dispatch Livewire event
            }
        });
    }
</script>