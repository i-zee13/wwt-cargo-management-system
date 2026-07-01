<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    public $timestamps = false;
    protected $table = "branches";
    protected $guarded  = [];
}
