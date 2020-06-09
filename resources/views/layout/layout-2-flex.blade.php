@extends('layout.application')

@section('layout-content')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <!-- Layout sidenav -->
        @include('layout.includes.layout-sidenav')

        <!-- Layout container -->
            <div class="layout-container">
                <!-- Layout navbar -->
            @include('layout.includes.layout-navbar')

            <!-- Layout content -->
                <div class="layout-content">

                    <!-- Content -->
                    <div class="container-fluid d-flex align-items-stretch flex-grow-1 p-0">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Layout footer -->
                    @include('layout.includes.layout-footer')
                </div>
                <!-- Layout content -->

            </div>
            <!-- / Layout container -->

        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
@endsection
