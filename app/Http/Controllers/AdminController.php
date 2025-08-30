<?php

namespace App\Http\Controllers; // Make sure the namespace is correct

use App\Http\Controllers\Controller; // Extend the base Controller
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // For authenticated user info
use App\Models\User; // For user analysis
use App\Models\Homestay; // For homestay analysis and management
use App\Models\Booking; // For booking management and timetable
use App\Models\HomestayAmenity; // For homestay amenities
use Carbon\Carbon; // For date manipulation
use Illuminate\Support\Facades\Storage; // ← add this line

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard (Analysis Dashboard).
     */
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalHomestays = Homestay::count();
        $totalBookings = Booking::count();
        $totalCompletedBookings = Booking::where('status', 'completed')->count();
        $totalPendingBookings = Booking::where('status', 'pending')->count();
        $totalRevenue = Booking::where('status', 'completed')->sum('total_amount');

        // Filter values from dropdown
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year', now()->year); // default to current year

        $monthlyBookings = 0;
        $monthlyRevenue = 0;
        $yearlyBookings = 0;
        $yearlyRevenue = 0;

        // Monthly
        if ($selectedMonth) {
            $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
            $endOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

            $monthlyBookings = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $monthlyRevenue = Booking::where('status', 'completed')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('total_amount');
        }

        // Yearly
        if ($selectedYear) {
            $startOfYear = Carbon::create($selectedYear)->startOfYear();
            $endOfYear = Carbon::create($selectedYear)->endOfYear();

            $yearlyBookings = Booking::whereBetween('created_at', [$startOfYear, $endOfYear])->count();
            $yearlyRevenue = Booking::where('status', 'completed')
                ->whereBetween('created_at', [$startOfYear, $endOfYear])
                ->sum('total_amount');
        }

        $recentHomestays = Homestay::with('images')->latest()->take(5)->get();
        $recentBookings = Booking::with(['user:user_id,name,email', 'homestay:homestay_id,title'])->latest()->take(3)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalHomestays',
            'totalBookings',
            'totalCompletedBookings',
            'totalPendingBookings',
            'totalRevenue',
            'monthlyBookings',
            'yearlyBookings',
            'monthlyRevenue',
            'yearlyRevenue',
            'recentHomestays',
            'recentBookings'
        ));
    }



    /**
     * Display the Booking Management page.
     * Allows filtering and pagination.
     */
    public function bookingManagement(Request $request)
    {
        $query = Booking::with(['user', 'homestay']); // Eager load user and homestay details

        // Filter by status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Filter by homestay title/ID
        if ($request->filled('homestay_search')) {
            $search = $request->input('homestay_search');
            $query->whereHas('homestay', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%'); // Assuming homestay ID can be searched
            });
        }

        // Filter by user email/name
        if ($request->filled('user_search')) {
            $search = $request->input('user_search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Date range filtering (e.g., by check-in date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('check_in_date', [$startDate, $endDate]);
        }

        $bookings = $query->latest('created_at')->paginate(10); // Order by most recent booking creation

        return view('admin.booking_management', compact('bookings'));
    }

    /**
     * Display the Homestay Management page.
     * Allows filtering and pagination.
     */
    public function homestayManagement(Request $request)
    {
        $query = Homestay::with('images'); 

        // Filter by title or location
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('location_city', 'like', '%' . $search . '%')
                  ->orWhere('location_state', 'like', '%' . $search . '%');
        }

        // Filter by availability status
        if ($request->filled('availability') && $request->input('availability') !== 'all') {
            $isAvailable = ($request->input('availability') === 'available');
            $query->where('is_available', $isAvailable);
        }

        $homestays = $query->latest('created_at')->paginate(10);
        $amenities = HomestayAmenity::all(); // Fetch all amenities for the dropdown

        return view('admin.homestay_management', compact('homestays', 'amenities'));
    }

    /**
     * Display a Timetable/Upcoming Bookings overview.
     * This will be a simple list of upcoming bookings for now.
     * A real "timetable" often involves a calendar library on the frontend.
     */
    public function timetable(Request $request)
    {
        // $query = Booking::with(['user', 'homestay'])
        //                 ->where('check_out_date', '>=', Carbon::now()->startOfDay()) // Only upcoming or current bookings
        //                 ->whereIn('status', ['pending', 'completed']); // Only confirmed or awaiting confirmation

        // // Filter by specific date if requested
        // if ($request->filled('date')) {
        //     $selectedDate = Carbon::parse($request->input('date'))->startOfDay();
        //     $query->where(function ($q) use ($selectedDate) {
        //         $q->where('check_in_date', '<=', $selectedDate)
        //           ->where('check_out_date', '>=', $selectedDate);
        //     });
        // }

        $bookings = Booking::with('user', 'homestay')->get();

        // $upcomingBookings = $query->orderBy('check_in_date', 'asc')->paginate(10);

        return view('admin.timetable', compact('bookings'));
    }

    public function storeHomestay(Request $request)
    {   
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_city' => 'required|string|max:255',
            'location_state' => 'required|string|max:255',
            'location_country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'cleaning_fee' => 'required|numeric|min:0',
            'room_type' => 'required|string',
            'bedrooms' => 'required|integer|min:0',
            'beds' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'check_in_time' => 'required|string|max:255',
            'check_out_time' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'tags' => 'nullable|string',
            'rules' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'amenity_ids' => 'required|array|min:1',
            'amenity_ids.*' => 'exists:homestay_amenities,id',
        ]);

        $homestay = Homestay::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'location_city' => $request->input('location_city'),
            'location_state' => $request->input('location_state'),
            'location_country' => $request->input('location_country'),
            'address' => $request->input('address'),
            'price_per_night' => $request->input('price_per_night'),
            'cleaning_fee' => $request->input('cleaning_fee'),
            'room_type' => $request->input('room_type'),
            'bedrooms' => $request->input('bedrooms'),
            'beds' => $request->input('beds'),
            'bathrooms' => $request->input('bathrooms'),
            'max_guests' => $request->input('max_guests'),
            'check_in_time' => $request->input('check_in_time'),
            'check_out_time' => $request->input('check_out_time'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'tags' => $request->input('tags'),
            'rules' => $request->input('rules'),
            'is_available' => true, // Default value, can adjust later
            'host_id' => Auth::id(), // Assuming the admin is the host
            'amenity_ids' => array_map('intval', $request->input('amenity_ids')),
            'homestay_id' => (string) \Str::uuid(), // Generate a UUID for the homestay
            'rating_avg' => 0, // Default rating
            'reviews_count' => 0, // Default reviews count
            'cancellation_policy' => 'flexible', // Default cancellation policy
            'created_at' => now()->addHours(8),
            'updated_at' => now()->addHours(8),
        ]);

        // Handle custom amenities
        $customAmenities = array_merge(
            $request->input('custom_amenities', []),
            $request->input('add_custom_amenities', []),
            $request->input('edit_custom_amenities', [])
        );

        $newAmenityIds = [];
        foreach ($customAmenities as $amenity) {
            $newAmenity = HomestayAmenity::firstOrCreate(
                [
                    'category' => $amenity['category'],
                    'amenity'  => $amenity['amenity'],
                ],
                [
                    'icon'     => $amenity['icon'] ?? null,
                ]
            );
            $newAmenityIds[] = $newAmenity->id;
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('assets/homestay', 'public');
                $homestay->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        // Merge new amenities into homestay->amenity_ids
        $currentAmenities = $homestay->amenity_ids ?? [];
        $updatedAmenities = array_unique(array_merge($currentAmenities, $newAmenityIds));

        // Save back to homestay
        $homestay->amenity_ids = $updatedAmenities;
        $homestay->save();

        return redirect()->route('admin.homestay_management')->with('success', 'Homestay added successfully!');
    }


    /**
     * Update an existing homestay.
     */
    public function updateHomestay(Request $request, $id)
    {
        $homestay = Homestay::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_city' => 'required|string|max:255',
            'location_state' => 'required|string|max:255',
            'location_country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'cleaning_fee' => 'required|numeric|min:0',
            'room_type' => 'required|string',
            'bedrooms' => 'required|integer|min:0',
            'beds' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'check_in_time' => 'required|string|max:255',
            'check_out_time' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'tags' => 'nullable|string',
            'rules' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'amenity_ids' => 'required|array',
            'amenity_ids.*' => 'exists:homestay_amenities,id',
        ]);

        $homestay->update($request->only([
            'title', 'description', 'location_city', 'location_state',
            'location_country', 'address', 'price_per_night', 'cleaning_fee',
            'room_type', 'bedrooms', 'beds', 'bathrooms', 'max_guests',
            'check_in_time', 'check_out_time', 'latitude', 'longitude',
            'tags', 'rules'
        ]) + [
            'updated_at' => now()->addHours(8),
        ]);

        $customAmenities = array_merge(
            $request->input('custom_amenities', []),
            $request->input('add_custom_amenities', []),
            $request->input('edit_custom_amenities', [])
        );

        $newAmenityIds = [];
        foreach ($customAmenities as $amenity) {
            $newAmenity = HomestayAmenity::firstOrCreate(
                [
                    'category' => $amenity['category'],
                    'amenity'  => $amenity['amenity'],
                ],
                [
                    'icon' => $amenity['icon'] ?? null,
                ]
            );
            $newAmenityIds[] = $newAmenity->id;
        }


        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($homestay->images as $image) {
                \Storage::disk('public/assets/homestay/')->delete($image->image_url);
                $image->delete();
            }

            foreach ($request->file('images') as $image) {
                $path = $image->store('assets/homestay', 'public');
                $homestay->images()->create(['image_url' => $path]);
            }
        }

        // Merge new amenities into homestay->amenity_ids
        $currentAmenities = $request->input('amenity_ids');
        $updatedAmenities = array_unique(array_merge($currentAmenities, $newAmenityIds));

        // Save back to homestay
        $homestay->amenity_ids = $updatedAmenities;
        $homestay->save();

        return redirect()->route('admin.homestay_management')->with('success', 'Homestay updated successfully!');
    }

    /**
     * Delete an existing homestay.
     */
    public function deleteHomestay($id)
    {
        $homestay = Homestay::findOrFail($id);

        foreach ($homestay->images as $image) {
            \Storage::disk('public/assets/homestay/')->delete($image->image_url);
            $image->delete();
        }

        $homestay->delete();

        return response()->json(['success' => true, 'message' => 'Homestay deleted successfully!']);
    }

    public function social_media()
    {
        // Check if token exists
        $hasToken = Storage::disk('local')->exists('x_token.json');

        return view('admin.social_media', compact('hasToken'));        
    }

}