@extends('layout')


@section('content')

    @if($player && $game && $team)
        <section class="section">
            <div class="container">
                <div class="heading">
                    <div class="title">Regresar al juego anterior</div>
                </div>
                <a class="button is-link" href="{{ route('game_play', ['id' => $game]) }}">REGRESAR</a>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            <div class="heading">
                <h1 class="title">Crear nuevo juego</h1>
            </div>

            <section class="section">
                <div id="app">

                    <div class="container">
                        <create-game game-url="{{ route('view_game', ['id' => 'XXX']) }}"></create-game>
                    </div>

                </div>
            </section>
        </div>

    </section>

    <section class="section">
        <div class="container">
            <div class="heading">
                <h1 class="title">Acceder a juego en curso</h1>
            </div>

            <section class="section">
                <div class="columns">

                    <div class="column">
                        <form method="post" action="{{ route('game_login') }}">
                            {{ csrf_field() }}
                            <p class="control has-addons">
                                <input class="input" type="text" placeholder="# De Juego" name="id">
                                <button class="button is-info">
                                    ACCEDER
                                </button>
                            </p>
                        </form>
                    </div>

                    <div class="column">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Juego</th>
                                    <th>Ver juego</th>
                                    <th>Jugar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($games as $game)
                                    <tr>
                                        <td>{{ $game->id }}</td>
                                        <td>
                                            <a class="button is-outlined is-info"
                                                href="{{ route('view_game', ['id' => $game->id]) }}">
                                                Ver
                                            </a>
                                        </td>
                                        <td>
                                            <form method="post" action="{{ route('game_login') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{ $game->id }}">
                                                <button class="button is-outlined is-success">
                                                    jugar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                    
                    </div>

                </div>
            </section>
        </div>
    </section>


@endsection

@section('scripts')

@endsection
