<?php
//remove sort on shop archive
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

function set_woocommerce_products_per_page($cols)
{
    return 15; // Set to 3 products per page
}
add_filter('loop_shop_per_page', 'set_woocommerce_products_per_page', 20);

function remove_woocommerce_page_title()
{
    return false;
}
add_filter('woocommerce_show_page_title', 'remove_woocommerce_page_title');


function custom_woocommerce_result_count()
{
    global $wp_query;

    if ($wp_query->found_posts > 0) {
        echo '<p class="custom-result-count">' . sprintf(
            _n('Zeige %d Produkt', 'Zeige %d Produkte', $wp_query->found_posts, 'woocommerce'),
            $wp_query->found_posts
        ) . '</p>';
    }
}


function custom_product_search_form()
{
    $current_category = get_queried_object(); // Get the current category (if on a category page)
    $category_id = (isset($current_category->term_id)) ? $current_category->term_id : '';

    $form = '<form role="search" method="get" class="woocommerce-product-search" action="' . esc_url(home_url('/')) . '">
        <label class="screen-reader-text" for="woocommerce-product-search-field">' . esc_html__('Search for:', 'woocommerce') . '</label>
        <input type="search" id="woocommerce-product-search-field" autocomplete="off" class="search-field" placeholder="' . esc_attr__('Suche nach Produkten', 'woocommerce') . '" value="' . get_search_query() . '" name="s" />
        <input type="hidden" name="post_type" value="product" />';

    // Add category filter if on a category page
    if ($category_id) {
        $form .= '<input type="hidden" name="product_cat" value="' . esc_attr($current_category->slug) . '" />';
    }

    $form .= '<button type="submit" value="' . esc_attr__('Search', 'woocommerce') . '"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
  <path d="M28.7078 27.2928L22.449 21.0353C24.2631 18.8574 25.1676 16.064 24.9746 13.2362C24.7815 10.4084 23.5057 7.76385 21.4125 5.85275C19.3193 3.94164 16.5698 2.9111 13.7362 2.9755C10.9025 3.0399 8.20274 4.19429 6.19851 6.19851C4.19429 8.20274 3.0399 10.9025 2.9755 13.7362C2.9111 16.5698 3.94164 19.3193 5.85275 21.4125C7.76385 23.5057 10.4084 24.7815 13.2362 24.9746C16.064 25.1676 18.8574 24.2631 21.0353 22.449L27.2928 28.7078C27.3857 28.8007 27.496 28.8744 27.6174 28.9247C27.7388 28.975 27.8689 29.0008 28.0003 29.0008C28.1317 29.0008 28.2618 28.975 28.3832 28.9247C28.5046 28.8744 28.6149 28.8007 28.7078 28.7078C28.8007 28.6149 28.8744 28.5046 28.9247 28.3832C28.975 28.2618 29.0008 28.1317 29.0008 28.0003C29.0008 27.8689 28.975 27.7388 28.9247 27.6174C28.8744 27.496 28.8007 27.3857 28.7078 27.2928ZM5.00029 14.0003C5.00029 12.2203 5.52813 10.4802 6.51706 9.00015C7.50599 7.52011 8.9116 6.36656 10.5561 5.68537C12.2007 5.00418 14.0103 4.82595 15.7561 5.17322C17.5019 5.52048 19.1056 6.37765 20.3642 7.63632C21.6229 8.895 22.4801 10.4986 22.8274 12.2445C23.1746 13.9903 22.9964 15.7999 22.3152 17.4444C21.634 19.089 20.4805 20.4946 19.0004 21.4835C17.5204 22.4724 15.7803 23.0003 14.0003 23.0003C11.6141 22.9976 9.3265 22.0486 7.63925 20.3613C5.95199 18.6741 5.00293 16.3864 5.00029 14.0003Z" fill="black"/>
</svg></button>
    </form>';

    return $form;
}



function display_product_categories_in_loop()
{
    global $product;
    $terms = get_the_terms($product->get_id(), 'product_cat');

    if ($terms && !is_wp_error($terms)) {
        // Sort categories so parent appears first
        usort($terms, function ($a, $b) {
            return ($a->parent === 0) ? -1 : 1;
        });

        echo '<div class="product-categories">';
        foreach ($terms as $term) {
            echo '<a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a> ';
        }
        echo '</div>';
    }
}

