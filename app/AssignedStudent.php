<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedStudent extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function section()
    {
        return $this->belongsTo('App\Section');
    }
}
