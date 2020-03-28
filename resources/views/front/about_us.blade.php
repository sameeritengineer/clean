@extends('front.index')
@section('title', 'About us')
@section('content')
<div class="page-area">
            <div class="breadgram-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="breadgram">
                            <div class="section-headline white-headline text-center">
                                <h3>About us</h3>
                            </div>
                            <ul>
                                <li class="home-bread active"> <a href="index.html">Home</a> </li>
                                <li style="color: #fff">/</li>
                                <li> <a href="about.html">About us</a> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--Slider img End-->
<!-- clean Section Start -->
<section class="about_section padding_60">
<div class="container">
<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
    <div class="about-image">
        <img src="{{asset('front/images/ab.jpg')}}" alt="">
    </div>
    </div>
	<div class="col-md-6 col-sm-6 col-xs-12">
	<div class="about-content">
	    <h3>Clean Home - Bringing Excellence in Residential &amp; Commercial cleaning services</h3>
	    <p>The phrasal sequence of the Lorem Ipsum text is now so widespread and commonplace that many DTP programmes can generate dummy text using the starting sequence "Lorem ipsum". Fortunately, the phrase 'Lorem Ipsum' is now recognized by electronic pre-press systems and, when found, an alarm can be raised.</p>
	    <div class="about-details text-center hidden-sm">
	        <div class="single-about">
            <div class="icon-title">
            	<a href="#"><img src="{{asset('front/images/gift.png')}}"></a>
                <h5>Certified company</h5>
            </div>
				<p>The phrasal sequence of the Lorem Ipsum text is now so widespread</p>
	        </div>
	        <div class="single-about">
	        <div class="icon-title">
				<a href="#"><img src="{{asset('front/images/thumb.png')}}"></a>
				<h5>Our experience</h5>
	            </div>
	            <p>The phrasal sequence of the Lorem Ipsum text is now so widespread</p>
	        </div>
	    	</div>
		</div>
	</div>
	</div>
</div>
</section>
<!-- about Section Start --> 
<div class="about-feature">
<div class="container">
<div class="about-feature">
<div class="row">
	<!-- Start column -->
	<div class="col-md-4 col-sm-4 col-xs-12">
	<div class="single-feature">
	    <div class="feature-icon">
	        <img src="{{asset('front/images/eye.png')}}">
	    </div>
	    <div class="feature-text">
	        <h4>Our <span class="color">Mission</span></h4>
	        <p>The phrasal sequence of the Lorem Ipsum text is now so widespread and commonplace.</p>
	    </div>
	</div>
	</div>
	<!-- Start column -->
	<div class="col-md-4 col-sm-4 col-xs-12">
	<div class="single-feature">
	    <div class="feature-icon">
	        <img src="{{asset('front/images/leaf.png')}}">
	    </div>
	    <div class="feature-text">
	        <h4>Our <span class="color">Vision</span></h4>
	        <p>The phrasal sequence of the Lorem Ipsum text is now so widespread and commonplace.</p>
	    </div>
	</div>
	</div>
	<!-- Start column -->
	<div class="col-md-4 col-sm-4 col-xs-12">
	<div class="single-feature">
	    <div class="feature-icon">
	        <img src="{{asset('front/images/diamond.png')}}">
	    </div>
	    <div class="feature-text">
	        <h4>Our <span class="color">Experience</span></h4>
	        <p>The phrasal sequence of the Lorem Ipsum text is now so widespread and commonplace.</p>
	    </div>
	</div>
	</div>
	<!-- End column -->
</div>
</div>
</div>
</div>
<!-- about Section End --> 
<!-- Our History Section Start -->
<section class="history_section padding_60">
<div class="container">	
<div class="row">
	<div class="about_heading text-center">
	<h2>Our History</h2>
	<p class="about_h_p">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
	</div>
<div class="our-history flex-box">
<div class="row">
	<div class="col-md-8">
	<div class="choose_us_left flat-white">
	<div class="choose_resons pb-30">
		<span class="circle text-center mr-10"><h5 class="years my-10 color-primary">1997</h5></span>
		<div class="choose_text">
			<h5 class="inner-title">The company was founded.</h5>
			<p>Penatibus ridiculus metus iaculis fermentum odio suspendisse. In auctor eros magnis non. Aliquet imperdiet potenti libero accumsan magna rhoncus mauris id facilisis imperdiet ornare fames quisque dignissim tellus aenean.</p>
		</div>
	</div>
	<div class="choose_resons pb-30">
		<span class="circle text-center mr-10"><h5 class="years my-10 color-primary">2007</h5></span>
		<div class="choose_text">
			<h5 class="inner-title">Become a top listed company.</h5>
			<p>Penatibus ridiculus metus iaculis fermentum odio suspendisse. In auctor eros magnis non. Aliquet imperdiet potenti libero accumsan magna rhoncus mauris id facilisis imperdiet ornare fames quisque dignissim tellus aenean.</p>
		</div>
	</div>
	<div class="choose_resons pb-30">
		<span class="circle text-center mr-10"><h5 class="years my-10 color-primary">2017</h5></span>
		<div class="choose_text">
			<h5 class="inner-title">We started working all over the state.</h5>
			<p>Penatibus ridiculus metus iaculis fermentum odio suspendisse. In auctor eros magnis non. Aliquet imperdiet potenti libero accumsan magna rhoncus mauris id facilisis imperdiet ornare fames quisque dignissim tellus aenean.</p>
		</div>
	</div>
	</div>
	</div>
	<div class="col-md-4">
		<div class="our_history_right"> <img src="{{asset('front/images/woman_2.png')}}" alt=""> </div>
	</div>
</div>
</div>

</div>
</div>
</section>
<!-- Our History Section End -->
<!-- Get Started Section Start -->
<section class="get_started">
<div class="container">
<div class="row">
	<div class="col-md-7 offset-md-1 float-right">
		<h1>Get Started with your free estimate</h1>
	</div>
	<div class="col-md-3 float-left">
		<button class="free">Get free Estimate</button>
	</div>
</div>
</div>
</section> 
<!-- Get Started Section End -->
<div class="clearfix"> </div>
<!-- Our Team Section Start -->  
<section class="our_team padding_60">
<div class="container">	
<div class="row">
	<div class="about_heading text-center">
		<h2>Our Team</h2>
		<p class="about_h_p">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
	</div>
<div class="team_section">
<div class="row">	
	<div class="col-md-3">
	<div class="expert_box text-center">
		<img src="{{asset('front/images/team1.jpg')}}">
		<h6>JACKOBS</h6>
		<p>Clean Expert</p>
		<p>Lorem ipsum dolor Fusce varius euismod lacus eget feugiat rorem ipsum..</p>
		<button class="expert_btn">view Detail</button>
	</div>	
	</div>
		<div class="col-md-3">
	<div class="expert_box text-center">
		<img src="{{asset('front/images/team2.jpg')}}">
		<h6>GANELIA</h6>
		<p>Clean Expert</p>
		<p>Lorem ipsum dolor Fusce varius euismod lacus eget feugiat rorem ipsum..</p>
		<button class="expert_btn">view Detail</button>
	</div>	
	</div>
		<div class="col-md-3">
	<div class="expert_box text-center">
		<img src="{{asset('front/images/team3.jpg')}}">
		<h6>POLWALL</h6>
		<p>Clean Expert</p>
		<p>Lorem ipsum dolor Fusce varius euismod lacus eget feugiat rorem ipsum..</p>
		<button class="expert_btn">view Detail</button>
	</div>	
	</div>
		<div class="col-md-3">
	<div class="expert_box text-center">
		<img src="{{asset('front/images/team4.jpg')}}">
		<h6>GANELIA</h6>
		<p>Clean Expert</p>
		<p>Lorem ipsum dolor Fusce varius euismod lacus eget feugiat rorem ipsum..</p>
		<button class="expert_btn">view Detail</button>
	</div>	
	</div>
</div>
</div>
</div>		
</section>
<!-- Our Team Section Start -->  
<!-- Testimonial Section Start -->       
<section class="testimonial text-center">
<h1>Our Clients Say</h1>
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
<!-- Testimonial Section Start -->  

@endsection