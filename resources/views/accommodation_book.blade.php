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
    <style>

    </style>
</head>

<body>
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
        </div>
    </div>
    <div class="container mt-4"  style="max-width: 1120px;">
        <div class="card p-4 shadow-sm mb-4">
            <div class="d-flex mb-3">
                <img src="{{ asset($homestay->images[0]->image_url) ?? 'placeholder.jpg' }}" class="rounded me-3" style="width: 100px; height: 100px; object-fit: cover;" alt="Homestay Image">
                <div>
                    <h5 class="mb-1">{{ $homestay->title }}</h5>
                    <p class="mb-0">{{ $homestay->room_type }} in {{ $homestay->location_city }}, {{ $homestay->location_state }}</p>
                    <p class="mb-0">⭐ {{ number_format($homestay->reviews->avg('rating'), 2) ?? 'no rating yet' }} ({{ $homestay->reviews->count() }}) ratings</p>
                    <p class="mb-0">Superhost by StayBuddy</p>
                </div>
            </div>

            <hr>

            <h5 class="fw-bold">Your trip</h5>
            <div class="d-flex justify-content-between mb-2">
                <div>
                    <strong>Dates</strong><br>
                    <p class="mb-0">Check-in: {{ \Carbon\Carbon::parse($checkin)->format('d M Y') }}</p>
                    <p class="mb-0">Checkout: {{ \Carbon\Carbon::parse($checkout)->format('d M Y') }}</p>
                </div>
                <a href="#" class="text-decoration-none">Edit</a>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <strong>Guests</strong><br>
                    {{ $guests > 1 ? $guests . ' guests' : $guests . ' guest' }}<br>
                </div>
                <a href="#" class="text-decoration-none">Edit</a>
            </div>

            <hr>

            <h6 class="fw-bold">Your total</h6>
            <div class="d-flex justify-content-between">
                <div>RM{{ number_format($homestay->price_per_night, 2) }} x {{ $nights > 1 ? $nights . ' nights' : $nights . ' night' }}</div>
                <div>RM{{ number_format($homestay->price_per_night * $nights, 2) }}</div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <div>Cleaning fee</div>
                <div>RM {{ number_format($homestay->cleaning_fee, 2) }}</div>
            </div>
            <hr>
            <div class="d-flex justify-content-between fw-bold mb-3">
                <div>Total (MYR)</div>
                <div>RM{{ number_format(($homestay->price_per_night * $nights) + $homestay->cleaning_fee, 2) }}</div>
            </div>

            <hr>
            @auth
                <form action="{{ route('accommodation.book.submit', ['homestay_id' => $homestay->homestay_id]) }}" method="POST">
                    @csrf
                    <!-- <input type="hidden" name="check_in" value="{{ $checkin }}">
                    <input type="hidden" name="check_out" value="{{ $checkout }}"> -->
                    <input type="hidden" name="total_guests" value="{{ $guests }}">
                    <input type="hidden" name="nights" value="{{ $nights }}">
                    <input type="hidden" name="total_price" value="{{ ($homestay->price_per_night * $nights) + $homestay->cleaning_fee }}">
                    <input type="hidden" name="user_email" value="{{ auth()->user()->email }}">
                    <input type="hidden" name="user_name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="check_in_date" value="{{ \Carbon\Carbon::parse($checkin)->format('Y-m-d') }}">
                    <input type="hidden" name="check_out_date" value="{{ \Carbon\Carbon::parse($checkout)->format('Y-m-d') }}">

                    <button type="submit" class="btn btn-success w-100" style="height: 50px;font-size: 18px;">Reserve & Pay</button>
                </form>                
            @else
                <h5 class="mt-4 fw-bold mb-3">Log in or sign up to book</h5>
                @php
                    session(['url.intended' => url()->current()]);
                @endphp
                <div>
                    <div class="w-100">
                        <a href="#" class="btn btn-success w-100 pt-2" style="font-size: 18px;height:50px" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                    </div>
                    <div class="w-100">
                        <a href="#" class="text-success" style="font-size: 18px;" data-bs-toggle="modal" data-bs-target="#registerModal">No account? Sign up</a>
                    </div>
                </div>

                {{-- Divider with "or" --}}
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1 border-top"></div>
                    <span class="px-2 text-muted">or</span>
                    <div class="flex-grow-1 border-top"></div>
                </div>

                {{-- Google login button --}}
                <a href="{{ route('google.redirect', ['redirect' => urlencode(url()->current())]) }}" class="btn btn-outline-dark w-100 mb-2 pt-2" style="height: 50px;font-size: 18px;">
                    <img src="https://www.gstatic.com/marketing-cms/assets/images/d5/dc/cfe9ce8b4425b410b49b7f2dd3f3/g.webp=s96-fcrop64=1,00000000ffffffff-rw" alt="Google" width="20" height="20" class="me-2 ">Continue with Google
                </a>
            @endauth
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
                    @php
                        session(['url.intended' => url()->current()]);
                    @endphp
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
</body>
