<?php
/**
 * Templates Widget class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Templates Widget class
 */
class Taskip_Templates_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'taskip_templates_widget',
            __('Taskip Templates', 'taskip-templates'),
            array(
                'description' => __('Display a list of Taskip document templates.', 'taskip-templates'),
                'classname' => 'taskip-templates-widget',
            )
        );
    }

    /**
     * Widget frontend display
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $template_type = !empty($instance['template_type']) ? $instance['template_type'] : '';
        $template_industry = !empty($instance['template_industry']) ? $instance['template_industry'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
        $order = !empty($instance['order']) ? $instance['order'] : 'DESC';
        $show_image = !empty($instance['show_image']) ? (bool) $instance['show_image'] : false;

        // Query args
        $args = array(
            'post_type' => 'taskip_template',
            'posts_per_page' => $number,
            'orderby' => $orderby,
            'order' => $order,
        );

        // Add taxonomy query if specified
        $tax_query = array();

        if (!empty($template_type)) {
            $tax_query[] = array(
                'taxonomy' => 'template_type',
                'field'    => 'slug',
                'terms'    => $template_type,
            );
        }

        if (!empty($template_industry)) {
            $tax_query[] = array(
                'taxonomy' => 'template_industry',
                'field'    => 'slug',
                'terms'    => $template_industry,
            );
        }

        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        // Get templates
        $templates = new WP_Query($args);

        if ($templates->have_posts()) {
            echo '<ul class="taskip-templates-widget-list">';

            while ($templates->have_posts()) {
                $templates->the_post();

                echo '<li class="taskip-widget-template-item">';

                if ($show_image && has_post_thumbnail()) {
                    echo '<a href="' . esc_url(get_permalink()) . '" class="taskip-widget-template-image">';
                    the_post_thumbnail('thumbnail');
                    echo '</a>';
                }

                echo '<div class="taskip-widget-template-content">';
                echo '<h4 class="taskip-widget-template-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h4>';

                $template_type_terms = get_the_terms(get_the_ID(), 'template_type');
                if ($template_type_terms && !is_wp_error($template_type_terms)) {
                    echo '<span class="taskip-widget-template-type">' . esc_html($template_type_terms[0]->name) . '</span>';
                }

                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';

            // "View All" link
            if (!empty($instance['show_view_all']) && (bool) $instance['show_view_all']) {
                $view_all_url = get_post_type_archive_link('taskip_template');
                echo '<p class="taskip-widget-view-all"><a href="' . esc_url($view_all_url) . '">' . __('View All Templates', 'taskip-templates') . '</a></p>';
            }
        } else {
            echo '<p class="taskip-widget-no-templates">' . __('No templates found.', 'taskip-templates') . '</p>';
        }

        // Reset post data
        wp_reset_postdata();

        echo $args['after_widget'];
    }

    /**
     * Widget backend form
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Taskip Templates', 'taskip-templates');
        $template_type = !empty($instance['template_type']) ? $instance['template_type'] : '';
        $template_industry = !empty($instance['template_industry']) ? $instance['template_industry'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
        $order = !empty($instance['order']) ? $instance['order'] : 'DESC';
        $show_image = !empty($instance['show_image']) ? (bool) $instance['show_image'] : false;
        $show_view_all = !empty($instance['show_view_all']) ? (bool) $instance['show_view_all'] : true;

        // Get template types
        $template_types = get_terms(array(
            'taxonomy' => 'template_type',
            'hide_empty' => false,
        ));

        // Get industries
        $template_industries = get_terms(array(
            'taxonomy' => 'template_industry',
            'hide_empty' => false,
        ));

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'taskip-templates'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('template_type')); ?>"><?php esc_html_e('Template Type:', 'taskip-templates'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('template_type')); ?>" name="<?php echo esc_attr($this->get_field_name('template_type')); ?>">
                <option value=""><?php esc_html_e('All Template Types', 'taskip-templates'); ?></option>
                <?php foreach ($template_types as $type) : ?>
                    <option value="<?php echo esc_attr($type->slug); ?>" <?php selected($template_type, $type->slug); ?>><?php echo esc_html($type->name); ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('template_industry')); ?>"><?php esc_html_e('Industry:', 'taskip-templates'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('template_industry')); ?>" name="<?php echo esc_attr($this->get_field_name('template_industry')); ?>">
                <option value=""><?php esc_html_e('All Industries', 'taskip-templates'); ?></option>
                <?php foreach ($template_industries as $industry) : ?>
                    <option value="<?php echo esc_attr($industry->slug); ?>" <?php selected($template_industry, $industry->slug); ?>><?php echo esc_html($industry->name); ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of templates to show:', 'taskip-templates'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order By:', 'taskip-templates'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Date', 'taskip-templates'); ?></option>
                <option value="title" <?php selected($orderby, 'title'); ?>><?php esc_html_e('Title', 'taskip-templates'); ?></option>
                <option value="rand" <?php selected($orderby, 'rand'); ?>><?php esc_html_e('Random', 'taskip-templates'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:', 'taskip-templates'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                <option value="DESC" <?php selected($order, 'DESC'); ?>><?php esc_html_e('Descending', 'taskip-templates'); ?></option>
                <option value="ASC" <?php selected($order, 'ASC'); ?>><?php esc_html_e('Ascending', 'taskip-templates'); ?></option>
            </select>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_image); ?> id="<?php echo esc_attr($this->get_field_id('show_image')); ?>" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>"><?php esc_html_e('Show featured image', 'taskip-templates'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_view_all); ?> id="<?php echo esc_attr($this->get_field_id('show_view_all')); ?>" name="<?php echo esc_attr($this->get_field_name('show_view_all')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_view_all')); ?>"><?php esc_html_e('Show "View All" link', 'taskip-templates'); ?></label>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['template_type'] = (!empty($new_instance['template_type'])) ? sanitize_text_field($new_instance['template_type']) : '';
        $instance['template_industry'] = (!empty($new_instance['template_industry'])) ? sanitize_text_field($new_instance['template_industry']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        $instance['orderby'] = (!empty($new_instance['orderby'])) ? sanitize_text_field($new_instance['orderby']) : 'date';
        $instance['order'] = (!empty($new_instance['order'])) ? sanitize_text_field($new_instance['order']) : 'DESC';
        $instance['show_image'] = (!empty($new_instance['show_image'])) ? (bool) $new_instance['show_image'] : false;
        $instance['show_view_all'] = (!empty($new_instance['show_view_all'])) ? (bool) $new_instance['show_view_all'] : true;

        return $instance;
    }
}