<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Caras y Gestos</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body>
<div class="flex-center position-ref full-height">
    <section class="hero is-info">
        <div class="hero-head">
            
        </div>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column">
                        <h1 class="title">Caras y Gestos</h1>
                        <h2 class="subtitle">Juega caras y gestos con tus amigos!</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-foot">
            <div class="container">
                <nav class="tabs is-boxed">
                    <div class="nav-left nav-menu">
                        <a href="/" class="nav-item">
                            Inicio
                            <span class="icon">
                                <i class="fa fa-home"></i>
                            </span>
                        </a>
                        <a href="{{ route('admin') }}" class="nav-item">
                            Agregar palabras
                            <span class="icon">
                                <i class="fa fa-book"></i>
                            </span>
                        </a>
                        <a href="{{ route('spycodes_play') }}" class="nav-item">
                            Capitanes - SPYCODES
                            <span class="icon">
                                <i class="fa fa-user-secret"></i>
                            </span>
                        </a>
                        <a href="{{ route('spycodes_view') }}" class="nav-item">
                            Tablero - SPYCODES
                            <span class="icon">
                                <i class="fa fa-gamepad"></i>
                            </span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </section>

    @if (session('message'))
        <section class="section">
            <article class="message {{ session('messageClass') }}">
                <div class="message-header">Mensaje</div>
                <div class="message-body">{{ session('message') }}</div>
            </article>
        </section>
    @endif

    @yield('content')

    <section class="footer">
        <div class="container">
            <div class="content has-text-centered">
                <p> 
                    Made by Teslark
                    <span class="fa-stack fa-lg" aria-hidden="true">
                        <i class="fa fa-square-o fa-stack-2x" style="font-size: 2em;"></i>
                        <i class="fa fa-heart fa-stack-1x"></i>
                    </span>

                </p>
            </div>
        </div>
    </section>

    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.5.0/socket.io.min.js"></script> 
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
</div>
</body>
</html>
