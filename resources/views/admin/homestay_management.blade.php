@extends('layouts.admin')

@section('page_title', 'Homestay Management')

@section('styles')
<style>
    nav{
        width: 100%;
    }
    .carousel-item img {
        max-height: 85vh !important;
        object-fit: contain !important;
    }
</style>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container mt-4">

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.homestay_management') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="search" class="form-label">Search Homestay</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Title, City, or State">
        </div>
        <div class="col-md-4">
            <label for="availability" class="form-label">Availability</label>
            <select name="availability" id="availability" class="form-select">
                <option value="all">All</option>
                <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    {{-- Add New Homestay Button --}}
    <button class="btn btn-success mb-4" style="color: white;" data-bs-toggle="modal" data-bs-target="#addHomestayModal">Add New Homestay</button>

    {{-- Homestay Table --}}
    @if ($homestays->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Price/Night</th>
                    <th>Max Guests</th>
                    <th class="text-center" style="min-width: 125px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($homestays as $homestay)
            <tr data-homestay='@json($homestay)' onclick="showHomestayDetails(this)" style="cursor: pointer;">
                <td>{{ $homestay->title }}</td>
                <td>{{ $homestay->location_city }}</td>
                <td>{{ $homestay->location_state }}</td>
                <td>RM {{ number_format($homestay->price_per_night, 2) }}</td>
                <td>{{ $homestay->max_guests }}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="event.stopPropagation(); editHomestay('{{ $homestay->homestay_id }}')">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); deleteHomestay('{{ $homestay->homestay_id }}')">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between mt-2">
        {{ $homestays->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @else
    <div class="alert alert-warning">No homestays found.</div>
    @endif
</div>

{{-- Add Homestay Modal --}}
<div class="modal fade" id="addHomestayModal" tabindex="-1" aria-labelledby="addHomestayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.homestay.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addHomestayModalLabel">Add New Homestay</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    {{-- Title --}}
                    <div class="mb-3 col-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3 col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                    </div>

                    {{-- City --}}
                    <div class="mb-3 col-6">
                        <label for="location_city" class="form-label">City</label>
                        <input type="text" id="location_city" name="location_city" class="form-control" required>
                    </div>

                    {{-- State --}}
                    <div class="mb-3 col-6">
                        <label for="location_state" class="form-label">State</label>
                        <input type="text" id="location_state" name="location_state" class="form-control" required>
                    </div>

                    {{-- Country --}}
                    <div class="mb-3 col-6">
                        <label for="location_country" class="form-label">Country</label>
                        <input type="text" id="location_country" name="location_country" class="form-control" required> 
                    </div>

                    {{-- Address --}}
                    <div class="mb-3 col-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>

                    {{-- Price per Night --}}
                    <div class="mb-3 col-6">
                        <label for="price_per_night" class="form-label">Price/Night</label>
                        <input type="number" id="price_per_night" name="price_per_night" class="form-control" required>
                    </div>

                    {{-- Cleaning Fee --}}
                    <div class="mb-3 col-6">
                        <label for="cleaning_fee" class="form-label">Cleaning Fee</label>
                        <input type="number" id="cleaning_fee" name="cleaning_fee" class="form-control" required>
                    </div>

                    {{-- Max Guests --}}
                    <div class="mb-3 col-6">
                        <label for="max_guests" class="form-label">Max Guests</label>
                        <input type="number" id="max_guests" name="max_guests" class="form-control" required>
                    </div>

                    {{-- Room Type --}}
                    <div class="mb-3 col-6">
                        <label for="room_type" class="form-label">Room Type</label>
                        <select id="room_type" name="room_type" class="form-select" required>
                            <option value="">Select Room Type</option>
                            <option value="entire_home">Entire Home</option>
                            <option value="entire_unit">Entire Unit</option>
                            <option value="entire_condo">Entire Condo</option>
                            <option value="private_room">Private Room</option>
                            <option value="shared_room">Shared Room</option>
                        </select>
                    </div>

                    {{-- Bedrooms --}}
                    <div class="mb-3 col-6">
                        <label for="bedrooms" class="form-label">Bedrooms</label>
                        <input type="number" id="bedrooms" name="bedrooms" class="form-control" required>
                    </div>

                    {{-- Beds --}}
                    <div class="mb-3 col-6">
                        <label for="beds" class="form-label">Beds</label>
                        <input type="number" id="beds" name="beds" class="form-control" required>
                    </div>

                    {{-- Bathrooms --}}
                    <div class="mb-3 col-6">
                        <label for="bathrooms" class="form-label">Bathrooms</label>
                        <input type="number" id="bathrooms" name="bathrooms" class="form-control" required>
                    </div>

                    {{-- checkin time --}}
                    <div class="mb-3 col-6">
                        <label for="check_in_time" class="form-label">Check-in Time</label>
                        <input type="text" id="check_in_time" name="check_in_time" class="form-control" required>
                    </div>

                    {{-- checkout time --}}
                    <div class="mb-3 col-6">
                        <label for="check_out_time" class="form-label">Check-out Time</label>
                        <input type="text" id="check_out_time" name="check_out_time" class="form-control" required>
                    </div>

                    {{-- tags --}}
                    <div class="mb-3 col-12">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" id="tags" name="tags" class="form-control" placeholder="e.g. Beachfront, Pet-friendly, etc.">
                        <small class="text-muted">Separate tags with commas.</small>
                    </div>

                    {{-- rules --}}
                    <div class="mb-3 col-12">
                        <label for="rules" class="form-label">House Rules</label>
                        <textarea id="rules" name="rules" class="form-control" rows="3" placeholder="e.g. No smoking, No pets, etc."></textarea>
                        <small class="text-muted">Specify house rules for guests.</small>
                    </div>

                    {{-- Map Picker --}}
                    <div class="mb-3 col-12">
                        <label class="form-label">Pin Location on Map</label>
                        <div id="map" style="height: 400px; width: 100%; border: 1px solid #ccc;"></div>
                    </div>

                    {{-- Latitude --}}
                    <div class="mb-3 col-6">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" step="0.0000001" required readonly>
                    </div>

                    {{-- Longitude --}}
                    <div class="mb-3 col-6">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" step="0.0000001" required readonly>
                    </div>

                    {{-- Images --}}
                    <div class="mb-4 col-12">
                        <label for="images" class="form-label">Upload Images</label>
                        <input type="file" id="images" name="images[]" class="form-control" multiple accept="image/*" required>
                        <small class="text-muted">You can select multiple images.</small>
                        <!-- Preview Container -->
                        <div id="imagesPreview" class="mt-2 d-flex flex-wrap"></div>
                    </div>

                   {{-- Amenities --}}
                    <div class="mb-3">
                        <label class="form-label">Select Amenities</label>

                        @php
                            // Group amenities by category
                            $groupedAmenities = $amenities->groupBy('category');
                        @endphp

                        @foreach($groupedAmenities as $category => $items)
                            <h6 class="fw-bold mt-2">{{ $category }}</h6>
                            <div class="row">
                                @foreach($items as $amenity)
                                    <div class="col-md-4 mb-2 d-flex align-items-center">
                                        <input
                                            class="form-check-input me-2"
                                            type="checkbox"
                                            id="amenity_{{ $amenity->id }}"
                                            name="amenity_ids[]"
                                            value="{{ $amenity->id }}"
                                            checked
                                        >
                                        <label for="amenity_{{ $amenity->id }}" class="form-check-label d-flex align-items-center">
                                            <i class="{{ $amenity->icon }} me-2 fs-5 icon-light"></i>
                                            <span class="fs-6 icon-light">{{ $amenity->amenity }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <small class="text-muted">Tick the boxes to select multiple amenities.</small>
                    </div>
                    {{-- Custom Amenities --}}
                    <div class="mb-3">
                        <label class="form-label">Add Custom Amenity</label>
                        <div id="addCustomAmenitiesContainer"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="customAmenityField()">+ Add Amenity</button>
                        <small class="text-muted d-block mt-1">These will be saved into the amenities database and selectable for future homestays.</small>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" style="color: white;">Save Homestay</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Homestay Modal --}}
<div class="modal fade" id="editHomestayModal" tabindex="-1" aria-labelledby="editHomestayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="editHomestayForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST') {{-- If you're using PATCH, adjust method here --}}
        <div class="modal-header">
          <h5 class="modal-title">Edit Homestay</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body row">
          <input type="hidden" id="edit_homestay_id" name="homestay_id">

          <div class="mb-3 col-12">
            <label for="edit_title" class="form-label">Title</label>
            <input type="text" id="edit_title" name="title" class="form-control" required>
          </div>

          <div class="mb-3 col-12">
            <label for="edit_description" class="form-label">Description</label>
            <textarea id="edit_description" name="description" class="form-control" rows="4"></textarea>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_location_city" class="form-label">City</label>
            <input type="text" id="edit_location_city" name="location_city" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_location_state" class="form-label">State</label>
            <input type="text" id="edit_location_state" name="location_state" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_location_country" class="form-label">Country</label>
            <input type="text" id="edit_location_country" name="location_country" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_address" class="form-label">Address</label>
            <input type="text" id="edit_address" name="address" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_price_per_night" class="form-label">Price/Night</label>
            <input type="number" id="edit_price_per_night" name="price_per_night" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_cleaning_fee" class="form-label">Cleaning Fee</label>
            <input type="number" id="edit_cleaning_fee" name="cleaning_fee" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_max_guests" class="form-label">Max Guests</label>
            <input type="number" id="edit_max_guests" name="max_guests" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_room_type" class="form-label">Room Type</label>
            <select id="edit_room_type" name="room_type" class="form-select" required>
              <option value="">Select Room Type</option>
              <option value="Entire home">Entire Home</option>
              <option value="Entire unit">Entire Unit</option>
              <option value="Entire condo">Entire Condo</option>
              <option value="Private room">Private Room</option>
              <option value="Shared room">Shared Room</option>
            </select>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_bedrooms" class="form-label">Bedrooms</label>
            <input type="number" id="edit_bedrooms" name="bedrooms" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_beds" class="form-label">Beds</label>
            <input type="number" id="edit_beds" name="beds" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_bathrooms" class="form-label">Bathrooms</label>
            <input type="number" id="edit_bathrooms" name="bathrooms" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_check_in_time" class="form-label">Check-in Time</label>
            <input type="text" id="edit_check_in_time" name="check_in_time" class="form-control" required>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_check_out_time" class="form-label">Check-out Time</label>
            <input type="text" id="edit_check_out_time" name="check_out_time" class="form-control" required>
          </div>

          <div class="mb-3 col-12">
            <label for="edit_tags" class="form-label">Tags</label>
            <input type="text" id="edit_tags" name="tags" class="form-control" placeholder="e.g. Beachfront, Pet-friendly">
          </div>

          <div class="mb-3 col-12">
            <label for="edit_rules" class="form-label">House Rules</label>
            <textarea id="edit_rules" name="rules" class="form-control" rows="3" placeholder="e.g. No smoking, No pets"></textarea>
          </div>

          <div class="mb-3 col-12">
            <label class="form-label">Pin Location on Map</label>
            <div id="edit_map" style="height: 400px; width: 100%; border: 1px solid #ccc;"></div>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_latitude" class="form-label">Latitude</label>
            <input type="text" id="edit_latitude" name="latitude" class="form-control" step="0.0000001" required readonly>
          </div>

          <div class="mb-3 col-6">
            <label for="edit_longitude" class="form-label">Longitude</label>
            <input type="text" id="edit_longitude" name="longitude" class="form-control" step="0.0000001" required readonly>
          </div>

          <div class="mb-4 col-12">
            <label for="edit_images" class="form-label">Add New Images (optional)</label>
            <input type="file" id="edit_images" name="images[]" class="form-control" multiple accept="image/*">
            <small class="text-muted">You can select multiple images.</small>
            <div id="editImagesPreview" class="mt-2 d-flex flex-wrap"></div>
          </div>

          {{-- Amenities --}}
          <div class="mb-3">
            <label class="form-label">Select Amenities</label>
            @foreach($groupedAmenities as $category => $items)
              <h6 class="fw-bold mt-2">{{ $category }}</h6>
              <div class="row">
                @foreach($items as $amenity)
                  <div class="col-md-4 mb-2 d-flex align-items-center">
                    <input class="form-check-input me-2 edit-amenity-checkbox"
                           type="checkbox"
                           id="edit_amenity_{{ $amenity->id }}"
                           name="amenity_ids[]"
                           value="{{ $amenity->id }}">
                    <label for="edit_amenity_{{ $amenity->id }}" class="form-check-label d-flex align-items-center">
                      <i class="{{ $amenity->icon }} me-2 fs-5 icon-light"></i>
                      <span class="fs-6 icon-light">{{ $amenity->amenity }}</span>
                    </label>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>

            {{-- Custom Amenities --}}
            <div class="mb-3">
                <label class="form-label">Add Custom Amenity</label>
                <div id="editCustomAmenitiesContainer"></div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="customAmenityField(true)">+ Add Amenity</button>
                <small class="text-muted d-block mt-1">These will be saved into the amenities database and selectable for future homestays.</small>
            </div>

        </div>


        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Homestay</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Homestay Detail View Modal --}}
