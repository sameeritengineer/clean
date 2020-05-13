@extends('supervisor.layouts.index')
@section('title','Show Opportunity')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Show Current Opportunity</h3>
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb"></ol>
          </div>
        </div>
      </div>
    </div>
    <div class="serviceproviderDelete"></div>
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
        @php Session::forget('success'); @endphp
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error) 
          <li>{{ $error}}</li>
        @endforeach 
      </ul>
    </div>
    @endif 
    <div class="content-body">
      <div class="row">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title"></h4>
                  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements"></div>
                </div>
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">
                    <div class="table-responsive">
                    <div class="card" style="width:50%; margin:0 auto">
                      <img src="@if($opportunity->cust_image != null) {{asset('/profile/'.$opportunity->cust_image)}} @else {{asset('no-profile.png')}} @endif" class="card-img-top" alt="{{$opportunity->cust_firstname}}" style="width:200px;">
                      <div class="card-body">
                        <h5 class="card-title"><strong>Customer Id | Customer Name:</strong> {{$opportunity->cutomer_id}} | {{$opportunity->cust_firstname}}</h5>
                        <p class="card-text"><strong>Job ID:</strong> {{$opportunity->id}}</p>
                        <p class="card-text"><strong>Customer Zipcode:</strong> {{$opportunity->zipcode}}</p>
                        <p class="card-text"><strong>Customer Address:</strong> {{$opportunity->customer_address}}</p>
                        <p class="card-text"><strong>Booking Date:</strong> {{$opportunity->date}} </p>
                        <p class="card-text"><strong>Booking Time:</strong> {{$opportunity->time}} </p>
                        <p class="card-text"><strong>Customer Booked Services:</strong> {{$opportunity->Services_names}} </p>
                        <button type="button" class="btn btn-outline-primary block btn-lg" data-toggle="modal"data-target="#inlineForm">Assign To</button>
                      </div>
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
        <label class="modal-title text-text-bold-600" id="myModalLabel33">Assign To</label>
      </div>
      <form action="{{route('provider_notification')}}" id="provider_notification" method="post" novalidate>
        @csrf
        <div class="modal-body">          
          <div class="form-group">
            <div class="controls">
              <input type="hidden" name="id" value="{{$opportunity->id}}">            
              <select class="form-control" required name="provider_id">
                @foreach($opportunity->providers as $provider)
                @php $explode = explode('.',$provider)@endphp
                  <option value="{{$explode[0]}}">{{$explode[1]}}</option>
                @endforeach
              </select>
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
<script type="text/javascript">
$('#provider_notification').submit(function(e) 
{
    var action = $('#provider_notification').attr('action');
    var addServiceForm = $('#provider_notification')[0];
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
        if(data.status == 1)
        {
          toastr.success("You'r successfully sent notification", "Great !");          
          $("#inlineForm").modal('hide');         
          location.href = data.url;
        }
        else
        {
          toastr.error("Something error. Try again", "Oops !");
        }          
      }
    });
  return false;
  e.preventDefault();
});
</script>
@endsection
