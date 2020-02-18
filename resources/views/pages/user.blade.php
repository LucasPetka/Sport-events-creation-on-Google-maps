@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row justify-content-center">
        <h1 class="display-4 mt-5">Profile</h1>
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-body">


                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <figure class="figure">
                                @if (isset($user[0]->provider))
                                    <img src="http://graph.facebook.com/{{ $user[0]->provider_id }}/picture?type=large" class="figure-img img-fluid img-thumbnail" width="180px" height="180px" alt="profile-photo">
                                @else
                                    <img src="../images/avatars/{{ $user[0]->avatar }}" class="figure-img img-fluid img-thumbnail" width="180px" height="180px" alt="profile-photo">
                                @endif
                                <figcaption class="figure-caption">{{ $user[0]->name }}</figcaption>
                                @if ($user[0]->isAdmin === 1)
                                <figcaption class="figure-caption"><b>Admin</b></figcaption>
                                @else
                                <figcaption class="figure-caption">User</figcaption>
                                @endif
                            </figure>
                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-9">
                            <table class="table">
                                <tbody>
                                  <tr>
                                    <th scope="row">Joined</th>
                                    <td>{{ $user[0]->created_at }}</td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>

            

            </div>
        </div>
    </div>
</div>
</div>
@endsection
