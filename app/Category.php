<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function words()
    {
        return $this->hasMany('App\Word');
    }
}
