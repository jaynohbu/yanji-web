$(function() {	
  
	var bod = $('body');
	var fullpage = $('#fullpage');
	var nextt = $('.next');
	var logolow = $('.logolow');
	var logomain = $('.logomain');
	
	var s2t = $('#section2 .title');
	var s2tsub = $('#section2 .title-sub');
	var s2ttext = $('#section2 .title-text');
	var s2colright = $('#section2 .col-right');
	var s2aboutoutline = $('#section2 .about-outline');
	var click2 = $('.click');
	var menucover = $('.menu-cover');
	var menuoutline = $('.menu-outline');
	var s1 = $('.s1');
	var s2 = $('.s2');
	var s3 = $('.s3');
	var s4 = $('.s4');
	var s5 = $('.s5');
	var s6 = $('.s6');
	
	var s5t = $('#section5 .title');
	var s5tsub = $('#section5 .title-sub');
	var s5ttext = $('#section5 .title-text');
	var s5colright = $('#section5 .col-right');
	var s5aboutoutline = $('#section5 .about-outline');
	
	bod.css("display","none");
    bod.fadeIn({duration:400,queue:false});
	
	function homestick() {	
		s3.stop().css('left','-100px').css('top','370px');
		s4.stop().css('left','-110px').css('top','390px');
   	 	s3.delay(2000).animate({left:'100px',top:'170px',opacity:0.6}, 900, "easeOutQuart");
		s4.delay(2100).animate({left:'110px',top:'190px',opacity:0.6}, 700, "easeOutQuart");
		s5.stop().css('right','-100px').css('top','390px');
		s6.stop().css('right','-110px').css('top','410px');
   	 	s5.delay(2300).animate({right:'100px',top:'190px',opacity:0.6}, 900, "easeOutQuart");
		s6.delay(2400).animate({right:'110px',top:'210px',opacity:0.6}, 700, "easeOutQuart");
	};
	homestick();
	
	function menuwhite() {	
   	 	$('.hamburger-inner-b,.hamburger-inner,.hamburger-inner-a').css('background-color','white');
		$('.hamburger-menu').css('color','white');
	};
	function menublack() {	
   	 	$('.hamburger-inner-b,.hamburger-inner,.hamburger-inner-a').css('background-color','black');
		$('.hamburger-menu').css('color','black');
	};
	
	fullpage.fullpage({
		anchors: ['home','about','menu','bookatable','contacts'],
		css3:true,
		easing:'easeInOutCubic',
		scrollingSpeed:1100,
		scrollBar:true,
		menu:'.nav',
		responsiveWidth:1024,
		onLeave: function(index, nextIndex, direction){
			if(nextIndex == 1){ //home
				menuwhite();
				homestick();
				logomain.stop().css('opacity','0');
				logolow.stop().css('margin-top','110px').css('opacity','0');
				logomain.delay(300).animate({opacity:1},{duration:1000});
				logolow.delay(400).animate({opacity:1,marginTop:"0px"}, 1000, "easeOutQuint");
			}
			if(index == 1){ //home
				s3.animate({left:'-100px',top:'370px',opacity:0}, 900, "easeOutQuint");
				s4.animate({left:'-110px',top:'390px',opacity:0}, 700, "easeOutQuint");
				s5.animate({right:'-100px',top:'390px',opacity:0}, 900, "easeOutQuint");
				s6.animate({right:'-110px',top:'410px',opacity:0}, 700, "easeOutQuint");
			}
			if(nextIndex == 2){ //about
				menublack();
				s2t.delay(600).animate({marginRight:'0px',opacity:1}, 1000, "easeOutQuint");
				s2tsub.delay(600).animate({opacity:1},{duration:300});
				s2ttext.delay(800).animate({opacity:1}, 300);
				
				s2colright.stop().css('right','-20%').css('opacity','0');
				s2aboutoutline.stop().css('margin-top','0').css('margin-left','0');
				s2colright.animate({right:'0px',opacity:1}, 2000, "easeInOutExpo");
				s2aboutoutline.delay(100).animate({marginTop:'35px',marginLeft:'20px'}, 1000, "easeOutQuint");
				
			}
			if(index == 2){ //about
				s2t.animate({marginRight:'30px',opacity:0}, 300);
				s2tsub.animate({opacity:0},{duration:300});
				s2ttext.animate({opacity:0}, 300);
				
				s2colright.animate({right:'-20%',opacity:0}, 2000, "easeOutExpo");
				s2aboutoutline.animate({marginTop:0,marginLeft:0}, 700, "easeOutQuint");
			}
			if(nextIndex == 3){ //menu
				menuwhite();
				menucover.stop().css('margin-left','25%').css('opacity','0');
				menuoutline.stop().css('top','40px').css('left','45px');
				menucover.delay(600).animate({marginLeft:'15%',opacity:1}, 1000, "easeOutQuint");
				menuoutline.delay(800).animate({top:'-20px',left:'-25px',opacity:1}, 1000, "easeOutQuint");
			}
			if(index == 3){ //menu
				menucover.animate({marginLeft:'35%',opacity:0}, 800, "easeOutQuint");
				menuoutline.animate({top:'40px',left:'45px',opacity:0}, 700, "easeOutQuint");
			}
			if(nextIndex == 4){ //book
				menublack();
				s1.stop().css('left','-100px').css('top','200px').css('opacity','0');
				s2.stop().css('left','-110px').css('top','400px').css('opacity','0');
				s1.delay(900).animate({left:'100px',top:'240px',opacity:1}, 900, "easeOutQuint");
				s2.delay(1000).animate({left:'110px',top:'250px',opacity:1}, 700, "easeOutQuint");
			}
			if(index == 4){ //book
				s1.animate({left:'-100px',top:'200px',opacity:0}, 900, "easeOutQuint");
				s2.animate({left:'-110px',top:'300px',opacity:0}, 700, "easeOutQuint");
			}
			if(nextIndex == 5){ //contacts
				menublack();
			}
		},
		
	});
	
	$.fn.fullpage.setAllowScrolling(false);

});
