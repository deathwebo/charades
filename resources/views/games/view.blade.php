@extends('layout')


@section('content')
    <section class="section">
        <div class="container">
            <div class="heading">
                <h1 class="title">Juego: {{ $game->id }}</h1>
            </div>

            <section class="section">
                <div id="app">
                    <view-game :passed-game="{{ $game }}"></view-game>
                </div>
            </section>
        </div>

    </section>
@endsection

@section('scripts')



@endsection
