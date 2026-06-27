<?php
/**
 * Front page template.
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-front-page">
    <section id="enclave-urbano" class="eu-section eu-section-intro">
        <div class="eu-container eu-intro-grid">
            <div class="eu-intro-logo">
                <?php echo eu_logo_img('small', 'eu-intro-logo__img'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>

            <div class="eu-intro-text" style="text-align:<?php echo esc_attr(eu_get_option('home_mission_align', 'left')); ?>">
                <?php echo wpautop(eu_kses_content(eu_get_option('home_mission'))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>

			
        </div>
    </section>
<section class="eu-section eu-section-about">

    <div class="eu-container eu-about-layout">

        <div class="eu-about-content">

            <?php
            $about_page = get_post(get_option('page_on_front'));
            ?>

            <?php if($about_page): ?>

                <h2 class="eu-section-title">
                    <?php echo esc_html($about_page->post_title); ?>
                </h2>

                <div class="eu-about-text">

                    <?php
                    echo apply_filters(
                        'the_content',
                        $about_page->post_content
                    );
                    ?>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<section class="eu-section eu-section-home-news">

    <div class="eu-container">

        <div class="eu-home-news-header">
            <h2 class="eu-section-title">Novedades</h2>
            <a href="<?php echo esc_url(get_post_type_archive_link('eu_news')); ?>" class="eu-home-news-ver-todas">
                <?php esc_html_e('Ver todas', 'enclave-urbano'); ?>
            </a>
        </div>

        <?php
        $featured_news = new WP_Query(array(
            'post_type'      => 'eu_news',
            'posts_per_page' => 3,
            'meta_query'     => array(
                array(
                    'key'   => '_eu_featured_news',
                    'value' => '1',
                ),
            ),
            'orderby' => 'date',
            'order'   => 'DESC',
        ));

        if ($featured_news->have_posts()) : ?>

        <div class="eu-novedades-grid eu-novedades-grid--home">

            <?php while ($featured_news->have_posts()) : $featured_news->the_post(); ?>

                <a href="<?php the_permalink(); ?>" class="eu-novedad-card">

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="eu-novedad-card__image">
                            <?php the_post_thumbnail('medium_large'); ?>
                        </div>
                    <?php else : ?>
                        <div class="eu-novedad-card__image eu-novedad-card__image--placeholder"></div>
                    <?php endif; ?>

                    <div class="eu-novedad-card__overlay"></div>

                    <div class="eu-novedad-card__content">
                        <h3 class="eu-novedad-card__title"><?php the_title(); ?></h3>
                        <span class="eu-novedad-card__date"><?php echo get_the_date('d/m/Y'); ?></span>
                    </div>

                </a>

            <?php endwhile; wp_reset_postdata(); ?>

        </div>

        <?php else : ?>

            <?php
            // Si no hay destacadas, mostrar las 3 más recientes
            $recent_news = new WP_Query(array(
                'post_type'      => 'eu_news',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ));
            if ($recent_news->have_posts()) : ?>

            <div class="eu-novedades-grid eu-novedades-grid--home">

                <?php while ($recent_news->have_posts()) : $recent_news->the_post(); ?>

                    <a href="<?php the_permalink(); ?>" class="eu-novedad-card">

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="eu-novedad-card__image">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </div>
                        <?php else : ?>
                            <div class="eu-novedad-card__image eu-novedad-card__image--placeholder"></div>
                        <?php endif; ?>

                        <div class="eu-novedad-card__overlay"></div>

                        <div class="eu-novedad-card__content">
                            <h3 class="eu-novedad-card__title"><?php the_title(); ?></h3>
                            <span class="eu-novedad-card__date"><?php echo get_the_date('d/m/Y'); ?></span>
                        </div>

                    </a>

                <?php endwhile; wp_reset_postdata(); ?>

            </div>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</section>

   
</main>
<?php
get_footer();
