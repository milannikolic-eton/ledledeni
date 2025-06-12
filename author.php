<?php get_header(); 
 $postsPageID = get_option('page_for_posts'); 
 $term = get_queried_object();
 $total = count_user_posts(get_queried_object()->ID);

 if (is_author()){
    $author = get_queried_object();
    $author_id = $author->ID;
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_bio = get_the_author_meta('description', $author_id);
	$author_img_id = get_field('author_image', 'user_' . $author_id);
}
?>

<div class="category-heading">
	<div class="container flex flex-vertical-center">
		<div class="category-heading-content">

			<div class="flex">
				 <?php if($author_img_id){
						echo '<div class="author-image">' . wp_get_attachment_image( $author_img_id, 'thumbnail' ) . '</div>';
					} ?>
					<div class="author-bio">
			
						<h1><?php echo $author_name; ?></h1>
						<div> <?php echo $author_bio; ?> </div>
					</div>
			</div>
			
			<div class="total-articles">
				<b> <?php echo  $total; ?></b>
				<span>Articles</span>
			</div>
		</div>

		
	</div>
</div>


<div class="page-section">

	<div class="container">

		<div class="row" id="posts-feed">



			<?php get_template_part('template-parts/loop-related'); ?>


        </div>
		</div><!-- /row -->

						<?php
//global $wp_query; // you can remove this line if everything works for you
						 
						 
 
// don't display the button if there are not enough posts
if (  $wp_query->max_num_pages > 1 ){
	
	$posts_on_page = 9;
	$bar = $posts_on_page / $total * 100;

	echo '<div class="text-center">';
	echo "<div class='pagination-bar-wrapper'>";
	echo "<div class='pagination-bar-text'> Showing <span class='loaded-posts'>". $posts_on_page ."</span> of a <span class='total-posts'>" . $total . "</span> Items </div>";
	echo "<div class='pagination-bar'><span style='width:".$bar."%'></span></div>";
	echo "</div>";
	echo '<div class="ajax_loadmore btn">Load more</div></div>';

} else {
	$posts_on_page = $total;
	$bar = $posts_on_page / $total * 100;
}
?>
	</div><!-- /container -->
</div><!-- /page-section -->
<?php get_footer(); ?>

	