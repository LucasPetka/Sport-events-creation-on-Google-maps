@extends('layouts.app')

@section('content')
<div class="container mt-5">
    
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
                        <div class="col-3">
                            <figure class="figure">
                                <img src="images/blank-user-img.jpg" class="figure-img img-fluid img-thumbnail" width="180px" height="180px" alt="profile-photo">
                                <figcaption class="figure-caption">{{ $user->name }}</figcaption>
                                @if ($user->isAdmin === 1)
                                <figcaption class="figure-caption"><a href="/admin"><b>Admin panel</b></a></figcaption>
                                @else
                                <figcaption class="figure-caption">User</figcaption>
                                @endif
                                <figcaption> <a href="#" class="badge badge-success"><i class="fas fa-user-edit"></i> Edit profile </a> </figcaption>
                            </figure>
                        </div>
                        <div class="col-9">
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
                                        <a class="btn btn-primary float-right mr-5" href="/email/resend" role="button"> <i class="far fa-envelope"></i> Send verification email again</a>
                                    </td>   
                                    @endif
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary ml-1 mr-1 float-right" data-toggle="collapse" data-target="#createdEvents" aria-expanded="false" aria-controls="createdEvents">
                        <i class="far fa-calendar-alt"></i>  Your created events <span class="badge badge-light">{{ count($events) }}</span>
                        </button>
                        <button type="button" class="btn btn-primary ml-1 mr-1 float-right"  data-toggle="collapse" data-target="#goingto" aria-expanded="false" aria-controls="goingto">
                        <i class="fas fa-calendar-check"></i> Events you have joined <span class="badge badge-light">{{ count($goingtoevents) }}</span>
                        </button>
                        <button type="button" class="btn btn-success ml-1 mr-1 float-right" data-toggle="collapse" data-target="#createdPlaces" aria-expanded="false" aria-controls="createdPlaces">
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
                                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Denied places</a>
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

                                                @foreach ($types as $type)
                                                    @if($type->id == $place->type)
                                                    <img src="../storage/sport_logo/{{ $type->image }}" alt="{{ $type->name }}"> {{ $type->name }}</<img>
                                                    @endif
                                                @endforeach

                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-5">
                                                    {{ $place->about }}
                                                </div>
                                                <div class="col-7">
                                                    <SmallMap v-bind:place='{{ $place }}' v-bind:size='"width:auto; height: 250px;"'> </SmallMap>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                @endforeach
                                    
                                @endif
                                


                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                                @if (count($accepted_places) > 0)

                                @foreach ($accepted_places as $place)

                                <div class="card mt-2 mb-3">
                                    <div class="card-body">
                                        {{ $place->title }}

                                        <div class="float-right"> 

                                            @foreach ($types as $type)
                                                @if($type->id == $place->type)
                                                <img src="../storage/sport_logo/{{ $type->image }}" alt="{{ $type->name }}"> {{ $type->name }}</<img>
                                                @endif
                                            @endforeach

                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-5">
                                                {{ $place->about }}
                                            </div>
                                            <div class="col-7">
                                                <SmallMap v-bind:place='{{ $place }}' v-bind:size='"width:auto; height: 250px;"'> </SmallMap>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                    
                                @endforeach
                                    
                                @endif

                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
                        </div>
                            
                    </div>

                    <div class="collapse" id="createdEvents" data-parent="#accordionExample">
                        <p class="h4 text-center">Created</p>
                            @if(count($events) > 0)
                                @foreach ($events as  $event)
                                <div class="card mb-3">
                                    <div class="card-header">
                                      {{ $event->title }}
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">

                                                @for ($i = 0; $i < count($places); $i++)
                                                    @if($event->place_id == $places[$i]->id)
                                                     <SmallMap v-bind:place='{{ $places[$i] }}' v-bind:size='"width:100%; height:250px;"'> </SmallMap>
                                                    @endif
                                                @endfor

                                            </div>

                                            <div class="col-7">
                                                {{ $event->about }}
                                            <hr>

                                                {{ $diff = Carbon\Carbon::parse($event->time_from)->diffForHumans(Carbon\Carbon::now()) }} 

                                                <div class="float-right"> {{ Carbon\Carbon::parse($event->time_from)->format('Y-m-d') }}  </div>
                                
                                            <hr>

                                            <table>
                                                <tr>
                                                    <td><i class="far fa-clock"></i> From</td>
                                                    <td class="pl-3">{{ Carbon\Carbon::parse($event->time_from)->format('H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="far fa-clock"></i> To</td>
                                                    <td class="pl-3">{{ Carbon\Carbon::parse($event->time_until)->format('H:i') }}</td>
                                                </tr>

                                            </table>


                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>

                                @endforeach
                            @endif
                    </div>
                    <div class="collapse" id="goingto" data-parent="#accordionExample">
                        <p class="h4 text-center">Going to</p>
                        @if(count($goingtoevents) > 0)
                                @foreach ($goingtoevents as  $event)
                                <div class="card mb-3">
                                    <div class="card-header">
                                      {{ $event->title }}
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">

                                                @for ($i = 0; $i < count($places); $i++)
                                                    @if($event->place_id == $places[$i]->id)
                                                     <SmallMap v-bind:place='{{ $places[$i] }}' v-bind:size='"width:100%; height:250px;"'> </SmallMap>
                                                    @endif
                                                @endfor

                                            </div>

                                            <div class="col-7">
                                                {{ $event->about }}
                                            <hr>

                                                {{ $diff = Carbon\Carbon::parse($event->time_from)->diffForHumans(Carbon\Carbon::now()) }} 

                                                <div class="float-right"> {{ Carbon\Carbon::parse($event->time_from)->format('Y-m-d') }}  </div>
                                
                                            <hr>

                                            <table>
                                                <tr>
                                                    <td><i class="far fa-clock"></i> From</td>
                                                    <td class="pl-3">{{ Carbon\Carbon::parse($event->time_from)->format('H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="far fa-clock"></i> To</td>
                                                    <td class="pl-3">{{ Carbon\Carbon::parse($event->time_until)->format('H:i') }}</td>
                                                </tr>

                                            </table>


                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>

                                @endforeach
                            @endif

                    </div>
                </div>
                



            </div>
        </div>
    </div>
</div>
@endsection
