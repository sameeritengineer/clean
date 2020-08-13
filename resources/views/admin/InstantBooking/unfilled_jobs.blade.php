@extends('admin.layouts.index')
@section('title','Unfilled Jobs')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Unfilled Jobs</h3>
        <div class="row breadcrumbs-top"><div class="breadcrumb-wrapper col-xs-12"><ol class="breadcrumb"></ol></div></div>
      </div>
    </div>

    <div class="content-body cnt-wrapper">
      <div class="row">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">
                    @php $lists = \App\AdminSession::where('admin_id','!=',auth()->user()->id)->where('status','active')->get();@endphp
                    @if(count($lists)>0)
                      @foreach($lists as $list)
                      @php $user = \App\User::find($list->admin_id);@endphp
                        @if(Request::url() === $list->url)
                          {{$user->first_name." ".$user->last_name}} is working on this page.
                        @endif
                      @endforeach
                    @endif
                  </h4>
                </div>
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      @foreach($countries->slice(0,3) as $country)
                        <li class="nav-item">
                          <a class="nav-link" id="{{$country->name}}-tab" data-toggle="tab" href="#{{$country->name}}" role="tab" aria-controls="{{$country->name}}" aria-selected="true" onclick="get_state_list('{{$country->id}}')">{{$country->name}}</a>
                        </li>
                      @endforeach
                    </ul>
                    <div class="tab-content tab-country-wrapper">
                      <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid">
                          <div class="row"><div class="append_countries">
                            </div></div>
                          <div class="row"><div class="append_states"></div></div>
                          <div class="row">
                            
                            <div class="append_cities"></div>
                          </div>
                          <div class="row open_opportunity" style="display: none">
                            <div class="col-md-12"><hr class="line-bx mt-5 mb-5"><h4 class="mb-5">Job Lists </h4></div>

                            <table class="table">
                              <thead>
                                <th>View Job</th>
                                <th>Job ID</th>
                                <th>Customer Name</th>
                                <th>Customer Address</th>
                                <th>Zipcode</th>
                                <th>Booking Date</th>
                                <th>Booking Time</th>
                                <th>Service</th>
                              </thead>
                              <tbody class="append_jobs"></tbody>
                            </table>
                          </div>
                          <div class="row"><div class="append_provider_list"></div></div>
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
<script>
function get_state_list(country_id)
{
  $('.append_states').html('');
  $('.append_cities').html('');
  $('.append_countries').html('');
  $('.append_provider_list').html('');
  $('.open_opportunity').css('display','none');
  var url = "{{url('serviceadmin/get_unfilled_jobs_state')}}/"+country_id;
  $.ajax
  ({
    type: "get",   
    url: url,             
    data: {"_token": "{{csrf_token()}}"},
    success:function(data)
    {
      if(data.data)
      {
        $('.append_countries').append('<div class="col-md-12"><br></div><h4>Countries </h4>'+data.data);
      }
      else
      {
        $('.append_states').append('<div class="col-md-12"><br></div><h4>States </h4>'+data);
      }
    },    
  });
}

function states(id)
{ 
  $('.append_cities').html('');
  $('.append_provider_list').html('');
  $('.open_opportunity').css('display','none');
  var url = "{{url('serviceadmin/get_unfilled_jobs_city')}}/"+id;
  $.ajax
  ({
    type: "get",   
    url: url,             
    data: {"_token": "{{csrf_token()}}"},
    success:function(data)
    {
      $('.append_cities').append('<div class="col-md-12"><hr class="line-bx"></div><h4>Cities </h4>'+data);
    },    
  });
}

function get_jobs_lists(city_id)
{
  $('.open_opportunity').css('display','none');
  $('.append_jobs').html('');
  $('.append_provider_list').html('');
  var url = "{{url('serviceadmin/get_unfilled_jobs')}}/"+city_id;
  $.ajax
  ({
    type: "get",   
    url: url,             
    data: {"_token": "{{csrf_token()}}"},
    success:function(data)
    {
      console.log(data)
      $('.open_opportunity').css('display','block');
      $('.append_jobs').append(data);
    },    
  });
}
function get_job_list(zipcode,data)
{
  $('.append_provider_list').html('');
  var url = "{{url('serviceadmin/get_provider_zipcode')}}";
  $.ajax
  ({
    type: "post",   
    url: url,             
    data: {"_token": "{{csrf_token()}}","data":data},
    success:function(data)
    {
      console.log(data)
      $('.open_opportunity').css('display','none');
      $('.append_jobs').html('');
      $('.append_cities').html('');
      $('.append_states').html('');
      $('.append_provider_list').append(data);
    },    
  });
}
</script>
@endsection
