<?php

namespace App;

use App\Traits\OperatorsModelAttributes;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Foundation\Auth\User as Authenticatable;

class OperatorModel extends Model
{
    use OperatorsModelAttributes;
    protected $table = 'operators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'op_id';
    protected $fillable = [
        'opname',
        'telco',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];
}
