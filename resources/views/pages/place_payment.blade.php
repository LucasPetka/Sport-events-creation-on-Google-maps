
@extends('layouts.app')

@section('content')
        
<div class="container mt-5">


<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-4 mb-5">


        <div class="card" >
            <SmallMap class="card-img-top" v-bind:place='{{ $place }}' v-bind:size='"width:auto; height: 300px;"'> </SmallMap>
            <div class="card-body">
                <p class="h6"><img src="../storage/sport_logo/{{ $place->typee->image }}" alt="{{ $place->title }}"> {{ $place->title }}</p>
                <hr>
                <p class="card-text">{{ $place->about }}</p>
            </div>
          </div>



    </div>

    <div class="col-sm-12 col-md-12 col-lg-8 mb-5">

        @if($place->highlighted)
            <h4>This place is already published until {{ Carbon\Carbon::parse($place->highlight_valid)->format('Y-m-d') }}</h4>
        @else
            <div class="card mb-3">
                <div class="card-body">
                    <payment v-bind:place="{{ $place }}" v-bind:user="{{ $user }}"> </payment>
                </div>
            </div>
        @endif
    </div>
</div>





</div>
        
@endsection

