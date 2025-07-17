<?php
/**
 * Shortcode class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode class
 */
class Taskip_Shortcodes {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize properties
    }

    /**
     * Initialize metaboxes
     */
    public function initialize() {
        // Add meta boxes for template metadata
        add_shortcode('taskip_template_download', [$this,'taskip_template_download_shortcode']);
    }

    function taskip_template_download_shortcode($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'template_id' => get_the_ID(), // Default to current post ID
            'button_text' => 'Download Now'
        ), $atts);

        // Get template ID
        $template_id = (int)$atts['template_id'];


        // Get template title for display
        $template_title = get_the_title($template_id);

        // Generate unique ID for this instance
        $unique_id = wp_generate_uuid4();

        ob_start();
        ?>
        <div class="taskip-download-container">
            <button id="<?php echo $unique_id; ?>" class="taskip-download-btn-two"
                    data-template-id="<?php echo esc_attr($template_id); ?>"
                    data-template-title="<?php echo esc_attr($template_title); ?>">
                <?php echo esc_html($atts['button_text']); ?>
            </button>
        </div>

        <!-- Popup Modal -->
        <div id="taskip-modal-<?php echo $unique_id; ?>" class="taskip-modal" style="display: none;">
            <div class="taskip-modal-content">
                <span class="taskip-close" data-target="<?php echo $unique_id; ?>">&times;</span>
                <h3>Download <?php echo esc_html($template_title); ?></h3>
                <form id="taskip-form-<?php echo $unique_id; ?>" class="taskip-download-form">
                    <div class="taskip-form-group">
                        <label for="taskip-name-<?php echo $unique_id; ?>">Name *</label>
                        <input type="text" id="taskip-name-<?php echo $unique_id; ?>" name="name" required>
                    </div>

                    <div class="taskip-form-group">
                        <label for="taskip-email-<?php echo $unique_id; ?>">Email *</label>
                        <input type="email" id="taskip-email-<?php echo $unique_id; ?>" name="email" required>
                    </div>

                    <div class="taskip-form-group taskip-consent">
                        <label class="taskip-checkbox-label">
                            <input type="checkbox" id="taskip-consent-<?php echo $unique_id; ?>" name="consent" required>
                            I agree to receive news and updates related to Taskip. We respect your privacy and will not spam you. You can unsubscribe at any time.
                        </label>
                    </div>

                    <button type="submit" class="taskip-submit-btn">
                        <span class="taskip-btn-text">Download Now</span>
                        <span class="taskip-btn-loading" style="display: none;">
                        <span class="taskip-spinner"></span>
                        Processing...
                    </span>
                    </button>
                </form>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                // Store unique ID for this instance
                var uniqueId = '<?php echo $unique_id; ?>';

                // Button click handler
                $('#' + uniqueId).on('click', function() {
                    $('#taskip-modal-' + uniqueId).fadeIn(300);
                });

                // Close modal handler
                $('.taskip-close[data-target="' + uniqueId + '"]').on('click', function() {
                    $('#taskip-modal-' + uniqueId).fadeOut(300);
                });

                // Close modal when clicking outside
                $('#taskip-modal-' + uniqueId).on('click', function(e) {
                    if (e.target === this) {
                        $(this).fadeOut(300);
                    }
                });

                // Form submission handler
                $('#taskip-form-' + uniqueId).on('submit', function(e) {
                    e.preventDefault();

                    var $form = $(this);
                    var $submitBtn = $form.find('.taskip-submit-btn');
                    var $btnText = $submitBtn.find('.taskip-btn-text');
                    var $btnLoading = $submitBtn.find('.taskip-btn-loading');

                    // Show loading state
                    $btnText.hide();
                    $btnLoading.show();
                    $submitBtn.prop('disabled', true);

                    // Get form data
                    var formData = {
                        action: 'taskip_process_template_download',
                        name: $form.find('[name="name"]').val(),
                        email: $form.find('[name="email"]').val(),
                        consent: $form.find('[name="consent"]').prop('checked') ? '1' : '0',
                        template_id: $('#' + uniqueId).data('template-id'),
                        nonce: '<?php echo wp_create_nonce('taskip_download_nonce'); ?>'
                    };

                    // Send AJAX request
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                // Close modal
                                $('#taskip-modal-' + uniqueId).fadeOut(300);

                                // Open download in new tab
                                window.open(response.data.download_url, '_blank');

                                // Reset form
                                $form[0].reset();

                                // Show success message (optional)
                                if (response.data.message) {
                                    alert(response.data.message);
                                }
                            } else {
                                alert('Error: ' + response.data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            alert('An error occurred. Please try again.');
                        },
                        complete: function() {
                            // Hide loading state
                            $btnText.show();
                            $btnLoading.hide();
                            $submitBtn.prop('disabled', false);
                        }
                    });
                });
            });
        </script>
        <?php

        return ob_get_clean();
    }

}