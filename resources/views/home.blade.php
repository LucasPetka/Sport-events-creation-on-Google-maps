@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row justify-content-center">
        <h1 class="display-4 mt-5">Profile</h1>
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-body">

                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary ml-1 mr-1 float-right">
                                Notifications <span class="badge badge-light">4</span>
                            </button>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3">
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
                                    <figcaption> <a href="#" class="badge badge-success"><i class="fas fa-user-edit"></i> Update profile </a> </figcaption>

                            </figure>
                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-9">
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

                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary m-1 float-right" data-toggle="collapse" data-target="#createdEvents" aria-expanded="false" aria-controls="createdEvents">
                            <i class="far fa-calendar-alt"></i>  Your created events <span class="badge badge-light">{{ count($createdevents) }}</span>
                        </button>
                        <button type="button" class="btn btn-primary m-1 float-right"  data-toggle="collapse" data-target="#goingto" aria-expanded="false" aria-controls="goingto">
                            <i class="fas fa-calendar-check"></i> Events you have joined <span class="badge badge-light">{{ count($goingtoevents) }}</span>
                        </button>
                        <button type="button" class="btn btn-success m-1 float-right" data-toggle="collapse" data-target="#createdPlaces" aria-expanded="false" aria-controls="createdPlaces">
                            <i class="fas fa-map-marked-alt"></i>  Places  
                        </button>
                    </div>
                </div>

                
                <div class="accordion" id="accordionExample">
                    <div class="collapse" id="createdPlaces" data-parent="#accordionExample">
                        <p class="h4 text-center">Places</p>

                        <nav class="mt-2">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Submited places</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Accepted places</a>
                                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Declined places</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">

                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                @if (count($submited_places) > 0)
                                    @foreach ($submited_places as $place)
                                        <div class="card mt-2 mb-3">
                                            <div class="card-body">
                                                {{ $place->title }}
                                                <div class="float-right"> 
                                                    <img src="../storage/sport_logo/{{ $place->typee->image }}" alt="{{ $place->typee->name }}"> {{ $place->typee->name }}</<img>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-8">
                                                        {{ $place->about }}
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="button" class="btn btn-outline-success btn-lg float-right" data-toggle="modal" data-target="#submited_map{{ $place->id }}">
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal fade" id="submited_map{{ $place->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">{{ $place->title}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <SmallMap v-bind:place='{{ $place }}' v-bind:size='"width:auto; height: 450px;"'> </SmallMap>
                                                        </div>  
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-center mt-4 text-muted"> No submited places.. </p> 
                                @endif
                            </div>

                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                @if (count($accepted_places) > 0)
                                    @foreach ($accepted_places as $place)
                                    <div class="card mt-2 mb-3">
                                        <div class="card-body">
                                            {{ $place->title }}
                                            <div class="float-right"> 
                                                    <img src="../storage/sport_logo/{{ $place->typee->image }}" alt="{{ $place->typee->name }}"> {{ $place->typee->name }}</<img>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-8">
                                                    {{ $place->about }}
                                                </div>
                                                <div class="col-4">
                                                    <button type="button" class="btn btn-outline-success btn-lg float-right" data-toggle="modal" data-target="#accepted_map{{ $place->id }}">
                                                        <i class="fas fa-map-marked-alt"></i>
                                                    </button>
                                                </div>
                                                <div class="modal fade" id="accepted_map{{ $place->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ $place->title}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <SmallMap v-bind:place='{{ $place }}' v-bind:size='"width:auto; height: 450px;"'> </SmallMap>
                                                    </div>  
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-center mt-4 text-muted"> No accepted places.. </p> 
                                @endif
                            </div>

                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

                                @if (count($declined_places) > 0)
                                    @foreach ($declined_places as $place)
                                    <div class="card mt-2 mb-3">
                                        <div class="card-body">
                                            {{ $place->title }}
                                            <div class="float-right"> 
                                                    <img src="../storage/sport_logo/{{ $place->typee->image }}" alt="{{ $place->typee->name }}"> {{ $place->typee->name }}</<img>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-8">
                                                    {{ $place->about }}
                                                </div>


                                                <div class="col-4">
                                                    {!!Form::open(['action' => ['DeclinedPlacesController@destroy', $place->id], 'method' => 'POST'])!!}
                                                        {{Form::hidden('_method', 'DELETE')}}
                                                        {{Form::button('<i class="fas fa-times"></i>', ['type' => 'submit','class' => 'btn btn-outline-danger btn-lg float-right m-1'])}}
                                                    {!!Form::close()!!}
                                                    <button type="button" class="btn btn-outline-primary btn-lg float-right m-1" data-toggle="modal" data-target="#editPlace{{ $place->id }}">
                                                        <i class="far fa-edit"></i>
                                                    </button>
                                                </div>
                                                

                                                <!------------------------------------------Re-submit MODAL-------------------------------------->
                                                {!! Form::open(['action' => ['DeclinedPlacesController@update', $place->id], 'method' => 'POST']) !!}
                                                <div class="modal fade" id="editPlace{{ $place->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Re-submit declined sport place</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            
                                                                <div class="form-group">
                                                                    {{Form::label('title', 'Title')}}
                                                                    {{Form::text('title', $place->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
                                                                </div>
                                                                <div class="form-group">
                                                                    {{Form::label('about', 'About')}}
                                                                    {{Form::textarea('about', $place->about, ['class' => 'form-control', 'placeholder' => 'About', 'rows'=> '6'])}}
                                                                </div>

                                                                <SmallMap v-bind:place='{{ $place }}' v-bind:drag='true' v-bind:size='"width:auto; height: 350px;"'> </SmallMap>
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        {{Form::button('Re-submit <i class="fas fa-plus"></i>', ['type'=>'submit','class'=>'btn btn-success'])}}
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-center mt-4 text-muted"> No declined places.. </p> 
                                @endif

                            </div>
                        </div>
                            
                    </div>

                    <div class="collapse" id="createdEvents" data-parent="#accordionExample">
                        <p class="h4 text-center">Created</p>
                        <hr>
                            @if(count($createdevents) > 0)
                                @foreach ($createdevents as  $event)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <img src="../storage/sport_logo/{{ $event->place->typee->image }}" alt="{{ $event->place->typee->name }}">  {{ $event->title }}
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-6">
                                                {{ $event->about }}
                                            </div>

                                            <div class="col-6">
                                                <hr>
                                                    {{ $diff = Carbon\Carbon::parse($event->time_from)->diffForHumans(Carbon\Carbon::now()) }} 
                                                    <div class="float-right"> {{ Carbon\Carbon::parse($event->time_from)->format('Y-m-d') }}  </div>                                
                                                <hr>
                                                    <table class="w-100">
                                                        <tr>
                                                        <td><i class="far fa-clock"></i></td>
                                                        <td class="pl-"><i class="far fa-calendar-alt"></i> {{ Carbon\Carbon::parse($event->time_from)->format('H:i') }} - {{ Carbon\Carbon::parse($event->time_until)->format('H:i') }}</td>
                                                        <td rowspan="2">
                                                        <button type="button" class="btn btn-outline-success btn-lg float-right" data-toggle="modal" data-target="#created_map{{ $event->place->id }}">
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </button>
                                                        </td>
                                                        </tr>
                                                    </table>
                                            </div>

                                            <!------------------------------------------MAP MODAL-------------------------------------->
                                            <div class="modal fade" id="created_map{{ $event->place->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle"><img src="../storage/sport_logo/{{ $event->place->typee->image }}" alt="{{ $event->place->typee->name }}"> {{ $event->place->title}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <SmallMap v-bind:place='{{ $event->place }}' v-bind:size='"width:100%; height:450px;"'> </SmallMap>
                                                    </div>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="text-center mt-4 text-muted"> No events created.. </p> 
                            @endif  
                    </div>

                    <div class="collapse" id="goingto" data-parent="#accordionExample">
                        <p class="h4 text-center">Going to</p>
                        <hr>
                        @if(count($goingtoevents) > 0)
                                @foreach ($goingtoevents as  $event)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <img src="../storage/sport_logo/{{ $event->place->typee->image }}" alt="{{ $event->place->typee->name }}"> {{ $event->title }}
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-6">
                                                {{ $event->about }}
                                            </div>

                                            <div class="col-6">
                                                <hr>
                                                    {{ $diff = Carbon\Carbon::parse($event->time_from)->diffForHumans(Carbon\Carbon::now()) }} 
                                                    <div class="float-right"><i class="far fa-calendar-alt"></i> {{ Carbon\Carbon::parse($event->time_from)->format('Y-m-d') }}  </div>                                
                                                <hr>
                                                    <table class="w-100">
                                                        <tr>
                                                        <td><i class="far fa-clock"></i></td>
                                                        <td class="pl-1">{{ Carbon\Carbon::parse($event->time_from)->format('H:i') }} - {{ Carbon\Carbon::parse($event->time_until)->format('H:i') }}</td>
                                                        <td rowspan="2">
                                                        <button type="button" class="btn btn-outline-success btn-lg float-right" data-toggle="modal" data-target="#going_map{{ $event->place->id }}">
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </button>
                                                        </td>
                                                        </tr>
                                                    </table>
                                            </div>

                                            <!------------------------------------------MAP MODAL-------------------------------------->
                                            <div class="modal fade" id="going_map{{ $event->place->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle"><img src="../storage/sport_logo/{{ $event->place->typee->image }}" alt="{{ $event->place->typee->name }}"> {{ $event->place->title}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <SmallMap v-bind:place='{{ $event->place }}' v-bind:size='"width:100%; height:450px;"'> </SmallMap>
                                                    </div>  
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="text-center mt-4 text-muted"> No events joined.. </p> 
                            @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
