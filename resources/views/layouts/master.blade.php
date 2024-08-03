@include('layouts.head')
<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @include('layouts.sidebar')
        <!--  Main wrapper -->
        <div class="body-wrapper">
            @include('layouts.header')
            <div class="container-fluid">
                <!--  Row 1 -->
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>
    </div>
    @include('layouts.scripts')
</body>
</html>
