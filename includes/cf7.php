<?php

//remove p from cf7
add_filter('wpcf7_autop_or_not', '__return_false');

// turn off cf7 scripts where you don't need them
add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');
add_action('wp_head', function () {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'contact-form-7')) {
        wpcf7_enqueue_scripts();
        wpcf7_enqueue_styles();
    } else {
        wp_dequeue_script('google-recaptcha');
        wp_deregister_script('google-recaptcha');
    }
});



/**
 * Insert data into cpt Registrations if subject selected is I want to register
 */


 add_action('wpcf7_mail_sent', 'save_registration_data_to_cpt');

 function save_registration_data_to_cpt($contact_form) {
     // Check if the form is the correct one
     if ($contact_form->id() === 6 || $contact_form->id() === 180) {
         // Get the form submission data
         $submission = WPCF7_Submission::get_instance();
         if ($submission) {
             $posted_data = $submission->get_posted_data();
             
             // Handle select field value (since it's an array)
             if (isset($posted_data['subject'][0]) && trim(strtolower($posted_data['subject'][0])) === 'registrierung onlineshop') {
                 // Get first name and last name for post title (remove any extra spaces)
                 $first_name = isset($posted_data['first-name']) ? sanitize_text_field($posted_data['first-name']) : '';
                 $last_name = isset($posted_data['last-name']) ? sanitize_text_field($posted_data['last-name']) : '';
                 // Create post title
                 $post_title = 'Registration - ' . $first_name . ' ' . $last_name;
 
                 // Create a new Registrations post
                 $post_id = wp_insert_post(array(
                     'post_title' => $post_title,
                     'post_type' => 'registrations',
                     'post_status' => 'publish',
                 ));
 
                 // Save the form data to ACF fields
                 if ($post_id) {
                     update_field('street', sanitize_text_field($posted_data['street']), $post_id);
                     update_field('zip_code', sanitize_text_field($posted_data['zip-code']), $post_id);
                     update_field('city', sanitize_text_field($posted_data['city']), $post_id);
                     update_field('country', sanitize_text_field($posted_data['country']), $post_id);
 
                     // Update new ACF fields
                     update_field('first_name', $first_name, $post_id);
                     update_field('last_name', $last_name, $post_id);
                     update_field('company', isset($posted_data['company']) ? sanitize_text_field($posted_data['company']) : '', $post_id);
                     update_field('email', isset($posted_data['your-email']) ? sanitize_email($posted_data['your-email']) : '', $post_id);
                     update_field('phone', isset($posted_data['phone']) ? sanitize_text_field($posted_data['phone']) : '', $post_id);
                     update_field('message', isset($posted_data['your-message']) ? sanitize_text_field($posted_data['your-message']) : '', $post_id);
                 }
             }
         }
     }
 }
 


