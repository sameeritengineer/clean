@extends('admin.layouts.index')
@section('title','Service Providers')
@section('content')

   <div class="app-content content container-fluid">
    <div class="content-wrapper">
      <div class="content-header row">
         <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">All Un-approved Profile Picture of Service Providers</h3>
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
                          <th>Profile</th>                   
                          <th>Approval</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($unapprovedProviderProfiles as $unapprovedprovider)
                        <tr id="unapprovedprovider{{$unapprovedprovider->id}}">
                          <td>{{$loop->iteration}}</td>                         
                          <td>{{$unapprovedprovider->first_name}}{{$unapprovedprovider->last_name}}</td>
                          <td>{{$unapprovedprovider->email}}</td>
                          <td><img id="imageresource" src="{{asset('profile/'.$unapprovedprovider->image)}}" onclick="showfullsizeimg({{$unapprovedprovider->id}},'{{asset('profile/'.$unapprovedprovider->image)}}');" style="width:100%;max-width:100px"></td>    
                          <td><button class="btn btn-success" onclick="updateProfileStatus({{$unapprovedprovider->id}})">Approve</button></td>
                          <td><button type="button" class="btn btn-icon btn-primary" data-id="" data-url="" onclick="deleteConfirm({{$unapprovedprovider->id}},'{{route('destroyunapproveprofilepic')}}');" data-toggle="modal" data-target="#default"><i class="ft-trash"></i></button></td>
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
            <p>Are you sure, you want to delete this?</p>
          </div>
          <div class="modal-footer">
          <a href="javascript:;" id="serviceproviderId"  data-id="" data-url="" onclick="deleteServiceproviderprofilepic();" class=" btn btn-outline-primary" data-dismiss="modal"> Yes</a>
          <a href="javascript:;"class="btn grey btn-outline-secondary" data-dismiss="modal">Close</a>
          </div>
        </div>
      </div>
 </div>

<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <!-- <h4 class="modal-title" id="myModalLabel">Image preview</h4> -->
      </div>
      <div class="modal-body">
        <img src="" id="imagepreview" style="width: 100%; height: auto;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
#imageresource:hover {
    opacity: 0.7;
}
#imageresource {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

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

function showfullsizeimg(id,url){
 $('#imagemodal').find('#imagepreview').attr('src', url); 
 $('#imagemodal').modal('show');   
}

function deleteConfirm(id,url){
    $('#serviceproviderId').attr('data-id',id);
    $('#serviceproviderId').attr('data-url',url);
    $("#myModal").modal('show');
}

function deleteServiceproviderprofilepic(){

     var id =    $("#serviceproviderId").attr('data-id');
     var url =    $("#serviceproviderId").attr('data-url');

   $.ajax({
           type: 'POST',
           data: {
                "id":id,
                " _token": "{{ csrf_token() }}",
              },
          url: "{{url('serviceadmin/destroy-Unapproved-profile-pic')}}",
          success: function (response) {
              console.log(response)
              if(response == "success"){
                
                $('#unapprovedprovider'+id).remove();
                $('.serviceproviderDelete').html('<div class="alert alert-success">Service Provider profile has been deleted successfully</div>');
              }
              else{
                  $('.serviceproviderDelete').html('<div class="alert alert-error">There is some error</div>');
              }
          } 
      });
}

function updateProfileStatus(id)
{     
      $('#domMessage').show();
      $.ajax
      ({
        type: 'POST',
        data: {"id":id," _token": "{{ csrf_token() }}",},
        url: "{{url('serviceadmin/updateProfileStatus')}}",
        success: function (response)
        {
          console.log(response)
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
