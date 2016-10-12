<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = "game_teams";

    public function game()
    {
        return $this->hasOne('App\Game');
    }
}
