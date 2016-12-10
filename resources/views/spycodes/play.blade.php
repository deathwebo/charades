@extends('spycodes')

@section('content')
    <div id="app">
        <spycodes-play
            passed-words="{{ json_encode($words) }}"
            reveal-word-url="{{ route("spycodes_reveal_word", ['wordKey' => '__XXX__']) }}"
            reset-url="{{ route("spycodes_reset") }}"
            home-url="{{ route("home") }}"
            timer-url="{{ route("spycodes_timer_toggle") }}"
        ></spycodes-play>
    </div>
@endsection

@section('scripts')
    
@endsection