<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SPYCODES</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body>
<div class="flex-center position-ref full-height">

    @yield('content')

    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };

        // Hack for then it's displayed through an iFrame with big ass Height
        window.onload = function () {
            if (window.self !== window.top) {
                var containers = document.getElementsByClassName('cards-container');

                if(containers.length > 0) {
                    containers[0].style.maxHeight = "720px";
                }
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.5.0/socket.io.min.js"></script> 
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
</div>
</body>
</html>
