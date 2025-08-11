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
class Taskip_Template_Shortcodes {

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
            <button id="taskip_template_____download_now_button" class="taskip-download-btn-two"
                    data-template-id="<?php echo esc_attr($template_id); ?>"
                    data-template-title="<?php echo esc_attr($template_title); ?>">
                <?php echo esc_html($atts['button_text']); ?>
            </button>
        </div>

        <!-- Popup Modal -->
        <div id="taskip-modal-template_____download_now_button" class="taskip-modal" style="display: none;">
            <div class="taskip-modal-content">
                <span class="taskip-close" id="taskip__download__modal_close_btn">&times;</span>
                <h3>Download <?php echo esc_html($template_title); ?></h3>
                <form id="taskip-form-template_____download_now_button" class="taskip-download-form">
                    <div class="taskip-form-group">
                        <label for="taskip-name-template_____download_now_button">Name *</label>
                        <input type="text" id="taskip-name-template_____download_now_button" name="name" required>
                    </div>

                    <div class="taskip-form-group">
                        <label for="taskip-email-template_____download_now_button">Email *</label>
                        <input type="email" id="taskip-email-template_____download_now_button" name="email" required>
                    </div>

                    <div class="taskip-form-group taskip-consent">
                        <label class="taskip-checkbox-label">
                            <input type="checkbox" id="taskip-consent-template_____download_now_button" name="consent" required>
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
        <?php

        return ob_get_clean();
    }

}