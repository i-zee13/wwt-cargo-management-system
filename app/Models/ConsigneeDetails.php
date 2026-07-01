<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsigneeDetails extends Model
{
    public $timestamps = false;
    protected $table = "consignee_details";
    protected $guarded  = [];  
    public function origin()
    {
        return $this->belongsTo(OriginModel::class, 'origin_id');
    }
}
