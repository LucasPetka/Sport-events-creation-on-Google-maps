@extends('layouts.admin')

@section('content')

        
    
    <div class="row">
    <div class="col-lg-3 mx-auto">

        <div class="h4 text-center"> <img src="../../../storage/sport_logo/{{ $type->image }}" alt="{{ $type->name }}"> {{ $type->name}} Update <img src="../../../storage/sport_logo/{{ $type->image_h }}" alt="{{ $type->name }}"></div>

        {!! Form::open(['action' => ['TypeController@update', $type->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
     
            <div class="form-group">
                {{Form::label('sport_name', 'Sport name')}}
                {{Form::text('sport_name', $type->name, ['class' => 'form-control', 'placeholder' => 'Name','required' => 'required'])}}
            </div>
            <div class="form-group">
                {{Form::label('sport_logo', 'Logo of sport')}}<br>
                {{Form::file('sport_logo')}}
            </div>
            <div class="form-group">
                {{Form::label('sport_logo_highlighted', 'Logo of sport - highlighted')}}<br>
                {{Form::file('sport_logo_highlighted')}}
            </div>
            
            
            {{Form::submit('Update', ['class'=>'btn btn-orange float-right'])}}
            <a class="btn btn-secondary float-right mr-2" href="/admin/sporttypes" role="button">Cancel</a>

        {!! Form::close() !!}

    </div>
    </d>        
        


        
        

  @endsection
