<?php
$postsPageID = get_option('page_for_posts');
// WP_Query arguments
$args = array(
	'post_type' => array('post'),
	'post_status' => array('publish'),
	'posts_per_page' => '6',
	'post__not_in' => array(get_the_id()),
);

// The Query
$query = new WP_Query($args);

// The Loop
if ($query->have_posts()) {
	?>
	<div class="latest-posts-block">

		<div class="flex flex-space-between slider-header">
			<h2>
				<?php
				// Check if Polylang function exists
				if (function_exists('pll_current_language')) {
					// Get the current language
					$current_language = pll_current_language();

					// Output label based on language
					if ($current_language == 'en') {
						echo 'Latest news';
					} elseif ($current_language == 'de') {
						echo 'neuste Nachrichten';
					}
				}
				?>
			</h2>
			<div>
				<div class="swiper-button-prev">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
						<path fill-rule="evenodd" clip-rule="evenodd"
							d="M21.875 12C21.875 12.4734 21.4832 12.8571 21 12.8571L5.98744 12.8571L9.74372 16.5368C10.0854 16.8715 10.0854 17.4142 9.74372 17.7489C9.40201 18.0837 8.84799 18.0837 8.50628 17.7489L3.25628 12.6061C2.91457 12.2714 2.91457 11.7286 3.25628 11.3939L8.50628 6.25105C8.84799 5.91632 9.40201 5.91632 9.74372 6.25105C10.0854 6.58579 10.0854 7.1285 9.74372 7.46324L5.98744 11.1429L21 11.1429C21.4832 11.1429 21.875 11.5266 21.875 12Z"
							fill="#262626" />
					</svg>
				</div>
				<div class="swiper-button-next">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
						<path fill-rule="evenodd" clip-rule="evenodd"
							d="M3 12C3 12.4734 3.39175 12.8571 3.875 12.8571L18.8876 12.8571L15.1313 16.5368C14.7896 16.8715 14.7896 17.4142 15.1313 17.7489C15.473 18.0837 16.027 18.0837 16.3687 17.7489L21.6187 12.6061C21.9604 12.2714 21.9604 11.7286 21.6187 11.3939L16.3687 6.25105C16.027 5.91632 15.473 5.91632 15.1313 6.25105C14.7896 6.58579 14.7896 7.1285 15.1313 7.46324L18.8876 11.1429L3.875 11.1429C3.39175 11.1429 3 11.5266 3 12Z"
							fill="#262626" />
					</svg>
				</div>

				<a class="btn" href="<?php echo get_the_permalink($postsPageID); ?>">
					<?php
					// Check if Polylang function exists
					if (function_exists('pll_current_language')) {
						// Get the current language
						$current_language = pll_current_language();

						// Output label based on language
						if ($current_language == 'en') {
							echo 'See all news';
						} elseif ($current_language == 'de') {
							echo 'Alle Nachrichten sehen';
						}
					}
					?>
				</a>
			</div>
		</div>
		<div class="swiper postsSwiper">

			<div class="swiper-wrapper">
				<?php
				while ($query->have_posts()) {
					$query->the_post();
					?>

					<div class="swiper-slide">
						<article class="post-in-loop">
							<?php if (has_post_thumbnail()): ?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-in-loop-image">
									<?php the_post_thumbnail('grid-item'); ?>
								</a>
							<?php endif; ?>


							<div class="post-in-loop-content">
								<span class="date"><?php the_time('d F Y'); ?></span>
								<h2>
									<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
								</h2>


								<a class="read-article" href="<?php echo get_the_permalink(); ?>">
									<?php
									// Check if Polylang function exists
									if (function_exists('pll_current_language')) {
										// Get the current language
										$current_language = pll_current_language();

										// Output label based on language
										if ($current_language == 'en') {
											echo 'Read Article';
										} elseif ($current_language == 'de') {
											echo 'Artikel Lesen';
										}
									}
									?> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<path fill-rule="evenodd" clip-rule="evenodd"
											d="M3 12C3 12.4734 3.39175 12.8571 3.875 12.8571L18.8876 12.8571L15.1313 16.5368C14.7896 16.8715 14.7896 17.4142 15.1313 17.7489C15.473 18.0837 16.027 18.0837 16.3687 17.7489L21.6187 12.6061C21.9604 12.2714 21.9604 11.7286 21.6187 11.3939L16.3687 6.25105C16.027 5.91632 15.473 5.91632 15.1313 6.25105C14.7896 6.58579 14.7896 7.1285 15.1313 7.46324L18.8876 11.1429L3.875 11.1429C3.39175 11.1429 3 11.5266 3 12Z"
											fill="#09AE77" />
									</svg></a>
							</div>



						</article>

					</div>

					<?php
				}//endwhile ?>

			</div>

		</div>
		<!-- Initialize Swiper -->


	</div>
	<script>
		// Function to calculate the offset dynamically
		const calculateOffset = () => {
			const slideWidth = 1460; // Desired slide width in pixels
			const viewportWidth = window.innerWidth; // Viewport width

			// Calculate the offset: (100vw - slideWidth) / 2
			return (viewportWidth - slideWidth) / 2;
		};

		// Function to initialize or update Swiper
		const initSwiper = () => {
			var swiper = new Swiper(".postsSwiper", {
				slidesPerView: "1.6",
				spaceBetween: 16,
				slidesOffsetBefore: 20,
				slidesOffsetAfter: 20,
				loop: false,
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
				// Responsive breakpoints
				breakpoints: {
					767: {
						slidesPerView: "2.2",
					},
					1025: {
						slidesPerView: "3.2",
					},
					1490: {
						slidesPerView: "3.7",
						spaceBetween: 24,
						slidesOffsetBefore: calculateOffset(), // Calculate offset for large screens
					}
				}
			});
		};

		// Initialize Swiper after the DOM is fully loaded
		document.addEventListener('DOMContentLoaded', initSwiper);

		// Recalculate and update Swiper on window resize
		window.addEventListener('resize', () => {
			// Re-initialize or update Swiper with new offset
			initSwiper();
		});
	</script>

	<?php
}

// Restore original Post Data
wp_reset_postdata();
?>