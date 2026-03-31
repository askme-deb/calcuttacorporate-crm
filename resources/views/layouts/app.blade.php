<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        @livewireStyles
    </head>

    <body id="body" class="{{ request()->routeIs('dashboard') ? 'enlarge-menu' : '' }}">
        <livewire:layout.leftbar />
        <livewire:layout.navigation />

        @yield('content')
        <!-- Javascript  -->
        <!-- vendor js -->
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/crm-index.init.js') }}"></script>
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script>
            let inactivityTimer;

            function resetTimer() {
                clearTimeout(inactivityTimer);
                inactivityTimer = setTimeout(() => {
                    console.log('Inactivity detected. Locking screen...');
                    Livewire.emitTo('dashboard', 'lockScreen');
                }, 30000); // 5 minutes
            }

            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
        </script>
        @livewireScripts

    </body>
    <!--end body-->
</html>
