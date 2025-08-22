<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightFareRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_detail_id',
        'rule_type',
        'amount',
        'additional_fee',
        'policy_info',
        'start_time',
        'end_time',
        'fare_components',
    ];

    protected $casts = [
        'fare_components' => 'array',
    ];

    public function flightDetail()
    {
        return $this->belongsTo(FlightDetail::class);
    }
}

