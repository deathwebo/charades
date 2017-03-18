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
                        <h1 class="title">Teslark Games</h1>
                        <h2 class="subtitle">Juega caras y gestos y Spycodes!</h2>
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
                        <a href="{{ route('charades_home') }}" class="nav-item">
                            Caras y gestos
                            <span class="icon">
                                <i class="fa fa-smile-o"></i>
                            </span>
                        </a>
                        <a href="{{ route('admin') }}" class="nav-item">
                            Agregar palabras
                            <span class="icon">
                                <i class="fa fa-book"></i>
                            </span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </section>

    @if (session('message'))
        <section class="section">
            <div class="notification {{ session('messageClass') }}">
                {{ session('message') }}
            </div>
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
