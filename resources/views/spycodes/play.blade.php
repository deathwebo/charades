@extends('spycodes')

@section('content')
    <a  
        class="button is-success"
        href="{{ route('spycodes_reset') }}" 
        style="position:absolute;top:5px; right: 5px; z-index: 999"
    >Reset</a>
    <div id="app">
        <spycodes-play
            passed-words="{{ json_encode($words) }}"
        ></spycodes-play>
    </div>
@endsection

@section('scripts')
    
@endsection