add_action('woocommerce_shop_loop_item_title', 'display_product_categories_in_loop', 15);



add_filter('woocommerce_loop_add_to_cart_link', 'custom_add_to_cart_button_for_logged_out_users', 10, 3);

function custom_add_to_cart_button_for_logged_out_users($button, $product, $args)
{
    // Check if the user is not logged in
    if (!is_user_logged_in()) {
        // Replace the button with a link to /kontakt and text "Anmelden für Preis"
        $button = sprintf(
            '<a href="%s" class="button">%s</a>',
            esc_url(home_url('/kontakt')), // Link to /kontakt
            esc_html__('Preis sichtbar nach Login', 'your-text-domain') // Button text
        );
    }

    return $button;
}

add_filter('woocommerce_get_price_html', 'hide_price_for_guest_users', 10, 2);

function hide_price_for_guest_users($price, $product) {
    if (!is_user_logged_in() && is_product()) {
        return '<a class="btn" href="' . esc_url(home_url('/kontakt')) . '">Preis sichtbar nach Login</a>';
    }
    return $price;
}

function remove_add_to_cart_for_guest_users() {
    if (!is_user_logged_in() && is_product()) {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    }
}
add_action('wp', 'remove_add_to_cart_for_guest_users');




add_filter('woocommerce_pagination_args', 'custom_woocommerce_pagination_svg_arrows');

function custom_woocommerce_pagination_svg_arrows($args)
{
    // Replace next arrow with SVG
    $args['next_text'] = '<span class="custom-next-arrow">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.8334 10.2333L11.0084 6.40834C10.8523 6.25313 10.6411 6.16602 10.4209 6.16602C10.2008 6.16602 9.98955 6.25313 9.83341 6.40834C9.75531 6.48581 9.69331 6.57798 9.651 6.67953C9.6087 6.78108 9.58691 6.89 9.58691 7.00001C9.58691 7.11002 9.6087 7.21894 9.651 7.32049C9.69331 7.42204 9.75531 7.51421 9.83341 7.59168L13.6667 11.4083C13.7449 11.4858 13.8068 11.578 13.8492 11.6795C13.8915 11.7811 13.9132 11.89 13.9132 12C13.9132 12.11 13.8915 12.2189 13.8492 12.3205C13.8068 12.422 13.7449 12.5142 13.6667 12.5917L9.83341 16.4083C9.67649 16.5642 9.5879 16.7759 9.58712 16.9971C9.58633 17.2182 9.67343 17.4306 9.82925 17.5875C9.98506 17.7444 10.1968 17.833 10.418 17.8338C10.6391 17.8346 10.8515 17.7475 11.0084 17.5917L14.8334 13.7667C15.3016 13.2979 15.5645 12.6625 15.5645 12C15.5645 11.3375 15.3016 10.7021 14.8334 10.2333Z" fill="black"/>
</svg>
    </span>';

    // Replace previous arrow with SVG
    $args['prev_text'] = '<span class="custom-prev-arrow">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.8329 12.5917C10.7548 12.5142 10.6928 12.422 10.6505 12.3205C10.6082 12.2189 10.5864 12.11 10.5864 12C10.5864 11.89 10.6082 11.7811 10.6505 11.6795C10.6928 11.578 10.7548 11.4858 10.8329 11.4083L14.6579 7.59168C14.736 7.51421 14.798 7.42204 14.8403 7.32049C14.8826 7.21894 14.9044 7.11002 14.9044 7.00001C14.9044 6.89 14.8826 6.78108 14.8403 6.67953C14.798 6.57798 14.736 6.48581 14.6579 6.40834C14.5018 6.25313 14.2905 6.16602 14.0704 6.16602C13.8502 6.16602 13.639 6.25313 13.4829 6.40834L9.65789 10.2333C9.18972 10.7021 8.92676 11.3375 8.92676 12C8.92676 12.6625 9.18972 13.2979 9.65789 13.7667L13.4829 17.5917C13.6381 17.7456 13.8476 17.8324 14.0662 17.8333C14.1759 17.834 14.2846 17.813 14.3861 17.7715C14.4877 17.73 14.58 17.6689 14.6579 17.5917C14.736 17.5142 14.798 17.422 14.8403 17.3205C14.8826 17.2189 14.9044 17.11 14.9044 17C14.9044 16.89 14.8826 16.7811 14.8403 16.6795C14.798 16.578 14.736 16.4858 14.6579 16.4083L10.8329 12.5917Z" fill="#000"/>
</svg>
    </span>';

    return $args;
}



