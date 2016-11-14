@extends('spycodes')

@section('content')
    <div id="app">
        <spycodes-view
            passed-words="{{ json_encode($words) }}"
        ></spycodes-view>
    </div>
@endsection

@section('scripts')
    
@endsection