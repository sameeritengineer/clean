@extends('admin.layouts.index')
@section('title','Jobs completed by city')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Job Completed By City</h3>
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb"></ol>
          </div>
        </div>
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
                          <th>Job Zipcode</th>
                          <th>Job City</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($results as $key=>$value)
                        <tr id="unapprovedprovider{{$value->id}}">
                          <td>{{$loop->iteration}}</td>                  
                          <td>{{$value->zipcode}}</td>
                          <td>{{$value->city_name}}</td>
                          <td><a href="{{route('Completed_jobs_by_id',['id'=>$value->zipcode])}}" class="btn btn-primary">Jobs</a></td>
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
