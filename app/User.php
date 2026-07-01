<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password', 'username', 'phone', 'city', 'state', 'country', 'address', 'designation', 'hiring', 'salary', 'reporting_to', 'department_id', 'crm_cms_access', 'picture', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // 'password', 'remember_token',
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function sendPasswordResetNotification($token)
    {
        
    }
}
