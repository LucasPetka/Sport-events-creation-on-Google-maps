@extends('layouts.app')

@section('content')

    <!---------------Successful and Error messages------------------>
        @if (session('success'))
        <div class="position-absolute alert alert-success alert-dismissible fade show" style="left:50px;" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if (session('error'))
        <div class="position-absolute alert alert-danger alert-dismissible fade show" style="left:50px;" role="alert">
            {{ session('error') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if ($errors->any())
            <div class="position-absolute alert alert-danger alert-dismissible fade show" style="left:50px; z-index:100;">
                <h5>Error!</h5>
                <ul class="list-unstyled mb-0 ">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

<div class="container overflow-y-auto">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-5 mb-4">

            <h1 class="display-4">Profile</h1>
            <div class="card shadow">
                <div class="card-body">

                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="font-weight-bold float-right text-secondary extend">Places uploaded <span class="badge badge-orange"> {{ $places_uploaded }} </span></h6>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <figure class="figure">

                                @if (isset($user->provider))
                                    <img src="http://graph.facebook.com/{{ $user->provider_id }}/picture?type=large" class="figure-img img-fluid img-thumbnail" width="180px" height="180px" alt="profile-photo">
                                @else
                                    <img src="storage/avatars/{{ $user->avatar }}" class="figure-img img-fluid img-thumbnail" width="180px" height="180px" alt="profile-photo">
                                @endif
                                    <figcaption class="figure-caption">{{ $user->name }}</figcaption>

                                @if ($user->isAdmin === 1)
                                    <figcaption class="figure-caption"><a href="/admin"><b>Admin panel</b></a></figcaption>
                                @else
                                    <figcaption class="figure-caption">User</figcaption>
                                @endif
                                    <figcaption> <button dusk="update-profile-button" class="badge badge-orange-secondary" data-toggle="modal" data-target="#editProfile"><i class="fas fa-user-edit"></i> Update profile </button> </figcaption>

                            </figure>
                        </div>

                            <form action="/update_profile" method="post" enctype="multipart/form-data">
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
                                        <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ $user->name }}" maxlength="20" required>
                                    </div>    

                                    <label for="profile_image">New Profile photo</label>
                                    <div>
                                        <input type="file" name="profile_image" id="profile_image">
                                    </div>

                                    <p class="mb-2 mt-4">Sports you like</p>

                                    
                                    @foreach ($types as $type)
                                        @if(!is_null($user->liked_sports))
                                            @if(in_array( $type->id, json_decode($user->liked_sports)))
                                            <div class="custom-control custom-checkbox float-left mr-4">
                                                <input type="checkbox" name="types[]" value="{{ $type->id }}" class="custom-control-input" id="{{  $type->id  }}" checked>
                                                <label id="sport-checkbox" class="custom-control-label" for="{{  $type->id  }}">{{  $type->name  }}</label>
                                            </div>
                                            @else
                                            <div class="custom-control custom-checkbox float-left mr-4">
                                                <input type="checkbox" name="types[]" value="{{ $type->id }}" class="custom-control-input" id="{{  $type->id  }}">
                                                <label id="sport-checkbox" class="custom-control-label" for="{{  $type->id  }}">{{  $type->name  }}</label>
                                            </div>
                                            @endif
                                        @else
                                            <div class="custom-control custom-checkbox float-left mr-4">
                                                <input type="checkbox" name="types[]" value="{{ $type->id }}" class="custom-control-input" id="{{  $type->id  }}">
                                                <label id="sport-checkbox" class="custom-control-label" for="{{  $type->id  }}">{{  $type->name  }}</label>
                                            </div>
                                        @endif
                                    @endforeach


                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button dusk="update_profile_submit_btn" type="submit" class="btn btn-orange">Save changes</button>
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
                                            <i class="fas fa-times"></i> 
                                            <a class="btn btn-orange float-right" href="/email/resend" role="button"> <i class="far fa-envelope"></i> Resend</a>
                                        </td>   
                                    @endif
                                  </tr>
                                </tbody>
                              </table>
                        </div>

                        @if(json_decode($user->liked_sports) != [])
                            <small class="text-muted ml-3">Sports you like:</small>
                            @foreach ($types as $type)
                                @if(in_array( $type->id, json_decode($user->liked_sports)))
                                    <span class="badge badge-orange m-1">  {{$type->name}} </span>
                                @endif
                            @endforeach
                            
                        @else
                            <small class="text-muted ml-3">Update profile and check the sports that you like the most</small>
                        @endif
                        
                </div>
                
                </div>
            </div>


            <recommendedevents v-bind:ip='{!! $location !!}' v-bind:status='1' v-bind:current-user='{!! Auth::user()->toJson() !!}' >
            </recommendedevents>
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
