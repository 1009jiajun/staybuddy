<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayBuddy</title>
    <link rel="icon" href="{{ asset('assets/logo/logo-icon-mini.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5fdf6;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            background-color: #fff;
        }
        .sidebar {
            background-color: #fff;
            padding: 20px;
            border-right: 1px solid #dee2e6;
            min-height: calc(100vh - 56px); /* Adjust based on navbar height */
            box-shadow: 2px 0 5px rgba(0,0,0,.05);
        }
        .sidebar-heading {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        .sidebar-link {
            display: block;
            padding: 10px 15px;
            margin-bottom: 5px;
            color: #495057;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.2s, color 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #e9ecef;
            color: #28a745;
            text-decoration: none;
        }
        .content-area {
            padding: 30px;
            background-color: #ffffff;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,.05);
        }

    .profile-btn {
        border: 1px solid #ddd;
        border-radius: 25px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: white;
    }
    .container-fluid {
        padding-left: 2rem;
        padding-right: 2rem;
    }
    .btn-save{
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
    .dropdown-item:hover {
        background-color:rgb(225, 250, 217);
    }

    .dropdown-item{
        margin: 6px 0 6px 0 !important;
        width: 100%;
        padding: 5px 10px;
        font-size: medium;
    }
    .list-group {
        cursor: pointer;
    }

    </style>
</head>
<body>

<div class="container-fluid border-bottom py-4">
    <div class="d-flex justify-content-between align-items-center">

        <a class="d-flex align-items-center" href="{{ url('/') }}" style="text-decoration: none; color: inherit;">
            <img src="{{ asset('assets/logo/logo-icon.png') }}" alt="Logo" height="48">
            <div class="ms-2">
                <span class="fw-bold fs-5 text-success d-block">StayBuddy</span>
                <p class="text-muted mb-0 small">Homestay reservation</p>
            </div>
        </a>

        <div class="d-flex align-items-center gap-3">
            <div class="profile-btn">
                <i class="fas fa-bars"></i>
                @auth
                @php
                    $image = auth()->user()->profile_image;
                    $isUrl = filter_var($image, FILTER_VALIDATE_URL);
                @endphp

                @if ($image)
                    <img src="{{ $isUrl ? $image : Storage::url($image) }}" {{-- CHANGED THIS LINE --}}
                        alt="Profile Image"
                        class="rounded-circle"
                        style="width: 35px; height: 35px; object-fit: cover;" referrerPolicy="no-referrer" />
                @else
                    <div class="rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 30px; height: 30px; background-color:rgb(147, 228, 166); color: white; font-weight: bold;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                @else
                    <i class="fas fa-user-circle fs-4 text-secondary"></i>
                @endauth
            </div>
        </div>

        <div class="profile-menu position-absolute bg-white border rounded shadow-md p-2" style="display: none; top: 80px; right: 2rem; z-index: 1000;width: 200px;">
            <div class="visitor-menu">
                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#registerModal" style="margin: 5px;">Register</a>
                <div class="dropdown-divider" style="border-top: 1px solid #dee2e6;"></div>
                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal" style="margin: 5px;">Login</a>
                <!-- <hr style="margin: 5px 0; border-top: 1px solid rgb(156, 158, 159);">
                <a href="/about" class="dropdown-item">About Us</a> -->
            </div>
            <div class="user-menu" style="display: none;">
                <a href="/account/profile" class="dropdown-item">Profile</a>
                <hr style="margin: 5px 0; border-top: 1px solid rgb(156, 158, 159);">
                <a href="/account/bookings" class="dropdown-item">My Bookings</a>
                <hr style="margin: 5px 0; border-top: 1px solid rgb(156, 158, 159);">
                <a href="/account/favourites" class="dropdown-item">My Favorites</a>
                <!-- <hr style="margin: 5px 0; border-top: 1px solid rgb(156, 158, 159);">
                <a href="/about" class="dropdown-item">About Us</a> -->
                <hr style="margin: 5px 0; border-top: 1px solid rgb(156, 158, 159);">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </div>
        </div>

    </div>
</div>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                        <span>Account</span>
                    </h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="sidebar-link {{ Request::routeIs('account.profile') ? 'active' : '' }}" href="{{ route('account.profile') }}">
                                <i class="fas fa-user-circle me-2"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="sidebar-link {{ Request::routeIs('account.bookings') ? 'active' : '' }}" href="{{ route('account.bookings') }}">
                                <i class="fas fa-book me-2"></i> My Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="sidebar-link {{ Request::routeIs('account.favourites') ? 'active' : '' }}" href="{{ route('account.favourites') }}">
                                <i class="fas fa-heart me-2"></i> My Favourites
                            </a>
                        </li>
                        </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-area">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileBtn = document.querySelector('.profile-btn');
        const profileMenu = document.querySelector('.profile-menu');
        const visitorMenu = document.querySelector('.visitor-menu');
        const userMenu = document.querySelector('.user-menu');

        // Simulated login state (replace with actual logic from backend/session)
        const isLoggedIn = @json(Auth::check());

        // Toggle correct menu based on login state
        if (isLoggedIn) {
            visitorMenu.style.display = 'none';
            userMenu.style.display = 'block';
        } else {
            visitorMenu.style.display = 'block';
            userMenu.style.display = 'none';
        }

        // Toggle menu visibility
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.style.display = profileMenu.style.display === 'none' ? 'block' : 'none';
        });

        // Hide dropdown if clicked outside
        document.addEventListener('click', function() {
            profileMenu.style.display = 'none';
        });

        // Prevent menu click from closing it
        profileMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>
</html>