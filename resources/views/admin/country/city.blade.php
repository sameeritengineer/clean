@extends('admin.layouts.index')
@section('title','City')
@section('content')
<div class="app-content content container-fluid load">
    <div class="content-wrapper data">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">City</h3>            
        </div> 
        <div class="content-body">
          <section class="">
            <div class="row">           
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body collapse in">
                    <div class="card-block ">
                      <div class="col-lg-2 col-md-6 col-sm-12">
                        <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add City</button>
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
                          <th>State</th>
                          <th>City</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($cities as $city)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$city->state->name}}</td>
                          <td>{{$city->name}}</td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="editCity({{$city->id}})"><i class="fa fa-pencil"></i></button></td>  
                          <td><button type="button" class="btn btn-outline-warning" onclick="deleteCity({{$city->id}},'{{$city->name}}')"><i class="fa fa-trash"></i></button></td>
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Add City</label>
      </div>
      <form action="{{route('addCity')}}" id="addCityForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <label>Select Country: </label>
          <div class="form-group">
            <div class="controls">
              <select id="select" required class="form-control country" name="countryId">
                <option value="" >Select Country</option>
                @foreach($countries as $country)
                  <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <label>Select State: </label>
          <div class="form-group">
            <div class="controls">
              <select name="stateId" id="select" required class="form-control state">
                <option value="" >Select State</option>
              </select>
            </div>
            <span id="stateIdErr" class="text-danger"></span>
          </div>
          <label>City: </label>          
            <div class="form-group">
              <div class="controls">
              <input type="text" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed" placeholder="Add City" id="name" name="name" required class="form-control">
              </div>
              <span id="nameErr" class="text-danger"></span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="addCity" value="Submit">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Edit City</label>
      </div>
      <form action="{{route('updateCity')}}" id="updateCityForm" method="post" novalidate>
        @csrf
        <input type="hidden" name="id" id="editId">
        <div class="modal-body">
          <label>Select Country: </label>
          <div class="form-group">
            <div class="controls">
              <input type="hidden" class="country">

              <select id="select" required class="form-control country" name="countryId">
                <option value="" disabled="disabled">Select Country</option>
                @foreach($countries as $country)               
                  <option value="{{$country->id}}">{{$country->name}}</option>                                 
                @endforeach
              </select>
            </div>
          </div>
          <label>Select State: </label>
          <div class="form-group">
            <div class="controls">
              <select name="stateId" id="select" required class="form-control state">             
                <option value="" disabled="disabled">Select State</option>
              </select>              
            </div>
            <span id="errorId" class="text-danger"></span>
          </div>
          <label>City: </label>          
            <div class="form-group">
              <div class="controls">
              <input type="text" placeholder="Add City" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed" id="editName" name="name" required class="form-control"> 
              </div>
              <span id="errorName" class="text-danger"></span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="updateCity" value="Update">
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade text-xs-left" id="deleteForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete City</label>
      </div>
      <form action="{{route('destroyCity')}}" id="deleteCityForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this city <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteCity" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function editCity(id)
{
  var url = "{{secure_url('serviceadmin/edit-city')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    { 
      $("#editForm").find('.state option').remove();     
      data.stateName.forEach( function(element)
      {
        $("#editForm").find('.state').append(element);
      });
      $("#editForm").find('#editId').val(data.id);
      $("#editForm").find('#editName').val(data.name);
      $("#editForm").find('.state').val(data.state_id);
      $("#editForm").find('.country').val(data.countryId);
      $("#editForm").modal("show");      
    },    
  });
}

$('#updateCityForm').submit(function(e)
{
  e.preventDefault();
  var value = $('#editName').val()
  var regex = new RegExp(/^[a-zA-Z][a-zA-Z ]+[a-zA-Z]$/);
  if(value.match(regex))
  {
    var action = $("#updateCityForm").attr('action');
    var updateCityForm = $("#updateCityForm")[0];
    var formData = new FormData(updateCityForm);
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
        if(data == 1)
        {
          toastr.success("You'r successfully updated city", "Great !");
          $('#updateCityForm')[0].reset();
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
          if(data.errors.name)
          {
            $( '#errorName' ).html( data.errors.name[0] );
          }
          if(data.errors.stateId)
          {
            $( '#errorId' ).html( data.errors.stateId[0] );
          }    
        }
      },    
    });
  }
  return false;
});

function deleteCity(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deleteCity').click(function(e)
{
  e.preventDefault();
  var deleteCityForm = $("#deleteCityForm")[0];
  var formData = new FormData(deleteCityForm);
  var action = $("#deleteCityForm").attr('action');
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
        toastr.success("You'r successfully deleted city", "Great !");
        $('#deleteCityForm')[0].reset();
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
$('#addCityForm').submit(function(e) 
{
  e.preventDefault();
  var value = $('#name').val()
  var regex = new RegExp(/^[a-zA-Z][a-zA-Z ]+[a-zA-Z]$/);
  if(value.match(regex))
  {
    var action = $('#addCityForm').attr('action');
    var addCityForm = $('#addCityForm')[0];
    var formData = new FormData(addCityForm);
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
          toastr.success("You'r successfully added city", "Great !");
          $("#inlineForm").modal('hide');
          $('#addCityForm')[0].reset();
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
          if(data.errors.name)
          {
            $( '#nameErr' ).html( data.errors.name[0] );
          }      
        } 
      }
    });
  }
  return false;   
});
$('.clear').click(function()
{
  $("#editForm").find('.remove').remove();
  $("#editForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#editForm").find('.form-group').removeClass('issue');
  $("#editForm").find('.form-group').removeClass('error');
  $("#editForm").find('.form-group.help-block ul li').remove();
  $("#editForm").find('.form-group').removeClass('text-danger');
  $("#editForm").find('.text-danger').text('');
  $("#inlineForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#inlineForm").find('.form-group').removeClass('issue');
  $("#inlineForm").find('.form-group').removeClass('error');
  $("#inlineForm").find('.form-group .help-block ul li').remove();
  $("#inlineForm").find('.text-danger').text('');
  $('#addCityForm')[0].reset();
});
</script>
@endsection