@extends('layouts.app') {{-- Extends the base layout --}}

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        @if(Request::routeIs('account.profile'))
        <h1 class="h2">Profile Information</h1>
        @elseif(Request::routeIs('account.bookings'))
        <h1 class="h2">My Bookings</h1>
        @elseif(Request::routeIs('account.favourites'))
        <h1 class="h2">My Favourites</h1>
        @else
            {{-- Optional: A default title if no specific route matches --}}
            <h1 class="h2">Account Dashboard</h1>
        @endif
    </div>

    <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailModalLabel">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content-placeholder">
                    <p>Loading booking details...</p> {{-- Placeholder content while loading --}}                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="cancelBookingBtn" data-booking-id="">Cancel Booking</button>
                <button type="button" class="btn btn-success" id="completeBookingBtn" data-booking-id="">Mark as Completed</button>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Profile Section Content --}}
                    @if(Request::routeIs('account.profile'))
                        <p>Here you can view and edit your personal details.</p>

                        {{-- Success/Error Messages for AJAX form --}}
                        <div id="profile-status-message" class="alert d-none mt-3" role="alert"></div>

                        <form id="profile-update-form" enctype="multipart/form-data" method="POST" action="{{ route('account.profile.update') }}">
                            @csrf {{-- CSRF token for security --}}

                            <div class="mb-3 text-center">
                                @php
                                    $user = Auth::user();
                                    $image = $user->profile_image;
                                    $isUrl = filter_var($image, FILTER_VALIDATE_URL);
                                    // Use 'storage/' prefix for local stored images if they are not full URLs
                                    $profileImageUrl = $image ? ($isUrl ? $image : asset('storage/' . $image)) : url('https://placehold.co/150');
                                @endphp
                                <img id="profile-image-preview" src="{{ $profileImageUrl }}"
                                    alt="Profile Image"
                                    class="rounded-circle mb-3"
                                    style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #ccc;" referrerPolicy="no-referrer" />
                                <br>
                                {{-- Changed label to be clickable and input to be hidden --}}
                                <label for="profileImageInput" class="btn btn-outline-secondary btn-sm">Change Image</label>
                                <input type="file" id="profileImageInput" name="profile_image" class="d-none" accept="image/*">
                                <div id="profile-image-error" class="text-danger mt-1"></div>
                            </div>

                            <div class="mb-3">
                                <label for="userName" class="form-label">Name <span style="color: red;">*</span></label>
                                {{-- Added name attribute and used actual user data --}}
                                <input type="text" class="form-control" id="userName" name="name" value="{{ $user->name ?? '' }}" required>
                                <div id="name-error" class="text-danger mt-1"></div>
                            </div>

                            <div class="mb-3">
                                <label for="userEmail" class="form-label">Email address <span style="color: red;">*</span></label>
                                {{-- Added name attribute and used actual user data --}}
                                <input type="email" class="form-control" id="userEmail" name="email" value="{{ $user->email ?? '' }}" required>
                                <div id="email-error" class="text-danger mt-1"></div>
                            </div>

                            <div class="mb-3">
                                <label for="userPhone" class="form-label">Phone Number</label>
                                {{-- Added name attribute and used actual user data --}}
                                <input type="text" class="form-control" id="userPhone" name="phoneNo" value="{{ $user->phoneNo ?? '' }}">
                                <div id="phone-no-error" class="text-danger mt-1"></div>
                            </div>

                            <button type="submit" class="btn btn-save">Save Changes</button>
                        </form>

                    {{-- My Bookings Section Content (unchanged, remember to remove style="display: none;" in prod) --}}
                    @elseif(Request::routeIs('account.bookings'))
                        <div id="bookings-content" >
                            <p>View your upcoming and past bookings here.</p>
                            @forelse($bookings ?? [] as $booking)
                            <a href="#" class="booking-item" data-bs-toggle="modal" data-bs-target="#bookingDetailModal" data-booking-id="{{ $booking->id }}" style="text-decoration: none; color: inherit;">
                                <ul class="list-group mb-2">
                                    <li class="list-group-item d-flex align-items-center">
                                        <div class="me-3">
                                            {{-- Assuming homestay has images relationship --}}
                                            <img src="{{ asset($booking->homestay_images->first()->image_url) }}"
                                                    alt="Homestay Image"
                                                    style="width: 140px; height: 120px; object-fit: cover; border-radius: 5px;">
                                        </div>
                                        <div>
                                            <strong>Booking ID:</strong> {{ $booking->id }} <br>
                                            <strong>Homestay:</strong> {{ $booking->homestay->title ?? 'N/A' }} <br>
                                            <strong>Dates:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }} <br>
                                            <strong>Guests:</strong> {{ $booking->total_guests }} <br>
                                            <strong>Status:</strong> <span class="badge {{ $booking->status == 'pending' ? 'bg-warning' : ($booking->status == 'completed' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($booking->status) }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </a>
                            @empty
                                <ul class="list-group mb-2">
                                <li class="list-group-item text-muted">You have no bookings yet.</li>
                            </ul>
                            @endforelse  
                        </div>

                    {{-- My Favourites Section Content (unchanged, remember to remove style="display: none;" in prod) --}}
                        @elseif(Request::routeIs('account.favourites'))
                            <div id="favourites-content" class="mt-4">
                                <h4>My Favourites</h4>
                                <p>Manage your saved favourite homestays.</p>
                                <div class="row">
                                    @forelse($favouriteHomestays ?? [] as $homestay)
                                        <div class="col-md-4 mb-3">
                                            <a href="{{ route('accommodation.detail', ['homestay_id' => $homestay->homestay_id]) }}" class="text-decoration-none text-dark">
                                                <div class="card h-100">
                                                    {{-- Use asset() for image URL, and ensure homestay has images --}}
                                                    <img src="{{ asset($homestay->images->first()->image_url ?? 'images/default_homestay.png') }}" class="card-img-top" alt="{{ $homestay->name ?? 'Homestay Image' }}" style="height: 200px; object-fit: cover;">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $homestay->title ?? 'Homestay' }}</h5> {{-- Changed to title, assuming your Homestay model uses 'title' --}}
                                                        <p class="card-text text-muted">{{ $homestay->location_city ?? '' }}, {{ $homestay->location_state ?? '' }}</p>
                                                        <p class="card-text fw-bold">RM {{ number_format($homestay->price_per_night, 2) }} / night</p>
                                                        {{-- Add a "Remove from Favourites" button --}}
                                                        <button class="btn btn-sm btn-outline-danger remove-favourite-btn" data-homestay-id="{{ $homestay->uuid }}">Remove</button>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted">You haven't added any favourites yet.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Profile Image Preview ---
        // Ensure IDs match the HTML:
        const profileImageInput = document.getElementById('profileImageInput'); // This is the file input
        const profileImagePreview = document.getElementById('profile-image-preview'); // This is the <img> tag

        if (profileImageInput && profileImagePreview) {
            profileImageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // --- Profile Update Form Submission (AJAX) ---
        const profileUpdateForm = document.getElementById('profile-update-form');
        const statusMessage = document.getElementById('profile-status-message');
        const nameError = document.getElementById('name-error');
        const emailError = document.getElementById('email-error');
        const phoneNoError = document.getElementById('phone-no-error');
        const profileImageError = document.getElementById('profile-image-error'); // New error element for image

        if (profileUpdateForm) {
            profileUpdateForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Clear previous messages and errors
                statusMessage.classList.add('d-none');
                statusMessage.classList.remove('alert-success', 'alert-danger');
                nameError.textContent = '';
                emailError.textContent = '';
                phoneNoError.textContent = '';
                profileImageError.textContent = ''; // Clear image error

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200 && body.success) {
                        statusMessage.textContent = body.message;
                        statusMessage.classList.remove('d-none');
                        statusMessage.classList.add('alert-success');

                        // Update the profile image in the main navbar (if it exists)
                        const navbarProfileImage = document.querySelector('.container-fluid.border-bottom .profile-btn img');
                        if (navbarProfileImage && body.profile_image_url) {
                            navbarProfileImage.src = body.profile_image_url;
                        } else if (navbarProfileImage && !body.profile_image_url) {
                            // If profile_image was cleared/removed, show default icon in navbar if there's an <img> for it.
                            // This scenario assumes 'profile_image_url' might be null/empty from backend if image is removed.
                            // You might want to update the logic here if you have a default user icon.
                        }

                        // Update initial of profile image in navbar (if it exists)
                        const navbarProfileInitial = document.querySelector('.container-fluid.border-bottom .profile-btn .rounded-circle.d-flex');
                        if(navbarProfileInitial && body.name){
                            navbarProfileInitial.textContent = body.name.charAt(0).toUpperCase();
                        }

                        // Set a timeout to hide the message after 10 seconds (10000 milliseconds)
                        setTimeout(() => {
                            statusMessage.classList.add('d-none');
                            statusMessage.classList.remove('alert-success'); // Clean up class
                        }, 10000); // 10 seconds

                    } else if (status === 422) { // Validation errors
                        statusMessage.textContent = 'Please correct the errors below.';
                        statusMessage.classList.remove('d-none');
                        statusMessage.classList.add('alert-danger');

                        if (body.errors) {
                            if (body.errors.name) {
                                nameError.textContent = body.errors.name[0];
                            }
                            if (body.errors.email) {
                                emailError.textContent = body.errors.email[0];
                            }
                            if (body.errors.phoneNo) { // Handle phoneNo error
                                phoneNoError.textContent = body.errors.phoneNo[0];
                            }
                            if (body.errors.profile_image) { // Handle profile image error
                                profileImageError.textContent = body.errors.profile_image[0];
                            }
                        }

                        // Validation errors usually require user action, so don't hide automatically.
                        // If you *still* want to hide validation errors, uncomment and adjust this:
                        // setTimeout(() => {
                        //     statusMessage.classList.add('d-none');
                        //     statusMessage.classList.remove('alert-danger');
                        // }, 10000); // 10 seconds

                    } else { // Other server errors (e.g., 500)
                        statusMessage.textContent = body.message || 'An unexpected error occurred.';
                        statusMessage.classList.remove('d-none');
                        statusMessage.classList.add('alert-danger');

                        // Set a timeout to hide general error messages as well
                        setTimeout(() => {
                            statusMessage.classList.add('d-none');
                            statusMessage.classList.remove('alert-danger'); // Clean up class
                        }, 10000); // 10 seconds
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusMessage.textContent = 'Network error or server unreachable.';
                    statusMessage.classList.remove('d-none');
                    statusMessage.classList.add('alert-danger');

                    // Set a timeout to hide network error messages
                    setTimeout(() => {
                        statusMessage.classList.add('d-none');
                        statusMessage.classList.remove('alert-danger'); // Clean up class
                    }, 10000); // 10 seconds
                });
            });
        }

        // --- Booking Detail Modal ---
        const bookingItems = document.querySelectorAll('.booking-item');
        const bookingDetailModal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
        const modalContentPlaceholder = document.getElementById('modal-content-placeholder');
        const cancelBookingBtn = document.getElementById('cancelBookingBtn');
        const completeBookingBtn = document.getElementById('completeBookingBtn');

        bookingItems.forEach(item => {
            item.addEventListener('click', function () {
                const bookingId = this.dataset.bookingId;
                modalContentPlaceholder.innerHTML = '<p>Loading booking details...</p>'; // Show loading message
                bookingDetailModal.show(); // Show modal immediately

                // Fetch booking details via AJAX
                fetch(`/account/bookings/${bookingId}`) // This route needs to be defined
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 404) {
                                throw new Error('Booking not found.');
                            }
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        
                        // Populate modal with fetched data
                        modalContentPlaceholder.innerHTML = `
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <img src="${data.homestay_image_url ?? 'https://placehold.co/140x120'}"
                                        alt="Homestay Image"
                                        style="width: 140px; height: 120px; object-fit: cover; border-radius: 5px;">
                                </div>
                                <div>
                                    <h5>${data.homestay_title || 'N/A'}</h5>
                                    <p><strong>Location:</strong> ${data.homestay_location || 'N/A'}</p>
                                </div>
                            </div>
                            <p><strong>Booking ID:</strong> ${data.id}</p>
                            <p><strong>Check-in:</strong> ${new Date(data.check_in_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                            <p><strong>Check-out:</strong> ${new Date(data.check_out_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                            <p><strong>Total Guests:</strong> ${data.total_guests}</p>
                            <p><strong>Total Amount:</strong> RM ${parseFloat(data.total_amount).toFixed(2)}</p>
                            <p><strong>Payment Status:</strong> <span class="badge bg-${data.status === 'pending' ? 'warning' : (data.status === 'completed' ? 'success' : (data.status === 'cancelled' ? 'danger' : 'info'))}">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span></p>
                            ${data.transaction_id ? `<p><strong>Transaction ID:</strong> ${data.transaction_id}</p>` : ''}
                            ${data.paid_at ? `<p><strong>Paid At:</strong> ${new Date(data.paid_at).toLocaleString()}</p>` : ''}
                            <p><strong>Booked On:</strong> ${new Date(data.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                            <div id="review-section"></div>
                        `;

                        if (data.status === 'completed') {
                            const reviewSection = document.getElementById('review-section');
                            reviewSection.innerHTML = `
                                <hr>
                                <form id="reviewForm" onsubmit="return false;">
                                    <input type="hidden" name="homestay_id" value="${data.homestay_id}">
                                    <input type="hidden" name="user_id" value="${data.user_id}">                                    
                                    <div class="mb-3">
                                        <label class="form-label d-block">Your Rating:</label>
                                        <div id="starRating" class="mb-2">
                                            ${[1, 2, 3, 4, 5].map(star => `
                                                <i class="bi ${data.rating >= star ? 'bi-star-fill' : 'bi-star'}"
                                                data-value="${star}"
                                                style="font-size: 1.5rem; cursor: pointer; color: ${data.rating >= star ? '#ffc107' : '#ccc'};"></i>
                                            `).join('')}
                                        </div>
                                        <input type="hidden" id="ratingValue" name="rating" value="${data.rating || 0}">
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control" id="reviewText" rows="3" placeholder="Enter your feedback...">${data.review || ''}</textarea>
                                    </div>
                                    <button type="button" id="submitReviewBtn" class="btn btn-primary btn-sm">Submit Review</button>
                                </form>
                            `;

                            // Handle star rating click
                            const stars = document.querySelectorAll('#starRating i');
                            stars.forEach(star => {
                                star.addEventListener('click', function () {
                                    const rating = parseInt(this.getAttribute('data-value'));
                                    document.getElementById('ratingValue').value = rating;

                                    // Highlight stars
                                    stars.forEach(s => {
                                        const value = parseInt(s.getAttribute('data-value'));
                                        s.classList.remove('bi-star-fill');
                                        s.classList.add('bi-star');
                                        s.style.color = '#ccc';

                                        if (value <= rating) {
                                            s.classList.remove('bi-star');
                                            s.classList.add('bi-star-fill');
                                            s.style.color = '#ffc107';
                                        }
                                    });
                                });
                            });

                            // Handle review submission
                            document.getElementById('submitReviewBtn').addEventListener('click', function () {
                                const review = document.getElementById('reviewText').value.trim();
                                const rating = parseInt(document.getElementById('ratingValue').value);
                                if (!review) return alert('Review cannot be empty.');
                                if (rating === 0) return alert('Please select a rating.');
                                
                                const homestayId = document.querySelector('input[name="homestay_id"]').value;
                                const userId = document.querySelector('input[name="user_id"]').value;

                                fetch(`/account/bookings/${data.id}/review`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ review, rating, homestayId, userId })
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error('Failed to submit review');
                                    return response.json();
                                })
                                .then(result => {
                                    if (result.success) {
                                        alert('Review submitted successfully!');
                                        bookingDetailModal.hide();
                                    } else {
                                        alert('Failed to submit review: ' + (result.message || 'Unknown error'));
                                    }
                                })
                                .catch(error => {
                                    console.error('Review error:', error);
                                    alert('There was an error submitting your review.');
                                });
                            });
                        }

                        // Update action buttons data-booking-id and visibility
                        cancelBookingBtn.dataset.bookingId = bookingId;
                        completeBookingBtn.dataset.bookingId = bookingId;

                        // Show/hide buttons based on booking status
                        if (data.status === 'pending') {
                            cancelBookingBtn.style.display = 'inline-block';
                            completeBookingBtn.style.display = 'inline-block'; // Or hide if "complete" is only for admin
                        } else if (data.status === 'unpaid') {
                            cancelBookingBtn.style.display = 'inline-block';
                            completeBookingBtn.style.display = 'none';
                        } else {
                            cancelBookingBtn.style.display = 'none';
                            completeBookingBtn.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching booking details:', error);
                        modalContentPlaceholder.innerHTML = `<p class="text-danger">Failed to load booking details: ${error.message}. Please try again.</p>`;
                        cancelBookingBtn.style.display = 'none';
                        completeBookingBtn.style.display = 'none';
                    });
            });
        });

        // Handle Cancel Booking button click
        cancelBookingBtn.addEventListener('click', function () {
            if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                const bookingId = this.dataset.bookingId;
                updateBookingStatus(bookingId, 'cancelled'); // Function to send update request
            }
        });

        // Handle Mark as Completed button click (be cautious with user-initiated 'complete')
        completeBookingBtn.addEventListener('click', function () {
            if (confirm('Are you sure you want to mark this booking as completed?')) {
                const bookingId = this.dataset.bookingId;
                updateBookingStatus(bookingId, 'completed'); // Function to send update request
            }
        });

        function updateBookingStatus(bookingId, newStatus) {
            fetch(`/account/bookings/${bookingId}/status`, { // This route needs to be defined
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 403) {
                        throw new Error('Unauthorized action.');
                    }
                    throw new Error('Failed to update booking status: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Booking status updated successfully!');
                    bookingDetailModal.hide();
                    // Optionally, refresh the page or update the specific list item
                    window.location.reload(); // Simple reload for now
                } else {
                    alert('Error updating booking status: ' + (data.message || 'Unknown error.'));
                }
            })
            .catch(error => {
                console.error('Error updating booking status:', error);
                alert('An error occurred: ' + error.message);
            });
        }
    });
</script>
@endpush