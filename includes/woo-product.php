<?php
// Add a single parent category before the product title on the single product page
add_action( 'woocommerce_single_product_summary', 'display_single_parent_category_before_title', 1 );

function display_single_parent_category_before_title() {
    global $product;

    // Get the categories assigned to the product
    $terms = get_the_terms( $product->get_id(), 'product_cat' );

    if ( $terms && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            // Check if the term is a parent category
            if ( $term->parent == 0 ) {
                echo '<div class="product-category"><span>' . esc_html( $term->name ) . '</span></div>';
                break; // Exit the loop after finding the first parent category
            }
        }
    }
}



// Move short description after the title on single product page
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6 );

//remove category in summary
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );




// Show sale price and savings for simple products on sale
add_action( 'woocommerce_single_product_summary', 'display_sale_price_and_savings', 10 );

function display_sale_price_and_savings() {
    global $product;

    // Check if the product is a simple product and is on sale
    if ( $product->is_type( 'simple' ) && $product->is_on_sale() ) {
        // Get the regular and sale prices
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();

        // Calculate the savings
        $savings = $regular_price - $sale_price;

        // Display the sale price, regular price, and savings
        echo '<p class="product-pricing">';
        echo '<span class="sale-price">' . wc_price( $sale_price ) . '</span>';
        echo '<div class="flex flex-vertical-center"><span class="regular-price"><del>' . wc_price( $regular_price ) . '</del></span>';
        echo '<span class="savings">Ahorras ' . wc_price( $savings ) . '</span></div>';
        echo '</p>';
    }
}



// Remove WooCommerce product tabs from default position
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// Remove the default WooCommerce tabs
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// Add the tabs after product summary with accordion structure
add_action( 'woocommerce_single_product_summary', 'custom_woocommerce_output_product_tabs', 200 );

function custom_woocommerce_output_product_tabs() {
    global $product;

    // Get the product tabs
    $tabs = apply_filters( 'woocommerce_product_tabs', array() );

    if ( ! empty( $tabs ) ) {
        echo '<div class="woocommerce-accordion-tabs">';

        foreach ( $tabs as $key => $tab ) {
            // Output the tab title with the "accordion-title" class
            echo '<div class="accordion"><div class="accordion-title" data-tab-key="' . esc_attr( $key ) . '">';
            echo esc_html( $tab['title'] );
            echo '</div>';

            // Output the tab content with the "accordion-content" class
            echo '<div class="accordion-content" id="content-' . esc_attr( $key ) . '">';
            if ( isset( $tab['callback'] ) && is_callable( $tab['callback'] ) ) {
                // Capture and display the content from the callback
                call_user_func( $tab['callback'], $key, $tab );
            }
            echo '</div></div>';
        }

        echo '</div>'; // End of woocommerce-accordion-tabs
    }
}

/**
 * Remove "Description" Heading Title @ WooCommerce Single Product Tabs
 */
add_filter( 'woocommerce_product_description_heading', '__return_null' );
add_filter('woocommerce_product_additional_information_heading', '__return_null');
add_filter('woocommerce_reviews_title', '__return_null');



// Hook to modify the onsale badge and display percentage discount for variable products
add_filter('woocommerce_sale_flash', 'custom_woocommerce_variable_sale_percentage', 10, 3);

function custom_woocommerce_variable_sale_percentage($html, $post, $product) {
    // Check if the product is on sale
    if ($product->is_type('variable')) {
        // Get available variations
        $available_variations = $product->get_available_variations();
        $regular_price = $sale_price = null;

        // Loop through the variations
        foreach ($available_variations as $variation) {
            $variation_obj = new WC_Product_Variation($variation['variation_id']);
            $variation_regular_price = $variation_obj->get_regular_price();
            $variation_sale_price = $variation_obj->get_sale_price();

            // Find the first variation with a sale price
            if ($variation_sale_price && $variation_regular_price > 0) {
                $regular_price = $variation_regular_price;
                $sale_price = $variation_sale_price;
                break;  // Break out of the loop once the first sale price is found
            }
        }

        // If a variation with sale price is found, calculate the percentage discount
        if ($regular_price && $sale_price) {
            $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);

            // Return custom sale flash with percentage
            $html = '<span class="onsale"> ' . $discount_percentage . ' %</span>';
        }
    } elseif ($product->is_on_sale()) {
        // For simple products
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();

        if ($regular_price > 0) {
            $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
            $html = '<span class="onsale">' . $discount_percentage . ' %</span>';
        }
    }

    return $html;
}




