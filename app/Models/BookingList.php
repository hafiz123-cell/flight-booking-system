<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingList extends Model
{
    use HasFactory;

    protected $table = 'booking_list';

    protected $fillable = [
        'user_id',
        'flight_detail_id',
        'traveller_detail_id',
        'return_flight_detail_id', 
        'payment_id',
        'status',
        'payment_status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // User who made the booking
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Flight for this booking
    public function flightDetail()
    {
        return $this->belongsTo(FlightDetail::class, 'flight_detail_id');
    }


    // Traveller info for this booking
    public function travellerDetail()
    {
        return $this->belongsTo(TravellerDetail::class, 'traveller_detail_id');
    }

    // Payment info for this booking
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}


