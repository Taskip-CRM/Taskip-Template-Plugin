<?php
/**
 * Admin class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin class
 */
class Taskip_Admin
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialize properties
    }

    /**
     * Initialize admin functionality
     */
    public function initialize()
    {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Add admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        // Add term meta fields for template type and industry taxonomies
        add_action('template_type_add_form_fields', array($this, 'add_taxonomy_image_field'));
        add_action('template_type_edit_form_fields', array($this, 'edit_taxonomy_image_field'), 10, 2);
        add_action('template_industry_add_form_fields', array($this, 'add_taxonomy_image_field'));
        add_action('template_industry_edit_form_fields', array($this, 'edit_taxonomy_image_field'), 10, 2);

        // Save term meta
        add_action('created_template_type', array($this, 'save_taxonomy_image'), 10, 2);
        add_action('edited_template_type', array($this, 'save_taxonomy_image'), 10, 2);
        add_action('created_template_industry', array($this, 'save_taxonomy_image'), 10, 2);
        add_action('edited_template_industry', array($this, 'save_taxonomy_image'), 10, 2);

        // Add admin notices
        add_action('admin_notices', array($this, 'admin_notices'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu()
    {
        add_submenu_page(
            'edit.php?post_type=taskip_template',
            __('Settings', 'taskip-templates'),
            __('Settings', 'taskip-templates'),
            'manage_options',
            'taskip-templates-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Settings page content
     */
    public function settings_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Taskip Templates Settings', 'taskip-templates'); ?></h1>

            <form method="post" action="options.php">
                <?php
                settings_fields('taskip_templates_settings');
                do_settings_sections('taskip_templates_settings');
                submit_button();
                ?>
            </form>

            <div class="taskip-templates-info">
                <h2><?php _e('Shortcodes', 'taskip-templates'); ?></h2>
                <p><?php _e('Use the following shortcodes to display templates on your pages:', 'taskip-templates'); ?></p>

                <div class="taskip-shortcode-info">
                    <code>[taskip_templates]</code>
                    <p><?php _e('Displays a grid of templates with optional filtering.', 'taskip-templates'); ?></p>
                    <p><strong><?php _e('Parameters:', 'taskip-templates'); ?></strong></p>
                    <ul>
                        <li><code>type</code>
                            - <?php _e('Filter by template type slug (comma-separated for multiple types)', 'taskip-templates'); ?>
                        </li>
                        <li><code>industry</code>
                            - <?php _e('Filter by industry slug (comma-separated for multiple industries)', 'taskip-templates'); ?>
                        </li>
                        <li><code>limit</code>
                            - <?php _e('Number of templates to display (default: 12)', 'taskip-templates'); ?></li>
                        <li><code>columns</code>
                            - <?php _e('Number of columns in the grid (default: 3)', 'taskip-templates'); ?></li>
                        <li><code>orderby</code>
                            - <?php _e('Order by parameter (default: date)', 'taskip-templates'); ?></li>
                        <li><code>order</code> - <?php _e('Order direction (default: DESC)', 'taskip-templates'); ?>
                        </li>
                    </ul>
                    <p><strong><?php _e('Example:', 'taskip-templates'); ?></strong></p>
                    <code>[taskip_templates type="invoices,contracts" limit="6" columns="2"]</code>
                </div>

                <div class="taskip-shortcode-info">
                    <code>[taskip_template_categories]</code>
                    <p><?php _e('Displays a list of template categories (types).', 'taskip-templates'); ?></p>
                    <p><strong><?php _e('Parameters:', 'taskip-templates'); ?></strong></p>
                    <ul>
                        <li><code>orderby</code>
                            - <?php _e('Order by parameter (default: name)', 'taskip-templates'); ?></li>
                        <li><code>order</code> - <?php _e('Order direction (default: ASC)', 'taskip-templates'); ?></li>
                        <li><code>hide_empty</code>
                            - <?php _e('Hide empty categories (default: 1)', 'taskip-templates'); ?></li>
                        <li><code>show_count</code>
                            - <?php _e('Show template count (default: 1)', 'taskip-templates'); ?></li>
                    </ul>
                </div>

                <div class="taskip-shortcode-info">
                    <code>[taskip_template_industries]</code>
                    <p><?php _e('Displays a list of template industries.', 'taskip-templates'); ?></p>
                    <p><strong><?php _e('Parameters:', 'taskip-templates'); ?></strong></p>
                    <ul>
                        <li><code>orderby</code>
                            - <?php _e('Order by parameter (default: name)', 'taskip-templates'); ?></li>
                        <li><code>order</code> - <?php _e('Order direction (default: ASC)', 'taskip-templates'); ?></li>
                        <li><code>hide_empty</code>
                            - <?php _e('Hide empty industries (default: 1)', 'taskip-templates'); ?></li>
                        <li><code>show_count</code>
                            - <?php _e('Show template count (default: 1)', 'taskip-templates'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook Current admin page hook suffix.
     */
    public function admin_enqueue_scripts($hook)
    {
        // Only enqueue on template admin pages
        $screen = get_current_screen();

        if (strpos($hook, 'taskip_template') !== false ||
            ($screen && $screen->post_type === 'taskip_template') ||
            ($screen && ($screen->taxonomy === 'template_type' || $screen->taxonomy === 'template_industry'))) {

            // Enqueue media scripts for the media uploader
            wp_enqueue_media();

            wp_enqueue_style(
                'taskip-templates-admin-style',
                TASKIP_TEMPLATES_PLUGIN_URL . 'admin/css/taskip-templates-admin.css',
                array(),
                TASKIP_TEMPLATES_VERSION
            );

            wp_enqueue_script(
                'taskip-templates-admin-script',
                TASKIP_TEMPLATES_PLUGIN_URL . 'admin/js/taskip-templates-admin.js',
                array('jquery'),
                TASKIP_TEMPLATES_VERSION,
                true
            );
        }
    }

    /**
     * Add image field to taxonomy add form
     *
     * @param string $taxonomy Taxonomy slug.
     */
    public function add_taxonomy_image_field($taxonomy)
    {
        ?>
        <div class="form-field term-image-wrap">
            <label for="taxonomy-image"><?php _e('Category Image', 'taskip-templates'); ?></label>
            <div id="taxonomy-image-preview" class="taxonomy-image-preview"></div>
            <input type="hidden" id="taxonomy-image" name="taxonomy_image" class="taxonomy-image-field" value="">
            <button type="button"
                    class="button button-secondary taxonomy-image-upload"><?php _e('Upload Image', 'taskip-templates'); ?></button>
            <button type="button" class="button button-secondary taxonomy-image-remove"
                    style="display:none;"><?php _e('Remove Image', 'taskip-templates'); ?></button>
            <p class="description"><?php _e('Upload an image for this category/industry.', 'taskip-templates'); ?></p>
        </div>
        <?php
    }

    /**
     * Add image field to taxonomy edit form
     *
     * @param WP_Term $term Term object.
     * @param string $taxonomy Taxonomy slug.
     */
    public function edit_taxonomy_image_field($term, $taxonomy)
    {
        $image_id = get_term_meta($term->term_id, 'taxonomy_image_id', true);
        $image_url = '';

        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
        }
        ?>
        <tr class="form-field term-image-wrap">
            <th scope="row"><label for="taxonomy-image"><?php _e('Category Image', 'taskip-templates'); ?></label></th>
            <td>
                <div id="taxonomy-image-preview" class="taxonomy-image-preview">
                    <?php if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($term->name); ?>"
                             style="max-width: 300px;">
                    <?php endif; ?>
                </div>
                <input type="hidden" id="taxonomy-image" name="taxonomy_image" class="taxonomy-image-field"
                       value="<?php echo esc_attr($image_id); ?>">
                <button type="button"
                        class="button button-secondary taxonomy-image-upload"><?php _e('Upload Image', 'taskip-templates'); ?></button>
                <button type="button"
                        class="button button-secondary taxonomy-image-remove" <?php echo $image_url ? '' : 'style="display:none;"'; ?>><?php _e('Remove Image', 'taskip-templates'); ?></button>
                <p class="description"><?php _e('Upload an image for this category/industry.', 'taskip-templates'); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save taxonomy image field
     *
     * @param int $term_id Term ID.
     * @param string $taxonomy Taxonomy slug.
     */
    public function save_taxonomy_image($term_id, $taxonomy_id)
    {
        if (isset($_POST['taxonomy_image']) && !empty($_POST['taxonomy_image'])) {
            update_term_meta($term_id, 'taxonomy_image_id', absint($_POST['taxonomy_image']));
        } else {
            delete_term_meta($term_id, 'taxonomy_image_id');
        }
    }

    /**
     * Display admin notices
     */
    public function admin_notices()
    {
        // Check if permalinks are not set to "Post name"
        $permalink_structure = get_option('permalink_structure');

        if (empty($permalink_structure) && isset($_GET['post_type']) && $_GET['post_type'] === 'taskip_template') {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php _e('For best results with Taskip Templates, please set your <a href="options-permalink.php">permalinks</a> to "Post name" structure.', 'taskip-templates'); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Register plugin settings
     */
    public function register_settings()
    {
        register_setting('taskip_templates_settings', 'taskip_fluentcrm_username');
        register_setting('taskip_templates_settings', 'taskip_fluentcrm_password');

        add_settings_section(
            'taskip_fluentcrm_section',
            __('Fluent CRM API Settings', 'taskip-templates'),
            array($this, 'fluentcrm_section_callback'),
            'taskip_templates_settings'
        );

        add_settings_field(
            'taskip_fluentcrm_username',
            __('API Username', 'taskip-templates'),
            array($this, 'fluentcrm_username_callback'),
            'taskip_templates_settings',
            'taskip_fluentcrm_section'
        );

        add_settings_field(
            'taskip_fluentcrm_password',
            __('API Password', 'taskip-templates'),
            array($this, 'fluentcrm_password_callback'),
            'taskip_templates_settings',
            'taskip_fluentcrm_section'
        );
    }

    /**
     * Fluent CRM section description
     */
    public function fluentcrm_section_callback()
    {
        echo '<p>' . esc_html__('Configure your Fluent CRM API credentials.', 'taskip-templates') . '</p>';
    }

    /**
     * Username field callback
     */
    public function fluentcrm_username_callback()
    {
        $username = get_option('taskip_fluentcrm_username');
        echo '<input type="text" name="taskip_fluentcrm_username" value="' . esc_attr($username) . '" class="regular-text">';
    }

    /**
     * Password field callback
     */
    public function fluentcrm_password_callback()
    {
        $password = get_option('taskip_fluentcrm_password');
        echo '<input type="password" name="taskip_fluentcrm_password" value="' . esc_attr($password) . '" class="regular-text">';
    }
}