<div class="modal fade" id="viewHomestayModal" tabindex="-1" aria-labelledby="viewHomestayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Homestay Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          @php
            $fields = [
              'homestay_id', 'title', 'description', 'host_id', 'location_country',
              'location_state', 'location_city', 'address', 'price_per_night',
              'currency', 'cleaning_fee', 'room_type', 'bedrooms', 'beds', 'bathrooms',
              'max_guests', 'rating_avg', 'reviews_count', 'cancellation_policy',
              'check_in_time', 'check_out_time', 'is_guest_favorite', 'tags'
            ];
          @endphp
          @foreach ($fields as $field)
            <div class="col-md-6 mb-3">
              <label class="form-label text-capitalize">{{ str_replace('_', ' ', $field) }}</label>
              <input type="text" class="form-control" id="view_{{ $field }}" readonly>
            </div>
          @endforeach
          <hr class="my-4">
            <h5>Images</h5>
            <div class="row" id="view_images_container"></div>

            <hr class="my-4">
            <h5>Amenities</h5>
            <ul id="view_amenities_list" class="list-group list-group-flush container"></ul>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content img border-0" style="background:rgb(53, 51, 51); border: none;">
            <div class="modal-body p-0" style="overflow-y: hidden;">
                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselImageContainer">
                        <!-- JS will inject images here -->
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
@endsection

