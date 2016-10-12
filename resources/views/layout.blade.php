<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Caras y Gestos</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body>
<div class="flex-center position-ref full-height">
    <section class="hero is-primary">
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
                <nav class="nav">
                    <div class="nav-left nav-menu">
                        <a href="" class="nav-item">Jugar</a>
                        <a href="" class="nav-item">Agregar palabras</a>
                    </div>
                </nav>
            </div>
        </div>
    </section>

    @yield('content')

    <section class="footer">
        <div class="container">
            <div class="content has-text-centered">
                <p>Made by Teslark</p>
            </div>
        </div>
    </section>

    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };
    </script>
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
</div>
</body>
</html>
