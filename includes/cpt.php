<?php

function create_registrations_cpt() {
  $args = array(
      'public' => false, // Not publicly queryable
      'label'  => 'Registrations',
      'supports' => array('title'),
      'show_ui' => true, // Show in the admin dashboard
      'show_in_menu' => true, // Show in the admin menu
      'capability_type' => 'post',
      'has_archive' => false, // No archive page
  );
  register_post_type('registrations', $args);
}
add_action('init', 'create_registrations_cpt');





// Add the meta box to the CPT "registrations" post type
function add_create_customer_button() {
  add_meta_box(
      'create_customer_button', // ID of the meta box
      'Create WooCommerce Customer', // Title of the meta box
      'display_create_customer_button', // Callback function to display the button
      'registrations', // Post type (CPT)
      'side', // Context
      'high' // Priority
  );
}
add_action('add_meta_boxes', 'add_create_customer_button');

// Display the button in the meta box
function display_create_customer_button($post) {
  // Check if this post has the necessary fields
  $first_name = get_field('first_name', $post->ID);
  $last_name = get_field('last_name', $post->ID);
  $email = get_field('email', $post->ID);
  $registration_status = get_post_meta($post->ID, '_registration_status', true); // Check if customer is created

  // If fields are missing, we don't show the button
  if (!$first_name || !$last_name || !$email) {
      echo '<p>Missing required data for customer creation.</p>';
      return;
  }

  // If registration is complete, hide the button
  if ($registration_status == 'registered') {
      echo '<p style="color: green;">Customer Registered</p>';
  } else {
      // Display the button
      echo '<button type="button" class="button" id="create_customer_btn">Create WooCommerce Customer</button>';
      echo '<script type="text/javascript">
      jQuery(document).ready(function($) {
          $("#create_customer_btn").on("click", function() {
              var post_id = ' . $post->ID . ';
              var first_name = "' . esc_js($first_name) . '";
              var last_name = "' . esc_js($last_name) . '";
              var email = "' . esc_js($email) . '";
              
              $.post(ajaxurl, {
                  action: "create_woocommerce_customer",
                  post_id: post_id,
                  first_name: first_name,
                  last_name: last_name,
                  email: email,
                  password: "worldoflights2025"
              }, function(response) {
                  // Check if the response is a success or error
                  if (response.success) {
                      alert("User successfully registered!");
                  } else {
                      alert("Error: " + response.data.message);
                  }
              });
          });
      });
  </script>';
  
  }
}

// Handle the customer creation via AJAX
add_action('wp_ajax_create_woocommerce_customer', 'create_woocommerce_customer_from_registration');
function create_woocommerce_customer_from_registration() {
    // Make sure it's a valid request
    if (!isset($_POST['post_id']) || !isset($_POST['first_name']) || !isset($_POST['last_name']) || !isset($_POST['email'])) {
        wp_send_json_error(array('message' => 'Missing data.'));
    }

    // Get the data
    $post_id = intval($_POST['post_id']);
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $password = 'worldoflights2025'; // Default password

    // Get additional ACF fields for the billing details
    $company = get_field('company', $post_id);
    $phone = get_field('phone', $post_id);
    $street = get_field('street', $post_id);
    $zip_code = get_field('zip_code', $post_id);
    $city = get_field('city', $post_id);
    // Hardcoding country as Switzerland
    $country = 'CH'; // Switzerland country code

    // Check if the email already exists
    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'Email already exists.'));
    }

    // Create the customer
    $user_id = wc_create_new_customer($email, $email, $password);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => 'Error creating customer: ' . $user_id->get_error_message()));
    }

    // Update the customer with additional info
    wp_update_user(array(
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
    ));

    // Update billing information in WooCommerce customer profile
    update_user_meta($user_id, 'billing_first_name', $first_name);
    update_user_meta($user_id, 'billing_last_name', $last_name);
    update_user_meta($user_id, 'billing_company', $company);
    update_user_meta($user_id, 'billing_email', $email);
    update_user_meta($user_id, 'billing_phone', $phone);
    update_user_meta($user_id, 'billing_address_1', $street);
    update_user_meta($user_id, 'billing_postcode', $zip_code);
    update_user_meta($user_id, 'billing_city', $city);
    update_user_meta($user_id, 'billing_country', $country); // Set country to Switzerland

    // Optionally, update ACF fields or custom post meta if needed (e.g., link customer to registration)
    update_post_meta($post_id, '_customer_user_id', $user_id);
    update_post_meta($post_id, '_registration_status', 'registered'); // Mark as registered

    wp_send_json_success(array('message' => 'Customer created successfully.'));
}



