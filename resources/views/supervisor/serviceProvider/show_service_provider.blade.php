@extends('admin.layouts.index')
@section('title','Service Providers')
@section('content')
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
       <div class="content-header-left col-md-6 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Service Provider</h3>
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb"></ol>
          </div>
        </div>
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
                    <div class="card">
                      @if($user->image != NULL)
                      <img class="card-img-top img-thumbnail" src="{{asset('profile/'.$user->image)}}" alt="{{$user->first_name.' '.$user->last_name}}">
                      @else
                      <img class="card-img-top" src="{{asset('profile/no-image-icon.png')}}" alt="{{$user->first_name.' '.$user->last_name}}">
                      @endif
                      <div class="card-body">

                        <p class="card-text"><strong>Name: </strong>{{$user->first_name.' '.$user->last_name}}</p>
                        <p class="card-text"><strong>Email: </strong>{{$user->email}}</p>
                        <p class="card-text"><strong>Mobile: </strong>{{$user->mobile ?? ""}}</p>
                        <p class="card-text"><strong>Address: </strong>{{$user->user_address->address ?? ""}}</p>
                        <p class="card-text"><strong>City: </strong>{{$user->user_address->city_name->name ?? ""}}</p>
                        <p class="card-text"><strong>State: </strong>{{$user->user_address->state_name->name ?? ""}}</p>
                        <p class="card-text"><strong>Zipcode: </strong>{{$user->user_address->zipcode_name->zipcode ?? ""}}</p>
                        <p class="card-text"><strong>Country: </strong>{{$user->user_address->country_name->name ?? ""}}</p>
                        <p class="card-text"><strong>Service: </strong>{{$user->services ?? ""}}</p>
                        <p class="card-text"><strong>Jobs Completed: </strong>{{$user->NoOfJobsCompleted}}</p>
                        <p class="card-text"><strong>Average Rating: </strong>{{$user->AverageRating}}</p>
                        @if(isset($user->comments) && count($user->comments)>0)
                        <p class="card-text"><strong>Admin Comment: </strong>
                          @foreach($user->comments as $comment)
                            <p>{{$comment->comments}}</p>
                          @endforeach                     
                        </p>
                        @endif
                        <p class="card-text"><strong>Add Comment: </strong></p>
                        <strong class="card-text text-danger show_error"></strong>
                        <p class="card-text">
                          <form method="post" action="{{route('admin_comments')}}" id="add_comments">
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <textarea class="form-control" name="comments"></textarea>
                            <input type="submit" value="Comment" class="btn btn-primary">
                          </form>
                        </p>
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
  $(document).ready(function(){
    $('#add_comments').submit(function(e)
    {
      e.preventDefault();
      var url = $(this).attr('action');
      var data = $(this).serialize();
      console.log(data)
      $.ajax
      ({
        type:'post',
        url:url,
        data:data,
        success:function(response)
        {
          console.log(response)
          if(response.status===true)
          {
            location.reload();
          }
          else
          {
            $('.show_error').html(response.message);
          }
        }
      })
    });
  });

</script>
@endsection