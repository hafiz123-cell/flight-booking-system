<?php
namespace App\Http\Controllers;

use App\Models\FlightFareRule;
use App\Models\FlightPrice;
use App\Models\FlightReturn;
use App\Models\Payment;
use App\Models\TravellerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use App\Models\FlightDetail;
use App\Models\BookingList;

class EasebuzzController extends Controller
{
    public function pay()
    {
        $key  = config('services.easebuzz.key');
        $salt = config('services.easebuzz.salt_key');
        $env  = config('services.easebuzz.mode');

        require_once app_path('Libraries/easebuzz-lib/easebuzz_payment_gateway.php');
        $easebuzz = new \Easebuzz($key, $salt, $env);

        $paymentData = Session::get('easebuzz_payment_data');

        if (!$paymentData || empty($paymentData['contact']['email']) || empty($paymentData['contact']['mobile'])) {
            return redirect()->back()->with('error', 'Missing traveller details for payment.');
        }

        $amount = $paymentData['amount'];

        if ($amount <= 0) {
            return redirect()->back()->with('error', 'Invalid payment amount.');
        }

        $priceId = $paymentData['price_id'] ?? 'N/A';
        $priceIdSanitized = preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $priceId);

        $productInfo = 'Trip Booking - Price ID ' . $priceIdSanitized;
        $productInfo = substr($productInfo, 0, 100);

        $postData = [
            'txnid'       => uniqid(),
            'amount'      => number_format($amount, 2, '.', ''),
            'firstname'   => $paymentData['passenger']['first_name'] ?? 'Guest',
            'email'       => $paymentData['contact']['email'],
            'phone'       => $paymentData['contact']['mobile'],
            'productinfo' => 'Test',
            'surl'        => route('easebuzz.success') . '?bkId=' . $paymentData['bookingId'],
            'furl'        => route('easebuzz.failure'),
        ];

        return $easebuzz->initiatePaymentAPI($postData);
    }

public function success(Request $request)
{
    $paymentData = $request->all();

    // ✅ Handle GET request without txnid (direct thank-you page)
    if ($request->isMethod('get') && empty($paymentData['txnid'])) {
        return view('thank-you', [
            'bookingId' => $request->query('bkId')
        ]);
    }

    // ✅ Easebuzz credentials
    $merchantKey = config('services.easebuzz.key');
    $salt        = config('services.easebuzz.salt_key');

    // ✅ Extract payment fields
    $status      = $paymentData['status'] ?? '';
    $email       = $paymentData['email'] ?? '';
    $firstname   = $paymentData['firstname'] ?? '';
    $productinfo = $paymentData['productinfo'] ?? '';
    $amount      = $paymentData['amount'] ?? '';
    $txnid       = $paymentData['txnid'] ?? '';

    // ✅ Verify hash
    $hashString = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$merchantKey;
    $calculatedHash = strtolower(hash('sha512', $hashString));

    if (!isset($paymentData['hash']) || $calculatedHash !== strtolower($paymentData['hash'])) {
        return response()->json(['error' => 'Hash verification failed'], 400);
    }

    //  Save payment record
    Payment::create([
        'txnid'        => $txnid,
        'easepayid'    => $paymentData['easepayid'] ?? null,
        'status'       => $status,
        'amount'       => number_format($amount, 2, '.', ''),
        'productinfo'  => $productinfo,
        'firstname'    => $firstname,
        'email'        => $email,
        'phone'        => $paymentData['phone'] ?? null,
        'raw_response' => json_encode($paymentData),
    ]);

    // ✅ Save payment record
$payment = Payment::create([
    'txnid'        => $txnid,
    'easepayid'    => $paymentData['easepayid'] ?? null,
    'status'       => $status, // "success" / "failed"
    'amount'       => number_format($amount, 2, '.', ''),
    'productinfo'  => $productinfo,
    'firstname'    => $firstname,
    'email'        => $email,
    'phone'        => $paymentData['phone'] ?? null,
    'raw_response' => json_encode($paymentData),
]);
      Session::put('payment', $payment);   
    //  Retrieve booking ID
    $tripReviewData   = Session::get('trip_review_datas', []);
    $sessionBookingId = $tripReviewData['bookingId'] ?? null;
    $requestBookingId = $request->query('bkId');

    if ($sessionBookingId && $sessionBookingId === $requestBookingId) {
        $bookingId = $sessionBookingId;
    } elseif (!$sessionBookingId && $requestBookingId) {
        $bookingId = $requestBookingId;
    } else {
        return response()->json([
            'error' => 'Booking ID mismatch or missing in session/request.',
            'sessionBookingId' => $sessionBookingId,
            'requestBookingId' => $requestBookingId
        ], 400);
    }


    //  Fetch traveller details
    $detail = TravellerDetail::where('booking_id', $bookingId)->first();

    $passengers = [];
    if ($detail && !empty($detail->passenger_data)) {
        // Decode passenger_data safely
        $decoded = is_string($detail->passenger_data)
            ? json_decode($detail->passenger_data, true)
            : ($detail->passenger_data ?? []);

        if (!is_array($decoded)) {
            $decoded = [];
        }

        // Pax type mapping
        $paxTypeMap = [
            'ADULT'  => 'ADULT',
            'CHILD'  => 'CHILD',
            'INFANT' => 'INFANT'
        ];

        foreach ($decoded as $p) {
            $storedType  = strtoupper($p['type'] ?? 'ADULT');
            $storedTitle = $p['title'] ?? 'Mr';

            // ✅ Handle DOB logic
            $storedDob = ($storedType === 'CHILD' && empty($p['dob']))
                ? now()->subYears(10)->format('Y-m-d')
                : ($p['dob'] ?? '1990-01-01');

            $pt = $paxTypeMap[$storedType] ?? 'ADULT';

            $passengers[] = [
                "ti"    => $storedTitle,
                "fN"    => $p['first_name'] ?? '',
                "lN"    => $p['last_name'] ?? '',
                "pt"    => $pt,
                "pan"   => $p['pan'] ?? 'ABCDE1234F',
                "dob"   => $storedDob,
                "pNat"  => $p['nationality'] ?? 'IN',
                "pNum"  => $p['passport_no'] ?? '87UYITB',
                "eD"    => $p['passport_expiry'] ?? '2030-08-09',
                "pid"   => $p['pid'] ?? '2024-09-08',
            ];
        }
    }

    // ✅ Prepare booking payload for TripJack API
    $bookingPayload = [
        "bookingId" => $bookingId,
        "paymentInfos" => [
            ["amount" => (float) $amount]
        ],
        "travellerInfo" => $passengers,
        "deliveryInfo" => [
            "emails"   => [$email],
            "contacts" => [$paymentData['phone'] ?? '']
        ]
    ];

    try {
        $client = new Client();
        $mode   = config('services.tripjack_token.mode'); // "test" or "live"
        $token  = config("services.tripjack_token.$mode.token");
        $url    = config("services.tripjack_token.$mode.url");

        $apiResponse = $client->post($url . '/oms/v1/air/book', [
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey'       => $token,
            ],
            'json' => $bookingPayload
        ]);

        $apiResult = json_decode($apiResponse->getBody(), true);

        // ✅ Save for later usage
        Session::put('apiResult', $apiResult);
        Session::put('paymentData', $paymentData);

        return redirect()->route('easebuzz.final.pay', [
            'bookingId' => $bookingId,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error'       => $e->getMessage(),
            'paymentData' => $paymentData,
            'bookingId'   => $bookingId
        ], 500);
    }
}


