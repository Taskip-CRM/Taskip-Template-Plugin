<?php
/**
 * The template for displaying tools archives
 *
 * @package Taskip Templates Showcase
 */

get_header(); ?>

<div class="taskip-tools-archive">
    <div class="container">
        <header class="taskip-tools-header">
            <div class="taskip taskip-inter taskip-pricing pt-120 pb-120">
                <div class="col-md-12">
                    <?php
                    // Get archive page settings
                    $archive_title = get_option('taskip_tools_archive_title', 'Free Tools Collection');
                    $archive_paragraph = get_option('taskip_tools_archive_paragraph', 'Discover our collection of free online tools designed to help you work smarter and faster. From generators to converters, find the perfect tool for your needs.');
                    ?>
                    <div class="subtitle-two"><?php echo esc_html__('Taskip Free Tools','taskip-templates')?></div>
                    <h1 class="taskip-second-heading-three text-center"><?php echo esc_html($archive_title); ?></h1>
                    <p class="template-section-para"><?php echo esc_html($archive_paragraph); ?></p>
                </div>
            </div>
        </header>

        <div class="taskip-tools-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); 
                    // Get tool metadata
                    $card_title = get_post_meta(get_the_ID(), '_taskip_tool_card_title', true);
                    $tagline = get_post_meta(get_the_ID(), '_taskip_tool_tagline', true);
                    $description = get_post_meta(get_the_ID(), '_taskip_tool_description', true);
                    $features = get_post_meta(get_the_ID(), '_taskip_tool_features', true);
                    $cta_url = get_post_meta(get_the_ID(), '_taskip_tool_cta_url', true);
                    $gradient_start = get_post_meta(get_the_ID(), '_taskip_tool_gradient_start', true);
                    $gradient_end = get_post_meta(get_the_ID(), '_taskip_tool_gradient_end', true);
                    
                    // Set defaults and fallbacks
                    $display_title = !empty($card_title) ? $card_title : get_the_title();
                    $gradient_start = !empty($gradient_start) ? $gradient_start : '#00b289';
                    $gradient_end = !empty($gradient_end) ? $gradient_end : '#7db1a5';
                    $tagline = !empty($tagline) ? $tagline : get_the_excerpt();
                    $description = !empty($description) ? $description : 'A helpful tool for your daily tasks.';
                    $cta_url = !empty($cta_url) ? $cta_url : get_permalink();
                    
                    // Process features into array
                    $features_array = array();
                    if (!empty($features)) {
                        $features_array = array_filter(array_map('trim', explode("\n", $features)));
                    }
                ?>
                    <div class="ib-tool-card">
                        <div class="ib-tool-header" style="background:linear-gradient(135deg, <?php echo esc_attr($gradient_start); ?> 0%, <?php echo esc_attr($gradient_end); ?> 100%);color:#ffffff">
                            <h3 class="ib-tool-name" style="color:#ffffff"><?php echo esc_html($display_title); ?></h3>
                            <?php if ($tagline) : ?>
                                <p class="ib-tool-tagline" style="color:#ffffff"><?php echo esc_html($tagline); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="ib-tool-body">
                            <?php if ($description) : ?>
                                <p class="ib-tool-description"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($features_array)) : ?>
                                <ul class="ib-tool-features">
                                    <?php foreach ($features_array as $feature) : ?>
                                        <li><?php echo esc_html($feature); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            
                            <div class="ib-tool-cta">
                                <a href="<?php echo esc_url($cta_url); ?>" style="color:<?php echo esc_attr($gradient_start); ?>" target="_blank" rel="noopener">
                                    <span>Try <?php echo esc_html($display_title); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="taskip-no-tools"><?php _e("No tools found.", "taskip-templates"); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="taskip-tools-pagination">
            <div class="blog-pagination-wraper">
                <?php 
                // Check if Taskip() function exists, otherwise use default WordPress pagination
                if (function_exists('Taskip')) {
                    Taskip()->post_pagination();
                } else {
                    the_posts_pagination();
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>