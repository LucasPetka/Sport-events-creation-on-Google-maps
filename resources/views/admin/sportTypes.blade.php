@extends('layouts.admin')

@section('content')

      @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show">
            <h5>Error!</h5>
              <ul class="list-unstyled mb-0 ">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
      @endif

      <!---------------Successful and Error messages------------------>
      @if (session('success'))
      <div class="position-absolute alert alert-success alert-dismissible fade show" style="right:50px;" role="alert">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      @endif
      @if (session('error'))
      <div class="position-absolute alert alert-danger alert-dismissible fade show" style="right:50px;" role="alert">
          {{ session('error') }}

          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      @endif


        <!-- Modal -->
        {!! Form::open(['action' => 'TypeController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
                            {{Form::label('sport_name', 'Sport name')}}
                            {{Form::text('sport_name', '', ['class' => 'form-control', 'placeholder' => 'Name','required' => 'required'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('sport_logo', 'Logo of sport')}}<br>
                            {{Form::file('sport_logo')}}
                        </div>
                        <div class="form-group">
                          {{Form::label('sport_logo_highlighted', 'Logo of sport - highlighted')}}
                          {{Form::file('sport_logo_highlighted')}}
                        </div>
                    
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{Form::submit('Submit', ['class'=>'btn btn-orange'])}}
                </div>
            </div>
            </div>
        </div>
        {!! Form::close() !!}


        <h3 class="mt-4"><i class="far fa-futbol text-orange-secondary"></i> Sport Types</h3>
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
                    
                    <a class="btn btn-primary float-left" href="/admin/sporttypes/edit/{{ $type->id }}" role="button"><i class="far fa-edit"></i></a>
                    
                    {!!Form::open(['action' => ['TypeController@destroy', $type->id], 'method' => 'POST', 'class' => 'float-left ml-2'])!!}
                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit','class' => 'btn btn-danger'])}}
                    {!!Form::close()!!}
                  </td>
                </tr>
                @endforeach
              @endif

            </tbody>
        </div>

  @endsection
