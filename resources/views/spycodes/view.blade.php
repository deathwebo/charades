@extends('spycodes')

@section('content')
    <div class="spycodes_menu">
        <a 
            class="button is-primary"
            href="{{ route('home') }}"
        >
            <i class="fa fa-home" aria-hidden="true"></i>
        </a>
    </div>
    <div id="app">
        <spycodes-view
            passed-words="{{ json_encode($words) }}"
        ></spycodes-view>
    </div>
@endsection

@section('scripts')
    
@endsection