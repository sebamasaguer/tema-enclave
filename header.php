<?php
/**
 * Site header.
 *
 * @package Enclave_Urbano
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Saltar al contenido', 'enclave-urbano'); ?></a>

<?php if (is_front_page()) : ?>
    <header class="eu-site-header eu-home-header">
        <div class="eu-home-hero" style="background-image:url('<?php echo esc_url(eu_get_option('home_hero_image')); ?>')">
            <div class="eu-home-hero__mark">
                <?php echo eu_logo_img('small', 'eu-home-hero__logo'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>

        <div class="eu-brand-strip">
            <div class="eu-container eu-brand-strip__inner">
                <a class="eu-strip-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php echo eu_logo_img('large', 'eu-strip-logo__img'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </a>
                <span class="eu-strip-divider" aria-hidden="true"></span>
                <p><?php echo esc_html(eu_get_option('tagline')); ?></p>
            </div>
        </div>

        <div class="eu-nav-bar eu-nav-bar--home">
            <div class="eu-container eu-nav-bar__inner">
                <button class="eu-menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu">
                    <span></span><span></span><span></span>
                    <span class="screen-reader-text"><?php esc_html_e('Abrir menú', 'enclave-urbano'); ?></span>
                </button>
                <nav id="primary-menu" class="eu-primary-nav" aria-label="<?php esc_attr_e('Menú principal', 'enclave-urbano'); ?>">
                    <?php eu_primary_menu(); ?>
                </nav>
            </div>
        </div>
    </header>
<?php else : ?>
    <header class="eu-site-header eu-inner-header">
        <div class="eu-container eu-inner-header__inner">
            <a class="eu-inner-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php echo eu_logo_img('small', 'eu-inner-logo__img'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </a>
            <button class="eu-menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu">
                <span></span><span></span><span></span>
                <span class="screen-reader-text"><?php esc_html_e('Abrir menú', 'enclave-urbano'); ?></span>
            </button>
            <nav id="primary-menu" class="eu-primary-nav eu-primary-nav--inner" aria-label="<?php esc_attr_e('Menú principal', 'enclave-urbano'); ?>">
                <?php eu_primary_menu(); ?>
            </nav>
        </div>
    </header>
<?php endif; ?>
