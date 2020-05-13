@extends('admin.layouts.index')
@section('title','Country')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">Country</h3>
      </div>  
      <div class="content-body">
        <section class="">
          <div class="row">           
            <div class="col-md-12">
              <div class="card">
                <div class="card-body collapse in">
                <div class="card-block ">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                      <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Country</button>
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
                          <th>Parent</th>
                          <th>Name</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($countries as $country)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$country->parent->name ?? "--"}}</td>
                          <td>{{$country->name}}</td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="editCountry({{$country->id}})"><i class="fa fa-pencil"></i></button></td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="deleteCountry({{$country->id}},'{{$country->name}}')"><i class="fa fa-trash"></i></button></td>    
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Country</label>
      </div>
      <form action="{{route('addCountry')}}" id="CountryForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <div class="controls">              
              <select class="form-control" name="parent_id">
                <option value="0">Select</option>
                @foreach($parent_countries as $country)
                  <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
              </select>
            </div>
            <span id="error" class="text-danger"></span>
          </div>          
          <div class="form-group">
            <div class="controls">              
              <input type="text" placeholder="Add Country" id="name" name="name"  class="form-control" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" required data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span id="error" class="text-danger"></span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="addCountry" value="Submit">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Edit Country</label>
      </div>
      <form action="{{route('updateCountry')}}" id="updateCountryForm" method="post" novalidate>
        @csrf
        <div class="modal-body"> 
          <div class="form-group">
            <div class="controls">              
              <select class="form-control" id="parent_id" name="parent_id">
                <option value="0">Select</option>
                @foreach($parent_countries as $country)
                  <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
              </select>
            </div>
            <span id="error" class="text-danger"></span>
          </div>         
          <div class="form-group">
            <div class="controls">
              <input type="hidden" id="editId" name="id">              
              <input type="text" placeholder="Edit Country" id="editName" name="name" required class="form-control" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span class="error text-danger"></span>           
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="updateCountry" value="Update">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete Country</label>
      </div>
      <form action="{{route('destroyCountry')}}" id="deleteCountryForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this country <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteCountry" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function editCountry(id)
{
  var url = "{{route('editCountry')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    {
      $("#editForm").find('#editId').val(data.id);
      $("#editForm").find('#editName').val(data.name);
      $("#editForm").find('#parent_id').val(data.parent_id);
      $("#editForm").modal("show");      
    },    
  });
}

$('#updateCountryForm').submit(function(e)
{
  e.preventDefault();
  var value = $('#editName').val();
  var regex = new RegExp(/^[a-zA-Z][a-zA-Z ]+[a-zA-Z]$/);
  if(value.match(regex))
  {
    var action = $("#updateCountryForm").attr('action');
    var updateCountryForm = $("#updateCountryForm")[0];
    var formData = new FormData(updateCountryForm);
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
          toastr.success("You'r successfully updated country", "Great !");
          $('#updateCountryForm')[0].reset();
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
            $( '.error' ).html( data.errors.name[0] );
          }      
        }          
      },
    }); 
  }
  return false;
});

function deleteCountry(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deleteCountry').click(function(e)
{
  e.preventDefault();
  var deleteCountryForm = $("#deleteCountryForm")[0];
  var formData = new FormData(deleteCountryForm);
  var action = $("#deleteCountryForm").attr('action');
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
        toastr.success("You'r successfully deleted country", "Great !");
        $('#deleteCountryForm')[0].reset();
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

$('#CountryForm').submit(function(e) 
{
  var value = $('#name').val()  
  var regex = new RegExp(/^[a-zA-Z][a-zA-Z ]+[a-zA-Z]$/);
  if(value.match(regex))
  {
    var action = $('#CountryForm').attr('action');
    var CountryForm = $('#CountryForm')[0];
    var formData = new FormData(CountryForm);
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
          toastr.success("You'r successfully added country", "Great !");
          $("#inlineForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('#CountryForm')[0].reset();
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
  e.preventDefault();
});

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