@extends('layouts.admin') {{-- This line remains the same --}}

@section('page_title', 'Analytics Dashboard') {{-- Set the page title for the dashboard --}}

@section('content')
<form method="GET" action="{{ route('admin.dashboard') }}" class="row mb-4 container">
    <div class="col-md-4">
        <label for="month" class="form-label">Select Month</label>
        <select name="month" id="month" class="form-select">
            <option value="">-- All Months --</option>
            @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="year" class="form-label">Select Year</label>
        <select name="year" id="year" class="form-select">
            <option value="">-- All Years --</option>
            @for ($y = now()->year; $y >= 2022; $y--)
                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>
    </div>
    <div class="col-md-4 align-self-end">
        <button type="submit" class="btn btn-primary">Apply Filter</button>
    </div>
</form>
<div class="container">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-3">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Homestays</h5>
                    <p class="card-text fs-3">{{ $totalHomestays }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text fs-3">{{ $totalBookings }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text fs-3">RM {{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Monthly Bookings</h5>
                    <p class="card-text fs-3">{{ $monthlyBookings }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Yearly Bookings</h5>
                    <p class="card-text fs-3">{{ $yearlyBookings }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white" style="background-color: #6f42c1;"> {{-- Purple --}}
                <div class="card-body">
                    <h5 class="card-title">Monthly Revenue</h5>
                    <p class="card-text fs-3">RM {{ number_format($monthlyRevenue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white" style="background-color: #fd7e14;"> {{-- Orange --}}
                <div class="card-body">
                    <h5 class="card-title">Yearly Revenue</h5>
                    <p class="card-text fs-3">RM {{ number_format($yearlyRevenue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Recent Homestays</h3>
            <ul class="list-group">
                @forelse($recentHomestays as $homestay)
                    <li class="list-group-item d-flex align-items-center">
                        <img src="{{ asset($homestay->images->first()->image_url ?? 'images/default_homestay.png') }}"
                             alt="{{ $homestay->title }}" class="me-2" style="width: 60px; height: 50px; object-fit: cover;">
                        <div>
                            <strong>{{ $homestay->title }}</strong><br>
                            <small>{{ $homestay->location_city }}, {{ $homestay->location_state }}</small>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No recent homestays.</li>
                @endforelse
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Recent Bookings</h3>
            <ul class="list-group">
                @forelse($recentBookings as $booking)
                    <li class="list-group-item">
                        <strong>Booking ID:</strong> {{ $booking->id }}<br>
                        <strong>Homestay:</strong> {{ $booking->homestay->title ?? 'N/A' }}<br>
                        <strong>User:</strong> {{ $booking->user->name ?? 'N/A' }}<br>
                        <strong>Check-in:</strong> {{ $booking->check_in_date }}<br>
                        <strong>Status:</strong> <span class="badge bg-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($booking->status) }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No recent bookings.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection