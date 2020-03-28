<!DOCTYPE html>
<html>
<head>
  <title>::Cleanerup::</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="{{asset('front/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('front/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('front/bootstrap-grid.min.css')}}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<!-- Start Header -->
<header>
  <div class="container">
  <div class="row">
    <div class="col-lg-3 col-md-4">
      <div class="logo"><a href="{{route('home')}}"><img src="{{asset('front/images/logo.png')}}" class="logo"></a></div>
    </div>
    <!--Logo End-->
    <div class="col-lg-9 col-md-8">
      <div class="header_right">              
              <ul class="clean_header_info">
                <li> 
                  <span class="info_icon"> <img src="{{asset('front/images/map_icon.png')}}"> </span>
                  <span class="info_detail"> 
                  <h5>123 Willow Crossing Ct,<br/>Clifton, VA, 20124</h5>
                  </span>                 
                </li>  
                <li> 
                  <span class="info_icon"> <img src="{{asset('front/images/mail_icon.png')}}"> </span>
                  <span class="info_detail"> 
                  <h5 class="email">support@gmail.com</h5> 
                  </span>                 
                </li>                
                <li class="call-no"> 
                  <span class="info_detail "> <small>CALL US ON: </small>
                  <h5 class="phone">800-456-7891</h5> 
                  </span>                 
                </li>                
              </ul>
           </div>
        </div>
        <!--Header Right End-->   
  </div>  
  </div>
</header>
<!-- End Header -->
<!--Slider img Start-->
<div id="clean_slider" class="carousel slide" data-ride="carousel">
<!--Nav Start-->
<div class="nav-scroller">
<div class="container">
<div class="row">
<div class="col-md-8 float-left">
    <nav class="navbar navbar-expand-md">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsExample04">
        <ul class="navbar-nav mr-auto" id="addremoveactive">
        <li>
          <a href="{{route('home')}}"  class="{{ Request::path() == '/' ? 'active' : '' }}">HOME</a></li>
        <li>
          <a href="{{route('about_us')}}" class="{{ Request::path() == 'About_us' ? 'active' : '' }}">ABOUT US</a>
        </li>
        <li>
          <a href="{{route('Services')}}" class="{{ Request::path() == 'Services' ? 'active' : '' }}">SERVICES</a>
        </li>
        <li>
          <a href="{{route('Pricing')}}" class="{{ Request::path() == 'Pricing' ? 'active' : '' }}">PRICING</a>
        </li>
        <li>
          <a href="{{route('Contact')}}" class="{{ Request::path() == 'Contact' ? 'active' : '' }}">CONTACT</a>
        </li> 
        </ul>
      </div>
    </nav>
</div>
<div class="col-md-4 float-right head-right">
    <div id="google_translate_element">
    </div>
    <a class="quote" href="{{route('Login')}}">LOGIN</a>
</div>
</div>
</div>
</div>  
</div>




