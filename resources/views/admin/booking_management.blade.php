@extends('layouts.admin')

@section('page_title', 'Booking Management')

@section('content')
<!-- Booking Details Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookingDetailsLabel">Booking Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label">Homestay</label>
              <input type="text" id="homestayTitle" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Booking ID</label>
              <input type="text" id="bookingId" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">User Name</label>
              <input type="text" id="userName" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">User Email</label>
              <input type="text" id="userEmail" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Check-In</label>
              <input type="text" id="checkIn" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Check-Out</label>
              <input type="text" id="checkOut" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <input type="text" id="status" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Total Amount</label>
              <input type="text" id="totalAmount" class="form-control" readonly>
            </div>            
            <div class="col-md-6">
                <label class="form-label">Total Guests</label>
                <input type="text" id="totalGuests" class="form-control" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">External Reference</label>
                <input type="text" id="externalReference" class="form-control" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">ToyyibPay Bill Code</label>
                <input type="text" id="toyyibCode" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Created At</label>
              <input type="text" id="createdAt" class="form-control" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Paid At</label>
                <input type="text" id="paidAt" class="form-control" readonly>
            </div>           
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="container mt-4">

    <form method="GET" action="{{ route('admin.booking_management') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="all">All</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="homestay_search" class="form-label">Homestay</label>
            <input type="text" name="homestay_search" id="homestay_search" value="{{ request('homestay_search') }}" class="form-control" placeholder="Title or ID">
        </div>
        <div class="col-md-3">
            <label for="user_search" class="form-label">User</label>
            <input type="text" name="user_search" id="user_search" value="{{ request('user_search') }}" class="form-control" placeholder="Name or Email">
        </div>
        <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-3 align-self-end text-center">
            <a href="{{ route('admin.booking_management') }}" class="btn btn-secondary w-100">Reset Filter</a>
        </div>

    </form>

    @if($bookings->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Homestay</th>
                        <th>User</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr class="booking-row"
                                data-id="{{ $booking->id }}"
                                data-homestay="{{ $booking->homestay->title ?? 'N/A' }}"
                                data-username="{{ $booking->user->name ?? 'N/A' }}"
                                data-useremail="{{ $booking->user->email ?? '' }}"
                                data-checkin="{{ $booking->check_in_date }}"
                                data-checkout="{{ $booking->check_out_date }}"
                                data-status="{{ ucfirst($booking->status) }}"
                                data-amount="RM {{ number_format($booking->total_amount, 2) }}"
                                data-created="{{ $booking->created_at->format('Y-m-d H:i') }}"
                                data-guests="{{ $booking->total_guests ?? '-' }}"
                                data-reference="{{ $booking->external_reference ?? '-' }}"
                                data-billcode="{{ $booking->toyyibpay_bill_code ?? '-' }}"
                                data-paidat="{{ $booking->paid_at ?? '-' }}"
                                style="cursor: pointer;">
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->homestay->title ?? 'N/A' }}</td>
                            <td>{{ $booking->user->name ?? 'N/A' }}<br><small>{{ $booking->user->email ?? '' }}</small></td>
                            <td>{{ $booking->check_in_date }}</td>
                            <td>{{ $booking->check_out_date }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $booking->status === 'completed' ? 'success' :
                                    ($booking->status === 'pending' ? 'warning' :
                                    ($booking->status === 'cancelled' ? 'danger' : 'secondary'))
                                }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>RM {{ number_format($booking->total_amount, 2) }}</td>
                            <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $bookings->withQueryString()->links() }}
        </div>
    @else
        <div class="alert alert-warning">No bookings found.</div>
    @endif
</div>
@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.booking-row').forEach(row => {
            row.addEventListener('click', function () {
                document.getElementById('bookingId').value = this.dataset.id;
                document.getElementById('homestayTitle').value = this.dataset.homestay;
                document.getElementById('userName').value = this.dataset.username;
                document.getElementById('userEmail').value = this.dataset.useremail;
                document.getElementById('checkIn').value = this.dataset.checkin;
                document.getElementById('checkOut').value = this.dataset.checkout;
                document.getElementById('status').value = this.dataset.status;
                document.getElementById('totalAmount').value = this.dataset.amount;
                document.getElementById('createdAt').value = this.dataset.created;

                 // New fields
                document.getElementById('totalGuests').value = this.dataset.guests;
                document.getElementById('externalReference').value = this.dataset.reference;
                document.getElementById('toyyibCode').value = this.dataset.billcode;
                document.getElementById('paidAt').value = this.dataset.paidat;

                const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
                modal.show();
            });
        });
    });
</script>
@endsection