@section('scripts')
<script>
    function loadGoogleMapsScript(callback) {
        if (typeof google !== 'undefined') return callback();

        const script = document.createElement('script');
        script.src = env('GOOGLE_MAPS_API_KEY') + callback.name;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadGoogleMapsScript(initMap); // Load the map only when the DOM is ready
    });
</script>

<script>
    let map, marker;

    function initMap() {
        const defaultLatLng = { lat: 2.2008000, lng: 102.2405000 }; // Default to Melaka

        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultLatLng,
            zoom: 14,
        });

        map.addListener("click", (e) => {
            const lat = e.latLng.lat().toFixed(7);
            const lng = e.latLng.lng().toFixed(7);

            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            if (marker) {
                marker.setMap(null);
            }

            marker = new google.maps.Marker({
                position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                map: map,
            });
        });
    }
</script>
<script>
    let editMap, editMarker;

function initEditMap(lat, lng) {
    const latLng = { lat: lat || 2.2008, lng: lng || 102.2405 };

    editMap = new google.maps.Map(document.getElementById("edit_map"), {
        center: latLng,
        zoom: 14,
    });

    if (editMarker) editMarker.setMap(null);

    editMarker = new google.maps.Marker({
        position: latLng,
        map: editMap,
    });

    editMap.addListener("click", (e) => {
        const newLat = e.latLng.lat().toFixed(7);
        const newLng = e.latLng.lng().toFixed(7);

        document.getElementById("edit_latitude").value = newLat;
        document.getElementById("edit_longitude").value = newLng;

        if (editMarker) editMarker.setMap(null);

        editMarker = new google.maps.Marker({
            position: { lat: parseFloat(newLat), lng: parseFloat(newLng) },
            map: editMap,
        });
    });
}
</script>
<script>
    const assetBaseUrl = "{{ asset('') }}";

    function deleteHomestay(homestayId) {
        if (confirm('Are you sure you want to delete this homestay?')) {
            fetch(`/admin/homestay/${homestayId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => {
                console.error('There was a problem deleting the homestay:', error);
                alert('Error deleting homestay. Please try again.');
            });
        }
    }

    function showHomestayDetails(row) {
        const homestay = JSON.parse(row.dataset.homestay);

        // Fill all inputs
        for (const key in homestay) {
            const input = document.getElementById('view_' + key);
            if (input) input.value = homestay[key] ?? '';
        }

       // Populate thumbnail images in view modal
        const imageContainer = document.getElementById('view_images_container');
        imageContainer.innerHTML = '';

        const carouselImageContainer = document.getElementById('carouselImageContainer');
        carouselImageContainer.innerHTML = '';

        if (homestay.images && homestay.images.length > 0) {
            homestay.images.forEach((img, index) => {
                // Thumbnail
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-2';
                col.innerHTML = `
                    <img src="${assetBaseUrl}${img.image_url}"
                        class="img-fluid rounded shadow-sm"
                        style="height: 200px; width:100%; object-fit: cover; cursor: pointer"
                        onclick="openImageModal(${index}, ${JSON.stringify(homestay.images).replace(/"/g, '&quot;')})"
                        alt="${homestay.title}">
                `;
                imageContainer.appendChild(col);

                // Prepare carousel slides
                const slide = document.createElement('div');
                slide.className = 'carousel-item' + (index === 0 ? ' active' : '');
                slide.innerHTML = `
                    <img src="${assetBaseUrl}${img.image_url}" class="d-block extend-image" alt="Slide ${index + 1}">
                `;
                carouselImageContainer.appendChild(slide);
            });
        }

        const amenitiesList = document.getElementById('view_amenities_list');
        amenitiesList.innerHTML = '';

        if (homestay.amenities_list && homestay.amenities_list.length > 0) {
            const grouped = {};

            // Group amenities by category
            homestay.amenities_list.forEach(am => {
                if (!grouped[am.category]) grouped[am.category] = [];
                grouped[am.category].push(am);
            });

            const amenitiesList = document.getElementById('view_amenities_list');
            amenitiesList.innerHTML = '';

            // Render grouped amenities
            Object.entries(grouped).forEach(([category, items]) => {
                // Category title
                const categoryTitle = document.createElement('h6');
                categoryTitle.className = 'mt-3';
                categoryTitle.innerText = category;
                amenitiesList.appendChild(categoryTitle);

                // Amenity items as list
                const ul = document.createElement('ul');
                ul.className = 'list-unstyled row';

                items.forEach(am => {
                    const li = document.createElement('li');
                    li.className = 'col-4 mb-3 d-flex align-items-center';
                    li.innerHTML = `
                        <i class="${am.icon} me-2 fs-5 icon-light"></i>
                        <span class="fs-6 icon-light">${am.amenity}</span>
                    `;
                    ul.appendChild(li);
                });

                amenitiesList.appendChild(ul);
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('viewHomestayModal'));
        modal.show();
    }

    function openImageModal(index, images) {
        const carouselImageContainer = document.getElementById('carouselImageContainer');
        carouselImageContainer.innerHTML = '';

        images.forEach((img, i) => {
            const slide = document.createElement('div');
            slide.className = 'carousel-item' + (i === index ? ' active' : '');
            slide.innerHTML = `
                <img src="${assetBaseUrl}${img.image_url}" class="d-block w-100" alt="Slide ${i + 1}">
            `;
            carouselImageContainer.appendChild(slide);
        });

        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
    
    function editHomestay(homestayId) {
        const homestay = JSON.parse(document.querySelector(`tr[data-homestay*="${homestayId}"]`).dataset.homestay);

        // Set form action URL dynamically (adjust route if needed)
        const form = document.getElementById('editHomestayForm');
        form.action = `/admin/update-homestay/${homestayId}`; // Update this path if needed

        // Fill basic fields
        document.getElementById('edit_homestay_id').value = homestay.homestay_id;
        document.getElementById('edit_title').value = homestay.title || '';
        document.getElementById('edit_description').value = homestay.description || '';
        document.getElementById('edit_location_city').value = homestay.location_city || '';
        document.getElementById('edit_location_state').value = homestay.location_state || '';
        document.getElementById('edit_location_country').value = homestay.location_country || '';
        document.getElementById('edit_address').value = homestay.address || '';
        document.getElementById('edit_price_per_night').value = homestay.price_per_night || '';
        document.getElementById('edit_cleaning_fee').value = homestay.cleaning_fee || '';
        document.getElementById('edit_max_guests').value = homestay.max_guests || '';
        document.getElementById('edit_room_type').value = homestay.room_type || '';
        document.getElementById('edit_bedrooms').value = homestay.bedrooms || '';
        document.getElementById('edit_beds').value = homestay.beds || '';
        document.getElementById('edit_bathrooms').value = homestay.bathrooms || '';
        document.getElementById('edit_check_in_time').value = homestay.check_in_time || '';
        document.getElementById('edit_check_out_time').value = homestay.check_out_time || '';
        document.getElementById('edit_tags').value = homestay.tags || '';
        document.getElementById('edit_rules').value = homestay.rules || '';
        document.getElementById('edit_latitude').value = homestay.latitude || '';
        document.getElementById('edit_longitude').value = homestay.longitude || '';

        document.querySelectorAll('.edit-amenity-checkbox').forEach(cb => cb.checked = false);
        if (homestay.amenity_ids) {
            let amenityIds = homestay.amenity_ids;

            // If amenityIds is a string (like "[1,2,3]"), parse it
            if (typeof amenityIds === 'string') {
                try {
                    amenityIds = JSON.parse(amenityIds);
                } catch (e) {
                    console.error('Invalid amenity_ids format:', amenityIds);
                    amenityIds = [];
                }
            }

            // Now ensure it's an array
            if (Array.isArray(amenityIds)) {
                amenityIds.forEach(id => {
                    const checkbox = document.querySelector(`#edit_amenity_${id}`);
                    if (checkbox) checkbox.checked = true;
                });
            }
        }


        // Initialize map
        initEditMap(parseFloat(homestay.latitude), parseFloat(homestay.longitude));

        // Clear preview
        const preview = document.getElementById('editImagesPreview');
        preview.innerHTML = '';
        if (homestay.images && Array.isArray(homestay.images)) {
            homestay.images.forEach(img => {
                const wrapper = document.createElement("div");
                wrapper.className = "position-relative me-2 mb-2";

                const image = document.createElement("img");
                image.src = `${assetBaseUrl}${img.image_url}`;
                image.style.width = "100px";
                image.style.height = "100px";
                image.style.objectFit = "cover";
                image.style.borderRadius = "5px";
                image.style.border = "1px solid #ccc";

                wrapper.appendChild(image);
                preview.appendChild(wrapper);
            });
        }

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('editHomestayModal'));
        modal.show();
    }

