<?php
/*
 *  Author: EtonDigital
 *  Url: https://www.etondigital.com/
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
    External Modules/Files
\*------------------------------------*/
require_once(get_template_directory() . '/includes/cpt.php');
require_once(get_template_directory() . '/includes/optimize.php');
require_once(get_template_directory() . '/includes/gutenberg-extended.php');
require_once(get_template_directory() . '/includes/cf7.php');

require_once( get_template_directory() . '/includes/woo-products.php' );
require_once( get_template_directory() . '/includes/woo-account.php' );
require_once( get_template_directory() . '/includes/woo-checkout.php' );

/*------------------------------------*\
    Theme Support
\*------------------------------------*/

/*function mytheme_gutenberg_colors() {
    // Fetch CSS variables via PHP
    echo '<style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
    </style>';

    // Add theme support for custom colors in Gutenberg
    add_theme_support( 'editor-color-palette', [
        [
            'name'  => __( 'Primary', 'mytheme' ),
            'slug'  => 'primary',
            'color' => 'var(--primary-color)',
        ],
        [
            'name'  => __( 'Secondary', 'mytheme' ),
            'slug'  => 'secondary',
            'color' => 'var(--secondary-color)',
        ],
        [
            'name'  => __( 'Accent', 'mytheme' ),
            'slug'  => 'accent',
            'color' => 'var(--accent-color)',
        ],
        [
            'name'  => __( 'Dark', 'mytheme' ),
            'slug'  => 'dark',
            'color' => 'var(--dark-color)',
        ],
        [
            'name'  => __( 'Light', 'mytheme' ),
            'slug'  => 'light',
            'color' => 'var(--light-color)',
        ],
    ] );
}
add_action( 'after_setup_theme', 'mytheme_gutenberg_colors' );*/

if (function_exists('add_theme_support')) {
    // Add support for editor color palette.
    add_theme_support('editor-color-palette', array(
        array(
            'name' => __('Primary', 'mytheme'),
            'slug' => 'primary',
            'color' => '#0073aa',
        ),
        array(
            'name' => __('Secondary', 'mytheme'),
            'slug' => 'secondary',
            'color' => '#005177',
        ),
        array(
            'name' => __('Accent', 'mytheme'),
            'slug' => 'accent',
            'color' => '#f78da7',
        ),
        array(
            'name' => __('Light Gray', 'mytheme'),
            'slug' => 'light-gray',
            'color' => '#f0f0f0',
        ),
        array(
            'name' => __('Dark Gray', 'mytheme'),
            'slug' => 'dark-gray',
            'color' => '#333333',
        ),
    ));
    // Add Menu Support
    add_theme_support('menus');

    add_theme_support('align-wide');

    add_theme_support('title-tag');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('grid-item', 400, 290, true);
    //add_image_size('rectangle', 860, 430, true);
    //  add_image_size('hero', 1920, 700, true);
    // add_image_size('square', 430, 430, true);

    // Enables post and comment RSS feed links to head
    // add_theme_support('automatic-feed-links');

    add_theme_support(
        'custom-logo',
        array(
            'height' => 36,
            'width' => 220,
            'flex-width' => true,
            'flex-height' => true,
        )
    );


    // Add WooCommerce support
    add_theme_support( 'woocommerce' );

    // Add WooCommerce product gallery features (zoom, lightbox, and gallery slider)
 //   add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}






add_filter('image_size_names_choose', 'my_custom_sizes');

function my_custom_sizes($sizes)
{
    return array_merge($sizes, array(
        'grid-item' => __('Grid item'),
        'square' => __('Medium Square'),
        'rectangle' => __('Medium Rectangle')
    ));
}





function change_cover_block_thumbnail($block_content, $block)
{
    // Only apply to the Cover block
    if (isset($block['blockName']) && $block['blockName'] === 'core/cover') {
        // Get the post's featured image ID
        $featured_image_id = get_post_thumbnail_id(get_the_ID());

        if ($featured_image_id) {
            // Check if the "hero" thumbnail size exists
            $hero_thumbnail = wp_get_attachment_image_src($featured_image_id, 'hero');

            if ($hero_thumbnail) {
                // Replace the original image with the "hero" thumbnail in the block content
                $full_image_url = wp_get_attachment_url($featured_image_id);
                $block_content = str_replace($full_image_url, $hero_thumbnail[0], $block_content);
            }
        }
    }

    return $block_content;
}
add_filter('render_block', 'change_cover_block_thumbnail', 10, 2);


/*------------------------------------*\
    Enqueue scripts
\*------------------------------------*/
function theme_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

        wp_register_script('themescripts', get_template_directory_uri() . '/assets/js/general.js', array('jquery'), '1.0.0'); // Custom scripts
        wp_enqueue_script('themescripts'); // Enqueue it!


        wp_register_style('main-theme-css', get_template_directory_uri() . '/style.min.css', array(), '1.1', 'all');
        wp_enqueue_style('main-theme-css'); // Enqueue it!

        if (has_block('acf/gallery-block') || has_block('acf/posts-slider') || has_block('acf/testimonials') || is_single() ) {
            wp_register_script('swiper', get_template_directory_uri() . '/assets/js/swiper.min.js', '', '1.0.0');
            wp_enqueue_script('swiper');

            wp_register_style('swiper-css', get_template_directory_uri() . '/assets/css/swiper.min.css', array(), '1.0', 'all');
            wp_enqueue_style('swiper-css'); // Enqueue it!



        }

        if( is_front_page() || is_page() && wp_get_post_parent_id(get_the_ID()) ){
            wp_enqueue_script('custom-swiper', get_template_directory_uri() . '/assets/js/custom-swiper.js', ['swiper'], '1.0', true);
        }


        if (has_block('acf/videos') ) {
            wp_register_script('venobox', get_template_directory_uri() . '/assets/js/venobox.min.js', '', '1.0.0');
            wp_enqueue_script('venobox');

            wp_register_style('venobox-css', get_template_directory_uri() . '/assets/css/venobox.min.css', array(), '1.0', 'all');
            wp_enqueue_style('venobox-css'); // Enqueue it!
        }


        // Enqueue Lenis script from CDN with defer attribute.
        wp_enqueue_script(
            'lenis', // Handle for the script.
            'https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.10/bundled/lenis.min.js', // Script URL.
            array(), // Dependencies (none in this case).
            null, // Version (null will use the current version).
            true // Load in the footer.
        );

        // Add the defer attribute.
        add_filter('script_loader_tag', function ($tag, $handle) {
            if ('lenis' === $handle) {
                return str_replace(' src', ' defer="defer" src', $tag);
            }
            return $tag;
        }, 10, 2);


    }
}
add_action('wp_enqueue_scripts', 'theme_scripts');


