<?php get_header(); ?>

<div class="page-content">

    <div class="gutenberg container-reference">

        <ul class="breadcrumb">
            <li><a href="<?php echo get_home_url(); ?>/referenzen">Referenzen</a></li>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M5.83325 18.3333L14.1666 1.66666" stroke="#D0D5DD" stroke-width="1.66667"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <li><b><?php echo get_the_title(); ?></b></li>
        </ul>

        <?php
        $images = get_field('gallery'); // Fetch gallery images from ACF
        if ($images): ?>
            <div class="swiper-container my-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $image): ?>
                        <div class="swiper-slide">
                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Navigation Arrows -->
                <div class="swiper-button-next"><svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="55" height="55" rx="27.5" stroke="white" />
                        <path
                            d="M36.6475 28.3975L29.8975 35.1475C29.7909 35.2469 29.6498 35.301 29.5041 35.2984C29.3584 35.2958 29.2193 35.2368 29.1163 35.1337C29.0132 35.0307 28.9542 34.8916 28.9516 34.7459C28.949 34.6002 29.0031 34.4591 29.1025 34.3525L34.8916 28.5625H19.75C19.6008 28.5625 19.4577 28.5032 19.3523 28.3978C19.2468 28.2923 19.1875 28.1492 19.1875 28C19.1875 27.8508 19.2468 27.7077 19.3523 27.6023C19.4577 27.4968 19.6008 27.4375 19.75 27.4375H34.8916L29.1025 21.6475C29.0031 21.5409 28.949 21.3998 28.9516 21.2541C28.9542 21.1084 29.0132 20.9694 29.1163 20.8663C29.2193 20.7632 29.3584 20.7042 29.5041 20.7016C29.6498 20.6991 29.7909 20.7531 29.8975 20.8525L36.6475 27.6025C36.7528 27.708 36.812 27.8509 36.812 28C36.812 28.1491 36.7528 28.292 36.6475 28.3975Z"
                            fill="white" />
                    </svg></div>
                <div class="swiper-button-prev"><svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="55" height="55" rx="27.5" stroke="white" />
                        <path
                            d="M36.8125 28C36.8125 28.1492 36.7532 28.2923 36.6477 28.3978C36.5423 28.5032 36.3992 28.5625 36.25 28.5625H21.1084L26.8975 34.3525C26.9528 34.404 26.9971 34.4661 27.0278 34.5351C27.0586 34.6041 27.0751 34.6786 27.0764 34.7541C27.0778 34.8296 27.0639 34.9047 27.0356 34.9747C27.0073 35.0447 26.9652 35.1084 26.9118 35.1618C26.8584 35.2152 26.7947 35.2573 26.7247 35.2856C26.6546 35.3139 26.5796 35.3278 26.5041 35.3265C26.4286 35.3251 26.3541 35.3086 26.2851 35.2778C26.2161 35.2471 26.154 35.2028 26.1025 35.1475L19.3525 28.3975C19.2472 28.292 19.188 28.1491 19.188 28C19.188 27.8509 19.2472 27.708 19.3525 27.6025L26.1025 20.8525C26.2091 20.7531 26.3502 20.6991 26.4959 20.7016C26.6416 20.7042 26.7806 20.7632 26.8837 20.8663C26.9868 20.9694 27.0458 21.1084 27.0484 21.2541C27.0509 21.3998 26.9969 21.5409 26.8975 21.6475L21.1084 27.4375H36.25C36.3992 27.4375 36.5423 27.4968 36.6477 27.6023C36.7532 27.7077 36.8125 27.8508 36.8125 28Z"
                            fill="white" />
                    </svg></div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    new Swiper('.my-swiper', {
                        slidesPerView: 1,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev'
                        },
                        loop: true
                    });
                });
            </script>
        <?php endif; ?>


        <div class="reference-content flex">
            <div class="reference-title">
                <h1><?php echo get_the_title(); ?></h1>
                <?php if (have_posts()):
                    while (have_posts()):
                        the_post(); ?>

                        <?php the_content(); ?>

                    <?php endwhile; endif; ?>
            </div>
            <?php
            $products = get_field('reference_products'); // Get related products
            
            if ($products): ?>
                <ul class="reference-products">
                    <li>Unsere Lichtlösungen für dieses Projekt:</li>
                    <?php foreach ($products as $product_id):
                        $product = wc_get_product($product_id); // Get product object
                        if ($product): ?>

                            <li>
                                <a href="<?php echo get_permalink($product_id); ?>">
                                    <?php echo esc_html($product->get_name()); ?>
                                </a>
                            </li>
                        <?php endif;
                    endforeach; ?>
                </ul>
            <?php endif; ?>




        </div>


    </div>

</div>


<?php get_footer(); ?>