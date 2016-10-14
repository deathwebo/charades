<?php

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

//    $team1 = new \App\Team();
//    $team2 = new \App\Team();
//
//    $team1->users = serialize(['erick', 'temy']);
//    $team2->users = serialize(['fermin', 'picazo']);
//
//    $team1->save();
//    $team2->save();
//
//    $game = new \App\Game();
//
//    $game->turn_time = 120;
//    $game->team_1 = $team1->id;
//    $game->team_2 = $team2->id;
//
//
//    $game->save();
//
//    $team = \App\Game::find($game->id)->team1()->get();
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
