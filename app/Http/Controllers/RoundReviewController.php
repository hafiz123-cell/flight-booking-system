<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\FlightDetail;
use App\Models\FlightFareRule;
use App\Models\FlightPrice;
use App\Models\FlightReturn;
use App\Models\TravellerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class RoundReviewController extends Controller
{
   
    public function reviewRound(Request $request)
   {
    $onwardPriceId = $request->query('onwardPriceId');
    $returnPriceId = $request->query('returnPriceId');
      
    $onwardFareIdentifier = $request->query('onwardFareIdentifier');
    $returnFareIdentifier = $request->query('returnFareIdentifier');

    $mode = config('services.tripjack_token.mode'); // "test" or "live"
    $token = config("services.tripjack_token.$mode.token");
    $url = config("services.tripjack_token.$mode.url");

    // Call the review API with BOTH onward & return priceIds
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'apikey' => $token,
    ])->post($url . '/fms/v1/review', [
        'priceIds' => [$onwardPriceId, $returnPriceId],
    ]);

    if ($response->successful()) {
        $data = $response->json();

        // Save full response in session
        Session::put('trip_review_datas', $data);
       
Session::put('selected_priceIds', [$onwardPriceId, $returnPriceId]);

        return view('flight.round.flight-review-round', [
            'tripData' => $data['tripInfos'] ?? [],
            'data' => $data,
            'onwardPriceId' => $onwardPriceId,
            'returnPriceId' => $returnPriceId,
            'onwardFareIdentifier' => $onwardFareIdentifier,
            'returnFareIdentifier' => $returnFareIdentifier,
            'priceData' => $data ?? [],
            'currentStep' => 1,
        ]);
    } else {
        // Handle API error
        $status = $response->status();
        $errorBody = $response->json();

        $errorMessage = $errorBody['message'] ?? 'Unknown error occurred while reviewing round trip flight.';

        return redirect()->back()->with('error', "TripJack Round Review API Failed: [HTTP $status] $errorMessage");
    }
}

    public function review(Request $request)
{
    $priceIds = $request->input('priceIds', []); 
    $initialPrices = $request->input('initialPrices', []); 

    if (empty($priceIds)) {
        return response()->json(['status' => 'error', 'message' => 'No priceIds provided']);
    }

    $mode  = config('services.tripjack_token.mode'); 
    $token = config("services.tripjack_token.$mode.token");
    $url   = config("services.tripjack_token.$mode.url");

    $apiResponse = Http::withHeaders([
        'Content-Type' => 'application/json',
        'apikey' => $token,
    ])->post($url . '/fms/v1/review', [
        'priceIds' => $priceIds
    ]);

    if (!$apiResponse->successful()) {
        return response()->json(['status' => 'error', 'message' => 'API request failed']);
    }

    $responseData = $apiResponse->json();
    $reviewResults = [];

    if (!empty($responseData['data']['priceInfo'])) {
        foreach ($responseData['data']['priceInfo'] as $i => $priceInfo) {
            $latestFare = $priceInfo['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? null;
            $initialFare = isset($initialPrices[$i]) ? (float)$initialPrices[$i] : null;
             Session::put('round-tf', $latestFare);
              Session::put('initial', $initialFare);
            if ($latestFare !== null && $initialFare !== null) {
                if ((float)$latestFare === $initialFare) {
                    $reviewResults[] = ['status' => 'same', 'priceId' => $priceIds[$i]];
                } else {
                    $reviewResults[] = [
                        'status' => 'updated',
                        'priceId' => $priceIds[$i],
                        'newPrice' => (float)$latestFare
                    ];
                }
            } else {
                $reviewResults[] = ['status' => 'error', 'priceId' => $priceIds[$i], 'message' => 'Fare not found'];
            }
        }
    }

    return response()->json([
        'status' => 'ok',
        'results' => $reviewResults
    ]);
}

public function proceed(Request $request)
{       
    // ✅ Get from request first
    $priceIds = $request->input('priceIds', []);
    $initialPrices = $request->input('initialPrices', []);

    if (empty($priceIds)) {
        return redirect()->back()->with('error', 'No priceIds provided');
    }

    // Store in session if needed later
    Session::put('selected_priceIds', $priceIds);
    Session::put('selected_initialPrices', $initialPrices);

    // Passenger counts
    $passengerCounts = Session::get('passenger_counts_round', [
        'adults'   => 1,
        'children' => 0,
        'infants'  => 0,
    ]);

    $countries = Country::orderBy('name')->get();
    $tripReviewData = Session::get('trip_review_datas', []);
    $priceData      = $tripReviewData['tripInfos'] ?? [];

    $totalFare = $tripReviewData['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? 0;
$data= Session::get('trip_review_datas');
$selectedPriceIds = Session::get('selected_priceIds', []); 
// default empty array if nothing is in session
            

    return view('flight.round.flight-personal-detail', [
        'tripData' => $data['tripInfos'] ?? [],
            'data' => $data,
             'selectedPriceIds' => $selectedPriceIds,
        'passengerCounts' => $passengerCounts,
        'priceIds'        =>  $priceIds,
        'tripReviewData'  => $tripReviewData,
        'priceData'       => $priceData,
        'countries'       => $countries,
        'currentStep'     => 2,
        'tf'              => $totalFare,
    ]);
}

public function traveller_detail_round(Request $request)
{  
    $amount   = $request->input('amount');
    $priceIds = $request->input('priceIds', []); // round trip = multiple priceIds

    Session::put('payment_amount', $amount);

    // ✅ Preprocess baggage/meal flat inputs into nested array
    $services = [];
    foreach ($request->all() as $key => $value) {
        if (preg_match('/^baggage_(\d+)_(\d+)$/', $key, $matches)) {
            $passengerIndex = $matches[1];
            $flightIndex    = $matches[2];
            $services[$passengerIndex][$flightIndex]['baggage'] = $value;
        }
        if (preg_match('/^meal_(\d+)_(\d+)$/', $key, $matches)) {
            $passengerIndex = $matches[1];
            $flightIndex    = $matches[2];
            $services[$passengerIndex][$flightIndex]['meal'] = $value;
        }
    }

    $validated = $request->validate([
        'passengers'               => 'required|array',
        'passengers.*.type'        => 'required|string|in:ADULT,CHILD,INFANT',
        'passengers.*.title'       => 'nullable|string',
        'passengers.*.first_name'  => 'required|string',
        'passengers.*.last_name'   => 'required|string',
        'passengers.*.dob'         => 'nullable|date',
        'passengers.*.ff_number'   => 'nullable|string',
        'passengers.*.save'        => 'nullable|boolean',

        'country_name'         => 'nullable|string',
        'mobile'               => 'nullable|string',
        'email'                => 'nullable|email',

        'gst_number'           => 'nullable|string',
        'gst_company_name'     => 'nullable|string',
        'gst_email'            => 'nullable|email',
        'gst_phone'            => 'nullable|string',
        'gst_address_line1'    => 'nullable|string',
        'gst_address_line2'    => 'nullable|string',
        'save_gst'             => 'nullable|boolean',
    ]);

    // combine gst address
    $companyAddress = trim(
        ($validated['gst_address_line1'] ?? '') . ' ' . ($validated['gst_address_line2'] ?? '')
    );

    // get bookingId from session
    $tripReviewData = Session::get('trip_review_datas', []);
    $bookingId      = $tripReviewData['bookingId'] ?? null;
    Session::put('bookingId', $bookingId);

    $travellerData  = [];
    $addToTraveller = false;

    foreach ($validated['passengers'] as $index => $passenger) {
        $passengerEntry = [
            'title'      => $passenger['title'] ?? null,
            'type'       => $passenger['type'],
            'first_name' => $passenger['first_name'],
            'last_name'  => $passenger['last_name'],
            'dob'        => $passenger['dob'] ?? null,
            'ff_number'  => $passenger['ff_number'] ?? null,
            'flights'    => $services[$index] ?? []
        ];

        $travellerData[] = array_filter($passengerEntry, fn($v) => !is_null($v));

        if (!empty($passenger['save'])) {
            $addToTraveller = true;
        }
    }

    // ✅ Store traveller info temporarily in session
    Session::put('traveller_detail_data', [
        'priceIds'             => $priceIds,
        'booking_id'           => $bookingId,
        'passenger_data'       => $travellerData,
        'add_to_traveller_list'=> $addToTraveller,
        'country_code'         => $validated['country_name'] ?? null,
        'mobile_number'        => $validated['mobile'] ?? null,
        'email'                => $validated['email'] ?? null,
        'gst_number'           => $validated['gst_number'] ?? null,
        'company_name'         => $validated['gst_company_name'] ?? null,
        'company_email'        => $validated['gst_email'] ?? null,
        'company_phone'        => $validated['gst_phone'] ?? null,
        'company_address'      => $companyAddress,
        'save_gst_details'     => !empty($validated['save_gst']),
    ]);

    return redirect()->route('review_detail_add_flight_round', [
        'priceId' => implode(',', $priceIds)
    ]);
}



public function review_detail_add_flight_round(Request $request, $priceId)
{    
    $data = Session::get('trip_review_datas');
    $priceIdsArray = explode(',', $priceId);

    if (!$data || empty($data['tripInfos'])) {
        return response()->json(['message' => 'No flight data found in session'], 404);
    }

    $bookingId = $data['bookingId'] ?? null;
    $savedOnwardFlights = [];
    $savedReturnFlights  = [];

    foreach ($data['tripInfos'] as $tripIndex => $trip) {
        foreach ($trip['sI'] as $flightIndex => $flight) {
            $departure = $flight['da'] ?? [];
            $arrival   = $flight['aa'] ?? [];
            $airline   = $flight['fD']['aI'] ?? [];

            if ($tripIndex === 0) {
                // Onward flight
                $flightDetail = FlightDetail::updateOrCreate(
                    ['flight_id' => $flight['id']],
                    [
                        'type'=>'roundtrip',
                        'booking_id'         => $bookingId,
                        'flight_number'      => $flight['fD']['fN'] ?? null,
                        'equipment_type'     => $flight['fD']['eT'] ?? null,
                        'stops'              => $flight['stops'] ?? 0,
                        'duration'           => $flight['duration'] ?? null,
                        'airline_code'       => $airline['code'] ?? null,
                        'airline_name'       => $airline['name'] ?? null,
                        'is_lcc'             => $airline['isLcc'] ?? null,
                        'departure_code'     => $departure['code'] ?? null,
                        'departure_name'     => $departure['name'] ?? null,
                        'departure_city'     => $departure['city'] ?? null,
                        'departure_country'  => $departure['country'] ?? null,
                        'departure_terminal' => $departure['terminal'] ?? null,
                        'departure_timezone' => $departure['timezoneId'] ?? null,
                        'departure_latitude' => isset($departure['latitudeAirport']) ? (float) str_replace(',', '.', $departure['latitudeAirport']) : null,
                        'departure_longitude'=> isset($departure['longitudeAirport']) ? (float) str_replace(',', '.', $departure['longitudeAirport']) : null,
                        'arrival_code'       => $arrival['code'] ?? null,
                        'arrival_name'       => $arrival['name'] ?? null,
                        'arrival_city'       => $arrival['city'] ?? null,
                        'arrival_country'    => $arrival['country'] ?? null,
                        'arrival_terminal'   => $arrival['terminal'] ?? null,
                        'arrival_timezone'   => $arrival['timezoneId'] ?? null,
                        'arrival_latitude'   => isset($arrival['latitudeAirport']) ? (float) str_replace(',', '.', $arrival['latitudeAirport']) : null,
                        'arrival_longitude'  => isset($arrival['longitudeAirport']) ? (float) str_replace(',', '.', $arrival['longitudeAirport']) : null,
                        'departure_time'     => isset($flight['dt']) ? Carbon::parse($flight['dt']) : null,
                        'arrival_time'       => isset($flight['at']) ? Carbon::parse($flight['at']) : null,
                        'is_iand'            => $flight['iand'] ?? null,
                        'is_rs'              => $flight['isRs'] ?? null,
                        'segment_number'     => $flight['sN'] ?? 0,
                    ]
                );

                $savedOnwardFlights[] = $flightDetail->id;

            } else {
                // Return flight
                $returnFlight = FlightReturn::updateOrCreate(
                    ['flight_id' => $flight['id']],
                    [
                        'onward_flight_id'   => $savedOnwardFlights[$flightIndex] ?? null,
                        'flight_number'      => $flight['fD']['fN'] ?? null,
                        'equipment_type'     => $flight['fD']['eT'] ?? null,
                        'stops'              => $flight['stops'] ?? 0,
                        'duration'           => $flight['duration'] ?? null,
                        'airline_code'       => $airline['code'] ?? null,
                        'airline_name'       => $airline['name'] ?? null,
                        'is_lcc'             => $airline['isLcc'] ?? null,
                        'departure_code'     => $departure['code'] ?? null,
                        'departure_name'     => $departure['name'] ?? null,
                        'departure_city'     => $departure['city'] ?? null,
                        'departure_country'  => $departure['country'] ?? null,
                        'departure_terminal' => $departure['terminal'] ?? null,
                        'departure_timezone' => $departure['timezoneId'] ?? null,
                        'departure_latitude' => isset($departure['latitudeAirport']) ? (float) str_replace(',', '.', $departure['latitudeAirport']) : null,
                        'departure_longitude'=> isset($departure['longitudeAirport']) ? (float) str_replace(',', '.', $departure['longitudeAirport']) : null,
                        'arrival_code'       => $arrival['code'] ?? null,
                        'arrival_name'       => $arrival['name'] ?? null,
                        'arrival_city'       => $arrival['city'] ?? null,
                        'arrival_country'    => $arrival['country'] ?? null,
                        'arrival_terminal'   => $arrival['terminal'] ?? null,
                        'arrival_timezone'   => $arrival['timezoneId'] ?? null,
                        'arrival_latitude'   => isset($arrival['latitudeAirport']) ? (float) str_replace(',', '.', $arrival['latitudeAirport']) : null,
                        'arrival_longitude'  => isset($arrival['longitudeAirport']) ? (float) str_replace(',', '.', $arrival['longitudeAirport']) : null,
                        'departure_time'     => isset($flight['dt']) ? Carbon::parse($flight['dt']) : null,
                        'arrival_time'       => isset($flight['at']) ? Carbon::parse($flight['at']) : null,
                        'is_iand'            => $flight['iand'] ?? null,
                        'is_rs'              => $flight['isRs'] ?? null,
                        'segment_number'     => $flight['sN'] ?? 0,
                    ]
                );

                $savedReturnFlights[] = $returnFlight->id;
            }

            // Save price info
            if (!empty($data['totalPriceInfo']['totalFareDetail'])) {
                $price = $data['totalPriceInfo']['totalFareDetail']['fC'];
                $taxes = $data['totalPriceInfo']['totalFareDetail']['afC']['TAF'] ?? [];

                FlightPrice::updateOrCreate(
                    ['flight_detail_id' => $tripIndex === 0 ? $flightDetail->id : $returnFlight->id],
                    [
                        'base_fare'     => $price['BF'] ?? 0,
                        'total_fare'    => $price['TF'] ?? 0,
                        'net_fare'      => $price['NF'] ?? 0,
                        'total_taxes'   => $price['TAF'] ?? 0,
                        'tax_breakdown' => json_encode($taxes),
                    ]
                );
            }

            // Save fare rules if available
            if (!empty($flight['fareRules'])) {
                foreach ($flight['fareRules'] as $rule) {
                    FlightFareRule::updateOrCreate(
                        [
                            'flight_detail_id' => $tripIndex === 0 ? $flightDetail->id : $returnFlight->id,
                            'rule_type'        => $rule['type'] ?? 'UNKNOWN'
                        ],
                        [
                            'amount'          => $rule['amount'] ?? null,
                            'additional_fee'  => $rule['additionalFee'] ?? null,
                            'policy_info'     => $rule['policyInfo'] ?? null,
                            'start_time'      => $rule['startTime'] ?? null,
                            'end_time'        => $rule['endTime'] ?? null,
                            'fare_components' => isset($rule['fareComponents']) ? json_encode($rule['fareComponents']) : null,
                        ]
                    );
                }
            }
        }
    }

    // Save traveller data
    $travellerData = Session::get('traveller_detail_data', []);
    if ($travellerData) {
        TravellerDetail::create([
            'price_id'         => json_encode($priceIdsArray),
            'booking_id'       => $travellerData['booking_id'],
            'flight_detail_id' => $savedOnwardFlights[0] ?? null,
            'passenger_data'   => $travellerData['passenger_data'],
            'add_to_traveller_list' => $travellerData['add_to_traveller_list'],
            'country_code'     => $travellerData['country_code'],
            'mobile_number'    => $travellerData['mobile_number'],
            'email'            => $travellerData['email'],
            'gst_number'       => $travellerData['gst_number'],
            'company_name'     => $travellerData['company_name'],
            'company_email'    => $travellerData['company_email'],
            'company_phone'    => $travellerData['company_phone'],
            'company_address'  => $travellerData['company_address'],
            'save_gst_details' => $travellerData['save_gst_details'],
        ]);
    }

    return redirect()->route('review_detail_round', [
        'priceId' => $priceId
    ]);
}




public function review_detail_round(Request $request, $priceId)
{        $data = Session::get('trip_review_datas');
   
    if (!$data || empty($data['tripInfos'])) {
        return response()->json(['message' => 'No flight data found in session'], 404);
    }

    $bookingId = $data['bookingId'] ?? null;
   
    $priceIds = explode(',', $priceId);
     
    // Fetch traveller detail for the given priceIds
    $detail = TravellerDetail::where('booking_id', $bookingId)->first();

    // Get trip review data from session
    $data = Session::get('trip_review_datas');

    // Save priceIds in session
    Session::put('price', $priceIds);

    // Passenger & contact details
    $passengerDetails = $detail->passenger_data ?? [];
    $contactDetails = [
        'email'  => $detail->email ?? null,
        'mobile' => $detail->mobile_number ?? null,
    ];

    // Prepare data for payment
    $amount = Session::get('payment_amount');

    Session::put('easebuzz_payment_data', [
        'passenger' => $passengerDetails,
        'contact'   => $contactDetails,
        'priceIds'  => $priceId,
        'amount'    => $amount,
        'trip_info' => $data['tripInfos'] ?? [],
    ]);

    $totalFare = $data['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? 0;

    return view('flight.round.flight-second-round-review', [
        'tripData' => $data['tripInfos'] ?? [],
        'priceId' => $priceId,
        'passengerDetails' => $passengerDetails,
        'contactDetails' => $contactDetails,
        'tf' => $totalFare,
        'currentStep' => 3,
    ]);
}

 

public function payment_round(Request $request)
{
    // Get trip review data from session
    $data = Session::get('trip_review_datas');
    if (!$data || empty($data['tripInfos'])) {
        return redirect()->back()->with('error', 'No trip data found in session.');
    }

    // Fetch traveller details using bookingId
    $bookingId = $data['bookingId'] ?? null;
    $detail = TravellerDetail::where('booking_id', $bookingId)->first();

    if (!$detail) {
        return redirect()->back()->with('error', 'Traveller details not found.');
    }

    // Passenger + Contact details
    $passengerDetails = $detail->passenger_data ?? [];
    $contactDetails = [
        'email'  => $detail->email ?? null,
        'mobile' => $detail->mobile_number ?? null,
    ];

    // Final payable amount (already saved earlier)
    $amount = Session::get('payment_amount');

    // ✅ Calculate fares for BOTH trips (onward + return)
    $baseFare = 0;
    $taxAndFee = 0;
    $amountToPay = 0;

    foreach ($data['tripInfos'] as $trip) {
        if (!empty($trip['totalPriceList'][0]['fd'])) {
            $priceList = $trip['totalPriceList'][0]['fd'];

            $adultFare  = $priceList['ADULT']['fC'] ?? [];
            $childFare  = $priceList['CHILD']['fC'] ?? [];
            $infantFare = $priceList['INFANT']['fC'] ?? [];

            // Add to totals
            $baseFare   += ($adultFare['BF'] ?? 0) + ($childFare['BF'] ?? 0) + ($infantFare['BF'] ?? 0);
            $taxAndFee  += ($adultFare['TAF'] ?? 0) + ($childFare['TAF'] ?? 0) + ($infantFare['TAF'] ?? 0);
            $amountToPay+= ($adultFare['TF'] ?? 0) + ($childFare['TF'] ?? 0) + ($infantFare['TF'] ?? 0);
        }
    }

    return view('flight.round.payment', [
        'tripData'         => $data['tripInfos'] ?? [],
        'amount'           => $amount,       // Session amount (final payable)
        'bf'               => $baseFare,     // Total Base Fare (onward + return)
        'tf'               => $taxAndFee,    // Total Taxes (onward + return)
        'atp'              => $amountToPay,  // Total (onward + return)
        'passengerDetails' => $passengerDetails,
        'contactDetails'   => $contactDetails,
        'currentStep'      => 4,
    ]);
}


public function pay_link_round(Request $request)
{    
    // Get all priceIds from session (roundtrip stores them as array)
    $priceIds = Session::get('price', []);

    // Get trip data from session
    $data = Session::get('trip_review_datas');
    if (!$data) {
        return redirect()->back()->with('error', 'No trip data found in session.');
    }

    // Use bookingId (safer than priceId for roundtrip)
    $bookingId = $data['bookingId'] ?? null;
    $detail = TravellerDetail::where('booking_id', $bookingId)->first();

    if (!$detail) {
        return redirect()->back()->with('error', 'Traveller details not found.');
    }

    // Passenger details
    $passengerDetails = $detail->passenger_data ?? [];

    // Contact details
    $contactDetails = [
        'email'  => $detail->email ?? null,
        'mobile' => $detail->mobile_number ?? null,
    ];

    // Amount already stored during traveller step
    $amount = Session::get('payment_amount');

    // Save all data for payment gateway
    Session::put('easebuzz_payment_data', [
        'passenger'   => $passengerDetails,
        'contact'     => $contactDetails,
        'price_ids'   => $priceIds,   // ✅ store roundtrip priceIds
        'amount'      => $amount,
        'trip_info'   => $data['tripInfos'] ?? [],
        'bookingId'   => $bookingId,
    ]);

    return redirect()->route('easebuzz.pay');
}


}


