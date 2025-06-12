<?php get_header();
$postsPageID = get_option('page_for_posts');
$news_post = get_post($postsPageID);
$page_content = apply_filters('the_content', $news_post->post_content);
?>
<div class="page-section blog-index">
	<div class="gutenberg">
		<?php echo apply_filters('the_content', $page_content); ?>
	</div>
	<div class="container" id="posts-feed">

		<?php get_template_part('template-parts/loop'); ?>

		<div class="pagination"><?php greentheme_pagination(); ?></div>

		
	</div><!-- /container -->
		<?php
if (function_exists('pll_current_language')) {
	// Get the current language
	$current_language = pll_current_language();

	// Output label based on language
	if ($current_language == 'en') {
		$pattern_id = 144;
	} elseif ($current_language == 'de') {
		$pattern_id = 864;
	}
} else {
	$pattern_id = 864;
}


// Fetch the pattern content (assuming it's stored in post content)
$pattern_post = get_post($pattern_id);

if ($pattern_post) {
    echo '<div class="gutenberg" style="margin-top:140px">';
    echo do_blocks($pattern_post->post_content);
    echo "</div>";
}
?>
</div><!-- /page-section -->
<?php get_footer(); ?>