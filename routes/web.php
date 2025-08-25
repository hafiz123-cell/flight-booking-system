<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\EasebuzzController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoundReviewController;
use App\Http\Controllers\TripjackController;
use App\Models\Airport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\RoutingController;


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
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']);
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

