@extends('layouts.admin')

@section('content')


        <!-- Modal -->
        {!! Form::open(['action' => 'AdminController@storeSportType', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new Sport Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    
                        <div class="form-group">
                            {{Form::label('sport_id', 'Sport ID')}}
                            {{Form::text('sport_id', '', ['class' => 'form-control', 'placeholder' => 'ID'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('sport_name', 'Sport name')}}
                            {{Form::text('sport_name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('sport_logo', 'Logo of sport')}}
                            {{Form::file('sport_logo')}}
                        </div>
                    
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                </div>
            </div>
            </div>
        </div>
        {!! Form::close() !!}




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
              <a class="nav-link active" href="/admin">
                <i class="fas fa-map-marked-alt"></i>
                Places to confirm ({{ count($places) }})
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

        <h3 class="mt-4"><i class="fas fa-user"></i> Sport Types</h3>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Sport ID</th>
                <th scope="col">Sport name</th>
                <th scope="col"> <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> Sport Type</button> </th>
              </tr>
            </thead>
            <tbody>

              @if(count($types) > 0)
                @foreach ($types as $key => $type)
                <tr>
                  <th scope="row">{{ ++$key }}</th>
                  <td>{{ $type->id }}</td>
                  <td> <img src="../storage/sport_logo/{{ $type->image }}" alt="{{ $type->name }}"> {{ $type->name }} </td>
                  <td>    
                    <button type="button" class="btn btn-primary float-left"> <i class="far fa-edit"></i> </button> 
                    {!!Form::open(['action' => ['AdminController@deleteSportType', $type->id], 'method' => 'POST', 'class' => 'float-left ml-2'])!!}
                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit','class' => 'btn btn-danger'])}}
                    {!!Form::close()!!}
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
