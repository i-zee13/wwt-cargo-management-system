<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailContentSettings extends Model
{
    public $timestamps = false;
    protected $table = "email_content_settings";
    protected $guarded  = [];
    
    
}
