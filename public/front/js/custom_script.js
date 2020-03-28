// widnow on scroll add class
$(window).scroll(function(){
    if ($(window).scrollTop() >= 300) 
    {
        $('.nav-scroller').addClass('fixed-header');
    }
    else 
    {
        $('.nav-scroller').removeClass('fixed-header');
    }
});
	//end script
//onclcik scrool top
$(document).ready(function(){
	//Check to see if the window is top if not then display button
	$(window).scroll(function()
	{
		if ($(this).scrollTop() > 300){
			$('#scrollup').fadeIn();
		} else {
			$('#scrollup').fadeOut();
		}
	});
	//Click event to scroll to top
	$('#scrollup').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
});

$(document).ready(function(){
  //testimonail slider
  $('#cysure_testimonail').carousel({
    pause: true,
    interval: 4000,
  });
});