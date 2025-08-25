<?php

namespace App\Http\Controllers;

use App\Models\FlightDetail;
use App\Models\FlightFareRule;
use App\Models\FlightPrice;
use App\Models\TravellerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Country;
class ReviewController extends Controller
{
public function review(Request $request, $priceId)
{
    $fareIdentifier = $request->query('fT');

    // Load TripJack config
    $mode = config('services.tripjack_token.mode'); // "test" or "live"
    $token = config("services.tripjack_token.$mode.token");
    $url   = config("services.tripjack_token.$mode.url");

    // Call the Review API
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'apikey'       => $token,
    ])->post($url . '/fms/v1/review', [
        'priceIds' => [$priceId]
    ]);

    if ($response->successful()) {
        $data = $response->json();

        // Extract session expiry (if available)
        $conditions = $data['conditions'] ?? [];
        $st  = $conditions['st']  ?? null; // seconds
        $sct = $conditions['sct'] ?? null; // timestamp

        $expiryTime = null;
        if ($st && $sct) {
            $expiryTime = \Carbon\Carbon::parse($sct)->addSeconds($st);
        }

        // Save expiry time + response in session
        Session::put('trip_review_data', $data);
        Session::put('tripjack_expiry_time', $expiryTime);

        return view('flight.flight-review', [
            'tripData'       => $data['tripInfos'] ?? [],
            'data'           => $data,
            'fareIdentifier' => $fareIdentifier,
            'priceId1'       => $priceId,
            'priceData'      => $data ?? [],
            'expiryTime'     => $expiryTime, // directly available in view
            'currentStep'    => 1,
        ]);

    } else {
        // Handle failure gracefully
        $status     = $response->status();
        $errorBody  = $response->json();
        $errorMessage = $errorBody['message'] ?? ($errorBody['errors'][0]['message'] ?? 'Unknown error occurred while reviewing flight.');

        return redirect()->back()
            ->with('error', "TripJack Review API Failed: [HTTP $status] $errorMessage");
    }
}



