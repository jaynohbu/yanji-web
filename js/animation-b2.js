$(function() {
		   
 // HAMBURGER

	$('.hamburger,.nav li, .nav a,.overlay').click(function(){
		$('.hamburger').toggleClass('is-active');
		$('.overlay').toggleClass('overlay-on');
		$('.overlay-footer').toggleClass('overlay-footer-on');
		var navli = $('.nav li');
		if (navli.hasClass('nav-li-on'))
		{		
			navli.each(function(h) {
				var nav = $(this);	
				setTimeout(function(){
					nav.toggleClass('nav-li-on');
				}, 50*h);
			});
		} else {
			
			setTimeout(function(){	
				$(navli.get().reverse()).each(function(i) {
					var nav = $(this);	
					setTimeout(function(){
						nav.toggleClass('nav-li-on');
					}, 50*i);
				});
			}, 300);	
		} 
	});

// FADE OUT PAGES

	$(".fade-btn").click(function(event){	
        event.preventDefault();
        linkLocation = this.href;
		$("#loader").delay(600).fadeIn(400, redirectPage);
    });
    function redirectPage() {
        window.location = linkLocation;
    };
	
// IF INTERNET EXPLORER
		
if (/MSIE (\d+\.\d+);/.test(navigator.userAgent) || navigator.userAgent.indexOf("Trident/") > -1 ){ 
	$('#bg-home').css('animation','none !important');
}
  
	
 });

// INTRO

$(window).load(function() {

var click1 = $('.click');
// LOADER - disable to disable iphone
//	if ($(window).width() > 960) {
   		$(".loader").delay(400).fadeOut();
		$("#loader").delay(600).fadeOut(2000);
//	}	
	
	$('.logomain').delay(500).animate({opacity:1},{duration:1400});
	$('.logolow').delay(1000).animate({opacity:1,marginTop:"0px"}, 1000, "easeOutQuart");

});

// ANCHOR MENU

$(document).ready(function () {
    $(document).on("scroll", onScroll);
    
    //smoothscroll
    $('.pages').on('click', function (e) {
        e.preventDefault();
        $(document).off("scroll");
        
        $('.pages').each(function () {
            $(this).removeClass('list-active');
        })
        $(this).addClass('list-active');
      
        var target = this.hash,
            menu = target;
        $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 1000, 'easeInOutQuint', function () {
            window.location.hash = target;
            $(document).on("scroll", onScroll);
        });
    });
});

//ATOMATIC HIGHLIGHT TO ANCHOR MENU

function onScroll(event){
    var scrollPos = $(document).scrollTop();
    $('.pages').each(function () {
        var currLink = $(this);
        var refElement = $(currLink.attr("href"));
        if (refElement.position().top-55 <= scrollPos && refElement.position().top-55 + refElement.height()-55 > scrollPos) {
            $('.pages').removeClass("list-active");
            currLink.addClass("list-active");
        }
        else{
            currLink.removeClass("list-active");
        }
    });
}