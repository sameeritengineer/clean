<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <title>Register</title>
  <link rel="apple-touch-icon" href="{{asset('app-assets/images/ico/apple-icon-120.png')}}">
  <link rel="shortcut icon" type="image/x-icon" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/images/ico/favicon.ico">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
  rel="stylesheet">
  <!-- BEGIN VENDOR CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/fonts/feather/style.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/fonts/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/fonts/flag-icon-css/css/flag-icon.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/extensions/pace.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/forms/icheck/icheck.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/forms/icheck/custom.css')}}">
  <!-- END VENDOR CSS-->
  <!-- BEGIN STACK CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/bootstrap-extended.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/app.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/colors.min.css')}}">
  <!-- END STACK CSS-->
  <!-- BEGIN Page Level CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/pages/login-register.min.css')}}">
  <!-- END Page Level CSS-->
  <!-- BEGIN Custom CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/style.css')}}">
  <!-- END Custom CSS-->
  <style>
  .form-group.error .form-control
  {
    border: 1px solid red;
  }
  .form-group.error .help-block ul li
  {
    color:red;
  }
  .form-group.issue .form-control
  {
    border: 1px solid #FFA87D;
  }
  .form-group.issue .help-block ul li
  {
    color:#FFA87D;
  }
  </style>
</head>
<body data-open="click" data-menu="vertical-menu" data-col="1-column" class="vertical-layout vertical-menu 1-column bg-full-screen-image blank-page blank-page">
  <div class="app-content content container-fluid">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">
        <section class="flexbox-container">
          <div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1 box-shadow-3 p-0">
            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
              <div class="card-header no-border">
                <div class="card-title text-xs-center">
                  <img src="{{asset('admin/app-assets/images/logo/stack-logo-dark.png')}}" alt="branding logo">
                </div>
                <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
                  <span>Register</span>
                </h6>
              </div>
              <div class="card-body collapse in">
                <div class="card-block">
                  <form class="form-horizontal" action="{{route('postRegister')}}" method="post" novalidate>
					          @csrf
                    <div class="form-group">
                      <div class="controls">
                      <fieldset class="position-relative has-icon-left">                      
                        <input type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name') }}" name="first_name" placeholder="Your First Name" data-validation-regex-regex="^[a-zA-Z]+(\s[a-zA-Z]+)?$" required data-validation-regex-message="Only Alphabets and Space allwoed">
                        <div class="form-control-position"><i class="ft-user"></i></div>
                          @if ($errors->has('first_name'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                          @endif                                          
                      </fieldset>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="controls">
                        <fieldset class="position-relative has-icon-left">
                          <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" placeholder="Your Last Name" data-validation-regex-regex="^[a-zA-Z]+(\s[a-zA-Z]+)?$" required data-validation-regex-message="Only Alphabets and Space allwoed">
                          <div class="form-control-position"><i class="ft-user"></i></div>
                          @if ($errors->has('last_name'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                          @endif
                        </fieldset>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="controls">
                        <fieldset class="position-relative has-icon-left">
                          <input type="email"  class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"id="user-email" placeholder="Your Email Address" required>
                          <div class="form-control-position"><i class="ft-mail"></i></div>
                          @if ($errors->has('email'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                          @endif
                        </fieldset>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="controls">
                        <fieldset class="position-relative has-icon-left">
                          <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="user-password" name="password" placeholder="Enter Password" required>
                          <div class="form-control-position"><i class="fa fa-key"></i></div>
                          @if ($errors->has('password'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                          @endif
                        </fieldset>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="controls">
                        <fieldset class="position-relative has-icon-left">
                          <input type="password" class="form-control" id="user-password" name="password_confirmation" placeholder="Enter Confirm Password" required>
                          <div class="form-control-position"><i class="fa fa-key"></i></div>
                        </fieldset>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="controls">
                        <fieldset class="position-relative has-icon-left">
                          <!-- <input type="tel" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ old('mobile') }}"" id="mobile" placeholder="+99(99)9999-9999" data-validation-regex-regex="[\+]\d{2}[\(]\d{2}[\)]\d{4}[\-]\d{4}" required data-validation-regex-message="Not a valid mobile no"> -->
                           <input type="tel" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ old('mobile') }}"" id="mobile" placeholder="+99(99)9999-9999">
                          <div class="form-control-position"><i class="fa fa-mobile"></i></div>
                          @if ($errors->has('mobile'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('mobile') }}</strong>
                            </span>
                          @endif
                        </fieldset>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-block"><i class="ft-unlock"></i> Register</button>
                  </form>
                </div>           
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
  <!-- BEGIN VENDOR JS-->
  <script src="{{asset('admin/app-assets/vendors/js/vendors.min.js')}}" type="text/javascript"></script>
  <!-- BEGIN VENDOR JS-->
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{asset('admin/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}" type="text/javascript"></script>
  <script src="{{asset('admin/app-assets/vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN STACK JS-->
  <script src="{{asset('admin/app-assets/js/core/app-menu.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('admin/app-assets/js/core/app.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('admin/app-assets/js/scripts/customizer.min.js')}}" type="text/javascript"></script>
  <!-- END STACK JS-->
  <!-- BEGIN PAGE LEVEL JS-->
  <script src="{{asset('admin/app-assets/js/scripts/forms/form-login-register.min.js')}}" type="text/javascript"></script>
  <!-- END PAGE LEVEL JS-->
</body>
</html>