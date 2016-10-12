<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function team1()
    {
        return $this->belongsTo('App\Team', 'id', 'team_1');
    }

    public function team2()
    {
        return $this->belongsTo('App\Team', 'id', 'team_2');
    }
}
