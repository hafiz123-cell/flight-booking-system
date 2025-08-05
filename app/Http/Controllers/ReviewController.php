<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReviewController extends Controller
{
   function review(Request $request, $priceId) {
    $fareIdentifier = $request->query('fT');

    // Call the review API using the priceId
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
         'apikey' => config('services.tripjack_token'), // Replace with your actual API key
    ])->post('https://tripjack.com/fms/v1/review', [
        'priceIds' => [$priceId]
    ]);

    if ($response->successful()) {
        $data = $response->json();
  
        return view('flight.flight-review', [
            'tripData' => $data['tripInfos'] ?? [],
            'fareIdentifier' => $fareIdentifier,
            'priceId' => $priceId,
            'priceData' => $priceData ?? [],
            'currentStep' => 1,
        ]);
    } else {
        abort(500, 'Failed to fetch review data');
    };
    
}


public function reviewFlightPrice(Request $request)
{
    $priceId = $request->input('priceId');
    $initialPrice = (float) $request->input('initialPrice');

    // Call the API to get latest price (Replace this URL with actual TripJack Review Price API)
    $apiResponse = Http::withHeaders([
        'Content-Type' => 'application/json',
         'apikey' => config('services.tripjack_token'), // Replace with your actual API key
    ])->post('https://tripjack.com/fms/v1/review', [
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

    }
