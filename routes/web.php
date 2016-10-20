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

Route::get('/', function (Request $request) {

    $player = $request->session()->get('player');
    $game = $request->session()->get('game');
    $team = $request->session()->get('team');

    Event::fire(new \App\Events\PlayerStartedTurn(['foo' => 'bar']));

    return view('games.create', compact('player', 'game', 'team'));
})->name('home');

Route::get('/admin', function() {

    $categories = \App\Category::all();

    return view('admin.index', compact('categories'));
})->name('admin');

Route::get('words', function() {

    $words = \App\Word::select('name')->get();

    return response()->json([
        'words' => $words->pluck('name')
    ]);
});

Route::post('word', function(Request $request){

    $name = $request->get('name');
    $categoryId = $request->get('category');

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

    if(!$categoryId) {
        $category = \App\Category::first();
    }
    else {
        $category = \App\Category::find($categoryId);
    }

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
Route::post('game/login', function(Request $request) {
    $id = $request->get('id');

    $game = \App\Game::with('team1')
        ->with('team2')
        ->findOrFail($id);

    $team1 = $game->team1;
    $team2 = $game->team2;

    $team1->players = unserialize($team1->players);
    $team2->players = unserialize($team2->players);

    $game->team1 = $team1;
    $game->team2 = $team2;

    return view('games.login', [
        'game' => $game
    ]);

})->name('game_login');

Route::post('/game/playerRegistration', function(Request $request){
    $player = $request->get('player');
    $game = $request->get('game');
    $team = $request->get('team');

    $request->session()->put('player', $player);
    $request->session()->put('game', $game);
    $request->session()->put('team', $team);

    return redirect()->route('game_play', ['id' => $game]);

})->name('register_game_player');

/* Para jugar el juego en curso */
Route::get('game/play/{id}', function($id, Request $request) {
    $game = \App\Game::findOrFail($id);

    $players = [];
    $players[1] = unserialize($game->team1->players);
    $players[2] = unserialize($game->team2->players);

    $player = $request->session()->get('player');
    $registeredGame = $request->session()->get('game');
    $team = $request->session()->get('team');

    if( $game->id != $registeredGame || !isset($players[$team]) || !in_array($player, $players[$team]) ) {
        return redirect()->route('home')
            ->with(
                'message',
                'Tu usuario registrado ya no concuerda con la configuración del juego'
            )
            ->with('messageClass', 'is-danger');
    }


    if($team != $game->current_team) {
        return redirect()->route('home')
            ->with(
                'message',
                'Todavía no es turno de tu equipo'
            )
            ->with('messageClass', 'is-info');
    }

    $teamName = 'team'.$team;

    if($game->{$teamName}->current_player != $player) {
        return redirect()->route('home')
            ->with(
                'message',
                'Todavía no es turno de jugar'
            )
            ->with('messageClass', 'is-info');
    }

    $word = \App\Word::inRandomOrder()->first();

    return view('games.play', compact('game', 'player', 'team', 'registeredGame', 'word'));
})->name('game_play');

Route::post('game/{id}/turn/started', function($id, Request $request) {
    // 1. We must receive: player, wordId
    $player = $request->get('player');
    $team   = $request->get('team');
    $wordId = $request->get('word');

    $game = \App\Game::findOrFail($id);

    $teamName = "team".$team;
    $team = $game->{$teamName};

    if(!in_array($player, $team->players) || $team->current_player != $player) {
        return response()->json([
            'response' => false,
            'reason' => 'No coincide el usuario con el equipo que juega'
        ]);
    }

    $word = \App\Word::findOrFail($wordId);

    // 2. We fire the event PlayerStartedTurn with the word
    Event::fire(new \App\Events\PlayerStartedTurn($word));

    return response()->json([
        'response' => true
    ]);
});

Route::post('game/{id}/turn/finished', function($id, Request $request) {
    // 1. We must receive: player, status
    $player = $request->get('player');
    $team   = $request->get('team');
    $status = $request->get('status');

    $game = \App\Game::findOrFail($id);

    $teamName = "team".$team;
    $team = $game->{$teamName};

    if(!in_array($player, $team->players) || $team->current_player != $player) {
        return response()->json([
            'response' => false,
            'reason' => 'No coincide el usuario con el equipo que juega'
        ]);
    }

    // 2. We switch to the next user and team on the game
    if($game->current_team == 1) {
        $game->current_team = 2;
    }

    if($game->current_team == 2) {
        $game->current_team = 1;
    }

    // Moving current player to the round players stack
    $players = unserialize($team->players);
    $roundPlayers = unserialize($team->round_players);
    $roundPlayers[] = $player;

    // If the round players equals the total players we reset the stack
    if(count($players) == count($roundPlayers)) {
        $roundPlayers = [];
    }

    // Choosing the next player

    $playerSelector = new RandomPlayerSelector();

    $nextPlayer = $playerSelector->getRandomPlayer(array_diff($players, $roundPlayers));

    $team->current_player = $nextPlayer;

    // 3. We adjust the score
    if($status == 'success') {
        $team->score++;
    }

    $game->save();
    $team->save();
    // 4. We fire the event PlayerFinishedTurn with the status

    $team1 = $game->team1;
    $team2 = $game->team2;

    $team1->players = unserialize($team1->players);
    $team2->players = unserialize($team2->players);

    $team1->round_players = unserialize($team1->round_players);
    $team2->round_players = unserialize($team2->round_players);

    $game->team1 = $team1;
    $game->team2 = $team2;
    Event::fire(new \App\Events\PlayerFinishedTurn($game));
});
