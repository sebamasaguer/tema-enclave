<?php
/**
 * Projects archive.
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main eu-projects-archive">
    <div class="eu-container">
        <header class="eu-page-header">
            <?php if (eu_get_option('projects_kicker')) : ?>
                <p class="eu-kicker"><?php echo esc_html(eu_get_option('projects_kicker')); ?></p>
            <?php endif; ?>
            <h1><?php echo esc_html(eu_get_option('projects_title') ?: __('Proyectos', 'enclave-urbano')); ?></h1>
        </header>

        <?php $projects_intro = eu_get_option('projects_intro'); if ($projects_intro) : ?>
            <div class="eu-projects-intro eu-page-editor-content">
                <?php echo wp_kses_post(wpautop($projects_intro)); ?>
            </div>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
            <div class="eu-project-grid">
                <?php $eu_project_index = 0; while (have_posts()) : the_post(); $eu_project_index++; ?>
                    <article <?php post_class($eu_project_index === 1 ? 'eu-project-card eu-project-card--featured' : 'eu-project-card'); ?>>
                        <a href="<?php the_permalink(); ?>" class="eu-project-card__image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('eu-card'); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(eu_get_option('home_hero_image')); ?>" alt="">
                            <?php endif; ?>
                        </a>
                        <div class="eu-project-card__body">
                            <p class="eu-kicker"><?php echo esc_html(eu_project_meta(get_the_ID(), 'location')); ?></p>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php if (has_excerpt()) : the_excerpt(); endif; ?>
                            <dl class="eu-project-card__facts">
                                <?php foreach (array_slice(eu_project_technical_fields(get_the_ID()), 0, 4) as $label => $value) : ?>
                                    <div><dt><?php echo esc_html($label); ?></dt><dd><?php echo esc_html($value); ?></dd></div>
                                <?php endforeach; ?>
                            </dl>
                            <a class="eu-button eu-button--outline" href="<?php the_permalink(); ?>"><?php esc_html_e('Ver proyecto', 'enclave-urbano'); ?></a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('Todavía no hay proyectos cargados.', 'enclave-urbano'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
