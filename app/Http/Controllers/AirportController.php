<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\Airport;
class AirportController extends Controller
{
   

public function showBookingPage()
{
    $airports = Airport::limit(1000)->get(); // limit for performance
    return view('flight.index-flights', compact('airports'));
}

}
