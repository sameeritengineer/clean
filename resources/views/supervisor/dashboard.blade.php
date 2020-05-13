@extends('supervisor.layouts.index')
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
        <a href="{{route('supervisior::employees')}}">
          <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card">
              <div class="card-body">
                <div class="media">
                  <div class="p-2 text-xs-center bg-primary bg-darken-2 media-left media-middle"><i class="icon-grid font-large-2 white"></i></div>
                  <div class="p-2 bg-gradient-x-primary white media-body">
                    <h5>Providers</h5>
                    <h5 class="text-bold-400"><i class="ft-plus"></i>{{$providers}}</h5>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        </a>
        <a href="{{route('supervisior::approve_pictures')}}">
          <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card">
              <div class="card-body">
                <div class="media">
                  <div class="p-2 text-xs-center bg-danger bg-darken-2 media-left media-middle"><i class="icon-grid font-large-2 white"></i></div>
                  <div class="p-2 bg-gradient-x-danger white media-body">
                    <h5>Approved Provider Profile</h5>
                    <h5 class="text-bold-400"><i class="ft-plus"></i>{{$approved_profiles}}</h5>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        </a>
        <a href="{{route('supervisior::declined_pic')}}">
          <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card">
              <div class="card-body">
                <div class="media">
                  <div class="p-2 text-xs-center bg-warning bg-darken-2 media-left media-middle"><i class="icon-grid font-large-2 white"></i></div>
                  <div class="p-2 bg-gradient-x-warning white media-body">
                    <h5>Declined Provider Profile</h5>
                    <h5 class="text-bold-400"><i class="ft-plus"></i>{{$declined_profiles}}</h5>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        </a>
        <a href="{{route('supervisior::approve_bio')}}">
          <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card">
              <div class="card-body">
                <div class="media">
                  <div class="p-2 text-xs-center bg-info bg-darken-2 media-left media-middle"><i class="icon-grid font-large-2 white"></i></div>
                  <div class="p-2 bg-gradient-x-info white media-body">
                    <h5>Provider Bio</h5>
                    <h5 class="text-bold-400"><i class="ft-plus"></i>{{$bios}}</h5>
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