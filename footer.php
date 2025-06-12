</div>
<!-- /body-content -->
</div>
<!-- /wrapper -->


<!-- footer -->
<footer class="footer" role="contentinfo">
  <div class="footer-top">
    <div class="container">
      <div class="footer-widget">
        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
      </div>
    </div><!-- /container -->
  </div><!-- /footer-top -->
  <div class="footer-bottom">
    <div class="container flex flex-vertical-center flex-space-between">
      <div class="copyright">
      Urheberrecht © 2025 LedLedeni. Alle Rechte vorbehalten
      </div><!-- /copyright -->

      <?php if (has_nav_menu('footer-menu2')) {
        wp_nav_menu(array('theme_location' => 'footer-menu2'));
      }
      ?>

      <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-2')) ?>
    </div><!-- /container -->
  </div><!-- /footer-bottom -->
</footer>
<!-- /footer -->


</div>
<!-- /wrapper -->

<?php wp_footer(); ?>

<div class="progress-wrap">
  <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
    <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
  </svg>
</div><!-- back to top -->
<script>
  jQuery(document).ready(function($) {
    $(".quantity").each(function() {
        var $input = $(this).find("input");
        var $minus = $("<button type='button' class='minus'>-</button>");
        var $plus = $("<button type='button' class='plus'>+</button>");
        
        $(this).prepend($minus).append($plus);

        $plus.click(function() {
            var val = parseInt($input.val());
            if (!isNaN(val)) {
                $input.val(val + 1).change();
            }
        });

        $minus.click(function() {
            var val = parseInt($input.val());
            if (!isNaN(val) && val > 1) {
                $input.val(val - 1).change();
            }
        });
    });
});

</script>
<?php if(is_page((14)) || is_page((34))): ?>
<script>
    document.querySelectorAll('input[type="submit"]').forEach(btn => console.log(btn.value));

document.addEventListener('wpcf7mailsent', function (event) {
    // Get the selected value of the dropdown field
    var subject = document.getElementById('subject').value;

    // Define the success message based on the selected value
    var successMessage = '';
    if (subject === 'Registrierung Onlineshop') {
        successMessage = 'Vielen Dank für Ihre Registrierungsanfrage! Sobald der Administrator Ihr Konto freigibt, erhalten Sie eine Bestätigungs-E-Mail.';
    } else {
        successMessage = 'Vielen Dank für Ihre Nachricht! Wir werden uns bald bei Ihnen melden.';
    }

    // Hide the form
    var form = event.target;
    form.style.display = 'none';

    // Create a new div to display the success message
    var messageBox = document.createElement('div');
    messageBox.className = 'cf7-success-message'; // Add a class for styling
    messageBox.innerHTML = successMessage;

    // Insert the message box in place of the form
    form.parentNode.insertBefore(messageBox, form);

    // Smoothly scroll to the message box
    messageBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
}, false);




