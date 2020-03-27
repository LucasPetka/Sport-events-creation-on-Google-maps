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

        <h3 class="mt-4"><i class="fas fa-map-marked-alt text-orange-secondary"></i> Places</h3>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Sport</th>
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
                  <td>
                    @if($place->paid == "1")
                    <i class="fas fa-coins"></i>
                    @else
                    Free
                    @endif
                  </td>
                  <td>
                      <button type="button" class="btn btn-primary mr-4" data-toggle="modal" data-target="#placeid{{ $place->id }}"> Open </button>
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
              <div class="modal-dialog " role="document">
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
                  <div class="modal-body pt-0 pb-0">
                    <form action="" method="post"></form>
                    <div class="row">
                        <div class="col-lg-12 p-0">
                            <SmallMap v-bind:place='{{ $place }}' v-bind:drag="true" v-bind:size='"width:auto; height: 300px;"'> </SmallMap>
                        </div>
                        <div class="col-lg-10 mx-auto m-3">
                            <div class="form-group">
                                <label>Title</label>
                                <input value=" {{ $place->title }} " type="text" class="form-control" maxlength="45" placeholder="Title" name="title">
                            </div>
                            <div class="form-group">
                                <label>About</label>
                                <textarea rows="6" class="form-control" maxlength="350" placeholder="Title" name="about"> {{ $place->about }} </textarea>       
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <div class="custom-control custom-checkbox float-left mr-4">
                                    <input type="checkbox" name="paid" class="custom-control-input" id="paid">
                                    <label class="custom-control-label" for="paid" >Paid</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-orange float-right btn-block mb-3"> Save </button>
                        </div>

                    </div>
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
          <p class="text-center mt-4 text-muted"> No places... </p>
        @endif


      
  @endsection
