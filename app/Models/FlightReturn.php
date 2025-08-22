<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightReturn extends Model
{
    use HasFactory;
    protected $table='flight_return_detail';
    protected $fillable = [
        'onward_flight_id', 'flight_id', 'flight_number', 'equipment_type', 'stops', 'duration',
        'airline_code', 'airline_name', 'is_lcc',
        'departure_code', 'departure_name', 'departure_city', 'departure_country', 'departure_terminal', 'departure_timezone', 'departure_latitude', 'departure_longitude',
        'arrival_code', 'arrival_name', 'arrival_city', 'arrival_country', 'arrival_terminal', 'arrival_timezone', 'arrival_latitude', 'arrival_longitude',
        'departure_time', 'arrival_time', 'is_iand', 'is_rs', 'segment_number'
    ];

    // Link back to the onward flight
    public function onwardFlight()
    {
        return $this->belongsTo(FlightDetail::class, 'onward_flight_id', 'id');
    }

    // Price for this return flight
    public function price()
    {
        return $this->hasOne(FlightPrice::class, 'flight_detail_id', 'id');
    }

    // Travelers linked to this return flight
    public function travellers()
    {
        return $this->hasMany(TravellerDetail::class, 'flight_detail_id', 'id');
    }
}
