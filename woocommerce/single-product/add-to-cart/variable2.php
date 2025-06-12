<?php
/**
 * Variable product add to cart
 */

defined('ABSPATH') || exit;

global $product;

$attribute_keys = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);
?>

<form class="variations_form cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data" data-product_id="<?php echo absint($product->get_id()); ?>" data-product_variations="<?php echo $variations_attr; ?>">
    
    <?php foreach ($attributes as $attribute_name => $options) : ?>
        <div class="variation-wrapper">
            <label class="variation-label"><?php echo wc_attribute_label($attribute_name); ?>:</label>
            <div class="variation-buttons" data-attribute_name="attribute_<?php echo esc_attr($attribute_name); ?>">
                <?php foreach ($options as $option) : ?>
                    <?php $option_slug = sanitize_title($option); ?>
                    <button type="button" class="variation-btn" data-value="<?php echo esc_attr($option); ?>">
                        <?php echo esc_html($option); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="attribute_<?php echo esc_attr($attribute_name); ?>" value="" class="variation-input">
        </div>
    <?php endforeach; ?>

    <?php do_action('woocommerce_before_add_to_cart_button'); ?>

    <div class="single_variation_wrap">
        <?php do_action('woocommerce_single_variation'); ?>
    </div>

    <button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>

    <?php do_action('woocommerce_after_add_to_cart_button'); ?>
</form>

<script>
jQuery(document).ready(function ($) {
    $('.variations_form').each(function () {
        var $form = $(this);

        $form.on('click', '.variation-btn', function () {
            var $button = $(this);
            var value = $button.data('value');
            var attrName = $button.closest('.variation-buttons').data('attribute_name');
            var $input = $form.find('input[name="' + attrName + '"]');
            var $select = $form.find('select[name="' + attrName + '"]');

            // Set value in hidden input and WooCommerce select field
            $input.val(value).trigger('change');
            $select.val(value).trigger('change');

            // Highlight selected button
            $button.siblings().removeClass('selected');
            $button.addClass('selected');

            // Trigger WooCommerce's variation update
            $form.trigger('check_variations');
        });

        // Ensure variation selection triggers the 'found_variation' event
        $form.on('found_variation', function () {
            $form.find('.single_add_to_cart_button').removeAttr('disabled');
        });

        // Automatically select the first variation (optional)
        $form.find('.variation-buttons').each(function () {
            $(this).find('.variation-btn').first().click();
        });
    });
});

</script>