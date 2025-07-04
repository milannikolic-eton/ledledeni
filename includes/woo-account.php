<?php


add_filter('woocommerce_account_menu_items', 'remove_items_from_my_account', 999);

function remove_items_from_my_account($menu_items) {
    unset($menu_items['dashboard']); // Remove Dashboard

    // Remove the "Downloads" item
    unset($menu_items['downloads']);
    
    // Remove the "Edit Account" item
    unset($menu_items['edit-account']);

    return $menu_items;
}


// Redirect users from My Account Dashboard to Orders
/*add_action('template_redirect', function () {
    if (is_account_page()) {
        global $wp;
        $current_endpoint = !empty($wp->request) ? explode('/', $wp->request)[1] : '';

        // Redirect only if no specific endpoint is set (i.e., user is on /my-account/)
        if (empty($current_endpoint)) {
            wp_redirect(wc_get_endpoint_url('orders'));
            exit;
        }
    }
});*/

add_action('template_redirect', function () {
    if (is_account_page()) {
        global $wp;
        $request_parts = explode('/', $wp->request);
        $current_endpoint = isset($request_parts[1]) ? $request_parts[1] : '';

        // Redirect only if no specific endpoint is set (i.e., user is on /my-account/)
        if (empty($current_endpoint)) {
            wp_redirect(wc_get_endpoint_url('orders'));
            exit;
        }
    }
});




add_action('woocommerce_before_account_navigation', function () {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $billing_first_name = get_user_meta($current_user->ID, 'billing_first_name', true);

        // Use the billing first name, or fallback to the standard first name
        $display_name = !empty($billing_first_name) ? $billing_first_name : $current_user->first_name;
        $discount = get_field('discount', 'user_' . $current_user->ID);
        
        // German translated text
        echo '<div class="my-account-greeting">';
        echo '<h2> Hallo ' . esc_html($display_name) . ',</h2>';
        echo '<div>Hier können Sie Ihre Bestellungen, Rechnungs- und Lieferadressen verwalten.</div>';
        if($discount){
            echo '<div class="your-discount">Ihr genehmigter Rabatt beträgt: <b>' . $discount . '%</b></div>';
        }
        echo '</div>';
    }
});








// Add a custom column to the WooCommerce Customers list
add_filter('manage_users_columns', function ($columns) {
    $columns['discount'] = __('Discount', 'your-textdomain');
    return $columns;
});

// Populate the custom column with the ACF "discount" field value
add_action('manage_users_custom_column', function ($value, $column_name, $user_id) {
    if ($column_name === 'discount') {
        $discount = get_field('discount', 'user_' . $user_id); // Get ACF field
        return $discount ? esc_html($discount) : '-'; // Display discount or '-'
    }
    return $value;
}, 10, 3);
