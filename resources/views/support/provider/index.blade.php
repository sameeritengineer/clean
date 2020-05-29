@extends('support.layouts.index')
@section('title','Service Providers')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">All Service Provider</h3>
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
                          <th>Profile Image</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Service</th>
                          <th>Jobs Completed</th>
                          <th>Average Rating</th>
                          <!-- <th>Working Status</th> -->
                          <th>View Jobs</th>
                          <!-- <th>View</th>
                          <th>Edit</th>
                          <th>Delete</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($allserviceproviders as $serviceprovider)
                        <tr id="serviceprovider{{$serviceprovider->id}}">
                          <td>{{$loop->iteration}}</td>
                          @if($serviceprovider->image != null)
                          <td><img src="{{asset('profile/'.$serviceprovider->image)}}" style="height:80px;width:80px;"></td>
                          @else
                          <td><img src="{{asset('profile/no-image-icon.png')}}" style="height:80px;width:80px;"></td> 
                          @endif                   
                          <td>{{$serviceprovider->first_name}}</td>
                          <td>{{$serviceprovider->email}}</td> 
                          @php
                             $servicename = App\Service::where('id',$serviceprovider->services)->value('name');
                          @endphp
                          <td>{{$servicename}}</td>   
                          
                          
                          <td><b>{{$serviceprovider->NoOfJobsCompleted}}</b></td>
                          <td>   
                            @if($serviceprovider->AverageRating > 0)
                            @php $rating = $serviceprovider->AverageRating; @endphp  
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
                         <!--  @if($serviceprovider->working_status == 0)     
                          <td>
                          <label class="switch"><input type="checkbox" data-id="{{$serviceprovider->id}}" data-site="gratis" class="switchery workingStatus" value="{{$serviceprovider->working_status}}" ><span class="slider round"></span></label>
                          </td>
                          @else                         
                          <td>
                          <label class="switch"><input type="checkbox" data-id="{{$serviceprovider->id}}" data-site="gratis" class="switchery workingStatus" value="{{$serviceprovider->working_status}}" checked><span class="slider round"></span></label>
                          </td>
                          @endif -->
                          <!-- <td><button type="button" class="btn btn-icon btn-primary" data-id="" data-url="" onclick="viewproviderprofile({{$serviceprovider->id}});"data-toggle="modal" data-target="#default"><i class="fa fa-eye"></i></button></td> -->
                          <td><a href="{{route('support::view_jobs',['id'=>$serviceprovider->id])}}"><button type="button" class="btn btn-icon btn-primary"><i class="fa fa-eye"></i> View Jobs</button></a></td>
                          <!-- <td><a href="{{route('showServiceProvider',['id'=>$serviceprovider->id])}}"><button type="button" class="btn btn-icon btn-primary"><i class="fa fa-eye"></i></button></a></td>
                          <td><a href="{{route('editServiceProvider',['id'=>$serviceprovider->id])}}"><button type="button" class="btn btn-icon btn-primary"><i class="ft-edit"></i></button></a></td>
                          <td><button type="button" class="btn btn-icon btn-primary" data-id="" data-url=""  onclick="deleteConfirm({{$serviceprovider->id}},'{{route('destroyServiceProvider')}}');" data-toggle="modal" data-target="#default"><i class="ft-trash"></i></button></td>  -->
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel1">Delete Confirm</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure, you want to delete this?</p>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" id="serviceproviderId"  data-id="" data-url="" onclick="deleteServiceprovider();" class=" btn btn-outline-primary" data-dismiss="modal"> Yes</a>
        <a href="javascript:;"class="btn grey btn-outline-secondary" data-dismiss="modal">Close</a>
      </div>
      </div>
    </div>
</div>
<div class="modal fade text-xs-left" id="viewprofile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="false" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content input-validation">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Service Provider Profile</label>
      </div>
      <form  method="post" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                 <label>First Name: </label>
                <div class="controls">
                  <input type="text" id="editfName" class="form-control" readonly>
                </div>
              </div>
            </div> 
            <div class="col-md-6">
              <div class="form-group">
                 <label>Last Name: </label>
                <div class="controls">
                  <input type="text" id="editlName" class="form-control" readonly>
                </div>
              </div>
            </div> 
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Email: </label>
              <div class="controls">
                <input type="email" id="editemail" class="form-control" readonly>
              </div>
            </div>
          </div> 
          <div class="col-md-6">
           <div class="form-group">
             <label>Mobile No: </label>
             <div class="controls">
              <input type="text" id="editmobile" class="form-control" readonly>
            </div>    
           </div>
          </div> 
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
               <label>Address: </label>
              <div class="controls">
                <input type="textarea" id="editaddress" class="form-control" readonly>
              </div>
            </div>
          </div> 
          <div class="col-md-6">
            <div class="form-group">
               <label>State: </label>
              <div class="controls">
                <input type="text" id="editstate" class="form-control" readonly>
              </div>
            </div>
          </div> 
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
               <label>City: </label>
              <div class="controls">
                <input type="text" id="editcity" class="form-control" readonly>
              </div>
            </div>
          </div> 
          <div class="col-md-6">
            <div class="form-group">
               <label>ZipCode: </label>
              <div class="controls">
                <input type="text" id="editzipCode" class="form-control" readonly>
              </div>
            </div>
          </div> 
        </div>
      </div>
      <div class="modal-footer">
        <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
      </div>
    </form>
  </div>
</div>
</div>
<script>
function viewproviderprofile(id)
{
  var url = "{{secure_url('support/view-full-provider-profile')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    {
      $("#viewprofile").find('#editfName').val(data.first_name);
      $("#viewprofile").find('#editlName').val(data.last_name);
      $("#viewprofile").find('#editemail').val(data.email);
      $("#viewprofile").find('#editmobile').val(data.mobile);
      $("#viewprofile").find('#editaddress').val(data.address);
      $("#viewprofile").find('#editstate').val(data.state);
      $("#viewprofile").find('#editcity').val(data.city);
      $("#viewprofile").find('#editzipCode').val(data.zipCode);
      $("#viewprofile").modal("show");      
    },    
  });
}
   
function deleteConfirm(id,url)
{
  $('#serviceproviderId').attr('data-id',id);
  $('#serviceproviderId').attr('data-url',url);
  $("#myModaldelete").modal('show');
}

function deleteServiceprovider()
{
  var id =  $("#serviceproviderId").attr('data-id');
  var url =  $("#serviceproviderId").attr('data-url');
  $.ajax
  ({
    type: 'POST',
    data: {"id":id," _token": "{{ csrf_token() }}",},
    url: "{{secure_url('support/destroy-service-provider')}}",
    success: function (response)
    {
      if(response == "success")
      {
        $('#serviceprovider'+id).remove();
        $('.serviceproviderDelete').html('<div class="alert alert-success">Service Provider has been deleted successfully</div>');
      }
      else
      {
        $('.serviceproviderDelete').html('<div class="alert alert-error">There is some error</div>');
      }
    } 
  });
}

$(".workingStatus").on("change", function(){
    var value = $(this).val();
    var id = $(this).attr('data-id');
    
      $.ajax({
               type: 'POST',
               context: this,
               data: {
                    "id": id,   
                    "value":value,
                    " _token": "{{ csrf_token() }}",
                                        
                    },
            url: '{{secure_url('support/Update-WorkingDay-Status')}}',
            success: function (response) {
                if(response == "success"){
                if(value == 0){
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
