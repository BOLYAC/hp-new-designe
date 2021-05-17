<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <title>Hashim Group CRM @yield('title')</title>
    @livewireStyles
    @include('layouts.vertical.css')
    @yield('style')
</head>
<body class="light-only" main-theme-layout="ltr">
<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="theme-loader"></div>
</div>
<!-- Loader ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper horizontal-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
@include('layouts.vertical.header')
<!-- Page Header Ends -->
    <!-- Page Body Start-->
    <div class="page-body-wrapper horizontal-menu">
        <nav-menus></nav-menus>


        @include('layouts.vertical.sidebar')f
        <!-- Page Sidebar Ends-->
        <div class="page-body">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="f-16 fa fa-home"></i></a>
                                </li>
                                @yield('breadcrumb-items')
                            </ol>
                            @yield('breadcrumb-title')
                        </div>
                        @yield('bookmarks-start')
                    </div>
                </div>
            </div>
            <!-- Container-fluid starts-->
        @yield('content')
        <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        @include('layouts.vertical.footer')
    </div>
</div>
@livewireScripts
@include('layouts.vertical.script')
</body>
</html>
