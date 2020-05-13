@extends('support.layouts.index')
@section('title','Provider Check In/Out')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2"><h3 class="content-header-title mb-0">Provider Check In/Out</h3></div>
    </div>
 
    <div class="content-body">
      <div class="row">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title"></h4>
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
                          <th>Provider</th>
                          <th>Customer Address</th>
                          <th>Customer Zipcode</th>
                          <th>Booking Date & Time</th>
                          <th>Booking Service</th>
                          <th>Provider Check In</th>
                          <th>Provider Check Out</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($provider_checks as $provider)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$provider->first_name." ".$provider->last_name}}</td>
                          <td>{{$provider->customer_address}}</td> 
                          <td>{{$provider->zipcode}}</td>
                          <td>{{$provider->date." ".$provider->time}}</td>
                          <td>{{$provider->Services}}</td>
                          <td>{{$provider->created_at->format('d M Y')." ".$provider->checkIn}}</td>
                          <td>
                            @if($provider->checkOut)
                              {{$provider->updated_at->format('d M Y')." ".$provider->checkOut}}
                            @endif
                          </td>
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
