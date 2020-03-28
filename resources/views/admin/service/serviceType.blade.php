@extends('admin.layouts.index')
@section('title','Service Type')
@section('content')
<div class="app-content content container-fluid load">
    <div class="content-wrapper data">
      <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        	<h3 class="content-header-title mb-0">Service type</h3>
    	</div>  
    	<div class="content-body">
    		<section class="">
    			<div class="row">   				
    				<div class="col-md-12">
    					<div class="card">
    						<div class="card-body collapse in">
								  <div class="card-block ">
  									<div class="col-lg-2 col-md-6 col-sm-12">
                      <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Service Type</button>
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
                <div class="table-responsive">               
                <table class="table table-striped table-bordered zero-configuration">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Image</th>
                      <th>Service</th>
                      <th>Name</th>
                      <th>Spanish Name</th>
                      <th>Price</th>
                      <th>Service Description</th>
                      <th>Status</th>
                      <th>Status</th> 
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach($serviceTypes as $types)
                    <tr>                  
                      <td>{{$loop->iteration}}</td>
                     <!--  <td><img src="{{$types->image}}" style="height:100px;width:100px;"/></td> -->
                     @if($types->image != null)
                      <td><img src="{{asset('normal_images/'.$types->image)}}" style="height:80px;width:80px;"></td>
                      @else
                      <td><img src="{{asset('profile/no-image-icon.png')}}" style="height:80px;width:80px;"></td> 
                     @endif 
                      <td>{{$types->service->name}}</td>
                      <td>{{$types->name}}</td>
                      <td>{{$types->spanish_name}}</td>
                      @php
                        $serviceprice = App\ServicePrice::where('servicetype_id',$types->id)->value('price');
                      @endphp
                      <td>{{$serviceprice}}</td> 
                      <td>{!!$types->description!!}</td>
                       @if($types->status == 0)
                      <td><input type="checkbox"  id="switchery0" class="switchery serviceTypeStatus" data-id="{{$types->id}}" value="{{$types->status}}"></td>
                      <td><input type="button"  class="serviceapproval btn btn-danger" value="Pending"></td>
                      @else
                      <td><input type="checkbox" id="switchery" data-id="{{$types->id}}" class="switchery serviceTypeStatus" value="{{$types->status}}" checked/></td>
                      <td><input type="button" class="serviceapproval btn btn-success" value="Approved"></td>
                      @endif  
                      <td><button type="button" class="btn btn-outline-warning" onclick="editServiceType({{$types->id}})"><i class="fa fa-pencil"></i></button></td>
                      <td><button type="button" class="btn btn-outline-warning" onclick="deleteServiceType({{$types->id}},'{{$types->name}}')"><i class="fa fa-trash"></i></button></td>    
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
          <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Service Type</label>
      </div>
      <form action="{{route('addServiceType')}}" id="addServiceTypeForm" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="modal-body">          
           <div class="form-group">
            <h5>Select Service</h5>
              <div class="controls">
                <select name="serviceId" id="select" required class="form-control">
                  <option value="">Select Service</option>
                  @foreach($services as $service)
                    <option value="{{$service->id}}">{{$service->name}}</option>
                  @endforeach
                </select>
              </div>
          </div>  
          <div class="form-group">
             <h5>Service Type</h5>
            <div class="controls">              
              <input type="text" placeholder="Add Name" id="name" name="name"  class="form-control" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" required data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
          </div>
          <div class="form-group">
             <h5>Service Type Spanish</h5>
            <div class="controls">              
              <input type="text" placeholder="Add Name Spanish" id="Spanish_name" name="Spanish_name"  class="form-control">
            </div>
          </div>
          <div class="form-group">
             <h5>Price</h5>
            <div class="controls">              
              <input type="number" placeholder="Add Price" id="price" name="price"  class="form-control" required="required">
            </div>
          </div>
          <div class="form-group">
             <h5>Service Description</h5>
            <div class="controls">              
              <textarea placeholder="Add Description" id="description" name="description"  class="form-control"  required ></textarea>
              <span class="frm-error" style="color:red;">
            </div>
          </div>
          <div class="form-group">
             <h5>Service Description Spanish</h5>
            <div class="controls">              
              <textarea placeholder="Add Description Spanish" id="Spanish_description" name="Spanish_description"  class="form-control"></textarea>
              <span class="frm-error" style="color:red;">
            </div>
          </div>
          <div class="form-group">
            <h5>Service Image</h5>
            <div class="controls">              
              <input type="file" id="image" name="image" class="form-control"  aceept="image/*" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="addServiceType" value="Submit">
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade text-xs-left" id="editForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="false" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content input-validation">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Edit Service Type</label>
      </div>
      <form action="{{route('updateServiceType')}}" id="updateServiceTypeForm" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <h5>Select Service<span class="required"></span></h5>
              <div class="controls">
                <select name="serviceId" id="select" required class="form-control">
                  <option value="" disabled="disabled">Select Service</option>
                  @foreach($services as $service)
                    <option value="{{$service->id}}">{{$service->name}}</option>
                  @endforeach
                </select>
              </div>
          </div>          
          <div class="form-group">
             <h5>Service Type<span class="required"></span></h5>
            <div class="controls">
              <input type="hidden" id="editId" name="id">              
              <input type="text" placeholder="Edit Name" id="editName" name="name" required class="form-control" data-validation-regex-regex="([a-zA-Z][a-zA-Z ]+[a-zA-Z]$)" data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span class="error text-danger"></span>           
          </div>
          <div class="form-group">
             <h5>Service Type Spanish</h5>
            <div class="controls">              
              <input type="text" placeholder="Add Name Spanish" id="Spanish_name" name="Spanish_name"  class="form-control">
            </div>
          </div>
          <div class="form-group">
            <h5>Price<span class="required"></span></h5>
            <div class="controls">              
              <input type="number" placeholder="Add Price" id="editprice" name="price"  class="form-control" required="required">
            </div> 
             <span id="error" class="text-danger"></span>          
          </div>
           <div class="form-group">
             <h5>Service Description<span class="required"></span></h5>
            <div class="controls">              
              <textarea placeholder="Add Description" id="editdescription" name="description" class="form-control" required></textarea>
            </div>
            <span id="error" class="text-danger"></span>
          </div>
          <div class="form-group">
             <h5>Service Description Spanish</h5>
            <div class="controls">              
              <textarea placeholder="Add Description Spanish" id="editSpanish_description" name="editSpanish_description"  class="form-control"></textarea>
              <span class="frm-error" style="color:red;">
            </div>
          </div>
          <div class="form-group">
            <h5>Service Image<span class="required"></span></h5>
            <img id="imageshow" src="" style="height:60px;width:60px;">
            <div class="controls">              
              <input class="form-control" type="file"  id="image" name="image"   accept="image/*">
            </div>          
            <span id="error" class="text-danger"></span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="updateServiceType" value="Update">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete Service</label>
      </div>
      <form action="{{route('destroyServiceType')}}" id="deleteServiceTypeForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this service <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteServiceType" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">

