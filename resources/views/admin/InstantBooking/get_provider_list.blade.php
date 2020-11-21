
<style type="text/css">
  p.after-btn {
    font-size: 14px;
    padding: 20px 0px;
}
h2.title-h2-un {
    background: #f2f2f2;
    font-weight: 600;
    color: #080808;
    padding: 6px 20px;
    margin-top: 50px;
}
.custom-control.custom-checkbox label:after {
    content: "";
    position: absolute;
    display: none;
}
.custom-control.custom-checkbox label:after {
    top: 3px;
    left: 3px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #5ac5ee;
}
.custom-control.custom-checkbox input:checked ~ label:after {
    display: block;
}
.custom-control.custom-checkbox label {
    position: absolute;
    top: 0;
    left: 5px;
    height: 25px;
    width: 25px;
    background-color: #e0e0e0;
    border-radius: 50%;
    border: 2px solid #5ac5ee;
}
.unfilled-table .custom-control.custom-checkbox {
    position: relative;
    display: block;
}
.custom-control.custom-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
    left: 9px;
    width: 20px;
    height: 23px;
    border-radius: 50%;
}
</style>
<div class="unfilled_jobs-wrapper">
      <form method="post" action="{{route('job_assigned_by_admin_provider_noti')}}" id="send_notification">@csrf
      <input type="hidden" name="job_id" value="{{$data['id']}}">
      <div class="row card">
        <div class="col-md-12">
          <h2 class="title-h2-un">Job</h2>
          <table class="table table-striped">
            <thead>
              <th>Job ID</th>
              <th>Customer Name</th>
              <th>Customer Address</th>
              <th>Zipcode</th>
              <th>Booking Date</th>
              <th>Booking Time</th>
              <th>Service</th>
            </thead>
            <tbody>
              <tr><td>{{$data['id']}}</td>
              <td>{{$data['cust_firstname']}}</td>
              <td>{{$data['customer_address']}}</td>
              <td>{{$data['zipcode']}}</td>
              <td>{{$data['date']}}</td>
              <td>{{$data['time']}}</td>
              <td>{{$data['Services_names']}}</td></tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-12">
          <div class="unfilled-table">
            <h2 class="title-h2-un">Active</h2>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Score</th>
                  <th>Total Reviews</th>
                  <th>Completed Jobs</th>
                  <th>Name</th>
                  <th>Distance</th>
                  <th>Phone Number</th>
                </tr>
              </thead>
              <tbody>
                @if(count($active_users)>0)
                @foreach($active_users as $user)
                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input type="radio" id="checkbox_label" name="provider_id[]" value="{{$user->id}}">
                        <label for="checkbox_label"></label>
                      </div>
                    </td>
                    <td>{{$user->score}}</td>
                    <td>{{$user->AverageRating}}</td>
                    <td>{{$user->NoOfJobsCompleted}}</td>
                    <td>{{$user->first_name." ".$user->last_name}}</td>
                    <td>{{number_format($user->distance,2)}} km</td>
                    <td>{{$user->mobile}}</td>
                  </tr>
                @endforeach
                @else
                <tr><td colspan="5" align="center">No active list found.</td></tr>
                @endif
              </tbody>
            </table>
            @if((count($active_users)>0))
            <div class="col-md-12"><textarea name="active_message"></textarea></div>
            <div class="col-md-12"><button class="btn btn-primary sendtextbtn-un" type="submit">Send Notification</button></div>
            @endif
            <h2 class="title-h2-un">Unavailable</h2>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Score</th>
                  <th>Total Reviews</th>
                  <th>Completed Jobs</th>
                  <th>Name</th>
                  <th>Distance</th>
                  <th>Phone Number</th>
                </tr>
              </thead>
              <tbody>
                @if(count($unaviable_users)>0)
                @foreach($unaviable_users as $user)
                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input type="radio" id="checkbox_label" name="provider_id[]" value="{{$user->id}}">
                        <label for="checkbox_label"></label>
                      </div>
                    </td>
                    <td>{{$user->score}}</td>
                    <td>{{$user->AverageRating}}</td>
                    <td>{{$user->NoOfJobsCompleted}}</td>
                    <td>{{$user->first_name." ".$user->last_name}}</td>
                    <td>{{$user->distance}} km</td>
                    <td>{{$user->mobile}}</td>
                  </tr>
                @endforeach
                @else
                <tr><td colspan="5" align="center">No unavailable list found.</td></tr>
                @endif
              </tbody>
            </table>
            @if((count($unaviable_users)>0))
            <div class="col-md-12"><textarea name="unavailable_message"></textarea></div>
            <div class="col-md-12"><button class="btn btn-primary sendtextbtn-un" type="submit">Send Notification</button></div>
            @endif
            <h2 class="title-h2-un">Near By</h2>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Score</th>
                  <th>Total Reviews</th>
                  <th>Completed Jobs</th>
                  <th>Name</th>
                  <th>Distance</th>
                  <th>Phone Number</th>
                </tr>
              </thead>
              <tbody>
                @if(count($nearby)>0)
                @foreach($nearby as $user)
                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input type="radio" id="checkbox_label" name="provider_id[]" value="{{$user->id}}">
                        <label for="checkbox_label"></label>
                      </div>
                    </td>
                    <td>{{$user->score}}</td>
                    <td>{{$user->AverageRating}}</td>
                    <td>{{$user->NoOfJobsCompleted}}</td>
                    <td>{{$user->first_name." ".$user->last_name}}</td>
                    <td>{{number_format($user->distance,2)}} km</td>
                    <td>{{$user->mobile}}</td>
                  </tr>
                @endforeach
                @else
                <tr><td colspan="5" align="center">No nearby list found.</td></tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
        @if((count($nearby)>0))
        <div class="col-md-12"><textarea name="nearby_message"></textarea></div>
        <div class="col-md-12"><button class="btn btn-primary sendtextbtn-un" type="submit">Send Notification</button></div>
        @endif
        <div class="col-md-12"></div>
      </div>
      </form>

</div>
<script type="text/javascript">
  $('#send_notification').submit(function(e)
  {
    e.preventDefault();
    var action = $(this).attr('action');
    var form = $(this)[0];
    var formData = new FormData(form);
    $.ajax
    ({
      type: "post",   
      url: action,             
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success:function(data)
      {
        console.log(data)
        if(data == "success")
        {
          toastr.success("You'r successfully sent notification", "Great !");
          var url = $(location).attr('href');
          $('.load').load(url+ ' .data');
          location.reload();
        }
        else
        {
          toastr.error("Error Occur, Try Again", "Oops !");
        }
      },
    })
  })
</script>
