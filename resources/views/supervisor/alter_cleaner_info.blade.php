@extends('supervisor.layouts.index')
@section('title','Alter Provider Info')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Alter Provider Info</h3>
        <div class="row breadcrumbs-top"><div class="breadcrumb-wrapper col-xs-12"><ol class="breadcrumb"></ol></div></div>
      </div>
    </div>

    <div class="content-body">
      <div class="row">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
			@if(Session::get('message'))
				<div class="alert alert-warning alert-dismissible" role="alert">
				{{Session::get('message')}}
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
			@endif
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
                          <th>Country</th>
                          <th>State</th>
                          <th>City</th>
                          <th>Zipcode</th>
                          <th>Date</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                     
                        @foreach($users as $employee)
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
                            <td>{{$employee->user_address->country_name->name ?? ""}}</td>
                            <td>{{$employee->user_address->state_name->name ?? ""}}</td>
                            <td>{{$employee->user_address->city_name->name ?? ""}}</td>
                            <td>{{$employee->user_address->zipcode_name->zipcode ?? ""}}</td>
                            <td>{{$employee->created_at->format('d M Y h:i A')}}</td>
                            <td><button onclick="editProfile('{{$employee}}')" class="btn btn-primary" type="submit">Edit</button></td>
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
<div class="modal fade text-xs-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="false" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Edit Provider Profile</label>
      </div>
      <form action="{{route('supervisior::alter_provider_info')}}" id="editProfileProvider" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
            <div class="controls">
              <input type="hidden" name="id" id="id">              
              <input type="text" id="name" class="form-control" readonly>
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
              <input type="text" id="email" class="form-control" readonly>
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
              <input type="text" id="mobile" class="form-control" readonly>
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
              <input type="text" id="address" class="form-control" name="address">
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
  
              <select id="select" name="country_id" id="country_id" required class="form-control country">
                <option value="">Select Country</option> 
                @foreach($countries as $country)   
                  <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach            
              </select>

            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
  
              <select id="select" name="state_id" required class="form-control state"><option value="">Select State</option>
				@foreach($countries as $country)   
                  @foreach($country->states as $state)
					<option value="{{$state->id}}">{{$state->name}}</option>
				  @endforeach
				@endforeach
			  </select>
              
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
  
              <select id="select" name="city_id" required class="form-control city"><option value="">Select City</option>
				@foreach($countries as $country)   
                  @foreach($country->states as $state)
					@foreach($state->cities as $city)
						<option value="{{$city->id}}">{{$city->name}}</option>
					@endforeach
				  @endforeach
				@endforeach
			  
			  </select>
              
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
  
              <select id="select" name="zipcode_id" required class="form-control zip"><option value="">Select Zipcode</option>
			  @foreach($countries as $country)   
                  @foreach($country->states as $state)
					@foreach($state->cities as $city)
						@foreach($city->zipcodes as $zip)
							<option value="{{$zip->id}}">{{$zip->zipcode}}</option>
						@endforeach
					@endforeach
				  @endforeach
				@endforeach
			  </select>
              
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="addService" value="Submit">
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $('.country').change(function()
  {
    var countryID = $(this).val();
    if(countryID)
    {
      $.ajax
      ({
        type:"GET",
        url:"{{url('supervisior/get-state-list')}}?country_id="+countryID,
        success:function(res)
        {
          if(res)
          {
            $(".state").html(res);      
          }
          else
          {
            $(".state").empty();
            $(".city").empty();
          }
         }
      });
    }
    else
    {
      $(".state").empty();
    $(".city").empty();
    }      
  });
  $('.state').change(function()
  {
    var StateID = $(this).val();
    if(StateID)
    {
      $.ajax
      ({
        type:"GET",
        url:"{{url('supervisior/get-city-list')}}?state_id="+StateID,
        success:function(res)
        {
          if(res)
          {
            console.log(res)
            if(res.cities)
            {
              $(".city").html(res.cities); 
            }     
          }
          else
          {
            $(".city").empty();
          }
         }
      });
    }
    else
    {
      $(".city").empty();
    }      
  });

  $('.city').change(function()
  {
    var cityID = $(this).val();
    if(cityID)
    {
      $.ajax
      ({
        type:"GET",
        url:"{{url('supervisior/get-zipcode-list')}}?city_id="+cityID,
        success:function(res)
        {
          if(res)
          {
            $(".zip").html(res);      
          }
          else
          {
            $(".zip").empty();
          }
         }
      });
    }
    else
    {
      $(".zip").empty();
    }      
  });

  function editProfile(employee)
  {
    var employees = JSON.parse(employee);
    console.log(employees)
    $('#editProfileProvider').find('#id').val(employees.id);
    $('#editProfileProvider').find('#name').val(employees.first_name);
    $('#editProfileProvider').find('#email').val(employees.email);
    $('#editProfileProvider').find('#mobile').val(employees.mobile);
    $('#editProfileProvider').find('#address').val(employees.user_address.address);
    $('#editProfileProvider').find('.country').val(employees.user_address.country_name.id);
    $('#editProfileProvider').find('.state').val(employees.user_address.state_name.id);
    $('#editProfileProvider').find('.city').val(employees.user_address.city_name.id);
    $('#editProfileProvider').find('.zip').val(employees.user_address.zipcode_name.id);  
    $("#inlineForm").modal("show");
  }

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
