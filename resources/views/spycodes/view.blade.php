@extends('spycodes')

@section('content')
    <div class="spycodes_menu">
        <a class="button is-primary is-small" href="{{ route('spycodes_home') }}">
            <i class="fa fa-home" aria-hidden="true"></i>
        </a>
    </div>
    <div id="app">
        <spycodes-view
            game-id="{{ $id }}"
            passed-words="{{ json_encode($words) }}"
        ></spycodes-view>
    </div>
@endsection

@section('scripts')
    
@endsection