jQuery(document).ready(function($){    
    $(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.mos-sticky-header').addClass('tiny');
		} else {
			$('.mos-sticky-header').removeClass('tiny');
		}
	});    
    $('.jarallax').jarallax({
        speed: 0.2
    });
    $('.slick-slider').slick();
});