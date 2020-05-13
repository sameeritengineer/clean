@extends('supervisor.layouts.index')
@section('title','Provider')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Approve Provider Bio</h3>
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
                          <th>Bio</th>
                          <th>Date</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                     
                        @foreach($bios as $bio)
                          <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                              @if($bio->image != null)
                                <img src="{{asset('profile/'.$bio->image)}}" style="width:100px; height:100px">
                              @else
                                No Image
                              @endif
                            </td>
                            <td>{{$bio->provider->first_name." ".$bio->provider->last_name}}</td>
                            <td>{{$bio->provider->email}}</td>
                            <td>{{$bio->provider->mobile}}</td>
                            <td>{{$bio->Bio}}</td>
                            <td>{{$bio->created_at->format('d M Y h:i A')}}</td>
                            <td>
                              @if($bio->status == 0)
                                <button type="button" onclick="changeStatus('{{$bio->id}}',1)" class="btn btn-primary">Approve</button>

                                <button type="button" onclick="changeStatus('{{$bio->id}}',2)" class="btn btn-primary">Decline</button>
                              @endif
                              @if($bio->status == 1)
                                <button type="button" class="btn btn-primary">Approved</button>

                                <button type="button" onclick="changeStatus('{{$bio->id}}',2)" class="btn btn-primary">Decline</button>
                              @endif
                              @if($bio->status == 2)
                                <button type="button" onclick="changeStatus('{{$bio->id}}',1)" class="btn btn-primary">Approve</button>

                                <button type="button" class="btn btn-primary">Declined</button>
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
      url:'{{route("supervisior::approve_bio")}}',
      data:{"_token": "{{csrf_token()}}","id":id,"status":status},
      success:function(data)
      {
        console.log(data);
        if(data.status === true)
        {
          toastr.success("You'r successfully approved bio", "Great !");
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
