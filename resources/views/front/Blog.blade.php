@extends('front.index')
@section('title', 'Blog')
@section('content')
<!--Nav End-->
<div class="page-area">
            <div class="breadgram-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="breadgram">
                            <div class="section-headline white-headline text-center">
                            	<h3>Blog</h3>
                            </div>
                            <ul>
                                <li class="home-bread active"> <a href="index.html">Home</a> </li>
                                <li style="color: #fff">/</li>
                                <li> <a href="blog.html">Blog</a> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--Slider img End-->


<!-- service Section Start -->
<section class="blog padding_60">
<div class="container">	
<div class="row">
	<div class="about_heading text-center">
		<h2>Blog</h2>
	</div>
	<div class="col-lg-9 col-md-8">
		<div class="blog-1">
		<img src="images/service6.jpg" alt="">
		<ul class="date">
			<li>Oct 3,2018</li>
			<li><i class="fa fa-comment"></i><span>2</span></li>
		</ul>
		<h3>New Cleaning With Hydrogen Peroxide </h3>
		<h4>by <strong>admin</strong></h4>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
		<a class="read_post" href="single_blog.html">Read Post</a>
	 </div>
	 <div class="blog-1">
		<img src="images/service3.jpg" alt="">
		<ul class="date">
			<li>Oct 3,2018</li>
			<li><i class="fa fa-comment"></i><span>2</span></li>
		</ul>
		<h3>Window Cleaning </h3>
		<h4>by <strong>admin</strong></h4>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
		<a class="read_post" href="single_blog.html">Read Post</a>

	 </div>
	 <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item disabled">
      <a class="page-link" href="#" tabindex="-1"><i class="fa fa-angle-double-left"></i></a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#"><i class="fa fa-angle-double-right"></i></a>
    </li>
  </ul>
</nav>
	</div>
	<div class="col-lg-3 col-md-4">
		<h2>Post Category</h2>
		<ul class="blog_side">
			    <li><i class="fa fa-angle-right"></i><a href="#">  Clean </a></li>
                <li><i class="fa fa-angle-right"></i><a href="#"> Apartments</a></li>
    		    <li><i class="fa fa-angle-right"></i><a href="#"> Housing</a></li>
                <li><i class="fa fa-angle-right"></i><a href="#"> Links</a></li>
    			<li><i class="fa fa-angle-right"></i><a href="#">Carpet</a></li>
    			<li><i class="fa fa-angle-right"></i><a href="#">Commercial</a></li>
    			<li><i class="fa fa-angle-right"></i><a href="#">Window</a></li>
		</ul>
		<div class="popular">
				<h2>Popular tags</h2>
				<div class="cloud">
					<a href="#">Clean</a>
				</div>
				<div class="cloud">
					<a href="#">Window Cleaning</a>
				</div>
				<div class="cloud">
					<a href="#">House Keeping</a>
				</div>
		</div>
	</div>
</div>
</div>
</section>
<!-- Our Pricing Section End -->
@endsection