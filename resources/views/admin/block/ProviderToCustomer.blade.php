@extends('admin.layouts.index')
@section('title','Block Provider')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
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

      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">Block Customer For Provider</h3>
      </div>  
  </div>
      <div class="content-body">
        <!-- Zero configuration table -->
        <section id="configuration">
          <div class="row">
          	 <div class="col-xs-4">
              <div class="card">
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">  
                   <div class="modal-header">
				        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close">
				        </button>
				        <label class="modal-title text-text-bold-600" id="myModalLabel33">Block Customer</label>
		           </div> 
                @if ($message = Session::get('error'))
                  <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                      <strong>{{ $message }}</strong>
                  </div>
                  @endif
			       <form action="" id="" method="post" novalidate>
			        @csrf
			        <div class="modal-body">
			          <div class="form-group">
			            <h5>Select Provider<span class="required"></span></h5>
			              <div class="controls">
			              	  <select class="select2 form-control block" id="responsive_single" name= "provider_id" style="width: 100%">
			              	  	@php 
			              	  	 $role_id = App\Role::where('name','provider')->first();
                                 $providers = App\User::join('user_roles','user_roles.user_id','=','users.id')->where('user_roles.role_id',$role_id->id)->select('users.id as User_id','users.first_name as first_name')->get();
			              	  	 @endphp
								           @foreach($providers as $provider)
		                          <option value="{{$provider->User_id}}">{{$provider->first_name}}</option>
		                        @endforeach
		                      </select>
			              </div>
			          </div>
                <div class="form-group">
                  <h5>Select Customer<span class="required"></span></h5>
                    <div class="controls">
                          <select class="select2 form-control block" id="responsive_single"  name= "customer_id" style="width: 100%">
                           @php 
                            $role_id = App\Role::where('name','customer')->first();
                                 $customers = App\User::join('user_roles','user_roles.user_id','=','users.id')->where('user_roles.role_id',$role_id->id)->select('users.id as User_id','users.first_name as first_name')->get();
                           @endphp

                            @foreach($customers as $customer)
                                <option value="{{$customer->User_id}}">{{$customer->first_name}}</option>
                              @endforeach
                          </select>
                    </div>
                </div>
			        </div>
			        <div class="modal-footer">
			          <input type="submit" class="btn btn-outline-primary btn-lg" id="addCountry" value="Block">
			        </div>
			      </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xs-8">
              <div class="card">
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">   
                   <div class="table-responsive">                 
                    <table class="table table-striped table-bordered zero-configuration">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Blocked by(Provider Name)</th>
                          <th>Blocked (Customer Name)</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($blockedcustomers as $b_customer)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          @php 
                          	$provider_name = App\User::find($b_customer->provider_id);
                          @endphp
                          <td>{{$provider_name->first_name}}</td>
                           @php 
                            $custome_name = App\User::find($b_customer->customer_id);
                          @endphp
                          <td>{{$custome_name->first_name}}</td>
                          <td><button class="btn btn-primary" onclick="unblockcustomer({{$b_customer->id}})">UnBlock</button></td>
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

<script type="text/javascript">

function unblockcustomer(id)
{
  var url = "{{secure_url('serviceadmin/UnBlock-Customer')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    {
      if(data == 1)
      {
        toastr.success("You'r successfully UnBlock Customer", "Great !");
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
    },

  });
}

$('.clear').click(function()
{
  $("#editForm").find('.remove').remove();
  $("#editForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#editForm").find('.form-group').removeClass('issue');
  $("#editForm").find('.form-group').removeClass('error');
  $("#editForm").find('.form-group.help-block ul li').remove();
  $("#editForm").find('.text-danger').text('');

  $("#inlineForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#inlineForm").find('.form-group').removeClass('issue');
  $("#inlineForm").find('.form-group').removeClass('error');
  $("#inlineForm").find('.form-group .help-block ul li').remove();
  $("#inlineForm").find('.text-danger').text('');
  $('#CountryForm')[0].reset();
});
</script>
@endsection