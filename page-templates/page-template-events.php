<?php /* Template Name: Events Template */ get_header(); ?>

<div class="page-content">

    <div class="gutenberg">
        <?php
        // Define the query arguments
        $args = [
            'post_type' => 'event', // Replace 'event' with your CPT slug
            'posts_per_page' => 1, // Get only one post
            'meta_key' => 'date_and_time', // The ACF field key
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_type' => 'DATETIME', // Define the meta type for sorting
            'meta_query' => [
                [
                    'key' => 'date_and_time', // Ensure the event is upcoming
                    'value' => current_time('Y-m-d H:i:s'), // Current date and time
                    'compare' => '>=', // Greater than or equal to
                    'type' => 'DATETIME',
                ],
            ],
        ];

        // Run the query
        $query = new WP_Query($args);

        // Check if a post was found
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $post_id = get_the_id();
                // Get the featured image URL
                $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                $date = get_field('date_and_time', get_the_id());
                $date_portion = substr($date, 0, 8);
                $time_portion = substr($date, -4);
                // Convert the string into a DateTime object
                $date_object = DateTime::createFromFormat('Ymd', $date_portion);
                // Format the time as H:i
                $formatted_time = DateTime::createFromFormat('Hi', $time_portion);
                $location = get_field('location', get_the_id());
                ?>
                <!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"customOverlayColor":"#483f4e","isUserOverlayColor":true,"align":"wide","className":"hero","layout":{"type":"constrained"}} -->
                <div class="wp-block-cover alignwide hero"><span aria-hidden="true"
                        class="wp-block-cover__background has-background-dim" style="background-color:#483f4e"></span>
                    <img class="wp-block-cover__image-background" src="<?php echo $featured_image_url; ?>"
                        alt="<?php echo get_the_title(); ?>">
                    <div class="wp-block-cover__inner-container"><!-- wp:columns -->
                        <div class="wp-block-columns"><!-- wp:column {"width":"770px"} -->
                            <div class="wp-block-column" style="flex-basis:770px"><!-- wp:post-title {"level":1} /-->
                                <?php echo '<h1>' . get_the_title() . '</h1>'; ?>
                                <?php if (has_excerpt($post_id)) {
                                    echo '<div class="event-excerpt">' . get_the_excerpt($post_id) . '</div>';
                                } ?>
                                <div class="event-hero-details flex">
                                    <div>
                                        <a href="<?php echo get_the_permalink(); ?>" class="btn">View event</a>
                                    </div>
                                    <?php if ($date_object): ?>
                                        <div class="date">
                                            <span>Date</span>
                                            <?php echo $date_object->format('F j, Y'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($formatted_time): ?>
                                        <div class="time">
                                            <span>Time</span>
                                            <?php echo $formatted_time->format('H:i'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($location): ?>
                                        <div class="location">
                                            <span>Location</span>
                                            <?php echo $location; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- /wp:column -->
                        </div>
                        <!-- /wp:columns -->
                    </div>
                </div>
                <!-- /wp:cover -->
                <?php
                // Display the event details
        
            }
        }

        // Reset post data
        wp_reset_postdata();
        ?>


        <div class="wp-block-group events-block upcoming-events">
            <div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
                <?php
                // Use the current date and time in YmdHi format
                $current_date = current_time('YmdHi');

                // Query upcoming events
                $args = [
                    'post_type' => 'event',
                    'posts_per_page' => -1,
                    'meta_key' => 'date_and_time', // The ACF field key
                    'orderby' => 'meta_value',
                    'order' => 'ASC',
                    'meta_type' => 'DATETIME', // Define the meta type for sorting
                    'meta_query' => [
                        [
                            'key' => 'date_and_time', // Ensure the event is upcoming
                            'value' => current_time('Y-m-d H:i:s'), // Current date and time
                            'compare' => '>=', // Greater than or equal to
                            'type' => 'DATETIME',
                        ],
                    ],
                ];

                $upcoming_events = new WP_Query($args);

                // Display the events if there are any
                if ($upcoming_events->have_posts()):
                    echo '<h2 class="events-heading">Events & Adventures</h2>';
                    echo '<div class="events">';
                    while ($upcoming_events->have_posts()):
                        $upcoming_events->the_post();
                        echo '<a class="event-tile" href="' . get_the_permalink() . '">';
                        ?>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="event-image">
                                <?php the_post_thumbnail('grid-item'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="event-tile-content">
                            <?php
                            echo '<div class="event-title">' . get_the_title() . '</div>';
                            if (has_excerpt()) {
                                echo '<div class="event-excerpt">' . get_the_excerpt() . '</div>';
                            }
                            echo '<div class="flex">';
                            ?>
                            <div class="event-location">
                                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_2238_10)">
                                        <path
                                            d="M4.18752 11.7579C0.782103 6.82104 0.149994 6.31437 0.149994 4.5C0.149994 2.01471 2.1647 0 4.64999 0C7.13528 0 9.14999 2.01471 9.14999 4.5C9.14999 6.31437 8.51788 6.82104 5.11246 11.7579C4.88898 12.0807 4.41098 12.0807 4.18752 11.7579ZM4.64999 6.375C5.68553 6.375 6.52499 5.53554 6.52499 4.5C6.52499 3.46446 5.68553 2.625 4.64999 2.625C3.61445 2.625 2.77499 3.46446 2.77499 4.5C2.77499 5.53554 3.61445 6.375 4.64999 6.375Z"
                                            fill="#882EE3" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2238_10">
                                            <rect width="9.6" height="12" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <?php echo get_field('location', get_the_id()); ?>
                            </div>
                            <?php
                            $event_date_time = get_field('date_and_time', get_the_id());
                            echo '<div class="event-date"><svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.349998 10.875C0.349998 11.4961 0.853905 12 1.475 12H9.725C10.3461 12 10.85 11.4961 10.85 10.875V4.5H0.349998V10.875ZM7.85 6.28125C7.85 6.12656 7.97656 6 8.13125 6H9.06875C9.22344 6 9.35 6.12656 9.35 6.28125V7.21875C9.35 7.37344 9.22344 7.5 9.06875 7.5H8.13125C7.97656 7.5 7.85 7.37344 7.85 7.21875V6.28125ZM7.85 9.28125C7.85 9.12656 7.97656 9 8.13125 9H9.06875C9.22344 9 9.35 9.12656 9.35 9.28125V10.2188C9.35 10.3734 9.22344 10.5 9.06875 10.5H8.13125C7.97656 10.5 7.85 10.3734 7.85 10.2188V9.28125ZM4.85 6.28125C4.85 6.12656 4.97656 6 5.13125 6H6.06875C6.22344 6 6.35 6.12656 6.35 6.28125V7.21875C6.35 7.37344 6.22344 7.5 6.06875 7.5H5.13125C4.97656 7.5 4.85 7.37344 4.85 7.21875V6.28125ZM4.85 9.28125C4.85 9.12656 4.97656 9 5.13125 9H6.06875C6.22344 9 6.35 9.12656 6.35 9.28125V10.2188C6.35 10.3734 6.22344 10.5 6.06875 10.5H5.13125C4.97656 10.5 4.85 10.3734 4.85 10.2188V9.28125ZM1.85 6.28125C1.85 6.12656 1.97656 6 2.13125 6H3.06875C3.22344 6 3.35 6.12656 3.35 6.28125V7.21875C3.35 7.37344 3.22344 7.5 3.06875 7.5H2.13125C1.97656 7.5 1.85 7.37344 1.85 7.21875V6.28125ZM1.85 9.28125C1.85 9.12656 1.97656 9 2.13125 9H3.06875C3.22344 9 3.35 9.12656 3.35 9.28125V10.2188C3.35 10.3734 3.22344 10.5 3.06875 10.5H2.13125C1.97656 10.5 1.85 10.3734 1.85 10.2188V9.28125ZM9.725 1.5H8.6V0.375C8.6 0.16875 8.43125 0 8.225 0H7.475C7.26875 0 7.1 0.16875 7.1 0.375V1.5H4.1V0.375C4.1 0.16875 3.93125 0 3.725 0H2.975C2.76875 0 2.6 0.16875 2.6 0.375V1.5H1.475C0.853905 1.5 0.349998 2.00391 0.349998 2.625V3.75H10.85V2.625C10.85 2.00391 10.3461 1.5 9.725 1.5Z" fill="#882EE3"/>
