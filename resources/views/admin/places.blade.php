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

        <h3 class="mt-4"><i class="fas fa-map-marked-alt text-orange-secondary"></i> Places waiting for confirmation</h3>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Sport</th>
                <th scope="col">User</th>
                <th scope="col">Paid</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>

              @if(count($places) > 0)
                @foreach ($places as $key => $place)
                <tr>
                  <th scope="row">{{ ++$key }}</th>
                  <td>{{ $place->title }}</td>
                  <td><img src="../storage/sport_logo/{{ $place->typee->image }}" alt="{{ $place->typee->name }}"> {{ $place->typee->name }}</td>
                  <td>{{ $place->user['name'] }}</td>
                  <td>
                    @if($place->paid == "1")
                    <i class="fas fa-coins"></i>
                    @else
                    Free
                    @endif
                  </td>
                  <td>
                      <button type="button" class="btn btn-primary mr-4" data-toggle="modal" data-target="#placeid{{ $place->id }}"> Open </button>
                      <a dusk="accept_submited_place" href ="/admin/accplace/{{ $place->id }}"  class="btn btn-success mr-2" data-toggle="tooltip" data-placement="top" title="Publish new place" ><i class="fas fa-check"></i></a>
                      <a dusk="decline_submited_place" href ="/admin/decplace/{{ $place->id }}"  class="btn btn-danger mr-2" data-toggle="tooltip" data-placement="top" title="Decline this place"><i class="fas fa-times"></i></a>
                      <a dusk="delete_submited_place" href ="/admin/destroyplace/{{ $place->id }}"  class="btn btn-outline-danger ml-2 mr-2" data-toggle="tooltip" data-placement="top" title="Destroy this place"><i class="fas fa-trash-alt"></i></a>
                      
                  </td>
                </tr>
                @endforeach
              @endif

            </tbody>
          </table>
        </div>

        @if(count($places) > 0)
          @foreach ($places as $key => $place)
            <div class="modal fade" id="placeid{{ $place->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      <small><img src="../storage/sport_logo/{{ $place->typee->image }}" alt="{{ $place->typee->name }}"></small>
                      {{ $place->title }}  
                      @if($place->paid == "1")
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
                            <SmallMap v-bind:place='{{ $place }}' v-bind:size='"width:auto; height: 500px;"'> </SmallMap>
                        </div>
                        <div class="col-lg-5">
                            {{ $place->about }}
                            <hr>
                              <small><img src="../storage/sport_logo/{{ $place->typee->image }}" alt="{{ $place->typee->name }}"> {{ $place->typee->name }}</small>
                            <br>
                                <small>Created by: {{ $place->user['name'] }}</small>
                            <hr>
                            <a href ="/admin/accplace/{{ $place->id }}"  class="btn btn-success mr-2"> Accept <i class="fas fa-check"></i></a>
                            <a href ="/admin/decplace/{{ $place->id }}"  class="btn btn-danger mr-2"> Decline <i class="fas fa-times"></i></a>
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

          <div class="row">
            <div class="mx-auto">
              {{ $places->links() }}
            </div>
          </div>
          

        @else
          <p class="text-center mt-4 text-muted"> No new places submited... </p>
        @endif


      
  @endsection
