@extends('admin.layouts.index')
@section('title','Promo')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2"><h3 class="content-header-title mb-0">Promo</h3></div>  
    	<div class="content-body">
    		<section class="">
    			<div class="row">   				
    				<div class="col-md-12">
    					<div class="card">
    						<div class="card-body collapse in">
  								<div class="card-block ">
  									<div class="col-lg-2 col-md-6 col-sm-12">
                      <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Promo</button>
                    </div>  
      						</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</section>
    	</div>
      <div class="content-body">
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
                          <th>Promo Name</th>
                          <th>Service</th>
                          <th>Discount</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                      	@foreach($promos as $promo)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$promo->promo_name}}</td>
                          <td>{{$promo->service->name}}</td>
                          <td>{{$promo->discount}}</td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="editService('{{$promo->id}}','{{route('promo.show',['id'=>$promo->id])}}')"><i class="fa fa-pencil"></i></button></td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="deleteService('{{$promo->id}}','{{$promo->promo_name}}')"><i class="fa fa-trash"></i></button></td>    
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
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Promo</label>
      </div>
      <form action="{{route('promo.create')}}" id="addServiceForm" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
            <div class="controls">              
              <input type="text" placeholder="Add Promo Name" id="name" name="promo_name"  class="form-control" data-validation-regex-regex="([a-zA-Z ]*$)" required data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span id="error" class="text-danger"></span>
          </div>
          <div class="form-group">
            <div class="controls">              
              <select name="service_id" class="form-control">
                @foreach($services as $service)
                  <option value="{{$service->id}}">{{$service->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
              <input type="text" placeholder="Add Discount" id="discount" name="discount"  class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
              <input type="radio" name="status" value="active" checked>
              <label for="male">Activate</label>
              <input type="radio" name="status" value="deactive">
              <label for="male">De-activate</label>
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

<div class="modal fade text-xs-left" id="editForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="false" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Edit Promo</label>
      </div>
      <form action="{{route('promo.edit')}}" id="updateServiceForm" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
            <div class="controls">
              <input type="hidden" id="editId" name="id">              
              <input type="text" placeholder="Edit Promo Name" id="editName" name="promo_name" required class="form-control" data-validation-regex-regex="([a-zA-Z ]*$)" data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span class="error text-danger"></span>           
          </div>
          <div class="form-group">
            <div class="controls">              
              <select name="service_id" class="form-control" id="service_id">
                @foreach($services as $service)
                  <option value="{{$service->id}}">{{$service->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
              <input type="text" placeholder="Add Discount" id="discount" name="discount" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <div class="controls">              
              <input type="radio" id="active" name="status" value="active">
              <label for="active">Activate</label>
              <input type="radio" id="deactive" name="status" value="deactive">
              <label for="deactive">De-activate</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="updateService" value="Update">
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade text-xs-left" id="deleteForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete Promo</label>
      </div>
      <form action="{{route('promo.destroy')}}" id="deleteServiceForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this promo <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteService" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
function editService(id,url)
{
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}"},
    success:function(data)
    {
      //console.log(data);
      $("#editForm").find('#editId').val(data.id);
      $("#editForm").find('#editName').val(data.promo_name);
      $("#editForm").find('#service_id').val(data.service_id);
      $("#editForm").find('#discount').val(data.discount);
      if(data.status == "active")
      {
        $("#editForm").find('#active').attr('checked',true);
      }
      if(data.status == "deactive")
      {
        $("#editForm").find('#deactive').attr('checked',true);
      }
      $("#editForm").modal("show");      
    },    
  });
}

$('#updateServiceForm').submit(function(e)
{
  e.preventDefault();
  var value = $('#editName').val()
  var regex = new RegExp(/^[a-zA-Z]+(\s[a-zA-Z]+)?$/);
  if(value.match(regex))
  {
    var action = $("#updateServiceForm").attr('action');
    var updateServiceForm = $("#updateServiceForm")[0];
    var formData = new FormData(updateServiceForm);
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
          toastr.success("You'r successfully updated promo", "Great !");
          $('#updateServiceForm')[0].reset();
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
          if(data.errors.promo_name)
          {
            $( '.error' ).html( data.errors.promo_name[0] );
          }      
        }          
      },
    }); 
  }
  return false;
});

function deleteService(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deleteService').click(function(e)
{
  e.preventDefault();
  var deleteServiceForm = $("#deleteServiceForm")[0];
  var formData = new FormData(deleteServiceForm);
  var action = $("#deleteServiceForm").attr('action');
  $.ajax
  ({
    type: "post",   
    url: action,             
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    //dataType:"html",
    success:function(data)
    {
      console.log(data)      
      if(data == 1)
      {
        toastr.success("You'r successfully deleted promo", "Great !");
        $('#deleteServiceForm')[0].reset();
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

$('#addServiceForm').submit(function(e) 
{
  var value = $('#name').val()  
  var regex = new RegExp(/^[a-zA-Z]+(\s[a-zA-Z]+)?$/);
  if(value.match(regex))
  {
    var action = $('#addServiceForm').attr('action');
    var addServiceForm = $('#addServiceForm')[0];
    var formData = new FormData(addServiceForm);
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
        console.log(data)        
        if(data == 1)
        {
          toastr.success("You'r successfully added promo", "Great !");          
          $("#inlineForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('.load').load(url+ ' .data', function()
          {
            $('.zero-configuration').DataTable();
          });           
          window.location.reload();
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
  $("#editForm").find('.form-group').removeClass('error');
  $("#editForm").find('.form-group').removeClass('issue');
  $("#editForm").find('.help-block ul li').remove();
  $("#editForm").find('.text-danger').text('');

  $("#inlineForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#inlineForm").find('.form-group').removeClass('error');
  $("#inlineForm").find('.form-group').removeClass('issue');
  $("#inlineForm").find('.help-block ul li').remove();
  $("#inlineForm").find('.text-danger').text('');
  $('#addServiceForm')[0].reset();
});
</script>
@endsection