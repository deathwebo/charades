@extends('spycodes')

@section('content')
    <div id="app">
        <spycodes-play
            passed-words="{{ json_encode($words) }}"
            reveal-word-url="{{ route("spycodes_reveal_word", ['id' => $id,'wordKey' => '__XXX__']) }}"
            reset-url="{{ route("spycodes_reset", ['id' => $id]) }}"
            home-url="{{ route("spycodes_home") }}"
            timer-url="{{ route("spycodes_timer_toggle", ['id' => $id]) }}"
            game-id="{{ $id }}"
        ></spycodes-play>
    </div>
@endsection

@section('scripts')
    
@endsection