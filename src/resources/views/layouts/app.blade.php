<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <style>
        .main-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
        }
    </style>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app" class="main-container">
        @if(\Session::has('success'))
        <div class="container position-relative d-flex flex-row-reverse">
            <div style="z-index: 1;" class="notification-alert alert alert-success position-absolute m-0 mt-1 col-md-4">
                <p class="m-0 text-center font-weight-bold">
                    {!! \Session::get('success') !!}
                </p>
            </div>
        </div>
        @elseif(\Session::has('error'))
        <div class="container position-relative d-flex flex-row-reverse">
            <div style="z-index: 1;" class="notification-alert alert alert-danger position-absolute m-0 mt-1 col-md-4">
                <p class="m-0 text-center font-weight-bold">
                    {!! \Session::get('error') !!}
                </p>
            </div>
        </div>
        @elseif ($errors->any())
        <div class="container position-relative d-flex flex-row-reverse">
            <div style="z-index: 1;" class="notification-alert alert alert-danger position-absolute m-0 mt-1 col-md-4">
                <p class="m-0 text-center font-weight-bold">
                    {{ __('Ingresa los datos solicitados para continuar') }}
                </p>
            </div>
        </div>
        @endif

        <nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand font-weight-bold" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                @auth
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @endauth

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @auth
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->firstname }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Cerrar sesión') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="main-content py-4">
            @yield('content')
        </main>

        <div class="footer bg-dark py-4 text-white text-center">
            <p class="m-0 font-weight-bold">
                {!! __('Ticket Service. Todos los derechos reservados &copy;') !!}
            </p>
        </div>
    </div>
    <script>
        //URL TO UPDATE USER IN EDIT MODAL
        var url_admin_update = '{{route("admin.update", ":id")}}';
    </script>
</body>

</html>