public function reviewFlightPrice(Request $request)
{
    $priceId = $request->input('priceId');
    $initialPrice = (float) $request->input('initialPrice');
 $mode = config('services.tripjack_token.mode'); // "test" or "live"
$token = config("services.tripjack_token.$mode.token");
$url = config("services.tripjack_token.$mode.url");
    // Call the API to get latest price (Replace this URL with actual TripJack Review Price API)
    $apiResponse = Http::withHeaders([
        'Content-Type' => 'application/json',
         'apikey' => $token, // Replace with your actual API key
    ])->post($url .'/fms/v1/review', [
        'priceIds' => [$priceId]
    ]);

    if ($apiResponse->successful()) {
        $responseData = $apiResponse->json();

        // Get the Latest Total Fare
        $latestFareData = $responseData['data']['priceInfo'][0]['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? null;

        if ($latestFareData !== null) {
            $latestFare = (float) $latestFareData;

            if ($latestFare == $initialPrice) {
                return response()->json(['status' => 'same']);
            } else {
                return response()->json(['status' => 'updated', 'newPrice' => $latestFare]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Fare not found']);
        }
    } else {
        return response()->json(['status' => 'error', 'message' => 'API failed']);
    }
}


public function price(Request $request, $priceId)
{
    $passengerCounts = Session::get('passenger_counts', [
        'adults' => 1,
        'children' => 0,
        'infants' => 0,
    ]);

    $countries = Country::orderBy('name')->get();
    $tripReviewData = Session::get('trip_review_data', []);
    $priceData = $tripReviewData['tripInfos'] ?? [];

    // ✅ Directly get total fare from totalPriceInfo
    $totalFare = $tripReviewData['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? 0;
  
    return view('flight.flight-personal-detail', [
        'passengerCounts' => $passengerCounts,
        'priceIds' => $priceId,
        'tripReviewData' => $tripReviewData,
        'priceData' => $priceData,
        'countries' => $countries,
        'currentStep' => 2,
        'tf' => $totalFare, // ✅ Send directly to Blade
    ]);
}



public function traveller_detail(Request $request)
{  
    $amount  = $request->input('amount');
    $priceId = $request->input('price_id');

    // Store the amount in session
    Session::put('payment_amount', $amount);

    // ✅ Validation
    $validated = $request->validate([
        'passengers' => 'required|array',
        'passengers.*.type' => 'required|string|in:ADULT,CHILD,INFANT',
        'passengers.*.title' => 'nullable|string',
        'passengers.*.first_name' => 'required|string',
        'passengers.*.last_name' => 'required|string',
        'passengers.*.dob' => 'nullable|date',
        'passengers.*.ff_number' => 'nullable|string',
        'passengers.*.save' => 'nullable|boolean',

        'passenger_services' => 'nullable|array',
        'passenger_services.*.baggage' => 'nullable|string',
        'passenger_services.*.baggage_amount' => 'nullable|numeric',
        'passenger_services.*.meal' => 'nullable|string',
        'passenger_services.*.meal_amount' => 'nullable|numeric',

        'country_code' => 'nullable|string',
        'mobile'       => 'nullable|string',
        'email'        => 'nullable|email',

        'gst_number'       => 'nullable|string',
        'gst_company_name' => 'nullable|string',
        'gst_email'        => 'nullable|email',
        'gst_phone'        => 'nullable|string',
        'gst_address_line1'=> 'nullable|string',
        'gst_address_line2'=> 'nullable|string',
        'save_gst'         => 'nullable|boolean',
    ]);

    // ✅ Combine GST address
    $companyAddress = trim(
        ($validated['gst_address_line1'] ?? '') . ' ' . ($validated['gst_address_line2'] ?? '')
    );

    // ✅ Prepare passenger + service data
    $travellerData = [];
    $addToTraveller = false;
    $services = $validated['passenger_services'] ?? [];

    foreach ($validated['passengers'] as $index => $passenger) {
        $service = $services[$index] ?? [
            'baggage' => null,
            'baggage_amount' => null,
            'meal' => null,
            'meal_amount' => null,
        ];

        $passengerEntry = [
            'title'           => $passenger['title'] ?? null,
            'type'            => $passenger['type'],
            'first_name'      => $passenger['first_name'],
            'last_name'       => $passenger['last_name'],
            'dob'             => $passenger['dob'] ?? null,
            'ff_number'       => $passenger['ff_number'] ?? null,
            'baggage'         => $service['baggage'] ?? null,
            'baggage_amount'  => $service['baggage_amount'] ?? null,
            'meal'            => $service['meal'] ?? null,
            'meal_amount'     => $service['meal_amount'] ?? null,
        ];

        $travellerData[] = array_filter($passengerEntry, fn($value) => !is_null($value));

        if (!empty($passenger['save'])) {
            $addToTraveller = true;
        }
    }

    // ✅ Store traveller data in session
    Session::put('traveller_data', [
        'price_id' => $priceId,
        'passenger_data' => $travellerData,
        'add_to_traveller_list' => $addToTraveller,
        'country_code' => $validated['country_code'] ?? null,
        'mobile_number' => $validated['mobile'] ?? null,
        'email' => $validated['email'] ?? null,
        'gst_number' => $validated['gst_number'] ?? null,
        'company_name' => $validated['gst_company_name'] ?? null,
        'company_email' => $validated['gst_email'] ?? null,
        'company_phone' => $validated['gst_phone'] ?? null,
        'company_address' => $companyAddress,
        'save_gst_details' => !empty($validated['save_gst']),
    ]);

    // ✅ Redirect to review page
    return redirect()->route('review_detail_add_flight', ['priceId' => $priceId]);
}



public function review_detail_add_flight(Request $request, $priceId)
{
    // ✅ Get trip + traveller data from session
    $data = Session::get('trip_review_data');
    $travellerData = Session::get('traveller_data');

    if (!$data || !isset($data['tripInfos'][0]['sI'][0])) {
        return response()->json(['message' => 'No flight data found in session'], 404);
    }

    $bookingId = $data['bookingId'] ?? null;
    $flight    = $data['tripInfos'][0]['sI'][0];
    $departure = $flight['da'];
    $arrival   = $flight['aa'];
    $airline   = $flight['fD']['aI'];
    $totalTaxes = $data['totalPriceInfo']['totalFareDetail']['fC']['TAF'] ?? 0;

    
    $flightDetail = FlightDetail::updateOrCreate(
        ['flight_id' => $flight['id']],
        ['type'=>'Oneway',
            'booking_id'         => $bookingId,
            'flight_number'      => $flight['fD']['fN'],
            'equipment_type'     => $flight['fD']['eT'] ?? null,
            'stops'              => $flight['stops'],
            'duration'           => $flight['duration'],

            'airline_code'       => $airline['code'],
            'airline_name'       => $airline['name'],
            'is_lcc'             => $airline['isLcc'] ?? null,

            'departure_code'     => $departure['code'],
            'departure_name'     => $departure['name'],
            'departure_city'     => $departure['city'] ?? null,
            'departure_country'  => $departure['country'] ?? null,
            'departure_terminal' => $departure['terminal'] ?? null,
            'departure_timezone' => $departure['timezoneId'] ?? null,
            'departure_latitude' => isset($departure['latitudeAirport']) ? (float) str_replace(',', '.', $departure['latitudeAirport']) : null,
            'departure_longitude'=> isset($departure['longitudeAirport']) ? (float) str_replace(',', '.', $departure['longitudeAirport']) : null,

            'arrival_code'       => $arrival['code'],
            'arrival_name'       => $arrival['name'],
            'arrival_city'       => $arrival['city'] ?? null,
            'arrival_country'    => $arrival['country'] ?? null,
            'arrival_terminal'   => $arrival['terminal'] ?? null,
            'arrival_timezone'   => $arrival['timezoneId'] ?? null,
            'arrival_latitude'   => isset($arrival['latitudeAirport']) ? (float) str_replace(',', '.', $arrival['latitudeAirport']) : null,
            'arrival_longitude'  => isset($arrival['longitudeAirport']) ? (float) str_replace(',', '.', $arrival['longitudeAirport']) : null,

            'departure_time'     => Carbon::parse($flight['dt']),
            'arrival_time'       => Carbon::parse($flight['at']),
            'is_iand'            => $flight['iand'] ?? null,
            'is_rs'              => $flight['isRs'] ?? null,
            'segment_number'     => $flight['sN'] ?? 0,
            'price'              => $totalTaxes ?? 0,
        ]
    );

   
    if (!empty($data['totalPriceInfo']['totalFareDetail'])) {
        $price = $data['totalPriceInfo']['totalFareDetail']['fC'];
        $taxes = $data['totalPriceInfo']['totalFareDetail']['afC']['TAF'] ?? [];

        FlightPrice::updateOrCreate(
            ['flight_detail_id' => $flightDetail->id],
            [
                'base_fare'    => $price['BF'] ?? 0,
                'total_fare'   => $price['TF'] ?? 0,
                'net_fare'     => $price['NF'] ?? 0,
                'total_taxes'  => $price['TAF'] ?? 0,
                'tax_breakdown'=> json_encode($taxes),
            ]
        );
    }

    
    if (!empty($data['tripInfos'][0]['totalPriceList'][0]['fareRuleInformation']['tfr'])) {
        $fareRules = $data['tripInfos'][0]['totalPriceList'][0]['fareRuleInformation']['tfr'];

        foreach ($fareRules as $ruleType => $rules) {
            foreach ($rules as $rule) {
                FlightFareRule::create([
                    'flight_detail_id' => $flightDetail->id,
                    'type'             => 'oneway',
                    'rule_type'        => $ruleType,
                    'amount'           => $rule['amount'] ?? null,
                    'additional_fee'   => $rule['additionalFee'] ?? null,
                    'policy_info'      => $rule['policyInfo'] ?? null,
                    'start_time'       => $rule['st'] ?? null,
                    'end_time'         => $rule['et'] ?? null,
                    'fare_components'  => json_encode($rule['fcs'] ?? []),
                ]);
            }
        }
    }

    
    if (!empty($travellerData)) {
        TravellerDetail::create([
            'price_id'             => $travellerData['price_id'],
            'booking_id'           => $bookingId,
            'flight_detail_id'     => $flightDetail->id,
            'passenger_data'       => $travellerData['passenger_data'], // includes baggage + meal + amounts
            'add_to_traveller_list'=> $travellerData['add_to_traveller_list'],
            'country_code'         => $travellerData['country_code'],
            'mobile_number'        => $travellerData['mobile_number'],
            'email'                => $travellerData['email'],
            'gst_number'           => $travellerData['gst_number'],
            'company_name'         => $travellerData['company_name'],
            'company_email'        => $travellerData['company_email'],
            'company_phone'        => $travellerData['company_phone'],
            'company_address'      => $travellerData['company_address'],
            'save_gst_details'     => $travellerData['save_gst_details'],
        ]);
    }

    return redirect()->route('review_detail', ['priceId' => $priceId]);
}



public function review_detail(Request $request, $priceId)
{          $data = Session::get('trip_review_data');
   
    $bookingId = $data['bookingId'] ?? null;
    $detail = TravellerDetail::where('booking_id', $bookingId )->first();
    $data   = Session::get('trip_review_data');
 Session::put('price',$priceId);
    $passengerDetails = $detail->passenger_data ?? [];
    $contactDetails = [
        'email'  => $detail->email ?? null,
        'mobile' => $detail->mobile_number ?? null,
    ];

    // Try to get price dynamically from trip data
   $amount = Session::get('payment_amount');
 // adjust key based on your actual structure
 if (is_string($passengerDetails)) {
    $passengerDetails = json_decode($passengerDetails, true);
}
 
    Session::put('easebuzz_payment_data', [
        'passenger' => $passengerDetails,
        'contact'   => $contactDetails,
        'priceIds'  => $priceId,
        'amount'    => $amount,
        'trip_info' => $data['tripInfos'] ?? [],
    ]);


$totalFare = $data['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? 0;
    // //    return redirect()->route('easebuzz.pay');
    return view('flight.flight-second-review', [
        'tripData' => $data['tripInfos'] ?? [],
        'priceId' => $priceId,
        'passengerDetails' => $passengerDetails,
        'contactDetails' => $contactDetails,
        'tf'=>$totalFare,
        'currentStep' => 3,
    ]);
}

public function payment(Request $request)
{
    $data = Session::get('trip_review_data');

    // Fetch bookingId from session data
    $bookingId = $data['bookingId'] ?? null;
    $detail = TravellerDetail::where('booking_id', $bookingId)->first();

    // Decode passenger_data properly
    $passengerDetails = $detail && $detail->passenger_data 
        ? (is_string($detail->passenger_data) ? json_decode($detail->passenger_data, true) : $detail->passenger_data) 
        : [];

    $contactDetails = [
        'email'  => $detail->email ?? null,
        'mobile' => $detail->mobile_number ?? null,
    ];

    // ✅ Get fare details from trip data
    $priceList   = $data['tripInfos'][0]['totalPriceList'][0]['fd'] ?? [];
    $adultFare   = $priceList['ADULT']['fC'] ?? [];
    $childFare   = $priceList['CHILD']['fC'] ?? [];
    $infantFare  = $priceList['INFANT']['fC'] ?? [];

    // ✅ Base + Taxes from API
    $baseFare    = ($adultFare['BF'] ?? 0) + ($childFare['BF'] ?? 0) + ($infantFare['BF'] ?? 0);
    $taxAndFee   = ($adultFare['TAF'] ?? 0) + ($childFare['TAF'] ?? 0) + ($infantFare['TAF'] ?? 0);
    $amountToPay = ($adultFare['TF'] ?? 0) + ($childFare['TF'] ?? 0) + ($infantFare['TF'] ?? 0);

    // ✅ Calculate baggage + meal separately
    $baggageCharges = 0;
    $mealCharges    = 0;

    foreach ($passengerDetails as $p) {
        if (!empty($p['baggage_amount'])) {
            $baggageCharges += (float) $p['baggage_amount'];
        }
        if (!empty($p['meal_amount'])) {
            $mealCharges += (float) $p['meal_amount'];
        }
    }

    // ✅ Total extras
    $extraCharges = $baggageCharges + $mealCharges;

    // ✅ Final Payable
    $finalAmount = $amountToPay + $extraCharges;

    return view('flight.payment', [
        'tripData'         => $data['tripInfos'] ?? [],
        'baseFare'         => $baseFare,
        'taxAndFee'        => $taxAndFee,
        'amountToPay'      => $amountToPay,
        'baggageCharges'   => $baggageCharges,
        'mealCharges'      => $mealCharges,
        'extraCharges'     => $extraCharges,   // combined
        'finalAmount'      => $finalAmount,    // ✅ total payable
        'passengerDetails' => $passengerDetails,
        'contactDetails'   => $contactDetails,
        'currentStep'      => 4,
    ]);
}


public function search(Request $request)
{
    $q = strtoupper($request->input('q'));  // uppercase for case-insensitive compare
    $type = strtoupper($request->input('type')); // e.g. ADULT, CHILD, INFANT

    $rows = DB::table('traveller_detail')
        ->select('passenger_data')
        ->where('add_to_traveller_list', 1)  // example filter to only saved travellers
        ->get();

    $results = [];

    foreach ($rows as $row) {
        $passengers = json_decode($row->passenger_data, true);

        if (!is_array($passengers)) continue;

        foreach ($passengers as $p) {
            // Optional: check passenger type if stored in each passenger object
            if (isset($p['type']) && strtoupper($p['type']) !== $type) {
                continue;
            }

            // Match first or last name with search query
            if (
                (isset($p['first_name']) && strpos(strtoupper($p['first_name']), $q) !== false) ||
                (isset($p['last_name']) && strpos(strtoupper($p['last_name']), $q) !== false)
            ) {
                $results[] = $p;
            }
        }
    }

    // Limit to first 10 matches
    $results = array_slice($results, 0, 10);

    return response()->json($results);
}


public function phone()
{
    return response()->json(config('phone_length'));
}

public function pay_link(Request $request)
{        $data = Session::get('trip_review_data');
   
    $bookingId = $data['bookingId'] ?? null;
    $price=Session::get('price');
    $detail = TravellerDetail::where('booking_id', $bookingId)->first();
   
    $data   = Session::get('trip_review_data');
     $passenger= $detail && $detail->passenger_data 
        ? (is_string($detail->passenger_data) ? json_decode($detail->passenger_data, true) : $detail->passenger_data) 
        : [];      

    $passengerDetails = $detail->passenger_data ?? [];
   
    $contactDetails = [
        'email'  => $detail->email ?? null,
        'mobile' => $detail->mobile_number ?? null,
    ];
        // Try to get price dynamically from trip data
   $amount = Session::get('payment_amount');
     // ✅ Calculate baggage + meal separately
    $baggageCharges = 0;
    $mealCharges    = 0;

    foreach ($passenger as $p) {
        if (!empty($p['baggage_amount'])) {
            $baggageCharges += (float) $p['baggage_amount'];
        }
        if (!empty($p['meal_amount'])) {
            $mealCharges += (float) $p['meal_amount'];
        }
    }

    // ✅ Total extras
    $extraCharges = $baggageCharges + $mealCharges;

    // ✅ Final Payable
    $finalAmount = $amount + $extraCharges;

  
 // adjust key based on your actual structure

    Session::put('easebuzz_payment_data', [
        'passenger' => $passengerDetails,
        'contact'   => $contactDetails,
        'price_id'  => $price,
        'amount'    => $finalAmount,
        'trip_info' => $data['tripInfos'] ?? [],
        'bookingId' => $data['bookingId'] ?? null,

    ]);

            

       return redirect()->route('easebuzz.pay');
    // return view('flight.flight-second-review', [
    //     'tripData' => $data['tripInfos'] ?? [],
    //     'priceId' => $price,
    //     'passengerDetails' => $passengerDetails,
    //     'contactDetails' => $contactDetails,
    //     'currentStep' => 3,
    // ]);
}



    }
