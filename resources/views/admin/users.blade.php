@extends('layouts.admin')

@section('content')


        <h3 class="mt-4"><i class="fas fa-user"></i> Users</h3>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Created at</th>
                <th scope="col">Verified e-mail</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>

              @if(count($users) > 0)
                @foreach ($users as $key => $user)
                <tr>
                  <th scope="row">{{ ++$key }}</th>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->created_at }}</td>
                  <td>
                      @if (isset($user->email_verified_at))
                        {{ $user->email_verified_at }}
                      @else
                        <i class="fas fa-times"></i>
                      @endif
                  </td>
                  <td>     
                      <a href ="/admin/deleteuser/{{ $user->id }}"  class="btn btn-danger mr-2"><i class="fas fa-times"></i></a>
                  </td>
                </tr>
                @endforeach
              @endif

            </tbody>
        </div>

  @endsection
