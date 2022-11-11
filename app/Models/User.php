<?php

namespace App\Models;

//use App\Traits\UserAttributes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
//        'username',
//        'email',
//        'password',
//        'phone_number',
//        'pin',
//        'balance',
//        'status',
//        'image',
//        'attempt_count'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


//    protected $casts = [
//        'docs'=> 'array'
//    ];

//    public function getStatusAttribute($value)
//    {
//
//        if($value==1)
//        {
//            return 'Active';
//        }else
//        {
//            return 'Inactive';
//        }
//
//    }
}
