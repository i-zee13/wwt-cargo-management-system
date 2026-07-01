<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarrierDetails extends Model
{
    public $timestamps = false;
    protected $table = "carrier_details";
    protected $guarded  = [];
    public function origin()
    {
        return $this->belongsTo(OriginModel::class, 'origin_id');
    }
}
