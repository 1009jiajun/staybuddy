<?php
namespace App\Http\Controllers;

use App\Models\Homestay;
use App\Models\HomestayAmenity;
use App\Models\Booking; // <-- Import the Booking model
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; // <-- Import Auth facade
use Illuminate\Support\Facades\Log; // <-- Import Log facade
use Illuminate\Support\Facades\DB;

class AccommodationController extends Controller
{
    public function show($homestay_id)
    {
        // Fetch homestay with relationships (eager loading)
        $homestay = Homestay::with(['images', 'reviews'])
            ->findOrFail($homestay_id);

        // Decode amenity_ids safely (fallback to empty array if null)
        $amenityIds = [];
        if (is_string($homestay->amenity_ids)) {
            $amenityIds = json_decode($homestay->amenity_ids, true) ?? [];
        } elseif (is_array($homestay->amenity_ids)) {
            $amenityIds = $homestay->amenity_ids;
        }


        // Get amenities and group by category
        $amenities = HomestayAmenity::whereIn('id', $amenityIds)->get();
        $groupedAmenities = $amenities->groupBy('category');

        $favoritedHomestayIds = [];

        if (Auth::check()) {
            $user = Auth::user();
            $favoritedHomestayIds = DB::table('favourite_homestays')
                ->where('user_id', $user->user_id)
                ->pluck('homestay_id')
                ->toArray();

            // Check if the current homestay is favorited by the user
            $homestay->is_favorited = in_array($homestay->homestay_id, $favoritedHomestayIds);
        }

        $reservedDates = DB::table('bookings')
            ->where('homestay_id', $homestay_id)
            ->get(['check_in_date', 'check_out_date']);

        $disabledDates = [];

        foreach ($reservedDates as $booking) {
            $start = Carbon::parse($booking->check_in_date);
            $end = Carbon::parse($booking->check_out_date)->subDay(); // don't block the checkout day
            while ($start->lte($end)) {
                $disabledDates[] = $start->format('Y-m-d');
                $start->addDay();
            }
        }

        // Step 2: Find the next available date
        $today = Carbon::today();
        $nextAvailableDate = $today->copy();
        while (in_array($nextAvailableDate->format('Y-m-d'), $disabledDates)) {
            $nextAvailableDate->addDay();
        }

        // Pass to view
        $nextAvailable = $nextAvailableDate->format('Y-m-d');


        return view('accommodation_detail', compact(
            'homestay',
            'amenities',
            'groupedAmenities',
            'favoritedHomestayIds',
            'disabledDates',
            'nextAvailable'
        ));
    }

