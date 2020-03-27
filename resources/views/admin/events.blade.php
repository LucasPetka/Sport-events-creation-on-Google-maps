@extends('layouts.admin')

@section('content')

        <!---------------Successful and Error messages------------------>
        @if (session('success'))
          <div class="position-absolute alert alert-success alert-dismissible fade show" style="right:50px;" role="alert">
              {{ session('success') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
        @endif
        @if (session('error'))
          <div class="position-absolute alert alert-danger alert-dismissible fade show" style="right:50px;" role="alert">
              {{ session('error') }}

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
        @endif

        <h3 class="mt-4"><i class="fas fa-calendar-alt text-orange-secondary"></i> Events waiting for confirmation</h3>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Sport type</th>
                <th scope="col">User</th>
                <th scope="col">Date</th>
                <th scope="col">Start/End Time</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>

              @if(count($events) > 0)
                @foreach ($events as $key => $event)
                <tr>
                    <th scope="row">{{ ++$key }}</th>
                    <td>{{ $event->title }}</td>
                    <td><img src="../storage/sport_logo/{{ $event->place->typee->image }}" alt="{{ $event->place->typee->name }}"> {{ $event->place->typee->name }}</td>
                    <td>{{ $event->user->name }}</td>
                    <td>{{ Carbon\Carbon::parse($event->time_from)->format('Y-m-d') }}</td>
                    <td>
                        {{ Carbon\Carbon::parse($event->time_from)->format('H:i') }}-{{ Carbon\Carbon::parse($event->time_until)->format('H:i') }}
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary mr-4" data-toggle="modal" data-target="#placeid{{ $event->id }}"> Open </button>
                        <a href ="/admin/accevent/{{ $event->id }}"  class="btn btn-success mr-2" data-toggle="tooltip" data-placement="top" title="Publish new event" ><i class="fas fa-check"></i></a>
                        <a href ="/admin/decevent/{{ $event->id }}"  class="btn btn-danger mr-2" data-toggle="tooltip" data-placement="top" title="Decline this event"><i class="fas fa-times"></i></a>
                    </td>
                </tr>
                @endforeach
              @endif

            </tbody>
          </table>
        </div>

        @if(count($events) > 0)
          @foreach ($events as $key => $event)
            <div class="modal fade" id="placeid{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      <small><img src="../storage/sport_logo/{{ $event->place->typee->image }}" alt="{{ $event->place->typee->name }}"></small>
                      {{ $event->place->title }}  
                      @if($event->place->paid == "1")
                      <i class="fas fa-coins ml-3"></i> Paid
                      @else
                      <i class="fas fa-coins ml-3"></i> Free
                      @endif

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-7">
                            <SmallMap v-bind:place='{{ $event->place }}' v-bind:size='"width:auto; height: 500px;"'> </SmallMap>
                        </div>
                        <div class="col-lg-5">
                            <h5> {{ $event->title }} </h5>
                            <hr>
                            {{ $event->place->about }}
                            <hr>
                            <i class="fas fa-calendar-alt"></i> {{ Carbon\Carbon::parse($event->time_from)->format('Y-m-d') }}<br>
                            <i class="far fa-clock"></i> {{ Carbon\Carbon::parse($event->time_from)->format('H:i') }}-{{ Carbon\Carbon::parse($event->time_until)->format('H:i') }}<br>
                                <small>Sent by: {{ $event->user->name }}</small>
                            <hr>
                            <a href ="/admin/accevent/{{ $event->id }}"  class="btn btn-orange-secondary mr-2"> Accept <i class="fas fa-check"></i></a>
                            <a href ="/admin/decevent/{{ $event->id }}"  class="btn btn-danger mr-2"> Decline <i class="fas fa-times"></i></a>
                        </div>

                    </div>
                    


                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach

        @else
          <p class="text-center mt-4 text-muted"> No new events submited... </p>
        @endif


      
  @endsection
