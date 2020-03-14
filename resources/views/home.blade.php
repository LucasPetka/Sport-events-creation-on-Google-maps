@extends('layouts.app')

@section('content')
<div class="container overflow-y-auto">
    
    <div class="row justify-content-center mt-5">
        <div class="col-lg-5">

            <h1 class="display-4">Profile</h1>
            <div class="card">
                <div class="card-body">

                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary ml-1 mr-1 float-right">
                                Notifications <span class="badge badge-light">4</span>
                            </button>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <figure class="figure">

                                @if (isset($user->provider))
                                    <img src="http://graph.facebook.com/{{ $user->provider_id }}/picture?type=large" class="figure-img img-fluid img-thumbnail" width="180px" height="180px" alt="profile-photo">
                                @else
                                    <img src="images/avatars/{{ $user->avatar }}" class="figure-img img-fluid img-thumbnail" width="180px" height="180px" alt="profile-photo">
                                @endif
                                    <figcaption class="figure-caption">{{ $user->name }}</figcaption>

                                @if ($user->isAdmin === 1)
                                    <figcaption class="figure-caption"><a href="/admin"><b>Admin panel</b></a></figcaption>
                                @else
                                    <figcaption class="figure-caption">User</figcaption>
                                @endif
                                    <figcaption> <button class="badge badge-success" data-toggle="modal" data-target="#editProfile"><i class="fas fa-user-edit"></i> Update profile </button> </figcaption>

                            </figure>
                        </div>

                            <form action="/update_profile" method="post">
                            <!-- Edit Profile MODAL -->
                            <div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                @csrf
                                <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Profile</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">

                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ $user->name }}">
                                    </div>    

                                    <p class="mb-2 mt-5">Sports you like</p>

                                    
                                    @foreach ($types as $type)
                                        @if(!is_null($user->liked_sports))
                                            @if(in_array( $type->id, json_decode($user->liked_sports)))
                                            <div class="custom-control custom-checkbox float-left mr-4">
                                                <input type="checkbox" name="types[]" value="{{ $type->id }}" class="custom-control-input" id="{{  $type->id  }}" checked>
                                                <label class="custom-control-label" for="{{  $type->id  }}">{{  $type->name  }}</label>
                                            </div>
                                            @else
                                            <div class="custom-control custom-checkbox float-left mr-4">
                                                <input type="checkbox" name="types[]" value="{{ $type->id }}" class="custom-control-input" id="{{  $type->id  }}">
                                                <label class="custom-control-label" for="{{  $type->id  }}">{{  $type->name  }}</label>
                                            </div>
                                            @endif
                                        @else
                                            <div class="custom-control custom-checkbox float-left mr-4">
                                                <input type="checkbox" name="types[]" value="{{ $type->id }}" class="custom-control-input" id="{{  $type->id  }}">
                                                <label class="custom-control-label" for="{{  $type->id  }}">{{  $type->name  }}</label>
                                            </div>
                                        @endif
                                    @endforeach


                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </form>


                        <div class="col-sm-12 col-md-8 col-lg-8">
                            <table class="table">
                                <tbody>
                                  <tr>
                                    <th scope="row"> E-mail</th>
                                    <td>{{ $user->email }}</td>

                                  </tr>
                                  <tr>
                                    <th scope="row">Joined</th>
                                    <td>{{ $user->created_at }}</td>

                                  </tr>
                                  <tr>
                                    <th scope="row">Verified</th>
                                    @if (isset($user->email_verified_at))
                                        <td> Verified ({{ $user->email_verified_at }})</td>
                                    @else
                                        <td> 
                                            Not verified 
                                            <a class="btn btn-primary float-right mr-5" href="/email/resend" role="button"> <i class="far fa-envelope"></i> Send verification email</a>
                                        </td>   
                                    @endif
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                </div>
                
                </div>
            </div>
        </div>

        <div class="col-lg-7">

            <showprofileinfo 
                v-bind:user='{!! Auth::user()->toJson() !!}'
             > 
            </showprofileinfo>

        </div>

    </div>
</div>
@endsection
