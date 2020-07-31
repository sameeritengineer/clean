<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <title>Login</title>
  <link rel="apple-touch-icon" href="{{asset('admin/app-assets/images/ico/apple-icon-120.png')}}">
  <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/app-assets/images/ico/apple-icon-120.png')}}">
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
                  <img src="{{asset('admin/app-assets/images/logo/Cleanerup.png')}}" alt="branding logo">
                </div>
                <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
                  <span>Login</span>
                </h6>
              </div>
              <div class="card-body collapse in">
                <div class="card-block">
                  <form class="form-horizontal" id="signinForm" action="{{ route('login') }}" method="post" novalidate>
					          @csrf
                    <div class="form-group">
                      <div class="controls">
                        <fieldset class="position-relative has-icon-left">
                          <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>

                           @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                          <div class="form-control-position"><i class="ft-user"></i></div>
                        </fieldset>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="controls">
                        <fieldset class="position-relative has-icon-left">
                         <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                          <div class="form-control-position"><i class="fa fa-key"></i></div>
                        </fieldset>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-block"><i class="ft-unlock"></i> Login</button>
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