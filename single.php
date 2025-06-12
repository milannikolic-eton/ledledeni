<?php get_header();
$postsPageID = get_option('page_for_posts');
$author_id = get_post_field('post_author', get_the_id());
//$author_image = get_field('author_image', 'user_' . $author_id);
$author_id = get_post_field('post_author', get_the_id());
$author_name = get_the_author_meta('display_name', $author_id);
//$author_img_id = get_field('author_image', 'user_' . $author_id);
$author_bio = get_the_author_meta('description', $author_id);
$author_link = get_author_posts_url($author_id);
?>

<div class="single-post-intro">
	<div class="container">
		<?php
		// Check if the current post has categories
		$categories = get_the_category();

		if (!empty($categories)) {
			$parent_category = null;

			foreach ($categories as $category) {
				// Check if the category is a parent category (parent ID = 0)
				if ($category->parent == 0) {
					$parent_category = $category;
					break; // Stop the loop when we find a parent category
				}
			}

			// If we found a parent category, display its link
			if ($parent_category) {
				$parent_link = get_category_link($parent_category->term_id);
				echo '<a class="read-article" href="' . esc_url($parent_link) . '">Back to ' . esc_html($parent_category->name) . '</a>';
			}
		}
		?>

		<h1><?php echo get_the_title(); ?></h1>

		<span class="date"><?php the_time('d F Y'); ?></span>

		<?php if (has_post_thumbnail()):
			echo $featured_image = get_the_post_thumbnail(get_the_ID(), 'large');
		?>

		<?php endif; ?>
	</div> <!-- /container -->
</div><!-- /single-post-intro -->

<div class="gutenberg">



	<div class="container-narrow">
		<?php
		if (!has_excerpt()) {

		} else {
			echo '<div class="single-excerpt">' . get_the_excerpt() . '</div>';
		}
		?>
		<?php the_content(); // Dynamic Content ?>
	</div>



</div> <!-- /gutenberg -->



<?php get_template_part('template-parts/related-posts'); ?>

<?php
// Define the post ID of the Gutenberg block pattern
//$block_pattern_id = 123;
$block_pattern_id = 90;

// Get the post content of the Gutenberg block pattern
$block_pattern_post = get_post($block_pattern_id);

if ($block_pattern_post && $block_pattern_post->post_type === 'wp_block') {
	// Access the block content
	$block_content = $block_pattern_post->post_content;

	// Output the block content
	echo apply_filters('the_content', $block_content);
}
?>

<?php get_footer(); ?>