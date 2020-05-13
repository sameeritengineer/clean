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
        <h3 class="content-header-title mb-0">Update Service Provider</h3>
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
                  <form class="form-horizontal" action="{{route('updateServiceProvider')}}" method="post" enctype="multipart/form-data" novalidate>
                  @csrf
                    <div class="row">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="form-group col-md-6">
                             <input type="hidden" name="id" value="{{$serviceProvider->id}}">
                            <label>First Name</label>
                            <div class="controls">              
                              <input type="text" value="{{ $serviceProvider->first_name }}" name="fname" placeholder="Enter First Name" class="form-control" required>
                            </div>
                          </div>  
                          <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <div class="controls">
                              <input type="text" value="{{ $serviceProvider->last_name }}" name="lname" placeholder="Enter Last Name" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                           <div class="form-group col-md-6">
                            <label>Select Service</label>
                            @php

                            $servicetype= App\User::where('id',$serviceProvider->id)->value('services');

                            $serviceName = App\Service::where('id',$servicetype)->value('name');

                            $stypes = App\Servicetype::where('service_id', $servicetype)->get();

                            @endphp
                            <div class="controls">
                              <select name="service_id" id="servicelist" required class="form-control">
                              <option value="{{$servicetype}}">{{$serviceName}}</option>

                             @foreach($services as $service)
                              @if($service->name != $serviceName)
                               <option value="{{$service->id}}">{{$service->name}}</option>
                               @endif
                               @endforeach
                                         
                              </select>
                            </div>
                          </div>
                            <div class="form-group col-md-6">
                            <label>Select Service Type</label>
                            <div class="controls">
                             <select name="servicetype[]" id="servicetype_list" class="select2-placeholder-multiple form-control" multiple="multiple">
                               @php

                                  $servicetypes = App\Sptostype::where('serviceprovider_id',$serviceProvider->id)->get();

                                  $stypenames= array();
                                  
                                  foreach($servicetypes as $servicetype){

                                  $stypenames[] = App\Servicetype::where('id', $servicetype->servicetype_id)->first();
                                  
                                  }

                                  $stypeids = array_column($stypenames, 'id');
                               
                                  @endphp

                                  @foreach($stypenames as $stypename)
                                  <option value='{{$stypename->id}}' selected>{{$stypename->name}}</option>
                                  @endforeach 

                                   @foreach($stypes as $stype)

                                       @if(!(in_array($stype->id, $stypeids)))
                                        
                                       <option value='{{$stype->id}}'>{{$stype->name}}</option>
                                        @endif
                                  @endforeach                                 
                            </select>
                          </div>
                         </div>
                        </div>
                        <div class="row">
                         <div class="form-group col-md-6">
                            <label>Email</label>
                            <div class="controls">              
                             <input name="email" class="form-control" value="{{ $serviceProvider->email }}" required="" placeholder="Email" data-validation-required-message="Email is required" aria-invalid="false" type="email">
                            </div>
                          </div>  
                          <div class="form-group col-md-6">
                            <label>Contact No</label>
                            <div class="controls">
                              <input name="mobile"  type="number" class="form-control" value="{{ $serviceProvider->mobile }}" required="" data-validation-required-message="Mobile Number is required" aria-invalid="false" placeholder="Enter Mobile Number">
                            </div>
                          </div>     
                          
                        </div>
                        <div class="row">                                      
                          <div class="form-group col-md-6">
                            <label>Select Country</label>
                            <div class="controls">
                              <select id="select" name="countryId" required class="form-control country">

                            @php
                           $countries= App\Country::get();
                           $sCId = App\Useraddress::where('userId', $serviceProvider->id)->value('country');
                           $sCName = App\Country::where('id',$sCId)->value('name');
                           @endphp 
                          @if($sCName)
                            <option value="{{$sCId}}">{{$sCName}}</option> 
                            @else 
                             <option value="">Select Country Name</option> 
                            @endif  

                            @foreach($countries as $country)

                            @if($country->id != $sCId) 
                              
                            <option value="{{$country->id}}">{{$country->name}}</option> 
                            @endif   

                            @endforeach   

                              </select>
                            </div>          
                          </div>
                          <div class="form-group col-md-6">
                            <label>Select State</label>
                            <div class="controls">
                              <select id="select" name="stateId" required class="form-control state">
                                 @php
                                   $sSId = App\Useraddress::where('userId', $serviceProvider->id)->value('state');
                                   $sSName = App\State::where('id',$sSId)->value('name');
                                   $states = App\State::where('country_id', $sCId)->get();
                            
                                   @endphp 
                                               
                               @if($sSName)
                               @foreach($states as $state)
                               <option value="{{$state->id}}" @if($state->id == $sSId) selected="selected" @endif>{{$state->name}}</option>
                               @endforeach
                               @else
                              
                               <option value="">Select State Name</option>
                               @foreach($states as $state)
                               <option value="{{$state->id}}">{{$state->name}}</option>
                               @endforeach
                               @endif                                           
                              </select>
                            </div>
                          </div>
                        
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label>Select City</label>
                            <div class="controls">
                              <select name="cityId" id="select" required class="form-control city">
                               @php
          
                              $sCId = App\Useraddress::where('userId', $serviceProvider->id)->value('city');
                              $sCName = App\City::where('id',$sCId)->value('name');
                              $cities = App\City::where('state_id', $sSId)->get();

                             @endphp 

                              @if($sCName)
                              @foreach($cities as $city)
                              <option value="{{$city->id}}" @if($city->id == $sCId) selected="selected" @endif>{{$city->name}}</option>

                              @endforeach
                              @else
                              @php
                              
                              @endphp
                             <option value="">Select City Name</option>
                             @foreach($cities as $city)
                             <option value="{{$city->id}}">{{$city->name}}</option>
                             @endforeach
                             @endif                                                                                                    
                              </select>
                            </div>
                          </div>
                           <div class="form-group col-md-6">
                            <label>Select Zipcode</label>
                            <div class="controls">              
                              <select name="zipcodeId" id="select" required class="form-control zip">
                              @php

                              $sZipcodeId = App\Useraddress::where('userId', $serviceProvider->id)->value('zipCode');        
                              $sZipcode = App\Zipcode::where('id', $sZipcodeId)->value('zipcode');
                              $zipcodes = App\Zipcode::where('city_id', $sCId)->get();
                            @endphp

                            @if($sZipcode)
                            @foreach($zipcodes as $zipcode)
                            <option value="{{$zipcode->id}}"@if($zipcode->id == $sZipcodeId) selected="selected" @endif>{{$zipcode->zipcode}}</option>
                            @endforeach
                            @else
                            @php
                           
                            @endphp
                           <option value="">Select Postal Code</option>
                           @foreach($zipcodes as $zipcode)
                           <option value="{{$zipcode->id}}">{{$zipcode->zipcode}}</option>
                           @endforeach

                           @endif                      
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                             <label>Start time</label>
                            @php
                            $starttime = App\Approved_Bio::where('serviceprovider_id', $serviceProvider->id)->value('starttime');
                            @endphp
                             <div class="input-group" id="startingtimepicker" >
                  <input type="text" value="{{$starttime}}" class="form-control startclock"  name="start_clock" id="startclock" required>
                             <span class="input-group-addon">
                             <span class="ft-clock"></span>
                             </span>
                             </div>
                         </div>
                         <div class="form-group col-md-6">
                          <label>End time</label>
                          @php
                            $endtime = App\Approved_Bio::where('serviceprovider_id', $serviceProvider->id)->value('endtime');
                          @endphp
                           <div class="input-group" id="endingtimepicker" >
                  <input type="text" value="{{$endtime}}"  class="form-control endclock"  name="end_clock" id="endclock" required>
                           <span class="input-group-addon">
                           <span class="ft-clock"></span>
                           </span>
                           </div> 
                         </div>                          
                        </div>
                        <div class="row">
                          <div class="form-group col-md-12">
                            <label>Bio</label>
                            @php
                            $bio = App\Approved_Bio::where('serviceprovider_id', $serviceProvider->id)->value('Bio');
                            @endphp
                            <div class="controls">              
                              <textarea placeholder="Add bio" class="textarea" id="description" name="bio"  class="form-control"  required >{{$bio}}</textarea> 
                            </div>
                          </div> 
                        </div>
                        <div class="row">
                         <div class="form-group col-md-6">
                            <label>Address</label>
                            @php
                            $address = App\Useraddress::where('userId', $serviceProvider->id)->value('address');
                            @endphp
                            <div class="controls">
                              <input name="address" type="text" value="{{$address}}" class="form-control" placeholder="Add Address" required data-validation-required-message="Address is required">
                            </div> 
                        <!-- <div id="property" class="controls">
                            <input type="text" name="address" value="{{$serviceProvider->address}}" class="form-control" id="address" placeholder="Add Address" required data-validation-required-message="Address is required">
                             <input type="text" name="lat" value="{{$serviceProvider->lattitude}}" class="form-control" placeholder="Latitude" readonly>
                             <input type="text" name="lng" value="{{$serviceProvider->longitude}}" class="form-control" placeholder="Longitude" readonly>
                          </div> -->
                          </div>
                          <div class="form-group col-md-6">
                             <label>Profile Image</label>
                               <div class="controls">   
                              <input type="file" name="profileimage" id="inputFile"class="form-control" accept="image/*" >
                            </div>
                          </div>

                        </div>
                         <div class="row">
                          <div class="form-group col-md-6">
                          @if($serviceProvider->image != null)
                            <img id="image_upload_preview" src="{{asset('profile/'.$serviceProvider->image)}}" alt="" style="height:20%;width:20%">
                          @else
                             <img id="image_upload_preview" src="{{asset('profile/no-image-icon.png')}}" alt="" style="height:20%;width:20%">
                          @endif 
                            
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

<!-- <script>
      $(document).ready(function(){
       $("#address").geocomplete({details:"#property"});
      });
</script> -->

<script>
$('#servicelist').on("change",function (){

 var serviceId = $(this).find('option:selected').val();
        $.ajax
        ({
            url: "{{secure_url('serviceadmin/get-servicetype-list')}}",
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
      url:"{{secure_url('serviceadmin/get-state-list')}}?country_id="+countryID,
      success:function(res)
      {
        if(res)
        {
          $(".state").html(res);   
            $(".city").html("<option>Select City name</option");
              $(".zip").html("<option>Select Zip Code</option");   
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
      url:"{{secure_url('serviceadmin/get-city-list')}}?state_id="+StateID,
      success:function(res)
      {
        if(res)
        {
          $(".city").html(res);   
          $(".zip").html("<option>Select Zip Code</option");      
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
      url:"{{secure_url('serviceadmin/get-zipcode-list')}}?city_id="+cityID,
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