<?php
/**
 * The template for displaying template archives
 *
 * @package Taskip Templates Showcase
 */

get_header(); ?>

    <div class="taskip-templates-archive">
        <div class="container">
            <header class="taskip-templates-header">
                <div class="taskip taskip-inter taskip-pricing pt-120 pb-120">
                    <div class="col-md-12">
                        <div class="subtitle-two"><?php echo esc_html__('Taskip Templates Library','taskip-templates')?></div>
                        <h1 class="taskip-second-heading-three text-center"><?php echo esc_html__('Business Agreements, Proposals, Contracts, SOPs & Scope of Work Documents','taskip-templates')?></h1>
                        <p class="template-section-para"><?php _e("Welcome to the Taskip Templates Library. your smart shortcut to getting things done faster. Whether you're drafting a business agreement, writing a proposal, creating a contract, building an SOP, or outlining a scope of work, our ready-to-use templates are built to help you move quicker and work smarter. Perfect for modern teams, freelancers, and growing businesses.", "taskip-templates"); ?></p>
                    </div>
                </div>
            </header>

            <div class="taskip-templates-filters">
                <div class="taskip-filter-industries">
                    <ul class="taskip-filter-list">
                        <?php
                        $template_industries = get_terms(array(
                            "taxonomy" => "template_industry",
                            "hide_empty" => false
                        ));

                        if (!empty($template_industries) && !is_wp_error($template_industries)) :
                            foreach ($template_industries as $industry) :

                                $image_id = get_term_meta($industry->term_id, 'taxonomy_image_id', true);
                                $image_url = '';

                                if ($image_id) {
                                    $image_url = wp_get_attachment_url($image_id);
                                }

                                ?>
                                <li class="taskip-filter-item">
                                    <div id="taxonomy-image-preview" class="taxonomy-image-preview">
                                        <?php if ($image_url) : ?>
                                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($industry->name); ?>" style="max-width: 300px;">
                                        <?php endif; ?>
                                    </div>
                                    <a class="title" href="<?php echo esc_url(get_term_link($industry)); ?>"><?php echo esc_html($industry->name); ?></a>
                                    <span class="count">(<?php echo esc_html($industry->count); ?>) <?php echo esc_html__('Templates','taskip-templates')?></span>
                                </li>
                            <?php endforeach;
                        endif; ?>
                    </ul>
                </div>
            </div>
            <div class="taskip-template-type-with-search">
                <div class="template-type-list">
                    <h3><?php echo esc_html__('Template Type:')?></h3>
                    <ul>
                        <?php
                        $template_industries = get_terms(array(
                            "taxonomy" => "template_type",
                            "hide_empty" => false
                        ));

                        if (!empty($template_industries) && !is_wp_error($template_industries)) :
                            foreach ($template_industries as $industry) :
                                ?>
                                <li class="taskip-filter-item">
                                    <a class="title" href="<?php echo esc_url(get_term_link($industry)); ?>"><?php echo esc_html($industry->name); ?></a>
                                </li>
                            <?php endforeach;
                        endif; ?>
                    </ul>
                </div>
                <div class="template-search-form-wrapper">
                    <form action="#" method="get">
                        <input type="text" name="search" value="" placeholder="<?php echo esc_html__('Search Templates','taskip-templates');?>">
                    </form>
                </div>
            </div>

            <div class="taskip-templates-grid">
                <?php if (have_posts()) : ?>
                    <?php
                    while (have_posts()) : the_post();
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
                                    <a href="<?php echo esc_url($preview_url); ?>"  target="_blank" class="taskip-template-view"><?php _e("Use Template", "taskip-templates"); ?></a>
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
        </div>
    </div>

<?php get_footer(); ?>