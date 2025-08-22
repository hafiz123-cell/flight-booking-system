<?php


    namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravellerDetail extends Model
{
    use HasFactory;

    protected $table = 'traveller_detail';

   protected $fillable = [
    'price_id',
    'booking_id',
    'flight_detail_id',  // <-- add this
    'passenger_data',
    'add_to_traveller_list',
    'country_code',
    'mobile_number',
    'email',
    'gst_number',
    'company_name',
    'company_email',
    'company_phone',
    'company_address',
    'save_gst_details'
];

    protected $casts = [
        'price_id' => 'array', 
         'flight_detail_id' => 'array',       // auto-cast JSON to array
        'passenger_data' => 'array',
        'add_to_traveller_list' => 'boolean',
        'save_gst_details' => 'boolean',
    ];

}
