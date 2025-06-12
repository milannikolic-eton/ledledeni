jQuery(document).ready(function($) {
    'use strict';
    
    if (typeof variationData === 'undefined') {
        return;
    }
    
    var variations = variationData.variations;
    var attributes = variationData.attributes;
    
    // Function to get currently selected attributes
    function getCurrentSelection() {
        var selected = {};
        $('.variations select, .variations input:checked').each(function() {
            var $this = $(this);
            var attributeName = $this.attr('name');
            var value = $this.val();
            
            if (value && value !== '') {
                selected[attributeName] = value;
            }
        });
        return selected;
    }
    
    // Function to check if a specific attribute combination is available
    function isVariationAvailable(testAttributes) {
        return variations.some(function(variation) {
            if (!variation.variation_is_active || !variation.variation_is_visible) {
                return false;
            }
            
            var matches = true;
            for (var attr in testAttributes) {
                if (testAttributes.hasOwnProperty(attr)) {
                    var variationValue = variation.attributes[attr];
                    var testValue = testAttributes[attr];
                    
                    // If variation has empty value for this attribute, it means "any"
                    if (variationValue !== '' && variationValue !== testValue) {
                        matches = false;
                        break;
                    }
                }
            }
            return matches;
        });
    }
    
    // Function to update availability visual indicators
    function updateAvailability() {
        var currentSelection = getCurrentSelection();
        
        // Check each attribute dropdown/input
        $('.variations .value select, .variations .value input[type="radio"]').each(function() {
            var $this = $(this);
            var attributeName = $this.attr('name');
            var isSelect = $this.is('select');
            
            if (isSelect) {
                // Handle select dropdowns
                $this.find('option').each(function() {
                    var $option = $(this);
                    var optionValue = $option.val();
                    
                    if (optionValue === '') {
                        return; // Skip empty option
                    }
                    
                    // Create test selection with this option
                    var testSelection = $.extend({}, currentSelection);
                    testSelection[attributeName] = optionValue;
                    
                    // Check if this combination is available
                    if (isVariationAvailable(testSelection)) {
                        $option.removeClass('variation-option-unavailable');
                        $option.prop('disabled', false);
                    } else {
                        $option.addClass('variation-option-unavailable');
                        $option.prop('disabled', true);
                    }
                });
            } else {
                // Handle radio buttons
                var radioValue = $this.val();
                var $label = $('label[for="' + $this.attr('id') + '"]');
                
                if (!$label.length) {
                    $label = $this.closest('label');
                }
                
                // Create test selection with this radio value
                var testSelection = $.extend({}, currentSelection);
                testSelection[attributeName] = radioValue;
                
                // Check if this combination is available
                if (isVariationAvailable(testSelection)) {
                    $this.removeClass('variation-option-unavailable');
                    $label.removeClass('variation-option-unavailable');
                    $this.prop('disabled', false);
                } else {
                    $this.addClass('variation-option-unavailable');
                    $label.addClass('variation-option-unavailable');
                    $this.prop('disabled', true);
                }
            }
        });
        
        // Handle color/image swatches if you're using them
        $('.variations .swatch').each(function() {
            var $swatch = $(this);
            var $input = $swatch.find('input[type="radio"]');
            
            if ($input.length) {
                var attributeName = $input.attr('name');
                var swatchValue = $input.val();
                
                // Create test selection with this swatch value
                var testSelection = $.extend({}, currentSelection);
                testSelection[attributeName] = swatchValue;
                
                // Check if this combination is available
                if (isVariationAvailable(testSelection)) {
                    $swatch.removeClass('variation-option-unavailable');
                    $input.prop('disabled', false);
                } else {
                    $swatch.addClass('variation-option-unavailable');
                    $input.prop('disabled', true);
                }
            }
        });
    }
    
    // Initial check
    updateAvailability();
    
    // Update when selections change
    $('.variations').on('change', 'select, input', function() {
        setTimeout(updateAvailability, 100); // Small delay to ensure DOM is updated
    });
    
    // Also listen to WooCommerce's own variation events
    $('form.variations_form').on('woocommerce_variation_has_changed', function() {
        updateAvailability();
    });
    
    // Reset functionality
    $('.reset_variations').on('click', function() {
        setTimeout(function() {
            updateAvailability();
        }, 100);
    });
});