<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = [];

    public function class()
    {
        return $this->belongsTo('App\SClass');
    }

    public function assigned_students()
    {
        return $this->hasMany('App\AssignedStudent');
    }
}
