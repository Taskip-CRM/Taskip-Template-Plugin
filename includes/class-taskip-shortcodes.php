<?php
/**
 * Shortcodes class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcodes class
 */
class Taskip_Shortcodes {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize properties
    }

    /**
     * Initialize shortcodes
     */
    public function initialize() {
        add_shortcode('taskip_templates', array($this, 'templates_shortcode'));
        add_shortcode('taskip_template_categories', array($this, 'template_categories_shortcode'));
        add_shortcode('taskip_template_industries', array($this, 'template_industries_shortcode'));
    }

    /**
     * Shortcode to display templates
     *
     * @param array $atts Shortcode attributes.
     * @return string The HTML output of the shortcode.
     */
    public function templates_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => '',
            'industry' => '',
            'limit' => 12,
            'columns' => 3,
            'orderby' => 'date',
            'order' => 'DESC'
        ), $atts, 'taskip_templates');

        // Build query args
        $args = array(
            'post_type' => 'taskip_template',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order']
        );

        // Add taxonomy query if specified
        $tax_query = array();

        if (!empty($atts['type'])) {
            $tax_query[] = array(
                'taxonomy' => 'template_type',
                'field'    => 'slug',
                'terms'    => explode(',', $atts['type']),
            );
        }

        if (!empty($atts['industry'])) {
            $tax_query[] = array(
                'taxonomy' => 'template_industry',
                'field'    => 'slug',
                'terms'    => explode(',', $atts['industry']),
            );
        }

        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        // Get templates
        $templates = new WP_Query($args);

        // Start output buffer
        ob_start();

        if ($templates->have_posts()) :
            echo '<div class="taskip-templates-grid columns-' . esc_attr($atts['columns']) . '">';

            while ($templates->have_posts()) : $templates->the_post();
                $template_id = get_the_ID();
                $preview_image = get_the_post_thumbnail_url($template_id, 'large');
                $template_type = get_the_terms($template_id, 'template_type');
                $template_type_name = is_array($template_type) ? $template_type[0]->name : '';
                $template_type_slug = is_array($template_type) ? $template_type[0]->slug : '';

                ?>
                <div class="taskip-template-item">
                    <div class="taskip-template-image">
                        <?php if ($preview_image) : ?>
                            <img src="<?php echo esc_url($preview_image); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php else : ?>
                            <div class="taskip-template-placeholder"></div>
                        <?php endif; ?>
                        <div class="taskip-template-overlay">
                            <a href="<?php the_permalink(); ?>" class="taskip-template-view"><?php _e('View Template', 'taskip-templates'); ?></a>
                        </div>
                    </div>
                    <div class="taskip-template-content">
                        <h3 class="taskip-template-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if ($template_type_name) : ?>
                            <div class="taskip-template-type">
                                <a href="<?php echo esc_url(get_term_link($template_type[0])); ?>"><?php echo esc_html($template_type_name); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="taskip-template-excerpt"><?php the_excerpt(); ?></div>
                    </div>
                </div>
            <?php
            endwhile;

            echo '</div>';

            // Pagination
            echo '<div class="taskip-templates-pagination">';
            $big = 999999999;
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $templates->max_num_pages
            ));
            echo '</div>';
        else :
            echo '<p class="taskip-no-templates">' . __('No templates found.', 'taskip-templates') . '</p>';
        endif;

        // Reset post data
        wp_reset_postdata();

        // Return the output
        return ob_get_clean();
    }

    /**
     * Shortcode to display template categories
     *
     * @param array $atts Shortcode attributes.
     * @return string The HTML output of the shortcode.
     */
    public function template_categories_shortcode($atts) {
        $atts = shortcode_atts(array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 1,
            'show_count' => 1
        ), $atts, 'taskip_template_categories');

        $terms = get_terms(array(
            'taxonomy' => 'template_type',
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'hide_empty' => (bool) $atts['hide_empty']
        ));

        if (empty($terms) || is_wp_error($terms)) {
            return '<p class="taskip-no-categories">' . __('No template categories found.', 'taskip-templates') . '</p>';
        }

        ob_start();

        echo '<div class="taskip-template-categories">';

        foreach ($terms as $term) {
            // Get category image from term meta if available, otherwise use a placeholder
            $category_image = get_term_meta($term->term_id, 'category_image', true);
            $count_html = $atts['show_count'] ? ' <span class="count">(' . $term->count . ')</span>' : '';

            ?>
            <div class="taskip-category-item">
                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="taskip-category-link">
                    <?php if (!empty($category_image)) : ?>
                        <div class="taskip-category-image">
                            <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($term->name); ?>">
                        </div>
                    <?php else : ?>
                        <div class="taskip-category-icon">
                            <span class="dashicons dashicons-media-document"></span>
                        </div>
                    <?php endif; ?>
                    <h3 class="taskip-category-title"><?php echo esc_html($term->name); ?><?php echo $count_html; ?></h3>
                </a>
            </div>
            <?php
        }

        echo '</div>';

        return ob_get_clean();
    }

    /**
     * Shortcode to display template industries
     *
     * @param array $atts Shortcode attributes.
     * @return string The HTML output of the shortcode.
     */
    public function template_industries_shortcode($atts) {
        $atts = shortcode_atts(array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 1,
            'show_count' => 1
        ), $atts, 'taskip_template_industries');

        $terms = get_terms(array(
            'taxonomy' => 'template_industry',
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'hide_empty' => (bool) $atts['hide_empty']
        ));

        if (empty($terms) || is_wp_error($terms)) {
            return '<p class="taskip-no-industries">' . __('No template industries found.', 'taskip-templates') . '</p>';
        }

        ob_start();

        echo '<div class="taskip-template-industries">';

        foreach ($terms as $term) {
            // Get industry image from term meta if available, otherwise use a placeholder
            $industry_image = get_term_meta($term->term_id, 'industry_image', true);
            $count_html = $atts['show_count'] ? ' <span class="count">(' . $term->count . ')</span>' : '';

            ?>
            <div class="taskip-industry-item">
                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="taskip-industry-link">
                    <?php if (!empty($industry_image)) : ?>
                        <div class="taskip-industry-image">
                            <img src="<?php echo esc_url($industry_image); ?>" alt="<?php echo esc_attr($term->name); ?>">
                        </div>
                    <?php else : ?>
                        <div class="taskip-industry-icon">
                            <span class="dashicons dashicons-building"></span>
                        </div>
                    <?php endif; ?>
                    <h3 class="taskip-industry-title"><?php echo esc_html($term->name); ?><?php echo $count_html; ?></h3>
                </a>
            </div>
            <?php
        }

        echo '</div>';

        return ob_get_clean();
    }
}