/*------------------------------------*\
    CONVERT THUMBNAILS TO WEBP
\*------------------------------------*/
add_filter('image_editor_output_format', function ($formats) {
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png'] = 'image/webp';

    return $formats;
});



add_filter('upload_mimes', 'rudr_svg_upload_mimes');

function rudr_svg_upload_mimes($mimes)
{

    // it is recommended to uncomment these lines for security reasons
    // if( ! current_user_can( 'administrator' ) ) {
    // 	return $mimes;
    // }

    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;

}

add_filter('wp_check_filetype_and_ext', 'rudr_svg_filetype_ext', 10, 5);

function rudr_svg_filetype_ext($data, $file, $filename, $mimes, $real_mime)
{

    if (!$data['type']) {

        $filetype = wp_check_filetype($filename, $mimes);
        $type = $filetype['type'];
        $ext = $filetype['ext'];

        if ($type && 0 === strpos($type, 'image/') && 'svg' !== $ext) {
            $ext = false;
            $type = false;
        }

        $data = array(
            'ext' => $ext,
            'type' => $type,
            'proper_filename' => $data['proper_filename'],
        );

    }

    return $data;

}
/*------------------------------------*\
    NAVIGATION
\*------------------------------------*/
function header_nav()
{
    wp_nav_menu(
        array(
            'theme_location' => 'header-menu',
            'menu' => '',
            'container' => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id' => '',
            'menu_class' => 'menu',
            'menu_id' => '',
            'echo' => true,
            'fallback_cb' => 'wp_page_menu',
            'before' => '',
            'after' => '',
            'link_before' => '',
            'link_after' => '',
            'items_wrap' => '<ul>%3$s</ul>',
            'depth' => 0,
            'walker' => ''
        )
    );
}


// Register Navigation
function register_theme_menus()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'greentheme'), // Main Navigation
        'secondary-menu' => __('Secondary Menu', 'greentheme'), // Secondary menu
        'footer-menu' => __('Footer Menu', 'greentheme'), // Footer menu
        'footer-menu2' => __('Footer Bottom Menu', 'greentheme'), // Footer bottom Navigation

    ));
}
add_action('init', 'register_theme_menus');

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation


