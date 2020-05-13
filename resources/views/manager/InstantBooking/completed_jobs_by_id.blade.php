@extends('manager.layouts.index')
@section('title','Completed CompletedJobs')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">All Completed Jobs</h3>
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
                  <h4 class="card-title"><a href="{{route('get_completed_jobs_by_city')}}" class="btn btn-primary">Back</a></h4>
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
                          <th>Provider Name</th>
                          <th>Provider Completed-jobs</th>
                          <th>Provider Overall-rating</th>
                          <th>Job Address</th>
                          <th>Job Zipcode</th>
                          <th>Job Date/Day</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($jobs as $completedJob)
                        <tr id="unapprovedprovider{{$completedJob->id}}">
                          <td>{{$loop->iteration}}</td>
                          <td>{{$completedJob->customer_id}}  |  {{$completedJob->customer_name}}</td>
                          <td>{{$completedJob->job_id}}</td> 
                          <td>{{$completedJob->Services_names}}</td>                     
                          <td>{{$completedJob->Provider_fistname}}</td>
                          <td><b>{{$completedJob->NoOfJobsCompleted}}</b></td>
                          <td>   
                            @if($completedJob->AverageRating > 0)
                            @php $rating = $completedJob->AverageRating; @endphp  
                            <ul style="list-style: none; padding: 0px;">
                              @while($rating>0)
                                    @if($rating >0.5)
                                       <li style="float: left; padding-right: 2px;"><span class="fa fa-star checked" style="color: #f94419;"></span></li>
                                    @else
                                        <li style="float: left; padding-right: 2px;"><span class="fa fa-star-half-o" style="color: #f94419;"></span></li>
                                    @endif
                                    @php $rating--; @endphp
                                @endwhile
                            </ul>
                            @else
                            <ul style="list-style: none; padding: 0px;">
                            <li style="float: left; padding-right: 2px;"><span class="fa fa-star" style="color:#dddddd;"></span></li>
                            <li style="float: left; padding-right: 2px;"><span class="fa fa-star" style="color:#dddddd;"></span></li>
                            <li style="float: left; padding-right: 2px;"><span class="fa fa-star" style="color:#dddddd;"></span></li>
                            <li style="float: left; padding-right: 2px;"><span class="fa fa-star" style="color:#dddddd;"></span></li>
                            <li style="float: left; padding-right: 2px;"><span class="fa fa-star" style="color:#dddddd;"></span></li>
                            </ul>
                             @endif
                          </td>
                          <td>{{$completedJob->customer_address}}</td>
                          <td>{{$completedJob->zipcode}}</td>
                          <td>{{$completedJob->date}}</td> 
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
