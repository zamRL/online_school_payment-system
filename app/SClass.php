<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SClass extends Model
{
    protected $guarded = [];
    protected $table = 'classes';

    public function sections()
    {
        return $this->hasMany('App\Section', 'class_id', 'id')
            ->orderBy('name', 'asc');
    }
}
