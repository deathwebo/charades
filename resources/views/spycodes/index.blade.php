@extends('layout')

@section('content')

<section class="section">
    <div class="container">
        <div id="app">
            <spycodes-home
                passed-games="{{ json_encode($games) }}"
                create-game-url="{{ route("spycodes_game_create") }}"
                game-view-url="{{ route("spycodes_view", ['id' => '__XXX__']) }}"
                game-play-url="{{ route("spycodes_play", ['id' => '__XXX__']) }}"
                validate-password-url="{{ route("spycodes_validate_password") }}"
            ></spycodes-home>
        </div>
    </div>
</section>

@endsection

@section('scripts')

@endsection