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
        );

        register_post_type('taskip_template', $args);


        $labels = array(
            'name'               => _x('Use Cases', 'post type general name', 'taskip-templates'),
            'singular_name'      => _x('Use Cases', 'post type singular name', 'taskip-templates'),
            'menu_name'          => _x('Use Cases', 'admin menu', 'taskip-templates'),
            'name_admin_bar'     => _x('Use Case', 'add new on admin bar', 'taskip-templates'),
            'add_new'            => _x('Add New', 'template', 'taskip-templates'),
            'add_new_item'       => __('Add New Use Case', 'taskip-templates'),
            'new_item'           => __('New Use Case', 'taskip-templates'),
            'edit_item'          => __('Edit Use Case', 'taskip-templates'),
            'view_item'          => __('View Use Case', 'taskip-templates'),
            'all_items'          => __('All Use Cases', 'taskip-templates'),
            'search_items'       => __('Search Use Cases', 'taskip-templates'),
            'parent_item_colon'  => __('Parent Use Cases:', 'taskip-templates'),
            'not_found'          => __('No Use Cases found.', 'taskip-templates'),
            'not_found_in_trash' => __('No Use Cases found in Trash.', 'taskip-templates')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Taskip use cases.', 'taskip-templates'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'use-cases'),
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
                'custom-fields',
                'revisions',
                'block-templates' // Add block templates support
            ),
            'show_in_rest'       => true, // Required for Gutenberg editor
        );

        register_post_type('taskip_usecases', $args);

        // Register Tools post type
        $tools_labels = array(
            'name'               => _x('Tools', 'post type general name', 'taskip-templates'),
            'singular_name'      => _x('Tool', 'post type singular name', 'taskip-templates'),
            'menu_name'          => _x('Tools', 'admin menu', 'taskip-templates'),
            'name_admin_bar'     => _x('Tool', 'add new on admin bar', 'taskip-templates'),
            'add_new'            => _x('Add New', 'tool', 'taskip-templates'),
            'add_new_item'       => __('Add New Tool', 'taskip-templates'),
            'new_item'           => __('New Tool', 'taskip-templates'),
            'edit_item'          => __('Edit Tool', 'taskip-templates'),
            'view_item'          => __('View Tool', 'taskip-templates'),
            'all_items'          => __('All Tools', 'taskip-templates'),
            'search_items'       => __('Search Tools', 'taskip-templates'),
            'parent_item_colon'  => __('Parent Tools:', 'taskip-templates'),
            'not_found'          => __('No tools found.', 'taskip-templates'),
            'not_found_in_trash' => __('No tools found in Trash.', 'taskip-templates')
        );

        $tools_args = array(
            'labels'             => $tools_labels,
            'description'        => __('Free tools for users.', 'taskip-templates'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'tools'),
            'capability_type'    => 'page',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-admin-tools',
            'supports'           => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'comments',
                'custom-fields',
                'revisions',
                'page-attributes',
                'block-templates'
            ),
            'show_in_rest'       => true,
        );

        register_post_type('tools', $tools_args);

    }

    /**
     * Enqueue assets for the block editor
     */
    public function enqueue_block_editor_assets() {
        // Only enqueue for our custom post types
        $allowed_post_types = array('taskip_template', 'taskip_usecases', 'tools');
        if (!in_array(get_post_type(), $allowed_post_types)) {
            return;
        }

    }

    /**
     * Ensure block editor is enabled for our post type
     */
    public function enable_block_editor($can_edit, $post_type) {
        $allowed_post_types = array('taskip_template', 'taskip_usecases', 'tools');
        if (in_array($post_type, $allowed_post_types)) {
            return true; // Force enable block editor for our post types
        }

        return $can_edit;
    }

    /**
     * Add custom block categories for templates
     */
    public function add_block_categories($categories, $post) {

        return $categories;
    }
}