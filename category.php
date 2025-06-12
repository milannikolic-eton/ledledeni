<?php get_header();
$postsPageID = get_option('page_for_posts');
$term = get_queried_object();

if ($term->parent == 0) {
	$parent_category_id = get_queried_object_id();
} else {
	$parent_category_id = $term->parent;
}

$background_color = get_field('background_color', $term);
$category_graphic = get_field('category_graphic', $term);

$child_categories = get_categories(array(
	'parent'     => $parent_category_id, // Specify the parent category ID.
	'hide_empty' => true,              // Set to true to hide empty categories.
));

if($background_color){
?>
<style>
	.category-subcategories .current-cat a,
	.category-subcategories li a:hover {
		background-color: <?php echo $background_color; ?>;
		border-color: <?php echo $background_color; ?>!important;
		color:#fff!important;
	}
</style>
<?php
}
?>


<div class="gutenberg">
	<div class="wp-block-group hero-banner">
		<div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained" style="background-color:<?php echo $background_color; ?>">
			<h1 class="wp-block-heading has-text-align-center"><?php single_cat_title(); ?></h1>
			<p class="has-text-align-center has-large-font-size"><?php echo $term->description; ?></p>
			<?php if($category_graphic): ?>
			<div class="svg-graphic"><?php echo wp_get_attachment_image($category_graphic, 'large'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>


<div class="category-subcategories">
	<div class="container flex flex-vertical-center">
	<?php 
	echo '<ul>';
	if ($term->parent == 0) {
        echo '<li class="current-cat">';
		echo '<a href="">';
		echo 'All';
		echo '</a>';
		echo '</li>';
    } else {
		echo '<li>';
		echo '<a href="' . esc_url(get_category_link($parent_category_id)) . '">';
		echo 'All';
		echo '</a>';
		echo '</li>';
	}
	foreach ($child_categories as $child_category) {
		if($child_category->term_id == $term->term_id){
			echo '<li class="current-cat">';
			echo '<a href="' . esc_url(get_category_link($child_category->term_id)) . '">';
			echo esc_html($child_category->name);
			echo '</a>';
			echo '</li>';
		} else{
			echo '<li>';
			echo '<a href="' . esc_url(get_category_link($child_category->term_id)) . '">';
			echo esc_html($child_category->name);
			echo '</a>';
			echo '</li>';
		}
	}
	echo '</ul>';
	?>
	</div>
</div>


<div class="container-wide">
	<div class="articles-grid">
			<div id="posts-feed">
				<?php get_template_part('template-parts/loop'); ?>

			</div>
			<?php
			//global $wp_query; // you can remove this line if everything works for you
			$total = get_queried_object()->category_count;


			// don't display the button if there are not enough posts
			if ($wp_query->max_num_pages > 1) {

				$posts_on_page = 8;
				$bar = $posts_on_page / $total * 100;

				echo '<div class="text-center">';
				echo "<div class='pagination-bar-wrapper'>";
				echo "<div class='pagination-bar-text'> Showing <span class='loaded-posts'>" . $posts_on_page . "</span> of a <span class='total-posts'>" . $total . "</span> Items </div>";
				echo "<div class='pagination-bar'><span style='width:" . $bar . "%'></span></div>";
				echo "</div>";
				echo '<div class="ajax_loadmore btn">Load more</div></div>';

			} else {
				$posts_on_page = $total;
				$bar = $posts_on_page / $total * 100;
			}
			?>
	</div>
</div>


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