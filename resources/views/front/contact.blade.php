@extends('front.index')
@section('title', 'contact')
@section('content')
<!--Nav End-->
<div class="page-area">
            <div class="breadgram-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="breadgram">
                            <div class="section-headline white-headline text-center">
                                <h3>Contact us</h3>
                            </div>
                            <ul>
                                <li class="home-bread active"> <a href="index.html">Home</a> </li>
                                <li style="color: #fff">/</li>
                                <li> <a href="contact.html">Contact us</a> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--Slider img End-->
<!-- Our Pricing Section Start -->
<section class="contact_section padding_60">
<div class="container">
<div class="row">
	<div class="about_heading text-center">
		<h2>Get in Touch</h2>
		<p class="about_h_p">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
	</div>
</div>
<div class="row icon_primary">
	<div class="col-md-3 col-sm-6">
		<div class="contact_info mb-30 color-gray text-center">
			<div class="contact_icon margin-auto circle text-center"><i class="fa fa-map-marker"></i></div>
			<h6 class="inner-title my-10">Contact Address</h6>
			<p>1707 Orlando Central Pkwy Ste 100, USA Orlando, FL</p>
		</div>
	</div>
	<div class="col-md-3 col-sm-6">
		<div class="contact_info mb-30 color-gray-a text-center">
			<div class="contact_icon margin-auto circle text-center"><i class="fa fa-mobile"></i></div>
			<h6 class="inner-title my-10">Phone Number</h6>
			<p> +(241) 123 4567,<br> +(291) 123 8167</p>
		</div>
	</div>
	<div class="col-md-3 col-sm-6">
		<div class="contact_info mb-30 color-gray-a text-center">
			<div class="contact_icon margin-auto circle text-center"><i class="fa fa-envelope"></i></div>
			<h6 class="inner-title my-10">Email Address</h6>
			<p> cleanall@domain.com <br> info@domain.com </p>
		</div>
	</div>
	<div class="col-md-3 col-sm-6">
		<div class="contact_info mb-30 color-gray-a text-center">
			<div class="contact_icon margin-auto circle text-center"><i class="fa fa-question"></i></div>
			<h6 class="inner-title my-10">24/7 Online Support</h6>
			<p> +(01) 423 801 3581 <br> Skype: vimrul.256</p>
		</div>
	</div>
</div>
</div>
</section>
<!-- Our contact Section End -->
<!-- Our Location Section Start -->
<section class="contact_location p-0">
<div class="container-fluid">
<div class="row">
	<div class="col-md-6">
	<div class="row">
	<div class="message">
		<h3 class="inner-title down_line white color-white">Send Message</h3>
		<div class="text-area color-white mb-30"><p>Et tellus mattis. Habitasse varius aliquam. Sociosqu tellus aptent enim vulputate interdum proin ut integer mauris. Bibendum aliquet convallis tempus.</p></div>
		<form id="contact-form" class="contact_message" action="email.php" method="post" novalidate="novalidate">
			<div class="row">
				<div class="form-group col-md-6 col-sm-6">
					<input class="form-control" id="name" type="text" name="name" placeholder="Name">
				</div>
				<div class="form-group col-md-6 col-sm-6">
					<input class="form-control valid" id="email" type="text" name="email" placeholder="Email">
				</div>
				<div class="form-group col-md-12 col-sm-12">
					<input class="form-control" id="subject" type="text" name="subject" placeholder="Subject">
				</div>
				<div class="form-group col-md-12 col-sm-12">
					<textarea class="form-control" id="message" name="message" placeholder="Message"></textarea>
				</div>
				<div class="form-group col-md-12 col-sm-6">
					<input class="btn btn-default my-15" id="send" type="submit" value="Send">
				</div>
			</div>
		</form>
	</div>
	</div>
	</div>
	<!-- Map Section Start -->
	<div class="col-md-6">
	<div class="row">
	<div class="cleaner_map">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d227748.3825624477!2d75.65046970649679!3d26.88544791796718!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396c4adf4c57e281%3A0xce1c63a0cf22e09!2sJaipur%2C+Rajasthan!5e0!3m2!1sen!2sin!4v1500819483219" style="border:0; width:100%; height:100%;" >		
		</iframe>					
	</div>
	</div>
	</div>
	<!-- Map Section End -->	
</div>
</div>
</section>
<!-- Our Location Section End -->  
@endsection