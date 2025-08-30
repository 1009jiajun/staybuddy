<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\XController;
use App\Http\Controllers\UploadController;
Route::get('/', function () {
    return view('home');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/homestays', [HomeController::class, 'index'])->name('homestays');
Route::get('/accommodation/{homestay_id}/book', [AccommodationController::class, 'book'])->name('accommodation.book');
Route::post('/accommodation/{homestay_id}/book/submit', [AccommodationController::class, 'submitBooking'])->name('accommodation.book.submit');
Route::get('/accommodation/{homestay_id}', [AccommodationController::class, 'show'])->name('accommodation.detail');


Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/admin_logout', [AuthController::class, 'admin_logout'])->name('admin_logout');
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [UserController::class, 'profile']);
//     Route::get('/my-bookings', [BookingController::class, 'index']);
// });
// Account Management Routes (assuming you're protecting these with middleware like 'auth')
Route::middleware(['auth'])->group(function () {
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update'); // New POST route
    Route::get('/account/bookings', [AccountController::class, 'bookings'])->name('account.bookings');
    Route::get('/account/favourites', [AccountController::class, 'showFavourites'])->name('account.favourites');
    Route::post('/account/favourites/toggle', [AccountController::class, 'toggle'])->name('account.favourites.toggle');
    Route::get('/account/bookings/{booking}', [AccountController::class, 'getBookingDetails']);
    Route::post('/account/bookings/{booking}/status', [AccountController::class, 'updateBookingStatus']);
    Route::post('/account/bookings/{id}/review', [AccountController::class, 'storeReview']);

});
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
Route::get('/payment/return', [PaymentController::class, 'handleReturn'])->name('payment.return');


Route::post('/save-x-token', [XController::class, 'saveToken']);
Route::post('/post-to-x', [XController::class, 'postToX']);
Route::post('/admin/x-logout', [XController::class, 'logoutFromX'])->name('x_logout');


// OAuth 2.0 login routes
Route::get('/x-auth/redirect', [XController::class, 'redirectToX'])->name('x-auth.redirect');
Route::get('/x-auth/callback', [XController::class, 'handleXCallback'])->name('x-auth.callback');

Route::get('/test-storage', function() {
    try {
        Storage::disk('local')->put('x_token_test.json', 'ok');
        return Storage::disk('local')->exists('x_token_test.json') ? 'Writable' : 'Not writable';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});


// routes/web.php

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard (Analysis Dashboard)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Booking Management
    Route::get('/bookings', [AdminController::class, 'bookingManagement'])->name('booking_management');
    // You might also need routes for viewing/editing individual bookings:
    // Route::get('/bookings/{booking}/edit', [AdminController::class, 'editBooking'])->name('bookings.edit');
    // Route::put('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');

    // Homestay Management
    Route::get('/homestays', [AdminController::class, 'homestayManagement'])->name('homestay_management');
    Route::post('/homestay', [AdminController::class, 'storeHomestay'])->name('homestay.store');
    Route::post('/update-homestay/{id}', [AdminController::class, 'updateHomestay'])->name('admin.homestay.update');
    Route::delete('/homestay/{id}', [AdminController::class, 'deleteHomestay'])->name('homestay.delete');
    // If you plan to use resource controllers:
    // Route::resource('homestays', AdminHomestayController::class); // Make sure you create this controller

    // Timetable / Upcoming Bookings
    Route::get('/timetable', [AdminController::class, 'timetable'])->name('timetable');

    // Social Media Engagement
    Route::get('/social-media', [AdminController::class, 'social_media'])->name('social_media');

    Route::post('/upload-image', [UploadController::class, 'Quill'])->name('upload-image');

    // User Management (if you create a separate AdminUserController)
    // Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    // Route::resource('users', AdminUserController::class); // Make sure you create this controller
});





// Route::get('/home', function () {
//     return view('home');
// })->middleware('auth');