</svg>' . date('d.m.Y. - g:i A', strtotime($event_date_time)) . '</div>';
                            echo '</div>';
                            echo '</div>'; //event tile content
                            echo '</a>'; //event tile
                    endwhile;
                    echo '</div>';
                    wp_reset_postdata();
                else:
                    echo '<p>No upcoming events.</p>';
                endif;
                ?>
                </div>
            </div>

        </div> <!-- /events-block -->



        <div class="wp-block-group events-block previous-events">
            <div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
                <?php
                // Use the current date and time in YmdHi format
                $current_date = current_time('YmdHi');

                // Query upcoming events
                $args = [
                    'post_type' => 'event',
                    'posts_per_page' => -1,
                    'meta_key' => 'date_and_time', // The ACF field key
                    'orderby' => 'meta_value',
                    'order' => 'DESC',
                    'meta_type' => 'DATETIME', // Define the meta type for sorting
                    'meta_query' => [
                        [
                            'key' => 'date_and_time', // Ensure the event is upcoming
                            'value' => current_time('Y-m-d H:i:s'), // Current date and time
                            'compare' => '<', // Greater than or equal to
                            'type' => 'DATETIME',
                        ],
                    ],
                ];

                $upcoming_events = new WP_Query($args);

                // Display the events if there are any
                if ($upcoming_events->have_posts()):
                    echo '<h2 class="events-heading">Previous events</h2>';
                    echo '<div class="events">';
                    while ($upcoming_events->have_posts()):
                        $upcoming_events->the_post();
                        echo '<a class="event-tile" href="' . get_the_permalink() . '">';
                        ?>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="event-image">
                                <?php the_post_thumbnail('grid-item'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="event-tile-content">
                            <?php
                            echo '<div class="event-title">' . get_the_title() . '</div>';
                            if (has_excerpt()) {
                                echo '<div class="event-excerpt">' . get_the_excerpt() . '</div>';
                            }
                            echo '<div class="flex">';
                            ?>
                            <div class="event-location">
                                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_2238_10)">
                                        <path
                                            d="M4.18752 11.7579C0.782103 6.82104 0.149994 6.31437 0.149994 4.5C0.149994 2.01471 2.1647 0 4.64999 0C7.13528 0 9.14999 2.01471 9.14999 4.5C9.14999 6.31437 8.51788 6.82104 5.11246 11.7579C4.88898 12.0807 4.41098 12.0807 4.18752 11.7579ZM4.64999 6.375C5.68553 6.375 6.52499 5.53554 6.52499 4.5C6.52499 3.46446 5.68553 2.625 4.64999 2.625C3.61445 2.625 2.77499 3.46446 2.77499 4.5C2.77499 5.53554 3.61445 6.375 4.64999 6.375Z"
                                            fill="#882EE3" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2238_10">
                                            <rect width="9.6" height="12" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <?php echo get_field('location', get_the_id()); ?>
                            </div>
                            <?php
                            $event_date_time = get_field('date_and_time', get_the_id());
                            echo '<div class="event-date"><svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.349998 10.875C0.349998 11.4961 0.853905 12 1.475 12H9.725C10.3461 12 10.85 11.4961 10.85 10.875V4.5H0.349998V10.875ZM7.85 6.28125C7.85 6.12656 7.97656 6 8.13125 6H9.06875C9.22344 6 9.35 6.12656 9.35 6.28125V7.21875C9.35 7.37344 9.22344 7.5 9.06875 7.5H8.13125C7.97656 7.5 7.85 7.37344 7.85 7.21875V6.28125ZM7.85 9.28125C7.85 9.12656 7.97656 9 8.13125 9H9.06875C9.22344 9 9.35 9.12656 9.35 9.28125V10.2188C9.35 10.3734 9.22344 10.5 9.06875 10.5H8.13125C7.97656 10.5 7.85 10.3734 7.85 10.2188V9.28125ZM4.85 6.28125C4.85 6.12656 4.97656 6 5.13125 6H6.06875C6.22344 6 6.35 6.12656 6.35 6.28125V7.21875C6.35 7.37344 6.22344 7.5 6.06875 7.5H5.13125C4.97656 7.5 4.85 7.37344 4.85 7.21875V6.28125ZM4.85 9.28125C4.85 9.12656 4.97656 9 5.13125 9H6.06875C6.22344 9 6.35 9.12656 6.35 9.28125V10.2188C6.35 10.3734 6.22344 10.5 6.06875 10.5H5.13125C4.97656 10.5 4.85 10.3734 4.85 10.2188V9.28125ZM1.85 6.28125C1.85 6.12656 1.97656 6 2.13125 6H3.06875C3.22344 6 3.35 6.12656 3.35 6.28125V7.21875C3.35 7.37344 3.22344 7.5 3.06875 7.5H2.13125C1.97656 7.5 1.85 7.37344 1.85 7.21875V6.28125ZM1.85 9.28125C1.85 9.12656 1.97656 9 2.13125 9H3.06875C3.22344 9 3.35 9.12656 3.35 9.28125V10.2188C3.35 10.3734 3.22344 10.5 3.06875 10.5H2.13125C1.97656 10.5 1.85 10.3734 1.85 10.2188V9.28125ZM9.725 1.5H8.6V0.375C8.6 0.16875 8.43125 0 8.225 0H7.475C7.26875 0 7.1 0.16875 7.1 0.375V1.5H4.1V0.375C4.1 0.16875 3.93125 0 3.725 0H2.975C2.76875 0 2.6 0.16875 2.6 0.375V1.5H1.475C0.853905 1.5 0.349998 2.00391 0.349998 2.625V3.75H10.85V2.625C10.85 2.00391 10.3461 1.5 9.725 1.5Z" fill="#882EE3"/>
</svg>' . date('d.m.Y. - g:i A', strtotime($event_date_time)) . '</div>';
                            echo '</div>';
                            echo '</div>'; //event tile content
                            echo '</a>'; //event tile
                    endwhile;
                    echo '</div>';
                    wp_reset_postdata();
                else:
                    echo '<p>No upcoming events.</p>';
                endif;
                ?>
                </div>
            </div>

        </div> <!-- /events-block -->

    </div><!-- /gutenberg -->
</div><!-- /page-section -->
<?php get_footer(); ?>