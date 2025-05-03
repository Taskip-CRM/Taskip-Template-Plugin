<?php
/**
 * Main plugin class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 */
class Taskip_Templates {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize properties
    }

    /**
     * Initialize the plugin
     */
    public function initialize() {
        // Initialize post types
        $post_types = new Taskip_Post_Types();
        $post_types->initialize();

        // Initialize taxonomies
        $taxonomies = new Taskip_Taxonomies();
        $taxonomies->initialize();

        // Initialize shortcodes
        $shortcodes = new Taskip_Shortcodes();
        $shortcodes->initialize();

        // Initialize metaboxes
        $metaboxes = new Taskip_Metaboxes();
        $metaboxes->initialize();

        // Register widget
        add_action('widgets_init', array($this, 'register_widgets'));

        // Enqueue frontend scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Load template files
        add_filter('template_include', array($this, 'template_loader'));
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('Taskip_Templates_Widget');
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        // Only enqueue on template pages
        if (is_post_type_archive('taskip_template') ||
            is_singular('taskip_template') ||
            is_tax('template_type') ||
            is_tax('template_industry') ||
            is_page_template('templates/page-templates.php')) {

            wp_enqueue_style(
                'taskip-templates-style',
                TASKIP_TEMPLATES_PLUGIN_URL . 'assets/css/taskip-templates.css',
                array(),
                TASKIP_TEMPLATES_VERSION
            );

            wp_enqueue_script(
                'taskip-templates-script',
                TASKIP_TEMPLATES_PLUGIN_URL . 'assets/js/taskip-templates.js',
                array('jquery'),
                TASKIP_TEMPLATES_VERSION,
                true
            );

            // Localize script
            wp_localize_script('taskip-templates-script', 'taskipTemplates', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('taskip-templates-nonce')
            ));
        }
    }

    /**
     * Load template file based on current request
     *
     * @param string $template Standard template file provided by WordPress.
     * @return string Path to the template file to be used.
     */
    public function template_loader($template) {
        $file = '';

        if (is_singular('taskip_template')) {
            $file = 'single-taskip_template.php';
        } elseif (is_post_type_archive('taskip_template')) {
            $file = 'archive-taskip_template.php';
        } elseif (is_tax('template_type') || is_tax('template_industry')) {
            $file = 'taxonomy-template.php';
        }

        if ($file && file_exists(TASKIP_TEMPLATES_PLUGIN_DIR . 'templates/' . $file)) {
            return TASKIP_TEMPLATES_PLUGIN_DIR . 'templates/' . $file;
        }

        return $template;
    }
}