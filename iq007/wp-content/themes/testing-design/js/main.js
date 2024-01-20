$(document).ready(function(){
	$('.slider').slick({
		nextArrow: '.slider-next-btn',
		prevArrow: '.slider-prev-btn',
		slidesToShow: 3,
		responsive: [
		{
			breakpoint: 992,
			settings: {
				slidesToShow: 2,
			}
		},
		{
			breakpoint: 576,
			settings: {
				slidesToShow: 1,
			}
		}
		]
	});
	$(function() {
		$('.slider .slick-slide').matchHeight();
	});
	$(function($){
		$('.request-tel').mask("+7(999) 999-9999");
	});
	document.addEventListener( 'wpcf7mailsent', function() {
		$("#modal-form").modal("hide");
		$("#modal-form-success").modal("show");
	}, false );
	$('.header__burger').click(function(event) {
		$('.header__burger,.mob-menu').toggleClass('active');
		$('body').toggleClass('lock');
	})
	if (window.outerWidth < 992) {
		$('.menu li').click(function(event) {
			$('.header__burger,.mob-menu').toggleClass('active');
			$('body').toggleClass('lock');
		})
	}
	window.addEventListener('resize', function(event) {
		if (window.outerWidth > 991) {
			$('.header__burger,.mob-menu').removeClass('active');
			$('body').removeClass('lock');
		}
		if (window.outerWidth < 992) {
			$('.menu li a').click(function(event) {
				$('.header__burger,.mob-menu').toggleClass('active');
				$('body').toggleClass('lock');
			})
		}
	}, true);
});