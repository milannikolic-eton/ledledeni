<?php 
$gallery = get_field('gallery'); // Replace 'gallery' with your field name
if ($gallery): ?>
    <div class="swiper-container-gallery">
        <div class="swiper-wrapper">
            <?php foreach ($gallery as $image): ?>
                <div class="swiper-slide">
                    <img src="<?php echo esc_url($image['sizes']['large']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Progress bar pagination -->
        <div class="swiper-pagination"></div>
    </div>
<?php endif; ?>


<script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('.swiper-container-gallery', {
                slidesPerView: 1.8,
                spaceBetween: 32, // Adjust spacing between slides
                speed:1200,
                pagination: {
                    el: '.swiper-pagination',
                    type: 'progressbar', // Use progress bar type for pagination
                },
                autoplay: {
                    delay: 3000, // Time in milliseconds between slides
                    disableOnInteraction: true, // Disable autoplay on interaction
                    pauseOnMouseEnter: true,
                },
                loop: false, // Optional: enables looping
            });
        });
    </script>
