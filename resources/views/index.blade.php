
@extends('layouts.app')

@section('content')

<div id="loading-screen">

    <div class="d-flex justify-content-center vertical-center">
        <div id="geras" class="mr-4">MoSi</div>
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

        <div id="app">
                @if(!empty(Auth::check()))         
                    <Places dusk="places-component" v-bind:ip='{!! $location !!}' v-bind:status='1' v-bind:current-user='{!! Auth::user()->toJson() !!}'></Places>         
                @else
                    <Places dusk="places-component" v-bind:ip='{!! $location !!}' v-bind:status='0'></Places>     
                @endif
        </div>


        <script type="application/javascript">
        //animation
        window.onload = function() {
            document.getElementById("loading-screen").style.display = "none";
        };
        </script>

        
@endsection

