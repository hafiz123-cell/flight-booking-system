<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_detail_id', 'base_fare', 'total_fare', 'net_fare', 'total_taxes', 'tax_breakdown'
    ];


    protected $casts = [
        'tax_breakdown' => 'array',
    ];

   public function flight()
    {
        return $this->belongsTo(FlightDetail::class, 'flight_detail_id', 'id');
    }
}


