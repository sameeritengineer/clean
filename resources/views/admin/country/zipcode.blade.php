@extends('admin.layouts.index')
@section('title','Zipcode')
@section('content')
<div class="app-content content container-fluid load">
    <div class="content-wrapper data">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">Zipcode</h3>            
        </div> 
        <div class="content-body">
          <section class="">
            <div class="row">           
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body collapse in">
                    <div class="card-block ">
                      <div class="col-lg-2 col-md-6 col-sm-12">
                        <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Zipcode</button>
                      </div>                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="content-body">
        <!-- Zero configuration table -->
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">
                   <div class="table-responsive">                    
                    <table class="table table-striped table-bordered zero-configuration">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>City</th>
                          <th>Zipcode</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($zipcodes as $zip)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$zip->city->name}}</td>
                          <td>{{$zip->zipcode}}</td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="editZip('{{$zip->id}}')"><i class="fa fa-pencil"></i></button></td>  
                          <td><button type="button" class="btn btn-outline-warning" onclick="deleteZip('{{$zip->id}}','{{$zip->zipcode}}')"><i class="fa fa-trash"></i></button></td>
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Zipcode</label>
      </div>
      <form action="{{route('addZipcode')}}" id="addZipForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <label>Select Country: </label>
          <div class="form-group">
            <div class="controls">
              <select id="select" required class="form-control country">
                <option value="">Select Country</option>
                @foreach($countries as $country)
                  <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <label>Select State: </label>
          <div class="form-group">
            <div class="controls">
              <select id="select" required class="form-control state">
                <option value="">Select State</option>
              </select>
            </div>
          </div>
          <label>Select City: </label>
          <div class="form-group">
            <div class="controls">
              <select name="cityId" id="select" required class="form-control city">
                <option value="">Select City</option>
              </select>
            </div>
          </div>
          
          <label>Zipcode: </label>
          <div class="form-group">
            <div class="controls">
              <input type="text" placeholder="Add Zipcode" name="zipcode" required class="form-control">
            </div>
            <span id="zipErr" class="text-danger"></span>
          </div>
          <label>Near By: </label>
          <div class="form-group">
            <div class="controls">
              <select name="near_by[]" multiple class="form-control near_by select2" style="width:100%">
                <option value="">Select Near By Zipcode</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="addZip" value="Submit">
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade text-xs-left" id="editForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="false" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Edit Zipcode</label>
      </div>
      <form action="{{route('updateZipcode')}}" id="updateZipForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <label>Select Country: </label>
          <div class="form-group">
            <div class="controls">
              <select id="select" required class="form-control country">
                <option value="" disabled="disabled">Select Country</option>
                @foreach($countries as $country)
                  <option value="{{$country->id}}">{{$country->name}}</option>
                  @php $countryId = $country->id;@endphp
                @endforeach
              </select>
            </div>
            <span id="countryErr" class="text-danger"></span>
          </div>
          <label>Select State: </label>
          <div class="form-group">
            <div class="controls">
              <select id="select" required class="form-control state">
                <option value="" disabled="disabled">Select State</option>
              </select>
            </div>
            <span id="stateErr" class="text-danger"></span>
          </div>
          <label>Select City: </label>
          <div class="form-group">
            <div class="controls">
              <select name="cityId" id="select" required class="form-control city">
                <option value="" disabled="disabled">Select City</option>
              </select>
            </div>
            <span id="cityErr" class="text-danger"></span>
          </div>
          <label>Zipcode: </label>
          <div class="form-group">
            <div class="controls">
              <input type="hidden" id="editId" name="id">
              <input type="text" placeholder="Add Zipcode" name="zipcode" id="zipcode" required class="form-control">
            </div>
          </div>
          <span id="zip" class="text-danger"></span>
          <label>Near By: </label>
          <div class="form-group">
            <div class="controls">
              <select name="near_by[]" multiple="multiple" class="form-control near_by multipleSelect" style="width:100%">
                <option value="">Select Near By Zipcode</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="updateZip" value="Update">
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade text-xs-left" id="deleteForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete Zipcode</label>
      </div>
      <form action="{{route('destroyZipcode')}}" id="deleteZipForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this zipcode <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteZip" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.multipleSelect').select2();
});
function editZip(id)
{
  var url = "{{secure_url('serviceadmin/edit-zip')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    {
      console.log(data)
      $("#editForm").find('.state option').remove();
      $("#editForm").find('.city option').remove();
      $("#editForm").find('.near_by option').remove();
      data.stateName.forEach( function(element)
      {
        $("#editForm").find('.state').append(element);
      });
      data.cityName.forEach( function(element)
      {
        $("#editForm").find('.city').append(element);
      }); 
      data.getAllZipcode.forEach( function(element)
      {
        $("#editForm").find('.near_by').append(element);
      });    
      $("#editForm").find('#editId').val(data.id);
      $("#editForm").find('#zipcode').val(data.zipcode);
      $("#editForm").find('.city').val(data.city_id);
      $("#editForm").find('.near_by').val(data.near_by);
      $("#editForm").find('.country').val(data.countryId);
      $("#editForm").find('.state').val(data.stateId);
      $("#editForm").modal("show");      
    },    
  });
}

