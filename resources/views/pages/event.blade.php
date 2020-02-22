
@extends('layouts.app')

@section('content')
        
<div class="container mt-5">


<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-4 mb-5">


        <div class="card" >
            <SmallMap class="card-img-top mt-3" v-bind:place='{{ $event->place }}' v-bind:size='"width:auto; height: 300px;"'> </SmallMap>
            <div class="card-body">
                <p class="h6"><img src="../storage/sport_logo/{{ $event->place->typee->image }}" alt="{{ $event->place->typee->name }}"> {{ $event->place->title }}</p>
                <hr>
                <p class="card-text">{{ $event->place->about }}</p>
            </div>
          </div>



    </div>

    <div class="col-sm-12 col-md-12 col-lg-8 mb-5">
        <p class="h4">{{ $event->title }}</p>
        <div class="card mb-3">
            <div class="card-body">
                {{ $event->about }}
                <hr>
                <span class="float-left">
                    <i class="far fa-calendar-alt"></i> {{ Carbon\Carbon::parse($event->time_from)->format('Y-m-d') }}
                </span>
                <span class="float-right">
                    <i class="far fa-clock"></i> 
                    {{ Carbon\Carbon::parse($event->time_from)->format('H:i') }}
                    - {{ Carbon\Carbon::parse($event->time_until)->format('H:i') }}
                </span>
            </div>
        </div>

        <chats :user="{{ auth()->user() }}" :event="{{ $event }}" ></chats>
    </div>
</div>





</div>
        
@endsection

