<?php get_header(); 
 $postsPageID = get_option('page_for_posts'); 
?>
<div class="page-section">
	<div class="container">
		<div class="row" id="posts-feed">
			<div class="col12">
				<h1><?php single_cat_title(); ?></h1>

			</div>


			<?php get_template_part('template-parts/loop'); ?>


        </div>
		</div><!-- /row -->

						<?php
//global $wp_query; // you can remove this line if everything works for you
 
// don't display the button if there are not enough posts
if (  $wp_query->max_num_pages > 1 )
	echo '<div class="text-center"><div class="ajax_loadmore btn">Load more</div></div>';
?>
	</div><!-- /container -->
</div><!-- /page-section -->
<?php get_footer(); ?>