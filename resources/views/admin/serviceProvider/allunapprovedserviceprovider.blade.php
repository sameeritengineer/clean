@extends('admin.layouts.index')
@section('title','Service Providers')
@section('content')

   <div class="app-content content container-fluid">
    <div class="content-wrapper">
      <div class="content-header row">
         <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">All Un-approved Service Provider</h3>
          <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-xs-12">
              <ol class="breadcrumb">
              
              </ol>
            </div>
          </div>
        </div>
      </div>
      
       <div class="serviceproviderDelete"></div>
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php

            Session::forget('success');

            @endphp
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
        <!-- Stats -->
        <div class="row">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title"></h4>
                  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements">
                  </div>
                </div>
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered zero-configuration">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Service</th>                         
                          <th>Approval</th>
                          <th>View</th>
                          <th>Decline</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($unapprovedproviders as $unapprovedprovider)
                        <tr id="unapprovedprovider{{$unapprovedprovider->id}}">
                          <td>{{$loop->iteration}}</td>                         
                          <td>{{$unapprovedprovider->first_name}}</td>
                           @php
                             $servicename = App\Service::where('id',$unapprovedprovider->services)->value('name');
                            @endphp
                          <td>{{$unapprovedprovider->email}}</td>
                          <td>{{$servicename}}</td>
                          <td>
                            <button class="btn btn-success" onclick="updateStatus({{$unapprovedprovider->id}})">Approve</button>
                          </td>
                          <td><button type="button" class="btn btn-icon btn-primary" data-id="" data-url="" onclick="viewproviderprofile({{$unapprovedprovider->id}});" data-toggle="modal" data-target="#default"><i class="fa fa-eye"></i></button></td>
                          <td><button type="button" class="btn btn-icon btn-primary" data-id="" data-url="" onclick="DeclineConfirm({{$unapprovedprovider->id}},'{{route('DeclineunApprovedProvider')}}');" data-toggle="modal" data-target="#default"><i class="ft-trash"></i></button></td>
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
  <div class="modal fade text-xs-left " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel1">Delete Confirm</h4>
          </div>
          <div class="modal-body">
            <h5></h5>
            <p>Are you sure, you want to decline this?</p>
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
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
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
 
<script type="text/javascript"> 
function viewproviderprofile(id)
{
  var url = "{{secure_url('serviceadmin/viewProviderProfile')}}";
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
  
function DeclineConfirm(id,url){
    $('#serviceproviderId').attr('data-id',id);
    $('#serviceproviderId').attr('data-url',url);
    $("#myModal").modal('show');
}

function deleteServiceprovider()
{
     var id =    $("#serviceproviderId").attr('data-id');
     var url =    $("#serviceproviderId").attr('data-url');

   $.ajax({
           type: 'POST',
           data: {
                "id":id,
                " _token": "{{ csrf_token() }}",
              },
          url: "{{secure_url('serviceadmin/Decline-Unapproved-provider')}}",
          success: function (response) {

              if(response == "success"){
                
                $('#unapprovedprovider'+id).remove();
                $('.serviceproviderDelete').html('<div class="alert alert-success">Service Provider has been Decline successfully</div>');
              }
              else{
                  $('.serviceproviderDelete').html('<div class="alert alert-error">There is some error</div>');
              }
          } 
      });
}

function updateStatus(id)
{
   $('#domMessage').show();
      $.ajax
      ({
        type: 'POST',
        data: {"id":id," _token": "{{ csrf_token() }}",},
        url: "{{secure_url('serviceadmin/updateStatus')}}",
        success: function (response)
        {
          if(response == 1)
          {                                       
            $('#unapprovedprovider'+id).remove();
            $('.serviceproviderDelete').html('<div class="alert alert-success">Service Provider has been Approved successfully</div>');
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