// Remove related products from single product page
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


//add featured products with random order instad of related products
add_action( 'woocommerce_after_single_product', 'custom_woocommerce_featured_products', 30 );
function custom_woocommerce_featured_products() {

    global $product;
    // Query for featured products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4, // Number of products to display
        'orderby'        => 'rand', // Random order
        'post__not_in'   => array( $product->get_id() ),
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured', // Get featured products
            ),
        ),
    );

    $featured_products = new WP_Query( $args );

    if ( $featured_products->have_posts() ) {
        echo '<section class="featured-products"><div class="flex"><h2>' . __( 'Destacados', 'woocommerce' ) . '</h2>';
        if (function_exists('pll_current_language')) {
            $current_language = pll_current_language(); // Get the current language
            if ($current_language === 'es') {
                // Code for Spanish language
                echo '<a href="'. get_permalink(57) .'">Ver todos los destacados</a></div>';
            } else {
                echo '<a href="'. get_permalink(3291) .'">View all featured products</a></div>';
            }
        }
        
        woocommerce_product_loop_start(); // Start the product loop
        
        while ( $featured_products->have_posts() ) {
            $featured_products->the_post();
            wc_get_template_part( 'content', 'product' ); // Display each product
        }
        
        woocommerce_product_loop_end(); // End the product loop
        echo '</section>';
    }

    wp_reset_postdata();
}



function product_scripts()  {
    ?>
    <script>
   /*  document.addEventListener('DOMContentLoaded', function () {
    const thumbnailContainer = document.querySelector('.flex-control-thumbs');

    if (thumbnailContainer) {
        const scrollLeftButton = document.createElement('button');
        const scrollRightButton = document.createElement('button');
        
        scrollLeftButton.innerHTML = '&larr;';
        scrollRightButton.innerHTML = '&rarr;';
        
        scrollLeftButton.style.position = scrollRightButton.style.position = 'absolute';
        scrollLeftButton.style.top = scrollRightButton.style.top = '50%';
        scrollLeftButton.style.transform = scrollRightButton.style.transform = 'translateY(-50%)';
        scrollLeftButton.style.left = '-30px';
        scrollRightButton.style.right = '-30px';

        scrollLeftButton.addEventListener('click', function () {
            thumbnailContainer.scrollBy({ left: -100, behavior: 'smooth' });
        });
        
        scrollRightButton.addEventListener('click', function () {
            thumbnailContainer.scrollBy({ left: 100, behavior: 'smooth' });
        });

        thumbnailContainer.parentElement.appendChild(scrollLeftButton);
        thumbnailContainer.parentElement.appendChild(scrollRightButton);
    }
});
   */
    </script>
    <?php
}
add_action( 'woocommerce_after_single_product', 'product_scripts', 30 );



// ACF custom shipping fields above the add to cart button

add_action('woocommerce_before_add_to_cart_button', 'display_acf_custom_fields');

