(function ($, root, undefined) {
	
	$(function () {
		
		$(document).ready(function(){

			/*function handleAccordion() {
				if ($(window).width() < 767) {
					$('.mob-accordion').off('click').on('click', function () {
						$(this).siblings().removeClass('accordion-opened');
						$(this).parent().siblings().find('.mob-accordion').removeClass('accordion-opened');
						$(this).parent().siblings().find('.accordion-content').slideUp();
						$(this).siblings().find('.accordion-content').slideUp();
						$(this).toggleClass('accordion-opened');
						$(this).find('.accordion-content').slideToggle();
					});
				} else {
					$('.mob-accordion').off('click');
				}
			}

			// Run on page load
			handleAccordion();*/

			// Run on window resize
			/*$(window).resize(function () {
				handleAccordion();
			});*/
			

			$('.scroll-down .box, .scroll-down img').click(function(e){

			        // Find the next section
			        var currentSection = $(this).parent().parent().parent();
			        var nextSection = currentSection.next();

			            $('html, body').animate({
			                scrollTop: $('.next-section').offset().top
			            }, 500); // Adjust the duration as needed
			        
			});


			$('.contact-column textarea').attr('rows', '3');

			$('textarea').on('change keyup paste input', function() {
				$(this).css('height', 'auto'); // Reset the height
				$(this).css('height', this.scrollHeight + 'px'); // Set the height to the scroll height
				this.style.setProperty('height', this.scrollHeight + 'px', 'important');
			});

			/*------------------------------------*\
   				 TABS
			\*------------------------------------*/
			$('.tabs p:first-child, .tab-content:nth-child(2)').addClass('active');

			$('.tabs p').click(function() {
				$(this).siblings().removeClass('active');
				$(this).addClass('active');
				 var index = $(this).index();
     			 console.log(index);
     			 $('.tab-content:nth-child('+ parseInt(index + 2) +')' ).addClass('active');
     			 $('.tab-content:nth-child('+ parseInt(index + 2) +')' ).siblings().removeClass('active');
			});



			$('.wp-block-gallery a').attr('data-gall', 'gall1');
			$(".wp-block-gallery .wp-block-image").each(function(){
			    $(this).find('a').attr('title', $(this).find('img').attr('title'));
			  });

	/*		new VenoBox({
    selector: '.wp-block-gallery a',
    numeration: true,
    infinigall: true,
    share: true,
    spinner: 'rotating-plane'
});
*/
			
			//search
			$('.search-icon').click(function(e) {
				$('.header-search').slideToggle('swing');
			});

			$('body .body-content').click(function(e) {
				$('.header-search').slideUp('swing');
			});

			$('.search-again .btn').click(function(e) {
				e.preventDefault();
				$('.searchform').slideDown('swing');
				$('body,html').animate({
					scrollTop : 0                      
				}, 500);
			//	$('.searchform').slideDown('swing');
			});

			//close cart sidebar
			$('.close-icon, .close-sidebar').click(function(e) {
				$('.minicart-wrappper, body').removeClass('active');
			});

			/*------------------------------------*\
   				 MOBILE MENU
			\*------------------------------------*/			
			//open mobile menu
			$('#mob-menu-bar').click(function() {
				$(this).toggleClass('change');
				$('.header-bottom nav, body').toggleClass('menu-open');
			});

			$('header nav .cta > a').click(function() {
				$('#mob-menu-bar').trigger('click');
			});

			 //mob submenus
			 $('nav .menu-item-has-children > a').click(function (e) {
				e.preventDefault();
				
				var $submenu = $(this).next().next('.sub-menu'); // Target the sub-menu directly
				$(this).parent().toggleClass('opened');
				
				$(this).parent().siblings().find('.menu-item-has-children').removeClass('opened');
			});
			/*------------------------------------*\
   				 END OF MOBILE MENU
			\*------------------------------------*/
			$(window).scroll(function() {
				if ($(this).scrollTop() >= 500) {        
					$('#return-to-top').fadeIn(200);    
				} else {
					$('#return-to-top').fadeOut(200);   
				}
			});


			$('#return-to-top').click(function() {      
				$('body,html').animate({
					scrollTop : 0                      
				}, 500);
			});
			


			//create sticky nav
			$(window).scroll(function() {

				if ($(this).scrollTop() > 1){  
					$('.header').addClass("sticky");
					$('#theme-toggle').addClass('hide');
				}
				else{
					$('.header').removeClass("sticky");
					$('#theme-toggle').removeClass('hide');
				}
			});


			//accordions
			$('.accordion-title').click(function(){
				$(this).toggleClass('opened');
				$(this).next().slideToggle();
				//$(this).parent().siblings().find('.accordion-content').slideUp();
				//$(this).parent().siblings().find('.accordion-title').removeClass('opened');
			});


			//animate click on achor
			var $root = $('html, body');
			$('a[href^="#"]').click(function () {
			    $root.animate({
			        scrollTop: $( $.attr(this, 'href') ).offset().top
			    }, 500);

			    return false;
			});

			$('.gutenberg .sticky a:first-child').addClass('clicked');

			$('.gutenberg .sticky a').click(function(){
				$(this).addClass('clicked');
				$(this).siblings().removeClass('clicked');
			});



			$(window).scroll(function() {
	        var scrollDistance = $(window).scrollTop();
	    
	        // Assign active class to nav links while scolling
	        $('.wp-block-group').each(function(i) {
                if ($(this).position().top <= scrollDistance) {
                    var id = $(this).attr("id");
                        $('.gutenberg .sticky a[href="#'+id+'"]').addClass("clicked").siblings().removeClass("clicked");
                }
			        });
			}).scroll();





			// Hide Header on on scroll down
/*var didScroll;
var lastScrollTop = 0;
var delta = 1;
var navbarHeight = $('header').outerHeight();

$(window).scroll(function(event){
    didScroll = true;
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $('header').removeClass('nav-down').addClass('nav-up');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('header').removeClass('nav-up').addClass('nav-down');
        }
    }
    
    lastScrollTop = st;
}*/











$(window).on("load", function () {
    var urlHash = window.location.href.split("#")[1];
    if (urlHash &&  $('#' + urlHash).length )
          $('html,body').animate({
              scrollTop: $('#' + urlHash).offset().top
          }, 500);
});







	//Scroll back to top
	
	var progressPath = document.querySelector('.progress-wrap path');
	var pathLength = progressPath.getTotalLength();
	progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
	progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
	progressPath.style.strokeDashoffset = pathLength;
	progressPath.getBoundingClientRect();
	progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';		
	var updateProgress = function () {
		var scroll = $(window).scrollTop();
		var height = $(document).height() - $(window).height();
		var progress = pathLength - (scroll * pathLength / height);
		progressPath.style.strokeDashoffset = progress;
	}
	updateProgress();
	$(window).scroll(updateProgress);	
	var offset = 50;
	var duration = 550;
	jQuery(window).on('scroll', function() {
		if (jQuery(this).scrollTop() > offset) {
			jQuery('.progress-wrap').addClass('active-progress');
		} else {
			jQuery('.progress-wrap').removeClass('active-progress');
		}
	});				
	jQuery('.progress-wrap').on('click', function(event) {
		event.preventDefault();
		jQuery('html, body').animate({scrollTop: 0}, duration);
		return false;
	})
	


	
    $('.zigzag .read-more').click(function (event) {
        event.preventDefault();
        $(this).prev().slideToggle('slow');
        $(this).text($(this).text() == 'Read less...' ? 'Read more...' : 'Read less...');
    });
	$('.coach .read-more').click(function (event) {
        event.preventDefault();
        $(this).prev().toggleClass('expanded');
        $(this).text($(this).text() == 'Read less...' ? 'Read more...' : 'Read less...');
    });

		});//ready
	});
	


})(jQuery, this);
