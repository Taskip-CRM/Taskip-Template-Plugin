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
        add_action('add_meta_boxes', array($this, 'add_tools_meta_boxes'));
        add_action('add_meta_boxes', array($this, 'add_legal_meta_boxes'));

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
     * Add meta boxes for tools metadata
     */
    public function add_tools_meta_boxes() {
        add_meta_box(
            'taskip_tools_meta',
            __('Tool Details', 'taskip-templates'),
            array($this, 'render_tools_meta_box'),
            'tools',
            'normal',
            'high'
        );
    }

    /**
     * Add meta boxes for legal page metadata
     */
    public function add_legal_meta_boxes() {
        add_meta_box(
            'taskip_legal_meta',
            __('Legal Page Details', 'taskip-templates'),
            array($this, 'render_legal_meta_box'),
            'legal',
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
     * Render tools meta box
     *
     * @param WP_Post $post The post object.
     */
    public function render_tools_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('taskip_tools_meta', 'taskip_tools_meta_nonce');

        // Get saved metadata
        $card_title = get_post_meta($post->ID, '_taskip_tool_card_title', true);
        $tagline = get_post_meta($post->ID, '_taskip_tool_tagline', true);
        $description = get_post_meta($post->ID, '_taskip_tool_description', true);
        $features = get_post_meta($post->ID, '_taskip_tool_features', true);
        $cta_url = get_post_meta($post->ID, '_taskip_tool_cta_url', true);
        $gradient_start = get_post_meta($post->ID, '_taskip_tool_gradient_start', true);
        $gradient_end = get_post_meta($post->ID, '_taskip_tool_gradient_end', true);
        
        // Set defaults
        $gradient_start = !empty($gradient_start) ? $gradient_start : '#667eea';
        $gradient_end = !empty($gradient_end) ? $gradient_end : '#764ba2';
        ?>
        <div class="taskip-meta-section">
            <div class="taskip-meta-field">
                <label for="taskip_tool_card_title"><?php _e('Card Title (Optional):', 'taskip-templates'); ?></label>
                <input type="text" id="taskip_tool_card_title" name="_taskip_tool_card_title" value="<?php echo esc_attr($card_title); ?>" class="widefat" />
                <span class="description"><?php _e('Custom title for archive page card. Leave empty to use default post title.', 'taskip-templates'); ?></span>
            </div>
            
            <div class="taskip-meta-field">
                <label for="taskip_tool_tagline"><?php _e('Tool Tagline:', 'taskip-templates'); ?></label>
                <input type="text" id="taskip_tool_tagline" name="_taskip_tool_tagline" value="<?php echo esc_attr($tagline); ?>" class="widefat" />
                <span class="description"><?php _e('Short tagline for the tool (displayed in header)', 'taskip-templates'); ?></span>
            </div>
            
            <div class="taskip-meta-field">
                <label for="taskip_tool_description"><?php _e('Tool Description:', 'taskip-templates'); ?></label>
                <textarea id="taskip_tool_description" name="_taskip_tool_description" class="widefat" rows="3"><?php echo esc_textarea($description); ?></textarea>
                <span class="description"><?php _e('Brief description of the tool (displayed in body)', 'taskip-templates'); ?></span>
            </div>
            
            <div class="taskip-meta-field">
                <label for="taskip_tool_features"><?php _e('Tool Features:', 'taskip-templates'); ?></label>
                <textarea id="taskip_tool_features" name="_taskip_tool_features" class="widefat" rows="5"><?php echo esc_textarea($features); ?></textarea>
                <span class="description"><?php _e('List of tool features (one per line)', 'taskip-templates'); ?></span>
            </div>
            
            <div class="taskip-meta-field">
                <label for="taskip_tool_cta_url"><?php _e('CTA URL:', 'taskip-templates'); ?></label>
                <input type="url" id="taskip_tool_cta_url" name="_taskip_tool_cta_url" value="<?php echo esc_url($cta_url); ?>" class="widefat" />
                <span class="description"><?php _e('URL for the "Try [Tool Name]" button', 'taskip-templates'); ?></span>
            </div>
            
            <div class="taskip-meta-field">
                <label for="taskip_tool_gradient_start"><?php _e('Header Gradient Start Color:', 'taskip-templates'); ?></label>
                <input type="color" id="taskip_tool_gradient_start" name="_taskip_tool_gradient_start" value="<?php echo esc_attr($gradient_start); ?>" />
                <span class="description"><?php _e('Starting color for the header gradient', 'taskip-templates'); ?></span>
            </div>
            
            <div class="taskip-meta-field">
                <label for="taskip_tool_gradient_end"><?php _e('Header Gradient End Color:', 'taskip-templates'); ?></label>
                <input type="color" id="taskip_tool_gradient_end" name="_taskip_tool_gradient_end" value="<?php echo esc_attr($gradient_end); ?>" />
                <span class="description"><?php _e('Ending color for the header gradient', 'taskip-templates'); ?></span>
            </div>
        </div>
        <?php
    }

    /**
     * Render legal page meta box
     *
     * @param WP_Post $post The post object.
     */
    public function render_legal_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('taskip_legal_meta', 'taskip_legal_meta_nonce');

        // Get saved metadata
        $hero_description = get_post_meta($post->ID, '_taskip_legal_hero_description', true);
        $hero_icon = get_post_meta($post->ID, '_taskip_legal_hero_icon', true);
        $icon_background_color = get_post_meta($post->ID, '_taskip_legal_icon_bg_color', true);
        
        // Set defaults
        $icon_background_color = !empty($icon_background_color) ? $icon_background_color : '#4ECDC4';
        ?>
        <div class="taskip-meta-section">
            <div class="taskip-meta-field">
                <label for="taskip_legal_hero_description"><?php _e('Hero Description:', 'taskip-templates'); ?></label>
                <textarea id="taskip_legal_hero_description" name="_taskip_legal_hero_description" class="widefat" rows="3"><?php echo esc_textarea($hero_description); ?></textarea>
                <span class="description"><?php _e('Short description that appears below the title in the hero section', 'taskip-templates'); ?></span>
            </div>
            
            <div class="taskip-meta-field">
                <label for="taskip_legal_hero_icon"><?php _e('Hero Icon (SVG Code):', 'taskip-templates'); ?></label>
                <textarea id="taskip_legal_hero_icon" name="_taskip_legal_hero_icon" class="widefat" rows="8"><?php echo esc_textarea($hero_icon); ?></textarea>
                <span class="description"><?php _e('Paste SVG code for the icon. Example: &lt;svg&gt;...&lt;/svg&gt;', 'taskip-templates'); ?></span>
            </div>
            
            <div class="taskip-meta-field">
                <label for="taskip_legal_icon_bg_color"><?php _e('Icon Background Color:', 'taskip-templates'); ?></label>
                <input type="color" id="taskip_legal_icon_bg_color" name="_taskip_legal_icon_bg_color" value="<?php echo esc_attr($icon_background_color); ?>" />
                <span class="description"><?php _e('Background color for the hero icon', 'taskip-templates'); ?></span>
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

        // Add nonce check for usecases, templates, tools, and legal
        if (isset($_POST['taskip_usecases_meta_nonce'])) {
            if (!wp_verify_nonce($_POST['taskip_usecases_meta_nonce'], 'taskip_usecases_meta')) {
                return;
            }
        } elseif (isset($_POST['taskip_template_meta_nonce'])) {
            if (!wp_verify_nonce($_POST['taskip_template_meta_nonce'], 'taskip_template_meta')) {
                return;
            }
        } elseif (isset($_POST['taskip_tools_meta_nonce'])) {
            if (!wp_verify_nonce($_POST['taskip_tools_meta_nonce'], 'taskip_tools_meta')) {
                return;
            }
        } elseif (isset($_POST['taskip_legal_meta_nonce'])) {
            if (!wp_verify_nonce($_POST['taskip_legal_meta_nonce'], 'taskip_legal_meta')) {
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
        } elseif ($_POST['post_type'] === 'tools') {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Save tools metadata
            if (isset($_POST['_taskip_tool_card_title'])) {
                update_post_meta($post_id, '_taskip_tool_card_title', sanitize_text_field($_POST['_taskip_tool_card_title']));
            }
            if (isset($_POST['_taskip_tool_tagline'])) {
                update_post_meta($post_id, '_taskip_tool_tagline', sanitize_text_field($_POST['_taskip_tool_tagline']));
            }
            if (isset($_POST['_taskip_tool_description'])) {
                update_post_meta($post_id, '_taskip_tool_description', sanitize_textarea_field($_POST['_taskip_tool_description']));
            }
            if (isset($_POST['_taskip_tool_features'])) {
                update_post_meta($post_id, '_taskip_tool_features', sanitize_textarea_field($_POST['_taskip_tool_features']));
            }
            if (isset($_POST['_taskip_tool_cta_url'])) {
                update_post_meta($post_id, '_taskip_tool_cta_url', esc_url_raw($_POST['_taskip_tool_cta_url']));
            }
            if (isset($_POST['_taskip_tool_gradient_start'])) {
                update_post_meta($post_id, '_taskip_tool_gradient_start', sanitize_hex_color($_POST['_taskip_tool_gradient_start']));
            }
            if (isset($_POST['_taskip_tool_gradient_end'])) {
                update_post_meta($post_id, '_taskip_tool_gradient_end', sanitize_hex_color($_POST['_taskip_tool_gradient_end']));
            }
        } elseif ($_POST['post_type'] === 'legal') {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Save legal page metadata
            if (isset($_POST['_taskip_legal_hero_description'])) {
                update_post_meta($post_id, '_taskip_legal_hero_description', sanitize_textarea_field($_POST['_taskip_legal_hero_description']));
            }
            if (isset($_POST['_taskip_legal_hero_icon'])) {
                update_post_meta($post_id, '_taskip_legal_hero_icon', wp_kses($_POST['_taskip_legal_hero_icon'], array(
                    'svg' => array(
                        'class' => array(),
                        'aria-hidden' => array(),
                        'aria-labelledby' => array(),
                        'role' => array(),
                        'xmlns' => array(),
                        'width' => array(),
                        'height' => array(),
                        'viewbox' => array(),
                        'fill' => array(),
                        'stroke' => array(),
                        'stroke-width' => array(),
                        'stroke-linecap' => array(),
                        'stroke-linejoin' => array(),
                    ),
                    'path' => array(
                        'd' => array(),
                        'fill' => array(),
                        'stroke' => array(),
                        'stroke-width' => array(),
                        'stroke-linecap' => array(),
                        'stroke-linejoin' => array(),
                    ),
                    'circle' => array(
                        'cx' => array(),
                        'cy' => array(),
                        'r' => array(),
                        'fill' => array(),
                        'stroke' => array(),
                        'stroke-width' => array(),
                    ),
                    'rect' => array(
                        'x' => array(),
                        'y' => array(),
                        'width' => array(),
                        'height' => array(),
                        'fill' => array(),
                        'stroke' => array(),
                        'stroke-width' => array(),
                        'rx' => array(),
                        'ry' => array(),
                    ),
                    'g' => array(),
                    'defs' => array(),
                    'clippath' => array(
                        'id' => array(),
                    ),
                )));
            }
            if (isset($_POST['_taskip_legal_icon_bg_color'])) {
                update_post_meta($post_id, '_taskip_legal_icon_bg_color', sanitize_hex_color($_POST['_taskip_legal_icon_bg_color']));
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