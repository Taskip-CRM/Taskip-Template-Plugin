<?php
/**
 * Taxonomies class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Taxonomies class
 */
class Taskip_Taxonomies {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize properties
    }

    /**
     * Initialize taxonomies
     */
    public function initialize() {
        add_action('init', array($this, 'register_taxonomies'));
    }

    /**
     * Register taxonomies for templates
     */
    public function register_taxonomies() {
        // Template Type Taxonomy
        $type_labels = array(
            'name'              => _x('Template Types', 'taxonomy general name', 'taskip-templates'),
            'singular_name'     => _x('Template Type', 'taxonomy singular name', 'taskip-templates'),
            'search_items'      => __('Search Template Types', 'taskip-templates'),
            'all_items'         => __('All Template Types', 'taskip-templates'),
            'parent_item'       => __('Parent Template Type', 'taskip-templates'),
            'parent_item_colon' => __('Parent Template Type:', 'taskip-templates'),
            'edit_item'         => __('Edit Template Type', 'taskip-templates'),
            'update_item'       => __('Update Template Type', 'taskip-templates'),
            'add_new_item'      => __('Add New Template Type', 'taskip-templates'),
            'new_item_name'     => __('New Template Type Name', 'taskip-templates'),
            'menu_name'         => __('Template Types', 'taskip-templates'),
        );

        $type_args = array(
            'hierarchical'      => true,
            'labels'            => $type_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'template-type'),
        );

        register_taxonomy('template_type', array('taskip_template'), $type_args);

        // Industry Taxonomy
        $industry_labels = array(
            'name'              => _x('Industries', 'taxonomy general name', 'taskip-templates'),
            'singular_name'     => _x('Industry', 'taxonomy singular name', 'taskip-templates'),
            'search_items'      => __('Search Industries', 'taskip-templates'),
            'all_items'         => __('All Industries', 'taskip-templates'),
            'parent_item'       => __('Parent Industry', 'taskip-templates'),
            'parent_item_colon' => __('Parent Industry:', 'taskip-templates'),
            'edit_item'         => __('Edit Industry', 'taskip-templates'),
            'update_item'       => __('Update Industry', 'taskip-templates'),
            'add_new_item'      => __('Add New Industry', 'taskip-templates'),
            'new_item_name'     => __('New Industry Name', 'taskip-templates'),
            'menu_name'         => __('Industries', 'taskip-templates'),
        );

        $industry_args = array(
            'hierarchical'      => true,
            'labels'            => $industry_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'industry'),
        );

        register_taxonomy('template_industry', array('taskip_template'), $industry_args);
    }
}