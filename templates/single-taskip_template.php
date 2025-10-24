<?php
/**
 * The template for displaying single template
 *
 * @package Taskip Templates Showcase
 */

get_header();

$preview_url = get_post_meta(get_the_ID(), "_taskip_preview_url", true);
?>

    <div class="taskip-template-single taskip-single-blog-part">
    <div class="taskip-template-container container single-blog-body-wraper">
<?php while (have_posts()) : the_post(); ?>
    <div class="taskip-template-header">
        <div class="single-blog-main-heading margin-bottom-40">
            <h1><?php the_title(); ?></h1>
        </div>


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
                <div class="taskip-template-type industry">
                    <span class="meta-label"><?php _e("Industry:", "taskip-templates"); ?></span>
                    <?php
                        foreach($template_industry as $industry){
                            printf('<a href="%s">%s</a>',esc_url(get_term_link($industry)),esc_html($industry->name));
                        }
                    ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="taskip-template-content-wrap">
    <div class="taskip-template-main">
        <div class="taskip-template-image">
            <a href="<?php echo esc_url($preview_url)?>" target="_blank">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail("large", array("class" => "taskip-main-image")); ?>
            <?php else : ?>
                    <div class="taskip-template-placeholder"></div>
            <?php endif; ?>
            </a>
        </div>

        <div class="taskip-template-content single-blog-body">
            <?php the_content(); ?>
        </div>
        <div class="taskip-related-templates margin-bottom-60">
            <h3><?php _e("Related Templates", "taskip-templates"); ?></h3>
            <div class="taskip-templates-grid related-item">
            <?php
            // Get related templates based on same template type
            $related_args = array(
                'post_type' => 'taskip_template',
                'posts_per_page' => 4,
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
                    $preview_url = get_post_meta(get_the_ID(), "_taskip_preview_url", true);
                    ?>
                    <div class="taskip-template-item">
                        <div class="taskip-template-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail("large", array("class" => "taskip-template-thumb")); ?>
                                </a>
                            <?php else: ?>
                                <a href="<?php the_permalink(); ?>">
                                    <div class="taskip-template-placeholder"></div>
                                </a>
                            <?php endif; ?>

                            <div class="taskip-template-overlay">
                                <a href="<?php the_permalink(); ?>" class="taskip-template-view"><?php _e("Preview", "taskip-templates"); ?></a>
                                <a href="<?php echo esc_url($preview_url);?>" class="taskip-template-view"><?php _e("Use Template", "taskip-templates"); ?></a>
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
                wp_reset_postdata();
            else : ?>
                <p><?php _e("No related templates found.", "taskip-templates"); ?></p>
            <?php endif; ?>
        </div>

        </div>
    </div>

    <div class="taskip-template-sidebar">
    <div class="taskip-template-actions">
        <h3><?php _e("Your pre-built template is ready", "taskip-templates"); ?></h3>
            <?php
            $template_features = get_post_meta(get_the_ID(), "_taskip_template_features", true);
            if ($template_features) : ?>
            <ul>
                <?php
                $features = explode("\n", $template_features);
                foreach ($features as $feature) :
                    $feature = trim($feature);
                    if (!empty($feature)) : ?>
                        <li><i class="fa-regular fa-circle-check"></i> <?php echo esc_html($feature); ?></li>
                    <?php endif;
                endforeach; ?>
            </ul>
        <?php
            endif;
        ?>

        <a href="<?php echo esc_url($preview_url)?>" class="taskip-signup-btn" target="_blank"><?php _e("Use This Template", "taskip-templates"); ?></a>
        <?php echo do_shortcode('[taskip_template_download]'); ?>
    </div>
        <div class="ai_generator_tools">
            <div class="ib-tool-card">
                <div class="ib-tool-header" style="background:linear-gradient(135deg, #00b289 0%, #7db1a5 100%);color:#ffffff">
                    <h3 class="ib-tool-name" style="color:#ffffff">Free Ai Scope of Work Generator</h3>
                    <p class="ib-tool-tagline" style="color:#ffffff">Generate comprehensive SOW documents for client projects, service offerings, and business partnerships. Streamline your project documentation with professional templates.</p>
                </div>
                <div class="ib-tool-cta" style="padding-top: 0;">
                    <a href="https://taskip.net/tools/free-ai-scope-of-work-generator/?ref=template-page" style="color:#00b289" target="_blank" rel="noopener">
                        <span>Try Free Scope of Work Generator</span>
                    </a>
                </div>
            </div>
        </div>


    </div>
<?php endwhile; ?>
    </div>
    </div>

<?php get_footer(); ?>