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

        <!-- <header class="taskip-case-studies-archive-header">
            <h1 class="taskip-case-studies-archive-title">
                <?php _e('Case Studies', 'taskip-templates'); ?>
            </h1>
            <p class="taskip-case-studies-archive-description">
                <?php _e('Discover real-world success stories and learn how our solutions have helped businesses achieve their goals.', 'taskip-templates'); ?>
            </p>
        </header> -->

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
                            </div>
                        <?php endif; ?>

                        <div class="taskip-case-study-content">                           
                            <h2 class="taskip-case-study-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="taskip-case-study-meta">
                                <a href="<?php the_permalink(); ?>" class="taskip-case-study-link">
                                    Read full case study
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                     <path d="M4 12H20M20 12L16 8M20 12L16 16" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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