/* * * Walker for the main menu * */
add_filter('walker_nav_menu_start_el', 'add_arrow', 10, 4);
function add_arrow($output, $item, $depth, $args)
{
    //Only add class to 'top level' items on the 'primary' menu. 
    if ('header-menu' == $args->theme_location && ($depth === 0 || $depth === 1) || 'secondary-menu' == $args->theme_location && $depth === 0) {
        if (in_array("menu-item-has-children", $item->classes)) {
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M19.8974 9.39739L12.3974 16.8974C12.2919 17.0027 12.1489 17.0619 11.9999 17.0619C11.8508 17.0619 11.7079 17.0027 11.6024 16.8974L4.10238 9.39739C4.00302 9.29075 3.94893 9.14972 3.9515 9.00399C3.95407 8.85827 4.01311 8.71923 4.11617 8.61617C4.21923 8.51311 4.35827 8.45407 4.50399 8.4515C4.64972 8.44893 4.79075 8.50303 4.89739 8.60239L11.9999 15.7039L19.1024 8.60239C19.209 8.50303 19.3501 8.44893 19.4958 8.4515C19.6415 8.45407 19.7805 8.51311 19.8836 8.61617C19.9867 8.71923 20.0457 8.85827 20.0483 9.00399C20.0508 9.14972 19.9967 9.29075 19.8974 9.39739Z" fill="black"/>
</svg>
';
        }
    }
    return $output;
}



/*
function add_woocommerce_categories_to_menu($items, $args) {
    if ($args->theme_location === 'header-menu') { // Adjust this to match your menu location
        $menu_items = wp_get_nav_menu_items($args->menu);

        foreach ($menu_items as $menu_item) {
            if ($menu_item->title === 'Produkte') { // Check for "Produkte"
                $product_categories = get_terms([
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                ]);

                if (!empty($product_categories)) {
                    foreach ($product_categories as $category) {
                        $items .= '<li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">';
                        $items .= '<a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a>';
                        $items .= '</li>';
                    }
                }
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_woocommerce_categories_to_menu', 10, 2);*/
class Custom_WooCommerce_Menu_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // Output the default menu item
        parent::start_el($output, $item, $depth, $args, $id);

        // Check if the menu item title is "Produkte"
        if ($item->title == 'Produkte' && $depth == 0) {
            // Get all WooCommerce product categories
            $product_categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
            ));

            if (!empty($product_categories) && !is_wp_error($product_categories)) {
                $output .= '<ul class="sub-menu">';
                $output .= '<span class="produkte">Produkte</span><div class="wp-block-button is-style-btn-arrow">
                <a class="wp-block-button__link wp-element-button" 
                href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">Alle Produkte anzeigen</a></div>';
                foreach ($product_categories as $category) {
                    $output .= '<li class="menu-item">';
                    $output .= '<a href="' . get_term_link($category) . '">' . $category->name . '</a>';
                    $output .= '</li>';
                }
                $output .= '</ul>';
            }
        }
    }
}


/*------------------------------------*\
    EXTEND NAV WAKLER FOR MOBILE MEGA MENU
\*------------------------------------*/
class dynamicSubMenu extends Walker_Nav_Menu
{
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class='sub-menu-wrap'><ul class='sub-menu'>\n";
    }
    function end_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul></div>\n";
    }


} //class


