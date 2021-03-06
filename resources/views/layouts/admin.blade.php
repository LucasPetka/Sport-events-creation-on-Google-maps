<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="overflow-y: auto;">
    <head style="font-family: 'Roboto', sans-serif;">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>window.laravel = { csrfToken: '{{ csrf_token() }}'} </script>


        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

        <link rel="dns-prefetch" href="https://maps.gstatic.com">
        <link rel="dns-prefetch" href="https://maps.googleapis.com">
        <link rel="preconnect" href="https://maps.gstatic.com">
        <link rel="preconnect" href="https://maps.googleapis.com">
        
    </head>
<body>
  
    <div id="app">
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background: linear-gradient(0deg, rgba(37,57,48,1) 0%, rgba(50,69,68,1) 100%);">
          <div class="container">
            <a class="navbar-brand" href="/">
              <img src="/images/logonotext.png" height="30px" weight="100%"> MoSi</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
              </ul>

              <form class="form-inline my-2 my-lg-0">
                  <a href ="/home"  class="btn btn-outline-light mr-2"> <i class="fas fa-chevron-left"></i> Back to profile</a>
              </form>
            </div>
          </div>
          </nav>

          <div class="container-fluid" style="height:94vh;">
            <div class="row" style="height:100%;">
              <nav class="col-md-2 d-none d-md-block bg-light sidebar shadow">
                <div class="sidebar-sticky">
        
                  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Admin dashboard</span>
                    <a class="d-flex align-items-center text-muted" href="#">
                      <span data-feather="plus-circle"></span> 
                    </a>
                  </h6>
        
                  <ul class="nav flex-column">
                    <li class="nav-item">
                      <a class="nav-link active" href="/admin">
                        <i class="fas fa-user-shield"></i>
                        Admin home
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active" dusk="places_to_confirm_btn" href="/admin/places_to_confirm">
                        <i class="fas fa-map-marked-alt"></i>
                        @if(is_countable($places))
                          Places to confirm ({{ $places_count }}) <span class="sr-only">(current)</span>
                        @else
                          Places to confirm ({{ $places }}) <span class="sr-only">(current)</span>
                        @endif
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active" href="/admin/events_to_confirm">
                        <i class="fas fa-calendar-alt"></i>
                        @if(is_countable($events))
                          Events to confirm ({{ $events_count }}) <span class="sr-only">(current)</span>
                        @else
                          Events to confirm ({{ $events }}) <span class="sr-only">(current)</span>
                        @endif
                      </a>
                    </li>
                  </ul>

                  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Static data</span>
                    <a class="d-flex align-items-center text-muted" href="#">
                      <span data-feather="plus-circle"></span> 
                    </a>
                  </h6>

                  <ul class="nav flex-column">
                    <li class="nav-item">
                      <a class="nav-link" href="/admin/users">
                        <i class="fas fa-user"></i>
                        Users
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="/admin/places">
                        <i class="fas fa-map-marker-alt"></i>
                        Places
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="/admin/sporttypes">
                        <i class="far fa-futbol"></i>
                        Sport Types
                      </a>
                    </li>
                  </ul>
                </div>
              </nav>
          
              <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 mt-4">
        
                @yield('content')
              
              </main>
            </div>
          </div>

    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
