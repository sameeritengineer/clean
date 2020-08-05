@extends('manager.layouts.index')
@section('title','Dashboard')
@section('content')
<style type="text/css">
@media screen and (min-width: 1024px) {
  div.myautoscroll {
      width: 100%;
      height: 245px;
      min-height: 245px;
      overflow-x: hidden;
      overflow-y: hidden; 
  }

  div.myautoscroll:hover {
    overflow-x: auto;
    overflow-y: scroll; 
  }
  /* width */
  div.myautoscroll::-webkit-scrollbar {
    width: 8px;
  }
  /* Track */
  div.myautoscroll::-webkit-scrollbar-track {
    border-radius: 5px;
  }
  /* Handle */
  div.myautoscroll::-webkit-scrollbar-thumb {
    background: #aaa; 
    border-radius: 10px;
  }
}
@media screen and (max-width:1024px) {
  div.myautoscroll {
      width: 100%;
      height: 245px;
      min-height: 245px;
      overflow-x: auto;
      overflow-y: scroll; 
  }
}
</style>
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row"></div>
      <div class="content-body">       
        <div class="row">
            <a href="{{route('manager::providers')}}">            
            <div class="col-xl-3 col-lg-6 col-xs-12">
              <div class="card">
                  <div class="card-body">
                    <div class="media">
                        <div class="p-2 text-xs-center bg-warning bg-darken-2 media-left media-middle">
                          <i class="icon-user-following font-large-2 white"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-warning white media-body">
                          <h5>All Service Providers</h5>
                          <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countallserviceprovider}}</h5>
                        </div>
                    </div>
                  </div>
              </div>
            </div> 
            </a>

          <a href="{{route('manager::OpenOpportunity')}}">
           <div class="col-xl-3 col-lg-6 col-xs-12">
              <div class="card">
                  <div class="card-body">
                    <div class="media">
                        <div class="p-2 text-xs-center bg-primary bg-darken-2 media-left media-middle">
                          <i class="fa fa-street-view font-large-2 white"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-primary white media-body">
                          <h5>New Opportunities</h5>
                          <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countnewOpportunity}}</h5>
                        </div>
                    </div>
                  </div>
              </div>
            </div> 
          </a>
          <a href="{{route('manager::viewAllCurrentRunningJobs')}}">
          <div class="col-xl-3 col-lg-6 col-xs-12">
              <div class="card">
                  <div class="card-body">
                    <div class="media">
                        <div class="p-2 text-xs-center bg-primary bg-darken-2 media-left media-middle">
                          <i class="fa fa-calendar-plus-o font-large-2 white"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-primary white media-body">
                          <h5>Current Running Jobs</h5>
                          <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countcurrent_jobs}}</h5>
                        </div>
                    </div>
                  </div>
              </div>
            </div> 
            </a>
            <a href="{{route('manager::viewAllCompletedJobs')}}">
            <div class="col-xl-3 col-lg-6 col-xs-12">
              <div class="card">
                  <div class="card-body">
                    <div class="media">
                        <div class="p-2 text-xs-center bg-danger bg-darken-2 media-left media-middle">
                          <i class="fa fa-calendar-check-o font-large-2 white"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-danger white media-body">
                          <h5>All Completed Jobs</h5>
                          <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countCompletedJobs}}</h5>
                        </div>
                    </div>
                  </div>
              </div>
            </div>   
            </a>
            <a href="{{route('manager::viewAllProviderCanceledJobs')}}">            
            <div class="col-xl-3 col-lg-6 col-xs-12">
              <div class="card">
                  <div class="card-body">
                    <div class="media">
                        <div class="p-2 text-xs-center bg-warning bg-darken-2 media-left media-middle">
                          <i class="fa fa-calendar-times-o font-large-2 white"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-warning white media-body">
                          <h5>Provider Canceled Jobs</h5>
                          <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countP_CanceledJobs}}</h5>
                        </div>
                    </div>
                  </div>
              </div>
            </div> 
            </a>
            <a href="{{route('manager::viewAllCustomerCanceledJobs')}}"> 
            <div class="col-xl-3 col-lg-6 col-xs-12">
              <div class="card">
                  <div class="card-body">
                    <div class="media">
                        <div class="p-2 text-xs-center bg-success bg-darken-2 media-left media-middle">
                          <i class="fa fa-calendar-times-o font-large-2 white"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-success white media-body">
                          <h5>Customer Canceled Jobs</h5>
                          <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countC_CanceledJobs}}</h5>
                        </div>
                    </div>
                  </div>
              </div>
            </div>  
          </a>
      </div> 
    </div>
  </div>
</div>
@endsection