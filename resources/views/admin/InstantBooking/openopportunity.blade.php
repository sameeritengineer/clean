@extends('admin.layouts.index')
@section('title','Open Opportunity')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Open Current Opportunity</h3>
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
                    <div class="">
                    <table class="table-responsive table table-striped table-bordered zero-configuration">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Customer Profile Pic</th>
                          <th>Customer Id | Customer Name</th>
                          <th>Job Id</th>
                          <th>Customer Zipcode</th>
                          <th>Customer Address</th>
                          <th>Booking Date & Time</th>
                          <th>Customer Booked Services</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($OpenOpportunites as $Opportunite)
                        <tr id="unapprovedprovider{{$Opportunite->id}}">
                          <td>{{$loop->iteration}}</td>
                          <td><img src="@if($Opportunite->cust_image != null) {{asset('/profile/'.$Opportunite->cust_image)}} @else {{asset('no-profile.png')}} @endif" style="height:80px;width:80px;"></td>
                          <td>{{$Opportunite->cutomer_id}} | {{$Opportunite->cust_firstname}}</td>
                          <td>{{$Opportunite->id}}</td> 
                          <td>{{$Opportunite->zipcode}}</td>                     
                          <td>{{$Opportunite->customer_address}}</td>
                          <td>{{$Opportunite->date}} {{$Opportunite->time}}</td>
                          <td>{{$Opportunite->Services_names}}</td>
                          <td><a href="{{route('show_opportunity',[$Opportunite->id])}}" class="btn btn-warning">Assign To</a></td>
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
