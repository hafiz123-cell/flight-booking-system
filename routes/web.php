<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\EasebuzzController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoundReviewController;
use App\Http\Controllers\TripjackController;
use App\Models\Airport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoutingController;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('flight.index-flights');
})->name('home');


Route::get('/test-env', function () {
    return response()->json([
        'mode' => env('TRIPJACK_API_MODE'),
        'token_test' => env('TRIPJACK_API_TOKEN_TEST'),
        'url_test' => env('TRIPJACK_URL_TEST'),
        'easebuzz_salt' => env('TRIPJACK_EASEBUZZ_SALT_KEY'),
        'easebuzz_key' => env('TRIPJACK_EASEBUZZ_KEY'),
        'token_live' => env('TRIPJACK_API_TOKEN'),
        'url_live' => env('TRIPJACK_URL'),
    ]);
});

Route::get('/register/page', function () {
    return view('auth.register'); // Assuming this is your register view
})->name('register.show'); // Use a unique name


Route::get('/contact', function () {
    return view('main.contact'); // Assuming this is your register view
})->name('contact'); // Use a unique name

Route::get('/landing', function () {
    return view('main.landing'); // Assuming this is your register view
})->name('register.landing'); // Use a unique name
Route::get('/login/page', function () {
    return view('auth.login'); // Assuming this is your register view
})->name('login.show'); // Use a unique name

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login/page', [AuthController::class, 'loginUser'])->name('login_user_page');
// Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/gofly/search', [TripjackController::class, 'search'])->name('tripjack.search');
Route::get('/gofly/search/roundtrip', [TripjackController::class, 'searchRoundTrip'])->name('flight.search.roundtrip');
Route::get('/gofly/search/multicity', [TripjackController::class, 'searchMulticity'])->name('flight.search.multicity');
Route::get('airports/search', function (\Illuminate\Http\Request $request) {
    $query = $request->query('query');
    $results = \DB::table('bravo_flight_api')
        ->where('address', 'like', "%$query%")
        ->limit(5)
        ->get(['name', 'code','address']);
    return response()->json($results);
});
Route::get('/redirect-to-booking', function (Illuminate\Http\Request $request) {
    $itineraryId = $request->query('itineraryId');
    $fareIdentifier = $request->query('fareIdentifier');

    if ($itineraryId && $fareIdentifier) {
        $redirectUrl = "http://127.0.0.1:8000/gofly.com/flight/itinerary/{$itineraryId}?fT={$fareIdentifier}";
        return redirect()->away($redirectUrl); // Laravel's safe external redirect
    }

    return abort(400, 'Missing parameters');
})->name('redirect.booking');

Route::get('/invoice/{bookingId}', [EasebuzzController::class, 'invoice'])->name('invoice.show');

Route::get('/gofly.com/flight/itinerary/{priceId}', [ReviewController::class,'review'])->name('review');
Route::post('/api/flight/review-price', [ReviewController::class, 'reviewFlightPrice']);
Route::get('/gofly.com/flight/paxFix/{priceId}', [ReviewController::class, 'price'])->name('price');
Route::post('/trip.store/review', [ReviewController::class, 'traveller_detail_review'])->name('trip.review');
Route::post('/trip.store', [ReviewController::class, 'traveller_detail'])->name('trip.store');
Route::get('/flight/review_detail/{priceId}', [ReviewController::class, 'review_detail'])->name('review_detail');
Route::get('/flight/review_details/{priceId}', [ReviewController::class, 'review_detail_add_flight'])->name('review_detail_add_flight');
Route::post('/start-payment', [EasebuzzController::class, 'startPayment'])->name('start.payment');
Route::get('/payment-route', [ReviewController::class, 'payment'])->name('payment');
Route::get('/traveller-search', [ReviewController::class, 'search']);
Route::get('/pay/link', [ReviewController::class, 'pay_link'])->name('pay_link');
Route::get('/pay', [EasebuzzController::class, 'pay'])->name('easebuzz.pay');
Route::match(['get', 'post'], '/payment-success', [EasebuzzController::class, 'success'])->name('easebuzz.success');
Route::get('/pay/final', [EasebuzzController::class, 'final_payment'])->name('easebuzz.final.pay');
Route::match(['get', 'post'], '/payment-failure', [EasebuzzController::class, 'failure'])->name('easebuzz.failure');

Route::get('/phone-lengths', [ReviewController::class, 'phone']);
Route::post('/payment/initiateLink', function (Request $request) {
    return response()->json([
        'status' => 1,
        'data' => 'fake-access-key'
    ]);
});


// round review

Route::get('/gofly/flight/itinerary', [RoundReviewController::class, 'reviewRound'])->name('review.round');

Route::post('/gofly/flight/review-price', [RoundReviewController::class, 'review'])
    ->name('flight.reviewPrice');
Route::get('/proceed', [RoundReviewController::class, 'proceed'])->name('proceed');
Route::post('/trip/store/round/review', [RoundReviewController::class, 'traveller_round_review'])->name('trip.review.round');
Route::post('/trip.store/round', [RoundReviewController::class, 'traveller_detail_round'])->name('trip.store.round');
Route::get('/flight/review_details/round/{priceId}', [RoundReviewController::class, 'review_detail_add_flight_round'])->name('review_detail_add_flight_round');
Route::get('/flight/review_detail_round/{priceId}', [RoundReviewController::class, 'review_detail_round'])->name('review_detail_round');
Route::get('/payment-route/round', [RoundReviewController::class, 'payment_round'])->name('payment_round');
Route::get('/pay/link/round', [RoundReviewController::class, 'pay_link_round'])->name('pay_link_round');






//main 
Route::get('/contact', function () {
    return view('main.contact');
})->name('contact');


//admin dashboard
Route::get('gofly/admin_dashboard', function () {
    return view('admin_dashboard.index');
})->middleware('auth')->name('admin.index');
 // optional, if you want to protect it

Route::get('/user/data', [AdminController::class, 'getUserData'])
    ->middleware('auth') // make sure only logged-in users can access
    ->name('user.data');
Route::get('/admin/users/{user}', [AdminController::class, 'show'])->name('admin.users.show');
Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

Route::get('/user/search', [AdminController::class, 'index'])->name('admin.users.index');
 Route::get('/user/management', [AdminController::class, 'view'])->name('admin.user');

Route::get('/user/{id}', [AdminController::class, 'show'])->name('admin.admin.view');
Route::get('/user/{id}/edit', [AdminController::class, 'edit'])->name('admin.admin.edit');
Route::put('/user/{id}', [AdminController::class, 'update'])->name('admin.users.update');
Route::delete('/user/{id}', [AdminController::class, 'destroy'])->name('admin.admin.destroy');


//booking


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
   // Show the configuration page
Route::get('config', [ConfigController::class, 'config'])->name('config');

// Handle Live/Test switch
Route::post('config/set-mode', [ConfigController::class, 'setMode'])->name('config.setMode');

    // Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    // Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    // Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});

Route::get('/user/booking', [BookingController::class, 'index'])->name('admin.bookings.index');
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    // Route::get('', [RoutingController::class, 'index'])->name('root');
    // Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    // Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
