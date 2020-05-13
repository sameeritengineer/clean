@extends('admin.layouts.index')
@section('title','Service Providers Jobs')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Service Provider Jobs</h3>
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
                            <th>Customer</th>
                            <th>Customer Address</th>
                            <th>Zipcode</th>
                            <th>Date & Time</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Comments</th>
                            <th>Add Comment</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($jobs as $job)
                          <tr>
                            <td>{{$job->instant_schedule_jobs_id}}</td>
                            <td>
                              @php $customer_name = \App\User::find($job->cutomer_id);@endphp
                              {{$customer_name->first_name." ".$customer_name->last_name}}
                            </td>
                            <td>{{$job->customer_address}}</td>
                            <td>{{$job->zipcode}}</td>
                            <td>{{$job->date." ".$job->time}}</td>
                            <td>
                              @php
                                $service_id= explode(',',$job->Services);
                                $services = App\Servicetype::whereIn('id',$service_id)->pluck('name');               
                              @endphp
                              {{$services}}
                            </td>
                            <td>@if($job->status == 1) Job Complete @else -- @endif</td>
                            <td><button class="btn btn-primary" onclick="show_comments_model('{{$job->provider_id}}','{{$job->id}}')">Show Comment</button>
                            </td>
                            <td><button class="btn btn-primary" onclick="show_model('{{$job->provider_id}}','{{$job->id}}')">Add Comment</button></td>
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
<div class="modal fade text-xs-left" id="myModaldelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel1">Add Comment</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="{{route('admin_comments')}}" id="add_comments">
          @csrf
          <strong class="card-text text-danger show_error"></strong><br>
          <input type="hidden" name="user_id" id="user_id">
          <input type="hidden" name="job_id" id="job_id">
          <textarea class="form-control" name="comments" placeholder="Write Your Comment Here..."></textarea>
          <div class="modal-footer"><input type="submit" value="Comment" class="btn btn-primary"></div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade text-xs-left" id="show_comments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel1">Show Comment</h4>
      </div>
      <div class="modal-body">
        <p id="show_comments_data"></p>
      </div>
    </div>
  </div>
</div>
<script>
  function show_comments_model(provider_id,job_id)
  {
    $('#show_comments_data').html('');
    var url = "{{secure_url('serviceadmin/show_all_provider_comment')}}";
    $.ajax
    ({
      type:'post',
      url:url,
      data:{"_token":"{{csrf_token()}}",'user_id':provider_id,"job_id":job_id},
      success:function(response)
      {
        console.log(response)
        $('#show_comments_data').html(response);
      }
    })
    $("#show_comments").modal('show');
  }

  function show_model(provider_id,job_id)
  {
    $('#add_comments').find('#user_id').val(provider_id);
    $('#add_comments').find('#job_id').val(job_id);
    $("#myModaldelete").modal('show');
  }
  $(document).ready(function(){
    $('#add_comments').submit(function(e)
    {
      e.preventDefault();
      var url = $(this).attr('action');
      var data = $(this).serialize();
      console.log(data)
      $.ajax
      ({
        type:'post',
        url:url,
        data:data,
        success:function(response)
        {
          console.log(response)
          if(response.status===true)
          {
            location.reload();
          }
          else
          {
            $('.show_error').html(response.message);
          }
        }
      })
    });
  });
</script>
@endsection