$('#updateZipForm').submit(function(e)
{
  e.preventDefault();
  var action = $("#updateZipForm").attr('action');
  var updateZipForm = $("#updateZipForm")[0];
  var formData = new FormData(updateZipForm);
  $.ajax
  ({
    type: "post",   
    url: action,             
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success:function(data)
    {
      console.log(data)
      if(data == 1)
      {
        toastr.success("You'r successfully updated zipcode", "Great !");
        $('#updateZipForm')[0].reset();
        $("#editForm").modal('hide');
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
      if(data.errors)
      { 
        if(data.errors.zipcode)
        {
          $( '#zip' ).html( data.errors.zipcode[0] );
        }     
      }
    },    
  });
});


function deleteZip(id,zip)
{
  var name = $('#deleteForm').find('#text').text(zip);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deleteZip').click(function(e)
{
  e.preventDefault();
  var deleteZipForm = $("#deleteZipForm")[0];
  var formData = new FormData(deleteZipForm);
  var action = $("#deleteZipForm").attr('action');
  $.ajax
  ({
    type: "post",   
    url: action,             
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    dataType:"html",
    success:function(data)
    {
      if(data == 1)
      {
        toastr.success("You'r successfully deleted zipcode", "Great !");
        $('#deleteZipForm')[0].reset();
        $("#deleteForm").modal('hide');
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
      $(".city").empty().html('<option value="">Select City</option>');
        }
        else
        {
          $(".state").empty();
        }
       }
    });
  }
  else
  {
    $(".state").empty();
  }      
});
$('.state').change(function()
{
  var stateID = $(this).val();
  if(stateID)
  {
    $.ajax
    ({
      type:"GET",
      url:"{{secure_url('serviceadmin/get-city-list')}}?state_id="+stateID,
      success:function(res)
      {
        if(res)
        {
          if(res.cities)
          {
            $(".city").html(res.cities);
          }
          if(res.zipcode)
          {
            $(".near_by").html(res.zipcode);
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
$('#addZipForm').submit(function(e) 
{
  e.preventDefault();
  var action = $('#addZipForm').attr('action');
  var addZipForm = $('#addZipForm')[0];
  var formData = new FormData(addZipForm);
  $.ajax
  ({
    type:"Post",
    url:action,
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success:function(data)
    {
      if(data == 1)
      {
        toastr.success("You'r successfully added zipcode", "Great !");
        $("#inlineForm").modal('hide');
        $('#addZipForm')[0].reset();
        var url = $(location).attr('href');
        $('.load').load(url+ ' .data');
        $('.load').load(url+ ' .data', function()
        {
          $('.zero-configuration').DataTable();
        });
      }
      else
      {
        toastr.error("Something error. Try again", "Oops !");
      }
      if(data.errors)
      { 
        if(data.errors.zipcode)
        {
          $( '#zipErr' ).html( data.errors.zipcode[0] );
        }      
      }
    },
  });
});
$('.clear').click(function()
{
  $("#editForm").find('.remove').remove();
  $("#editForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#editForm").find('.form-group').removeClass('error');
  $("#editForm").find('.form-group').removeClass('issue');
  $("#editForm").find('.form-group .help-block ul li').remove();
  $("#editForm").find('.text-danger').text('');

  $("#inlineForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#inlineForm").find('.form-group').removeClass('error');
  $("#inlineForm").find('.form-group').removeClass('issue');
  $("#inlineForm").find('.form-group .help-block ul li').remove();
  $("#inlineForm").find('.text-danger').text('');
  $('#addZipForm')[0].reset();
});
</script>
@endsection