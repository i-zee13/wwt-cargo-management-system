<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageModel extends Model
{
    public $timestamps = false;
    protected $table = "packages";
    protected $guarded  = []; 
  
}
