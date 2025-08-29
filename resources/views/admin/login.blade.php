<!DOCTYPE html>
<html lang="en" data-bs-theme="light" style="height: 100%;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StayBuddy</title>
    <link rel="icon" href="{{ asset('assets/logo/logo-icon-mini.png') }}" type="image/png">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: rgb(113, 212, 124);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-login-card {
            max-width: 550px;
            width: 100%;
            margin: auto;
            border-radius: 1rem;
        }

        .form-control-user {
            border-radius: 10rem;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .btn-user {
            border-radius: 10rem;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .text-center a.small {
            display: block;
            margin-top: 15px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .text-center a.small:hover {
            text-decoration: underline;
            color:rgb(64, 197, 79);
        }

        .alert ul {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card border-0 shadow-lg o-hidden admin-login-card">
            <div class="card-body p-0">
                <div class="p-5">
                    <div class="text-center">
                        <h4 class="text-dark mb-4">Welcome Back!</h4>
                    </div>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="user" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control form-control-user" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter Email Address...">
                        </div>
                        <div class="mb-3">
                            <input class="form-control form-control-user" type="password" name="password" required placeholder="Password">
                        </div>
                        <button class="btn btn-primary d-block btn-user w-100 mb-2" type="submit" style="background-color: rgb(64, 197, 79); border-color: rgb(64, 197, 79);">
                            Login</button>
                        <a href="{{ route('google.redirect') }}" class="btn btn-outline-dark w-100 mb-2 rounded-pill">
                            <i class="fab fa-google me-2"></i>Continue with Google
                        </a>
                    </form>

                    <div class="text-center">
                        <a class="small" href="#">Please contact the development team if forgotten your password.</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
