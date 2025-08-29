<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Make sure this is imported
use App\Models\Homestay;
use App\Models\FavouriteHomestay; // Assuming you have a FavouriteHomestay model for user favorites
use App\Models\Booking; // Assuming you have a Booking model for availability checks
use Carbon\Carbon; // For date handling

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Start a new query for Homestay model, eager loading images
        $query = Homestay::with('images');

        // Filter by location
        if ($request->filled('location')) {
            $location = $request->input('location');
            $query->where(function ($q) use ($location) {
                $q->where('location_city', 'like', '%' . $location . '%')
                  ->orWhere('location_state', 'like', '%' . $location . '%')
                  ->orWhere('title', 'like', '%' . $location . '%'); // Also search in homestay name
            });
        }

        // Filter by guests (adults + children)
        $adults = (int) $request->input('adults', 1); // Default to 1 adult if not provided
        $children = (int) $request->input('children', 0); // Default to 0 children if not provided
        $totalGuests = $adults + $children;

        if ($totalGuests > 0) {
            $query->where('max_guests', '>=', $totalGuests);
        }

        // Filter by check-in and check-out dates for availability
        $checkInDate = $request->input('check_in_date');
        $checkOutDate = $request->input('check_out_date');

        if ($checkInDate && $checkOutDate) {
            // Convert dates to Carbon instances for easier comparison
            $start = Carbon::parse($checkInDate)->startOfDay();
            $end = Carbon::parse($checkOutDate)->endOfDay();

            // Filter out homestays that have conflicting bookings
            $query->whereDoesntHave('bookings', function ($bookingQuery) use ($start, $end) {
                $bookingQuery->where(function ($q) use ($start, $end) {
                    // Check for bookings that overlap with the requested dates
                    $q->whereBetween('check_in_date', [$start, $end->subDay()]) // Booking starts within range (inclusive of check-in)
                      ->orWhereBetween('check_out_date', [$start->addDay(), $end]) // Booking ends within range (inclusive of check-out)
                      ->orWhere(function ($q2) use ($start, $end) { // Booking spans entire range
                          $q2->where('check_in_date', '<', $start)
                             ->where('check_out_date', '>', $end);
                      });
                })->whereIn('status', ['paid', 'pending']); // Only consider 'paid' or 'pending' bookings for conflicts
            });
        }

        // Get the filtered homestays
        $homestays = $query->paginate(20); // Example: paginate 12 results per page. Or use ->get()

        $favoritedHomestayIds = [];

        if (Auth::check()) {
            $user = Auth::user();
            $favoritedHomestayIds = DB::table('favourite_homestays')
                ->where('user_id', $user->user_id)
                ->pluck('homestay_id')
                ->toArray();

           $homestays->each(function ($homestay) use ($favoritedHomestayIds) {
                $homestay->setAttribute('is_favorited', in_array($homestay->homestay_id, $favoritedHomestayIds));
            });
        }


        // Pass the homestays and filters to the view
        return view('home', [
            'homestays' => $homestays,
            'filters' => [
                'location' => $request->input('location'),
                'adults' => $adults,
                'children' => $children,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
            ]
        ]);
    }
}