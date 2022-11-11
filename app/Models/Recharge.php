<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    protected $guarded=[
//        'user_id',
//        'transaction_id',
//        'type',
//        'deposit_account',
//        'amount',
//        'sent_from',
//        'received_amount',
//        'updated_by',
//        'receipt',
//        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
