@extends('layout')

@section('content')

    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h2 class="subtitle">Hola {{ $player }}, tu palabra es:</h2>
                <h1 class="title">{{ $word->name }}</h1>
                <h1 class="subtitle">De la categoría: {{ $word->category->name }}</h1>
            </div>
        </div>
    </section>

    <section class="hero">
        <div class="hero-body">
            <div class="container">

                <h1 class="title is-1 has-text-centered">00:00:00</h1>

                <div class="columns has-text-centered">
                    <div class="column">
                        <button class="button is-large is-success">COMENZAR</button>
                    </div>
                </div>

                <div class="columns">
                    <div class="column has-text-right">
                        <button class="button is-large is-primary">ACERTASTE</button>
                    </div>
                    <div class="column has-text-left">
                        <button class="button is-large is-danger">FALLASTE</button>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection