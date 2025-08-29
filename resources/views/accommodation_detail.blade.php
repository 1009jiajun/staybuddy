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
    <script>
        const disabledDates = @json($disabledDates);
        const nextAvailable = @json($nextAvailable);
    </script>

    <style>
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
        padding: 5px 10px;
        display: flex;
        align-items: center;
        gap: 10px;
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

    .bar div {
        border-radius: inherit;
        padding: 0.8rem 1.5rem;
        transition: background 250ms ease;
    }

    .bar div:hover {
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

    h1.title {
        font-size: 1.7rem;
        font-weight: 500;
    }

    .custom-banner-text {
        font-size: 1.1rem;
        font-weight: 400;
    }

    /* Slide from bottom on small screens */
    @media (max-width: 576px) {
        .modal.fade .modal-dialog {
            transform: translateY(100%);
            transition: transform 0.3s ease-out;
        }

        .modal.fade.show .modal-dialog {
            transform: translateY(0);
        }
    }

    @media (min-width: 768px) {
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    }

    .icon-light {
        opacity: 0.8;
    }

    .profile-avatar {
        width: 40px !important;
        /* Fixed width */
        height: 40px !important;
        /* Fixed height */
        min-width: 40px !important;
        /* Prevent shrinkage */
        border-radius: 50%;
        /* Perfect circle */
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 18px;
        /* Optimal font size */
        flex-shrink: 0;
        /* Prevent flex shrinkage */
    }

    .modal-content.img {
        background-color: rgba(0, 0, 0, 1);
    }

    .modal-content.img .modal-body {
        overflow-y: hidden;
    }

    .modal-body img {
        max-height: 80vh;
        object-fit: contain;
    }

    .dropdown-item:hover {
        background-color:rgb(225, 250, 217);
    }

    .dropdown-item{
        margin: 6px 0 6px 0 !important;
        width: 100%;
        padding: 5px 10px;
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
    </style>
</head>

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content img bg-transparent border-0">
            <div class="modal-body p-0">
                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach (json_decode($homestay->images) as $index => $image)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset($image->image_url) }}" class="d-block w-100">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


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
    <div class="container border-bottom py-4">
        <div class="d-flex justify-content-between align-items-center">

            <!-- Left: Logo -->
            <a href="{{ url('/') }}" class="text-decoration-none text-success">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/logo/logo-icon.png') }}" alt="Logo" height="48">
                    <div class="ms-2">
                        <span class="fw-bold fs-5 text-success d-block">StayBuddy</span>
                        <p class="text-muted mb-0 small">Homestay reservation</p>
                    </div>
                </div>
            </a>

            <!-- Middle: Search Bar -->

            <div class="bar">
                <div class="location">
                    <h6 class="text-muted">Where</h6>
                    <input type="text" placeholder="Search destinations">
                </div>
                <div class="check-in">
                    <h6 class="text-muted">Check in</h6>
                    <input type="text" placeholder="Add dates">
                </div>
                <div class="check-out">
                    <h6 class="text-muted">Check out</h6>
                    <input type="text" placeholder="Add dates">
                </div>
                <div class="guests">
                    <h6 class="text-muted">Who</h6>
                    <input type="text" placeholder="Add guests">
                    <span class="ms-3 search-icon">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>

            <!-- Right: Profile Section -->
            <div class="d-flex align-items-center gap-3">
                <div class="profile-btn">
                    <i class="fas fa-bars"></i>
                        @auth
                        @php
                            $image = auth()->user()->profile_image;
                            $isUrl = filter_var($image, FILTER_VALIDATE_URL);
                        @endphp

                        @if ($image)
                            <img src="{{ $isUrl ? $image : asset($image) }}" 
                                alt="Profile Image" 
                                class="rounded-circle" 
                                style="width: 35px; height: 35px; object-fit: cover;">
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
            <!-- Dropdown Menu for Profile -->
            <div class="profile-menu position-absolute bg-white border rounded shadow-md p-2" style="display: none; top: 75px; right: 2rem; z-index: 1000;width: 200px;">
                <div class="visitor-menu">
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#registerModal" style="margin: 5px;">Register</a>
                    <div class="dropdown-divider" style="border-top: 1px solid #dee2e6;"></div>
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal" style="margin: 5px;">Login</a>
                    <hr style="margin: 5px 0; border-top: 1px solid rgb(156, 158, 159);">
                    <a href="/about" class="dropdown-item">About Us</a>
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
    <div class="container py-4">
        <!-- {{-- Listing Header --}} -->
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <h1 class="title">{{ $homestay->title }}</h1>
            <div class="d-flex gap-1 align-items-center mt-2">
                 {{-- FAVORITE BUTTON LOGIC --}}
                @auth {{-- Show button only if user is logged in --}}
                <button class="btn btn-success btn-sm rounded-pill favourite-toggle-btn d-inline-flex align-items-center gap-1"
                        data-homestay-id="{{ $homestay->homestay_id }}"
                        data-is-favourited="{{ $homestay->is_favorited ? 'true' : 'false' }}">
                    <i class="{{ $homestay->is_favorited ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                    <span class="favourite-text">{{ $homestay->is_favorited ? 'Unsave' : 'Save' }}</span>
                </button>
                @endauth
                @guest {{-- Optional: Show disabled button or just the badge if not logged in --}}
                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal" style="margin: 0px;padding:0px;text-decoration: none; color: inherit;background: transparent;">
                    <button class="btn btn-success btn-sm rounded-pill" title="Log in to favorite">
                        <span><i class="fa-regular fa-heart"></i></span> Save
                    </button>
                </a>
                @endguest
               <!-- <button class="btn btn-outline-success btn-sm rounded-pill d-inline-flex align-items-center gap-1">
                    <i class="fas fa-share-alt"></i> Share
                </button> -->

            </div>
        </div>

        <!-- {{-- Image Collection --}} -->
        <div class="row">
            <div class="col-6">
                <img src="{{ asset($homestay->images[0]->image_url) }}" class="d-block w-100 rounded"
                    style="height: 417px; object-fit: cover;" alt="Homestay Image {{ 0 }}" data-bs-toggle="modal"
                    data-bs-target="#imageModal" data-bs-slide-to="{{ 0 }}" style="cursor: pointer;">

            </div>
            <div class="col-6">
                <div class="row">
                    @foreach (json_decode($homestay->images) as $index => $image)
                    @if ($index > 0 && $index < 5) <div class="col-6 mb-3 position-relative">
                        <img src="{{ asset($image->image_url) }}" class="d-block w-100 rounded"
                            style="height: 200px; object-fit: cover;" alt="Homestay Image {{ $index + 1 }}"
                            data-bs-toggle="modal" data-bs-target="#imageModal" data-bs-slide-to="{{ $index }}"
                            style="cursor: pointer;">
                        @if ($index == 4)
                        <a href="#showAllPhotos"
                            class="btn btn-light border shadow position-absolute d-flex align-items-center gap-2"
                            style="bottom: 10px; right: 20px; z-index: 10;" data-bs-toggle="modal"
                            data-bs-target="#imageModal" data-bs-slide-to="0">
                            <i class="fas fa-th-large"></i> Show all photos
                        </a>
                        @endif
                </div>
                @endif
                @endforeach
            </div>
        </div>

    </div>

    <div class="row">
        <!-- {{-- Left Column --}} -->
        <div class="col-lg-8">
            <!-- {{-- Amenities --}} -->
            <div class="mb-4 mt-2">
                <h4 class="fw-semibold">{{ $homestay->room_type }} in {{ $homestay->location_state }}</h4>
                <ul class="list-unstyled d-flex flex-wrap gap-1">
                    <li>{{ $homestay->max_guests }} guests -</li>
                    <li>{{ $homestay->bedrooms }} bedrooms -</li>
                    <li>{{ $homestay->beds }} beds -</li>
                    <li>{{ $homestay->bathrooms }} bathrooms</li>
                </ul>
            </div>

            <div class="border rounded-3 p-3 d-flex align-items-center flex-nowrap justify-content-evenly"
                style="max-width: 800px; max-height: 100px;">
                <div class="d-flex align-items-center me-3 mb-2 mb-md-0">
                    <div class="me-2" style="width: auto; height: 60px;"><svg viewBox="0 0 20 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg" height="60">
                            <g clip-path="url(#clip0_5880_37773)">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.4895 25.417L14.8276 24.4547L16.5303 23.6492L17.1923 24.6116L16.3409 25.0143L17.1923 24.6116C18.6638 26.751 17.9509 29.3868 15.5999 30.4989C14.8548 30.8513 14.0005 31.0196 13.1221 30.987L12.8044 30.9752L12.7297 29.2305L13.0474 29.2423C13.5744 29.2618 14.0871 29.1608 14.5341 28.9494C15.9447 28.2821 16.3725 26.7007 15.4895 25.417Z"
                                    fill="#222222"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.32441 10.235C10.0819 8.96204 10.9247 7.4878 10.853 5.81232C10.7813 4.13685 9.80929 2.59524 7.93708 1.18749C6.17964 2.46049 5.33678 3.93473 5.40851 5.6102C5.48024 7.28568 6.45221 8.82729 8.32441 10.235Z"
                                    fill="#F7F7F7"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.19425 0.489275C7.55718 0.226387 8.10753 0.246818 8.49416 0.537533C10.5385 2.07473 11.7071 3.84975 11.7923 5.84026C11.8775 7.83076 10.8574 9.52453 8.93841 10.9146C8.57548 11.1775 8.02513 11.157 7.6385 10.8663C5.59415 9.32914 4.4256 7.55411 4.34039 5.56361C4.25517 3.57311 5.27521 1.87933 7.19425 0.489275ZM7.92362 2.3684C6.77985 3.38355 6.29788 4.47199 6.3478 5.63813C6.39772 6.80428 6.97457 7.93203 8.20904 9.03547C9.35281 8.02032 9.83478 6.93187 9.78486 5.76573C9.73493 4.59959 9.15809 3.47184 7.92362 2.3684Z"
                                    fill="#222222"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.6806 24.0529C14.1314 22.353 12.4326 21.4688 10.5842 21.4001C8.73575 21.3315 7.10737 22.0923 5.69905 23.6824C7.24822 25.3823 8.94702 26.2666 10.7955 26.3352C12.6439 26.4038 14.2723 25.6431 15.6806 24.0529Z"
                                    fill="#F7F7F7"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.90529 24.1787C4.60807 23.8526 4.58911 23.4097 4.8593 23.1046C6.38985 21.3765 8.27538 20.4331 10.521 20.5164C12.7666 20.5998 14.7391 21.6864 16.4227 23.5339C16.7199 23.86 16.7389 24.303 16.4687 24.608C14.9381 26.3361 13.0526 27.2795 10.807 27.1962C8.56134 27.1128 6.5889 26.0262 4.90529 24.1787ZM6.98781 23.7198C8.22307 24.8808 9.46778 25.4045 10.7323 25.4515C11.9968 25.4984 13.2005 25.0656 14.3402 23.9928C13.1049 22.8318 11.8602 22.3081 10.5957 22.2611C9.3312 22.2142 8.12744 22.6471 6.98781 23.7198Z"
                                    fill="#222222"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.6766 20.7043C10.2137 18.5957 9.16392 17.0928 7.52727 16.1956C5.89062 15.2984 3.99442 15.1864 1.83867 15.8596C2.30157 17.9683 3.35135 19.4712 4.988 20.3684C6.62465 21.2656 8.52085 21.3775 10.6766 20.7043Z"
                                    fill="#F7F7F7"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.791956 15.9443C0.703053 15.5393 0.94431 15.1569 1.37329 15.023C3.7337 14.2859 5.9714 14.3695 7.95247 15.4554C9.92449 16.5364 11.1013 18.3139 11.6022 20.5956C11.6911 21.0006 11.4499 21.3829 11.0209 21.5169C8.66048 22.254 6.42277 22.1704 4.4417 21.0844C2.46969 20.0034 1.29285 18.226 0.791956 15.9443ZM2.95349 16.4656C3.43375 17.9951 4.27991 19.007 5.41321 19.6282C6.5306 20.2407 7.84423 20.4286 9.44069 20.0743C8.96043 18.5448 8.11427 17.5329 6.98097 16.9116C5.86358 16.2991 4.54995 16.1113 2.95349 16.4656Z"
                                    fill="#222222"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.90911 15.6267C8.65652 13.6743 8.53705 11.9555 7.55072 10.4702C6.56438 8.98484 4.90844 8.03014 2.58291 7.60605C1.8355 9.55846 1.95497 11.2773 2.9413 12.7626C3.92764 14.2479 5.58357 15.2026 7.90911 15.6267Z"
                                    fill="#F7F7F7"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M1.66037 7.28295C1.80927 6.89397 2.26578 6.67525 2.74598 6.76282C5.29848 7.22831 7.26368 8.31371 8.44396 10.0911C9.61955 11.8614 9.70866 13.854 8.89805 15.9715C8.74915 16.3605 8.29264 16.5792 7.81244 16.4916C5.25994 16.0261 3.29474 14.9407 2.11446 13.1634C0.938866 11.393 0.849755 9.40048 1.66037 7.28295ZM3.3385 8.6613C2.94038 10.1267 3.14588 11.3465 3.83454 12.3835C4.51397 13.4067 5.60091 14.1584 7.21992 14.5931C7.61804 13.1278 7.41254 11.9079 6.72388 10.8709C6.04445 9.84774 4.95751 9.09607 3.3385 8.6613Z"
                                    fill="#222222"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_5880_37773">
                                    <rect width="18.8235" height="32" fill="white"
                                        transform="translate(0.453125 0.000488281)"></rect>
                                </clipPath>
                            </defs>
                        </svg></div>
                    <div class="text-center me-2">
                        <div class="fw-bold mb-0">Guest</div>
                        <div class="fw-bold mb-0">favorite</div>
                    </div>
                    <div style="width: auto; height: 60px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 32"
                            fill="none" height="60">
                            <g clip-path="url(#clip0_5880_37786)">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.06516 25.417L4.72713 24.4547L3.02437 23.6492L2.3624 24.6116L3.21378 25.0143L2.3624 24.6116C0.890857 26.751 1.60381 29.3868 3.95483 30.4989C4.69986 30.8513 5.55423 31.0196 6.43257 30.987L6.75025 30.9752L6.82494 29.2305L6.50726 29.2423C5.98026 29.2618 5.46764 29.1608 5.02062 28.9494C3.61001 28.2821 3.18223 26.7007 4.06516 25.417Z"
                                    fill="#222222"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.2303 10.235C9.47283 8.96204 8.62998 7.4878 8.70171 5.81232C8.77344 4.13685 9.7454 2.59524 11.6176 1.18749C13.375 2.46049 14.2179 3.93473 14.1462 5.6102C14.0744 7.28568 13.1025 8.82729 11.2303 10.235Z"
                                    fill="#F7F7F7"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12.3604 0.489275C11.9975 0.226387 11.4472 0.246818 11.0605 0.537533C9.01618 2.07473 7.84763 3.84975 7.76242 5.84026C7.6772 7.83076 8.69724 9.52453 10.6163 10.9146C10.9792 11.1775 11.5296 11.157 11.9162 10.8663C13.9605 9.32914 15.1291 7.55411 15.2143 5.56361C15.2995 3.57311 14.2795 1.87933 12.3604 0.489275ZM11.6311 2.3684C12.7748 3.38355 13.2568 4.47199 13.2069 5.63813C13.157 6.80428 12.5801 7.93203 11.3456 9.03547C10.2019 8.02032 9.71991 6.93187 9.76983 5.76573C9.81975 4.59959 10.3966 3.47184 11.6311 2.3684Z"
                                    fill="#222222"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.87411 24.0529C5.42328 22.353 7.12208 21.4688 8.97051 21.4001C10.8189 21.3315 12.4473 22.0923 13.8556 23.6824C12.3065 25.3823 10.6077 26.2666 8.75924 26.3352C6.9108 26.4038 5.28243 25.6431 3.87411 24.0529Z"
                                    fill="#F7F7F7"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M14.6494 24.1787C14.9466 23.8526 14.9656 23.4097 14.6954 23.1046C13.1648 21.3765 11.2793 20.4331 9.03368 20.5164C6.78805 20.5998 4.81561 21.6864 3.13199 23.5339C2.83478 23.86 2.81582 24.303 3.08601 24.608C4.61655 26.3361 6.50208 27.2795 8.74771 27.1962C10.9933 27.1128 12.9658 26.0262 14.6494 24.1787ZM12.5669 23.7198C11.3316 24.8808 10.0869 25.4045 8.82241 25.4515C7.55791 25.4984 6.35415 25.0656 5.21452 23.9928C6.44977 22.8318 7.69449 22.3081 8.95899 22.2611C10.2235 22.2142 11.4272 22.6471 12.5669 23.7198Z"
                                    fill="#222222"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.87809 20.7043C9.34099 18.5957 10.3908 17.0928 12.0274 16.1956C13.6641 15.2984 15.5603 15.1864 17.716 15.8596C17.2531 17.9683 16.2033 19.4712 14.5667 20.3684C12.93 21.2656 11.0338 21.3775 8.87809 20.7043Z"
                                    fill="#F7F7F7"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M18.7627 15.9443C18.8516 15.5393 18.6104 15.1569 18.1814 15.023C15.821 14.2859 13.5833 14.3695 11.6022 15.4554C9.6302 16.5364 8.45336 18.3139 7.95247 20.5956C7.86356 21.0006 8.10482 21.3829 8.5338 21.5169C10.8942 22.254 13.1319 22.1704 15.113 21.0844C17.085 20.0034 18.2618 18.226 18.7627 15.9443ZM16.6012 16.4656C16.1209 17.9951 15.2748 19.007 14.1415 19.6282C13.0241 20.2407 11.7105 20.4286 10.114 20.0743C10.5943 18.5448 11.4404 17.5329 12.5737 16.9116C13.6911 16.2991 15.0047 16.1113 16.6012 16.4656Z"
                                    fill="#222222"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.6456 15.6267C10.8982 13.6743 11.0176 11.9555 12.004 10.4702C12.9903 8.98484 14.6462 8.03014 16.9718 7.60605C17.7192 9.55846 17.5997 11.2773 16.6134 12.7626C15.6271 14.2479 13.9711 15.2026 11.6456 15.6267Z"
                                    fill="#F7F7F7"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M17.8943 7.28295C17.7454 6.89397 17.2889 6.67525 16.8087 6.76282C14.2562 7.22831 12.291 8.31371 11.1107 10.0911C9.93513 11.8614 9.84602 13.854 10.6566 15.9715C10.8055 16.3605 11.262 16.5792 11.7422 16.4916C14.2947 16.0261 16.26 14.9407 17.4402 13.1634C18.6158 11.393 18.7049 9.40048 17.8943 7.28295ZM16.2162 8.6613C16.6143 10.1267 16.4088 11.3465 15.7201 12.3835C15.0407 13.4067 13.9538 14.1584 12.3348 14.5931C11.9366 13.1278 12.1421 11.9079 12.8308 10.8709C13.5102 9.84774 14.5972 9.09607 16.2162 8.6613Z"
                                    fill="#222222"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_5880_37786">
                                    <rect width="18.8235" height="32" fill="white"
                                        transform="matrix(-1 0 0 1 19.1016 0.000488281)"></rect>
                                </clipPath>
                            </defs>
                        </svg></div>
                </div>

                <div class="d-flex mb-2 mb-md-0" style="width: 300px;">
                    <div class="fw-bold mb-0">One of the most loved homes on StayBuddy, according to guests</div>
                </div>


                <div class="d-flex align-items-center gap-3">
                    <div class="text-center">
                        <div class="fw-bold custom-banner-text">4.91</div>
                        <div class="text-warning small">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="d-flex align-items-center gap-3">
                    <div class="text-center">
                        <div class="fw-bold custom-banner-text">5</div>
                        <div class="fw-bold custom-banner-text">Reviews</div>
                    </div>
                </div>
            </div>


            <hr class="my-4">

            <!-- Description -->
            <div class="mb-4">
                <h4 class="fw-semibold">About this place</h4>

                <p id="description" class="d-inline">
                    {!! nl2br(e(Str::words($homestay->description, 70, '...'))) !!}
                </p>

                <span id="more-text" class="d-none">{!! nl2br(e($homestay->description)) !!}</span>

                <br>
                <!-- Show More Link -->
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#descriptionModal"
                    class="text-decoration-none text-success d-flex align-items-center mt-2">
                    <p class="text-decoration-underline mb-0">Show more</p>
                    <i class="fas fa-chevron-right ms-2 mt-1"></i>
                </a>
            </div>

            <hr class="my-4">

            <!-- {{-- House Offers --}} -->
            <div class="mb-4">
                <h4 class="fw-semibold">What this place offers</h4>
                <ul class="list-unstyled row">
                    @foreach ($amenities->take(8) as $amenity)
                    <li class="col-6 mb-2 d-flex align-items-center">
                        <i class="{{ $amenity->icon }} me-3 fs-5 icon-light"></i>
                        <span class="fs-6 icon-light">{{ $amenity->amenity }}</span>
                    </li>
                    @endforeach
                </ul>

                @if ($amenities->count() > 8)
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                    data-bs-target="#allAmenitiesModal">
                    Show all amenities
                </button>
                @endif
            </div>

            <hr class="my-4">

            <!-- House rules -->
            <div class="mb-4">
                <h4 class="fw-semibold">House rules</h4>

                <p id="description" class="d-inline">
                    {!! nl2br(e(Str::words($homestay->rules, 70, '...'))) !!}
                </p>

                <span id="more-text" class="d-none">{!! nl2br(e($homestay->rules)) !!}</span>

                <br>
                <!-- Show More Link -->
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#rulesModal"
                    class="text-decoration-none text-success d-flex align-items-center mt-2">
                    <p class="text-decoration-underline mb-0">Show more</p>
                    <i class="fas fa-chevron-right ms-2 mt-1"></i>
                </a>
            </div>
        </div>

        <!-- {{-- Right Column (Booking) --}} -->
        <div class="col-lg-4 mt-4">
            <form method="GET" action="{{ route('accommodation.book', ['homestay_id' => $homestay->homestay_id]) }}">
                @csrf
                <div class="p-4 shadow rounded bg-white">
                    <h5 id="price-summary">
                        <strong class="text-decoration-underline">
                            RM{{ number_format($homestay->price_per_night, 2) }}
                        </strong> for 1 night
                    </h5>

                    @php
                        $checkin = \Carbon\Carbon::parse($nextAvailable)->format('Y-m-d');
                        $checkout = \Carbon\Carbon::parse($nextAvailable)->addDay()->format('Y-m-d');
                    @endphp

                    <div class="row g-0 border rounded mb-3 mt-3">
                        <div class="col-6 p-2 border-end">
                            <label class="text-uppercase small fw-bold">Check-in</label>
                            <input type="date" name="checkin" id="checkin" class="form-control border-0"
                                value="{{ $checkin  }}" min="{{ $checkin  }}" required>
                        </div>
                        <div class="col-6 p-2">
                            <label class="text-uppercase small fw-bold">Checkout</label>
                            <input type="date" name="checkout" id="checkout" class="form-control border-0"
                                value="{{ $checkout }}" min="{{ $checkout }}" required>
                        </div>
                        <div class="col-12 p-2 border-top">
                            <label class="text-uppercase small fw-bold">Guests</label>
                            <select name="guests" class="form-select border-0">
                                @for ($i = 1; $i <= $homestay->max_guests; $i++)
                                    <option value="{{ $i }}">{{ $i }} guest{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="homestay_id" value="{{ $homestay->homestay_id }}">
                    <button type="submit" class="btn btn-success w-100 mb-2">Reserve</button>
                    <div class="text-center text-muted small">You won't be charged yet</div>
                </div>
            </form>
        </div>

        <hr class="my-4">

        <div class="col-12 mt-4">
            <!-- {{-- Reviews --}} -->
            <div class="mb-2 align-items-center d-flex justify-content-center">
                <img src="{{ asset('assets/logo/rating-left.png') }}" alt="Review Icon" width="50" height="90"
                    class="me-2">
                <h2 class="fw-semibold" style="font-size:4rem;">{{$homestay->rating_avg}}</h2>
                <img src="{{ asset('assets/logo/rating-right.png') }}" alt="Review Icon" width="50" height="90"
                    class="ms-2">
            </div>
            <div class="mb-1 align-items-center d-flex justify-content-center">
                <h4 class="fw-semibold">Guest favourite</h4>
            </div>

            <div class="align-items-center d-flex justify-content-center" style="margin: 0 37.5%; max-width: 25%;">
                <h6 class="text-center">The home is rated {{ $homestay->rating_avg }} out of 5 based on ratings,
                    reviews, and reliability</h6>
            </div>

            <hr class="my-4">

            <div class="row">
                @foreach ($homestay->reviews->take(4) as $review)
                @php
                $initial = strtoupper(substr($review->user_name, 0, 1));
                $colors = [
                '#FF5733', '#33FF57', '#3357FF', '#F033FF', '#FF33F0',
                '#33FFF0', '#FF6B35', '#8F3AFF', '#2EC4B6', '#FF3366',
                '#44CCFF', '#FF9F1C', '#8A2BE2', '#20B2AA', '#FF6347',
                '#9370DB', '#00CED1', '#FF8C00', '#9932CC', '#00BFFF'
                ];
                $bgColor = $colors[array_rand($colors)];
                @endphp

                <div class="col-md-6 mb-3">
                    <div class="pb-2 h-100 d-flex align-items-start">
                        <!-- Fixed Size Avatar -->
                        <div class="profile-avatar me-3" style="background-color: {{ $bgColor }}">
                            {{ $initial }}
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ $review->user_name }}</strong>
                                <small class="text-muted">Reviewed on {{ $review->review_date }}</small>
                            </div>

                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++) @if($i <=$review->
                                    rating)
                                    <i class="fas fa-star text-dark fa-xs"></i> <!-- Filled star -->
                                    @else
                                    <i class="far fa-star text-dark fa-xs"></i> <!-- Outlined star -->
                                    @endif
                                    @endfor
                            </div>

                            <p class="mb-0">{{ $review->review_text }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($homestay->reviews->count() > 4)
            <div class="mb-4">
                <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal"
                    data-bs-target="#reviewsModal">
                    Show All Reviews ({{ $homestay->reviews->count() }})
                </button>
            </div>

            <hr class="my-4">

            <!-- Reviews Modal -->
            <div class="modal fade" id="reviewsModal" tabindex="-1" aria-labelledby="reviewsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reviewsModalLabel">All Reviews</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach ($homestay->reviews as $review)
                                @php
                                $initial = strtoupper(substr($review->user_name, 0, 1));
                                $colors = [
                                '#FF5733', '#33FF57', '#3357FF', '#F033FF', '#FF33F0',
                                '#33FFF0', '#FF6B35', '#8F3AFF', '#2EC4B6', '#FF3366',
                                '#44CCFF', '#FF9F1C', '#8A2BE2', '#20B2AA', '#FF6347',
                                '#9370DB', '#00CED1', '#FF8C00', '#9932CC', '#00BFFF'
                                ];
                                $bgColor = $colors[array_rand($colors)];
                                @endphp

                                <div class="col-md-6 mb-3">
                                    <div class=" pb-2 h-100 d-flex align-items-start">
                                        <!-- Fixed Size Avatar -->
                                        <div class="profile-avatar me-3" style="background-color: {{ $bgColor }}">
                                            {{ $initial }}
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $review->user_name }}</strong>
                                                <small class="text-muted">Reviewed on
                                                    {{ $review->review_date }}</small>
                                            </div>

                                            <div class="mb-2">
                                                @for($i = 1; $i <= 5; $i++) @if($i <=$review->
                                                    rating)
                                                    <i class="fas fa-star text-dark fa-xs"></i> <!-- Filled star -->
                                                    @else
                                                    <i class="far fa-star text-dark fa-xs"></i> <!-- Outlined star -->
                                                    @endif
                                                    @endfor
                                            </div>

                                            <p class="mb-0">{{ $review->review_text }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- {{-- Map --}} -->
            <div class="mb-4 mt-2">
                <h4 class="fw-semibold">Location</h4>
                <h6 class="fw-semibold mb-4">{{ $homestay->location_state }}, {{ $homestay->location_state }},
                    {{ $homestay->location_country }}</h6>
                <div id="map" style="height: 600px; width: 100%;" class="rounded" data-lat="{{ $homestay->latitude }}"
                    data-lng="{{ $homestay->longitude }}"></div>
            </div>

            <hr class="my-4">

            <!-- {{-- Check-in info--}} -->
            <div class="mb-4">
                <h4 class="fw-semibold">Check-in & Check-out</h4>
                <p class="text-muted mb-0">Check-in time is {{ $homestay->check_in_time }}, Check-out time is {{ $homestay->check_out_time }}</p>
                <p class="text-muted mb-0">Check-in is done through a self-check-in process. You will receive the check-in instructions 24 hours before your arrival.</p>
            </div>
        </div>
    </div>
    </div>
    

    <!-- Modal -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down modal-lg">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">About this place</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! nl2br(e($homestay->description)) !!}
                </div>
            </div>
        </div>
    </div>

    <!--Modal for house rules-->
    <div class="modal fade" id="rulesModal" tabindex="-1" aria-labelledby="rulesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down modal-lg">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header">
                    <h5 class="modal-title" id="rulesModalLabel">House Rules</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! nl2br(e($homestay->rules)) !!}
                </div>
            </div>
        </div>
    </div>


    <!-- All Amenities Modal -->
    <div class="modal fade" id="allAmenitiesModal" tabindex="-1" aria-labelledby="allAmenitiesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header">
                    <h5 class="modal-title" id="allAmenitiesModalLabel">All Amenities</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($groupedAmenities as $category => $items)
                    <h6 class="fw-bold mt-3">{{ $category }}</h6>
                    <ul class="list-unstyled row">
                        @foreach ($items as $amenity)
                        <li class="col-6 mb-3 d-flex align-items-center">
                            <i class="{{ $amenity->icon }} me-2 fs-5 icon-light"></i>
                            <span class="fs-6 icon-light">{{ $amenity->amenity }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endforeach
                </div>
            </div>
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

<!-- Your map function script first -->
<script>
function initMap() {
    const mapDiv = document.getElementById("map");
    const lat = parseFloat(mapDiv.dataset.lat);
    const lng = parseFloat(mapDiv.dataset.lng);
    const location = {
        lat,
        lng
    };

    const map = new google.maps.Map(mapDiv, {
        zoom: 14,
        center: location,
    });
    new google.maps.Marker({
        position: location,
        map: map,
    });
}
</script>


<!-- Google Maps API loader -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrG4Wj-o0TWxo2Iozdjwj7Tm_mdY6V2Z8&callback=initMap">
</script>

<!-- Include Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#checkin", {
        minDate: "today",
        disable: disabledDates,
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            // Optional: Automatically set checkout date to +1
            const checkoutInput = document.getElementById("checkout");
            const tomorrow = new Date(selectedDates[0]);
            tomorrow.setDate(tomorrow.getDate() + 1);
            checkoutInput._flatpickr.setDate(tomorrow);
        }
    });

    flatpickr("#checkout", {
        minDate: new Date().fp_incr(1),
        disable: disabledDates,
        dateFormat: "Y-m-d"
    });
