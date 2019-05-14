<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceInterval extends Model
{
    protected $table = "price_intervals";

    protected $fillable = [
        'price', 'start_date', 'end_date'
    ];

}