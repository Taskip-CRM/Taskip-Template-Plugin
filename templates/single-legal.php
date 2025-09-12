<?php
/**
 * Single Legal Page Template
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="taskip-legal-container">
    <div class="taskip-legal-wrapper">
        
        <!-- Left Sidebar Navigation -->
        <aside class="taskip-legal-sidebar">
            <nav class="taskip-legal-nav">
                <h3 class="taskip-legal-nav-title"><?php _e('Pages', 'taskip-templates'); ?></h3>
                <ul class="taskip-legal-nav-list">
                    <?php
                    // Get all legal pages
                    $legal_pages = get_posts(array(
                        'post_type' => 'legal',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ));

                    if ($legal_pages) :
                        foreach ($legal_pages as $legal_page) :
                            $is_current = (get_the_ID() === $legal_page->ID) ? 'current' : '';
                            ?>
                            <li class="taskip-legal-nav-item <?php echo $is_current; ?>">
                                <a href="<?php echo get_permalink($legal_page->ID); ?>" 
                                   class="taskip-legal-nav-link <?php echo $is_current; ?>">
                                    <?php echo esc_html($legal_page->post_title); ?>
                                </a>
                            </li>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="taskip-legal-main">
            <?php while (have_posts()) : the_post(); ?>
                <?php
                // Get custom meta data
                $hero_description = get_post_meta(get_the_ID(), '_taskip_legal_hero_description', true);
                $hero_icon = get_post_meta(get_the_ID(), '_taskip_legal_hero_icon', true);
                $icon_bg_color = get_post_meta(get_the_ID(), '_taskip_legal_icon_bg_color', true);
                
                // Use defaults if not set
                if (empty($icon_bg_color)) {
                    $icon_bg_color = '#4ECDC4';
                }
                ?>
                
                <!-- Hero Section -->
                <section class="taskip-legal-hero">
                    <div class="taskip-legal-hero-content">
                        <div class="taskip-legal-hero-icon-container" style="background-color: <?php echo esc_attr($icon_bg_color); ?>;">
                            <?php if ($hero_icon) : ?>
                                <div class="taskip-legal-hero-icon">
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
                                <!-- Default document icon if no custom icon is set -->
                                <div class="taskip-legal-hero-icon">
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
                        
                        <div class="taskip-legal-hero-text">
                            <h1 class="taskip-legal-hero-title"><?php the_title(); ?></h1>
                            <?php if ($hero_description) : ?>
                                <p class="taskip-legal-hero-description">
                                    <?php echo esc_html($hero_description); ?>
                                </p>
                            <?php elseif (get_the_excerpt()) : ?>
                                <p class="taskip-legal-hero-description">
                                    <?php echo get_the_excerpt(); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <article id="post-<?php the_ID(); ?>" <?php post_class('taskip-legal-article'); ?>>

                    <div class="taskip-legal-content">
                        <?php the_content(); ?>
                    </div>

                </article>
            <?php endwhile; ?>
        </main>

    </div>
</div>

<?php get_footer(); ?>