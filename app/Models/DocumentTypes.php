<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTypes extends Model
{
    public $timestamps = false;
    protected $table = "documents_types";
    protected $guarded  = [];
}