    public function book($homestay_id)
    {
        // Fetch homestay with relationships (eager loading)
        $homestay = Homestay::with(['images', 'reviews'])
            ->findOrFail($homestay_id);

        $guests = request()->query('guests');
        $checkin = Carbon::parse(request()->query('checkin'));
        $checkout = Carbon::parse(request()->query('checkout'));
        $nights = $checkin->diffInDays($checkout);

        return view('accommodation_book', compact('homestay', 'checkin', 'checkout', 'guests', 'nights'));
    }

public function submitBooking($homestay_id)
    {
        Log::info('--- Starting submitBooking for Homestay ID: ' . $homestay_id . ' ---');

        // Ensure user is authenticated
        if (!Auth::check()) {
            Log::warning('submitBooking: User not authenticated. Redirecting to login.');
            return back()->with('error', 'Please log in to make a booking.');
        }

        $user = Auth::user();
        Log::debug('submitBooking: Authenticated user ID: ' . $user->user_id . ', Email: ' . $user->email);

        $homestay = Homestay::findOrFail($homestay_id);
        $homestay_name = $homestay->title;
        Log::debug('submitBooking: Homestay found: ' . $homestay_name . ' (ID: ' . $homestay->id . ')');


        try {
            // Validate the request
            $validatedData = request()->validate([
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'total_guests' => 'required|integer|min:1|max:' . $homestay->max_guests,
                'total_price' => 'required|numeric|min:1',
                'user_email' => 'required|email',
                'user_name' => 'required|string|max:255',
            ]);
            Log::info('submitBooking: Request data validated successfully.');
            Log::debug('submitBooking: Validated Data: ' . json_encode($validatedData));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('submitBooking: Validation failed. Errors: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput(); // Return with validation errors
        } catch (\Exception $e) {
            Log::error('submitBooking: An unexpected error occurred during validation: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during booking validation.');
        }


        // Generate a unique reference number for the booking
        $externalReferenceNo = 'BOOK-' . Str::upper(Str::random(10));
        Log::debug('submitBooking: Generated external reference: ' . $externalReferenceNo);

        // 1. Create the booking record with 'unpaid' status
        try {
            $booking = Booking::create([
                'homestay_id' => $homestay_id, // Assuming homestay_id is the correct bigint/integer
                'user_id' => $user->user_id, // Assuming user->id is the correct bigint/integer
                'check_in_date' => $validatedData['check_in_date'],
                'check_out_date' => $validatedData['check_out_date'],
                'total_guests' => $validatedData['total_guests'],
                'total_amount' => $validatedData['total_price'],
                'status' => 'unpaid',
                'external_reference' => $externalReferenceNo,
                // now add 8 hours to the current time for 'created_at'
                'created_at' => Carbon::now()->addHours(8),
                'updated_at' => Carbon::now()->addHours(8),
                'paid_at' => Carbon::now()->addHours(8), // Set paid_at to current time + 8 hours
            ]);
            Log::info('submitBooking: Booking record created with ID: ' . $booking->id . ' and status: ' . $booking->status);
        } catch (\Exception $e) {
            Log::error('submitBooking: Failed to create booking record: ' . $e->getMessage());
            return back()->with('error', 'Failed to create booking record. Please try again.');
        }


        // Convert RM to cent format for ToyyibPay
        $amountInCents = (int) round($validatedData['total_price'] * 100);
        Log::debug('submitBooking: Total price (RM): ' . $validatedData['total_price'] . ', Amount in Cents: ' . $amountInCents);

        $maxHomestayNameLength = 50; // Or 54 if you don't use '...' or ensure it's exactly 3 chars

        $limitedHomestayName = Str::limit($homestay->title, $maxHomestayNameLength, '...');

        // Process Payment (ToyyibPay)
        $billData = [
            'userSecretKey' => env('TOYYIBPAY_SECRET_KEY', 'vio7yufl-xk3t-o5is-3ahz-2n2ccly9gk96'),
            'categoryCode' => env('TOYYIBPAY_CATEGORY_CODE', 'um0ubxse'),
            'billName' => Str::limit(Carbon::parse($validatedData['check_in_date'])->format('Ymd') . '-' . $homestay_name, 30, ''),
            'billDescription' => 'Reservation for ' . $limitedHomestayName . ' from ' . $validatedData['check_in_date'] . ' to ' . $validatedData['check_out_date'],
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => $amountInCents,
            'billReturnUrl' => route('payment.return', ['booking_id' => $booking->id, 'external_ref' => $externalReferenceNo]),
            'billCallbackUrl' => route('payment.callback', ['booking_id' => $booking->id, 'external_ref' => $externalReferenceNo]),
            'billExternalReferenceNo' => $externalReferenceNo,
            'billTo' => $validatedData['user_name'],
            'billEmail' => $validatedData['user_email'],
            'billPhone' => $user->phoneNo ?? '00-00000000',
            'billSplitPayment' => 0,
            'billPaymentChannel' => '2',
            'billContentEmail' => 'Thank you for your booking. Please make payment to confirm your reservation.',
            'billChargeToCustomer' => '',
            'billExpiryDays' => 1
        ];

        Log::debug('submitBooking: ToyyibPay Bill Data prepared: ' . json_encode($billData));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://dev.toyyibpay.com/index.php/api/createBill');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $billData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        Log::info('submitBooking: ToyyibPay raw response: ' . $response);

        // Check for curl errors BEFORE closing
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error('submitBooking: Curl error during ToyyibPay API call: ' . $error);
            $booking->delete(); // Delete the created booking record
            return back()->with('error', 'Failed to connect to payment gateway: ' . $error);
        }

        curl_close($ch);

        // Decode response
        $bill = json_decode($response, true);

        // Check if ToyyibPay responded with a bill
        if (!is_array($bill) || !isset($bill[0]['BillCode'])) {
            Log::error('submitBooking: ToyyibPay createBill API response invalid. Response: ' . $response);
            $booking->delete(); // Delete the created booking record
            return back()->with('error', 'Failed to create payment bill. Payment gateway response was invalid.');
        }

        $billCode = $bill[0]['BillCode'];
        Log::info('submitBooking: ToyyibPay Bill Code received: ' . $billCode);

        // 2. Update the booking record with ToyyibPay BillCode
        try {
            $booking->update([
                'toyyibpay_bill_code' => $billCode,
                'status' => 'pending', // Status is 'pending' once payment process is initiated
            
            ]);
            Log::info('submitBooking: Booking record updated with ToyyibPay BillCode and status "pending".');
        } catch (\Exception $e) {
            Log::error('submitBooking: Failed to update booking record with BillCode: ' . $e->getMessage());
            // Decide how to handle this critical error:
            // - Redirect with error, but booking might still be in 'unpaid' state and payment link exists
            // - Maybe attempt to retry updating, or alert admin
            return redirect("https://dev.toyyibpay.com/{$billCode}")->with('warning', 'Booking payment initiated, but an issue occurred saving payment reference. Please check your booking history later.');
        }

        // Redirect to ToyyibPay payment page
        Log::info('submitBooking: Redirecting user to ToyyibPay: ' . "https://dev.toyyibpay.com/{$billCode}");
        return redirect("https://dev.toyyibpay.com/{$billCode}");
    }
}