</script>

<script>
document.querySelectorAll('[data-bs-slide-to]').forEach(img => {
    img.addEventListener('click', function() {
        const slideIndex = this.getAttribute('data-bs-slide-to');
        const carousel = bootstrap.Carousel.getOrCreateInstance(document.getElementById(
            'imageCarousel'));
        carousel.to(parseInt(slideIndex));
    });
});
</script>

<!-- Update the price summary when check-in or check-out date changes -->
 <!-- jQuery must come first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
$(document).ready(function () {
    const pricePerNight = {{ $homestay->price_per_night }};

    function updatePrice() {
        const checkin = $('#checkin').val();
        const checkout = $('#checkout').val();

        if (!checkin || !checkout) return;

        const checkinDate = new Date(checkin);
        const checkoutDate = new Date(checkout);

        if (isNaN(checkinDate.getTime()) || isNaN(checkoutDate.getTime())) return;

        if (checkoutDate > checkinDate) {
            const timeDiff = checkoutDate - checkinDate;
            const nights = timeDiff / (1000 * 60 * 60 * 24);
            const total = nights * pricePerNight;

            $('#price-summary').html(
                `<strong class="text-decoration-underline">RM${total.toFixed(2)}</strong> for ${nights} night${nights > 1 ? 's' : ''}`
            );
        } else {
            $('#price-summary').html(
                `<span class="text-danger">Invalid dates selected</span>`
            );
        }
    }

    $('#checkin').on('change', function () {
        const checkinVal = $(this).val();
        if (checkinVal) {
            $('#checkout').attr('min', checkinVal);
        }
        updatePrice();
    });

    $('#checkout').on('change', updatePrice);

    updatePrice(); // initial
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

        const toggleButtons = document.querySelectorAll('.favourite-toggle-btn');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the <a> tag from being clicked
                event.stopPropagation(); // Stop click from propagating to parent <a>

                const homestayId = this.dataset.homestayId;
                const userId = '{{ Auth::id() }}'; // Get the authenticated user's ID
                let isFavourited = this.dataset.isFavourited === 'true'; // Convert string to boolean
                const icon = this.querySelector('i');
                const text = this.querySelector('.favourite-text');

                // Visual feedback immediately (optimistic update)
                if (isFavourited) {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    this.dataset.isFavourited = 'false';
                    text.textContent = 'Save';
                } else {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    this.dataset.isFavourited = 'true';
                    text.textContent = 'Unsave';
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
                            text.textContent = 'Unsave';
                        } else { // If it was not favorited, meaning we tried to add
                            icon.classList.remove('fa-solid');
                            icon.classList.add('fa-regular');
                            this.dataset.isFavourited = 'false';
                            text.textContent = 'Save';
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
</script>

</html>