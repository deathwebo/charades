@extends('spycodes')

@section('content')
    <div class="spycodes_menu">
        <a class="button is-success is-small" href="{{ route('spycodes_reset') }}">
            <i class="fa fa-refresh" aria-hidden="true"></i>
        </a>
        <a class="button is-primary is-small" href="{{ route('home') }}">
            <i class="fa fa-home" aria-hidden="true"></i>
        </a>
    </div>
    <div id="app">
        <spycodes-play
            passed-words="{{ json_encode($words) }}"
            reveal-word-url="{{ route("spycodes_reveal_word", ['wordKey' => '__XXX__']) }}"
        ></spycodes-play>
    </div>
@endsection

@section('scripts')
    
@endsection