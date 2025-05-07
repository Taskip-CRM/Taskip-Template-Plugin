<?php
/**
 * Metaboxes class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Metaboxes class
 */
class Taskip_Metaboxes {

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
        add_action('add_meta_boxes', array($this, 'add_template_meta_boxes'));

        // Save template metadata
        add_action('save_post', array($this, 'save_template_meta'));
    }

    /**
     * Add meta boxes for template metadata
     */
    public function add_template_meta_boxes() {
        add_meta_box(
            'taskip_template_meta',
            __('Template Details', 'taskip-templates'),
            array($this, 'render_template_meta_box'),
            'taskip_template',
            'normal',
            'high'
        );
    }

    /**
     * Render template meta box
     *
     * @param WP_Post $post The post object.
     */
    public function render_template_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('taskip_template_meta', 'taskip_template_meta_nonce');

        // Get saved metadata
        $preview_url = get_post_meta($post->ID, '_taskip_preview_url', true);
        $demo_url = get_post_meta($post->ID, '_taskip_demo_url', true);
        $template_features = get_post_meta($post->ID, '_taskip_template_features', true);

        ?>
        <div class="taskip-meta-section">
            <p class="taskip-meta-field">
                <label for="taskip_preview_url"><?php _e('Preview URL:', 'taskip-templates'); ?></label>
                <input type="url" id="taskip_preview_url" name="taskip_preview_url" value="<?php echo esc_url($preview_url); ?>" class="widefat">
                <span class="description"><?php _e('URL for the template preview image (alternative to featured image)', 'taskip-templates'); ?></span>
            </p>

            <p class="taskip-meta-field">
                <label for="taskip_demo_url"><?php _e('Demo URL:', 'taskip-templates'); ?></label>
                <input type="url" id="taskip_demo_url" name="taskip_demo_url" value="<?php echo esc_url($demo_url); ?>" class="widefat">
                <span class="description"><?php _e('URL to view a live demo of the template', 'taskip-templates'); ?></span>
            </p>
            <div class="taskip-meta-field">
                <label for="taskip_template_features"><?php _e('Template Features:', 'taskip-templates'); ?></label>
                <textarea id="taskip_template_features" name="taskip_template_features" class="widefat" rows="5"><?php echo esc_textarea($template_features); ?></textarea>
                <span class="description"><?php _e('List of template features (one per line)', 'taskip-templates'); ?></span>
            </div>
        </div>
        <?php
    }

    /**
     * Save template metadata
     *
     * @param int $post_id The post ID.
     */
    public function save_template_meta($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['taskip_template_meta_nonce'])) {
            return;
        }

        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['taskip_template_meta_nonce'], 'taskip_template_meta')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions
        if (isset($_POST['post_type']) && 'taskip_template' == $_POST['post_type']) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        } else {
            return;
        }

        // Save metadata
        if (isset($_POST['taskip_preview_url'])) {
            update_post_meta($post_id, '_taskip_preview_url', esc_url_raw($_POST['taskip_preview_url']));
        }

        if (isset($_POST['taskip_demo_url'])) {
            update_post_meta($post_id, '_taskip_demo_url', esc_url_raw($_POST['taskip_demo_url']));
        }

        if (isset($_POST['taskip_template_features'])) {
            update_post_meta($post_id, '_taskip_template_features', sanitize_textarea_field($_POST['taskip_template_features']));
        }
    }
}