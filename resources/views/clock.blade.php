
<!-- ClockPicker Stylesheet -->
<link rel="stylesheet" type="text/css" href="{{asset('timepicker/dist/bootstrap-clockpicker.min.css')}}">
<form  method="post" action="{{route('serviceadmin/democlock')}}" enctype="multipart/form-data" novalidate>
    <div class="row">
      <div class="col-md-12"> 
        <div class="row">
          <div class="form-group col-md-6">       	
             <label>Start time</label>
        	   <div class="input-group" id="startingtimepicker" >
        	   <input type="text" class="form-control startclock"  name="clock" id="startclock">
        	   <span class="input-group-addon"><span class="ft-clock"></span></span>
        	   </div>     
  		    </div>
        </div>
      </div>
    </div>
    <input type="submit" name="submit">
</form>						
<!-- jQuery and Bootstrap scripts -->
<script type="text/javascript" src="{{asset('timepicker/assets/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('timepicker/assets/js/bootstrap.min.js')}}"></script>
 
<!-- ClockPicker script -->
<script type="text/javascript" src="{{asset('timepicker/dist/bootstrap-clockpicker.min.js')}}"></script>
 
<script type="text/javascript">
$('#startingtimepicker').clockpicker({placement: 'bottom',align: 'right',donetext: 'Done'})
$('.startclock').change(function(){
 var starttime = console.log(this.value);   
 $('.form-control .startclock').find("#startclock").val(starttime);
});
</script>




