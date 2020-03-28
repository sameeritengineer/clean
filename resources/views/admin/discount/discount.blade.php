@extends('admin.layouts.index')
@section('title','Discount Codes')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
        	<h3 class="content-header-title mb-0">Discount Codes</h3>
    	</div>  
    	<div class="content-body">
    		<section class="">
    			<div class="row">   				
    				<div class="col-md-12">
    					<div class="card">
    						<div class="card-body collapse in">
								<div class="card-block ">
  								<div class="col-lg-2 col-md-6 col-sm-12">
        								<button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Discount Codes</button>
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
                          <th>Discount Of</th>
                          <th>De-Active/Active</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                      	@foreach($discountCodes as $discount)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$discount->code_name}}</td>
                          <td>{{$discount->discount}}</td>
                          @if($discount->status == 0)     
                          <td>
                          <label class="switch"><input type="checkbox" data-id="{{$discount->id}}" data-site="gratis" class="switchery discountStatus" value="{{$discount->status}}" ><span class="slider round"></span></label>
                          </td>
                          @else                         
                          <td>
                          <label class="switch"><input type="checkbox" data-id="{{$discount->id}}" data-site="gratis" class="switchery discountStatus" value="{{$discount->status}}" checked><span class="slider round"></span></label>
                          </td>
                          @endif
                          <td><button type="button" class="btn btn-outline-warning" onclick="deleteDiscount({{$discount->id}},'{{$discount->code_name}}')"><i class="fa fa-trash"></i></button></td>    
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Discount Codes</label>
      </div>
      <form action="{{route('addDiscount')}}" id="DiscountForm" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
          	<h5>Discount Code</h5>
            <div class="controls">              
              <input type="text" placeholder="Add discount Code" id="codename" name="codename"  class="form-control" data-validation-regex-regex="^[a-zA-Z ]*$" required data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span id="error" class="text-danger"></span>
          </div>
          <div class="form-group">
          	<h5>Discount</h5>
            <div class="controls">              
              <input type="text" placeholder="Enter Value in %" id="discount" name="discount"  class="form-control percentage-inputmask">
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

<div class="modal fade text-xs-left" id="deleteForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete Discount Code</label>
      </div>
      <form action="{{route('destroyDiscount')}}" id="deleteDiscountForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this Discount Code <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteDiscount" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">

function deleteDiscount(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deleteDiscount').click(function(e)
{
  e.preventDefault();
  var deleteDiscountForm = $("#deleteDiscountForm")[0];
  var formData = new FormData(deleteDiscountForm);
  var action = $("#deleteDiscountForm").attr('action');
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
        toastr.success("You'r successfully deleted discount Code", "Great !");
        $('#deleteDiscountForm')[0].reset();
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

$('#DiscountForm').submit(function(e) 
{
  var value = $('#codename').val();
  var regex = new RegExp(/^[a-zA-Z]+(\s[a-zA-Z]+)?$/);
  if(value.match(regex))
  {
    var action = $('#DiscountForm').attr('action');
    var DiscountForm = $('#DiscountForm')[0];
    var formData = new FormData(DiscountForm);
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
          toastr.success("You'r successfully added discount code", "Great !");
          $("#inlineForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('#DiscountForm')[0].reset();
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


$(".discountStatus").on("change", function(){
    var value = $(this).val();
    var id = $(this).attr('data-id');
    
      $.ajax({
               type: 'POST',
               context: this,
               data: {
                    "id": id,   
                    "value":value,
                    " _token": "{{ csrf_token() }}",
                                        
                    },
            url: '{{route('updateDiscountStatus')}}',
            success: function (response) 
            {
	            if(response == "success")
	            {
	                if(value == 0)
	                {
	                  $(this).val(1);
	                  $(this).parent().next('td').empty();                           
	                }
	                if (value == 1)
	                {
	                  $(this).val(0);
	                  $(this).parent().next('td').empty();    
	                }
	            } 
          }
       });
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
  $('#DiscountForm')[0].reset();
});
</script>
@endsection