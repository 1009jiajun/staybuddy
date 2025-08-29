<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>StayBuddy</title>
    <link rel="icon" href="/assets/logo/logo-icon-mini.png" type="image/png">

    <link rel="stylesheet" href="/assets/css/bootstrap2.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&display=swap">
    <link rel="stylesheet" href="/assets/css/fontawesome-all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <!-- Font Awesome 6 Free (Includes fa-solid) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    @yield('styles')
    <style>
        #editor {
            min-height: 100px;
            max-height: 140px;
            overflow-y: auto;
        }

        #editor2 {
            min-height: 160px;
            max-height: 160px;
            overflow-y: auto;
        }
         .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar align-items-start sidebar sidebar-dark accordion p-0 navbar-dark" style="background-color:rgb(70, 157, 119);">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon"><i class="fas fa-user-circle"></i></div>
                    <div class="sidebar-brand-text mx-3">
                        {{-- Dynamic sidebar brand text --}}
                        <span>@yield('title', 'Admin')</span>
                    </div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}" style="text-align: center;">
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/homestays*') ? 'active' : '' }}" href="{{ url('/admin/homestays') }}" style="text-align: center;">
                            <span>Homestay Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/bookings*') ? 'active' : '' }}" href="{{ url('/admin/bookings') }}" style="text-align: center;">
                            <span>Booking Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/timetable*') ? 'active' : '' }}" href="{{ url('/admin/timetable') }}" style="text-align: center;">
                            <span>Booking Timetable</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/social-media*') ? 'active' : '' }}" href="{{ url('/admin/social-media') }}" style="text-align: center;">
                            <span>Social Media Engagement</span>
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('admin_logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <li class="nav-item">
                         <a href="#" style="text-align: center;" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span>Log out</span></a>
                    </li>
                </ul>
                <div class="text-center d-none d-md-inline">
                    <button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button>
                </div>
            </div>
        </nav>

        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-expand bg-white shadow mb-4 topbar">
                    <div class="container-fluid">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3" type="button">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h3 class="text-dark">@yield('page_title')</h3>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow">
                                    <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                                        <span class="d-none d-lg-inline me-2 text-gray-600 small">
                                            {{ session('email') }}
                                        </span>
                                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>{{ session('email', 'Guest') }}</a>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright">
                        <span>Copyright © StayBuddy
                            2025</span>
                    </div>
                </div>
            </footer>
        </div>

        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>

    <script src="/assets/js/bootstrap2.min.js"></script>
    <script src="/assets/js/bs-init.js"></script>
    <script src="/assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    @yield('scripts')
</body>

</html>
