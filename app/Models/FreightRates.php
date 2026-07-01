<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreightRates extends Model
{
    public $timestamps = false;
    protected $table = "freight_rates";
    protected $guarded  = [];
}
