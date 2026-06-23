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

        <?php
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        if ( $prev_post || $next_post ) :
        ?>
        <nav class="eu-news-nav">
            <?php if ( $prev_post ) : ?>
                <a class="eu-news-nav__arrow" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">&#8592;</a>
            <?php else : ?>
                <span class="eu-news-nav__spacer"></span>
            <?php endif; ?>

            <?php if ( $next_post ) : ?>
                <a class="eu-news-nav__arrow" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">&#8594;</a>
            <?php else : ?>
                <span class="eu-news-nav__spacer"></span>
            <?php endif; ?>
        </nav>
        <?php endif; ?>

    </div>

</section>

</main>

<?php get_footer(); ?>