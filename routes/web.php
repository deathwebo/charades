<?php

use App\Lib\RandomPlayerSelector;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {

//
//    return $team;

    return view('games.create');
//    return view('welcome');
});

Route::get('/admin', function() {
    return view('admin.index');
});

Route::get('words', function() {

    $words = \App\Word::select('name')->get();

    return response()->json([
        'words' => $words->pluck('name')
    ]);
});

Route::post('word', function(Request $request){

    $name = $request->get('name');

    if(!$name) {
        return response()->json([
            'response' => false,
            'reason' => 'No se puede utilizar una palabra vacia'
        ]);
    }

    $foundWord = \App\Word::where('name', $name)->first();

    if($foundWord) {
        return response()->json([
            'response' => false,
            'reason' => 'La palabra ya existe en el sistema'
        ]);
    }

    $newWord = new \App\Word();
    $newWord->name = $name;

    $category = \App\Category::first();

    if($category->words()->save($newWord)) {
        return response()->json([
            'response' => true,
            'reason' => ''
        ]);
    }

    return response()->json([
        'response' => false,
        'reason' => 'Error guardando la palabra en el sistema'
    ]);

});


Route::post('game', function(Request $request){

    $turn_time = $request->get('turnTime');
    $members1 = $request->get('teamMembers1');
    $members2 = $request->get('teamMembers2');

    $playerSelector = new RandomPlayerSelector();

    $player1 = $playerSelector->getRandomPlayer($members1);
    $player2 = $playerSelector->getRandomPlayer($members2);

    $roundPlayers1 = serialize([$player1]);
    $roundPlayers2 = serialize([$player2]);

    $team1 = new \App\Team();
    $team2 = new \App\Team();

    $team1->players = serialize($members1);
    $team2->players = serialize($members2);

    $team1->round_players = $roundPlayers1;
    $team2->round_players = $roundPlayers2;

    $team1->current_player = $player1;
    $team2->current_player = $player2;

    $team1->save();
    $team2->save();

    $game = new \App\Game();

    $game->turn_time = $turn_time;
    $game->team_1 = $team1->id;
    $game->team_2 = $team2->id;

    $game->save();

    return response()->json([
        'id' => $game->id
    ]);
});

Route::get('game/{id}', function($id){
    $game = \App\Game::with('team1')
        ->with('team2')
        ->findOrFail($id);

    $team1 = $game->team1;
    $team2 = $game->team2;

    $team1->players = unserialize($team1->players);
    $team2->players = unserialize($team2->players);

    $team1->round_players = unserialize($team1->round_players);
    $team2->round_players = unserialize($team2->round_players);

    $game->team1 = $team1;
    $game->team2 = $team2;

    return view('games.view', [
        'game' => $game
    ]);
})->name('view_game');

/* Para entrar a juego en curso y hacer login */
Route::get('game/login', function() {

});

/* Para jugar el juego en curso */
Route::get('game/play', function() {

});
