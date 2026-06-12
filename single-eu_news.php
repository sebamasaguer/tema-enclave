<?php get_header(); ?>

<main class="eu-main">

<section class="eu-section eu-section-news">

    <div class="eu-container">

        <h1 class="eu-section-title">
            Novedades
        </h1>

        <div class="eu-news-grid">

            <?php while(have_posts()) : the_post(); ?>

                <article class="eu-news-card">

                    <?php if(has_post_thumbnail()) : ?>

                        <a class="eu-news-card__image" href="<?php the_permalink(); ?>">

                            <?php the_post_thumbnail('large'); ?>

                        </a>

                    <?php endif; ?>

                    <div class="eu-news-card__content">

                        <h2 class="eu-news-card__title">

                            <a href="<?php the_permalink(); ?>">

                                <?php the_title(); ?>

                            </a>

                        </h2>

                        <div class="eu-news-card__excerpt">

                            <?php the_content(); ?> 

                        </div>

                    </div>

                </article>

            <?php endwhile; ?>

        </div>

    </div>

</section>

</main>

<?php get_footer(); ?>