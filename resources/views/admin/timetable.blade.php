@extends('layouts.admin')

@section('page_title', 'Booking Timetable')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
    #calendar {
        max-width: 100%;
        margin: 40px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
</style>
<style>
  .fc-event-time {
    width: auto;
    min-width: 60px;
  }
  .fc-daygrid-day-number {
    text-decoration: none;
    color: #000000;
  }
  .fc-col-header-cell-cushion{
    text-decoration: none;
    color: #000000;
  }
</style>

@endsection

@section('content')
<div class="container mt-4">
    <div id="calendar"></div>
</div>

<!-- Booking Details Modal (reused) -->
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
            <div class="col-md-12"><label class="form-label">Homestay</label><input type="text" id="homestayTitle" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">Booking ID</label><input type="text" id="bookingId" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">User Name</label><input type="text" id="userName" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">User Email</label><input type="text" id="userEmail" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">Check-In</label><input type="text" id="checkIn" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">Check-Out</label><input type="text" id="checkOut" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">Status</label><input type="text" id="status" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">Total Amount</label><input type="text" id="totalAmount" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">Total Guests</label><input type="text" id="totalGuests" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">External Reference</label><input type="text" id="externalReference" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">ToyyibPay Bill Code</label><input type="text" id="toyyibCode" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">Created At</label><input type="text" id="createdAt" class="form-control" readonly></div>
            <div class="col-md-6"><label class="form-label">Paid At</label><input type="text" id="paidAt" class="form-control" readonly></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short' // e.g., "12:00am"
            },
            events: [
                @foreach ($bookings as $booking)
                {
                    id: '{{ $booking->id }}',
                    title: '{{ $booking->homestay->title ?? "N/A" }} ({{ ucfirst($booking->status) }})',
                    start: '{{ $booking->check_in_date }}',
                    end: '{{ \Carbon\Carbon::parse($booking->check_out_date)->addDay()->format("Y-m-d") }}', // +1 because FullCalendar is exclusive on end date
                    backgroundColor: '{{$booking->status === "completed" ? "#28a745" : ($booking->status === "pending" ? "#ffc107" : ($booking->status === "cancelled" ? "#dc3545" : "#6c757d")) }}',
                    borderColor: '{{ $booking->status === "completed" ? "#28a745" : ($booking->status === "pending" ? "#ffc107" : ($booking->status === "cancelled" ? "#dc3545" : "#6c757d"))  }}',
                    extendedProps: {
                        homestay: '{{ $booking->homestay->title ?? "N/A" }}',
                        user: '{{ $booking->user->name ?? "N/A" }}',
                        email: '{{ $booking->user->email ?? "" }}',
                        status: '{{ ucfirst($booking->status) }}',
                        checkin: '{{ $booking->check_in_date }}',
                        checkout: '{{ $booking->check_out_date }}',
                        amount: 'RM {{ number_format($booking->total_amount, 2) }}',
                        created: '{{ $booking->created_at->format("Y-m-d H:i") }}',
                        guests: '{{ $booking->total_guests ?? "-" }}',
                        reference: '{{ $booking->external_reference ?? "-" }}',
                        billcode: '{{ $booking->toyyibpay_bill_code ?? "-" }}',
                        paidat: '{{ $booking->paid_at ?? "-" }}',
                    }
                },
                @endforeach
            ],
            eventClick: function (info) {
                const data = info.event.extendedProps;

                document.getElementById('bookingId').value = info.event.id;
                document.getElementById('homestayTitle').value = data.homestay;
                document.getElementById('userName').value = data.user;
                document.getElementById('userEmail').value = data.email;
                document.getElementById('checkIn').value = data.checkin;
                document.getElementById('checkOut').value = data.checkout;
                document.getElementById('status').value = data.status;
                document.getElementById('totalAmount').value = data.amount;
                document.getElementById('createdAt').value = data.created;
                document.getElementById('totalGuests').value = data.guests;
                document.getElementById('externalReference').value = data.reference;
                document.getElementById('toyyibCode').value = data.billcode;
                document.getElementById('paidAt').value = data.paidat;

                new bootstrap.Modal(document.getElementById('bookingDetailsModal')).show();
            }
        });

        calendar.render();
    });
</script>
@endsection