add_action('woocommerce_after_shop_loop_item_title', 'hide_prices_for_logged_out_users', 9);

function hide_prices_for_logged_out_users()
{
    // Check if the user is not logged in
    if (!is_user_logged_in()) {
        // Remove the price display
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

        // Display a custom message
        // echo '<p class="login-to-see-price">' . esc_html__('Login to see prices', 'your-text-domain') . '</p>';
    }
}




// Move short description after the title on single product page
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6);

//remove category in summary
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);





// Remove WooCommerce product tabs from default position
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

// Remove the default WooCommerce tabs
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

// Add the tabs after product summary with accordion structure
add_action('woocommerce_after_single_product_summary', 'custom_woocommerce_output_product_tabs', 1);

function custom_woocommerce_output_product_tabs()
{
    global $product;

    // Get the product tabs
    $tabs = apply_filters('woocommerce_product_tabs', array());

    // Get all product attributes
    $attributes = $product->get_attributes();

    if (!empty($tabs) || !empty(get_field('mfn-page-items-seo')) || !empty( $attributes )) {
        echo '<div class="woocommerce-accordion-tabs">';
        if(get_field('mfn-page-items-seo') || !empty( $attributes )){
            echo '<div class="accordion mfn-page-items">
            <div class="accordion-title opened" data-tab-key="technical">Technische Daten</div>
            <div class="accordion-content" id="content-technical">' . get_field('mfn-page-items-seo');
        }

       
        
        if ( ! empty( $attributes ) && empty(get_field('mfn-page-items-seo')) ) {
            $product = wc_get_product(get_the_ID());
            $categories = wp_get_post_terms($product->get_id(), 'product_cat');

            if (!empty($categories)) {
                // Assuming the first category is the main category
                $main_category = $categories[0]->name;
                echo '<p><strong>Artikelgruppe</strong> '. $main_category .'</p>';
            }

            echo '<p><strong>Artikelbezeichnung</strong> '. get_the_title() .'</p>';

            $sku = $product->get_sku();
            if (!empty($sku)) {
                echo '<p class="product-sku"><strong>Artikelcode</strong> ' . esc_html($sku) . '</p>';
            }

            

            foreach ( $attributes as $attribute ) {
                // Skip if this attribute is used for variations
                if ( $attribute->get_variation() ) {
                    continue;
                }
        
                // Get attribute name
                $attribute_name = $attribute->get_name();
                
                // Get attribute values
                if ( $attribute->is_taxonomy() ) {
                    // For taxonomy-based attributes (e.g., color, size)
                    $terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'names' ) );
                    $attribute_value = implode( ', ', $terms );
                } else {
                    // For custom attributes (manually added)
                    $attribute_value = $product->get_attribute( $attribute_name );
                }
                
                // Display attribute name & value
                echo '<p><strong>' . esc_html( wc_attribute_label( $attribute_name ) ) . '</strong> ' . esc_html( $attribute_value ) . '</p>';
            }
        } 
        echo '</div></div>';//technical content

        foreach ($tabs as $key => $tab) {
            // Output the tab title with the "accordion-title" class
            echo '<div class="accordion"><div class="accordion-title" data-tab-key="' . esc_attr($key) . '">';
            echo esc_html($tab['title']);
            echo '</div>';

            // Output the tab content with the "accordion-content" class
            echo '<div class="accordion-content" id="content-' . esc_attr($key) . '">';
            if (isset($tab['callback']) && is_callable($tab['callback'])) {
                // Capture and display the content from the callback
                call_user_func($tab['callback'], $key, $tab);
            }
            echo '</div></div>';
        }//endoforeach 

        ?>
        <?php if( have_rows('manuals') ): ?>
            <div class="accordion"><div class="accordion-title">Dokumente & Downloads</div>
            <div class="accordion-content">
        <?php while( have_rows('manuals') ): the_row(); 
            $file = get_sub_field('file');
            if( $file ):
                $file_url = $file['url'];
                $file_name = $file['filename']; // or use $file['title'] or $file['name']
        ?>
            
                <div class="wp-block-button is-style-btn-arrow">
                <a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($file_url); ?>" download>

                    
                    <?php echo esc_html($file_name); ?>
                </a>
                </div>
        <?php endif; endwhile; ?>
        </div></div>
        <?php endif; ?>

        <?php

        echo '</div>'; // End of woocommerce-accordion-tabs
    }
}


