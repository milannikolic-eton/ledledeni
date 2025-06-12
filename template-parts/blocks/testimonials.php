<?php
// Create id attribute allowing for custom "anchor" value.
$id = 'testimonials-' . $block['id'];

// Create class attribute allowing for custom "className" and "align" values.
$className = 'testimonials';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}
$testimonials = get_field('testimonials');
?>
<?php if ($testimonials): ?>
  <div class="slider-wrapper testimonials-slider" id="<?php echo $id; ?>">
    <div class="swiper testimonialsSwiper">
      <div class="swiper-wrapper">
        <?php while (have_rows('testimonials')):
          the_row();
          $title = get_sub_field('name');
          $subtitle = get_sub_field('position');
          $testimonial_text = get_sub_field('testimonial');

          ?>
          <div class="swiper-slide text-center">


            <?php if ($testimonial_text): ?>
              <div class="testimonial-text"><?php echo $testimonial_text; ?></div>
            <?php endif; ?>

            <div class="testimonial-footer">

              <div>
                <?php if ($title): ?>
                  <div class="testimonial-title">- <?php echo $title; ?></div>
                <?php endif; ?>

                <?php if ($subtitle): ?>
                  <div class="testimonial-subtitle"><?php echo $subtitle; ?></div>
                <?php endif; ?>
              </div>


            </div>



          </div>
        <?php endwhile; ?>
      </div>

      <div class="flex flex-center swiper-controls">
        <div class="swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"
            fill="none">
            <path opacity="1"
              d="M28.0008 16.0006C28.0008 16.2658 27.8954 16.5201 27.7079 16.7077C27.5204 16.8952 27.266 17.0006 27.0008 17.0006H7.41454L14.7083 24.2931C14.8012 24.386 14.8749 24.4963 14.9252 24.6177C14.9755 24.7391 15.0013 24.8692 15.0013 25.0006C15.0013 25.132 14.9755 25.2621 14.9252 25.3835C14.8749 25.5048 14.8012 25.6151 14.7083 25.7081C14.6154 25.801 14.5051 25.8747 14.3837 25.9249C14.2623 25.9752 14.1322 26.0011 14.0008 26.0011C13.8694 26.0011 13.7393 25.9752 13.6179 25.9249C13.4965 25.8747 13.3862 25.801 13.2933 25.7081L4.29329 16.7081C4.20031 16.6152 4.12655 16.5049 4.07623 16.3835C4.0259 16.2621 4 16.132 4 16.0006C4 15.8691 4.0259 15.739 4.07623 15.6176C4.12655 15.4962 4.20031 15.3859 4.29329 15.2931L13.2933 6.29306C13.4809 6.10542 13.7354 6 14.0008 6C14.2662 6 14.5206 6.10542 14.7083 6.29306C14.8959 6.4807 15.0013 6.73519 15.0013 7.00056C15.0013 7.26592 14.8959 7.52042 14.7083 7.70806L7.41454 15.0006H27.0008C27.266 15.0006 27.5204 15.1059 27.7079 15.2934C27.8954 15.481 28.0008 15.7353 28.0008 16.0006Z"
              fill="#121212" />
          </svg></div>
        <div class="swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"
            fill="none">
            <path
              d="M27.7075 16.7081L18.7075 25.7081C18.5199 25.8957 18.2654 26.0011 18 26.0011C17.7346 26.0011 17.4801 25.8957 17.2925 25.7081C17.1049 25.5204 16.9994 25.2659 16.9994 25.0006C16.9994 24.7352 17.1049 24.4807 17.2925 24.2931L24.5863 17.0006H5C4.73478 17.0006 4.48043 16.8952 4.29289 16.7077C4.10536 16.5201 4 16.2658 4 16.0006C4 15.7353 4.10536 15.481 4.29289 15.2934C4.48043 15.1059 4.73478 15.0006 5 15.0006H24.5863L17.2925 7.70806C17.1049 7.52042 16.9994 7.26592 16.9994 7.00056C16.9994 6.73519 17.1049 6.4807 17.2925 6.29306C17.4801 6.10542 17.7346 6 18 6C18.2654 6 18.5199 6.10542 18.7075 6.29306L27.7075 15.2931C27.8005 15.3859 27.8742 15.4962 27.9246 15.6176C27.9749 15.739 28.0008 15.8691 28.0008 16.0006C28.0008 16.132 27.9749 16.2621 27.9246 16.3835C27.8742 16.5049 27.8005 16.6152 27.7075 16.7081Z"
              fill="#121212" />
          </svg></div>
      </div>

    </div>
  </div>

  <script>
    var swiper = new Swiper(".testimonialsSwiper", {
      slidesPerView: 1, // Use integers instead of strings
      loop: true,
      spaceBetween: 20,
     // slidesOffsetBefore: 20,
     // slidesOffsetAfter: 20, // This should now apply correctly
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        540: {
          slidesPerView: 2, // Adjusting to integers
          spaceBetween: 20,
        },
        1200: {
          slidesPerView: 3, // Adjusting to integers
          spaceBetween: 32,
        },
      },
      speed: 800,
    });
  </script>
<?php endif; ?>