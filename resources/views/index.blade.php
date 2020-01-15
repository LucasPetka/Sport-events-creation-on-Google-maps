
@extends('layouts.app')

@section('content')
        <div id="app" style="width:100%; height:100vh;">

                @if(!empty(Auth::check()))         
                    <Places v-bind:status='1' v-bind:current-user='{!! Auth::user()->toJson() !!}'></Places>         
                @else
                    <Places v-bind:status='0'></Places>     
                @endif
        
        </div>

        
@endsection

