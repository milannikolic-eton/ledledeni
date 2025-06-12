<?php get_header(); ?>
<div class="container woocommerce-content">



    <?php if (is_product_category() || is_shop() || is_product_tag()): ?>
        <div class="shop-header">
            <?php
            if (is_product_category()) {
                $term = get_queried_object();
                echo '<h1 class="shop-title">' . esc_html($term->name) . '</h1>';
            } elseif (is_product_tag()) {
                $term = get_queried_object();
                echo '<h1 class="shop-title">' . esc_html($term->name) . '</h1>';
            } elseif (is_shop()) {
                echo '<h1 class="shop-title">Shop LED-Leuchten, die zu Ihren Bedürfnissen passen</h1>';
            }
            ?>

            <div class="shop-header-meta">
                <?php
                if (!function_exists('woocommerce_result_count')) {
                    function woocommerce_result_count()
                    {
                        if (!wc_get_loop_prop('is_paginated') || !woocommerce_products_will_display()) {
                            return;
                        }
                        $args = array(
                            'total' => wc_get_loop_prop('total'),
                            'per_page' => wc_get_loop_prop('per_page'),
                            'current' => wc_get_loop_prop('current_page'),
                        );
                        wc_get_template('loop/result-count.php', $args);
                    }
                }

                // Place the result count where you want
                woocommerce_result_count(); ?>

                <?php echo custom_product_search_form(); ?>


                    <button id="filter-menu-btn" class="hamburger-btn btn">
                        Produktfilter öffnen
                    </button>
      
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Function to check if all filter wrappers are hidden
    function areAllFiltersHidden() {
        const filterWrappers = document.querySelectorAll('.woo-filters .wc-blocks-filter-wrapper');
        
        // Return true if no filters exist or all are hidden
        return filterWrappers.length === 0 || 
               Array.from(filterWrappers).every(wrapper => {
                   return wrapper.hasAttribute('hidden') || 
                          wrapper.style.display === 'none' || 
                          wrapper.classList.contains('hidden');
               });
    }

    // Function to toggle filter button visibility
    function toggleFilterButton() {
        const filterButton = document.getElementById('filter-menu-btn');
        
        if (filterButton) {
            if (areAllFiltersHidden()) {
                filterButton.style.display = 'none';
            } else {
                filterButton.style.display = ''; // Reset to default
            }
        }
    }

    // Initial check
    toggleFilterButton();

    // Optional: Set up MutationObserver to watch for changes in filter visibility
    const observer = new MutationObserver(function(mutations) {
        toggleFilterButton();
    });

    // Observe the filters container for changes
    const filtersContainer = document.querySelector('.woo-filters');
    if (filtersContainer) {
        observer.observe(filtersContainer, {
            attributes: true,
            attributeFilter: ['hidden', 'style', 'class'],
            childList: true,
            subtree: true
        });
    }

    // Also observe the button itself in case it gets modified elsewhere
    const filterButton = document.getElementById('filter-menu-btn');
    if (filterButton) {
        observer.observe(filterButton, {
            attributes: true,
            attributeFilter: ['style']
        });
    }
});
</script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const filterBtn = document.getElementById("filter-menu-btn");
                        const closeBtn = document.getElementById("close-filter-btn");
                        const filterMenu = document.querySelector(".woo-filters");

                        filterBtn.addEventListener("click", function () {
                            filterMenu.classList.toggle("open");
                            this.textContent = filterMenu.classList.contains("open")
                                ? "Produkte Filter schließen"
                                : "Produkte Filter öffnen";
                        });

                        closeBtn.addEventListener("click", function () {
                            filterMenu.classList.remove("open");
                            filterBtn.textContent = "Produkte Filter öffnen";
                        });

                        // Close when clicking outside
                        document.addEventListener("click", function (event) {
                            if (
                                filterMenu.classList.contains("open") &&
                                !filterMenu.contains(event.target) &&
                                event.target !== filterBtn
                            ) {
                                filterMenu.classList.remove("open");
                                filterBtn.textContent = "Produkte Filter öffnen";
                            }
                        });
                    });
                </script>
            </div>
        </div>
        <div class="shop-container">
            <div class="woo-filters">
                <button id="close-filter-btn" class="close-btn">✖</button>
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-5')) ?>
            </div>
            <div class="listed-products">

                <?php woocommerce_content(); ?>
            </div>
        </div>

        <?php
        $block_pattern_id = 331;

        // Get the post content of the Gutenberg block pattern
        $block_pattern_post = get_post($block_pattern_id);

        if ($block_pattern_post && $block_pattern_post->post_type === 'wp_block') {
            // Access the block content
            $block_content = $block_pattern_post->post_content;

            // Output the block content
            echo apply_filters('the_content', $block_content);
        }
        ?>

    <?php else: ?>
        <?php $args = array(
            'delimiter' => ' / ',
        );
        woocommerce_breadcrumb($args);
        ?>
        <?php woocommerce_content(); ?>
    <?php endif; ?>

</div>
<?php get_footer(); ?>