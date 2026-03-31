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
</head>

<body id="body" class="auth-page card-bg">
   <!-- Log In page -->
    <div class="container-fluid">
        <div class="row vh-100">
            <div class="col-12">
                <div class="card-body p-0">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-5 col-xl-3 col-lg-4">
                            <div class="card mb-0 border-0">
                                <div class="card-body p-0">
                                    <div class="text-center p-3">
                                        <a href="/" wire:navigate class="logo logo-admin">
                                            <x-application-logo class="auth-logo" height="50" alt="logo" />
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold font-18">Let's Get Started CODHR</h4>   
                                        <p class="text-muted  mb-0">Sign in to continue to CODHR.</p>  
                                    </div>
                                </div><!--end card-body-->
                                <div class="card-body pt-0">  
                                    {{ $slot }}                                  
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                        <div class="col-md-7 col-xl-9 col-lg-8  p-0 vh-100 d-flex justify-content-center auth-bg">
                            <div class="accountbg d-flex align-items-center"> 
                                <div class="account-title text-center text-white">
                                    <img src="{{ asset('assets/images/logo.jpeg')}}" alt="" class="">
                                    <h4 class="mt-3 text-white">Welcome To <span class="text-warning">CODHR</span> </h4>
                                    <h1 class="text-white">Simplifying HR, Amplifying Success.</h1>
                                    <p class="font-18 mt-3">Smart HR Solutions for Smarter Businesses.</p>
                                    <div class="border w-25 mx-auto border-warning"></div>
                                </div>
                            </div><!--end /div-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
    <!-- vendor js -->
    
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    
</body>

</html>