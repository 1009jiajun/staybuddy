<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayBuddy</title>
    <link rel="icon" href="{{ asset('assets/logo/logo-icon-mini.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
    body {
        background-color: #f5fdf6;
    }

    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: scale(1.02);
    }

    .header {
        background-color: #28a745;
        color: white;
        padding: 20px;
        text-align: center;
        border-radius: 0 0 15px 15px;
    }

    .search-box {
        background-color: white;
        border-radius: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 5px 20px;
    }

    .search-box input {
        border: none;
        outline: none;
    }

    .search-icon {
        background-color: #28a745;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
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

    .bar {
        width: 55%;
        background: white;
        box-shadow: 0 0 5px hsl(0 0% 78%);
        height: 55px;
        border-radius: 100vw;
        display: flex;
        justify-content: center;
        font-size: 0.6rem;
    }

    .search-field {
        border-radius: inherit;
        padding: 0.8rem 1.5rem;
        transition: background 250ms ease;
    }

    .search-field:hover {
        background: hsl(0 0% 94%);
    }

    .location {
        width: 28%;
    }

    .check-in,
    .check-out,
    .guests {
        width: 24%;
    }

    input[type="text"] {
        background: none;
        border: none;
        padding: 0 0 0 0;
        font-size: 0.9rem !important;
        /* or 16px, or any size you want */
    }

    input[type="text"]::placeholder {
        font-size: 0.9rem;
        /* match this to your input text size */
    }


    input[type="text"]:focus {
        outline: none;
    }

    ::placeholder {
        font-size: 0.75rem;
    }

    .guests {
        position: relative;
    }

    .guests span {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        background: #28a745;
        color: white;
        font-size: 0.8rem;
        padding: 0.7rem;
        border-radius: 50%;
    }

    .bar>div {
        position: relative;
    }


    .bar>div::before {
        position: absolute;
        content: "";
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 1px;
        height: 55%;
        background: hsl(0 0% 90%);
    }

    .bar>div:nth-of-type(1)::before {
        background: transparent;
    }


    .bar>div:hover::before {
        background: transparent;
    }

    .container-fluid {
        padding-left: 2rem;
        padding-right: 2rem;
    }

    .bar h6 {
        margin: 0;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .carousel-control-prev,
    .carousel-control-next {
        opacity: 0;
        transition: opacity 0.3s ease;
        outline: none;
        /* removes default focus outline */
    }

    /* Show on hover */
    .carousel:hover .carousel-control-prev,
    .carousel:hover .carousel-control-next {
        opacity: 1;
    }

    /* Hide when not hovered, even if focused */
    .carousel:not(:hover) .carousel-control-prev:focus,
    .carousel:not(:hover) .carousel-control-next:focus {
        opacity: 0;
    }

    .carousel-indicators [data-bs-target] {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .d-flex.align-items-center.gap-3 {
        position: relative;
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

    .modal-content {
        border-radius: 1.2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .modal-body input[type="text"], .modal-body input[type="email"], .modal-body input[type="password"] {
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
        padding: 0.5rem;
        font-size: 1rem !important;
    }

    .modal-body input[type="text"]::placeholder, .modal-body input[type="email"]::placeholder, .modal-body input[type="password"]::placeholder {
        color: #6c757d;
        font-size: 1rem !important;

    }

    /* Common dropdown styling */
    .search-dropdown {
        /* These styles are applied in HTML directly for initial setup, but good to have here too */
        /* position: absolute; */
        /* display: none; */
        /* background-color: #fff; */
        /* border: 1px solid #ddd; */
        /* border-radius: 8px; */
        /* box-shadow: 0 4px 12px rgba(0,0,0,0.15); */
        /* z-index: 1000; */
        /* padding: 15px; */
    }

    /* Specific styling for guest buttons */
    .guest-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem !important;
        font-weight: bold;
    }

    .check-in-menu, .check-out-menu {
        min-width: 300px;
        width: auto !important;
    }
    #filter-tags .badge {
        font-size: 0.75rem;
        padding: 0.6em 0.6em;
        cursor: pointer;
        background-color: #28a745 !important;
    }

    #clear-filters-btn {
        font-size: 0.75rem;
    }

    .fa-solid {
        color: #28a745;
    }
    </style>
</head>

<body>
    <!-- show alert if the user is logged in -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <!-- HEADER -->
   <div class="container-fluid border-bottom py-4">
    <div class="d-flex justify-content-between align-items-center">

        <a class="d-flex align-items-center" href="{{ url('/') }}" style="text-decoration: none; color: inherit;">
            <img src="{{ asset('assets/logo/logo-icon.png') }}" alt="Logo" height="48">
            <div class="ms-2">
                <span class="fw-bold fs-5 text-success d-block">StayBuddy</span>
                <p class="text-muted mb-0 small">Homestay reservation</p>
            </div>
        </a>    

        <form action="{{ url('/') }}" method="GET" class="bar position-relative">
            <div class="location search-field" data-target="destination-menu">
                <h6 class="text-muted">Where</h6>
                <input type="text" placeholder="Search destinations" class="location-input" name="location" value="{{ old('location', $filters['location'] ?? '') }}">

            </div>

            <div class="check-in search-field" data-target="check-in-menu">
                <h6 class="text-muted">Check in</h6>
                <input type="text" placeholder="Add dates" class="check-in-input" name="check_in_date" readonly value="{{ old('check_in_date', $filters['check_in_date'] ?? '') }}">
            </div>

            <div class="check-out search-field" data-target="check-out-menu">
                <h6 class="text-muted">Check out</h6>
                <input type="text" placeholder="Add dates" class="check-out-input" name="check_out_date" readonly value="{{ old('check_out_date', $filters['check_out_date'] ?? '') }}">
            </div>

            <div class="guests search-field" data-target="guests-menu">
                <h6 class="text-muted">Who</h6>
                <input type="text" placeholder="Add guests" class="guests-input" value="{{ old('adults', $filters['adults'] ?? 1) }} adults, {{ old('children', $filters['children'] ?? 0) }} children" readonly>
                <input type="hidden" name="adults" id="adults-hidden-input" value="{{ old('adults', $filters['adults'] ?? 1) }}">
                <input type="hidden" name="children" id="children-hidden-input" value="{{ old('children', $filters['children'] ?? 0) }}">
                <!-- <button type="submit" class="ms-3 search-icon border-0" style="cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button> -->
                <span class="ms-3 search-icon">
                    <button type="submit" class="search-icon border-0" style="cursor: pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                </span>
            </div>

            <div class="destination-menu search-dropdown position-absolute bg-white border rounded shadow-md p-2"
                style="display: none; z-index: 1000; width: 200px;">
                <a href="#" class="dropdown-item">Melaka</a>
                <a href="#" class="dropdown-item">Selangor</a>
                <a href="#" class="dropdown-item">Johor</a>
                <a href="#" class="dropdown-item">Perak</a>
                <a href="#" class="dropdown-item">Kuala Lumpur</a>
                <a href="#" class="dropdown-item">Sabah</a>
                <a href="#" class="dropdown-item">Pahang</a>
                <a href="#" class="dropdown-item">Pulau Pinang</a>
                <a href="#" class="dropdown-item">Kedah</a>
                <a href="#" class="dropdown-item">Negeri Sembilan</a>
            </div>

            <div class="check-in-menu search-dropdown position-absolute bg-white border rounded shadow-md p-2"
                style="display: none; z-index: 1000; min-width: 300px;width: 100% !important;">
            </div>

            <div class="check-out-menu search-dropdown position-absolute bg-white border rounded shadow-md p-2"
                style="display: none; z-index: 1000; min-width: 300px; width: 100% !important;">
            </div>

            <div class="guests-menu search-dropdown position-absolute bg-white border rounded shadow-md p-2"
                style="display: none; z-index: 1000; width: 250px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <div class="fw-bold" style="font-size: 0.8rem;">Guests</div>
                        <div class="text-muted" style="font-size: 0.8rem;">Ages 13 or above</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-outline-secondary guest-btn" data-type="adults" data-action="minus">-</button>
                        <span class="mx-2 guest-count" style="font-size: 0.8rem;" data-type="adults">1</span>
                        <button type="button" class="btn btn-outline-secondary guest-btn" data-type="adults" data-action="plus">+</button>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold" style="font-size: 0.8rem;">Children</div>
                        <div class="text-muted" style="font-size: 0.8rem;">Ages 2-12</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-outline-secondary guest-btn" data-type="children" data-action="minus">-</button>
                        <span class="mx-2 guest-count" style="font-size: 0.8rem;" data-type="children">0</span>
                        <button type="button" class="btn btn-outline-secondary guest-btn" data-type="children" data-action="plus">+</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="d-flex align-items-center gap-3">
            <div class="profile-btn">
                <i class="fas fa-bars"></i>
                @auth
                @php
                    $image = auth()->user()->profile_image;
                    $isUrl = filter_var($image, FILTER_VALIDATE_URL);
                @endphp

                @if ($image)
                    <img src="{{ $isUrl ? $image : Storage::url($image) }}"
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

        <div class="profile-menu position-absolute bg-white border rounded shadow-md p-2" style="display: none; top: 75px; right: 2rem; z-index: 1000;width: 200px;">
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

    <div id="staybuddyCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/banner/banner3.png') }}" class="d-block w-100" alt="Slide 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/banner/banner1.png') }}" class="d-block w-100" alt="Slide 2">
            </div>
            <!-- <div class="carousel-item">
                <img src="{{ asset('assets/images/slide3.jpg') }}" class="d-block w-100" alt="Slide 3">
            </div> -->
        </div>

        <!-- Optional: Carousel controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#staybuddyCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#staybuddyCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <div class="container mt-5 mb-5">
       @php
            // Count filters that are not null or empty (excluding default adults = 1)
            $isFilterApplied = 
                !empty($filters['location']) ||
                $filters['adults'] > 1 ||
                $filters['children'] > 0 ||
                !empty($filters['check_in_date']) ||
                !empty($filters['check_out_date']);
        @endphp

        @if ($isFilterApplied)
            @php
                $filtersQuery = request()->except(['page']); // Keep other filters intact, remove 'page' param if exists
            @endphp

            <div class="d-flex flex-wrap align-items-center justify-content-start mb-3" id="active-filters-container">
                <div class="d-flex flex-wrap gap-2" id="filter-tags">

                    @if (!empty($filters['location']))
                        @php
                            $query = array_merge($filtersQuery, ['location' => null]);
                        @endphp
                        <a href="{{ route('home', $query) }}" class="badge bg-primary text-decoration-none">
                            Location: {{ $filters['location'] }} &times;
                        </a>
                    @endif

                    @if ($filters['adults'] > 1)
                        @php
                            $query = array_merge($filtersQuery, ['adults' => null]);
                        @endphp
                        <a href="{{ route('home', $query) }}" class="badge bg-primary text-decoration-none">
                            Adults: {{ $filters['adults'] }} &times;
                        </a>
                    @endif

                    @if ($filters['children'] > 0)
                        @php
                            $query = array_merge($filtersQuery, ['children' => null]);
                        @endphp
                        <a href="{{ route('home', $query) }}" class="badge bg-primary text-decoration-none">
                            Children: {{ $filters['children'] }} &times;
                        </a>
                    @endif

                    @if (!empty($filters['check_in_date']))
                        @php
                            $query = array_merge($filtersQuery, ['check_in_date' => null]);
                        @endphp
                        <a href="{{ route('home', $query) }}" class="badge bg-primary text-decoration-none">
                            Check-In: {{ $filters['check_in_date'] }} &times;
                        </a>
                    @endif

                    @if (!empty($filters['check_out_date']))
                        @php
                            $query = array_merge($filtersQuery, ['check_out_date' => null]);
                        @endphp
                        <a href="{{ route('home', $query) }}" class="badge bg-primary text-decoration-none">
                            Check-Out: {{ $filters['check_out_date'] }} &times;
                        </a>
                    @endif
                </div>
                <!-- Clear Filters Button -->
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger ms-3" id="clear-filters-btn">Clear All Filters</a>
            </div>
        @endif

        <div class="row g-3">
            @foreach ($homestays as $homestay)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <a href="{{ route('accommodation.detail', ['homestay_id' => $homestay->homestay_id]) }}"
                    class="text-decoration-none text-dark">
                    <div class="card h-100 border-0 shadow-sm rounded">
                        <div class="position-relative">
                            <!-- Carousel -->
                            <div id="carousel{{ $homestay->homestay_id }}" class="carousel slide"
                                data-bs-ride="carousel" data-carousel-id="{{ $homestay->homestay_id }}">
                                <div class="carousel-inner rounded-top">
                                    @foreach ($homestay->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset($image->image_url) }}" class="d-block w-100"
                                            style="height: 200px; object-fit: cover;" alt="Image {{ $index + 1 }}">
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Indicators -->
                                <div id="carousel-indicators-{{ $homestay->homestay_id }}"
                                    class="carousel-indicators position-absolute bottom-0 translate-middle-x mb-2"
                                    style="left: 35%;">
                                    @foreach ($homestay->images as $index => $image)
                                    @if ($index < 5) <button type="button"
                                        data-bs-target="#carousel{{ $homestay->homestay_id }}"
                                        data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                                        aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                                        @endif
                                        @endforeach
                                </div>

                                <!-- Controls -->
                                <button class="carousel-control-prev d-none d-md-flex" type="button"
                                    data-bs-target="#carousel{{ $homestay->homestay_id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next d-none d-md-flex" type="button"
                                    data-bs-target="#carousel{{ $homestay->homestay_id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>

                            <!-- Badge and Heart -->
                            <div
                                class="d-flex justify-content-between align-items-center position-absolute top-0 start-0 w-100 p-2 z-2">
                                <span class="badge bg-light text-dark">⭐ Guest favorite</span>

                                {{-- FAVORITE BUTTON LOGIC --}}
                                @auth {{-- Show button only if user is logged in --}}
                                <button class="btn btn-light btn-sm rounded-circle favourite-toggle-btn"
                                        data-homestay-id="{{ $homestay->homestay_id }}"
                                        data-is-favourited="{{ $homestay->is_favorited ? 'true' : 'false' }}">
                                    <i class="{{ $homestay->is_favorited ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                                </button>
                                @endauth
                                @guest {{-- Optional: Show disabled button or just the badge if not logged in --}}
                                <button class="btn btn-light btn-sm rounded-circle" disabled title="Log in to favorite">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                @endguest
                            </div>
                        </div>

                        <div class="card-body p-2" style="background-color: transparent;">
                            <h6 class="mb-0 fw-bold">{{ $homestay->location_city }}, {{ $homestay->location_state }}
                            </h6>
                            <small class="text-muted d-block text-truncate"
                                style="white-space: nowrap; overflow: hidden;">{{ $homestay->title }}</small>
                            <small class="text-muted">{{ $homestay->max_guests }} guests</small>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mt-1 fw-bold">RM{{ $homestay->price_per_night }} <span
                                        class="fw-normal">night</span></div>
                                <div class="bold">★ {{ $homestay->rating_avg }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Log in to StayBuddy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/login">
                        @csrf
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required />
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control password-input" placeholder="Password"  required />
                            <span class="input-group-text toggle-password3" style="cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <button  type="submit" class="btn btn-success w-100">Log In</button>
                        <hr />
                        <a href="{{ route('google.redirect') }}" class="btn btn-outline-dark w-100 mb-2"><i class="fab fa-google me-2"></i>Continue with Google</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Create your account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/register">
                        @csrf
                        <input type="text" name="name" class="form-control mb-3" style="padding: 6px 12px; border: 1px solid #dee2e6" placeholder="Full Name" required />
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required />
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control password-input" placeholder="Password" required />
                            <span class="input-group-text toggle-password" style="cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password_confirmation" class="form-control password-input" placeholder="Confirm Password" required />
                            <span class="input-group-text toggle-password2" style="cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>                
                        </div>
                        <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a></label>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                        <hr />
                        <a href="{{ route('google.redirect') }}" class="btn btn-outline-dark w-100 mb-2"><i class="fab fa-google me-2"></i>Continue with Google</a>
                    </form> 
                </div>
            </div>
        </div>
    </div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
        intent="WELCOME"
        chat-title="StayBuddy"
        agent-id="a04b4238-6b41-4b51-86ea-477a792d1acd"
        language-code="en">
    </df-messenger>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-carousel-id]').forEach(carousel => {
        const id = carousel.getAttribute('data-carousel-id');
        const indicatorsContainer = document.querySelector(`#carousel-indicators-${id}`);
        const indicators = indicatorsContainer.querySelectorAll('button');
        const totalSlides = carousel.querySelectorAll('.carousel-item').length;
        let currentSlide = 0;

        const updateIndicators = (index) => {
            const groupStart = Math.floor(index / 5) * 5;

            indicators.forEach((btn, i) => {
                const slideIndex = groupStart + i;
                if (slideIndex < totalSlides) {
                    btn.style.display = 'inline-block';
                    btn.setAttribute('data-bs-slide-to', slideIndex);
                    btn.setAttribute('aria-label', `Slide ${slideIndex + 1}`);
                    btn.classList.toggle('active', slideIndex === index);
                    btn.setAttribute('aria-current', slideIndex === index ? 'true' :
                        'false');
                } else {
                    btn.style.display = 'none';
                }
            });
        };

        indicators.forEach(btn => {
            btn.addEventListener('click', () => {
                const slideTo = parseInt(btn.getAttribute('data-bs-slide-to'));
                const bsCarousel = bootstrap.Carousel.getInstance(carousel);
                bsCarousel.to(slideTo);
            });
        });

        carousel.addEventListener('slid.bs.carousel', function(e) {
            currentSlide = e.to;
            updateIndicators(currentSlide);
        });

        updateIndicators(currentSlide);
    });
    const toggleButtons = document.querySelectorAll('.favourite-toggle-btn');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the <a> tag from being clicked
                event.stopPropagation(); // Stop click from propagating to parent <a>

                const homestayId = this.dataset.homestayId;
                const userId = '{{ Auth::id() }}'; // Get the authenticated user's ID
                let isFavourited = this.dataset.isFavourited === 'true'; // Convert string to boolean
                const icon = this.querySelector('i');

                // Visual feedback immediately (optimistic update)
                if (isFavourited) {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    this.dataset.isFavourited = 'false';
                } else {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    this.dataset.isFavourited = 'true';
                }

                // Send AJAX request
                fetch('{{ route('account.favourites.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // <-- ADD THIS LINE
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ homestay_id: homestayId , user_id: userId, action: isFavourited ? 'remove' : 'add' })
                })
                .then(response => {
                    if (!response.ok) {
                        // If error, revert UI and throw for catch block
                        if (isFavourited) { // If it was favorited, meaning we tried to remove
                            icon.classList.remove('fa-regular');
                            icon.classList.add('fa-solid');
                            this.dataset.isFavourited = 'true';
                        } else { // If it was not favorited, meaning we tried to add
                            icon.classList.remove('fa-solid');
                            icon.classList.add('fa-regular');
                            this.dataset.isFavourited = 'false';
                        }
                        return response.json().then(err => { throw new Error(err.message || 'Network response was not ok.'); });
                    }
                    return response.json();
                })
                .then(data => {
                    // UI already updated optimistically, but you could add more logic here
                    // based on data.action if needed (e.g., a toast notification)
                    console.log(data.message);
                })
                .catch(error => {
                    console.error('Error toggling favourite:', error);
                    alert('An error occurred: ' + error.message); // Inform user of error
                });
            });
        });
});
</script>

