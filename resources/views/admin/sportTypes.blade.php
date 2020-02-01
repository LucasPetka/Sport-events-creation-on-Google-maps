@extends('layouts.admin')

@section('content')


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