add_filter( 'woocommerce_product_tabs', 'custom_upsells_product_tab' );
function custom_upsells_product_tab( $tabs ) {
    global $product;

    if ( $product->get_upsell_ids() ) {
        $tabs['upsells_tab'] = array(
            'title'    => __( 'Zubehör', 'woocommerce' ),
            'priority' => 50,
            'callback' => 'custom_upsells_product_tab_content'
        );
    }

    return $tabs;
}

function custom_upsells_product_tab_content() {
    global $product;

    $upsells = $product->get_upsell_ids();

    if ( ! empty( $upsells ) ) {
        // Temporarily override global $posts to show upsells
        $args = array(
            'post_type'      => 'product',
            'post__in'       => $upsells,
            'posts_per_page' => -1,
            'orderby'        => 'post__in'
        );

        $upsell_loop = new WP_Query( $args );

        if ( $upsell_loop->have_posts() ) {
            woocommerce_product_loop_start();
            while ( $upsell_loop->have_posts() ) {
                $upsell_loop->the_post();
                wc_get_template_part( 'content', 'product' );
            }
            woocommerce_product_loop_end();
        }

        wp_reset_postdata();
    }
}



/**
 * Remove "Description" Heading Title @ WooCommerce Single Product Tabs
 */
add_filter('woocommerce_product_description_heading', '__return_null');
add_filter('woocommerce_product_additional_information_heading', '__return_null');
add_filter('woocommerce_reviews_title', '__return_null');



// Hook to modify the onsale badge and display percentage discount for variable products
add_filter('woocommerce_sale_flash', 'custom_woocommerce_variable_sale_percentage', 10, 3);

