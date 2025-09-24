<?php
/**
 * Single Case Study Template
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="taskip taskip-case-study-container">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        // Get custom meta data
        $company_name = get_post_meta(get_the_ID(), '_case_study_company', true);
        $industry = get_post_meta(get_the_ID(), '_case_study_industry', true);
        $challenge = get_post_meta(get_the_ID(), '_case_study_challenge', true);
        $solution = get_post_meta(get_the_ID(), '_case_study_solution', true);
        $results = get_post_meta(get_the_ID(), '_case_study_results', true);
        $testimonial = get_post_meta(get_the_ID(), '_case_study_testimonial', true);
        $testimonial_author = get_post_meta(get_the_ID(), '_case_study_testimonial_author', true);
        $testimonial_position = get_post_meta(get_the_ID(), '_case_study_testimonial_position', true);
        $website_url = get_post_meta(get_the_ID(), '_case_study_website', true);
        ?>

        <div class="container">
            <article class="taskip-case-study-single">
                <!-- Hero Section -->
                <header class="taskip-case-study-hero">
                    <div class="taskip-case-study-hero-content">
                       <?php if (has_post_thumbnail()) : ?>
                            <div class="taskip-case-study-hero-image">
                                <?php the_post_thumbnail('full', array('class' => 'taskip-case-study-featured-image')); ?>
                            </div>
                        <?php endif; ?>
                    
                        <div class="taskip-case-study-meta-row">
                            <div class="taskip-case-study-meta-item">
                                <img src="https://wcr2.taskip.net/taskip/clock-icon.png" alt="clock" style="width: 16px; height: 16px;">
                                <span class="taskip-case-study-meta-value"><?php echo get_the_date(); ?></span>
                            </div>
                        </div>

                        <h1 class="taskip-case-study-single-title"><?php the_title(); ?></h1>
                    </div>
                </header>

                <!-- Content Sections -->
                <div class="taskip-case-study-single-content">
                    <!-- Main Content -->
                    <section class="taskip-case-study-section taskip-case-study-main-content">
                        <div class="taskip-case-study-section-content">
                            <?php the_content(); ?>
                        </div>
                    </section>
                </div>

                

            </article>
        </div>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>