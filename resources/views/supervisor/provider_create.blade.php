@extends('supervisor.layouts.index')
@section('title','Add New Provider')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    @if(Session::has('info'))
      <div class="alert alert-{{Session::has('info')}}">
        {{ Session::get('message') }}
        @php Session::forget('info'); @endphp
      </div>
    @endif

    <div class="content-header row">
      <div class="content-header-left col-md-11 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Add New Profile</h3>
      </div>
      <div class="content-header-right col-md-1 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0 text-right"><a href="" class="btn btn-primary">Back</a></h3>
      </div>
    </div>
    <div class="content-body">
      <section class="input-validation">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body collapse in">
                <div class="card-block ">
                  @if ($message = Session::get('error'))
                  <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                      <strong>{{ $message }}</strong>
                  </div>
                  @endif
                  <form class="form-horizontal" action="{{route('supervisior::provider_create')}}" method="post" enctype="multipart/form-data" novalidate>
                  @csrf
                    <div class="row">
                      <div class="col-md-12">    
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label>First Name</label>
                            <div class="controls">              
                              <input type="text" name="first_name" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed" placeholder="Enter First Name" class="form-control" required data-validation-required-message="First Name is required" value="{{ old('first_name') }}">
                            </div>
                          </div>  
                          <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <div class="controls">
                              <input type="text" name="last_name" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed" placeholder="Enter Last Name" class="form-control" required data-validation-required-message="last Name is required" value="{{ old('last_name') }}">
                            </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Email</label>
                            <div class="controls">              
                             <input name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" required="" placeholder="Email" data-validation-required-message="Email is required" aria-invalid="false" type="email" value="{{ old('email') }}">
                             @if ($errors->has('email'))
                              <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                              </span>
                            @endif
                            </div>
                          </div>   
                          <div class="form-group col-md-6">
                            <label>Password</label>
                            <div class="controls">
                              <input name="password"  type="password" class="form-control" required="" data-validation-required-message="password is required" aria-invalid="false" placeholder="Enter Mobile Number">
                            </div>
                          </div>                     
                          <div class="form-group col-md-6">
                            <label>Select Role</label>
                            <div class="controls">
                              <select id="select" name="role" required class="form-control {{ $errors->has('role') ? ' is-invalid' : '' }}" value="{{ old('role') }}">
                                <option value="">Select Role</option> 
                                @foreach($roles as $role)   
                                 <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach            
                              </select>
                               @if ($errors->has('role'))
                              <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('role') }}</strong>
                              </span>
                              @endif
                            </div>
                          </div> 
                          <div class="form-group col-md-6">
                            <label>Contact No</label>
                            <div class="controls">
                              <input name="mobile"  type="number" class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}" value="{{ old('mobile') }}" required="" data-validation-required-message="Mobile Number is required" aria-invalid="false" placeholder="Enter Mobile Number">
                              @if ($errors->has('mobile'))
                              <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('mobile') }}</strong>
                              </span>
                              @endif
                            </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Select Country</label>
                            <div class="controls">
                              <select id="select" name="country_id" required class="form-control country {{ $errors->has('country_id') ? ' is-invalid' : '' }}" value="{{ old('country_id') }}">
                                <option value="">Select Country</option> 
                                @foreach($countries as $country)   
                                 <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach            
                              </select>
                            </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Select State</label>
                            <div class="controls">
                              <select id="select" name="state_id" required class="form-control state">
                                <option value="">Select State</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Select City</label>
                            <div class="controls">
                              <select id="select" name="city_id" required class="form-control city">
                                <option value="">Select City</option> 
                                           
                              </select>
                            </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Select Zipcode</label>
                            <div class="controls">
                              <select id="select" name="zipcode_id" required class="form-control zip">
                                <option value="">Select Zipcode</option> 
                                           
                              </select>
                            </div>
                          </div>  
                          <div class="form-group col-md-12">
                            <label>Add Address</label>
                            <div class="controls">
                              <input name="customer_address"  type="text" class="form-control" required="" data-validation-required-message="Address is required" aria-invalid="false" placeholder="Enter Address" value="{{ old('customer_address') }}">
                            </div>
                          </div>  
                        </div>
                      </div>
                      <div class="text-xs-right">
                        <button type="submit" class="btn btn-success">Submit <i class="fa fa-thumbs-o-up position-right"></i></button>
                        <button type="reset" class="btn btn-danger" onClick="window.location.reload()">Reset <i class="fa fa-refresh position-right"></i></button>
                      </div>
                    </div>
                  </div>                
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
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
</script>
@endsection