function custom_woocommerce_variable_sale_percentage($html, $post, $product)
{
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



add_filter('woocommerce_output_related_products_args', 'custom_related_products_args');

function custom_related_products_args($args)
{
    // Set the number of related products to 3
    $args['posts_per_page'] = 4; // Number of related products
    $args['columns'] = 4; // Number of columns (optional, adjust as needed)

    return $args;
}









add_filter('woocommerce_get_price_html', 'custom_text_after_price', 10, 2);
function custom_text_after_price($price, $product)
{
    if (is_product()) {
        $price .= ' <small>exkl. 8.1% MwSt.</small>'; // Change the text as needed
    }
    return $price;
}



add_action('woocommerce_single_product_summary', 'custom_sku_below_title', 5);
function custom_sku_below_title()
{
    global $product;
    if ($product->get_sku() && !$product->is_type('variable')) {
        echo '<p class="custom-sku"><b>Artikelcode:</b> ' . $product->get_sku() . '</p>';
    }

    if ($product->is_type('variable')) {
        echo '<div class="woocommerce-variation-sku custom-sku">';
        echo '<span class="label"><strong>' . esc_html__('Artikelcode:', 'woocommerce') . '</strong></span> ';
        echo '<span class="value">' . esc_html($product->get_sku()) . '</span>';
        echo '</div>';
        
        wc_enqueue_js("
            jQuery(function($) {
                $('form.variations_form').on('show_variation', function(event, variation) {
                    $('.woocommerce-variation-sku .value').text(variation.sku);
                }).on('hide_variation', function() {
                    $('.woocommerce-variation-sku .value').text('" . esc_js($product->get_sku()) . "');
                });
            });
        ");
    }
}


// Remove default price location
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

// Add price below title (priority 5)
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 5);




add_action('woocommerce_single_product_summary', 'shipping_single_product_banner', 115);
function shipping_single_product_banner()
{
    ?>
    <div class="shippinig-banner">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M31.6963 14.7225L29.9463 10.3475C29.8173 10.0221 29.5933 9.74302 29.3035 9.54679C29.0136 9.35057 28.6713 9.24627 28.3213 9.2475H23.75V8C23.75 7.80109 23.671 7.61032 23.5303 7.46967C23.3897 7.32902 23.1989 7.25 23 7.25H4C3.53587 7.25 3.09075 7.43437 2.76256 7.76256C2.43437 8.09075 2.25 8.53587 2.25 9V23C2.25 23.4641 2.43437 23.9092 2.76256 24.2374C3.09075 24.5656 3.53587 24.75 4 24.75H6.325C6.49714 25.5977 6.95705 26.3599 7.62681 26.9073C8.29657 27.4547 9.13498 27.7538 10 27.7538C10.865 27.7538 11.7034 27.4547 12.3732 26.9073C13.043 26.3599 13.5029 25.5977 13.675 24.75H20.325C20.4971 25.5977 20.957 26.3599 21.6268 26.9073C22.2966 27.4547 23.135 27.7538 24 27.7538C24.865 27.7538 25.7034 27.4547 26.3732 26.9073C27.043 26.3599 27.5029 25.5977 27.675 24.75H30C30.4641 24.75 30.9092 24.5656 31.2374 24.2374C31.5656 23.9092 31.75 23.4641 31.75 23V15C31.7498 14.9049 31.7316 14.8108 31.6963 14.7225ZM23.75 10.75H28.3225C28.3726 10.75 28.4215 10.7649 28.463 10.793C28.5044 10.8211 28.5365 10.861 28.555 10.9075L29.8925 14.25H23.75V10.75ZM3.75 9C3.75 8.9337 3.77634 8.87011 3.82322 8.82322C3.87011 8.77634 3.9337 8.75 4 8.75H22.25V17.25H3.75V9ZM10 26.25C9.55499 26.25 9.11998 26.118 8.74997 25.8708C8.37996 25.6236 8.09157 25.2722 7.92127 24.861C7.75097 24.4499 7.70642 23.9975 7.79323 23.561C7.88005 23.1246 8.09434 22.7237 8.40901 22.409C8.72368 22.0943 9.12459 21.8801 9.56105 21.7932C9.9975 21.7064 10.4499 21.751 10.861 21.9213C11.2722 22.0916 11.6236 22.38 11.8708 22.75C12.118 23.12 12.25 23.555 12.25 24C12.25 24.5967 12.0129 25.169 11.591 25.591C11.169 26.0129 10.5967 26.25 10 26.25ZM20.325 23.25H13.675C13.5029 22.4023 13.043 21.6401 12.3732 21.0927C11.7034 20.5453 10.865 20.2462 10 20.2462C9.13498 20.2462 8.29657 20.5453 7.62681 21.0927C6.95705 21.6401 6.49714 22.4023 6.325 23.25H4C3.9337 23.25 3.87011 23.2237 3.82322 23.1768C3.77634 23.1299 3.75 23.0663 3.75 23V18.75H22.25V20.685C21.76 20.9443 21.3333 21.3087 21.0005 21.7521C20.6677 22.1956 20.4371 22.707 20.325 23.25ZM24 26.25C23.555 26.25 23.12 26.118 22.75 25.8708C22.38 25.6236 22.0916 25.2722 21.9213 24.861C21.751 24.4499 21.7064 23.9975 21.7932 23.561C21.8801 23.1246 22.0943 22.7237 22.409 22.409C22.7237 22.0943 23.1246 21.8801 23.561 21.7932C23.9975 21.7064 24.4499 21.751 24.861 21.9213C25.2722 22.0916 25.6236 22.38 25.8708 22.75C26.118 23.12 26.25 23.555 26.25 24C26.25 24.5967 26.0129 25.169 25.591 25.591C25.169 26.0129 24.5967 26.25 24 26.25ZM30.25 23C30.25 23.0663 30.2237 23.1299 30.1768 23.1768C30.1299 23.2237 30.0663 23.25 30 23.25H27.675C27.501 22.4035 27.0405 21.6429 26.371 21.0964C25.7016 20.5499 24.8642 20.251 24 20.25C23.9163 20.25 23.8325 20.25 23.75 20.2588V15.75H30.25V23Z"
                fill="#89BE0D" />
        </svg>
        Kostenlose Lieferung innerhalb der Schweiz
    </div>
    <?php
}





add_filter('woocommerce_get_breadcrumb', function ($crumbs) {
    if (!empty($crumbs)) {
        // Get the last breadcrumb
        $last_index = count($crumbs) - 1;

        // Add a class to the last breadcrumb
        $crumbs[$last_index][0] = ' &nbsp; &nbsp; ' . esc_html($crumbs[$last_index][0]);
    }

    return $crumbs;
}, 10, 1);









// Change the "View Cart" button URL to the checkout page after adding to cart
add_filter('woocommerce_add_to_cart_fragments', 'change_view_cart_url_after_add_to_cart');

function change_view_cart_url_after_add_to_cart($fragments) {
    // Check if the cart is not empty
    if (WC()->cart->get_cart_contents_count() > 0) {
        // Update the "View Cart" button URL to the checkout page
        $fragments['a.cart-contents'] = str_replace(
            wc_get_cart_url(),
            wc_get_checkout_url(),
            $fragments['a.cart-contents']
        );
    }
    
    return $fragments;
}


function change_add_to_cart_button_text($text) {
    if (WC()->cart->get_cart_contents_count() > 0) {
        return __('Proceed to Checkout', 'woocommerce');
    }
    return $text; // Default 'Add to Cart' text
}



/**
 * Summary of footer_banner_in_shop
 * Add global pattern as footer banner
 */
function footer_banner_in_shop(){
    $block_pattern_id = 331;

    // Get the post content of the Gutenberg block pattern
    $block_pattern_post = get_post($block_pattern_id);

    if ($block_pattern_post && $block_pattern_post->post_type === 'wp_block') {
        // Access the block content
        $block_content = $block_pattern_post->post_content;

        // Output the block content
        echo apply_filters('the_content', $block_content);
    }
}
add_action('woocommerce_after_single_product', 'footer_banner_in_shop', 1);










add_filter('wc_add_to_cart_message_html', function ($message, $products) {
    $checkout_url = wc_get_checkout_url(); // Get checkout page URL

    // Replace the cart link with the checkout link
    $message = preg_replace('/<a.*?class="button wc-forward".*?>.*?<\/a>/', '<a href="' . esc_url($checkout_url) . '" class="button wc-forward">Zur Kasse</a>', $message);

    return $message;
}, 10, 2);









/**
 * Summary of custom_featured_products_slider
 * [featured_products_slider] - shortcode for featured products slider
 */
function custom_featured_products_slider() {
    if (!function_exists('wc_get_products')) return '';

    ob_start();

 

    // Query WooCommerce Featured Products
    $args = array(
        'limit' => 10, // Adjust the number of products
        'status' => 'publish',
        'featured' => true,
    );
    $products = wc_get_products($args);

    if (!$products) {
        return '<p>No featured products found.</p>';
    }
    ?>

    <div class="custom-swiper-container featured-products-slider">
        <div class="swiperFeatured">
            <div class="swiper-wrapper">
                <?php foreach ($products as $product) : 
                    // Setup global product post for WooCommerce templates
                    $post_object = get_post($product->get_id());
                    if (!$post_object) continue;

                    global $post, $product;
                    $post = $post_object;
                    $product = wc_get_product($post->ID);
                    setup_postdata($post);
                ?>
                    <div class="swiper-slide">
                        <?php wc_get_template_part('content', 'product'); ?>
                    </div>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
           
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.swiperFeatured', {
                slidesPerView: 1.4,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                speed:600,
                autoplay: {
                    delay: 6000, // Auto-slide every 3 seconds
                    disableOnInteraction: false, // Keep autoplay running even after user interaction
                },
                loop:true,
                grabCursor: true, // Enables drag cursor
                freeMode: true, // Allows smooth dragging
                breakpoints: {
                    1600: { slidesPerView: 5.5 },
                    1024: { slidesPerView: 3.7 },
                    480: { slidesPerView: 2.8 },
                }
            });
        });
    </script>

    <?php
    return ob_get_clean();
}

