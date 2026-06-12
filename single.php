<?php
/**
 * Single post template.
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main">
    <div class="eu-container eu-single-shell">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('eu-single-article'); ?>>
                <header class="eu-page-header">
                    <p class="eu-kicker"><?php esc_html_e('News', 'enclave-urbano'); ?></p>
                    <h1><?php the_title(); ?></h1>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                </header>
                <?php if (has_post_thumbnail()) : ?>
                    <figure class="eu-single-hero"><?php the_post_thumbnail('eu-hero'); ?></figure>
                <?php endif; ?>
                <div class="eu-page-editor-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