/*------------------------------------*\
    WIDGETS
\*------------------------------------*/
if (function_exists('register_sidebar')) {
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Footer top', 'greentheme'),
        'description' => __('Description for this widget-area...', 'greentheme'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

if (function_exists('register_sidebar')) {
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Footer Bottom', 'greentheme'),
        'description' => __('Description for this widget-area...', 'greentheme'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

if (function_exists('register_sidebar')) {
    // Define Sidebar Shop Sidebar
    register_sidebar(array(
        'name' => __('Shop Sidebar', 'greentheme'),
        'description' => __('Shop Sidebar with filters', 'greentheme'),
        'id' => 'widget-area-5',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Add page slug to body class
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}
add_filter('body_class', 'add_slug_to_body_class');





/*------------------------------------*\
    COMMENTS
\*------------------------------------*/
// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function greenthemecomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?>     <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?>
        id="comment-<?php comment_ID() ?>">
        <?php if ('div' != $args['style']): ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php endif; ?>
            <div class="comment-author vcard">
                <?php if ($args['avatar_size'] != 0)
                    echo get_avatar($comment, $args['180']); ?>
                <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
            </div>
            <?php if ($comment->comment_approved == '0'): ?>
                <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
                <br />
            <?php endif; ?>

            <div class="comment-meta commentmetadata"><a
                    href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
                    <?php
                    printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'), '  ', '');
                       ?>
            </div>

            <?php comment_text() ?>

            <div class="reply">
                <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>
            <?php if ('div' != $args['style']): ?>
            </div>
        <?php endif; ?>
    <?php }



// hide admin header for everyone except the admins
if (!current_user_can('manage_options')) {
    add_filter('show_admin_bar', '__return_false');
}


/*------------------------------------*\
    Add acf options page
\*------------------------------------*/
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Theme options',
        'menu_title' => 'Theme options',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'update_core',
        'redirect' => false
    ));

}

//custom gutenberg blocks
add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types()
{

    // Check function exists.
    if (function_exists('acf_register_block_type')) {

        // register logos slider block.
        acf_register_block_type(array(
            'name' => 'posts_slider',
            'title' => __('Articles slider'),
            'description' => __('Latest posts block'),
            'render_template' => 'template-parts/blocks/articles-slider.php',
            'category' => 'formatting',
            'icon' => 'admin-comments',
            'keywords' => array('Articles slider', 'sails'),
        ));
    }


    // Check function exists.
    if (function_exists('acf_register_block_type')) {

        // register logos slider block.
        acf_register_block_type(array(
            'name' => 'accordions',
            'title' => __('Accordions'),
            'description' => __('Accordions section'),
            'render_template' => 'template-parts/blocks/accordions.php',
            'category' => 'formatting',
            'icon' => 'admin-comments',
            'keywords' => array('Accordions', 'ledledeni'),
        ));
    }


    // Check function exists.
    if (function_exists('acf_register_block_type')) {

        // register logos slider block.
        acf_register_block_type(array(
            'name' => 'testimonials',
            'title' => __('Testimonials'),
            'description' => __('Testimonials section'),
            'render_template' => 'template-parts/blocks/testimonials.php',
            'category' => 'formatting',
            'icon' => 'admin-comments',
            'keywords' => array('Testimonials', 'ledledeni'),
        ));
    }



}


/*------------------------------------*\
    CF7
\*------------------------------------*/





/*------------------------------------*\
    PAGINATION
\*------------------------------------*/
function greentheme_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'mid_size' => 2,
        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M14.8334 10.2333L11.0084 6.40834C10.8523 6.25313 10.6411 6.16602 10.4209 6.16602C10.2008 6.16602 9.98955 6.25313 9.83341 6.40834C9.75531 6.48581 9.69331 6.57798 9.651 6.67953C9.6087 6.78108 9.58691 6.89 9.58691 7.00001C9.58691 7.11002 9.6087 7.21894 9.651 7.32049C9.69331 7.42204 9.75531 7.51421 9.83341 7.59168L13.6667 11.4083C13.7449 11.4858 13.8068 11.578 13.8492 11.6795C13.8915 11.7811 13.9132 11.89 13.9132 12C13.9132 12.11 13.8915 12.2189 13.8492 12.3205C13.8068 12.422 13.7449 12.5142 13.6667 12.5917L9.83341 16.4083C9.67649 16.5642 9.5879 16.7759 9.58712 16.9971C9.58633 17.2182 9.67343 17.4306 9.82925 17.5875C9.98506 17.7444 10.1968 17.833 10.418 17.8338C10.6391 17.8346 10.8515 17.7475 11.0084 17.5917L14.8334 13.7667C15.3016 13.2979 15.5645 12.6625 15.5645 12C15.5645 11.3375 15.3016 10.7021 14.8334 10.2333Z" fill="#262626"/>
</svg>',
        'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M14.8334 10.2333L11.0084 6.40834C10.8523 6.25313 10.6411 6.16602 10.4209 6.16602C10.2008 6.16602 9.98955 6.25313 9.83341 6.40834C9.75531 6.48581 9.69331 6.57798 9.651 6.67953C9.6087 6.78108 9.58691 6.89 9.58691 7.00001C9.58691 7.11002 9.6087 7.21894 9.651 7.32049C9.69331 7.42204 9.75531 7.51421 9.83341 7.59168L13.6667 11.4083C13.7449 11.4858 13.8068 11.578 13.8492 11.6795C13.8915 11.7811 13.9132 11.89 13.9132 12C13.9132 12.11 13.8915 12.2189 13.8492 12.3205C13.8068 12.422 13.7449 12.5142 13.6667 12.5917L9.83341 16.4083C9.67649 16.5642 9.5879 16.7759 9.58712 16.9971C9.58633 17.2182 9.67343 17.4306 9.82925 17.5875C9.98506 17.7444 10.1968 17.833 10.418 17.8338C10.6391 17.8346 10.8515 17.7475 11.0084 17.5917L14.8334 13.7667C15.3016 13.2979 15.5645 12.6625 15.5645 12C15.5645 11.3375 15.3016 10.7021 14.8334 10.2333Z" fill="#262626"/>
</svg>',
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}
add_action('init', 'greentheme_pagination');







//estimated reading time
function reading_time()
{
    $content = get_post_field('post_content', $post->ID);
    $word_count = str_word_count(strip_tags($content));
    $readingtime = ceil($word_count / 200);

    if ($readingtime == 1) {
        $timer = " minute";
    } else {
        $timer = " minutes";
    }
    $totalreadingtime = $readingtime . $timer;

    return $totalreadingtime;
}





/*** mobile picture for wp block cover ****/
add_action('enqueue_block_editor_assets', 'enqueue_responsive_cover', 100);

function enqueue_responsive_cover()
{
    $dir = get_template_directory_uri() . '/assets/js';
    wp_enqueue_script('my-cover', $dir . '/my-cover.js', ['wp-blocks', 'wp-dom'], null, true);
}


add_filter('render_block_core/cover', 'my_responsive_cover_render', 10, 2);

function my_responsive_cover_render($content, $block)
{
    // If the block has a mobile image set
    if (isset($block['attrs']['mobileImageURL'])) {
        $mobileImage = $block['attrs']['mobileImageURL'];

        // Modify the content to insert the <picture> tag with mobile <source>
        $content = preg_replace(
            '/<img([^>]+)\/>/Ui',
            "<picture><source srcset='{$mobileImage}' media='(max-width:781px)' sizes='100vw'><img$1></picture>",
            $content
        );
    }

    return $content;
}






// Disable users rest routes
/*add_filter('rest_endpoints', function( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
});

*/








/**** SEO */
function noindex_paged()
{
    if (is_paged()) {
        echo '<meta name="robots" content="noindex, follow" />' . "\n";
    }
}
add_action('wp_head', 'noindex_paged');






/**
 * **
 * Extract ID from youtube url to use it to fetch video cover
 */
function get_youtube_thumbnail($youtube_url)
{
    preg_match('/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $youtube_url, $matches);
    if (!empty($matches[1])) {
        $video_id = $matches[1];
        return "https://img.youtube.com/vi/$video_id/maxresdefault.jpg";
    }
    return false;
}






/**
 * Summary of get_child_pages_shortcode
 * Shortcode to show child pages from specific parent page ID
 * [child_pages parent="123"]
 */
function get_child_pages_shortcode($atts) {
    $atts = shortcode_atts(array(
        'parent' => ''
    ), $atts, 'child_pages');

    if (empty($atts['parent'])) {
        return 'Please provide a parent page ID.';
    }

    $args = array(
        'post_type'      => 'page',
        'post_parent'    => intval($atts['parent']),
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return 'No child pages found.';
    }

    ob_start();

    echo '<div class="child-pages-list">';
    while ($query->have_posts()) {
        $query->the_post();
        ?>
        <div class="child-page">
            
            <div class="wp-block-button is-style-btn-arrow">
                <a href="<?php the_permalink(); ?>" class="wp-block-button__link wp-element-button"><?php the_title(); ?></a>
            </div>

            <div class="child-page-excerpt">
                <?php the_excerpt(); ?>
            </div>

            <?php if (has_post_thumbnail()) { ?>
                <div class="child-page-thumbnail">
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
                </div>
            <?php } ?>
        </div>
        <?php
    }
    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('child_pages', 'get_child_pages_shortcode');











add_filter('woocommerce_rest_check_permissions', 'allow_all_users_to_access_rest_api', 10, 4);

function allow_all_users_to_access_rest_api($permission, $context, $object_id, $post_type) {
    // Allow access to all users for all WooCommerce REST API endpoints
    return true;
}


/*

function import_woocommerce_categories_and_products() {
    // WooCommerce API Credentials
    $consumer_key = 'ck_2dde172c011f61ff3ba547065436bf9fab096f32'; // Replace with your WooCommerce API key
    $consumer_secret = 'cs_4f292f50c2751d4c06fc168e16eac2d0cbf38b38';
    $base_url = 'https://www.ledledeni.ch/wp-json/wc/v3';
    
    // Fetch all categories
    $categories = fetch_woocommerce_categories($base_url, $consumer_key, $consumer_secret);

    // Import categories into WordPress
    $category_map = import_categories_to_wp($categories);

    // Import only products from category ID 52
    $category_id = 52;
    import_products_from_category($base_url, $consumer_key, $consumer_secret, $category_id, $category_map);
}

// 🔹 Fetch WooCommerce categories
function fetch_woocommerce_categories($base_url, $consumer_key, $consumer_secret) {
    $response = wp_remote_get("$base_url/products/categories?per_page=100", [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode("$consumer_key:$consumer_secret"),
        ]
    ]);

    if (is_wp_error($response)) {
        error_log("Error fetching categories: " . $response->get_error_message());
        return [];
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}

// 🔹 Insert categories into WordPress
function import_categories_to_wp($categories) {
    $category_map = [];

    foreach ($categories as $category) {
        $term = term_exists($category['name'], 'product_cat');
        if (!$term) {
            $term = wp_insert_term($category['name'], 'product_cat', [
                'slug' => $category['slug'],
                'description' => $category['description'],
            ]);
        }

        if (!is_wp_error($term)) {
            $category_map[$category['id']] = $term['term_id'];
        }
    }

    return $category_map;
}

// 🔹 Fetch and insert products from category 52

function import_products_from_category($base_url, $consumer_key, $consumer_secret, $category_id, $category_map) {
    $per_page = 5;
    $page = 1;

    while (true) {
        $response = wp_remote_get("$base_url/products?category=$category_id&per_page=$per_page&page=$page", [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("$consumer_key:$consumer_secret"),
            ]
        ]);

        if (is_wp_error($response)) {
            error_log("Error fetching products: " . $response->get_error_message());
            break;
        }

        $products = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($products)) {
            break; // Stop if no more products
        }

        foreach ($products as $product) {
            // Check if product exists by SKU
            $existing_product_id = wc_get_product_id_by_sku($product['sku']);

            if ($existing_product_id) {
                $post_id = $existing_product_id;
                wp_update_post([
                    'ID'            => $post_id,
                    'post_title'    => wp_strip_all_tags($product['name']),
                    'post_name'     => $product['slug'],
                    'post_content'  => $product['description'],
                    'post_excerpt'  => $product['short_description'],
                    'menu_order'    => $product['menu_order'],
                ]);
            } else {
                $post_id = wp_insert_post([
                    'post_title'    => wp_strip_all_tags($product['name']),
                    'post_name'     => $product['slug'],
                    'post_content'  => $product['description'],
                    'post_excerpt'  => $product['short_description'],
                    'post_status'   => 'publish',
                    'post_type'     => 'product',
                    'menu_order'    => $product['menu_order'],
                ]);
            }

            if (!$post_id) {
                error_log("Failed to insert/update product: " . $product['name']);
                continue;
            }

            // Set WooCommerce product meta fields
            update_post_meta($post_id, '_price', $product['price']);
            update_post_meta($post_id, '_regular_price', $product['price']);
            update_post_meta($post_id, '_sku', $product['sku']);
            update_post_meta($post_id, '_stock_status', 'instock');
            update_post_meta($post_id, '_visibility', 'visible');

            // Assign Product Category
            if (isset($category_map[$category_id])) {
                wp_set_object_terms($post_id, [$category_map[$category_id]], 'product_cat');
            }

            // Assign Product Attributes
            if (!empty($product['attributes'])) {
                $attributes_data = [];
                foreach ($product['attributes'] as $attr) {
                    $attr_name = $attr['name'];
                    $attr_values = is_array($attr['options']) ? array_map('trim', $attr['options']) : [];

                    // Ensure attribute exists
                    $taxonomy = 'pa_' . sanitize_title($attr_name);
                    if (!taxonomy_exists($taxonomy)) {
                        register_taxonomy($taxonomy, 'product', [
                            'label' => $attr_name,
                            'rewrite' => ['slug' => sanitize_title($attr_name)],
                            'hierarchical' => false,
                        ]);
                    }

                    wp_set_object_terms($post_id, $attr_values, $taxonomy);
                    $attributes_data[$taxonomy] = [
                        'name'         => $taxonomy,
                        'value'        => implode('|', $attr_values),
                        'position'     => 0,
                        'is_visible'   => 1,
                        'is_variation' => 0,
                        'is_taxonomy'  => 1,
                    ];
                }
                update_post_meta($post_id, '_product_attributes', $attributes_data);
            }

            // Handle Product Image (First Image as Featured)
            if (!empty($product['images'][0]['src'])) {
                $image_url = $product['images'][0]['src'];
                $image_id = media_sideload_image($image_url, $post_id, $product['name'], 'id');
                if (!is_wp_error($image_id)) {
                    set_post_thumbnail($post_id, $image_id);
                }
            }

            // Handle Variable Products (if exists)
            if ($product['type'] === 'variable' && !empty($product['variations'])) {
                foreach ($product['variations'] as $variation) {
                    $variation_id = wc_get_product_id_by_sku($variation['sku']);

                    // Validate attributes before inserting/updating variation
                    $variation_attributes = isset($variation['attributes']) && is_array($variation['attributes']) ? $variation['attributes'] : [];

                    $variation_post = [
                        'post_title'   => $product['name'] . ' - ' . (!empty($variation_attributes) ? implode(', ', $variation_attributes) : ''),
                        'post_content' => '',
                        'post_status'  => 'publish',
                        'post_parent'  => $post_id,
                        'post_type'    => 'product_variation',
                    ];

                    if ($variation_id) {
                        $variation_post['ID'] = $variation_id;
                        $variation_post_id = wp_update_post($variation_post);
                    } else {
                        $variation_post_id = wp_insert_post($variation_post);
                    }

                    if (!$variation_post_id) {
                        error_log("Failed to insert/update variation: " . $variation['sku']);
                        continue;
                    }

                    // Set variation meta fields
                    update_post_meta($variation_post_id, '_price', $variation['price']);
                    update_post_meta($variation_post_id, '_regular_price', $variation['price']);
                    update_post_meta($variation_post_id, '_sku', $variation['sku']);
                    update_post_meta($variation_post_id, '_stock_status', 'instock');

                    // Set variation attributes
                    foreach ($variation_attributes as $attr_key => $attr_value) {
                        update_post_meta($variation_post_id, 'attribute_' . sanitize_title($attr_key), $attr_value);
                    }
                }
            }
        }

        $page++; // Fetch next page
    }
}
*/



// Run the import function manually from admin
//add_action('admin_init', 'import_woocommerce_categories_and_products');














//fetch_and_update_product_description('476', 'led-anbauleuchte-moon-15w');


// Add a custom admin page
// Add a custom button to each product in the WooCommerce backend
// Add a custom button to each product in the WooCommerce backend
function add_custom_button_to_product_edit_page() {
    global $post;

    // Check if we're on the product edit page
    if (!$post || $post->post_type !== 'product') {
        return;
    }

    // Get the product object
    $product = wc_get_product($post->ID);
    if (!$product) {
        return;
    }

    $product_id = $product->get_id();
    $product_slug = $product->get_slug();

    // Output the button
    echo '<div class="options_group">';
    echo '<p class="form-field">';
    echo '<button type="button" id="update-description-button" class="button button-primary" data-product-id="' . esc_attr($product_id) . '" data-product-slug="' . esc_attr($product_slug) . '">Update Description</button>';
    echo '</p>';
    echo '</div>';

    // Add JavaScript to handle the button click
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#update-description-button').on('click', function() {
            var product_id = $(this).data('product-id');
            var product_slug = $(this).data('product-slug');

            // Show a loading spinner
            $(this).text('Updating...').prop('disabled', true);

            // Send an AJAX request to update the description
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'update_product_description',
                    product_id: product_id,
                    product_slug: product_slug,
                    security: '<?php echo wp_create_nonce('update_product_description_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Description updated successfully!');
                    } else {
                        alert('Error: ' + response.data);
                    }
                },
                complete: function() {
                    // Restore the button text and enable it
                    $('#update-description-button').text('Update Description').prop('disabled', false);
                }
            });
        });
    });
    </script>
    <?php
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_button_to_product_edit_page');

