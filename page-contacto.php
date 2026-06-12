<?php
/**
 * Template Name: Contacto
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main eu-contact-page">
    <div class="eu-container eu-page-shell">
        <?php while (have_posts()) : the_post(); ?>
            <header class="eu-page-header eu-page-header--compact">
                <h1><?php the_title(); ?></h1>
            </header>

            <?php if (eu_has_editor_content()) : ?>
                <article class="eu-page-editor-content"><?php the_content(); ?></article>
            <?php endif; ?>

            <section class="eu-contact-layout">
                <div class="eu-contact-info-card">
                    <div class="eu-contact-logo"><?php echo eu_logo_img('small', ''); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                    <?php if (eu_get_option('contact_qr')) : ?>
                        <img class="eu-contact-qr" src="<?php echo esc_url(eu_get_option('contact_qr')); ?>" alt="QR contacto">
                    <?php endif; ?>
                    <h2><?php esc_html_e('Teléfono', 'enclave-urbano'); ?></h2>
                    <p><?php esc_html_e('Administración:', 'enclave-urbano'); ?> <?php echo esc_html(eu_get_option('phone_admin')); ?><br>
                    <?php esc_html_e('Comercialización:', 'enclave-urbano'); ?> <?php echo esc_html(eu_get_option('phone_sales')); ?></p>
                    <h2>Email</h2>
                    <p><a href="mailto:<?php echo esc_attr(eu_get_option('contact_email')); ?>"><?php echo esc_html(eu_get_option('contact_email')); ?></a></p>
                    <?php if (eu_get_option('address')) : ?>
                        <h2><?php esc_html_e('Ubicación', 'enclave-urbano'); ?></h2>
                        <p><?php echo nl2br(esc_html(eu_get_option('address'))); ?></p>
                    <?php endif; ?>
                </div>
                <?php eu_render_contact_form(array('context' => 'contacto', 'title' => __('Escribinos', 'enclave-urbano'))); ?>
            </section>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
