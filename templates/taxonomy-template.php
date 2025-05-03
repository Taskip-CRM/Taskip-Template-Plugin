<?php
/**
 * The template for displaying template taxonomy archives
 *
 * @package Taskip Templates Showcase
 */

get_header(); ?>

    <div class="taskip-templates-taxonomy">
        <div class="taskip-templates-container">
            <header class="taskip-templates-header">
                <?php
                $term = get_queried_object();
                ?>
                <h1 class="taskip-templates-title">
                    <?php
                    if (is_tax("template_type")) {
                        printf(__("%s Templates", "taskip-templates"), single_term_title("", false));
                    } elseif (is_tax("template_industry")) {
                        printf(__("Templates for %s Industry", "taskip-templates"), single_term_title("", false));
                    }
                    ?>
                </h1>

                <?php if ($term->description) : ?>
                    <div class="taskip-term-description">
                        <?php echo wpautop($term->description); ?>
                    </div>
                <?php endif; ?>
            </header>

            <div class="taskip-templates-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="taskip-template-item">
                            <div class="taskip-template-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail("medium", array("class" => "taskip-template-thumb")); ?>
                                    </a>
                                <?php else :
                                    $preview_url = get_post_meta(get_the_ID(), "_taskip_preview_url", true);
                                    if ($preview_url) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <img src="<?php echo esc_url($preview_url); ?>" alt="<?php the_title_attribute(); ?>" class="taskip-template-thumb">
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="taskip-template-placeholder"></div>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="taskip-template-overlay">
                                    <a href="<?php the_permalink(); ?>" class="taskip-template-view"><?php _e("View Template", "taskip-templates"); ?></a>
                                </div>
                            </div>

                            <div class="taskip-template-content">
                                <h2 class="taskip-template-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <?php
                                // If we're on an industry page, show the template type
                                // If we're on a template type page, show the industry
                                if (is_tax("template_industry")) {
                                    $template_type = get_the_terms(get_the_ID(), "template_type");
                                    if ($template_type) : ?>
                                        <div class="taskip-template-type">
                                            <a href="<?php echo esc_url(get_term_link($template_type[0])); ?>"><?php echo esc_html($template_type[0]->name); ?></a>
                                        </div>
                                    <?php endif;
                                } elseif (is_tax("template_type")) {
                                    $template_industry = get_the_terms(get_the_ID(), "template_industry");
                                    if ($template_industry) : ?>
                                        <div class="taskip-template-industry">
                                            <a href="<?php echo esc_url(get_term_link($template_industry[0])); ?>"><?php echo esc_html($template_industry[0]->name); ?></a>
                                        </div>
                                    <?php endif;
                                }
                                ?>

                                <div class="taskip-template-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>

                    <div class="taskip-templates-pagination">
                        <?php
                        echo paginate_links(array(
                            "prev_text" => __("&laquo; Previous", "taskip-templates"),
                            "next_text" => __("Next &raquo;", "taskip-templates")
                        ));
                        ?>
                    </div>
                <?php else : ?>
                    <p class="taskip-no-templates"><?php _e("No templates found.", "taskip-templates"); ?></p>
                <?php endif; ?>
            </div>

            <div class="taskip-templates-back">
                <a href="<?php echo esc_url(get_post_type_archive_link('taskip_template')); ?>" class="taskip-back-button">
                    <?php _e("View All Templates", "taskip-templates"); ?>
                </a>
            </div>
        </div>
    </div>

<?php get_footer(); ?>