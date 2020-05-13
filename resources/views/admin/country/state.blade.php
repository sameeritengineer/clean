@extends('admin.layouts.index')
@section('title','State')
@section('content')
<div class="app-content content container-fluid load">
    <div class="content-wrapper data">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">State</h3>
      </div>  
      <div class="content-body">
        <section class="">
          <div class="row">           
            <div class="col-md-12">
              <div class="card">
                <div class="card-body collapse in">
                  <div class="card-block ">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                      <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Sate</button>
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
                          <th>Country</th>
                          <th>State</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($states as $state)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$state->country->name}}</td>
                          <td>{{$state->name}}</td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="editState({{$state->id}})"><i class="fa fa-pencil"></i></button></td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="deleteState({{$state->id}},'{{$state->name}}')"><i class="fa fa-trash"></i></button></td>    
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Add State</label>
      </div>
      <form class="form-horizontal" action="{{route('addState')}}" id="addStateForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <h5>Select Country<span class="required"></span></h5>
              <div class="controls">
                <select name="countryId" id="select" required class="form-control">
                  <option value="">Select Country</option>
                  @foreach($countries as $country)
                    <option value="{{$country->id}}">{{$country->name}}</option>
                  @endforeach
                </select>
              </div>
          </div>
          <label>State: </label>
          <div class="form-group">
            <div class="controls">
              <input type="text" placeholder="Add State" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed" id="name" name="name" required class="form-control">
            </div>
            <span id="error" class="text-danger"></span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="addState" value="Submit">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Update</label>
      </div>
      <form class="form-horizontal" action="{{route('updateState')}}" id="updateStateForm" method="post" novalidate>
        @csrf
        <input type="hidden" name="id" id="editId">
        <div class="modal-body">
          <div class="form-group">
            <h5>Select Country<span class="required"></span></h5>
              <div class="controls">
                <select name="countryId" id="select" required class="form-control">
                  <option value="" disabled="disabled">Select Country</option>
                  @foreach($countries as $country)
                    <option value="{{$country->id}}">{{$country->name}}</option>
                  @endforeach
                </select>
              </div>
          </div>
          <label>State: </label>
          <div class="form-group">
            <div class="controls">
              <input type="text" placeholder="Add State" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed" name="name" id="editName" required class="form-control">
            </div>
            <span id="errorEdit" class="text-danger"></span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="updateState" value="Update">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete State</label>
      </div>
      <form action="{{route('destroyState')}}" id="deleteStateForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this state <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteState" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function editState(id)
{
  var url = "{{secure_url('serviceadmin/edit-state')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    {      
      $("#editForm").find('#editId').val(data.id);
      $("#editForm").find('#editName').val(data.name);
      $("#editForm").find('#select').val(data.country_id);
      $("#editForm").modal("show");      
    },    
  });
}

$('#updateStateForm').submit(function(e)
{
  e.preventDefault();
  var value = $('#editName').val()
  var regex = new RegExp(/^[a-zA-Z][a-zA-Z ]+[a-zA-Z]$/);
  if(value.match(regex))
  {
    var action = $("#updateStateForm").attr('action');
    var updateStateForm = $("#updateStateForm")[0];
    var formData = new FormData(updateStateForm);
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
          toastr.success("You'r successfully updated state", "Great !");
          $('#updateStateForm').trigger("reset");
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
            $( '#errorEdit' ).html( data.errors.name[0] );
          }      
        }
      },    
    });
  }
  return false;
});

function deleteState(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('.clear').click(function()
{
  $('#updateStateForm').trigger("reset");
});

$('#deleteState').click(function(e)
{
  e.preventDefault();
  var deleteStateForm = $("#deleteStateForm")[0];
  var formData = new FormData(deleteStateForm);
  var action = $("#deleteStateForm").attr('action');
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
        toastr.success("You'r successfully deleted state", "Great !");
        $('#deleteStateForm')[0].reset();
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

$('#addStateForm').submit(function() 
{
  var value = $('#name').val()
  var regex = new RegExp(/^[a-zA-Z][a-zA-Z ]+[a-zA-Z]$/);
  if(value.match(regex))
  {
    var action = $('#addStateForm').attr('action');
    var addStateForm = $('#addStateForm')[0];
    var formData = new FormData(addStateForm);
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
            toastr.success("You'r successfully added state", "Great !");
            $("#inlineForm").modal('hide');           
            var url = $(location).attr('href');
            $('.load').load(url+ ' .data');
            $('#addStateForm')[0].reset();
            //$("#inlineForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
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
              $( '#error' ).html( data.errors.name[0] );
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
  $("#editForm").find('.form-group').removeClass('error');
  $("#editForm").find('.form-group').removeClass('issue');
  $("#editForm").find('.form-group.help-block ul li').remove();
  $("#editForm").find('.form-group').removeClass('text-danger');
  $("#editForm").find('.text-danger').text('');
  $("#inlineForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#inlineForm").find('.form-group').removeClass('error');
  $("#inlineForm").find('.form-group').removeClass('issue');
  $("#inlineForm").find('.form-group .help-block ul li').remove();
  $("#inlineForm").find('.text-danger').text('');
  $('#addStateForm')[0].reset();
});
</script>
@endsection