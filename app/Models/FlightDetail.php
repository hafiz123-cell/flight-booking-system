<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightDetail extends Model
{
    protected $table = 'flight_detail';

    protected $fillable = [
        'booking_id',
        'flight_id',
        'type',
        'flight_number',
        'price',
        'equipment_type',
        'stops',
        'duration',
        'airline_code',
        'airline_name',
        'is_lcc',
        'departure_code',
        'departure_name',
        'departure_city',
        'departure_country',
        'departure_terminal',
        'departure_timezone',
        'departure_latitude',
        'departure_longitude',
        'arrival_code',
        'arrival_name',
        'arrival_city',
        'arrival_country',
        'arrival_terminal',
        'arrival_timezone',
        'arrival_latitude',
        'arrival_longitude',
        'departure_time',
        'arrival_time',
        'is_iand',
        'is_rs',
        'segment_number',
    ];

    public function returnFlights()
    {
        return $this->hasMany(FlightReturn::class, 'onward_flight_id', 'id');
    }

    // Flight price
    public function price()
    {
        return $this->hasOne(FlightPrice::class, 'flight_detail_id', 'id');
    }

    // Travelers linked to this flight
    public function travellers()
    {
        return $this->hasMany(TravellerDetail::class, 'flight_detail_id', 'id');
    }
}