// Handle the AJAX request to update the product description
function handle_update_product_description_ajax() {
    // Verify the nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'update_product_description_nonce')) {
        wp_send_json_error('Security check failed.');
    }

    // Get the product ID and slug from the AJAX request
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_slug = isset($_POST['product_slug']) ? sanitize_text_field($_POST['product_slug']) : '';

    if (!$product_id || !$product_slug) {
        wp_send_json_error('Invalid product ID or slug.');
    }

    // Fetch and update the product description
    fetch_and_update_product_description($product_id, $product_slug);

    // Send a success response
    wp_send_json_success('Description updated successfully.');
}
add_action('wp_ajax_update_product_description', 'handle_update_product_description_ajax');

// Function to fetch and update a single product description
function fetch_and_update_product_description($product_id, $product_slug) {
    // Construct the URL
    $url = "https://www.ledledeni.ch/produkt/{$product_slug}";
    error_log("Fetching URL: {$url}");

    // Fetch the page content with a User-Agent header
    $args = array(
        'headers' => array(
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ),
    );
    $response = wp_remote_get($url, $args);

    // Check if the request was successful
    if (is_wp_error($response)) {
        error_log("Failed to fetch product page for slug: {$product_slug}. Error: " . $response->get_error_message());
        return;
    }

    $body = wp_remote_retrieve_body($response);
    error_log("Page HTML: {$body}"); // Debugging: Log the entire HTML

    // Load the HTML content into a DOMDocument
    $dom = new DOMDocument();
    @$dom->loadHTML($body); // Suppress warnings for invalid HTML

    // Find the div with id 'tab-description'
    $xpath = new DOMXPath($dom);
    $description_div = $xpath->query('//div[@id="tab-description"]')->item(0);

    if ($description_div) {
        // Get the inner HTML of the div
        $description_html = '';
        foreach ($description_div->childNodes as $node) {
            $description_html .= $dom->saveHTML($node);
        }

        // Update the WooCommerce product description
        $product = wc_get_product($product_id);
        if ($product) {
            $product->set_description($description_html);
            $product->save();
            error_log("Successfully updated product ID: {$product_id}");
        } else {
            error_log("Product not found for ID: {$product_id}");
        }
    } else {
        error_log("No description found for product slug: {$product_slug}");
    }
}










