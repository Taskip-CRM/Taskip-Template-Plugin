<?php
/**
 * The template for displaying single template
 *
 * @package Taskip Templates Showcase
 */

get_header(); ?>

    <div class="taskip-template-single">
    <div class="taskip-template-container">
<?php while (have_posts()) : the_post(); ?>
    <div class="taskip-template-header">
        <h1 class="taskip-template-title"><?php the_title(); ?></h1>

        <?php
        $template_type = get_the_terms(get_the_ID(), "template_type");
        $template_industry = get_the_terms(get_the_ID(), "template_industry");
        ?>

        <div class="taskip-template-meta">
            <?php if ($template_type) : ?>
                <div class="taskip-template-type">
                    <span class="meta-label"><?php _e("Type:", "taskip-templates"); ?></span>
                    <a href="<?php echo esc_url(get_term_link($template_type[0])); ?>"><?php echo esc_html($template_type[0]->name); ?></a>
                </div>
            <?php endif; ?>

            <?php if ($template_industry) : ?>
                <div class="taskip-template-industry">
                    <span class="meta-label"><?php _e("Industry:", "taskip-templates"); ?></span>
                    <a href="<?php echo esc_url(get_term_link($template_industry[0])); ?>"><?php echo esc_html($template_industry[0]->name); ?></a>
                </div>
            <?php endif; ?>

            <?php
            $template_version = get_post_meta(get_the_ID(), "_taskip_template_version", true);
            if ($template_version) : ?>
                <div class="taskip-template-version">
                    <span class="meta-label"><?php _e("Version:", "taskip-templates"); ?></span>
                    <span><?php echo esc_html($template_version); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="taskip-template-content-wrap">
    <div class="taskip-template-main">
        <div class="taskip-template-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail("large", array("class" => "taskip-main-image")); ?>
            <?php else :
                $preview_url = get_post_meta(get_the_ID(), "_taskip_preview_url", true);
                if ($preview_url) : ?>
                    <img src="<?php echo esc_url($preview_url); ?>" alt="<?php the_title_attribute(); ?>" class="taskip-main-image">
                <?php else : ?>
                    <div class="taskip-template-placeholder"></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="taskip-template-content">
            <?php the_content(); ?>
        </div>

        <?php
        $template_features = get_post_meta(get_the_ID(), "_taskip_template_features", true);
        if ($template_features) : ?>
            <div class="taskip-template-features">
                <h3><?php _e("Template Features", "taskip-templates"); ?></h3>
                <ul>
                    <?php
                    $features = explode("\n", $template_features);
                    foreach ($features as $feature) :
                        $feature = trim($feature);
                        if (!empty($feature)) : ?>
                            <li><?php echo esc_html($feature); ?></li>
                        <?php endif;
                    endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="taskip-template-sidebar">
    <div class="taskip-template-actions">
        <h3><?php _e("Template Actions", "taskip-templates"); ?></h3>

        <?php
        $demo_url = get_post_meta(get_the_ID(), "_taskip_demo_url", true);
        if ($demo_url) : ?>
            <a href="<?php echo esc_url($demo_url); ?>" class="taskip-demo-btn" target="_blank"><?php _e("View Demo", "taskip-templates"); ?></a>
        <?php endif; ?>

        <a href="https://taskip.com/signup" class="taskip-signup-btn"><?php _e("Use This Template", "taskip-templates"); ?></a>
    </div>

    <div class="taskip-related-templates">
    <h3><?php _e("Related Templates", "taskip-templates"); ?></h3>

    <?php
    // Get related templates based on same template type
    $related_args = array(
        'post_type' => 'taskip_template',
        'posts_per_page' => 3,
        'post__not_in' => array(get_the_ID()),
        'orderby' => 'rand'
    );

    if ($template_type) {
        $related_args['tax_query'] = array(
            array(
                'taxonomy' => 'template_type',
                'field' => 'term_id',
                'terms' => $template_type[0]->term_id
            )
        );
    }

    $related_templates = new WP_Query($related_args);

    if ($related_templates->have_posts()) :
        while ($related_templates->have_posts()) : $related_templates->the_post();
            $related_image = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            if (!$related_image) {
                $related_image = get_post_meta(get_the_ID(), "_taskip_preview_url", true);
            }
            ?>
            <div class="taskip-related-item">
                <a href="<?php the_permalink(); ?>" class="taskip-related-link">
                    <?php if ($related_image) : ?>
                        <img src="<?php echo esc_url($related_image); ?>" alt="<?php the_title_attribute(); ?>" class="taskip-related-image">
                    <?php else : ?>
                        <div class="taskip-related-placeholder"></div>
                    <?php endif; ?>
                    <h4 class="taskip-related-title"><?php the_title(); ?></h4>
                </a>
            </div>
        <?php endwhile;
        wp_reset_postdata();
    else : ?>
        <p><?php _e("No related templates found.", "taskip-templates"); ?></p>
    <?php endif; ?>
    </div>
    </div>
    </div>
<?php endwhile; ?>
    </div>
    </div>

<?php get_footer(); ?>