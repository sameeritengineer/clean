@extends('admin.layouts.index')
@section('title','Notification')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    @if(Session::has('success'))

        <div class="alert alert-success">

            {{ Session::get('success') }}

            @php

            Session::forget('success');

            @endphp

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
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-xs-12 mb-2">
          <h3 class="content-header-title mb-0">Send Notification To Worker</h3>
        </div>  

          <div class="content-body">
           <section class="input-validation">
           <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body collapse in">
                  <div class="card-block">
                      @if ($message = Session::get('error'))
                  <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                      <strong>{{ $message }}</strong>
                  </div>
                  @endif
                     <form class="form form-horizontal form-bordered" action="{{route('sendNotificationToWorker')}}" method="post" enctype="multipart/form-data" novalidate>
                       @csrf
                      <div class="form-body">  
                        <h4 class="form-section"><i class="icon-clipboard4"></i> Requirements</h4>
                        <div class="form-group row">
                          <label class="col-md-3 label-control">Notification Title</label>
                          <div class="col-md-9">
                            <input type="text" class="form-control" placeholder="Notification title" name="Notification_title" required data-validation-required-message="Notification Title is required">
                            <div class="help-block"></div>
                          </div>
                        </div>
                        <div class="form-group row ">
                          <label class="col-md-3 label-control">Notification Body</label>
                          <div class="col-md-9">
                            <textarea  rows="5" class="form-control" name="Notification_body" required data-validation-required-message="Notification Body is required"></textarea>
                             <div class="help-block"></div>
                          </div>
                        </div>
                        <div class="form-group row last">
                          <label class="col-md-3 label-control">Send To</label>
                          <div class="col-md-9">
                            @if(isset($users) && count($users)>0)
                              <select class="select2 form-control" name="user_id[]" multiple="multiple">
                                @foreach($users as $user)
                                  <option value="{{$user->id}}">{{$user->first_name." ".$user->last_name}}</option>
                                @endforeach
                              </select>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Send</button>
                      </div>
                    </form>
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