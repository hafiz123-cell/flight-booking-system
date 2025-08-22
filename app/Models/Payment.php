<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'txnid',
        'easepayid',
        'status',
        'amount',
        'productinfo',
        'firstname',
        'email',
        'phone',
        'raw_response',
    ];
}

