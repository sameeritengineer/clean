@extends('supervisor.layouts.index')
@section('title','Unfilled Jobs')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Unfilled Jobss</h3>
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
                  <h4 class="card-title"></h4><a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a><div class="heading-elements"></div>
                </div>
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      @foreach($countries as $country)
                        <li class="nav-item">
                          <a class="nav-link active" id="{{$country->name}}-tab" data-toggle="tab" href="#{{$country->name}}" role="tab" aria-controls="{{$country->name}}" aria-selected="true">{{$country->name}}</a>
                        </li>
                      @endforeach
                    </ul>
                    <div class="tab-content tab-country-wrapper">
                      @foreach($countries as $country)
                        <div class="tab-pane show active" id="{{$country->name}}" role="tabpanel" aria-labelledby="{{$country->name}}">
                          <div class="container-fluid">
                            <div class="row">
                              @foreach($country->states as $state)
                              @if(in_array($state->name,$states))
                                <div class="col-md-3" onclick="states('{{$state->id}}')">
                                  <div class="cnty-btn active">
                                    <span class="country-name-text">{{$state->name}}</span><span class="circle-right "></span>
                                  </div>
                                </div>
                              @else
                                <div class="col-md-3" onclick="states('{{$state->id}}')">
                                  <div class="cnty-btn">
                                    <span class="country-name-text">{{$state->name}}</span><span class="circle-right "></span>
                                  </div>
                                </div>
                              @endif
                              @endforeach
                          </div>
                           <div class="row"><div class="col-md-12"><hr class="line-bx"></div><div class="append_cities"></div></div>
                          </div>
                        </div>
                      @endforeach
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
function states(id)
{ 
  $('.append_cities').html('');
  var url = "{{url('serviceadmin/get_unfilled_jobs_city')}}/"+id;
  $.ajax
  ({
    type: "get",   
    url: url,             
    data: {"_token": "{{csrf_token()}}"},
    success:function(data)
    {
      console.log(data)
      $('.append_cities').append(data);
    },    
  });
}
</script>
@endsection
