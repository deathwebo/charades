@extends('layout')


@section('content')
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
                <form>
                    <p class="control has-addons">
                        <input class="input" type="text" placeholder="# De Juego">
                        <button class="button is-info">
                            ACCEDER
                        </button>
                    </p>
                </form>
            </section>
        </div>
    </section>
@endsection

@section('scripts')

@endsection
