@extends('supervisor.layouts.index')
@section('title','Provider')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">List of all provider</h3>
        <div class="row breadcrumbs-top"><div class="breadcrumb-wrapper col-xs-12"><ol class="breadcrumb"></ol></div></div>
      </div>
    </div>

    <div class="content-body">
      <div class="row">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">
                    <div class="">
                    <table class="table table-striped table-bordered zero-configuration">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Image</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Mobile</th>
                          <th>Date</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                     
                        @foreach($employees as $employee)
                          <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                              @if($employee->image != null)
                                <img src="{{asset('profile/'.$employee->image)}}" style="width:100px; height:100px">
                              @else
                                No Image
                              @endif
                            </td>
                            <td>{{$employee->first_name." ".$employee->last_name}}</td>
                            <td>{{$employee->email}}</td>
                            <td>{{$employee->mobile}}</td>
                            <td>{{$employee->created_at->format('d M Y h:i A')}}</td>
                            <td>
                              @if($employee->status == 1)
                                <button type="button" onclick="changeStatus('{{$employee->id}}',0)" class="btn btn-primary"> De-activate</button>
                              @else
                                <button type="button" onclick="changeStatus('{{$employee->id}}',1)" class="btn btn-primary">Active</button>
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
<script>
  function changeStatus(id,status)
  {
    $.ajax
    ({
      type:'post',
      url:'{{route("supervisior::employees_permission")}}',
      data:{"_token": "{{csrf_token()}}","id":id,"status":status},
      success:function(data)
      {
        console.log(data);
        if(data.status === true)
        {
          toastr.success("You'r successfully changed permission", "Great !");
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('.load').load(url+ ' .data', function()
          {
            $('.zero-configuration').DataTable();
          });
        }
        else
        {
          toastr.error("Error Occur, Try Again", "Oops !");
        }
      }
    })
  }
</script>
@endsection