/**
 * Clean up 'mfn-page-items-seo' ACF field in all WooCommerce products:
 * 1. Remove 'hide' string
 * 2. Remove 'Technische Daten' line
 */
function clean_mfn_page_items_seo_field() {
    // Ensure WooCommerce and ACF are active
    if (!class_exists('WooCommerce') || !function_exists('get_field') || !function_exists('update_field')) {
        return;
    }

    // Get all products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );

    $products = get_posts($args);
    $count = 0;

    foreach ($products as $product) {
        $field_value = get_field('mfn-page-items-seo', $product->ID);
        $modified = false;
        
        if (!empty($field_value)) {
            $new_value = $field_value;
            
            // Remove 'hide' string
            if (strpos($new_value, 'hide') !== false) {
                $new_value = str_replace('hide', '', $new_value);
                $modified = true;
            }
			
			if (strpos($new_value, 'line') !== false) {
                $new_value = str_replace('line', '', $new_value);
                $modified = true;
            }
			
			if (strpos($new_value, 'left') !== false) {
                $new_value = str_replace('left', '', $new_value);
                $modified = true;
            }
            
            // Remove 'Technische Daten' line (with any surrounding whitespace)
            $pattern = '/\s*Technische Daten\s*\r?\n?/';
            if (preg_match($pattern, $new_value)) {
                $new_value = preg_replace($pattern, '', $new_value);
                $modified = true;
            }
            
            // Update the field if changes were made
            if ($modified) {
                update_field('mfn-page-items-seo', $new_value, $product->ID);
                $count++;
                error_log("Cleaned product ID: " . $product->ID);
            }
        }
    }

    return "Process completed. {$count} products were updated.";
}

// To run this function immediately (test first!)
//clean_mfn_page_items_seo_field();





function reset_product_editor_layout_for_user( $user_id ) {
    delete_user_meta( $user_id, '_woocommerce_meta_box_prefs_product' );
    delete_user_meta( $user_id, 'meta-box-order_product' );
    delete_user_meta( $user_id, 'closedpostboxes_product' );
}





// Add excerpt support to pages
add_action('init', 'add_excerpts_to_pages');
function add_excerpts_to_pages() {
    add_post_type_support('page', 'excerpt');
}