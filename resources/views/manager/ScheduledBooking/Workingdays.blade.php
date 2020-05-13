@extends('manager.layouts.index')
@section('title','Working Days')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">Days</h3>
      </div>  
      <div class="content-body">
        <section class="">
          <div class="row">           
            <div class="col-md-12">
              <div class="card">
                <div class="card-body collapse in">
                <div class="card-block ">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                      <button type="button" class="btn btn-outline-warning block btn-lg" data-toggle="modal"data-target="#inlineForm">Add Working days</button>
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
                          <th>Working Status</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($Workingdays as $Workingday)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$Workingday->day}}</td>
                          @if($Workingday->status == 0)     
                          <td>
                          <label class="switch"><input type="checkbox" data-id="{{$Workingday->id}}" data-site="gratis" class="switchery workingStatus" value="{{$Workingday->status}}" ><span class="slider round"></span></label>
                          </td>
                          @else                         
                          <td>
                          <label class="switch"><input type="checkbox" data-id="{{$Workingday->id}}" data-site="gratis" class="switchery workingStatus" value="{{$Workingday->status}}" checked><span class="slider round"></span></label>
                          </td>
                          @endif
                          <td><button type="button" class="btn btn-outline-warning" onclick="deleteWorkingday({{$Workingday->id}},'{{$Workingday->day}}')"><i class="fa fa-trash"></i></button></td>    
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
<div class="modal fade text-xs-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModal13" aria-hidden="false" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close clear" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <label class="modal-title text-text-bold-600" id="myModal13">Add Working Days</label>
      </div>
      <form action="{{route('manager::addworkingday')}}" id="WorkingdayForm" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
            <div class="controls">              
              <input type="text" placeholder="Add Name" id="day" name="day"  class="form-control" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" required data-validation-regex-message="Only Alphabets and Space allwoed">
            </div>
            <span id="error" class="text-danger"></span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="addworkingday" value="Submit">
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Delete Working Day</label>
      </div>
      <form action="{{route('manager::deleteWorkingday')}}" id="deleteWorkingdayForm" method="post" novalidate>
        @csrf
        <div class="modal-body">
          <h4>Are you sure want to delete this Working Day <span id="text"></span>?</h4>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg clear" data-dismiss="modal" value="Close">
          <input type="submit" class="btn btn-outline-primary btn-lg" id="deleteWorkingday" value="Delete">
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">

$(".workingStatus").on("change", function(){
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
                url: '{{route('manager::updateWorkingStatus')}}',
                success: function (response) {
                    if(response == "success"){
                    if(value == 0){
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

function deleteWorkingday(id,name)
{
  var name = $('#deleteForm').find('#text').text(name);
  var id = $('#deleteForm').find('#id').val(id);
  $("#deleteForm").modal("show");
}

$('#deleteWorkingday').click(function(e)
{
  e.preventDefault();
  var deleteWorkingdayForm = $("#deleteWorkingdayForm")[0];
  var formData = new FormData(deleteWorkingdayForm);
  var action = $("#deleteWorkingdayForm").attr('action');
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
        toastr.success("You'r successfully deleted Working Day", "Great !");
        $('#deleteWorkingdayForm')[0].reset();
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

$('#WorkingdayForm').submit(function(e) 
{
  var value = $('#day').val()  
  var regex = new RegExp(/^[a-zA-Z][a-zA-Z ]+[a-zA-Z]$/);
  if(value.match(regex))
  {
    var action = $('#WorkingdayForm').attr('action');
    var WorkingdayForm = $('#WorkingdayForm')[0];
    var formData = new FormData(WorkingdayForm);
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
          toastr.success("You'r successfully added Working Day", "Great !");
          $("#inlineForm").modal('hide');
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          $('#WorkingdayForm')[0].reset();
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
  $("#inlineForm").find('.form-group.validate input, .form-group.validate select, .form-group.validate textarea').css({'border':'1px solid #CCD6E6','color':'#3F587E'});
  $("#inlineForm").find('.form-group').removeClass('issue');
  $("#inlineForm").find('.form-group').removeClass('error');
  $("#inlineForm").find('.form-group .help-block ul li').remove();
  $("#inlineForm").find('.text-danger').text('');
  $('#WorkingdayForm')[0].reset();
});
</script>
@endsection