function editServiceType(id)
{
  var url = "{{route('editServiceType')}}";
  var imgUrl= "{{url('normal_images')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    { 
      $("#editForm").find('#editId').val(data.id);
      $("#editForm").find('#select').val(data.service_id);
      $("#editForm").find('#editName').val(data.name);
      $("#editForm").find('#Spanish_name').val(data.spanish_name);
      $("#editForm").find('#editprice').val(data.price);
      CKEDITOR.instances['editdescription'].setData(data.description);
      CKEDITOR.instances['editSpanish_description'].setData(data.spanish_decription);
      $("#editForm").find('#imageshow').attr('src',imgUrl+"/"+data.image);
      $("#editForm").modal("show");      
    },    
  });
}

$('#updateServiceTypeForm').submit(function(e)
{
   e.preventDefault();

    var des = CKEDITOR.instances['editdescription'].getData();
    $("#editForm").find('#editdescription').text(des);
     var spanisdes = CKEDITOR.instances['editSpanish_description'].getData();
    $("#editForm").find('#editSpanish_description').text(spanisdes);

  // var des = $('#editdescription').val();
 
  var value = $('#editName').val()
  var regex = new RegExp(/^[a-zA-Z][a-zA-Z ]+[a-zA-Z]$/);

  if(value.match(regex))
  {
    var action = $("#updateServiceTypeForm").attr('action');
    var updateServiceTypeForm = $("#updateServiceTypeForm")[0];
    var formData = new FormData(updateServiceTypeForm);

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
          toastr.success("You'r successfully updated service type", "Great !");
          $('#updateServiceTypeForm')[0].reset();
          $("#editForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('.load').load(url+ ' .data', function()
          {
            $('.zero-configuration').DataTable();
          });
          CKEDITOR.instances.editDescription.setData("");
          CKEDITOR.instances.editSpanish_description.setData("");
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

function deleteServiceType(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deleteServiceType').click(function(e)
{
  e.preventDefault();
  var deleteServiceTypeForm = $("#deleteServiceTypeForm")[0];
  var formData = new FormData(deleteServiceTypeForm);
  var action = $("#deleteServiceTypeForm").attr('action');
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
      console.log(data);      
      if(data == 1)
      {
        toastr.success("You'r successfully deleted service type", "Great !");
        $('#deleteServiceTypeForm')[0].reset();
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

$('#addServiceTypeForm').submit(function(e) 
{
    var des = CKEDITOR.instances['description'].getData();  
    $("#addServiceTypeForm").find('#description').text(des);
    var spanish_desc = CKEDITOR.instances['Spanish_description'].getData();
    $("#addServiceTypeForm").find('#Spanish_description').text(spanish_desc);
    var action = $('#addServiceTypeForm').attr('action');
    var addServiceTypeForm = $('#addServiceTypeForm')[0];
    var formData = new FormData(addServiceTypeForm);
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
          toastr.success("You'r successfully added service type", "Great!");  
            $('#addServiceTypeForm')[0].reset();        
          $("#inlineForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('.load').load(url+ ' .data', function()
          {
            $('.zero-configuration').DataTable();
          });
          CKEDITOR.instances.description.setData("");
          CKEDITOR.instances.Spanish_description.setData("");
        }
        else
        {
          //$('#addServiceTypeForm')[0].reset();
          toastr.error("Something error. Try again", "Oops !");
        }
        if(data.errors)
        {
          $(".frm-error").html('');
              $.each(data.errors, function(index, value)
              {
                console.log("index "+ index + " value " +value)
                $("#addServiceTypeForm input[name='" + index + "']").parent().find('span').html(value);
              });     
        }           
      }
    });
  return false;
  e.preventDefault();
});

$('body').on('change', '.serviceTypeStatus', function()
{
  var value = $(this).val();
  var id = $(this).attr('data-id');
  $.ajax
  ({
    type: 'POST',
    context: this,
    data: {"id": id,"value":value," _token": "{{ csrf_token() }}",},
    url: '{{route('serviceTypeStatus')}}',
    success: function (response)
    {
      if(response == "success")
      {
        if(value == 0)
        {
          $(this).val(1);
          $(this).parent().next('td').empty();
          $(this).parent().next('td').html( '<input type="button" class="btn btn-success" value="Approved" >' );
        }
        if (value == 1)
        {
          $(this).val(0);
          $(this).parent().next('td').empty();
          $(this).parent().next('td').html('<input type="button" class="btn btn-danger" value="Pending">');
        }
      } 
    }
  });
});

$('.clear').click(function()
{
  $("#editForm").find('input[type="file"]').val('').clone(true);
  $("#editForm").find('.remove').remove();
  $("#editForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#editForm").find('.form-group').removeClass('error');
  $("#editForm").find('.form-group').removeClass('issue');
  $("#editForm").find('.help-block').remove();
  $("#editForm").find('.text-danger').text('');

  $("#inlineForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#inlineForm").find('.form-group').removeClass('error');
  $("#inlineForm").find('.form-group').removeClass('issue');
  $("#inlineForm").find('.help-block ul li').remove();
  $("#inlineForm").find('.text-danger').text('');
  $('#addServiceTypeForm')[0].reset();
  CKEDITOR.instances.description.setData("");
  CKEDITOR.instances.editDescription.setData("");
});
CKEDITOR.replace('description', {
     extraPlugins: 'autoembed,embedsemantic,image2,mathjax,codesnippet,font,colorbutton',
      removeButtons: 'Font',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      colorButton_colors: 'CF5D4E,454545,FFF,CCC,DDD,CCEAEE,66AB16',
      colorButton_enableAutomatic: false
    });
CKEDITOR.replace('Spanish_description', {
      extraPlugins: 'autoembed,embedsemantic,image2,mathjax,codesnippet,font,colorbutton',
      removeButtons: 'Font',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      colorButton_colors: 'CF5D4E,454545,FFF,CCC,DDD,CCEAEE,66AB16',
      colorButton_enableAutomatic: false
    });
</script>
@endsection