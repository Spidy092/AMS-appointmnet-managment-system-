<!doctype html>
<html lang="en">

<head>
    <title>Million Fractional</title>
    @include('includes.meta')
    <link rel="stylesheet" href="{{ asset('public/assets/css/admin.css') }}">

    @yield('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js" integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    @include('includes.adminHeader')
    <div class="container">
        @include('includes.adminSidebar')
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <i class="fa-solid fa-bars"></i>
                </div>
                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                    </label>
                </div>
            </div>
            <div class="inner-wrapper">
                @yield('content')
                @include('includes.footer')
            </div>
            @include('includes.alerts')
        </div>
    </div>

    {{-- confirm box script  --}}
    @include('includes.confirmBox')

    @yield("javaScript")
    <script src="{{ asset('public/assets/js/script.js') }}"></script>
    </body>
</html>
