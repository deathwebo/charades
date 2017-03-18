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

Route::get('/charades', 'CharadesController@index')->name('charades_home');
Route::get('/admin', 'CharadesController@admin')->name('admin');
Route::get('words', 'CharadesController@words');
Route::post('word', 'CharadesController@word');
Route::post('game', 'CharadesController@game');
Route::get('game/{id}', 'CharadesController@viewGame')->name('view_game');
/* Para entrar a juego en curso y hacer login */
Route::post('game/login', 'CharadesController@gameLogin')->name('game_login');
Route::post('/game/playerRegistration', 'CharadesController@playerRegistration')->name('register_game_player');
/* Para jugar el juego en curso */
Route::get('game/play/{id}', 'CharadesController@playGame')->name('game_play');
Route::post('game/{id}/turn/started', 'CharadesController@startTurn')->name('start_turn');
Route::post('game/{id}/turn/finished', 'CharadesController@finishTurn')->name('finish_turn');

// SpyCodes routes
Route::get('/', 'SpyCodesController@index')->name('spycodes_home');
Route::get('spycodes/play/{id}', 'SpyCodesController@play')->name('spycodes_play');
Route::get('spycodes/reset/{id}', 'SpyCodesController@reset')->name('spycodes_reset');
Route::post('spycodes/revealWord/{id}/{wordKey}', 'SpyCodesController@revealWord')->name('spycodes_reveal_word');
Route::get('spycodes/view/{id}', 'SpyCodesController@view')->name('spycodes_view');
Route::post('spycodes/timer/toggle/{id}','SpyCodesController@toggleTimer')->name('spycodes_timer_toggle');
Route::post('spycodes/game/create', 'SpyCodesController@createGame')->name('spycodes_game_create');
Route::post('spycodes/validatePassword', 'SpyCodesController@validatePassword')->name('spycodes_validate_password');