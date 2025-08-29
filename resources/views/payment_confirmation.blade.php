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
    body {
        background-color: #f5fdf6;
    }
    </style>
</head>

    <body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center p-4 shadow rounded-4">
                    @if(isset($statusId) && $statusId == 1)
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-4x mb-3"></i>
                            <h3 class="fw-bold">Payment Successful</h3>
                            <p class="mb-2">Thank you for your reservation!</p>
                            <p class="small text-muted">Bill Code: <strong>{{ $billcode ?? 'N/A' }}</strong></p>
                        </div>
                    @else
                        <div class="text-danger">
                            <i class="fas fa-times-circle fa-4x mb-3"></i>
                            <h3 class="fw-bold">Payment Failed</h3>
                            <p class="mb-2">Unfortunately, your payment could not be processed.</p>
                            <p class="small text-muted">Please try again or contact support.</p>
                        </div>
                    @endif

                    <a href="{{ route('home') }}" class="btn btn-success mt-4">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>