@extends('admin.layouts.index')
@section('title','Coupon Used')
@section('content')
<div class="app-content content container-fluid load">
  <div class="content-wrapper data">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-xs-12 mb-2"><h3 class="content-header-title mb-0">Applied Coupon</h3></div>  
      <div class="content-body">
        <section id="configuration">
          <div class="row">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-body collapse in">
                  <div class="card-block card-dashboard">  
                   <div class="table-responsive">                  
                    <table class="table table-striped table-bordered zero-configuration">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>User</th>
                          <th>Promo Name</th>
                          <th>Count</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                      	@foreach($coupons as $promo)
                        <tr>                  
                          <td>{{$loop->iteration}}</td>
                          <td>{{$promo->user->first_name." ".$promo->user->last_name}}</td>
                          <td>{{$promo->promo->promo_name}}</td>
                          <td>{{$promo->total_count}}</td>
                          <td>{{$promo->created_at->format('d M Y h:i A')}}</td>    
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