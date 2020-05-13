@extends('support.layouts.index')
@section('title',' User')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2"><h3 class="content-header-title mb-0">Users</h3></div>
    </div>
 
    <div class="content-body">
      <div class="row">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title"><a href="{{route('support::users.create')}}" class="btn btn-info">Add New Profile</a></h4>
                  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements"></div>
                </div>
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered zero-configuration">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Image</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Mobile</th>
                          <th>Role</th>
                          <th>Address</th>
                          <th>Country</th>
                          <th>State</th>
                          <th>City</th>
                          <th>Zipcode</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($users as $user)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>
                            @if($user->image != null)
                              <img src="{{asset('profile/'.$user->image)}}" style="width:100px; height: 100px;">
                            @else
                              No Image
                            @endif
                          </td>
                          <td>{{$user->first_name." ".$user->last_name}}</td>
                          <td>{{$user->email}}</td> 
                          <td>{{$user->mobile}}</td>
                          <td>{{$user->name}}</td>
                          <td>{{$user->user_address['address']}}</td>
                          <td>{{$user->user_address->country_name->name ?? ""}}</td>
                          <td>{{$user->user_address->state_name->name ?? ""}}</td>
                          <td>{{$user->user_address->city_name->name ?? ""}}</td>
                          <td>{{$user->user_address->zipcode_name->zipcode ?? ""}}</td>
                          <td>{{$user->created_at->format('d M Y')}}</td>
                        </tr>
                         @endforeach
                      </tbody>
                    </table>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        </div>
      </div>
    </div>
</div>

@endsection
