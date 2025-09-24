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

<div class="taskip-case-study-container">
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

        <article class="taskip-case-study-single">
            <!-- Hero Section -->
            <header class="taskip-case-study-hero">
                <div class="taskip-case-study-hero-content">
                    <div class="taskip-case-study-breadcrumb">
                        <a href="<?php echo get_post_type_archive_link('case_studies'); ?>">
                            <?php _e('Case Studies', 'taskip-templates'); ?>
                        </a>
                        <span class="taskip-case-study-breadcrumb-separator">></span>
                        <span><?php the_title(); ?></span>
                    </div>

                    <?php if ($company_name) : ?>
                        <div class="taskip-case-study-company-name">
                            <?php echo esc_html($company_name); ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="taskip-case-study-title"><?php the_title(); ?></h1>

                    <div class="taskip-case-study-meta-row">
                        <?php if ($industry) : ?>
                            <div class="taskip-case-study-meta-item">
                                <span class="taskip-case-study-meta-label"><?php _e('Industry:', 'taskip-templates'); ?></span>
                                <span class="taskip-case-study-meta-value"><?php echo esc_html($industry); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($website_url) : ?>
                            <div class="taskip-case-study-meta-item">
                                <span class="taskip-case-study-meta-label"><?php _e('Website:', 'taskip-templates'); ?></span>
                                <a href="<?php echo esc_url($website_url); ?>" target="_blank" class="taskip-case-study-meta-link">
                                    <?php echo esc_html(str_replace(['http://', 'https://'], '', $website_url)); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="taskip-case-study-meta-item">
                            <span class="taskip-case-study-meta-label"><?php _e('Published:', 'taskip-templates'); ?></span>
                            <span class="taskip-case-study-meta-value"><?php echo get_the_date(); ?></span>
                        </div>
                    </div>
                </div>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="taskip-case-study-hero-image">
                        <?php the_post_thumbnail('full', array('class' => 'taskip-case-study-featured-image')); ?>
                    </div>
                <?php endif; ?>
            </header>

            <!-- Content Sections -->
            <div class="taskip-case-study-content">

                <!-- Overview/Excerpt -->
                <?php if (get_the_excerpt()) : ?>
                    <section class="taskip-case-study-section taskip-case-study-overview">
                        <h2 class="taskip-case-study-section-title"><?php _e('Overview', 'taskip-templates'); ?></h2>
                        <div class="taskip-case-study-section-content">
                            <?php the_excerpt(); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Challenge -->
                <?php if ($challenge) : ?>
                    <section class="taskip-case-study-section taskip-case-study-challenge">
                        <h2 class="taskip-case-study-section-title"><?php _e('The Challenge', 'taskip-templates'); ?></h2>
                        <div class="taskip-case-study-section-content">
                            <?php echo wp_kses_post($challenge); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Solution -->
                <?php if ($solution) : ?>
                    <section class="taskip-case-study-section taskip-case-study-solution">
                        <h2 class="taskip-case-study-section-title"><?php _e('Our Solution', 'taskip-templates'); ?></h2>
                        <div class="taskip-case-study-section-content">
                            <?php echo wp_kses_post($solution); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Main Content -->
                <section class="taskip-case-study-section taskip-case-study-main-content">
                    <div class="taskip-case-study-section-content">
                        <?php the_content(); ?>
                    </div>
                </section>

                <!-- Results -->
                <?php if ($results) : ?>
                    <section class="taskip-case-study-section taskip-case-study-results">
                        <h2 class="taskip-case-study-section-title"><?php _e('Results & Impact', 'taskip-templates'); ?></h2>
                        <div class="taskip-case-study-section-content taskip-case-study-results-content">
                            <?php echo wp_kses_post($results); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Testimonial -->
                <?php if ($testimonial) : ?>
                    <section class="taskip-case-study-section taskip-case-study-testimonial">
                        <div class="taskip-case-study-testimonial-content">
                            <blockquote class="taskip-case-study-quote">
                                "<?php echo wp_kses_post($testimonial); ?>"
                            </blockquote>
                            <?php if ($testimonial_author || $testimonial_position) : ?>
                                <div class="taskip-case-study-testimonial-author">
                                    <?php if ($testimonial_author) : ?>
                                        <span class="taskip-case-study-testimonial-name">
                                            <?php echo esc_html($testimonial_author); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($testimonial_position) : ?>
                                        <span class="taskip-case-study-testimonial-position">
                                            <?php echo esc_html($testimonial_position); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($company_name) : ?>
                                        <span class="taskip-case-study-testimonial-company">
                                            <?php echo esc_html($company_name); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

            </div>

            <!-- Navigation -->
            <nav class="taskip-case-study-navigation">
                <div class="taskip-case-study-nav-links">
                    <?php
                    $prev_post = get_previous_post(false, '', 'case_studies');
                    $next_post = get_next_post(false, '', 'case_studies');
                    ?>

                    <?php if ($prev_post) : ?>
                        <a href="<?php echo get_permalink($prev_post->ID); ?>" class="taskip-case-study-nav-link taskip-case-study-nav-prev">
                            <span class="taskip-case-study-nav-direction"><?php _e('Previous Case Study', 'taskip-templates'); ?></span>
                            <span class="taskip-case-study-nav-title"><?php echo get_the_title($prev_post->ID); ?></span>
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo get_post_type_archive_link('case_studies'); ?>" class="taskip-case-study-nav-link taskip-case-study-nav-archive">
                        <?php _e('All Case Studies', 'taskip-templates'); ?>
                    </a>

                    <?php if ($next_post) : ?>
                        <a href="<?php echo get_permalink($next_post->ID); ?>" class="taskip-case-study-nav-link taskip-case-study-nav-next">
                            <span class="taskip-case-study-nav-direction"><?php _e('Next Case Study', 'taskip-templates'); ?></span>
                            <span class="taskip-case-study-nav-title"><?php echo get_the_title($next_post->ID); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>

        </article>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>