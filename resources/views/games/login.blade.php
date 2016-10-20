@extends('layout')

@section('content')
<section id="app" class="section">
    <div class="container">
        <div class="heading">
            <h1 class="title">Participa en el juego {{ $game->id }}, ¿Quién eres tu?</h1>
        </div>
        <section class="section">
            <div class="columns">
                <div class="column">
                    <h2 class="subtitle">Equipo 1</h2>
                    <ul>
                        @foreach($game->team1->players as $player)
                            <li>
                                <button class="button playerSelect" data-team="1">{{$player}}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="column">
                    <h2 class="subtitle">Equipo 2</h2>
                    <ul>
                        @foreach($game->team2->players as $player)
                            <li>
                                <button class="button playerSelect" data-team="2">{{$player}}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <form id="register_form" method="post" action="{{ route('register_game_player') }}">
        {{ csrf_field() }}
        <input type="hidden" name="game" value="{{ $game->id }}">
        <input type="hidden" id="team" name="team" value="">
        <input type="hidden" id="player" name="player" value="">
    </form>

</section>
@endsection

@section('scripts')

<script type="text/javascript">
    $(function() {
        $('.playerSelect').on('click', function() {
            $('#player').val($(this).text());
            $('#team').val($(this).data('team'));
            $('#register_form').submit();
        });
    });
</script>

@endsection