@extends('layout.application')

@section('layout-content')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

        <!-- Layout sidenav -->
        @if($role != 5)
            @include('layout.includes.layout-sidenav')
        @endif
        <!-- Layout container -->
            <div class="layout-container">
                <!-- Layout navbar -->
            @include('layout.includes.layout-navbar')

            <!-- Layout content -->
                <div class="layout-content">

                    <!-- Content -->
                    <div class="container-fluid flex-grow-1 container-p-y">
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
