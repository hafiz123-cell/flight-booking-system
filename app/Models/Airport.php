<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{   protected $table = 'bravo_flight_api';
    protected $fillable = [
        'code',
        'name',
        'city',
        'city_code',
        'country',
        'country_code',
        'address',
    ];

    // Optional: If you don't use timestamps in the table
    public $timestamps = false;
}
