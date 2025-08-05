<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TripjackController;
use App\Models\Airport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

Route::get('/gofly.com/flight/itinerary/{priceId}', [ReviewController::class,'review'])->name('review');
Route::post('/api/flight/review-price', [ReviewController::class, 'reviewFlightPrice']);
