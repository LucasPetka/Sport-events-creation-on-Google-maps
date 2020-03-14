
@extends('layouts.app')

@section('content')
        
<div class="container mt-4">

    @if(!empty(Auth::check()))         
        <EventFinder v-bind:ip='{!! $location !!}' v-bind:status='1' v-bind:current-user='{!! Auth::user()->toJson() !!}'></EventFinder>         
    @else
        <EventFinder v-bind:ip='{!! $location !!}' v-bind:status='0'></EventFinder>     
    @endif

</div>
        
@endsection

