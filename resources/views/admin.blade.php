@extends('layouts.admin')

@section('content')

  <div class="container-fluid vh-100">
    <div class="row h-100">
      <nav class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Admin dashboard</span>
            <a class="d-flex align-items-center text-muted" href="#">
              <span data-feather="plus-circle"></span>
            </a>
          </h6>

          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#">
                <i class="fas fa-map-marked-alt"></i>
                Places to confirm ({{ count($places) }}) <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="fas fa-user"></i>
                Users
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="far fa-futbol"></i>
                Sport Types
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="fas fa-stream"></i>
                Users streams
              </a>
            </li>
          </ul>
        </div>
      </nav>
  
      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

        <h3 class="mt-4">Places waiting for confirmation</h3>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">About</th>
                <th scope="col">Sport type</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>

              @if(count($places) > 0)
                @foreach ($places as $key => $place)
                <tr>
                  <th scope="row">{{ ++$key }}</th>
                  <td>{{ $place->title }}</td>
                  <td>{{ $place->about }}</td>
                    @foreach ($types as $type)
                      @if($type->id == $place->type)
                      <td>{{ $type->name }}</td>
                      @endif
                    @endforeach
                  <td>
                      <a href ="/admin/accplace/{{ $place->id }}"  class="btn btn-success mr-2"><i class="fas fa-check"></i></a>
                      <a href ="/admin/decplace/{{ $place->id }}"  class="btn btn-danger mr-2"><i class="fas fa-times"></i></a>
                  </td>
                </tr>
                @endforeach
              @endif

            </tbody>
        </div>


      </main>
    </div>
  </div>
  @endsection
