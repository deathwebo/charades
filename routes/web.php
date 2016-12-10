<?php

use App\Lib\RandomPlayerSelector;
use App\Lib\SpycodesManager;
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

    $games = \App\Game::all();

    return view('games.create', compact('player', 'game', 'team','games'));
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

    $foundWord = \App\Word::where('name', $name)
        ->where('category_id', $categoryId) 
        ->first();

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

    $game = \App\Game::findOrFail($id);

    $teamName = "team".$team;
    $team = $game->{$teamName};

    if(!in_array($player, unserialize($team->players)) || $team->current_player != $player) {
        return response()->json([
            'response' => false,
            'reason' => 'No coincide el usuario con el equipo que juega'
        ]);
    }

    $word = \App\Word::with('category')
        ->findOrFail($id);

    // 2. We fire the event PlayerStartedTurn with the word
    Event::fire(new \App\Events\PlayerStartedTurn($word->toArray()));

    return response()->json([
        'response' => true
    ]);
})->name('start_turn');

Route::post('game/{id}/turn/finished', function($id, Request $request) {
    //  We must receive: player, team, status
    $player = $request->get('player');
    $team   = $request->get('team');
    $status = $request->get('status');

    $game = \App\Game::findOrFail($id);

    $teamName = "team".$team;
    $team = $game->{$teamName};

    $players = unserialize($team->players);

    if(!in_array($player, $players) || $team->current_player != $player) {
        return response()->json([
            'response' => false,
            'reason' => 'No coincide el usuario con el equipo que juega'
        ]);
    }

    if($status == 'success') {
        $team->score++;
    }

    // Choosing the next player
    $roundPlayers = unserialize($team->round_players);

    if(count($players) == count($roundPlayers)) {
        $roundPlayers = [];
    }

    $playerSelector = new RandomPlayerSelector();

    $nextPlayer = $playerSelector->getRandomPlayer(array_diff($players, $roundPlayers));
    $team->current_player = $nextPlayer;
    $roundPlayers[] = $nextPlayer;

    $team->round_players = serialize($roundPlayers);

    // We switch to the next team on the game
    $teamSwitch = [
        '1' => '2',
        '2' => '1'
    ];

    $game->current_team = $teamSwitch[$game->current_team];

    $team->save();
    $game->save();

    // We fire the event PlayerFinishedTurn with the status

    $team1 = $game->team1;
    $team2 = $game->team2;

    $team1->players = unserialize($team1->players);
    $team2->players = unserialize($team2->players);

    $team1->round_players = unserialize($team1->round_players);
    $team2->round_players = unserialize($team2->round_players);

    $game->team1 = $team1;
    $game->team2 = $team2;

    Event::fire(new \App\Events\PlayerFinishedTurn($game->toArray()));

    return response()->json([
        'response' => true,
        'reason' => ''
    ]);
})->name('finish_turn');

Route::get('spycodes/play', function() {

    $spycodesManager = new SpycodesManager();

    if($spycodesManager->isPlayInCourse()) {
        $words = $spycodesManager->getGeneratedWordsAsArray();
        return view('spycodes.play', compact('words'));
    }

    $words = $spycodesManager->generateWords();

    return view('spycodes.play', compact('words'));
})->name('spycodes_play');

Route::get('spycodes/reset', function() {
    $spycodesManager = new SpycodesManager();

    $words =$spycodesManager->generateWords();

    Event::fire(new \App\Events\SpyCodesResetGame($words));

    return redirect()->route('spycodes_play');
})->name('spycodes_reset');

Route::post('spycodes/revealWord/{wordKey}', function($wordKey) {
    $spycodesManager = new SpycodesManager();

    $word = $spycodesManager->revealWord($wordKey);

    Event::fire(new \App\Events\SpyCodesWordRevealed($wordKey));

    return response()->json(['word' => $word]);
})->name('spycodes_reveal_word');

Route::get('spycodes/view', function() {
    $spycodesManager = new SpycodesManager();

    if(!$spycodesManager->isPlayInCourse()) {
        return 'No existe juego en curso todavía';
    }

    $words = $spycodesManager->getGeneratedWordsAsArray();

    return view('spycodes.view', compact('words'));
})->name('spycodes_view');

Route::post('spycodes/timer/toggle', function() {

    Event::fire(new \App\Events\SpycodesTimerToggle());

    return response()->json(['response' => 'success']);
})->name('spycodes_timer_toggle');