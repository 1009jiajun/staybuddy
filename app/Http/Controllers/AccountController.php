<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // For database operations
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Validation\Rule;           
use Illuminate\Support\Facades\Storage;   
use App\Models\Booking; // Assuming you have a Booking model
use App\Models\Homestay; // Assuming you have a Homestay model for favourites
use App\Models\FavouriteHomestay; // Assuming you have a FavouriteHomestay model
use App\Models\HomestayReview;

class AccountController extends Controller
{
    public function profile()
    {
        // This will display the profile section of account_management.blade.php
        // You might fetch user data if needed, but Auth::user() is usually available.
        return view('account_management'); // Or return view('account.profile') if you break it into smaller views
    }

     public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:12', // Assuming you have a phone_number field
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->getKey(), $user->getKeyName())
            ],
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // 2. Update User Data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phoneNo = $request->input('phoneNo'); // Assuming you have a phone_number field
        $user->updated_at = now(); // Update the timestamp

        // 3. Handle Profile Image Upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if it exists and is not a URL
            if ($user->profile_image && !filter_var($user->profile_image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Profile updated successfully!', 'profile_image_url' => asset($user->profile_image)]);
    }

    public function bookings()
    {
        // Fetch bookings for the authenticated user
        $user = Auth::user();
       $bookings = Booking::where('user_id', $user->user_id)
                   ->with('homestay') // just load homestay
                   ->latest()
                   ->get();

        // Optional: eager load 1st image separately (if needed in view)
        foreach ($bookings as $booking) {
            $booking->image_url = \DB::table('homestay_images')
                ->where('homestay_id', $booking->homestay_id)
                ->value('image_url'); // or 'path' if it's named that
        }


        // This will also load account_management.blade.php
        // You would typically pass $bookings to a sub-view or use JavaScript to render.
        // For a simpler setup, you might have separate blade files for each section.
        return view('account_management', compact('bookings')); // Pass bookings data
    }


    // Define the many-to-many relationship with Homestay through favourite_homestays table
    public function favouriteHomestays()
    {
        return $this->belongsToMany(Homestay::class, 'favourite_homestays', 'user_id', 'homestay_id')
                    ->withTimestamps() // If you want updated_at/created_at on the pivot table
                    ->withPivot('added_at'); // To access the 'added_at' column
    }

    // You can also define a hasMany relationship to the pivot model itself if you need to access pivot data directly
    public function favourites()
    {
        return $this->hasMany(FavouriteHomestay::class, 'user_id', 'uuid'); // Assuming user's PK is 'uuid'
    }

    public function getBookingDetails(Booking $booking)
    {
        // Policy check: Ensure the authenticated user owns this booking
        if (Auth::id() !== $booking->user_id) { // Assuming Auth::id() returns the user's UUID
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // Eager load necessary relationships for the modal
        $booking->load(['homestay.images']);

        // Prepare data for JSON response, including homestay title and a single image URL
        $homestayTitle = $booking->homestay->title ?? 'N/A';
        $homestayLocation = $booking->homestay->location_city ?? 'N/A'; // Assuming 'location' exists on Homestay
        $homestayImageUrl = asset($booking->homestay->images->first()->image_url ?? ''); // Use asset() to get the full URL

        //fetch rating and reviews  based on reviewId if exists
        if ($booking->reviewId) {
            $review_id = $booking->reviewId; // Assuming reviewId is the ID of the HomestayReview
            $review = HomestayReview::where('review_id', $review_id)->first();
            if ($review) {
                $booking->rating = $review->rating;
                $booking->review = $review->review_text;
            } else {
                $booking->rating = null;
                $booking->review = null;
            }
        } else {
            $booking->rating = null;
            $booking->review = null;
        }

        // Return the data as JSON
        return response()->json([
            'id' => $booking->id,
            'homestay_id' => $booking->homestay_id,
            'user_id' => $booking->user_id,
            'check_in_date' => $booking->check_in_date,
            'check_out_date' => $booking->check_out_date,
            'total_guests' => $booking->total_guests,
            'total_amount' => $booking->total_amount,
            'external_reference' => $booking->external_reference,
            'toyyibpay_bill_code' => $booking->toyyibpay_bill_code,
            'transaction_id' => $booking->transaction_id,
            'paid_at' => $booking->paid_at,
            'status' => $booking->status,
            'created_at' => $booking->created_at,
            'updated_at' => $booking->updated_at,
            'homestay_title' => $homestayTitle,
            'homestay_location' => $homestayLocation,
            'homestay_image_url' => $homestayImageUrl,
            'rating' => $booking->rating, // Add rating if exists
            'review' => $booking->review, // Add review if exists
        ]);
    }

    /**
     * Update the status of a specific booking.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        // Policy check: Ensure the authenticated user owns this booking
        if (Auth::id() !== $booking->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $newStatus = $request->input('status');

        // Validate the status (basic validation, add more as needed)
        if (!in_array($newStatus, ['cancelled', 'completed'])) {
            return response()->json(['success' => false, 'message' => 'Invalid status provided.'], 400);
        }

        // Add logic to prevent inappropriate status transitions
        // E.g., cannot cancel a completed booking, or complete an already cancelled booking
        if ($booking->status === 'cancelled' || $booking->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Booking is already in a final state and cannot be updated.'], 400);
        }

        try {
            $booking->status = $newStatus;
            $booking->save();
            return response()->json(['success' => true, 'message' => 'Booking status updated to ' . $newStatus]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating status.'], 500);
        }
    }

    public function showFavourites()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your favourites.');
        }

        $user = Auth::user();

        // Retrieve favourite homestays for the authenticated user
        // Using the belongsToMany relationship for simplicity and eager loading
        $favouriteHomestays = $user->favouriteHomestays()
                                   ->with('images') // Eager load homestay images for display
                                   ->orderBy('pivot_added_at', 'desc') // Order by when they were favorited
                                   ->get();

        // The Blade snippet uses $favouriteHomestays, so pass it this way.
        return view('account_management', compact('favouriteHomestays'));
    }

    // NEW: Add/Remove Favourite Endpoint (Optional, but good for interactivity)

    public function toggle(Request $request)
    {
        // Always good to explicitly check authentication within the method as well
        // even if middleware is used, for clearer error handling for AJAX requests.
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        $user = Auth::user(); // Get the authenticated user model
        $homestayId = $request->homestay_id;
        $loggedInUserId = $request->user_id; // Assuming 'user_id' is the primary key in your User model

        // Determine the correct user ID property based on your User model's primary key
        // $userIdProperty = 'id'; // Default to 'id'
        // if (property_exists($user, 'uuid') && !empty($user->uuid)) {
        //     $userIdProperty = 'uuid'; // Use 'uuid' if your User model has it
        // }
        //If your User model explicitly uses 'user_id' as its PK:
        if (property_exists($user, 'user_id') && !empty($user->user_id)) {
            $userIdProperty = 'user_id';
        }

        // $loggedInUserId = $user->$userIdProperty; // This will be $user->id or $user->uuid

        // Validate the homestay_id if necessary, e.g., ensure it exists
        // $homestay = Homestay::find($homestayId);
        // if (!$homestay) {
        //     return response()->json(['message' => 'Homestay not found.'], 404);
        // }

        $favourite = FavouriteHomestay::where('user_id', $loggedInUserId) // Use the correct user ID
                                       ->where('homestay_id', $homestayId)
                                       ->first();

        if ($favourite) {
            DB::table('favourite_homestays')
            ->where('user_id', $loggedInUserId)
            ->where('homestay_id', $homestayId)
            ->delete();

            return response()->json(['message' => 'Removed from favourites', 'action' => 'removed']);
        } else {
            FavouriteHomestay::create([
                'user_id' => $loggedInUserId, // Use the correct user ID
                'homestay_id' => $homestayId,
                'added_at' => now()->timezone('Asia/Kuala_Lumpur'), // Use Carbon's timezone for accuracy
            ]);
            return response()->json(['message' => 'Added to favourites', 'action' => 'added']);
        }
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'review' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5', // Assuming rating is an integer between 1 and 5
        ]);

        $HomestayReview = new HomestayReview();

        $HomestayReview->homestay_id = $request->homestayId;
        $userName = auth()->user()->name; // Assuming you have a user model with a name attribute
        $HomestayReview->user_name = $userName; // Store the user's name in the review
        $HomestayReview->review_text = $request->review;
        $HomestayReview->rating = $request->rating; // Assuming you pass rating in the request
        $HomestayReview->review_date = now()->timezone('Asia/Kuala_Lumpur'); // Use Carbon's timezone for accuracy
        $HomestayReview->save();

        // Link the review to the booking (assuming the booking has a reviewId field)
        $booking = Booking::findOrFail($id);
        $booking->reviewId = $HomestayReview->id; // assign the newly created review's ID
        $booking->save();

        return response()->json(['success' => true]);
    }


}