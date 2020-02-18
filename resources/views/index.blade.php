
@extends('layouts.app')

@section('content')
        <div id="app">

                @if(!empty(Auth::check()))         
                    <Places v-bind:status='1' v-bind:current-user='{!! Auth::user()->toJson() !!}'></Places>         
                @else
                    <Places v-bind:status='0'></Places>     
                @endif
        
        </div>

        
@endsection

