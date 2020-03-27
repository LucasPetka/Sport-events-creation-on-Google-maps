<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="overflow-y: auto; height: 100%;">
    <head style="font-family: 'Roboto', sans-serif;">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"  integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"  crossorigin="anonymous">
        
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

        
</head>
<body style="background-image: url('https://www.transparenttextures.com/patterns/diagmonds-light.png'); height: 100%;">
    <div id="app" class="mh-100">
        <nav class="navbar navbar-expand-md navbar-dark shadow-lg" style="background-color: #253938; z-index:100;">
            <div class="container">
                <a class="navbar-brand white font-weight-bold" href="{{ url('/') }}">
                    <img src="/images/logonotext.png" height="25px" weight="100%">
                    MoSi
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item">
                            <a class="btn btn-orange mr-5" href="/find_event"><i class="fas fa-search"></i> Event finder</a>
                        </li>

                        @guest
                            <li class="nav-item">
                                
                                <a class="nav-link text-white" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> {{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle white" style="text-transform: capitalize;" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="z-index: 100;">

                                    <a class="dropdown-item" href="/home"><i class="fas fa-user-alt"></i> Profile</a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>

                                    

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

        <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
