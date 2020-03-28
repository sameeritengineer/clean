@extends('admin.layouts.index')
@section('title','User')
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
      <div class="content-header-left col-md-11 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0">Add User</h3>
      </div>
      <div class="content-header-right col-md-1 col-xs-12 mb-2">
        <h3 class="content-header-title mb-0 text-right"><a href="" class="btn btn-primary">Back</a></h3>
      </div>
    </div>
    <div class="content-body">
      <section class="input-validation">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body collapse in">
                <div class="card-block ">
                  @if ($message = Session::get('error'))
                  <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                      <strong>{{ $message }}</strong>
                  </div>
                  @endif
                  <form class="form-horizontal" action="{{route('saveUser')}}" method="post" enctype="multipart/form-data" novalidate>
                  @csrf
                    <div class="row">
                      <div class="col-md-12">    
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label>First Name</label>
                            <div class="controls">              
                              <input type="text" name="first_name" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed" placeholder="Enter First Name" class="form-control" required data-validation-required-message="First Name is required">
                            </div>
                          </div>  
                          <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <div class="controls">
                              <input type="text" name="last_name" data-validation-regex-regex="[a-zA-Z][a-zA-Z ]+[a-zA-Z]$" data-validation-regex-message="Only Alphabets and Space allwoed" placeholder="Enter Last Name" class="form-control" required data-validation-required-message="last Name is required">
                            </div>
                          </div>
                        </div>
                       
                        <div class="row"> 
                         <div class="form-group col-md-6">
                            <label>Email</label>
                            <div class="controls">              
                             <input name="email" class="form-control" required="" placeholder="Email" data-validation-required-message="Email is required" aria-invalid="false" type="email">
                            </div>
                          </div>   
                         <div class="form-group col-md-6">
                            <label>password</label>
                            <div class="controls">
                              <input name="password"  type="password" class="form-control" required="" data-validation-required-message="password is required" aria-invalid="false" placeholder="Enter Mobile Number">
                            </div>
                          </div>                     
                         
                          
                        </div>
                        <div class="row">
                         <div class="form-group col-md-6">
                            <label>Select Role</label>
                            <div class="controls">
                              <select id="select" name="role" required class="form-control">
                              <option value="">Select Role</option> 
                              @php $roles = App\Role::orderBy('id','desc')->get();@endphp
                              @foreach($roles as $role)   
                               <option value="{{$role->id}}">{{$role->name}}</option>
                              @endforeach            
                              </select>
                            </div>
                          </div> 
                          <div class="form-group col-md-6">
                            <label>Contact No</label>
                            <div class="controls">
                              <input name="mobile"  type="number" class="form-control" required="" data-validation-required-message="Mobile Number is required" aria-invalid="false" placeholder="Enter Mobile Number">
                            </div>
                          </div>  
                        </div>
                      
                        
                        <div class="text-xs-right">
                          <button type="submit" class="btn btn-success">Submit <i class="fa fa-thumbs-o-up position-right"></i></button>
                          <button type="reset" class="btn btn-danger" onClick="window.location.reload()">Reset <i class="fa fa-refresh position-right"></i></button>
                        </div>
                      </div>
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
 
@endsection