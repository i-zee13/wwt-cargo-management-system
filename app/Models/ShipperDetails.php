<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipperDetails extends Model
{
    public $timestamps = false;
    protected $table = "shipper_details";
    protected $guarded  = [];
    public function origin()
    {
        return $this->belongsTo(OriginModel::class, 'origin_id');
    }
}
