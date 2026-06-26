<?php
/**
 * Template Name: Inversiones
 *
 * @package Enclave_Urbano
 */

get_header();

$inversiones = new WP_Query(array(
    'post_type'      => 'eu_project',
    'posts_per_page' => -1,
    'orderby'        => array('menu_order' => 'ASC', 'date' => 'DESC'),
    'meta_query'     => array(
        array(
            'key'   => '_eu_project_category',
            'value' => 'inversion',
        ),
    ),
));
?>
<main id="main" class="eu-main eu-page-main eu-projects-archive eu-inversiones-page">
    <div class="eu-container">
        <header class="eu-page-header">
            <p class="eu-kicker"><?php esc_html_e('Oportunidades', 'enclave-urbano'); ?></p>
            <h1><?php esc_html_e('Inversiones', 'enclave-urbano'); ?></h1>
        </header>

        <?php if (have_posts()) : while (have_posts()) : the_post();
            $page_content = get_the_content();
            if ($page_content) : ?>
                <div class="eu-projects-intro eu-page-editor-content">
                    <?php the_content(); ?>
                </div>
            <?php endif;
        endwhile; endif; wp_reset_query(); ?>

        <?php if ($inversiones->have_posts()) : ?>
            <div class="eu-project-grid">
                <?php while ($inversiones->have_posts()) : $inversiones->the_post(); ?>
                    <article class="eu-project-card">
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
                            <a class="eu-button eu-button--outline" href="<?php the_permalink(); ?>"><?php esc_html_e('VER FICHA TÉCNICA', 'enclave-urbano'); ?></a>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p><?php esc_html_e('Todavía no hay proyectos de inversión cargados.', 'enclave-urbano'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
