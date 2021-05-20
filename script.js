jQuery(document).ready(function($){    
    $(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.mos-sticky-header').addClass('tiny');
		} else {
			$('.mos-sticky-header').removeClass('tiny');
		}
	});  
    $('.slick-slider').slick();
    $('.counter').counterUp();

    $.fn.BeerSlider = function (options) {
        options = options || {};
        return this.each(function () {
            new BeerSlider(this, options);
        });
    };
    $(".beer-slider").each(function (index, el) {
        $(el).BeerSlider({
            start: $(el).data("start")
        })
    });
    
});