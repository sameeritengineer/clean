@extends('admin.layouts.index')
@section('title','All Current Jobs')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">All Current Jobs</h3>
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
                          <th>Customer Profile Pic</th>
                          <th>Id | Customer Name</th>
                          <th>Job Id</th>
                          <th>Customer Booked Services</th>
                          <th>Job Assigned To</th>
                          <th>Job Address</th>
                          <th>Job Zipcode</th>
                          <th>Job Date/Day</th>
                          <th>Job Time</th>
                          <th>Cancel Job</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($current_jobs as $currentjob)
                        <tr id="unapprovedprovider{{$currentjob->id}}">
                          <td>{{$loop->iteration}}</td>
                          <td>
                            @if($currentjob->customer_profile)
                            <img src="{{asset('profile/'.$currentjob->customer_profile)}}" style="height:80px;width:80px;">
                            @endif
                          </td>
                          <td>{{$currentjob->customer_id}}  |  {{$currentjob->customer_name}}</td>
                          <td>{{$currentjob->job_id}}</td> 
                          <td>{{$currentjob->Services_names}}</td>                     
                          <td>{{$currentjob->Provider_fistname}}</td>
                          <td>{{$currentjob->customer_address}}</td>
                          <td>{{$currentjob->zipcode}}</td>
                          <td>{{$currentjob->date}}</td> 
                          <td>{{$currentjob->time}}</td>
                          <td>
                            <button class="btn btn-success" onclick="cancelthisjob({{$currentjob->id}})">Cancel </button>
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
<style type="text/css">
  .preloader{
    z-index: 2000;
    border: none;
    color: #e8e4e4;
    margin: 0;
    padding: 0px;
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    background-color: rgb(19, 16, 16);
    opacity: 0.6;
    cursor: pointer;
    position: absolute;
    display: block;
    background-image:  url('{{asset('admin/app-assets/images/loader/loader.gif')}}');
    background-repeat:no-repeat;
    background-size:100px;
    background-position: center center;
  }
</style>
<div id="domMessage" style="display: none;"> 
  <div class="preloader"> 

  </div>
</div> 

<script>
function cancelthisjob(id)
{
   $('#domMessage').show();
      $.ajax
      ({
        type: 'POST',
        data: {"id":id," _token": "{{ csrf_token() }}",},
        url: "{{secure_url('serviceadmin/cancelthisjob')}}",
        success: function (response)
        {
          if(response == 1)
          {                                       
            $('#unapprovedprovider'+id).remove();
            $('.serviceproviderDelete').html('<div class="alert alert-success">Job cancelled successfully</div>');
            $('#domMessage').hide();
          }
          else
          {
              $('.serviceproviderDelete').html('<div class="alert alert-error">There is some error</div>');
          }
        } 
    });
}
</script>
@endsection
