@extends('manager.layouts.index')
@section('title','Service Providers Checkout')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Service Provider Checkout</h3>
        <div class="row breadcrumbs-top"><div class="breadcrumb-wrapper col-xs-12"><ol class="breadcrumb"></ol></div></div>
      </div>
    </div>
    <div class="content-body">
      <div class="row">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title"></h4>
                  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a><div class="heading-elements"></div>
                </div>
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered zero-configuration">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Provider</th>
                            <th>Check In</th>
                            <th>Customer</th>
                            <th>Customer Address</th>
                            <th>Zipcode</th>
                            <th>Date & Time</th>
                            <th>Service</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($results as $job)
                          <tr>
                            <td>{{$job->job_checkin_out_id}}</td>
                            <td>
                              @php $provider_name = \App\User::find($job->provider_id);@endphp
                              {{$provider_name->first_name." ".$provider_name->last_name}}
                            </td>
                            <td>{{$job->checkIn}}</td>
                            <td>
                              @php $customer_name = \App\User::find($job->cutomer_id);@endphp
                              {{$customer_name->first_name." ".$customer_name->last_name}}
                            </td>
                            <td>{{$job->customer_address}}</td>
                            <td>{{$job->zipcode}}</td>
                            <td>{{$job->date." ".$job->time}}</td>
                            <td>
                              @foreach($job->Services as $service)
                                {{$service}}
                              @endforeach
                            </td>
                            <td>@if($job->status == 1) Job Complete @else -- @endif</td>
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
