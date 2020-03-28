@extends('admin.layouts.index')
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
            <a href="{{route('service')}}">
            <div class="col-xl-3 col-lg-6 col-xs-12">
            		<div class="card">
              			<div class="card-body">
                			<div class="media">
                  				<div class="p-2 text-xs-center bg-primary bg-darken-2 media-left media-middle">
                    				<i class="icon-grid font-large-2 white"></i>
                  				</div>
                  				<div class="p-2 bg-gradient-x-primary white media-body">
                    				<h5>Services</h5>
                    				<h5 class="text-bold-400"><i class="ft-plus"></i>{{$countservice}}</h5>
                  				</div>
               				</div>
              			</div>
            		</div>
          		</div> 
              </a>
              <a href="{{route('serviceType')}}">
              <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                      <div class="media">
                          <div class="p-2 text-xs-center bg-danger bg-darken-2 media-left media-middle">
                            <i class="icon-list font-large-2 white"></i>
                          </div>
                          <div class="p-2 bg-gradient-x-danger white media-body">
                            <h5>Service type</h5>
                            <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countServicetype}}</h5>
                          </div>
                      </div>
                    </div>
                </div>
              </div>   
              </a>
              <a href="{{route('viewAllServiceProvider')}}">        		
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
              <a href="{{route('unApprovedServiceProvider')}}"> 
              <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                      <div class="media">
                          <div class="p-2 text-xs-center bg-success bg-darken-2 media-left media-middle">
                            <i class="icon-user-unfollow font-large-2 white"></i>
                          </div>
                          <div class="p-2 bg-gradient-x-success white media-body">
                            <h5>Un-Approved registration</h5>
                            <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countallunapproved}}</h5>
                          </div>
                      </div>
                    </div>
                </div>
              </div>  
              </a>
              <a href="{{route('unApprovedProfileProvider')}}">
              <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                      <div class="media">
                          <div class="p-2 text-xs-center bg-success bg-darken-2 media-left media-middle">
                            <i class="icon-picture font-large-2 white"></i>
                          </div>
                          <div class="p-2 bg-gradient-x-success white media-body">
                            <h5>Un-Approved Pics</h5>
                            <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countallunapprovedprofile}}</h5>
                          </div>
                      </div>
                    </div>
                </div>
              </div> 
            </a>
            <a href="{{route('viewAllDeclineProvider')}}">
             <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                      <div class="media">
                          <div class="p-2 text-xs-center bg-warning bg-darken-2 media-left media-middle">
                            <i class="icon-user-unfollow font-large-2 white"></i>
                          </div>
                          <div class="p-2 bg-gradient-x-warning white media-body">
                            <h5>Decline Providers</h5>
                            <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countdeclinedProvider}}</h5>
                          </div>
                      </div>
                    </div>
                </div>
              </div> 
            </a>
            <a href="{{route('unApprovedBioProvider')}}">
             <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                      <div class="media">
                          <div class="p-2 text-xs-center bg-danger bg-darken-2 media-left media-middle">
                            <i class="icon-speech font-large-2 white"></i>
                          </div>
                          <div class="p-2 bg-gradient-x-danger white media-body">
                            <h5>Un-Approved Bio</h5>
                            <h5 class="text-bold-400"><i class="ft-plus"></i>{{$countallunapprovedbio}}</h5>
                          </div>
                      </div>
                    </div>
                </div>
              </div> 
            </a>
            <a href="{{route('OpenOpportunity')}}">
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
            <a href="{{route('viewAllCurrentRunningJobs')}}">
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
              <a href="{{route('viewAllCompletedJobs')}}">
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
              <a href="{{route('viewAllProviderCanceledJobs')}}">            
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
              <a href="{{route('viewAllCustomerCanceledJobs')}}"> 
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
        <div class="row match-height">
          <div class="col-xl-8 col-lg-12">
            <div class="card" style="height: 365px;">
              <div class="card-header">
                <h4 class="card-title">All jobs Status</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div style="padding: 0.4rem 1.5rem;">
                  <p>Total completed jobs {{$countCompletedJobs}}, Current jobs {{$countcurrent_jobs}}.</p>
                </div>
                <div class="myautoscroll">
                    <table id="recent-orders" class="table mb-0 table-striped">
                    <!-- <table class="table table-striped zero-configuration"> -->
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Job ID</th>
                        <th>Customer Name</th>
                        <th>Status</th> 
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($AllJobsLists as $AllJobsList)
                      <tr>
                        <td class="text-truncate">{{$loop->iteration}}</td>
                        <td class="text-truncate">CU-{{$AllJobsList->job_id}}</td>
                        <td class="text-truncate">{{$AllJobsList->customer_name}}</td>
                        @if($AllJobsList->status == 0)
                        <td class="text-truncate"><span class="tag tag-default tag-primary">Running</span></td>
                        @elseif($AllJobsList->status == 1)
                        <td class="text-truncate"><span class="tag tag-default tag-success">Completed</span></td>
                        @elseif($AllJobsList->status == 2 || $AllJobsList->status == 3)
                        <td class="text-truncate"><span class="tag tag-default tag-danger">Cancelled</span></td>
                        @endif
                      </tr>
                       @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
         <div class="col-xl-4 col-lg-12">
            <div class="card" style="height: 365px;">
              <div class="card-header">
                <h4 class="card-title">Top Service Providers</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body px-1">
                <div id="recent-buyers" class="list-group height-300 position-relative ps-container ps-theme-default ps-active-y" data-ps-id="c36d3f93-9645-1b8c-ca58-2262d74d1746">
                   @foreach($RecentCleaners as $cleaner)
                  <a href="#" class="list-group-item list-group-item-action media no-border">
                    <div class="media-left">
                      <span class="avatar avatar-md avatar-online">
                         @if($cleaner->image != null)
                        <img class="media-object rounded-circle" src="{{asset('profile/'.$cleaner->image)}}" alt="Generic placeholder image">
                         @else
                        <img class="media-object rounded-circle" src="{{asset('admin/app-assets/images/portrait/small/avatar-s-7.png')}}" alt="Generic placeholder image">
                        @endif 
                        <i></i>
                      </span>
                    </div>
                    <div class="media-body">
                      <h6 class="list-group-item-heading">{{$cleaner->first_name}}
                        <span class="float-xs-right pt-1">
                          <p style="padding-right: 5px;padding-top: 5px;margin: 0px;font-size: 14px;float: left;">{{$cleaner->Avg_Rating}} 
                          @php $rating = $cleaner->Avg_Rating; @endphp
                          <ul style="list-style: none;padding: 0px;margin: 0px;float: right;">
                            @while($rating>0)
                                  @if($rating >0.5)
                                     <li style="float: left; padding-right: 2px;"><span class="fa fa-star checked" style="color: #f94419;font-size: 14px;"></span></li>
                                  @else
                                     <li style="float: left; padding-right: 2px;"><span class="fa fa-star-half-o" style="color: #f94419;font-size: 14px;"></span></li>
                                  @endif
                                  @php $rating--; @endphp
                              @endwhile 
                            </ul></p>
                        </span>
                      </h6>
                      <p class="list-group-item-text">
                        <span class="tag tag-primary">Bedroom</span>
                        <span class="tag tag-danger">Kitchen </span>
                        <span class="tag tag-warning">Bathroom</span>
                      </p>
                    </div>
                  </a>
                  @endforeach
                <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;">
                  <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps-scrollbar-y-rail" style="top: 0px; height: 300px; right: 3px;">
                  <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 202px;"></div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div> 
	</div>
	</div>
</div>
@endsection