// Add custom column for registration status
function add_registration_status_column($columns) {
  $columns['registration_status'] = 'Registration Status';
  return $columns;
}
add_filter('manage_registrations_posts_columns', 'add_registration_status_column');

// Display registration status in the custom column
function display_registration_status_column($column, $post_id) {
  if ($column == 'registration_status') {
      $registration_status = get_post_meta($post_id, '_registration_status', true);
      if ($registration_status == 'registered') {
          echo '<span style="color: green;">Registered</span>';
      } else {
          echo '<span style="color: red;">Pending registration</span>';
      }
  }
}
add_action('manage_registrations_posts_custom_column', 'display_registration_status_column', 10, 2);

// Make the "Registration Status" column sortable
function set_registration_status_column_sortable($columns) {
  $columns['registration_status'] = 'registration_status';
  return $columns;
}
add_filter('manage_edit-registrations_sortable_columns', 'set_registration_status_column_sortable');



/**
 * Summary of create_industry_taxonomy
 * CPT reference and custom taxonomy industry
 */

 function create_reference_cpt() {
    $labels = array(
        'name'                  => _x('Referenzen', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('Referenz', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => _x('Referenzen', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Referenz', 'Add New on Toolbar', 'textdomain'),
        'archives'              => __('Referenz-Archiv', 'textdomain'),
        'attributes'            => __('Referenz-Attribute', 'textdomain'),
        'parent_item_colon'     => __('Übergeordnete Referenz:', 'textdomain'),
        'all_items'             => __('Alle Referenzen', 'textdomain'),
        'add_new_item'          => __('Neue Referenz hinzufügen', 'textdomain'),
        'add_new'               => __('Neu hinzufügen', 'textdomain'),
        'new_item'              => __('Neue Referenz', 'textdomain'),
        'edit_item'             => __('Referenz bearbeiten', 'textdomain'),
        'update_item'           => __('Referenz aktualisieren', 'textdomain'),
        'view_item'             => __('Referenz ansehen', 'textdomain'),
        'view_items'            => __('Referenzen ansehen', 'textdomain'),
        'search_items'          => __('Referenz suchen', 'textdomain'),
        'not_found'             => __('Nicht gefunden', 'textdomain'),
        'not_found_in_trash'    => __('Nicht im Papierkorb gefunden', 'textdomain'),
        'featured_image'        => __('Beitragsbild', 'textdomain'),
        'set_featured_image'    => __('Beitragsbild festlegen', 'textdomain'),
        'remove_featured_image' => __('Beitragsbild entfernen', 'textdomain'),
        'use_featured_image'    => __('Als Beitragsbild verwenden', 'textdomain'),
        'insert_into_item'      => __('In Referenz einfügen', 'textdomain'),
        'uploaded_to_this_item' => __('Zu dieser Referenz hochgeladen', 'textdomain'),
        'items_list'            => __('Referenzen-Liste', 'textdomain'),
        'items_list_navigation' => __('Navigation der Referenzen-Liste', 'textdomain'),
        'filter_items_list'     => __('Referenzen-Liste filtern', 'textdomain'),
    );

    $args = array(
        'label'                 => __('Referenz', 'textdomain'),
        'description'           => __('Post-Typ für Referenzen', 'textdomain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'taxonomies'            => array('industry'),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'hierarchical'          => false,
        'exclude_from_search'   => false,
        'show_in_rest'          => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'referenzen', 'with_front' => false),
    );

    register_post_type('reference', $args);
}
add_action('init', 'create_reference_cpt', 0);




/**
 * Adds a "Menu Order" column to the admin list table for the 'reference' post type.
 *
 * @param array $columns An array of column names.
 * @return array Modified array of column names.
 */
function add_reference_menu_order_column( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $title ) {
        $new_columns[ $key ] = $title;
        if ( 'title' === $key ) {
            $new_columns['menu_order'] = __( 'Menu Order', 'your-text-domain' );
        }
    }
    return $new_columns;
}
add_filter( 'manage_reference_posts_columns', 'add_reference_menu_order_column' );

/**
 * Populates the "Menu Order" column with the menu order value for each 'reference' post.
 *
 * @param string $column The name of the column being displayed.
 * @param int    $post_id The ID of the current post.
 */
function display_reference_menu_order_column( $column, $post_id ) {
    if ( 'menu_order' === $column ) {
        $post = get_post( $post_id );
        echo esc_html( $post->menu_order );
    }
}
add_action( 'manage_reference_posts_custom_column', 'display_reference_menu_order_column', 10, 2 );

/**
 * Makes the "Menu Order" column sortable in the admin list table for the 'reference' post type.
 *
 * @param array $sortable_columns An array of sortable column names.
 * @return array Modified array of sortable column names.
 */
function make_reference_menu_order_column_sortable( $sortable_columns ) {
    $sortable_columns['menu_order'] = 'menu_order';
    return $sortable_columns;
}
add_filter( 'manage_edit-reference_sortable_columns', 'make_reference_menu_order_column_sortable' );





function create_industry_taxonomy() {
    $labels = array(
        'name'                       => _x('Branchen', 'Taxonomy General Name', 'textdomain'),
        'singular_name'              => _x('Branche', 'Taxonomy Singular Name', 'textdomain'),
        'menu_name'                  => __('Branche', 'textdomain'),
        'all_items'                  => __('Alle Branchen', 'textdomain'),
        'parent_item'                => __('Übergeordnete Branche', 'textdomain'),
        'parent_item_colon'          => __('Übergeordnete Branche:', 'textdomain'),
        'new_item_name'              => __('Neuer Branchenname', 'textdomain'),
        'add_new_item'               => __('Neue Branche hinzufügen', 'textdomain'),
        'edit_item'                  => __('Branche bearbeiten', 'textdomain'),
        'update_item'                => __('Branche aktualisieren', 'textdomain'),
        'view_item'                  => __('Branche ansehen', 'textdomain'),
        'separate_items_with_commas' => __('Branchen mit Kommas trennen', 'textdomain'),
        'add_or_remove_items'        => __('Branchen hinzufügen oder entfernen', 'textdomain'),
        'choose_from_most_used'      => __('Wähle aus den meistgenutzten', 'textdomain'),
        'popular_items'              => __('Beliebte Branchen', 'textdomain'),
        'search_items'               => __('Branchen suchen', 'textdomain'),
        'not_found'                  => __('Nicht gefunden', 'textdomain'),
        'no_terms'                   => __('Keine Branchen', 'textdomain'),
        'items_list'                 => __('Branchenliste', 'textdomain'),
        'items_list_navigation'      => __('Navigation der Branchenliste', 'textdomain'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => false, // Archivseite deaktivieren
        'query_var'         => false, // Keine direkten Abfragen über ?industry= möglich
    );

    register_taxonomy('industry', array('reference'), $args);
}
add_action('init', 'create_industry_taxonomy', 0);




/***
 * Shortcode to show reference cpt with filters [reference_list]
 */
function reference_shortcode($atts) { 
    ob_start();

    // Output the filter buttons
    echo '<div id="industry-filter">';
    echo '<button class="industry-button active" data-industry="">Alle Branchen</button>';
    $industries = get_terms(array(
        'taxonomy' => 'industry',
        'hide_empty' => true,
    ));

    foreach ($industries as $industry) {

            echo '<button class="industry-button" data-industry="' . esc_attr($industry->slug) . '">' . esc_html($industry->name) . '</button>';
        
        
    }
    echo '</div>';

    // Output the container for the reference posts
    echo '<div id="reference-list" data-industry="">';
    reference_ajax_handler(); // Initial load of posts
    echo '</div>';

    // Load More button
    echo '<div class="text-center loadmore-wrap"><button class="btn" id="load-more" data-page="1" style="display:none;">Mehr laden</button></div>';

    return ob_get_clean();
}
add_shortcode('reference_list', 'reference_shortcode');

function filter_references_ajax() {
    $industry = isset($_POST['industry']) ? sanitize_text_field($_POST['industry']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    ob_start();
    $has_more = reference_ajax_handler($industry, $page);
    $html = ob_get_clean();

    wp_send_json(array(
        'html' => $html,
        'has_more' => $has_more,
    ));
}
add_action('wp_ajax_filter_references', 'filter_references_ajax');
add_action('wp_ajax_nopriv_filter_references', 'filter_references_ajax');

function reference_enqueue_scripts() {
    wp_enqueue_script('reference-ajax', get_template_directory_uri() . '/assets/js/references.js', array('jquery'), null, true);
    wp_localize_script('reference-ajax', 'ajax_params', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'reference_enqueue_scripts');

function reference_ajax_handler($industry = '', $page = 1) {
    $args = array(
        'post_type'      => 'reference',
        'posts_per_page' => 9,
        'post_status'    => 'publish',
        'paged'          => $page,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );

    if (!empty($industry)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'industry',
                'field'    => 'slug',
                'terms'    => $industry,
            ),
        );
    }

    $query = new WP_Query($args);
    $has_more = $query->max_num_pages > $page;

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo '<a href="' . get_the_permalink() . '" class="reference-item">';
            if (has_post_thumbnail()) {
                echo get_the_post_thumbnail(get_the_ID(), 'medium');
            }
            echo '<h2>' . get_the_title() . '</h2>';
            echo '</a>';
        }
        wp_reset_postdata();
    } else {
        echo '<p>Keine Referenzen gefunden.</p>';
    }

    return $has_more;
}
