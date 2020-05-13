@extends('admin.layouts.index')
@section('title','Customer Faq')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
     <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
        	<h3 class="content-header-title mb-0">Faq</h3>
    	  </div>  
    	  <div class="content-body">
    		 <section class="">
    			<div class="row">   				
    				<div class="col-md-12">
    					<div class="card">
    						<div class="card-body collapse in">
								<div class="card-block ">
  									<div class="col-lg-2 col-md-6 col-sm-12">
                      <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Customer Faq</button>
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
                          <th>Question</th>
                          <th>Answer</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($customer_faqs as $faq)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$faq->question}}</td>
                          <td>{!!$faq->answer!!}</td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="editcustomerFaq({{$faq->id}})"><i class="fa fa-pencil"></i></button></td>
                          <td><button type="button" class="btn btn-outline-warning" onclick="deletecustomerFaq({{$faq->id}})"><i class="fa fa-trash"></i></button></td>    
                        </tr>
                        @endforeach 
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>#</th>
                          <th>Question</th>
                          <th>Answer</th>
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Add customer Faq</label>
      </div>
      <form action="{{route('addcustomerfaq')}}" id="FaqForm" method="post" novalidate>
        @csrf
        <div class="modal-body"> 
        <label>Add Question: </label>              
          <div class="form-group">
            <div class="controls">      
              <input type="text" placeholder="Add Question" id="question" name="question" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed"  class="form-control" required data-validation-required-message="question is required">
            </div>
            <span id="error" class="text-danger"></span>
          </div>
          <label>Add Question in Spanish: </label>              
          <div class="form-group">
            <div class="controls">      
              <input type="text" placeholder="Add Question Spanish" id="question" name="spanish_question" class="form-control">
            </div>
          </div>
          <label>Add Answer: </label>   
          <div class="form-group">
             <div class="controls">              
            <textarea  id="description" name="description"  class="form-control"  required ></textarea>
              <span class="frm-error" style="color:red;">
            </div>
            <span id="error" class="text-danger"></span>
          </div>
          <label>Add Answer Spanish: </label>   
          <div class="form-group">
             <div class="controls">              
              <textarea  id="spanish_description" name="spanish_description"  class="form-control"></textarea>
             </div>
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Edit customer Faq</label>
      </div>
      <form action="{{route('updatecustomerFaq')}}" id="updatecustomerFaq" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
             <h5>Edit Question:<span class="required"></span></h5>
            <div class="controls">
              <input type="hidden" id="editId" name="id">              
              <input type="text" placeholder="Edit Question" id="editName" name="name" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed"  class="form-control" required data-validation-required-message="question is required">
            </div>
            <span class="error text-danger"></span>           
          </div>
          <label>Add Question in Spanish: </label>              
          <div class="form-group">
            <div class="controls">      
              <input type="text" placeholder="Add Question Spanish" id="spanishquestion" name="spanish_question" class="form-control">
            </div>
          </div>
          <div class="form-group">
             <h5>Edit Answer:<span class="required"></span></h5>
            <div class="controls">              
              <textarea placeholder="Edit Description" id="editdescription" name="description" class="form-control" required></textarea>
            </div>
            <span id="error" class="text-danger"></span>
          </div>
          <label>Add Answer Spanish: </label>   
          <div class="form-group">
             <div class="controls">              
              <textarea  id="editspanish_description" name="editspanish_description"  class="form-control"></textarea>
             </div>
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete customer Faq</label>
      </div>
      <form action="{{route('destroycustomerfaq')}}" id="deletecustomerFaqForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this faq <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deletecustomerFaq" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function editcustomerFaq(id)
{
  var url = "{{secure_url('serviceadmin/edit-customer-Faq')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","id":id},
    success:function(data)
    {
console.log(data)
      $("#editForm").find('#editId').val(data.id);
      $("#editForm").find('#editName').val(data.question);
      $("#editForm").find('#spanishquestion').val(data.spanishquestion);
       CKEDITOR.instances['editdescription'].setData(data.answer);
       CKEDITOR.instances['editspanish_description'].setData(data.spanishanswer);
      $("#editForm").modal("show");      
    },    
  });
}

$('#updatecustomerFaq').submit(function()
{
  var des = CKEDITOR.instances['editdescription'].getData();
  $("#editForm").find('#editdescription').text(des);
  var editspanish = CKEDITOR.instances['editspanish_description'].getData();
  $("#editForm").find('#editspanish_description').text(editspanish);
  var action = $("#updatecustomerFaq").attr('action');
  var updatecustomerFaq = $("#updatecustomerFaq")[0];
  var formData = new FormData(updatecustomerFaq);
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
          toastr.success("You'r successfully updated Faq", "Great !");
          $('#updatecustomerFaq')[0].reset();
          $("#editForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('.load').load(url+ ' .data', function()
          {
            $('.zero-configuration').DataTable();
          });
          CKEDITOR.instances.editdescription.setData("");
          CKEDITOR.instances.editspanish_description.setData("");
        }
        else
        {
          toastr.error("Error Occur, Try Again", "Oops !");
        }   
      },
    }); 
  return false;
});

function deletecustomerFaq(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deletecustomerFaq').click(function(e)
{
  e.preventDefault();
  var deletecustomerFaqForm = $("#deletecustomerFaqForm")[0];
  var formData = new FormData(deletecustomerFaqForm);
  var action = $("#deletecustomerFaqForm").attr('action');
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
        toastr.success("You'r successfully deleted FAQ", "Great !");
        $('#deletecustomerFaqForm')[0].reset();
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

$('#FaqForm').submit(function(e) 
{
    var value = $('#question').val()  
    var des = CKEDITOR.instances['description'].getData();
    $("#FaqForm").find('#description').text(des);
    var spanishdes = CKEDITOR.instances['spanish_description'].getData();
    $("#FaqForm").find('#spanish_description').text(spanishdes);
    var action = $('#FaqForm').attr('action');
    var addServiceTypeForm = $('#FaqForm')[0];
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
          toastr.success("You'r successfully added faq", "Great !");  
            $('#FaqForm')[0].reset();        
          $("#inlineForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('.load').load(url+ ' .data', function()
          {
            $('.zero-configuration').DataTable();
          });
          CKEDITOR.instances.description.setData("");
          CKEDITOR.instances.spanish_description.setData("");
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
                $("#FaqForm input[name='" + index + "']").parent().find('span').html(value);
              });     
        }           
      }
    });
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