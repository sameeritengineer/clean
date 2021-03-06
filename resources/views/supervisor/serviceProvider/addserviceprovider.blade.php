@extends('admin.layouts.index')
@section('title','Service Providers')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
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
<!-- ClockPicker Stylesheet -->
<link rel="stylesheet" type="text/css" href="{{asset('timepicker/dist/bootstrap-clockpicker.min.css')}}">

    <div class="content-header row">
      <div class="content-header-left col-md-11 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Add Service Provider</h3>
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
                  <form  class="form-horizontal" action="{{route('saveServiceProvider')}}" method="post" enctype="multipart/form-data" novalidate>
                  @csrf
                    <div class="row">
                      <div class="col-md-12">    
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label>First Name</label>
                            <div class="controls">              
                <input type="text" value="{{ old('fname') }}" name="fname" placeholder="Enter First Name" class="form-control" required>
                            </div>
                          </div>  
                          <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <div class="controls">
                    <input type="text" value="{{ old('lname') }}" name="lname" placeholder="Enter Last Name" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                           <div class="form-group col-md-6">
                            <label>Select Service</label>
                            <div class="controls">
                              <select name="service_id" id="servicelist" required class="form-control">
                              <option value="">Select Service</option>
                              @foreach($services as $service)   
                               <option value="{{$service->id}}">{{$service->name}}</option>
                               @endforeach             
                              </select>
                            </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Select Service Type</label>
                            <div class="controls">
                             <select name="servicetype[]" id="servicetype_list" class="select2-placeholder-multiple form-control" multiple="multiple"></select>
                            </div>
                          </div>
                        </div>
                        <div class="row"> 
                         <div class="form-group col-md-6">
                            <label>Email</label>
                            <div class="controls">              
                             <input name="email" class="form-control" required="" placeholder="Email" data-validation-required-message="Email is required" aria-invalid="false" type="email">
                            </div>
                          </div>   
                         <div class="form-group col-md-6">
                            <label>Contact No</label>
                            <div class="controls">
                              <input name="mobile"  type="number" class="form-control" value="{{ old('mobile') }}" required="" data-validation-required-message="Mobile Number is required" aria-invalid="false" placeholder="Enter Mobile Number">
                            </div>
                          </div>                        
                        </div>
                        <div class="row">
                           <div class="form-group col-md-6">
                            <label>Select Country</label>
                            <div class="controls">
                              <select id="select" name="countryId" required class="form-control country">
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
                              <select id="select" name="stateId" required class="form-control state">
                              <option value="">Select State</option>              
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                           <div class="form-group col-md-6">
                            <label>Select City</label>
                            <div class="controls">
                              <select name="cityId" id="select" required class="form-control city">
                              <option value="">Select City</option>                
                              </select>
                            </div>
                          </div>
                           <div class="form-group col-md-6">
                            <label>Select Zipcode</label>
                            <div class="controls">              
                              <select name="zipcodeId" id="select" required class="form-control zip">
                              <option value="">Select Zipcode</option>                
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">                     
                          <div class="form-group col-md-6">
                             <label>Start time</label>
                             <div class="input-group" id="startingtimepicker" >
                             <input type="text" class="form-control startclock"  name="start_clock" id="startclock" required>
                             <span class="input-group-addon">
                             <span class="ft-clock"></span>
                             </span>
                             </div>
                         </div>
                         <div class="form-group col-md-6">
                          <label>End time</label>
                           <div class="input-group" id="endingtimepicker" >
                           <input type="text" class="form-control endclock"  name="end_clock" id="endclock" required>
                           <span class="input-group-addon">
                           <span class="ft-clock"></span>
                           </span>
                           </div> 
                         </div>                          
                        </div>
                        <div class="row">
                          <div class="form-group col-md-12">
                            <label>Bio</label>
                            <div class="controls">              
                              <textarea placeholder="Add bio" class="textarea" id="description" name="bio"  class="form-control"  required ></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label>Address</label>
                            <div class="controls">
                            <input name="address" type="text" value="{{ old('address') }}"   class="form-control" placeholder="Add Address" required data-validation-required-message="Address is required">
                            </div>
                            <!-- SHOW LET LONG FOR ADDRESS FIELD. -->
                            <!-- <div id="property" class="controls" >
                              <input type="text" name="address" value="{{ old('address') }}" class="form-control" id="address" placeholder="Add Address" required data-validation-required-message="Address is required">
                              <input type="text" name="lat" class="form-control" placeholder="Latitude" readonly>
                              <input type="text" name="lng" class="form-control" placeholder="Longitude" readonly>
                            </div> -->
                        </div> 
                        <div class="form-group col-md-6 ">
                             <label>Profile Image</label>
                               <div class="controls">   
                              <input type="file" name="profileimage" id="inputFile" class="form-control" accept="image/*"  required data-validation-required-message="Profile Image is required">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                             <img id="image_upload_preview" src="" alt="" style="height:20%;width:20%">
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
<!-- jQuery and Bootstrap scripts -->
<script type="text/javascript" src="{{asset('timepicker/assets/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('timepicker/assets/js/bootstrap.min.js')}}"></script>
 
<!-- ClockPicker script -->
<script type="text/javascript" src="{{asset('timepicker/dist/bootstrap-clockpicker.min.js')}}"></script>
 
<script type="text/javascript">
$('#startingtimepicker').clockpicker({
    twelvehour: false,
    placement: 'bottom',
    align: 'left',
    donetext: 'Done'
})
$('.startclock').change(function()
{
 var starttime = console.log(this.value);   
 $('.form-control .startclock').find("#startclock").val(starttime); 
});

$('#endingtimepicker').clockpicker({
    twelvehour: false,
    placement: 'bottom',
    align: 'left',
    donetext: 'Done'
})
$('.endclock').change(function()
{
 var endtime = console.log(this.value);   
 $('.form-control .endclock').find("#endclock").val(endtime); 
});
</script>

<script type="text/javascript">
 
$('#servicelist').on("change",function (){
 var serviceId = $(this).find('option:selected').val();
  $.ajax
    ({
        url: "{{route('getServicetype')}}",
        type: "POST",
        data:{"_token":"{{csrf_token()}}","serviceId":serviceId,},
        success: function (response) 
        { 
           $('#servicetype_list').html(response);  
        },                                    
    });  
}); 


$('.country').change(function()
{
  var countryID = $(this).val();
  if(countryID)
  {
    $.ajax
    ({
      type:"GET",
      url:"{{url('serviceadmin/get-state-list')}}?country_id="+countryID,
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
      url:"{{url('serviceadmin/get-city-list')}}?state_id="+StateID,
      success:function(res)
      {
        if(res)
        {
          $(".city").html(res);      
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
      url:"{{url('serviceadmin/get-zipcode-list')}}?city_id="+cityID,
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

function readURL(input)
{
    if (input.files && input.files[0])
  {
        var reader = new FileReader();
        reader.onload = function (e)
    {
            $('#image_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#inputFile").change(function ()
{
    readURL(this);
});
</script>
@endsection