@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header">User Dashboard</div>

                <div class="card-body">

                    <Profile v-bind:current-user='{!! Auth::user()->toJson() !!}'> </Profile>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
