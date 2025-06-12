/*jQuery(function($){
	var canBeLoaded = true, // this param allows to initiate the AJAX call only if necessary
	    bottomOffset = 2000; // the distance (in px) from the page bottom when you want to load more posts
 
	$(window).scroll(function(){
		var data = {
			'action': 'loadmore',
			'query': misha_loadmore_params.posts,
			'page' : misha_loadmore_params.current_page
		};
		if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == true ){
			$.ajax({
				url : misha_loadmore_params.ajaxurl,
				data:data,
				type:'POST',
				beforeSend: function( xhr ){
					// you can also add your own preloader here
					// you see, the AJAX call is in process, we shouldn't run it again until complete
					canBeLoaded = false; 
				},
				success:function(data){
					if( data ) {
						//$('#posts-feed').find('article:last-of-type').after( data ); // where to insert posts
						$('#posts-feed').append( data ); 
						canBeLoaded = true; // the ajax is completed, now we can run it again
						misha_loadmore_params.current_page++;
						
					}
				}
			});
		}
	});

});
*/
jQuery(function($){ // use jQuery code inside this to avoid "$ is not defined" error
	$('.ajax_loadmore').click(function(){
 		var totalPosts = parseInt($('.total-posts').text());
 		var totalPages = Math.ceil( (totalPosts - 1) / 8);
 		var author = jQuery('.author-bio h1').text();

		var button = $(this),
		    data = {
			'action': 'loadmore',
			'author': author,
			'query': misha_loadmore_params.posts, // that's how we get params from wp_localize_script() function
			'page' : misha_loadmore_params.current_page
		};

		console.log(parseInt(misha_loadmore_params.current_page) + 1);
		var barPercent = ( parseInt(misha_loadmore_params.current_page) + 1 ) / totalPages * 100;
		var loadedPosts = (parseInt(misha_loadmore_params.current_page) + 1 ) * 8;

		$('.pagination-bar span').css('width', barPercent + '%');


		if (totalPosts > loadedPosts) {
			$('.loaded-posts').text( loadedPosts );
		} else {
			$('.loaded-posts').text( totalPosts );
		}
		


		console.log(data);
 
		$.ajax({ // you can also use $.post here
			url : misha_loadmore_params.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('loading...'); // change the button text, you can also add a preloader image
			},
			success : function( data ){
				if( data ) { 
					//button.text( 'More posts' ).prev().before(data); // insert new posts
					button.text('Load more');
					$('#posts-feed').append( data ); 
					misha_loadmore_params.current_page++;
 
					if ( misha_loadmore_params.current_page == misha_loadmore_params.max_page ) 
						button.remove(); // if last page, remove the button
 
					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
					button.remove(); // if no data, remove the button as well
				}
			}
		});
	});
});