add_shortcode('featured_products_slider', 'custom_featured_products_slider');





function enable_gutenberg_for_products($can_edit, $post_type) {
    if ($post_type === 'product') {
        $can_edit = true;
    }
    return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'enable_gutenberg_for_products', 10, 2);







/**
 * Apply user-specific discount from ACF field to product prices
 */
/*add_filter('woocommerce_product_get_price', 'apply_user_discount_to_price', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'apply_user_discount_to_price', 10, 2);
add_filter('woocommerce_product_variation_get_price', 'apply_user_discount_to_price', 10, 2);
add_filter('woocommerce_product_variation_get_regular_price', 'apply_user_discount_to_price', 10, 2);

function apply_user_discount_to_price($price, $product) {
    // Only for logged-in users
    if (!is_user_logged_in() || empty($price)) {
        return $price;
    }

    // Get current user ID
    $user_id = get_current_user_id();

    // Get discount percentage from ACF user field
    $discount_percentage = get_field('discount', 'user_' . $user_id);

    // If discount exists and is numeric
    if (!empty($discount_percentage) && is_numeric($discount_percentage) && $discount_percentage > 0) {
        // Ensure price is treated as float
        $price_float = (float) $price;
        $discount_float = (float) $discount_percentage;
        
        // Calculate discounted price
        $discounted_price = $price_float * (1 - ($discount_float / 100));
        
        // Return discounted price rounded to 2 decimals
        return round($discounted_price, 2);
    }

    return $price;
}*/