document.addEventListener('DOMContentLoaded', function() {
    const subjectDropdown = document.getElementById('subject');
    const additionalFields = document.getElementById('additional-fields');
    const submitButton = document.querySelector('.wpcf7-submit'); // Fetch submit button by class
    const streetField = document.getElementById('street');
    const zipCodeField = document.getElementById('zip-code');
    const cityField = document.getElementById('city');

    if (subjectDropdown && additionalFields && submitButton && streetField && zipCodeField && cityField) {
        // Function to check if additional fields are filled
        function checkAdditionalFields() {
            if (subjectDropdown.value === 'Registrierung Onlineshop') {
                return streetField.value.trim() !== '' &&
                       zipCodeField.value.trim() !== '' &&
                       cityField.value.trim() !== '';
            }
            return true; // Allow submission if subject is not "Registrierung Onlineshop"
        }

        // Function to update submit button state
        function updateSubmitButton() {
            if (checkAdditionalFields()) {
                submitButton.removeAttribute('disabled');
            } else {
                submitButton.setAttribute('disabled', 'disabled');
            }
        }

        // Listen for changes in the subject dropdown
        subjectDropdown.addEventListener('change', function() {
            if (this.value === 'Registrierung Onlineshop') {
                additionalFields.style.display = 'flex';
            } else {
                additionalFields.style.display = 'none';
            }
            updateSubmitButton(); // Update submit button state
        });

        // Listen for input in additional fields
        streetField.addEventListener('input', updateSubmitButton);
        zipCodeField.addEventListener('input', updateSubmitButton);
        cityField.addEventListener('input', updateSubmitButton);

        // Initial check on page load
        updateSubmitButton();
    } else {
        console.error('One or more required elements are missing in the form.');
    }
});
</script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;

    // Check for saved theme in localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        body.classList.add(savedTheme);
    } else {
        body.classList.add('light-mode'); // Default to light mode
    }

    // Toggle between dark and light mode
    themeToggle.addEventListener('click', function() {
        if (body.classList.contains('dark-mode')) {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
            localStorage.setItem('theme', 'light-mode');
        } else {
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark-mode');
        }
    });
});



  document.addEventListener('DOMContentLoaded', function () {
    /*** Venobox on the button */
    // Check if there are any elements with the class .video-btn
    const videoButtons = document.querySelectorAll('.video-btn > a');

    if (videoButtons.length > 0) {
      // Add the required attributes to each <a> element inside .video-btn
      videoButtons.forEach(btn => {
        btn.setAttribute('data-autoplay', 'true');
        btn.setAttribute('data-vbtype', 'video');
      });

      // Initialize Venobox
      new VenoBox({
        selector: '.video-btn > a'
      });
    }
/*** end of Venobox on the button */



    // Select all elements with the class "onscroll-view"
    const onScrollElements = document.querySelectorAll('.onscroll-view');

    // Create a new IntersectionObserver instance
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        // Check if the element is intersecting (in the viewport)
        if (entry.isIntersecting) {
          entry.target.classList.add('in-viewport');
        }/* else {
                entry.target.classList.remove('in-viewport');
            }*/
      });
    }, {
      // Set the threshold to 0.1, which means the callback will be triggered when 10% of the element is in the viewport
      threshold: 0.15
    });

    // Observe each selected element
    onScrollElements.forEach((el) => {
      observer.observe(el);
    });
  });




  // Initialize Lenis after the document is fully loaded
  document.addEventListener('DOMContentLoaded', function () {
    const lenis = new Lenis({
      // Options for Lenis
      duration: 1.2, // Duration of the scroll animation
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      orientation: 'vertical', // Scroll orientation
      smoothWheel: true, // Enable smooth scrolling
    });

    // Start the scrolling animation
    function raf(time) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);
  });


/*
  jQuery(document).ready(function ($) {
    $('.variations_form').each(function () {
        var $form = $(this);

        $form.find('.variations select').each(function () {
            var $select = $(this);
            var attrName = $select.attr('name');
            var options = $select.find('option');
            var selectedValue = $select.val();
            var buttonsHtml = '';

            options.each(function () {
                var value = $(this).val();
                var label = $(this).text();
                if (value !== '') {
                    buttonsHtml += `<button type="button" class="variation-btn" data-attr="${attrName}" data-value="${value}">${label}</button>`;
                }
            });

            var $wrapper = $('<div class="variation-buttons"></div>').html(buttonsHtml);
            $select.after($wrapper).hide();
        });

        // Handle button click
        $form.on('click', '.variation-btn', function () {
            var $button = $(this);
            var attrName = $button.data('attr');
            var value = $button.data('value');

            // Set the selected value in the hidden select field
            $form.find(`select[name="${attrName}"]`).val(value).trigger('change');

            // Update active class
            $button.siblings().removeClass('active');
            $button.addClass('active');
        });

        // Set initial selection (if any)
        $form.find('.variation-btn').each(function () {
            var $button = $(this);
            var attrName = $button.data('attr');
            var selectedValue = $form.find(`select[name="${attrName}"]`).val();
            if ($button.data('value') === selectedValue) {
                $button.addClass('active');
            }
        });
    });
});
*/


