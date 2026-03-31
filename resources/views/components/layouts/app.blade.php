<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <link href="{{ asset('assets/libs/mobius1-selectr/selectr.min.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Include Toastr.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <style>
        fieldset,
        legend {
            all: revert;
        }

        .reset {
            all: revert;
        }

        .notification-menu {
            padding: 10px;
        }

        h6.dropdown-item-text {
            padding: 10px;
        }

        span.position-absolute {
            left: 43px !important;
            top: 2px !important;
        }

        .dropdown-lg.pt-0 {
            padding-bottom: 20px;
        }
    </style>
    @livewireStyles
</head>

<body id="body" class="{{ request()->routeIs('dashboard') ? 'enlarge-menu' : '' }}">
    <livewire:layout.leftbar />
    <livewire:layout.navigation />

    {{ $slot }}
    <!-- Javascript  -->
    <!-- vendor js -->

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/pages/crm-index.init.js') }}"></script> -->
    <!-- App js -->

    {{-- <script src="{{ asset('assets/js/pages/forms-advanced.js') }}"></script> --}}
    <script src="{{ asset('assets/js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('assets/js/pages/toast.init.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/pages/sweet-alert.init.js') }}"></script> --}}

    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js')}}"></script>
    <!-- <script src="{{ asset('assets/js/pages/form-editor.init.js')}}"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    </script>

    @livewireScripts
    <script>
        // let inactivityTimer;

        // function resetTimer() {
        //     clearTimeout(inactivityTimer);
        //     inactivityTimer = setTimeout(() => {
        //         Livewire.dispatch('lockScreen'); // Emit the lockScreen event to trigger the lock
        //     }, 50000000); // Lock after 5 minutes of inactivity (adjust time as needed)
        // }
        // window.onload = resetTimer;
        // document.onmousemove = resetTimer;
        // document.onkeypress = resetTimer;
    </script>


    <script>
        document.addEventListener('livewire:init', function() {
            //  $('.js-example-basic-multiple').select2();
            Livewire.on('toastMessage', event => {
                const e = JSON.parse(event);
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right"
                }
                if (e.type === 'success') {
                    toastr.success(e.message);
                } else if (e.type === 'error') {
                    toastr.error(e.message);
                } else if (e.type === 'warning') {
                    toastr.warning(e.message);
                } else if (e.type === 'info') {
                    toastr.info(e.message);
                }
            });

            Livewire.on('swal:success', event => {
                const jsonData = JSON.parse(event);
                Swal.fire({
                    title: jsonData.title,
                    text: jsonData.text,
                    icon: jsonData.icon,
                });
            });
        });
    </script>

    <script>
        // document.addEventListener('livewire:init', function () {
        //     initializeSelect2();
        // });

        // document.addEventListener('livewire:update', function () {
        //     initializeSelect2();
        // });

        // function initializeSelect2() {
        //     if ($.fn.select2) { // Ensure Select2 is loaded
        //         $('.js-example-basic-multiple').select2();
        //     } else {
        //         console.error("Select2 library not loaded.");
        //     }
        // }
    </script>



</body>
<!--end body-->

</html>
