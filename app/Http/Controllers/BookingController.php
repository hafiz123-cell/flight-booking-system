<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingList;
class BookingController extends Controller
{
   public function index()
{
    $search = request('search');

    $bookings = BookingList::with(['user', 'flightDetail', 'travellerDetail', 'payment'])
        ->when($search, function ($query, $search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('flightDetail', fn($q) => $q->where('flight_number', 'like', "%{$search}%"))
                  ->orWhereHas('travellerDetail', fn($q) => $q->where('full_name', 'like', "%{$search}%"))
                  ->orWhereHas('payment', fn($q) => $q->where('status', 'like', "%{$search}%"));
        })
        ->latest()
        ->paginate(10); // <-- use paginate instead of get()

    if(request()->ajax()) {
        return view('admin_dashboard.booking.partials.table', compact('bookings'));
    }

    return view('admin_dashboard.booking.index', compact('bookings'));
}

}
