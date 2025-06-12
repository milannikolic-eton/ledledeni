<?php if (have_posts()):
	$i = 0;
	while (have_posts()):
		the_post();
		$i++; ?>


		<article class="post-in-loop">

			<?php if (get_post_type(get_the_ID()) != 'faq'): ?>
				<?php if (has_post_thumbnail()): ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-in-loop-image">
						<?php the_post_thumbnail('grid-item'); ?>
					</a>
				<?php else: ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-in-loop-image default-image">
						<?php the_post_thumbnail('grid-item'); ?>
					</a>
				<?php endif; ?>
			<?php endif; //not faq ?>

			<div class="post-in-loop-content">
				<span
					class="date"><?php if (get_post_type(get_the_ID()) == 'post') {
						echo 'News';
					} else {
						echo get_post_type(get_the_ID());
					} ?></span>
				<h2>
					<?php if (get_post_type(get_the_ID()) != 'faq'): ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php endif; //not faq ?>
						<?php the_title(); ?>
					<?php if (get_post_type(get_the_ID()) != 'faq'): ?>
					</a>
					<?php endif; //not faq ?>
				</h2>
				<?php if (get_post_type(get_the_ID()) == 'faq'):
					$faq_post = get_post($faq);
					echo $faq_content = apply_filters('the_content', $faq_post->post_content); ?>

				<?php else: //not faq ?>
					<a class="read-article" href="<?php echo get_the_permalink(); ?>">
					<?php
					// Check if Polylang function exists
					if (function_exists('pll_current_language')) {
						// Get the current language
						$current_language = pll_current_language();

						// Output label based on language
						if ($current_language == 'en') {
							echo 'Read More';
						} elseif ($current_language == 'de') {
							echo 'Mehr lesen';
						}
					}
					?> <svg
							xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M3 12C3 12.4734 3.39175 12.8571 3.875 12.8571L18.8876 12.8571L15.1313 16.5368C14.7896 16.8715 14.7896 17.4142 15.1313 17.7489C15.473 18.0837 16.027 18.0837 16.3687 17.7489L21.6187 12.6061C21.9604 12.2714 21.9604 11.7286 21.6187 11.3939L16.3687 6.25105C16.027 5.91632 15.473 5.91632 15.1313 6.25105C14.7896 6.58579 14.7896 7.1285 15.1313 7.46324L18.8876 11.1429L3.875 11.1429C3.39175 11.1429 3 11.5266 3 12Z"
								fill="#09AE77" />
						</svg></a>
				<?php endif; //if faq ?>
			</div>



		</article>


	<?php endwhile; ?>

<?php else: ?>

	<!-- article -->
	<article>

	</article>
	<!-- /article -->

<?php endif; ?>