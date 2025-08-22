<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TripjackController extends Controller
{   
 
public function search(Request $request)
 { 
    $data = $request->all();
       
    // Manually remap values from one-way inputs
    $fromWhere = $data['from_where_oneway'][0] ?? null;
    $toWhere = $data['to_where_oneway'][0] ?? null;

    $validated = Validator::make([
        'from_where' => $fromWhere,
        'to_where' => $toWhere,
        'departure_date' => $data['departure_date_oneway'],
        'travel_class' => $data['travel_class_oneway'],
        'adults' => $data['adult_count_oneway'],
        'children' => $data['child_count_oneway'],
        'infants' => $data['infant_count_oneway'],
    ], [
        'from_where' => 'required|string',
        'to_where' => 'required|string',
        'departure_date' => 'required|date',
        'travel_class' => 'required|string',
        'adults' => 'required|integer|min:1',
        'children' => 'nullable|integer',
        'infants' => 'nullable|integer',
    ])->validate();
    
     // Save passenger info in session
    Session::put('passenger_counts', [
        'adults' => (int) $validated['adults'],
        'children' => (int) ($validated['children'] ?? 0),
        'infants' => (int) ($validated['infants'] ?? 0),
    ]);
    // Prepare payload for TripJack API
    $payload = [
        "searchQuery" => [
            "cabinClass" => strtoupper($validated['travel_class']),
            "paxInfo" => [
                "ADULT" => (int) $validated['adults'],
                "CHILD" => (int) $validated['children'],
                "INFANT" => (int) $validated['infants'],
            ],
            "routeInfos" => [
                [
                    "fromCityOrAirport" => ["code" => strtoupper($validated['from_where'])],
                    "toCityOrAirport" => ["code" => strtoupper($validated['to_where'])],
                    "travelDate" => $validated['departure_date'],
                ]
            ],
            "searchModifiers" => [
                "isDirectFlight" => false,
                "isConnectingFlight" => false,
            ]
        ]
    ];
        $mode = config('services.tripjack_token.mode'); // "test" or "live"
$token = config("services.tripjack_token.$mode.token");
$url = config("services.tripjack_token.$mode.url");

    // Send TripJack API request
    $response = Http::withHeaders([
    'apikey' => $token,
    'Content-Type' => 'application/json',
])->post($url . '/fms/v1/air-search-all', $payload);

     
    if ($response->failed()) {
        return back()->with('error', 'TripJack API error: ' . $response->status());
    }

    $results = $response->json();
     
    return view('flight.flight-list', compact('results'));
}


public function searchRoundTrip(Request $request)
{
    // Step 1: Validate the request
    $validated = $request->validate([
        'from_where'     => 'required|array|min:1',
        'to_where'       => 'required|array|min:1',
        'depart_date'    => 'required|date_format:Y-m-d|before:return_date',
        'return_date'    => 'required|date_format:Y-m-d|after:depart_date',
        'adults'         => 'required|integer|min:1',
        'children'       => 'nullable|integer|min:0',
        'infants'        => 'nullable|integer|min:0',
        'travel_class'   => 'required|string'
    ]);

    // Step 2: Extract airport/city codes
    $fromCode = strtoupper($validated['from_where'][0]);
    $toCode   = strtoupper($validated['to_where'][0]);
     
    // ✅ Save passenger info in session (same as one-way)
    Session::put('passenger_counts_round', [
        'adults'   => (int) $validated['adults'],
        'children' => (int) ($validated['children'] ?? 0),
        'infants'  => (int) ($validated['infants'] ?? 0),
    ]);
    // Step 3: Prepare the payload using 'searchQuery' key
    $payload = [
        "searchQuery" => [
            "cabinClass" => strtoupper($validated['travel_class']),
            "paxInfo" => [
                "ADULT" => (int) $validated['adults'],
                "CHILD" => (int) ($validated['children'] ?? 0),
                "INFANT" => (int) ($validated['infants'] ?? 0),
            ],
            "routeInfos" => [
                [
                    "fromCityOrAirport" => ["code" => $fromCode],
                    "toCityOrAirport"   => ["code" => $toCode],
                    "travelDate"        => $validated['depart_date'],
                ],
                [
                    "fromCityOrAirport" => ["code" => $toCode],
                    "toCityOrAirport"   => ["code" => $fromCode],
                    "travelDate"        => $validated['return_date'],
                ]
            ],
            "searchModifiers" => [
                "isDirectFlight"      => false,
                "isConnectingFlight"  => false,
            ]
        ]
    ];
 $mode = config('services.tripjack_token.mode'); // "test" or "live"
$token = config("services.tripjack_token.$mode.token");
$url = config("services.tripjack_token.$mode.url");

    // Send TripJack API request
    $response = Http::withHeaders([
    'apikey' => $token,
    'Content-Type' => 'application/json',
])->post($url . '/fms/v1/air-search-all', $payload);
   

    // Step 5: Handle response
    if ($response->failed()) {
        return back()->with('error', 'TripJack API Error: ' . $response->status());
    }

    $resultsRound = $response->json();
    // Step 6: Pass data to the view
  
    return view('flight.flight-list', compact('resultsRound'));
}

public function searchMulticity(Request $request)
{  
    // Step 1: Transform flat input into nested 'segments' and 'seat_type' arrays
    $segments = [];

    foreach ($request->from_where_multicity_unique as $index => $fromCode) {
        $toCode = $request->to_where_multicity_unique[$index] ?? null;
        $date = $request->departure_date_multicity_unique[$index] ?? null;

        if ($fromCode && $toCode && $date) {
            $segments[] = [
                'from' => $fromCode,
                'to' => $toCode,
                'date' => $date,
            ];
        }
    }

    $transformedRequest = [
        'segments' => $segments,
        'seat_type' => [
            'adults'   => $request->adult_count_multicity_unique,
            'children' => $request->child_count_multicity_unique,
            'infants'  => $request->infant_count_multicity_unique,
            'class'    => $request->travel_class_multicity_unique,
        ]
    ];

    // Step 2: Validate the transformed request
    $validated = validator($transformedRequest, [
        'segments' => 'required|array|min:2',
        'segments.*.from' => 'required|string|size:3',
        'segments.*.to' => 'required|string|size:3',
        'segments.*.date' => 'required|date_format:Y-m-d',

        'seat_type.adults'   => 'required|integer|min:1',
        'seat_type.children' => 'nullable|integer|min:0',
        'seat_type.infants'  => 'nullable|integer|min:0',
        'seat_type.class'    => 'required|string',
    ])->validate();

    // Step 3: Convert to API payload
    $routeInfos = collect($validated['segments'])->map(function ($segment) {
        return [
            "fromCityOrAirport" => ["code" => strtoupper($segment['from'])],
            "toCityOrAirport"   => ["code" => strtoupper($segment['to'])],
            "travelDate"        => $segment['date'],
        ];
    })->toArray();

    $payload = [
        "searchQuery" => [
            "cabinClass" => strtoupper($validated['seat_type']['class']),
            "paxInfo" => [
                "ADULT" => (int) $validated['seat_type']['adults'],
                "CHILD" => (int) ($validated['seat_type']['children'] ?? 0),
                "INFANT" => (int) ($validated['seat_type']['infants'] ?? 0),
            ],
            "routeInfos" => $routeInfos,
            "searchModifiers" => [
                "isDirectFlight" => false,
                "isConnectingFlight" => false,
            ],
        ]
    ];

    $mode = config('services.tripjack_token.mode'); // "test" or "live"
$token = config("services.tripjack_token.$mode.token");
$url = config("services.tripjack_token.$mode.url");

    // Send TripJack API request
    $response = Http::withHeaders([
    'apikey' => $token,
    'Content-Type' => 'application/json',
])->post($url . '/fms/v1/air-search-all', $payload);

    if ($response->failed()) {
        return back()->with('error', 'TripJack API Error: ' . $response->status());
    }

    $resultsMulticity = $response->json();
   
    return view('flight.flight-list', compact('resultsMulticity'));
}



}
