@extends('layout')

@section('content')

    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h2 class="subtitle">Hola {{ $player }}, tu palabra es:</h2>
                <h1 class="title">{{ $word->name }}</h1>
                <h1 class="subtitle">De la categoría: {{ $word->category->name }}</h1>
            </div>
        </div>
    </section>

    <div id="app">
        <play-game
            passed-word="{{ $word }}"
            team="{{ $team }}"
            player="{{ $player }}"
            start-url="{{ route('start_turn', ['id' => 'XXX']) }}"
            finish-url="{{ route('finish_turn', ['id' => 'XXX']) }}"
            game-id="{{ $game->id }}"
        ></play-game>
    </div>

@endsection