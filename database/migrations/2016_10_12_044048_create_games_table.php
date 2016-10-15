<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->integer('team_1')->unsigned()->nullable();
            $table->integer('team_2')->unsigned()->nullable();
            $table->integer('turn_time');
            $table->smallInteger('current_team')->default(1);
            $table->timestamps();
        });

        Schema::table('games', function(Blueprint $table) {
            $table->foreign('team_1')->references('id')->on('game_teams');
            $table->foreign('team_2')->references('id')->on('game_teams');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
