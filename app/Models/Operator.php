<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;
    protected $guarded=[];

//     const $operators=['GP','BL','TT','AT','RB'];
     const OPERATOR=['GP','BL','TT','AT','RB'];
    public function hasPackage()
    {
        return $this->hasMany(Package::class,'operator','id');
    }
}
