@extends('admin.layouts.index')
@section('title','Provider Cancelled Jobs')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">All Provider Cancelled Jobs</h3>
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
                          <th>Customer Id | Customer Name</th>
                          <th>Job Id</th>
                          <th>Customer Booked Services</th>
                          <th>Job Assigned To</th>
                          <th>Job Address</th>
                          <th>Job Zipcode</th>
                          <th>Job Date/Day</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($P_CanceledJobs as $p_canceledjob)
                        <tr id="unapprovedprovider{{$p_canceledjob->id}}">
                          <td>{{$loop->iteration}}</td>
                          <td>{{$p_canceledjob->customer_id}}  |  {{$p_canceledjob->customer_name}}</td>
                          <td>{{$p_canceledjob->job_id}}</td> 
                          <td>{{$p_canceledjob->Services_names}}</td>                     
                          <td>{{$p_canceledjob->Provider_fistname}}</td>
                          <td>{{$p_canceledjob->customer_address}}</td>
                          <td>{{$p_canceledjob->zipcode}}</td>
                          <td>{{$p_canceledjob->date}}</td> 
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
