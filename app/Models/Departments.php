<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    public $timestamps = false;
    protected $table = "departments";
    protected $guarded  = [];
}
