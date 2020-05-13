@extends('admin.layouts.index')
@section('title','Role')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
        	<h3 class="content-header-title mb-0">Role</h3>
    	</div>  
    	<div class="content-body">
    		<section class="">
    			<div class="row">   				
    				<div class="col-md-12">
    					<div class="card">
    						<div class="card-body collapse in">
								<div class="card-block ">
  									<div class="col-lg-2 col-md-6 col-sm-12">
                      <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Role</button>
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
                          <th>Name</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                      	@foreach($roles as $role)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$role->name}}</td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="editRole({{$role->id}})"><i class="fa fa-pencil"></i></button></td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="deleteRole({{$role->id}},'{{$role->name}}')"><i class="fa fa-trash"></i></button></td>    
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </tfoot>
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Role</label>
      </div>
      <form action="{{route('addRole')}}" id="RoleForm" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
            <div class="controls">              
              <input type="text" placeholder="Add Role" id="name" name="name"  class="form-control" data-validation-regex-regex="^[a-zA-Z ]*$" required data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span id="error" class="text-danger"></span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="addRole" value="Submit">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Edit Role</label>
      </div>
      <form action="{{route('updateRole')}}" id="updateRoleForm" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
            <div class="controls">
              <input type="hidden" id="editId" name="id">              
              <input type="text" placeholder="Edit Role" id="editName" name="name" required class="form-control" data-validation-regex-regex="^[a-zA-Z]+(\s[a-zA-Z]+)?$" data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span class="error text-danger"></span>           
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="updateRole" value="Update">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete Role</label>
      </div>
      <form action="{{route('destroyRole')}}" id="deleteRoleForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this Role <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteRole" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function editRole(id)
{
  var url = "{{secure_url('serviceadmin/edit-role')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    {
      $("#editForm").find('#editId').val(data.id);
      $("#editForm").find('#editName').val(data.name);
      $("#editForm").modal("show");      
    },    
  });
}

$('#updateRoleForm').submit(function(e)
{
  e.preventDefault();
  var value = $('#editName').val()
  var regex = new RegExp(/^[a-zA-Z]+(\s[a-zA-Z]+)?$/);
  if(value.match(regex))
  {
    var action = $("#updateRoleForm").attr('action');
    var updateRoleForm = $("#updateRoleForm")[0];
    var formData = new FormData(updateRoleForm);
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
          toastr.success("You'r successfully updated role", "Great !");
          $('#updateRoleForm')[0].reset();
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

function deleteRole(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deleteRole').click(function(e)
{
  e.preventDefault();
  var deleteRoleForm = $("#deleteRoleForm")[0];
  var formData = new FormData(deleteRoleForm);
  var action = $("#deleteRoleForm").attr('action');
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
        toastr.success("You'r successfully deleted role", "Great !");
        $('#deleteRoleForm')[0].reset();
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

$('#RoleForm').submit(function(e) 
{
  var value = $('#name').val()  
  var regex = new RegExp(/^[a-zA-Z]+(\s[a-zA-Z]+)?$/);
  if(value.match(regex))
  {
    var action = $('#RoleForm').attr('action');
    var RoleForm = $('#RoleForm')[0];
    var formData = new FormData(RoleForm);
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
          toastr.success("You'r successfully added role", "Great !");
          $("#inlineForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('#RoleForm')[0].reset();
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
  $('#RoleForm')[0].reset();
});
</script>
@endsection