@extends('front.index')
@section('title', 'Home')
@section('content')
<!--Slider img Start-->
<div id="clean_slider" class="carousel slide" data-ride="carousel"> 
 <div class="carousel-inner">
  <div class="carousel-item active"> <img class="slider_img" src="{{asset('front/images/slider.png')}}" alt="First slide">
   <div class="container">
    <div class="row">
     <div class="carousel-caption">
      <h3>'Tired' <span>of all these</span></h3>
      <h1>Cleaning?</h1>
      <p>The <strong>Best Cleaning Website</strong> around you. The Best Cleaning Website around you.</p>
      <h2>Leave it to the Best <br/>
       <span>PROFESSIONALS </span></h2>
      <h4>CALL US @ <span>+333 3213 123 WE CLEAN</span></h4>
     </div>
    </div>
   </div>
  </div>
  <div class="carousel-item"> <img class="slider_img" src="{{asset('front/images/slider_1.png')}}" alt="Second slide">
   <div class="container">
    <div class="row">
     <div class="carousel-caption">
      <h3>'Tired' <span>of all these</span></h3>
      <h1>Cleaning?</h1>
      <p>The <strong>Best Cleaning Website</strong> around you. The Best Cleaning Website around you.</p>
      <h2>Leave it to the Best <br/>
       <span>PROFESSIONALS </span></h2>
      <h4>CALL US @ <span>+333 3213 123 WE CLEAN</span></h4>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
<!--Slider img End--> 

<section class="clean_service_section pt-5 bg-light mt-0 mb-0">
 <div class="container">
  <h2 class="main_heading">Cleaning Services That Shine</h2>
  <!-- image container starts -->
  <div class="w-100 pb-5 cleaner-image-section float-left align-content-center">
   <div class="position-relative"> 
        <img src="{{asset('front/images/Slider_xs.png')}}" alt="" class="img-fluid d-xs-block d-sm-none d-md-none d-lg-none first">
        <img src="{{asset('front/images/Slider_sm.png')}}" alt="" class="img-fluid d-xs-none d-sm-block d-md-none d-lg-none second">
        <img src="{{asset('front/images/Slider_lg.png')}}" alt="" class="img-fluid d-xs-none d-sm-none d-md-block d-lg-block third">    
   </div>
   <!-- image container finish --> 
  </div>
 </div>
</section>
<!-- clean section starts vishal --> 
<section class="Conscience_service_section padding_60">
 <div class="container">
  <div class="row">
   <div class="col-lg-8 offset-lg-4 col-md-8 offset-md-4">
    <h2 class="main_heading">Cleaning With a Conscience</h2>
    <h4>Professional Cleaning Services for Home and Office</h4>
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
    <ul>
     <li><a href="#">One-off, weekly visits</a></li>
     <li><a href="#">Vetted & background-checked cleaners</a></li>
     <li><a href="#">Keep the same cleaner for every visit</a></li>
     <li><a href="#">Book, manage & pay online</a></li>
    </ul>
   </div>
  </div>
 </div>
</section>
<!-- get started Section Start -->
<section class="get_started">
 <div class="container">
  <div class="row">
   <div class="col-md-7 offset-md-1 float-right">
    <h3 class="heading">Get Started with your free estimate</h3>
   </div>
   <div class="col-md-3 float-left">
    <button class="free">Get free Estimate</button>
   </div>
  </div>
 </div>
</section>
<section class="join text-center">
 <div class="container">
  <div class="row">
   <div class="google-app-store"> <img src="{{asset('front/images/middle_logo.png')}}" alt=""/>
    <h2>It's perfect time to join Cleanerup.co</h2>
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has <br/>
     simply dummy text of the printing and typesetting industry.</p>
    <div class="row no-margin">
     <div class="grn-btn text-center">
      <div class=" google float-left"></div>
      <div class="ios float-left"></div>
     </div>
    </div>
   </div>
   <div class="social-icons">
    <ul>
     <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
     <li><a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
     <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
     <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
    </ul>
   </div>
  </div>
 </div>
</section>
<section class="testimonial text-center">
 <h2 class="heading">Our Clients Say</h2>
 <div class="col-md-6 offset-md-3">
  <div id="clean_testimonail" class="carousel slide" data-ride="carousel"> 
   
   <!-- Indicators -->
   <ul class="carousel-indicators">
    <li data-target="#clean_testimonail" data-slide-to="0" class="active"></li>
    <li data-target="#clean_testimonail" data-slide-to="1"></li>
    <li data-target="#clean_testimonail" data-slide-to="2"></li>
   </ul>
   
   <!-- The slideshow -->
   <div class="carousel-inner">
    <div class="carousel-item active">
     <div class="testimonail_detail">
      <h5 class="clean_client_name">Johan Doe</h5>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
     </div>
    </div>
    <div class="carousel-item">
     <div class="testimonail_detail">
      <h5 class="clean_client_name">Johan Doe</h5>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
     </div>
    </div>
    <div class="carousel-item">
     <div class="testimonail_detail">
      <h5 class="clean_client_name">Johan Doe</h5>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
     </div>
    </div>
   </div>
  </div>
 </div>
</section>
@endsection
