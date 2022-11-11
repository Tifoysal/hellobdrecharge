<?php

namespace App;

use App\Traits\InboxModelAttributes;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\SoftDeletes;

class InboxModel extends Model
{
    use  InboxModelAttributes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inbox';

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

    protected $fillable = ['trxid', 'body', 'operator'];


}