/**
 * Display the original price crossed out and the discounted price
 */
/*add_filter('woocommerce_get_price_html', 'display_discounted_price_html', 10, 2);

function display_discounted_price_html($price_html, $product) {
    // Only for logged-in users
    if (!is_user_logged_in()) {
        return $price_html;
    }

    // Get current user ID
    $user_id = get_current_user_id();

    // Get discount percentage from ACF user field
    $discount_percentage = get_field('discount', 'user_' . $user_id);

    // If discount exists and is numeric
    if (!empty($discount_percentage) && is_numeric($discount_percentage) && $discount_percentage > 0) {
        $regular_price = (float) $product->get_regular_price();
        $discounted_price = (float) $product->get_price();
        
        // Only modify if there's a difference
        if ($regular_price > 0 && $regular_price != $discounted_price) {
            $price_html = '<del>' . wc_price($regular_price) . '</del> <ins>' . wc_price($discounted_price) . '</ins>';
        }
    }

    return $price_html;
}*/



/**
 * Add "per meter" text next to price for products in category ID 111 when language is German
 */
add_filter('woocommerce_get_price_html', 'add_per_meter_to_price', 10, 2);

function add_per_meter_to_price($price, $product) {

    if (has_term(111, 'product_cat', $product->get_id()) || has_term(128, 'product_cat', $product->get_id())) {
        $price .= ' <span class="per-meter">/ pro Meter</span>';
    }

    return $price;
}





/**
 * Completely remove WooCommerce upsell products
 */
function remove_woocommerce_upsells() {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
}
add_action( 'woocommerce_after_single_product_summary', 'remove_woocommerce_upsells', 1 );






/**
 * Summary of add_woocommerce_category_accordion
 * Make accordions in sidebar filters
 */