</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("images");
    const previewContainer = document.getElementById("imagesPreview");

    if (!imageInput || !previewContainer) return;

    imageInput.addEventListener("change", function (event) {
        previewContainer.innerHTML = ""; // Clear previous previews

        const selectedImages = Array.from(event.target.files); // Local declaration

        selectedImages.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const wrapper = document.createElement("div");
                wrapper.style.position = "relative";
                wrapper.style.display = "inline-block";
                wrapper.style.margin = "5px";

                const img = document.createElement("img");
                img.src = e.target.result;
                img.style.width = "100px";
                img.style.height = "100px";
                img.style.objectFit = "cover";
                img.style.border = "1px solid #ccc";
                img.style.borderRadius = "5px";

                const deleteBtn = document.createElement("button");
                deleteBtn.innerHTML = "&times;";
                deleteBtn.style.position = "absolute";
                deleteBtn.style.top = "0";
                deleteBtn.style.right = "0";
                deleteBtn.style.background = "red";
                deleteBtn.style.color = "white";
                deleteBtn.style.border = "none";
                deleteBtn.style.width = "20px";
                deleteBtn.style.height = "20px";
                deleteBtn.style.borderRadius = "50%";
                deleteBtn.style.cursor = "pointer";
                deleteBtn.style.fontSize = "14px";
                deleteBtn.style.display = "flex";
                deleteBtn.style.alignItems = "center";
                deleteBtn.style.justifyContent = "center";

                deleteBtn.addEventListener("click", () => {
                    wrapper.remove();
                });

                wrapper.appendChild(img);
                wrapper.appendChild(deleteBtn);
                previewContainer.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
        });
    });
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("edit_images");
    const previewContainer = document.getElementById("editImagesPreview");

    if (!imageInput || !previewContainer) return;

    imageInput.addEventListener("change", function (event) {
        previewContainer.innerHTML = ""; // Clear previous previews

        const selectedImages = Array.from(event.target.files);

        selectedImages.forEach((file) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const wrapper = document.createElement("div");
                wrapper.className = "position-relative me-2 mb-2";

                const img = document.createElement("img");
                img.src = e.target.result;
                img.style.width = "100px";
                img.style.height = "100px";
                img.style.objectFit = "cover";
                img.style.borderRadius = "5px";
                img.style.border = "1px solid #ccc";

                wrapper.appendChild(img);
                previewContainer.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
        });
    });
});
</script>
<script>
function customAmenityField(edit = false) {
    const container = document.getElementById(edit ? 'editCustomAmenitiesContainer' : 'addCustomAmenitiesContainer');
    const index = container.children.length;

    // unique prefix for edit vs add
    const prefix = edit ? 'edit' : 'add';

    const wrapper = document.createElement('div');
    wrapper.className = "row mb-2";

    wrapper.innerHTML = `
        <div class="col-md-3">
            <select name="${edit ? 'edit_custom_amenities' : 'add_custom_amenities'}[${index}][category]" 
                    class="form-control" required>
                <option value="">-- Select Category --</option>
                <option value="Bathroom">Bathroom</option>
                <option value="Bedroom and Laundry">Bedroom and Laundry</option>
                <option value="Entertainment">Entertainment</option>
                <option value="Family">Family</option>
                <option value="Heating and Cooling">Heating and Cooling</option>
                <option value="Home Safety">Home Safety</option>
                <option value="Internet and Office">Internet and Office</option>
                <option value="Kitchen and Dining">Kitchen and Dining</option>
                <option value="Location Features">Location Features</option>
                <option value="Outdoor">Outdoor</option>
                <option value="Parking and Facilities">Parking and Facilities</option>
            </select>
        </div>

        <div class="col-md-5">
            <input type="text" name="${edit ? 'edit_custom_amenities' : 'add_custom_amenities'}[${index}][amenity]" 
                   class="form-control" placeholder="Amenity" required>
        </div>
       <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" 
                    name="${edit ? 'edit_custom_amenities' : 'add_custom_amenities'}[${index}][icon]"
                    id="${prefix}IconInput${index}" placeholder="Click to pick icon" readonly>
                <button type="button" class="btn btn-outline-secondary" onclick="openIconPicker('${prefix}', ${index})">Choose</button>
            </div>
        </div>
        <div class="col-md-12">
            <!-- icon options will appear here -->
            <div class="icon-options mt-2 d-none" id="${prefix}IconOptions${index}">
                <!-- Sleeping / Comfort -->
                <i class="fa-solid fa-bed fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-bed')" title="Bed"></i>
                <i class="fa-solid fa-couch fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-couch')" title="Living Room"></i>
                <i class="fa-solid fa-fan fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-fan')" title="Fan"></i>
                <i class="fa-solid fa-snowflake fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-snowflake')" title="Air Conditioning"></i>
                <i class="fa-solid fa-fire fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-fire')" title="Heating"></i>
                <i class="fa-solid fa-wind fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-wind')" title="Ventilation"></i>

                <!-- Bathroom -->
                <i class="fa-solid fa-shower fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-shower')" title="Shower"></i>
                <i class="fa-solid fa-bath fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-bath')" title="Bathtub"></i>
                <i class="fa-solid fa-toilet fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-toilet')" title="Toilet"></i>
                <i class="fa-solid fa-soap fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-soap')" title="Toiletries"></i>
                <i class="fa-solid fa-sink fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-sink')" title="Sink"></i>
                <i class="fa-solid fa-baby fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-baby')" title="Baby Bath"></i>

                <!-- Food & Kitchen -->
                <i class="fa-solid fa-utensils fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-utensils')" title="Restaurant"></i>
                <i class="fa-solid fa-kitchen-set fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-kitchen-set')" title="Kitchen"></i>
                <i class="fa-solid fa-mug-hot fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-mug-hot')" title="Coffee/Tea Maker"></i>
                <i class="fa-solid fa-wine-bottle fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-wine-bottle')" title="Mini Bar"></i>
                <i class="fa-solid fa-blender fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-blender')" title="Blender"></i>
                <i class="fa-solid fa-ice-cream fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-ice-cream')" title="Freezer"></i>

                <!-- Entertainment -->
                <i class="fa-solid fa-tv fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-tv')" title="TV"></i>
                <i class="fa-solid fa-gamepad fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-gamepad')" title="Game Console"></i>
                <i class="fa-solid fa-music fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-music')" title="Music System"></i>
                <i class="fa-solid fa-film fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-film')" title="Movie Night"></i>

                <!-- Internet / Work -->
                <i class="fa-solid fa-wifi fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-wifi')" title="WiFi"></i>
                <i class="fa-solid fa-laptop fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-laptop')" title="Workspace"></i>
                <i class="fa-solid fa-print fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-print')" title="Printer"></i>

                <!-- Transportation -->
                <i class="fa-solid fa-car fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-car')" title="Parking"></i>
                <i class="fa-solid fa-bicycle fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-bicycle')" title="Bicycles"></i>
                <i class="fa-solid fa-bus fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-bus')" title="Shuttle Service"></i>
                <i class="fa-solid fa-plane fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-plane')" title="Airport Transfer"></i>

                <!-- Safety -->
                <i class="fa-solid fa-lock fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-lock')" title="Safe"></i>
                <i class="fa-solid fa-shield-halved fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-shield-halved')" title="Security"></i>
                <i class="fa-solid fa-fire-extinguisher fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-fire-extinguisher')" title="Fire Extinguisher"></i>
                <i class="fa-solid fa-kit-medical fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-kit-medical')" title="First Aid Kit"></i>
                <i class="fa-solid fa-bell fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-bell')" title="Reception Service"></i>

                <!-- Outdoor -->
                <i class="fa-solid fa-umbrella-beach fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-umbrella-beach')" title="Beach Access"></i>
                <i class="fa-solid fa-person-swimming fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-person-swimming')" title="Swimming Pool"></i>
                <i class="fa-solid fa-mountain-sun fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-mountain-sun')" title="Scenic View"></i>
                <i class="fa-solid fa-tree fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-tree')" title="Garden"></i>
                <i class="fa-solid fa-hot-tub-person fa-lg m-2" onclick="selectIcon('${prefix}', ${index}, 'fa-solid fa-hot-tub-person')" title="Hot Tub"></i>
            </div>
        </div>
    `;
    container.appendChild(wrapper);
}

function openIconPicker(prefix, index) {
    const picker = document.getElementById(prefix + "IconOptions" + index);
    picker.classList.toggle("d-none"); // toggle visibility
}

function selectIcon(prefix, index, iconClass) {
    const input = document.getElementById(prefix + "IconInput" + index);
    input.value = iconClass;
    // hide options after choosing
    document.getElementById(prefix + "IconOptions" + index).classList.add("d-none");
}
</script>


@endsection
