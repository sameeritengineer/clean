@extends('admin.layouts.index')
@section('title','Service Providers')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">All User</h3>
        <div class="row breadcrumbs-top"><div class="breadcrumb-wrapper col-xs-12"><ol class="breadcrumb"></ol></div></div>
      </div>
    </div>
    <div class="userDelete"></div>
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
                          <th>Name</th>
                          <th>Role</th>
                          <th>Email</th>
                          <th>Ip address</th>
                          <th>Ban IpAddress</th>
                          <th>Activate/De-activate</th>
                          <th>View</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($users as $user)
                        <tr id="user{{$user->user_id}}">
                          <td>{{$loop->iteration}}</td>
                          <td>{{$user->first_name}} {{$user->last_name}}</td>
                          <td>{{$role_id->name}}</td>
                          <td>{{$user->email}}</td>
                          <td>{{$user->ipaddress ?? "--"}}</td>
                          @if($user->ban_ip == 0)     
                            <td>
                              <label class="switch"><input type="checkbox" data-id="{{$user->id}}" data-site="gratis" class="switchery ban_ip" value="{{$user->ban_ip}}" ><span class="slider round"></span></label>
                            </td>
                          @else                         
                            <td>
                              <label class="switch"><input type="checkbox" data-id="{{$user->id}}" data-site="gratis" class="switchery ban_ip" value="{{$user->ban_ip}}" checked><span class="slider round"></span></label>
                            </td>
                          @endif
                          @if($user->status == 0)     
                            <td>
                              <label class="switch"><input type="checkbox" data-id="{{$user->id}}" data-site="gratis" class="switchery workingStatus" value="{{$user->status}}" ><span class="slider round"></span></label>
                            </td>
                          @else                         
                            <td>
                              <label class="switch"><input type="checkbox" data-id="{{$user->id}}" data-site="gratis" class="switchery workingStatus" value="{{$user->status}}" checked><span class="slider round"></span></label>
                            </td>
                          @endif
                          <!-- <td><a href="{{route('showEditUser',['id'=>$user->id])}}"><button type="button" class="btn btn-icon btn-primary"><i class="ft-edit"></i></button></a></td> -->
                          <td><a href="{{route('showUser',['id'=>$user->id])}}"><button type="button" class="btn btn-icon btn-primary"><i class="fa fa-eye"></i></button></a></td>
                          <td><button type="button" class="btn btn-icon btn-primary" data-id="" data-url="" onclick="deleteConfirm('{{$user->id}}','{{route('destroyUser')}}');"data-toggle="modal"data-target="#default"><i class="ft-trash"></i></button></td>
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
  <div class="modal fade text-xs-left " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel1">Delete Confirm</h4></div>
        <div class="modal-body">
          <h5></h5>
          <p>Are you sure, you want to delete this?</p>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" id="userId"  data-id="" data-url="" onclick="deleteUser();" class=" btn btn-outline-primary" data-dismiss="modal"> Yes</a>
          <a href="javascript:;"class="btn grey btn-outline-secondary" data-dismiss="modal">Close</a>
        </div>
      </div>
    </div>
   </div>
<script>
  function deleteConfirm(id,url)
  {
    $('#userId').attr('data-id',id);
    $('#userId').attr('data-url',url);
    $("#myModal").modal('show');
  }
  function deleteUser()
  {
    var id = $("#userId").attr('data-id');
    var url = $("#userId").attr('data-url');
    $.ajax
    ({
      type: 'POST',
      data:
      {
        "id":id,
        " _token": "{{ csrf_token() }}",
        // "_method":"delete",
      },
      url: "{{secure_url('serviceadmin/destroy-user')}}",
      success: function (response)
      {
        if(response == "success")
        {
          $('#user'+id).remove();
          $('.userDelete').html('<div class="alert alert-success">User has been deleted successfully</div>');
        }
        else
        {
          $('.userDelete').html('<div class="alert alert-error">There is some error</div>');
        }
      } 
    });
  }

  $(".workingStatus").on("change", function()
  {
    var value = $(this).val();
    var id = $(this).attr('data-id');
    $.ajax
    ({
      type: 'POST',
      context: this,
      data:
      {
        "id": id,   
        "value":value,
        " _token": "{{ csrf_token() }}",                               
      },
      url: '{{secure_url('serviceadmin/updateUserStatus')}}',
      success: function (response)
      {
        console.log(response)
        if(response == "success")
        {
          if(value == 0)
          {
            $(this).val(1);
            $(this).parent().next('td').empty();                           
          }
          if (value == 1)
          {
            $(this).val(0);
            $(this).parent().next('td').empty();    
          }
        } 
      }
    });
  });

  $(".ban_ip").on("change", function()
  {
    var value = $(this).val();
    var id = $(this).attr('data-id');
    $.ajax
    ({
      type: 'POST',
      context: this,
      data:
      {
        "id": id,   
        "value":value,
        " _token": "{{ csrf_token() }}",                               
      },
      url: "{{secure_url('serviceadmin/ban_ip')}}",
      success: function (response)
      {
        console.log(response)
        if(response == "success")
        {
          if(value == 0)
          {
            $(this).val(1);
            $(this).parent().next('td').empty();                           
          }
          if (value == 1)
          {
            $(this).val(0);
            $(this).parent().next('td').empty();    
          }
        } 
      }
    });
  });
</script>
      
@endsection
