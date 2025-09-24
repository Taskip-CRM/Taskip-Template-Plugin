<?php
/**
 * Archive Case Studies Template
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="taskip-case-studies-archive">
    <div class="taskip-case-studies-archive-container">

        <header class="taskip-case-studies-archive-header">
            <h1 class="taskip-case-studies-archive-title">
                <?php _e('Case Studies', 'taskip-templates'); ?>
            </h1>
            <p class="taskip-case-studies-archive-description">
                <?php _e('Discover real-world success stories and learn how our solutions have helped businesses achieve their goals.', 'taskip-templates'); ?>
            </p>
        </header>

        <?php if (have_posts()) : ?>
            <div class="taskip-case-studies-archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    // Get custom meta data for each case study
                    $company_name = get_post_meta(get_the_ID(), '_case_study_company', true);
                    $industry = get_post_meta(get_the_ID(), '_case_study_industry', true);
                    $results = get_post_meta(get_the_ID(), '_case_study_results', true);
                    ?>

                    <article class="taskip-case-study-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="taskip-case-study-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', array('class' => 'taskip-case-study-image')); ?>
                                </a>
                                <div class="taskip-case-study-overlay">
                                    <a href="<?php the_permalink(); ?>" class="taskip-case-study-read-more">
                                        <?php _e('Read Case Study', 'taskip-templates'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="taskip-case-study-content">
                            <?php if ($company_name) : ?>
                                <div class="taskip-case-study-company">
                                    <?php echo esc_html($company_name); ?>
                                </div>
                            <?php endif; ?>

                            <h2 class="taskip-case-study-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <?php if ($industry) : ?>
                                <div class="taskip-case-study-industry">
                                    <span class="taskip-case-study-industry-label"><?php _e('Industry:', 'taskip-templates'); ?></span>
                                    <?php echo esc_html($industry); ?>
                                </div>
                            <?php endif; ?>

                            <div class="taskip-case-study-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <?php if ($results) : ?>
                                <div class="taskip-case-study-results">
                                    <strong><?php _e('Key Results:', 'taskip-templates'); ?></strong>
                                    <?php echo wp_kses_post($results); ?>
                                </div>
                            <?php endif; ?>

                            <div class="taskip-case-study-meta">
                                <span class="taskip-case-study-date">
                                    <?php echo get_the_date(); ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="taskip-case-study-link">
                                    <?php _e('Read Full Case Study', 'taskip-templates'); ?>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="taskip-case-studies-pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('&laquo; Previous', 'taskip-templates'),
                    'next_text' => __('Next &raquo;', 'taskip-templates'),
                    'screen_reader_text' => __('Case Studies navigation', 'taskip-templates')
                ));
                ?>
            </div>

        <?php else : ?>
            <div class="taskip-case-studies-no-posts">
                <h2><?php _e('No Case Studies Found', 'taskip-templates'); ?></h2>
                <p><?php _e('We haven\'t published any case studies yet. Please check back soon!', 'taskip-templates'); ?></p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>