<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginModel extends Model
{
    public $timestamps = false;
    protected $table = "origins";
    protected $guarded  = [];
    public function consigneeDetails()
    {
        return $this->hasOne(ConsigneeDetails::class, 'origin_id');
    }
 
    public function shipperDetails()
    {
        return $this->hasOne(ShipperDetails::class, 'origin_id');
    } 
    public function CarrierDetails()
    {
        return $this->hasOne(CarrierDetails::class, 'origin_id');
    }
}
