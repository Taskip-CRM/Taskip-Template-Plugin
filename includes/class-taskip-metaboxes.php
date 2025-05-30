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
        add_action('add_meta_boxes', array($this, 'add_usecases_meta_boxes'));

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

    public function add_usecases_meta_boxes() {
        add_meta_box(
            'taskip_usecases_meta',
            __('Usecases Details', 'taskip-templates'),
            array($this, 'render_usecases_meta_box'),
            'taskip_usecases',
            'normal',
            'high'
        );
    }


    /**
     * Render usecases meta box
     *
     * @param WP_Post $post The post object.
     */
    public function render_usecases_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('taskip_usecases_meta', 'taskip_usecases_meta_nonce');

        // Get saved metadata
        $support = get_post_meta($post->ID, '_taskip_usecase_book_demo_url', true);
        $description = get_post_meta($post->ID, '_taskip_usecase_description', true);
        ?>
        <div class="taskip-meta-section">
            <div class="taskip-meta-field">
                <label for="_taskip_usecase_book_demo_url"><?php _e('Book Demo URL:', 'taskip-templates'); ?></label>
                <input id="taskip_usecase_book_demo_url" name="_taskip_usecase_book_demo_url" class="widefat" value="<?php echo esc_url($support); ?>" />
                <span class="description"><?php _e('book demo meeting url', 'taskip-templates'); ?></span>
            </div>
            <div class="taskip-meta-field">
                <label for="taskip_usecase_description"><?php _e('Description:', 'taskip-templates'); ?></label>
                <textarea id="taskip_usecase_description" name="taskip_usecase_description" class="widefat" rows="5"><?php echo esc_textarea($description); ?></textarea>
                <span class="description"><?php _e('Detailed description of the use case', 'taskip-templates'); ?></span>
            </div>
        </div>
        <?php
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
        $template_features = get_post_meta($post->ID, '_taskip_template_features', true);
        $preview_url = !empty($preview_url) ? $preview_url :'https://taskip.app/templates/---template--slug--?type=document';
        $template_features =  !empty($template_features) ? $template_features :  "Expert-designed and ready-to-use
Customize it for your team
Used by 250+ professionals";
        ?>
        <div class="taskip-meta-section">
            <p class="taskip-meta-field">
                <label for="taskip_preview_url"><?php _e('Preview URL:', 'taskip-templates'); ?></label>
                <input type="url" id="taskip_preview_url" name="taskip_preview_url" value="<?php echo esc_url($preview_url) ; ?>" class="widefat">
                <span class="description"><?php _e('URL for the template preview image (alternative to featured image)', 'taskip-templates'); ?></span>
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

        // Add nonce check for usecases
        if (isset($_POST['taskip_usecases_meta_nonce'])) {
            if (!wp_verify_nonce($_POST['taskip_usecases_meta_nonce'], 'taskip_usecases_meta')) {
                return;
            }
        } elseif (isset($_POST['taskip_template_meta_nonce'])) {
            if (!wp_verify_nonce($_POST['taskip_template_meta_nonce'], 'taskip_template_meta')) {
                return;
            }
        } else {
            return;
        }

        // If this is an autosave, our form has not been submitted
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check post type and permissions
        if (!isset($_POST['post_type'])) {
            return;
        }

        if ($_POST['post_type'] === 'taskip_template') {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Save template metadata
            if (isset($_POST['taskip_preview_url'])) {
                update_post_meta($post_id, '_taskip_preview_url', esc_url_raw($_POST['taskip_preview_url']));
            }
            if (isset($_POST['taskip_template_features'])) {
                update_post_meta($post_id, '_taskip_template_features', sanitize_textarea_field($_POST['taskip_template_features']));
            }
        } elseif ($_POST['post_type'] === 'taskip_usecases') {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Save usecases metadata
            if (isset($_POST['_taskip_usecase_book_demo_url'])) {
                update_post_meta($post_id, '_taskip_usecase_book_demo_url', sanitize_textarea_field($_POST['_taskip_usecase_book_demo_url']));
            }
            if (isset($_POST['taskip_usecase_description'])) {
                update_post_meta($post_id, '_taskip_usecase_description', sanitize_textarea_field($_POST['taskip_usecase_description']));
            }
        }


        // Check if our nonce is set
//        if (!isset($_POST['taskip_template_meta_nonce'])) {
//            return;
//        }
//
//        // Verify that the nonce is valid
//        if (!wp_verify_nonce($_POST['taskip_template_meta_nonce'], 'taskip_template_meta')) {
//            return;
//        }
//
//        // If this is an autosave, our form has not been submitted, so we don't want to do anything
//        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//            return;
//        }
//
//        // Check the user's permissions
//        if (isset($_POST['post_type']) && 'taskip_template' == $_POST['post_type']) {
//            if (!current_user_can('edit_post', $post_id)) {
//                return;
//            }
//        } else {
//            return;
//        }
//
//        // Save metadata
//        if (isset($_POST['taskip_preview_url'])) {
//            update_post_meta($post_id, '_taskip_preview_url', esc_url_raw($_POST['taskip_preview_url']));
//        }
//
//        if (isset($_POST['taskip_template_features'])) {
//            update_post_meta($post_id, '_taskip_template_features', sanitize_textarea_field($_POST['taskip_template_features']));
//        }
    }
}