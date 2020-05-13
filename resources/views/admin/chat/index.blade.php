@extends('admin.layouts.index')
@section('title','chat')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">chat</h3>
        <div class="row breadcrumbs-top"><div class="breadcrumb-wrapper col-xs-12"><ol class="breadcrumb"></ol></div></div>
      </div>
    </div>
 
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
                      <table class="table table-striped table-bordered zero-configuration">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Provider</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>View</th>
                          </tr>
                        </thead>
                        <tbody>
						@foreach($chats as $chat)
                          <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$chat->provider->first_name}}</td>
                            <td>{{$chat->user->first_name." ".$chat->user->last_name}}</td>
                            <td>{{$chat->created_at->format('d M Y h:i A')}}</td>
                            <td><a href="{{route('showChat',['user_id'=>$chat->user_id,'provider_id'=>$chat->provider_id])}}" class="btn btn-primary">View</a></td>
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
@endsection
