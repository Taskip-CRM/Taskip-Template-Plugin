<?php
/**
 * Archive Legal Pages Template
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="taskip-legal-archive">
    <div class="taskip-legal-archive-container">
        
        <header class="taskip-legal-archive-header">
            <h1 class="taskip-legal-archive-title">
                <?php _e('Legal Pages', 'taskip-templates'); ?>
            </h1>
            <p class="taskip-legal-archive-description">
                <?php _e('We are dedicated to safeguarding your data, ensuring your private information remains confidential, and maintaining transparency in all our business practices.', 'taskip-templates'); ?>
            </p>
        </header>

        <?php if (have_posts()) : ?>
            <div class="taskip-legal-archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    // Get custom meta data for each legal page
                    $hero_icon = get_post_meta(get_the_ID(), '_taskip_legal_hero_icon', true);
                    $icon_bg_color = get_post_meta(get_the_ID(), '_taskip_legal_icon_bg_color', true);
                    
                    // Use default color if not set
                    if (empty($icon_bg_color)) {
                        $icon_bg_color = '#667eea';
                    }
                    ?>
                    <article class="taskip-legal-archive-item">
                        <div class="taskip-legal-card">
                            
                            <div class="taskip-legal-card-icon">
                                <div class="taskip-legal-card-icon-container" style="background-color: <?php echo esc_attr($icon_bg_color); ?>;">
                                    <?php if ($hero_icon) : ?>
                                        <div class="taskip-legal-card-custom-icon">
                                            <?php echo wp_kses($hero_icon, array(
                                                'svg' => array(
                                                    'class' => array(),
                                                    'aria-hidden' => array(),
                                                    'aria-labelledby' => array(),
                                                    'role' => array(),
                                                    'xmlns' => array(),
                                                    'width' => array(),
                                                    'height' => array(),
                                                    'viewbox' => array(),
                                                    'fill' => array(),
                                                    'stroke' => array(),
                                                    'stroke-width' => array(),
                                                    'stroke-linecap' => array(),
                                                    'stroke-linejoin' => array(),
                                                ),
                                                'path' => array(
                                                    'd' => array(),
                                                    'fill' => array(),
                                                    'stroke' => array(),
                                                    'stroke-width' => array(),
                                                    'stroke-linecap' => array(),
                                                    'stroke-linejoin' => array(),
                                                ),
                                                'circle' => array(
                                                    'cx' => array(),
                                                    'cy' => array(),
                                                    'r' => array(),
                                                    'fill' => array(),
                                                    'stroke' => array(),
                                                    'stroke-width' => array(),
                                                ),
                                                'rect' => array(
                                                    'x' => array(),
                                                    'y' => array(),
                                                    'width' => array(),
                                                    'height' => array(),
                                                    'fill' => array(),
                                                    'stroke' => array(),
                                                    'stroke-width' => array(),
                                                    'rx' => array(),
                                                    'ry' => array(),
                                                ),
                                                'g' => array(),
                                                'defs' => array(),
                                                'clippath' => array(
                                                    'id' => array(),
                                                ),
                                            )); ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="taskip-legal-card-default-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                                <path d="M14 2V8H20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                                <path d="M16 13H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M16 17H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M10 9H9H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="taskip-legal-card-content">
                                <h2 class="taskip-legal-card-title">
                                    <a href="<?php the_permalink(); ?>" class="taskip-legal-card-link">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <?php if (get_the_excerpt()) : ?>
                                    <p class="taskip-legal-card-excerpt">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="taskip-legal-card-footer">
                                <a href="<?php the_permalink(); ?>" class="taskip-legal-card-button">
                                    <?php _e('Read More', 'taskip-templates'); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('Previous', 'taskip-templates'),
                'next_text' => __('Next', 'taskip-templates'),
            ));
            ?>

        <?php else : ?>
            <div class="taskip-legal-no-content">
                <p><?php _e('No legal pages found.', 'taskip-templates'); ?></p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>