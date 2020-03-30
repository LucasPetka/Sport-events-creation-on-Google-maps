@extends('layouts.admin')

@section('content')



        <h3 class="mt-4 text-orange-secondary"><i class="fas fa-user-shield"></i> Admin </h3>

        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card shadow h-100 py-2">
                  <div class="card-body">
                  <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Places on Map</div>
                        <div class="h5 mb-0 font-weight-bold text-secondary"> {{ $placesOnMap }} </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-map-marker-alt fa-2x text-secondary"></i>
                        </div>
                  </div>
                  </div>
                  </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
            <div class="card-body">
                  <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Events going to happen</div>
                        <div class="h5 mb-0 font-weight-bold text-secondary">{{ $eventsOnMap }}</div>
                        </div>
                        <div class="col-auto">
                              <i class="fas fa-calendar-alt fa-2x text-secondary"></i>
                        </div>
                  </div>
            </div>
            </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                  <div class="card-body">
                  <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Users registered</div>
                        <div class="h5 mb-0 font-weight-bold text-secondary">{{ $registeredUsers }}</div>
                  </div>
                  <div class="col-auto">
                        <i class="fas fa-user-friends fa-2x text-secondary"></i>
                  </div>
                  </div>
                  </div>
            </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pending confirmations</div>
                      <div class="h5 mb-0 font-weight-bold text-secondary"> {{ $confirmations }} </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-secondary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <admincharts> </admincharts>
        

  @endsection
