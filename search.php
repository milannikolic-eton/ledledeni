<?php get_header();
$postsPageID = get_option('page_for_posts');
$hero_url = get_the_post_thumbnail_url($postsPageID, 'hero');
?>
<div class="page-section">
	<div class="gutenberg">
		<div class="wp-block-cover has-parallax" style="min-height:55vh;aspect-ratio:unset;">
			<span aria-hidden="true"
				class="wp-block-cover__background has-background-dim wp-block-cover__gradient-background has-background-gradient"
				style="background:linear-gradient(90deg,rgb(0,0,0) 2%,rgba(0,0,0,0.57) 100%)"></span>
			<div class="wp-block-cover__image-background wp-image-240 has-parallax"
				style="background-position:50% 50%;background-image:url(<?php echo pll_home_url('de'); ?>wp-content/uploads/2024/09/kiri-tree.jpg)">
			</div>
			<div class="wp-block-cover__inner-container is-layout-constrained wp-block-cover-is-layout-constrained">
				<p class="has-text-align-left animated fadeInDown moving" style="font-size: 2rem;">
					<strong><?php echo sprintf(__('%s Search Results for ', 'ecoverde'), $wp_query->found_posts); ?></strong>
				</p>



				<h1 class="wp-block-heading animated fadeInUp moving"><?php echo get_search_query(); ?></h1>
			</div>
		</div>
	</div>

	<?php if (have_posts()): ?>
		<div class="container" id="posts-feed">

			<?php get_template_part('template-parts/loop-search'); ?>

			<div class="pagination"><?php greentheme_pagination(); ?></div>


		</div><!-- /container -->
	<?php endif; ?>
</div><!-- /page-section -->

<?php get_footer(); ?>