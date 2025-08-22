<?php
namespace App\Http\Controllers;

use App\Models\FlightFareRule;
use App\Models\FlightPrice;
use App\Models\Payment;
use App\Models\TravellerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use App\Models\FlightDetail;

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
          
    // Handle GET request without txnid (show thank-you page)
    if ($request->isMethod('get') && empty($paymentData['txnid'])) {
        return view('thank-you', [
            'bookingId' => $request->query('bkId')
        ]);
    }

    $merchantKey = config('services.easebuzz.key');
    $salt        = config('services.easebuzz.salt_key');

    // Extract payment data fields safely
    $status      = $paymentData['status'] ?? '';
    $email       = $paymentData['email'] ?? '';
    $firstname   = $paymentData['firstname'] ?? '';
    $productinfo = $paymentData['productinfo'] ?? '';
    $amount      = $paymentData['amount'] ?? '';
    $txnid       = $paymentData['txnid'] ?? '';

    // Verify hash to ensure data integrity
    $hashString = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$merchantKey;
    $calculatedHash = strtolower(hash('sha512', $hashString));

    if (!isset($paymentData['hash']) || $calculatedHash !== strtolower($paymentData['hash'])) {
        return response()->json(['error' => 'Hash verification failed'], 400);
    }

    // Save payment record
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

    // Retrieve booking IDs from session and request
    $tripReviewData   = Session::get('trip_review_data', []);
   
    $sessionBookingId = $tripReviewData['bookingId'] ?? null;
    $requestBookingId = $request->query('bkId');

    // Determine bookingId safely
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

    // Fetch traveller details from DB by booking ID
    $detail = TravellerDetail::where('booking_id', $bookingId)->first();
      
    $passengers = [];
    if ($detail && !empty($detail->passenger_data)) {
        // passenger_data is already an array thanks to $casts
        $decoded = $detail->passenger_data;
       
        $paxTypeMap = [
            'ADULT'  => 'ADULT',
            'CHILD'  => 'CHILD',
            'INFANT' => 'INFANT'
        ];

      foreach ($decoded as $index => $p) {
    // Use stored values, falling back if missing
    $storedType = strtoupper($p['type'] ?? 'ADULT');
    $storedTitle = $p['title'] ?? 'Mr';
    // If type is CHILD and no dob in DB → set as 10 years old from today
    if ($storedType === 'CHILD' && empty($p['dob'])) {
        $storedDob = now()->subYears(10)->format('Y-m-d');
    } else {
        $storedDob = $p['dob'] ?? '1990-01-01';
    }// default DOB if missing

    $paxTypeMap = [
        'ADULT'  => 'ADULT',
        'CHILD'  => 'CHILD',
        'INFANT' => 'INFANT'
    ];
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

    // Prepare booking payload for TripJack API
    $bookingPayload = [
        "bookingId" => $bookingId,
        "paymentInfos" => [
            ["amount" => (float) $amount]
        ],
        "travellerInfo" => $passengers,
        "deliveryInfo" => [
            "emails" => [$email],
            "contacts" => [$paymentData['phone'] ?? '']
        ]
    ];

    try {
        $client = new Client();
 $mode = config('services.tripjack_token.mode'); // "test" or "live"
$token = config("services.tripjack_token.$mode.token");
$url = config("services.tripjack_token.$mode.url");

        $apiResponse = $client->post($url .'/oms/v1/air/book', [
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey' => $token,
            ],
            'json' => $bookingPayload
        ]);
        $apiResult = json_decode($apiResponse->getBody(), true);
       
          Session::put('apiResult',$apiResult);
          Session::put('paymentData',$paymentData);
          
          

        return redirect()->route('easebuzz.final.pay',[
            'bookingId' => $bookingId,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'paymentData' => $paymentData,
            'bookingId' => $bookingId
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
      
    // Get all traveller details for this booking
    $travellerDetails = TravellerDetail::where('booking_id', $bookingId)->get();
    


    // Collect passenger details with from/to
    $passengerDetails = [];
    foreach ($travellerDetails as $traveller) {
           $type = $flightDetails->first()->type ?? null;


        $data = $traveller->passenger_data; // already array due to casts
        if (is_array($data)) {
            foreach ($data as &$p) {
                $p['from'] = $flightDetails->first()->from_city ?? 'NA';
                $p['to']   = $flightDetails->first()->to_city ?? 'NA';
            }
            $passengerDetails = array_merge($passengerDetails, $data);
        }
    }

    // Extract all flight_detail_ids from FlightDetail
    $flightDetailIds = $flightDetails->pluck('id')->toArray();

    // Fetch fare rules & prices for these flights
    $fareRules = FlightFareRule::whereIn('flight_detail_id', $flightDetailIds)->get();
    $priceDetails = FlightPrice::whereIn('flight_detail_id', $flightDetailIds)->get();

    return view('flight.payments_success', [
        'bookingId'        => $bookingId,
        'flightDetails'    => $flightDetails,
        'passengerDetails' => $passengerDetails,
        'fareRules'        => $fareRules,
        'type'=>$type,
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

    // Fetch flight details
    $flightDetails = FlightDetail::where('booking_id', $bookingId)->get();

    // Fetch flight prices
    $flightDetailIds = $flightDetails->pluck('id')->toArray();
    $priceDetails = FlightPrice::whereIn('flight_detail_id', $flightDetailIds)->get()->keyBy('flight_detail_id');

    // Attach price info to each flight individually
    foreach ($flightDetails as $flight) {
        $flightPrice = $priceDetails[$flight->id] ?? null;
        $flight->base_fare  = $flightPrice->base_fare ?? 0;
        $flight->total_fare = $flightPrice->total_fare ?? 0; 
        $flight->net_fare   = $flightPrice->net_fare ?? 0;
        $flight->taxes      = $flightPrice->total_taxes ?? 0;
    }

    // Fetch traveller details
    $travellerDetails = TravellerDetail::where('booking_id', $bookingId)->get();

    // Prepare passengers with from/to
    $passengerDetails = [];
    foreach ($travellerDetails as $traveller) {
        foreach ($traveller->passenger_data as $p) {
            $p['from'] = $flightDetails->first()->from_city ?? 'NA';
            $p['to']   = $flightDetails->first()->to_city ?? 'NA';
            $passengerDetails[] = $p;
        }
    }

    // Fare rules
    $fareRules = FlightFareRule::whereIn('flight_detail_id', $flightDetailIds)->get();

    // Contact details
    $contactDetails = [
        'email' => $travellerDetails->first()->email ?? null,
        'mobile'=> $travellerDetails->first()->mobile_number ?? null,
        'name'  => $travellerDetails->first()->name ?? null,
    ];

    // Calculate net price per flight
    $netPrice = $flightDetails->sum(fn($flight) => $flight->total_fare ?? 0);

    return view('flight.invoice', [
        'bookingId'        => $bookingId,
        'flightDetails'    => $flightDetails,
        'passengerDetails' => $passengerDetails,
        'fareRules'        => $fareRules,
        'priceDetails'     => $priceDetails,
        'contactDetails'   => $contactDetails,
        'netPrice'         => $netPrice,
    ]);
}


    public function failure(Request $request)
    {
        return response()->json([
            'message' => 'Payment Failed',
            'data' => $request->all()
        ]);
    }
}
