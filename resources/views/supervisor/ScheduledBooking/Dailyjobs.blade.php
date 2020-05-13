@extends('supervisor.layouts.index')
@section('title','Daily Scheduled Jobs')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">All Daily Scheduled Jobs</h3>
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb"></ol>
          </div>
        </div>
      </div>
    </div>
    <div class="serviceproviderDelete"></div>
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
        @php Session::forget('success'); @endphp
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error) 
          <li>{{ $error}}</li>
        @endforeach 
      </ul>
    </div>
    @endif 
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
                          <th>job Id</th>
                          <th>Customer Id | Customer Name</th>
                          <th>Customer Zipcode</th>
                          <th>Scheduled Time</th>
                          <th>Customer Address</th>
                          <th>Customer Selected Services</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($Dailyjobs as $dailyjob)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <th>{{$dailyjob->Job_id}}</th>
                          <td>{{$dailyjob->customer_id}} | {{$dailyjob->customer_name}}</td>
                          <th>{{$dailyjob->Zipcode}}</th>
                          <td>{{$dailyjob->daily_time}}</td>                     
                          <td>{{$dailyjob->customer_address}}</td>
                          <td>{{$dailyjob->services}}</td>
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
