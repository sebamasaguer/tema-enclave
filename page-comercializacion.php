<?php
/**
 * Template Name: Comercialización
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main eu-sales-page">
    <div class="eu-container eu-page-shell">
        <?php while (have_posts()) : the_post(); ?>
            <header class="eu-page-header eu-page-header--compact">
                <h1><?php the_title(); ?></h1>
            </header>
            <?php if (eu_has_editor_content()) : ?>
                <article class="eu-page-editor-content"><?php the_content(); ?></article>
            <?php else : ?>
                <section class="eu-sales-fallback">
                    <div>
                        <?php echo eu_inline_icon('target'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <h2><?php esc_html_e('Comercialización', 'enclave-urbano'); ?></h2>
                        <p><?php esc_html_e('Espacio preparado para comunicar estrategias comerciales, oportunidades de inversión, documentación de venta y canales de consulta de cada desarrollo.', 'enclave-urbano'); ?></p>
                    </div>
                    <a class="eu-button eu-button--primary" href="<?php echo esc_url(home_url('/proyectos/')); ?>"><?php esc_html_e('Ver proyectos', 'enclave-urbano'); ?></a>
                </section>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
