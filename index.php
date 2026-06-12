<?php
/**
 * Blog/news index.
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main">
    <div class="eu-container">
        <header class="eu-page-header">
            <h1><?php echo is_home() ? esc_html__('News', 'enclave-urbano') : wp_kses_post(get_the_archive_title()); ?></h1>
            <?php if (get_the_archive_description()) : ?>
                <div class="eu-archive-description"><?php the_archive_description(); ?></div>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="eu-card-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('eu-post-card'); ?>>
                        <a href="<?php the_permalink(); ?>" class="eu-post-card__image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('eu-card'); ?>
                            <?php else : ?>
                                <span><?php echo eu_inline_icon('compass'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="eu-post-card__body">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php the_excerpt(); ?>
                            <a class="eu-text-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Leer más', 'enclave-urbano'); ?></a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('Todavía no hay publicaciones.', 'enclave-urbano'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