<!-- authentication -->
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

<!-- password visibility toggle -->
<script>
  document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', function () {
      const input = this.previousElementSibling;
      const icon = this.querySelector('i');
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    });
  });

    document.querySelectorAll('.toggle-password2').forEach(toggle => {
        toggle.addEventListener('click', function () {
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
        });
    });

    document.querySelectorAll('.toggle-password3').forEach(toggle => {
        toggle.addEventListener('click', function () {
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
    // --- Common Dropdown Handling ---
    const searchFields = document.querySelectorAll('.search-field');
    const searchDropdowns = document.querySelectorAll('.search-dropdown');
    let activeDropdown = null; // To keep track of the currently open dropdown

    function showDropdown(field) {
        const targetMenuId = field.dataset.target;
        const targetMenu = document.querySelector(`.${targetMenuId}`);

        if (!targetMenu) return;

        // Hide any currently active dropdown
        if (activeDropdown && activeDropdown !== targetMenu) {
            activeDropdown.style.display = 'none';
        }

        // Position the new dropdown
        const fieldRect = field.getBoundingClientRect();
        const barRect = document.querySelector('.bar').getBoundingClientRect();

        targetMenu.style.top = `${fieldRect.bottom - barRect.top + 10}px`; // 10px gap
        targetMenu.style.left = `${fieldRect.left - barRect.left}px`;
        targetMenu.style.width = `${fieldRect.width}px`; // Match field width

        targetMenu.style.display = 'block';
        activeDropdown = targetMenu;

        // Special handling for date pickers to ensure they refresh
        if (field.classList.contains('check-in') || field.classList.contains('check-out')) {
            // Re-render Flatpickr if it's already initialized, or just ensure it's visible.
            // Flatpickr usually handles visibility on its own after initialization.
            targetMenu.style.left = `${fieldRect.left - barRect.left * 1.2 }px`;
            targetMenu.style.width = `${fieldRect.width *1.2}px`; // Match field width
        }

        // Special handling for guests dropdown
        if (field.classList.contains('guests')) {
            const guestMenu = document.querySelector('.guests-menu');
            guestMenu.style.top = `${fieldRect.bottom - barRect.top + 10}px`;
            guestMenu.style.left = `${fieldRect.left - barRect.left * 1.18}px`;
            guestMenu.style.width = `${fieldRect.width * 1.4}px`; // Match field width
            guestMenu.style.display = 'block';
            activeDropdown = guestMenu; // Update active dropdown to guests menu
        }
    }

    function hideDropdowns() {
        if (activeDropdown) {
            activeDropdown.style.display = 'none';
            activeDropdown = null;
        }
    }

    searchFields.forEach(field => {
        field.addEventListener('click', function (event) {
            event.stopPropagation(); // Prevent document click listener from immediately closing it
            showDropdown(field);
        });
    });

    // Hide dropdowns when clicking anywhere outside the search bar or an open dropdown
    document.addEventListener('click', function(event) {
        const barElement = document.querySelector('.bar');
        const clickedInsideBar = barElement && barElement.contains(event.target);
        const clickedInsideActiveDropdown = activeDropdown && activeDropdown.contains(event.target);

        if (!clickedInsideBar && !clickedInsideActiveDropdown) {
            hideDropdowns();
        }
    });

    // --- Location Dropdown (existing logic) ---
    const locationInput = document.querySelector('.location-input');
    const destinationMenu = document.querySelector('.destination-menu');

    if (locationInput && destinationMenu) {
        document.querySelectorAll('.destination-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                locationInput.value = this.textContent;
                hideDropdowns(); // Use common hide function
            });
        });
    }

    // --- Date Pickers (Flatpickr Integration) ---
    const checkInInput = document.querySelector('.check-in-input');
    const checkOutInput = document.querySelector('.check-out-input');
    const checkInMenu = document.querySelector('.check-in-menu');
    const checkOutMenu = document.querySelector('.check-out-menu');

    let checkInPicker, checkOutPicker; // To store Flatpickr instances

    // Initialize Check-in Flatpickr
    if (checkInInput && checkInMenu) {
        checkInPicker = flatpickr(checkInInput, {
            dateFormat: "Y-m-d",
            appendTo: checkInMenu, // Render calendar inside the custom dropdown div
            inline: true, // Display calendar always
            minDate: "today", // Cannot select past dates
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const nextDay = new Date(selectedDates[0]);
                    nextDay.setDate(nextDay.getDate() + 1); // Add 1 day

                    checkOutPicker.set('minDate', nextDay);

                    // If current check-out is before or same day, clear it
                    if (checkOutInput.value && new Date(checkOutInput.value) <= selectedDates[0]) {
                        checkOutInput.value = '';
                    }
                }
            },

            onOpen: function() {
                // Hide other dropdowns when date picker opens
                searchDropdowns.forEach(dropdown => {
                    if (dropdown !== checkInMenu && dropdown.style.display === 'block') {
                        dropdown.style.display = 'none';
                    }
                });
            },
            onClose: function() {
                // This might close the dropdown too soon if you're not careful.
                // The document click listener handles hiding.
            }
        });
    }

    // Initialize Check-out Flatpickr
    if (checkOutInput && checkOutMenu) {
        checkOutPicker = flatpickr(checkOutInput, {
            dateFormat: "Y-m-d",
            appendTo: checkOutMenu, // Render calendar inside the custom dropdown div
            inline: true, // Display calendar always
            minDate: "today", // Cannot select past dates initially
            onOpen: function() {
                if (checkInInput.value) {
                    const checkInDate = new Date(checkInInput.value);
                    const nextDay = new Date(checkInDate);
                    nextDay.setDate(nextDay.getDate() + 1); // Add 1 day

                    checkOutPicker.set('minDate', nextDay);
                } else {
                    checkOutPicker.set('minDate', "today");
                }

                // Hide other dropdowns
                searchDropdowns.forEach(dropdown => {
                    if (dropdown !== checkOutMenu && dropdown.style.display === 'block') {
                        dropdown.style.display = 'none';
                    }
                });
            },
            onClose: function() {
                // Handled by document click listener
            }
        });
    }

    // --- Guests Dropdown Logic ---
    const guestsInput = document.querySelector('.guests-input');
    const adultsHiddenInput = document.getElementById('adults-hidden-input');
    const childrenHiddenInput = document.getElementById('children-hidden-input');
    const guestCounts = { adults: 1, children: 0 }; // Initial counts

    function updateGuestsDisplay() {
        const totalGuests = guestCounts.adults + guestCounts.children;
        guestsInput.value = `${totalGuests} guest${totalGuests !== 1 ? 's' : ''}`;
        // Update hidden inputs
        adultsHiddenInput.value = guestCounts.adults;
        childrenHiddenInput.value = guestCounts.children;
    }

    document.querySelectorAll('.guest-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent clicks on buttons from closing the dropdown

            const type = this.dataset.type;
            const action = this.dataset.action;
            const countSpan = document.querySelector(`.guest-count[data-type="${type}"]`);

            if (action === 'minus') {
                if (type === 'adults' && guestCounts.adults > 1) { // Adults minimum 1
                    guestCounts.adults--;
                } else if (type === 'children' && guestCounts.children > 0) {
                    guestCounts.children--;
                }
            } else if (action === 'plus') {
                guestCounts[type]++;
            }

            countSpan.textContent = guestCounts[type];
            updateGuestsDisplay();
        });
    });

    // Initial display update
    updateGuestsDisplay();
});
</script>
<script>
    function updateFilterTags(filters) {
        const $filterTags = $('#filter-tags');
        $filterTags.empty(); // Clear existing tags

        Object.entries(filters).forEach(([key, value]) => {
            if (value > 0) {
                const label = key.charAt(0).toUpperCase() + key.slice(1);
                $filterTags.append(`<span class="badge bg-primary">${label}: ${value}</span>`);
            }
        });

        // Show or hide Clear button
        $('#clear-filters-btn').toggle(Object.values(filters).some(val => val > 0));
    }

    // Example usage
    const filters = { adults: 2, children: 1 };
    updateFilterTags(filters);

    // Clear button functionality
    $('#clear-filters-btn').click(function () {
        // Reset filters
        filters.adults = 0;
        filters.children = 0;

        // Update UI accordingly (including your card filtering logic)
        updateFilterTags(filters);

        // You should also update the counts or rerun the filter function here
    });
</script>
</html>