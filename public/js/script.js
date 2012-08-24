$(function() {

	//homepage js
	if($("body.homepage").length !=0){
		//news, get started, get involved tabs
		$(".content-tabs div").hide();
		$($(".news-nav li.active a").attr("href")).show();
	
		//on click fade in - fade out
		var tabbing = $('.news-nav li a').bind('click', function(e) {
		//var tabbing = $(".news-nav li a").click(function(e){
			e.preventDefault();
			var li_element = $(this).parent();
			var tab = $(this).attr("href");	
			$(".news-nav li").removeClass("active");	
			$(li_element).addClass("active");
			$(".content-tabs div").hide();
			$(tab).fadeIn(700);
		});
			
			
		//scroooollllolol
		$('.scrollpane').jScrollPane();	
	
		//back to top
		$("#to_top").click(function(e){
			e.preventDefault();
			$('html').animate({
					scrollTop: 0
				}, 400);
		});
	}
	
	$(".overview ul li a").click(function(e){
		e.preventDefault();
		$('.overview ul li').removeClass('active');
		$(this).parent().addClass('active').find('ul').slideToggle('500');
		if($(this).parent().find('ul').is(':visible')){
			$(this).parent().toggleClass('opened');
		}
	});
});
