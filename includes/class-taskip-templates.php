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

        // Initialize metaboxes
        $metaboxes = new Taskip_Metaboxes();
        $metaboxes->initialize();

        // Initialize Shortcodes
        $shortcodes = new Taskip_Shortcodes();
        $shortcodes->initialize();


        // Register widget

        // Enqueue frontend scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Load template files
        add_filter('template_include', array($this, 'template_loader'));

        add_action('wp_ajax_template_search', array($this, 'handle_template_search') );
        add_action('wp_ajax_nopriv_template_search', array($this,'handle_template_search'));

    }


    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {

        if (is_singular('taskip_template')) {
            // Enqueue jQuery (if not already loaded)
//            wp_enqueue_script('jquery');

            // Enqueue custom CSS
            wp_enqueue_style(
                'taskip-template-download',
                TASKIP_TEMPLATES_PLUGIN_URL . '/assets/css/taskip-template-download.css',
                array(),
                time()//TASKIP_TEMPLATES_VERSION
            );

            // Enqueue custom JS
            wp_enqueue_script(
                'taskip-template-download',
                TASKIP_TEMPLATES_PLUGIN_URL . '/assets/js/taskip-template-download.js',
                array('jquery'),
                TASKIP_TEMPLATES_VERSION,
                true
            );

            // Localize script for AJAX
            wp_localize_script('taskip-template-download', 'taskip_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('taskip_download_nonce')
            ));
        }

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
                'nonce' => wp_create_nonce('template_search_nonce')
            ));

        }
    }
    function handle_template_search() {
        check_ajax_referer('template_search_nonce', 'nonce');

        $search = sanitize_text_field($_POST['search']);

        $args = array(
            'post_type' => 'taskip_template', // Replace with your custom post type if needed
            'posts_per_page' => -1,
            's' => $search
        );

        $query = new WP_Query($args);
        ob_start();

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $preview_url = get_post_meta(get_the_ID(), "_taskip_preview_url", true);
                ?>
                <div class="taskip-template-item">
                    <div class="taskip-template-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail("large", array("class" => "taskip-template-thumb")); ?>
                            </a>
                        <?php else :?>
                            <a href="<?php the_permalink(); ?>">
                                <div class="taskip-template-placeholder"></div>
                            </a>
                        <?php endif; ?>

                        <div class="taskip-template-overlay">
                            <a href="<?php the_permalink(); ?>" class="taskip-template-view"><?php _e("Preview", "taskip-templates"); ?></a>
                            <a href="<?php echo esc_url($preview_url); ?>" target="_blank" class="taskip-template-view"><?php _e("Use Template", "taskip-templates"); ?></a>
                        </div>
                    </div>

                    <div class="taskip-template-content">
                        <?php
                        $template_type = get_the_terms(get_the_ID(), "template_type");
                        if ($template_type) : ?>
                            <div class="taskip-template-type">
                                <a href="<?php echo esc_url(get_term_link($template_type[0])); ?>"><?php echo esc_html($template_type[0]->name); ?></a>
                            </div>
                        <?php endif; ?>
                        <h2 class="taskip-template-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                    </div>
                </div>
            <?php endwhile;
        else : ?>
            <p class="taskip-no-templates"><?php _e("No templates found.", "taskip-templates"); ?></p>
        <?php endif;

        wp_reset_postdata();

        $html = ob_get_clean();
        wp_send_json_success($html);
        die();
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