<?php

namespace App\Models;

//use App\Traits\TransactionsModelAttributes;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{

//    use  TransactionsModelAttributes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */

//    protected $fillable = ['mobile', 'amount', 'status', 'type', 'success_datetime', 'user_id', 'telco', 'sender'];
    protected $guarded = [];

    public function user()
    {
    return $this->belongsTo(User::class);
    }

    public function sentFrom()
    {
    return $this->hasOne(Sender::class,'id','sender');
    }


}
