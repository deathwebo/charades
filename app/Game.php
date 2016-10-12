<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function team1()
    {
        return $this->belongsTo('App\Team', 'team_1', 'id');
    }

    public function team2()
    {
        return $this->belongsTo('App\Team', 'team_2', 'id');
    }
}