public function final_payment(Request $request)
{
    $bookingId = $request->query('bookingId');

    if (!$bookingId) {
        abort(404, 'Booking ID is missing');
    }

    $apiResult   = Session::get('apiResult');
    $paymentData = Session::get('paymentData');
   
    // Get flight details for this booking
    $flightDetails = FlightDetail::where('booking_id', $bookingId)->get();
       $flightDetail= FlightDetail::where('booking_id', $bookingId)->first();
    // Get all traveller details for this booking
    $travellerDetails = TravellerDetail::where('booking_id', $bookingId)->get();
$payment     = Payment::where('raw_response', 'like', '%"bkId":"'.$bookingId.'"%')->first();
$travellerDetail = TravellerDetail::where('booking_id', $bookingId)->first();
// Make sure payment is found
if (!$payment) {
    return response()->json(['error' => 'Payment not found for bookingId '.$bookingId], 404);
}
    $returnFlights = FlightReturn::whereIn('onward_flight_id', $flightDetails->pluck('id'))->get();
   
$bookingStatus     = $payment->status === 'success' ? 'confirmed' : 'cancelled';
$bookingPayStatus  = $payment->status === 'success' ? 'paid' : 'failed';

//  Save booking
$booking = BookingList::create([
    'user_id'             => auth()->id()??38 , // or auth()->id() Session::get('user_id')
    'flight_detail_id'    => $flightDetail['id'] ?? null,
    'traveller_detail_id' => $travellerDetail['id'] ?? null,
    'payment_id'          => $payment->id,
    'status'              => $bookingStatus,    // dynamic
    'payment_status'      => $bookingPayStatus, // dynamic
]); 
    // Collect passenger details with from/to
    $passengerDetails = [];
    foreach ($travellerDetails as $traveller) {
        $type = $flightDetails->first()->type ?? null;

        $data = $traveller->passenger_data;

        // ✅ Decode if still string
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (is_array($data)) {
            foreach ($data as &$p) {
                $p['from'] = $flightDetails->first()->from_city ?? 'NA';
                $p['to']   = $flightDetails->first()->to_city ?? 'NA';
                $passengerDetails[] = $p;
            }
        }
    }

    // Extract all flight_detail_ids from FlightDetail
    $flightDetailIds = $flightDetails->pluck('id')->toArray();

    // Fetch fare rules & prices for these flights
    $fareRules    = FlightFareRule::whereIn('flight_detail_id', $flightDetailIds)->get();
    $priceDetails = FlightPrice::whereIn('flight_detail_id', $flightDetailIds)->get();

    return view('flight.payments_success', [
        'bookingId'        => $bookingId,
        'flightDetails'    => $flightDetails,
        'returnFlight'     => $returnFlights,
        'passengerDetails' => $passengerDetails,
        'fareRules'        => $fareRules,
        'type'             => $type,
        'priceDetails'     => $priceDetails,
        'contactDetails'   => [
            'email'  => $travellerDetails->first()->email ?? null,
            'mobile' => $travellerDetails->first()->mobile_number ?? null,
        ],
        'apiResult'        => $apiResult,
        'paymentData'      => $paymentData,
    ]);
}


