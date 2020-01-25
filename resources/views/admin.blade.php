@extends('layouts.admin')

@section('content')

  <div class="container-fluid" style="height:94vh;">
    <div class="row" style="height:100%;">
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
              <a class="nav-link active" href="/admin">
                <i class="fas fa-map-marked-alt"></i>
                Places to confirm ({{ count($places) }}) <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/admin/users">
                <i class="fas fa-user"></i>
                Users
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/admin/sporttypes">
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

        <h3 class="mt-4"><i class="fas fa-map-marked-alt"></i> Places waiting for confirmation</h3>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Sport type</th>
                <th scope="col">User</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>

              @if(count($places) > 0)
                @foreach ($places as $key => $place)
                <tr>
                  <th scope="row">{{ ++$key }}</th>
                  <td>{{ $place->title }}</td>

                  @foreach ($types as $type)
                    @if($type->id == $place->type)
                      <td><img src="../storage/sport_logo/{{ $type->image }}" alt="{{ $type->name }}"> {{ $type->name }}</td>
                    @endif
                  @endforeach

                  @foreach ($users as $user)
                    @if($user->id == $place->personid)
                      <td>{{ $user->name }}</td>
                    @endif
                  @endforeach

                  <td>
                      <button type="button" class="btn btn-primary mr-4" data-toggle="modal" data-target="#placeid{{ $place->id }}"> Open </button>
                      <a href ="/admin/accplace/{{ $place->id }}"  class="btn btn-success mr-2" data-toggle="tooltip" data-placement="top" title="Publish new place" ><i class="fas fa-check"></i></a>
                      <a href ="/admin/decplace/{{ $place->id }}"  class="btn btn-danger mr-2" data-toggle="tooltip" data-placement="top" title="Decline this place"><i class="fas fa-times"></i></a>
                  </td>
                </tr>
                @endforeach
              @endif

            </tbody>
          </table>
        </div>

        @if(count($places) > 0)
          @foreach ($places as $key => $place)
            <div class="modal fade" id="placeid{{ $place->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      @foreach ($types as $type)
                        @if($type->id == $place->type)
                        <small><img src="../storage/sport_logo/{{ $type->image }}" alt="{{ $type->name }}"></small>
                        @endif
                      @endforeach
                      
                      
                      {{ $place->title }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-7">
                            <SmallMap v-bind:place='{{ $place }}' v-bind:size='"width:auto; height: 500px;"'> </SmallMap>
                        </div>
                        <div class="col-lg-5">
                            {{ $place->about }}
                            <hr>
                            @foreach ($types as $type)
                              @if($type->id == $place->type)
                              <small><img src="../storage/sport_logo/{{ $type->image }}" alt="{{ $type->name }}"> {{ $type->name }}</small>
                              @endif
                            @endforeach
                            <br>
                            @foreach ($users as $user)
                              @if($user->id == $place->personid)
                                <small>Created by: {{ $user->name }}</small>
                              @endif
                            @endforeach
                            <hr>
                            <a href ="/admin/accplace/{{ $place->id }}"  class="btn btn-success mr-2"> Accept <i class="fas fa-check"></i></a>
                            <a href ="/admin/decplace/{{ $place->id }}"  class="btn btn-danger mr-2"> Decline <i class="fas fa-times"></i></a>
                        </div>

                    </div>
                    


                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        @endif


      </main>
    </div>
  </div>
  @endsection
