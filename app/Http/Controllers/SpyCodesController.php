<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\SpycodesManager;
use App\Http\Requests;

class SpyCodesController extends Controller
{
    public function index()
    {
        $games = \App\SpycodesGame::orderBy('created_at', 'desc')->get();

        return view('spycodes.index', ['games' => $games]);
    }

    public function play($id, Request $request)
    {
        $game = \App\SpycodesGame::find($id);

        if($game->hasPassword && !$request->session()->get("auth.{$id}", false) ) {
            return redirect()->route("spycodes_home")
                ->with('message', 'No tienes permiso para acceder a ese juego')
                ->with('messageClass', 'is-danger');
        }

        $spycodesManager = new SpycodesManager();

        $words = $spycodesManager->getGeneratedWordsAsArray($id);
        if (!$words) {
            $words = $spycodesManager->generateWords($id);
        }

        return view('spycodes.play', compact('id','words'));
    }

    public function reset($id)
    {
        $spycodesManager = new SpycodesManager();

        $words =$spycodesManager->generateWords($id);

        \Event::fire(new \App\Events\SpyCodesResetGame($words, $id));

        return redirect()->route('spycodes_play', ['id' => $id]);
    }

    public function revealWord($id, $wordKey)
    {
        $spycodesManager = new SpycodesManager();

        $word = $spycodesManager->revealWord($id, $wordKey);

        \Event::fire(new \App\Events\SpyCodesWordRevealed($wordKey, $id));

        return response()->json(['word' => $word]);
    }

    public function view($id)
    {
        $spycodesManager = new SpycodesManager();

        $words = $spycodesManager->getGeneratedWordsAsArray($id);

        return view('spycodes.view', compact('id','words'));
    }

    public function toggleTimer($id)
    {
        \Event::fire(new \App\Events\SpyCodesTimerToggle($id));

        return response()->json(['response' => 'success']);
    }

    public function createGame(Request $request)
    {
        $name = $request->get('name');
        $hasPassword = $request->get('hasPassword');
        $password = $request->get('password');

        $game = new \App\SpycodesGame();

        $game->name = $name;
        $game->hasPassword = $hasPassword;

        if($hasPassword) {
            $game->password = \Hash::make($password);
        }

        if(! $game->save()) {
            return response()->json([
                'response' => false
            ]);
        }

        $spycodesManager = new SpycodesManager();
        $spycodesManager->generateWords($game->id);

        return response()->json([
            'response' => true,
            'gameId' => $game->id
        ]);
    }

    public function validatePassword(Request $request)
    {
        $gameId   = $request->get('id');
        $password = $request->get('password');

        if(!$gameId || !$password) {
            return response('', 401);
        }

        $game = \App\SpycodesGame::find($gameId);

        if(\Hash::check($password, $game->password)) {

            $request->session()->set("auth.{$gameId}", true);

            return response('');
        }

        return response('', 401);
    }

}