public function invoice($bookingId)
{
    if (!$bookingId) {
        abort(404, 'Booking ID is missing');
    }

    // Fetch onward flight details
    $flightDetails = FlightDetail::where('booking_id', $bookingId)->get();
    $type = $flightDetails->first()->type ?? null;
    $baseOnwardPrice = $flightDetails->first()->price ?? 0; // base price from flight detail

    // Fetch return flight details
    $returnFlights = FlightReturn::whereIn('onward_flight_id', $flightDetails->pluck('id'))->get();
    $baseReturnPrice = $returnFlights->first()->price ?? 0; // base price from return flight

    // Fetch traveller details
    $travellerDetails = TravellerDetail::where('booking_id', $bookingId)->get();

    $passengerDetails = [];
    $adultMeal = $adultBaggage = $childMeal = $childBaggage = 0;

    $firstOnwardSum = 0;
    $firstReturnSum = 0;

    // Prepare passengers and collect meal/baggage info
    foreach ($travellerDetails as $traveller) {
        $data = $traveller->passenger_data;
        if (is_string($data)) $data = json_decode($data, true);

        if (is_array($data)) {
            foreach ($data as &$p) {
                $p['from'] = $flightDetails->first()->from_city ?? 'NA';
                $p['to']   = $flightDetails->first()->to_city ?? 'NA';

                if (!empty($p['flights'])) {
                    foreach ($p['flights'] as $i => &$f) {
                        $f['baggage'] = $f['baggage'] ?? 'NA';
                        $f['baggage_amount'] = $f['baggage_amount'] ?? 0;
                        $f['meal'] = $f['meal'] ?? 'NA';
                        $f['meal_amount'] = $f['meal_amount'] ?? 0;

                        // Aggregate adult/child totals globally
                        if ($p['type'] === 'ADULT') {
                            $adultBaggage += $f['baggage_amount'];
                            $adultMeal    += $f['meal_amount'];
                        } elseif ($p['type'] === 'CHILD') {
                            $childBaggage += $f['baggage_amount'];
                            $childMeal    += $f['meal_amount'];
                        }

                        // First onward flight sum
                        if ($i === 0) {
                            $firstOnwardSum += ($f['meal_amount'] ?? 0) + ($f['baggage_amount'] ?? 0);
                        }

                        // First return flight sum (after onward flights)
                        if ($i === count($flightDetails)) {
                            $firstReturnSum += ($f['meal_amount'] ?? 0) + ($f['baggage_amount'] ?? 0);
                        }
                    }
                }

                $passengerDetails[] = $p;
            }
        }
    }

    $onward= $baseOnwardPrice+$firstOnwardSum;
    $returnFlight=$baseReturnPrice+$firstReturnSum;
        
    // Calculate net price (sum of all flights including addons)
    $netPrice = $onward+$returnFlight;

    return view('flight.invoice', [
        'bookingId'        => $bookingId,
        'flightDetails'    => $flightDetails,
        'returnFlights'    => $returnFlights,
        'passengerDetails' => $passengerDetails,
        'netPrice'         => $netPrice,
        'adultMeal'        => $adultMeal,
        'adultBaggage'     => $adultBaggage,
        'childMeal'        => $childMeal,
        'childBaggage'     => $childBaggage,
        'onward'=>$onward,
        'returnFlight'=> $returnFlight,
        'type'             => $type,
    ]);
}


   public function failure(Request $request)
{
    $data = $request->all();

    return view('flight.payment_failure', [
        'amount' => $data['amount'] ?? 0,
        'txnid'  => $data['txnid'] ?? '',
        'msg'    => $data['error_Message'] ?? 'Your payment could not be processed.',
    ]);
}

}
