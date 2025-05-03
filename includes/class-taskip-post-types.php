<?php
/**
 * Post Types class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Post Types class
 */
class Taskip_Post_Types {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize properties
    }

    /**
     * Initialize post types
     */
    public function initialize() {
        add_action('init', array($this, 'register_post_types'));

        // Add Gutenberg block editor support
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
        add_filter('use_block_editor_for_post_type', array($this, 'enable_block_editor'), 10, 2);

        // Add custom block categories for templates
        add_filter('block_categories_all', array($this, 'add_block_categories'), 10, 2);
    }

    /**
     * Register the custom post type for templates
     */
    public function register_post_types() {
        $labels = array(
            'name'               => _x('Templates', 'post type general name', 'taskip-templates'),
            'singular_name'      => _x('Template', 'post type singular name', 'taskip-templates'),
            'menu_name'          => _x('Templates', 'admin menu', 'taskip-templates'),
            'name_admin_bar'     => _x('Template', 'add new on admin bar', 'taskip-templates'),
            'add_new'            => _x('Add New', 'template', 'taskip-templates'),
            'add_new_item'       => __('Add New Template', 'taskip-templates'),
            'new_item'           => __('New Template', 'taskip-templates'),
            'edit_item'          => __('Edit Template', 'taskip-templates'),
            'view_item'          => __('View Template', 'taskip-templates'),
            'all_items'          => __('All Templates', 'taskip-templates'),
            'search_items'       => __('Search Templates', 'taskip-templates'),
            'parent_item_colon'  => __('Parent Templates:', 'taskip-templates'),
            'not_found'          => __('No templates found.', 'taskip-templates'),
            'not_found_in_trash' => __('No templates found in Trash.', 'taskip-templates')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Taskip document templates.', 'taskip-templates'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'templates'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-media-document',
            'supports'           => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'comments',
                'custom-fields',
                'revisions',
                'block-templates' // Add block templates support
            ),
            'show_in_rest'       => true, // Required for Gutenberg editor
            'template'           => array(
                // Define default blocks to include when creating a new template
                array('core/heading', array(
                    'level' => 2,
                    'content' => __('Template Description', 'taskip-templates'),
                )),
                array('core/paragraph', array(
                    'content' => __('Enter a detailed description of your template here...', 'taskip-templates'),
                )),
                array('core/heading', array(
                    'level' => 3,
                    'content' => __('How to Use This Template', 'taskip-templates'),
                )),
                array('core/list', array(
                    'values' => array(
                        __('Step 1: Sign up for Taskip', 'taskip-templates'),
                        __('Step 2: Select this template', 'taskip-templates'),
                        __('Step 3: Customize to your needs', 'taskip-templates')
                    )
                ))
            ),
            'template_lock'      => false // 'all' to lock the template, false to allow adding/removing blocks
        );

        register_post_type('taskip_template', $args);
    }

    /**
     * Enqueue assets for the block editor
     */
    public function enqueue_block_editor_assets() {
        // Only enqueue for our custom post type
        if (get_post_type() !== 'taskip_template') {
            return;
        }

        // Custom styles for the block editor
        wp_enqueue_style(
            'taskip-block-editor-styles',
            TASKIP_TEMPLATES_PLUGIN_URL . 'assets/css/taskip-block-editor.css',
            array('wp-edit-blocks'),
            TASKIP_TEMPLATES_VERSION
        );

        // Custom script for block editor
        wp_enqueue_script(
            'taskip-block-editor-script',
            TASKIP_TEMPLATES_PLUGIN_URL . 'assets/js/taskip-block-editor.js',
            array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
            TASKIP_TEMPLATES_VERSION,
            true
        );
    }

    /**
     * Ensure block editor is enabled for our post type
     */
    public function enable_block_editor($can_edit, $post_type) {
        if ($post_type === 'taskip_template') {
            return true; // Force enable block editor for our post type
        }

        return $can_edit;
    }

    /**
     * Add custom block categories for templates
     */
    public function add_block_categories($categories, $post) {
        if ($post && $post->post_type === 'taskip_template') {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'taskip-template-blocks',
                        'title' => __('Template Sections', 'taskip-templates'),
                        'icon'  => 'media-document',
                    ),
                )
            );
        }

        return $categories;
    }
}