</script>

<?php if(is_product()): ?>
<script>
  jQuery(document).ready(function ($) {
    $('.variations_form').each(function () {
        var $form = $(this);
        var $variationForm = $form.closest('.variations_form');

        // Create buttons for each select
        $form.find('.variations select').each(function () {
            var $select = $(this);
            var attrName = $select.attr('name');
            var options = $select.find('option');
            var selectedValue = $select.val();
            var buttonsHtml = '';

            options.each(function () {
                var value = $(this).val();
                var label = $(this).text();
                if (value !== '') {
                    buttonsHtml += `<button type="button" class="variation-btn" data-attr="${attrName}" data-value="${value}">${label}</button>`;
                }
            });

            var $wrapper = $('<div class="variation-buttons"></div>').html(buttonsHtml);
            $select.after($wrapper).hide();
        });

        // Handle button click
        $form.on('click', '.variation-btn', function () {
            var $button = $(this);
            if ($button.hasClass('disabled')) return false;
            
            var attrName = $button.data('attr');
            var value = $button.data('value');

            // Set the selected value in the hidden select field
            $form.find(`select[name="${attrName}"]`).val(value).trigger('change');

            // Update active class
            $form.find(`.variation-btn[data-attr="${attrName}"]`).removeClass('active');
            $button.addClass('active');
        });

        // Set initial selection (if any)
        $form.find('.variation-btn').each(function () {
            var $button = $(this);
            var attrName = $button.data('attr');
            var selectedValue = $form.find(`select[name="${attrName}"]`).val();
            if ($button.data('value') === selectedValue) {
                $button.addClass('active');
            }
        });

        // Update button states when variations change
        $form.on('found_variation update_variation_values', function() {
            updateVariationButtons($variationForm);
        });

        // Initialize button states
        setTimeout(function() {
            updateVariationButtons($variationForm);
        }, 100);
    });

    function updateVariationButtons($form) {
        // Reset all buttons
        $form.find('.variation-btn').removeClass('disabled unavailable');
        
        // Get current selections
        var currentSelections = {};
        $form.find('.variations select').each(function() {
            currentSelections[$(this).attr('name')] = $(this).val();
        });
        
        // Get all variations data
        var variations = $form.data('product_variations');
        
        // For each attribute, check which options are available
        $form.find('.variations select').each(function() {
            var $select = $(this);
            var attributeName = $select.attr('name');
            
            $select.find('option').each(function() {
                var value = $(this).val();
                if (!value) return;
                
                var $button = $form.find(`.variation-btn[data-attr="${attributeName}"][data-value="${value}"]`);
                
                // Temporarily select this value to check availability
                var tempSelections = $.extend({}, currentSelections);
                tempSelections[attributeName] = value;
                
                // Check if any variation matches these selections
                var isAvailable = variations.some(function(variation) {
                    return isVariationMatch(variation.attributes, tempSelections);
                });
                
                if (!isAvailable) {
                    $button.addClass('disabled unavailable');
                }
            });
        });
    }
    
    function isVariationMatch(variationAttrs, selectedAttrs) {
        for (var attr in selectedAttrs) {
            if (selectedAttrs[attr] && variationAttrs[attr] !== selectedAttrs[attr]) {
                return false;
            }
        }
        return true;
    }
});
</script>
<style>
  .variation-btn.unavailable {
    opacity: 0.3;
    cursor: not-allowed;
    position: relative;
}

.variation-btn.unavailable::after {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    width: 100%;
    height: 1px;
    background: currentColor;
    transform: rotate(-5deg);
}
</style>
<?php endif; ?>

</body>

</html>