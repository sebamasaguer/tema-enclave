<?php get_header(); ?>

<main class="eu-main">

<section class="eu-section eu-section-news">

    <div class="eu-container">

        <div class="eu-novedades-header">
            <h1 class="eu-section-title">Novedades</h1>
        </div>

        <?php if (have_posts()) : ?>

        <div class="eu-novedades-grid">

            <?php while (have_posts()) : the_post(); ?>

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
                        <?php
                        $featured = get_post_meta(get_the_ID(), '_eu_featured_news', true);
                        if ($featured) :
                        ?>
                            <span class="eu-novedad-card__badge"><?php esc_html_e('Destacada', 'enclave-urbano'); ?></span>
                        <?php endif; ?>
                        <h2 class="eu-novedad-card__title"><?php the_title(); ?></h2>
                        <span class="eu-novedad-card__date"><?php echo get_the_date('d/m/Y'); ?></span>
                    </div>

                </a>

            <?php endwhile; ?>

        </div>

        <?php else : ?>

        <p class="eu-empty-message"><?php esc_html_e('No hay novedades publicadas.', 'enclave-urbano'); ?></p>

        <?php endif; ?>

        <?php
        // Paginación
        $pagination = get_the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => '&larr; Anterior',
            'next_text' => 'Siguiente &rarr;',
        ));
        if ($pagination) :
        ?>
        <div class="eu-pagination">
            <?php echo $pagination; ?>
        </div>
        <?php endif; ?>

    </div>

</section>

</main>

<?php get_footer(); ?>