function add_woocommerce_category_accordion() {
    // Check if this is a product category page
    if (is_product_category() && strpos($_SERVER['REQUEST_URI'], '?') === false ) {
        ?>
        <script>
jQuery(document).ready(function($) {
    // Wait for WooCommerce filters to be fully rendered
    function initAccordion() {
        const $filters = $('.woo-filters .wp-block-woocommerce-attribute-filter');
        
        // Check if filters exist and are fully rendered
        if ($filters.length > 0 && $filters.find('ul').length > 0) {
            // Initialize accordion
            $filters.parent().parent().parent().addClass('initialized');
            
            // Hide all except first
            $('.initialized').not(':first').find('.wp-block-woocommerce-attribute-filter').slideUp(0);
            
            // Add active class to first title
            $('.woo-filters .initialized .wc-blocks-filter-wrapper:not([hidden]):first > h3.wp-block-heading').addClass('active');

            $('.woo-filters .initialized .wc-blocks-filter-wrapper:not([hidden]):first > h3.wp-block-heading').next().show();
            
            // Set up click handlers
            $('.woo-filters .wc-blocks-filter-wrapper > h3.wp-block-heading').on('click', function() {
                $(this).toggleClass('active');
                $(this).next('.wp-block-woocommerce-attribute-filter').stop(true, true).slideToggle(300);
            });
        } else {
            // Try again in 100ms if filters aren't ready
            setTimeout(initAccordion, 100);
        }
    }
    
    // Start checking for filters
    initAccordion();
    
    // Additional fallback in case of dynamic loading
    $(document).on('updated_checkout updated_cart_totals updated_wc_div', initAccordion);
});
        </script>

        <?php
    } elseif( is_product_category() && strpos($_SERVER['REQUEST_URI'], '?') != false ){ ?>
        <script>
        jQuery(document).ready(function($) {

    function initAccordion() {
        const $filters = $('.woo-filters .wp-block-woocommerce-attribute-filter');
        
        // Check if filters exist and are fully rendered
        if ($filters.length > 0 && $filters.find('ul').length > 0) {
            // Initialize accordion
            $filters.parent().parent().parent().addClass('initialized');

            $('.wp-block-woocommerce-attribute-filter').each(function() {
        const $filterBlock = $(this);
        const $prevH3 = $filterBlock.prev('h3');
        
        if ($filterBlock.find('input[type="checkbox"]:checked').length > 0) {
            $filterBlock.show();
            $prevH3.addClass('active');
        } else {
            $filterBlock.hide();
            $prevH3.removeClass('active');
        }
    });

    // Handle changes
    $(document).on('change', '.wp-block-woocommerce-attribute-filter input[type="checkbox"]', function() {
        const $filterBlock = $(this).closest('.wp-block-woocommerce-attribute-filter');
        const $prevH3 = $filterBlock.prev('h3');
        
        if ($(this).is(':checked')) {
            $filterBlock.slideDown();
            $prevH3.addClass('active');
        } else {
            if ($filterBlock.find('input[type="checkbox"]:checked').length === 0) {
                $filterBlock.slideUp();
                $prevH3.removeClass('active');
            }
        }
    });
            
            // Hide all except first
          //  $('.initialized').not(':first').find('.wp-block-woocommerce-attribute-filter').slideUp(0);
            
            // Add active class to first title
          //  $('.woo-filters .initialized .wc-blocks-filter-wrapper:not([hidden]):first > h3.wp-block-heading').addClass('active');

       //     $('.woo-filters .initialized .wc-blocks-filter-wrapper:not([hidden]):first > h3.wp-block-heading').next().show();
            
            // Set up click handlers
            $('.woo-filters .wc-blocks-filter-wrapper > h3.wp-block-heading').on('click', function() {
                $(this).toggleClass('active');
                $(this).next('.wp-block-woocommerce-attribute-filter').stop(true, true).slideToggle(300);
            });
        } else {
            // Try again in 100ms if filters aren't ready
            setTimeout(initAccordion, 100);
        }
    }
    
    // Start checking for filters
    initAccordion();
    
    // Additional fallback in case of dynamic loading
    $(document).on('updated_checkout updated_cart_totals updated_wc_div', initAccordion);


    
});</script>
   <?php }//endif
}
add_action('wp_footer', 'add_woocommerce_category_accordion');




/**
 * Show variable price with min value
 */
add_filter('woocommerce_variable_price_html', 'custom_variable_price_if_price_range', 10, 2);

function custom_variable_price_if_price_range($price, $product) {
    if ($product->is_type('variable')) {
        $variation_prices = $product->get_variation_prices(true); // true = including tax

        $prices = $variation_prices['price']; // array of variation prices
        $unique_prices = array_unique($prices);

        if (count($unique_prices) > 1) {
            $min_price = min($unique_prices);
            $formatted_price = wc_price($min_price);
            return 'ab ' . $formatted_price;
        }
    }

    return $price; // fallback to default price output
}



add_filter('woocommerce_get_availability', 'custom_out_of_stock_message', 10, 2);

function custom_out_of_stock_message($availability, $product) {
    if (!$product->is_in_stock()) {
        $availability['availability'] = 'Verfügbarkeit auf Anfrage – bitte kontaktieren Sie uns: 044 203 96 97';
    }
    return $availability;
}
