<?php
/**
 * Plugin Name: Taskip Templates Showcase
 * Plugin URI: https://taskip.com
 * Description: A plugin to showcase Taskip document templates and Usecases with custom URL structure
 * Version: 1.2.0
 * Author: Taskip
 * Author URI: https://taskip.com
 * Text Domain: taskip-templates
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TASKIP_TEMPLATES_VERSION', '1.2.0');
define('TASKIP_TEMPLATES_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TASKIP_TEMPLATES_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TASKIP_TEMPLATES_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Load required files
require_once TASKIP_TEMPLATES_PLUGIN_DIR . 'includes/class-taskip-templates.php';
require_once TASKIP_TEMPLATES_PLUGIN_DIR . 'includes/class-taskip-post-types.php';
require_once TASKIP_TEMPLATES_PLUGIN_DIR . 'includes/class-taskip-taxonomies.php';
require_once TASKIP_TEMPLATES_PLUGIN_DIR . 'includes/class-taskip-metaboxes.php';
require_once TASKIP_TEMPLATES_PLUGIN_DIR . 'includes/class-taskip-shortcodes.php';
require_once TASKIP_TEMPLATES_PLUGIN_DIR . 'includes/class-taskip-ajax.php';
require_once TASKIP_TEMPLATES_PLUGIN_DIR . 'admin/class-taskip-admin.php';

// Initialize the plugin
$taskip_templates = new Taskip_Templates();
$taskip_templates->initialize();

// Initialize admin class if in admin area
if (is_admin()) {
    $taskip_admin = new Taskip_Admin();
    $taskip_admin->initialize();
}

// Register activation/deactivation hooks
register_activation_hook(__FILE__, 'taskip_templates_activate');
register_deactivation_hook(__FILE__, 'taskip_templates_deactivate');

/**
 * Plugin activation function
 */
function taskip_templates_activate() {
    // Register post types to ensure permalinks are flushed properly
    $post_types = new Taskip_Post_Types();
    $post_types->register_post_types();

    // Register taxonomies
    $taxonomies = new Taskip_Taxonomies();
    $taxonomies->register_taxonomies();

     //Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Plugin deactivation function
 */
function taskip_templates_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}