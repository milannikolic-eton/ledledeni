<?php
$faq_cat_id = get_field('faq_category');
$choose_faqs = get_field('choose_faqs');
$manually_choose_faqs = get_field('manually_choose_faqs');
?>

	<?php if ($choose_faqs && $manually_choose_faqs): ?>
		<div class="all-faqs">
		<?php foreach( $choose_faqs as $faq ): 
			$faq_post = get_post($faq);
			$title = get_the_title( $faq );
			$faq_content = apply_filters('the_content', $faq_post->post_content);
			?>
			<div class="accordion">
				<div class="accordion-title"><?php echo esc_html( $title ); ?></div>
				<div class="accordion-content"><?php echo $faq_content; ?></div>
			</div>
		<?php endforeach; ?>
		</div>
	
	<?php else: //not manually chosen FAQs ?>
		<div class="faqs-wrapper container flex">
		<div class="faqs-filters">
			<div>
			<?php
			$faq_cat_id = get_field('faq_category');
			$i = 0;
			$cat_terms = get_terms(array(
				'taxonomy' => 'faq_category',
				'hide_empty' => true,
				'meta_key' => 'order', // The custom field key you want to order by
    			'orderby' => 'meta_value_num', // Use 'meta_value_num' if the 'order' field contains numbers
    			'order' => 'ASC', // Use 'ASC' for ascending order, or 'DESC' for descending order
			));


			?>

			<?php if ($cat_terms): ?>

				<?php foreach ($cat_terms as $term): $i++; ?>
					<span class="<?php if ($i == 1) {
						echo 'is-active';
					} ?>"
						cat-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></span>
				<?php endforeach; ?>

			<?php endif; ?>

			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/ecoverdi-mesure.svg">

			</div>
		</div> <!-- /faqs-filters -->
	

	<div class="all-faqs">
		<?php

		// Get all terms from the 'faq_category' taxonomy
		$faq_categories = get_terms(array(
			'taxonomy' => 'faq_category',
			'hide_empty' => true, // Show categories even if they have no posts
			'meta_key' => 'order', // The custom field key you want to order by
    		'orderby' => 'meta_value_num', // Use 'meta_value_num' if the 'order' field contains numbers
    		'order' => 'ASC', // Use 'ASC' for ascending order, or 'DESC' for descending order
		));

		if (!empty($faq_categories) && !is_wp_error($faq_categories)) {
			foreach ($faq_categories as $category) {
				// Display the category name
				echo '<h2 id="'. $category->term_id .'">' . esc_html($category->name) . '</h2>';

				// Query FAQs under this category
				$faq_query = new WP_Query(array(
					'post_type' => 'faq',
					'tax_query' => array(
						array(
							'taxonomy' => 'faq_category',
							'field' => 'term_id',
							'terms' => $category->term_id,
						),
					),
					'orderby'       => 'menu_order', 
    				'order'          => 'ASC', 
					'posts_per_page' => -1, // Show all FAQs
				));

				if ($faq_query->have_posts()) {

					while ($faq_query->have_posts()) {
						$faq_query->the_post();
						// Display each FAQ title
						echo '<div class="accordion">';
						echo '<div class="accordion-title">' . get_the_title() . '</div>';
						echo '<div class="accordion-content">' . get_the_content() . '</div>';
						echo '</div>';

					}

				} else {
					echo '<p>No FAQs available in this category.</p>';
				}
				wp_reset_postdata(); // Reset post data
			}
		} else {
			echo '<p>No FAQ categories found.</p>';
		}


		?>
	</div>

</div>

<?php endif; ?>


<script>
jQuery(document).ready(function() {
	


	jQuery('.accordion-title').click(function () {
						jQuery(this).toggleClass('opened').next().slideToggle().parent().siblings().find('.accordion-content').slideUp();
						jQuery(this).parent().siblings().find('.accordion-title').removeClass('opened');
					});//accordion

    jQuery('.faqs-filters span').on('click', function() {
		jQuery(this).addClass('is-active').siblings().removeClass('is-active');
        var targetId = jQuery(this).attr('cat-id');
        var targetElement = jQuery('#' + targetId);

        if (targetElement.length) {
            jQuery('html, body').animate({
                scrollTop: targetElement.offset().top - 80
            }, 1000); // Adjust the duration (1000ms = 1 second) as needed
        }
    });
});

</script>

