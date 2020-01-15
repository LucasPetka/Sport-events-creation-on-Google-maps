@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header">User Dashboard</div>

                <div class="card-body">

                   <!-- <Profile v-bind:current-user='{!! Auth::user()->toJson() !!}'> </Profile> --> 

                    Username: {{ $user->name }}<br>
                    E-mail: {{ $user->email }}  <br>
                    Joined: {{ $user->created_at }}  <br>
                    
                    @if ($user->isAdmin === 1)
                    Role: Admin     <br>
                    <a class="btn btn-dark float-right" href="/admin" role="button">Admin panel</a>
                    @else
                    Role: User      <br>
                    @endif

                    @if (isset($user->email_verified_at))
                    Email: Verified ({{ $user->email_verified_at }})    <br>
                    @else
                    Email: Not verified 
                    <a class="btn btn-primary float-right mr-5" href="/email/resend" role="button">Send verification email again</a>
                    @endif

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