function display_acf_custom_fields() {
    global $post;

    // Retrieve ACF fields for the current product
   // $date_of_arrival = get_field('date_of_arrival', $post->ID);
   // $shipping_message = get_field('shipping_message', $post->ID);
    if(get_field('date_of_arrival', $post->ID)){
        $date_of_arrival = get_field('date_of_arrival', $post->ID);
    } else {
        $date_of_arrival = get_field('date_of_arrival', 'option');
    }

    if(get_field('shipping_message', $post->ID)){
        $shipping_message = get_field('shipping_message', $post->ID);
    } else {
        $shipping_message = get_field('shipping_message', 'option');
    }
    
    

    echo '<div class="shipping-details">';

    // Calculate the arrival date excluding weekends
    if ($date_of_arrival) {
        $arrival_date = calculate_arrival_date($date_of_arrival);
        $formatted_date = format_spanish_date($arrival_date);
        echo '<p class="product-date-of-arrival"><svg width="32" height="24" viewBox="0 0 32 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M29.3148 8.44225L31.3109 9.8088C31.5209 9.94956 31.6935 10.1398 31.8136 10.3631C31.9337 10.5863 31.9977 10.8357 32 11.0895V19.1622C31.9996 19.4592 31.8822 19.7439 31.6734 19.9539C31.4646 20.1639 31.1815 20.282 30.8862 20.2824H29.7755C29.6413 21.0885 29.2272 21.8207 28.6069 22.3488C27.9866 22.8769 27.2002 23.1667 26.3874 23.1667C25.5747 23.1667 24.7883 22.8769 24.1679 22.3488C23.5476 21.8207 23.1335 21.0885 22.9993 20.2824H14.7641C14.6298 21.0885 14.2157 21.8207 13.5954 22.3488C12.975 22.8769 12.1886 23.1667 11.3758 23.1667C10.5631 23.1667 9.77663 22.8769 9.15628 22.3488C8.53593 21.8207 8.12184 21.0885 7.98761 20.2824H6.87555C6.58025 20.282 6.29715 20.1639 6.08834 19.9539C5.87953 19.7439 5.76207 19.4592 5.76171 19.1622V16.6847H4.64955C4.498 16.6847 4.35266 16.6242 4.24549 16.5164C4.13833 16.4086 4.07813 16.2625 4.07813 16.1101C4.07813 15.9576 4.13833 15.8115 4.24549 15.7037C4.35266 15.5959 4.498 15.5354 4.64955 15.5354H22.1646V1.98273H5.35379C5.25764 2.23274 5.21247 2.49966 5.22098 2.76758V5.76725C5.22098 5.91967 5.16078 6.06584 5.05362 6.17361C4.94645 6.28138 4.80111 6.34193 4.64955 6.34193C4.498 6.34193 4.35266 6.28138 4.24549 6.17361C4.13833 6.06584 4.07813 5.91967 4.07813 5.76725V2.76758C4.07813 1.61065 4.52568 0.833374 5.19197 0.833374H22.7361C22.8876 0.833382 23.0329 0.893931 23.1401 1.0017C23.2473 1.10947 23.3075 1.25564 23.3075 1.40805V3.44357H25.9729C26.2954 3.43852 26.6122 3.52844 26.8844 3.70225C27.1567 3.87606 27.3724 4.12617 27.505 4.42176L29.1557 8.25452C29.1901 8.33092 29.2452 8.39599 29.3148 8.44225ZM26.2563 4.66455C26.1706 4.61392 26.0723 4.58906 25.973 4.59291L23.3075 4.59292V8.34292H27.9485L26.4565 4.87857C26.4115 4.78945 26.342 4.71518 26.2563 4.66455ZM28.8177 9.49227H23.3075L23.3075 15.5354H30.8571V11.0895C30.8552 11.0229 30.8368 10.9579 30.8038 10.9003C30.7707 10.8426 30.724 10.794 30.6677 10.7589L28.8177 9.49227ZM14.7641 19.133H22.9993C23.0854 18.6215 23.2846 18.1359 23.5822 17.7122C23.8799 17.2885 24.2683 16.9373 24.7189 16.6847H13.0444C13.495 16.9373 13.8835 17.2884 14.1811 17.7122C14.4788 18.1359 14.678 18.6215 14.7641 19.133ZM9.70727 16.6847H6.90457L6.90458 19.1622L7.98613 19.1425C8.07105 18.6292 8.26988 18.1416 8.56784 17.7161C8.86581 17.2906 9.25526 16.9381 9.70727 16.6847ZM9.75329 21.3407C10.1838 21.7733 10.7673 22.0166 11.3758 22.0174C11.83 22.0174 12.274 21.882 12.6517 21.6282C13.0294 21.3745 13.3238 21.0139 13.4977 20.5919C13.6716 20.1699 13.7172 19.7056 13.6287 19.2576C13.5402 18.8095 13.3217 18.398 13.0006 18.0748C12.6796 17.7517 12.2705 17.5316 11.8251 17.4422C11.3797 17.3529 10.9179 17.3984 10.4982 17.573C10.0785 17.7475 9.71964 18.0433 9.46705 18.4229C9.21447 18.8026 9.07947 19.249 9.07913 19.7058L9.07951 19.7077L9.07917 19.7094C9.08037 20.3215 9.32279 20.9081 9.75329 21.3407ZM24.7663 21.3419C25.1966 21.7737 25.7794 22.0166 26.3873 22.0174C26.995 22.0166 27.5777 21.7739 28.008 21.3423C28.4383 20.9106 28.6811 20.3253 28.6835 19.7141L28.6822 19.7077L28.6835 19.7011C28.682 19.0896 28.4394 18.5036 28.0089 18.0717C27.5783 17.6399 26.995 17.3974 26.3869 17.3975C25.7788 17.3976 25.1956 17.6403 24.7652 18.0724C24.3348 18.5044 24.0924 19.0905 24.0912 19.7021L24.0923 19.7077L24.0913 19.7132C24.0933 20.3245 24.336 20.9101 24.7663 21.3419ZM29.1926 17.7122C29.4902 18.136 29.6894 18.6215 29.7755 19.133H30.8862L30.8627 16.6848H28.0558C28.5064 16.9373 28.8949 17.2885 29.1926 17.7122Z" fill="#393939"/>
<path d="M9.73382 8.40718C9.88537 8.40718 10.0307 8.34663 10.1379 8.23886C10.245 8.13109 10.3052 7.98492 10.3052 7.8325C10.3052 7.68009 10.245 7.53392 10.1379 7.42614C10.0307 7.31837 9.88537 7.25782 9.73382 7.25782H5.39481C5.24326 7.25782 5.09791 7.31837 4.99075 7.42614C4.88359 7.53392 4.82338 7.68009 4.82338 7.8325C4.82338 7.98492 4.88359 8.13109 4.99075 8.23886C5.09791 8.34663 5.24326 8.40718 5.39481 8.40718H9.73382Z" fill="#393939"/>
<path d="M2.21094 10.9385H9.73379C9.88535 10.9385 10.0307 10.878 10.1379 10.7702C10.245 10.6624 10.3052 10.5163 10.3052 10.3638C10.3052 10.2114 10.245 10.0653 10.1379 9.95748C10.0307 9.84971 9.88535 9.78916 9.73379 9.78916H2.21094C2.05939 9.78916 1.91404 9.84971 1.80688 9.95748C1.69971 10.0653 1.63951 10.2114 1.63951 10.3638C1.63951 10.5163 1.69971 10.6624 1.80688 10.7702C1.91404 10.878 2.05939 10.9385 2.21094 10.9385Z" fill="#393939"/>
<path d="M10.1379 12.4891C10.245 12.5969 10.3052 12.743 10.3052 12.8955C10.3052 13.0479 10.245 13.194 10.1379 13.3018C10.0307 13.4096 9.88537 13.4701 9.73382 13.4701H0.571429C0.419876 13.4701 0.274531 13.4096 0.167368 13.3018C0.060204 13.194 0 13.0479 0 12.8955C0 12.743 0.060204 12.5969 0.167368 12.4891C0.274531 12.3813 0.419876 12.3208 0.571429 12.3208H9.73382C9.88537 12.3208 10.0307 12.3813 10.1379 12.4891Z" fill="#393939"/>
</svg>
Recíbelo el <b>' . esc_html($formatted_date) . '</b></p>';
    }

    if ($shipping_message) {
        // Split the message into words
        $words = explode(' ', $shipping_message);

        // Remove and wrap the last word
        $last_word = '<b>' . array_pop($words) . '</b>';

        // Rebuild the message with the last word wrapped in <b>
        $shipping_message_with_bold = implode(' ', $words) . ' ' . $last_word;

        echo '<p class="product-shipping-message"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M21.458 22.887C21.4564 22.8883 21.4549 22.8896 21.4534 22.8909C21.4549 22.8896 21.4564 22.8883 21.458 22.887ZM11.9826 19.3667C11.9788 19.3587 11.975 19.3507 11.9712 19.3427C11.975 19.3507 11.9788 19.3587 11.9826 19.3667M8.53333 9.3286L5.45091 6.24617L5.21623 6.51335C2.99857 9.03807 1.63333 12.3821 1.63333 16C1.63333 23.9174 8.08257 30.3667 16 30.3667C19.6179 30.3667 22.9619 29.0014 25.4866 26.7838L25.7538 26.5491L23.4714 24.2667L24.2667 23.4714L26.5491 25.7538L26.7838 25.4866C29.0014 22.9619 30.3667 19.6179 30.3667 16C30.3667 8.08257 23.9174 1.63333 16 1.63333C12.3821 1.63333 9.03807 2.99857 6.51335 5.21623L6.24617 5.45091L9.3286 8.53333L8.53333 9.3286ZM11.3328 13.3H17.6667V14.4333H11.051L11.0046 14.7119C10.9348 15.1305 10.9001 15.5488 10.9001 16C10.9001 16.4171 10.9347 16.8689 11.0046 17.2882L11.051 17.5667H17.6667V18.7H11.3328L11.5258 19.1619C12.4985 21.4893 14.5359 23.1334 16.9334 23.1334C18.1837 23.1334 19.4287 22.678 20.4467 21.8026L21.227 22.6461C19.9923 23.7171 18.4731 24.3 16.9334 24.3C13.9066 24.3 11.2815 22.0891 10.25 18.9623L10.1744 18.7334H7.56673V17.6H9.89355L9.82886 17.2119C9.7653 16.8305 9.7334 16.4488 9.7334 16.0333C9.7334 15.6505 9.76541 15.2355 9.82886 14.8548L9.89355 14.4667H7.56673V13.3334H10.1411L10.2166 13.1044C11.2482 9.9776 13.8733 7.76668 16.9001 7.76668C18.5045 7.76668 19.9696 8.32187 21.2243 9.41722L20.4538 10.2294C19.4389 9.32417 18.2173 8.86668 16.9334 8.86668C14.5359 8.86668 12.4985 10.5107 11.5258 12.8382L11.3328 13.3ZM0.5 16C0.5 7.45076 7.45076 0.5 16 0.5C24.5492 0.5 31.5 7.45076 31.5 16C31.5 24.5492 24.5492 31.5 16 31.5C7.45076 31.5 0.5 24.5492 0.5 16Z" fill="#393939"/>
</svg>
 ' . wp_kses_post($shipping_message_with_bold) . '</p>';
    }

    echo '</div>';
}



// Function to calculate arrival date excluding weekends
function calculate_arrival_date($days_to_add) {
    $date = new DateTime();

    while ($days_to_add > 0) {
        $date->modify('+1 day');

        // Check if the day is a weekend (Saturday or Sunday)
        if ($date->format('N') < 6) { // 1 (Monday) to 5 (Friday)
            $days_to_add--;
        }
    }

    return $date;
}


// Function to format the date in Spanish day name and day number
function format_spanish_date($date) {
    // Spanish day names
    $days_in_spanish = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    
    // Get the day name and day number
    $day_name = $days_in_spanish[$date->format('w')];
    $day_number = $date->format('d');

    return $day_name . ' ' . $day_number;
}