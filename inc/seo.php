<?php
/**
 * SEO: título, Open Graph, Twitter Cards y Schema.org.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Título SEO según contexto de página.
 */
function eu_seo_title() {
    if (is_front_page()) {
        return get_bloginfo('name');
    }
    if (is_singular()) {
        return get_the_title();
    }
    if (is_post_type_archive()) {
        return post_type_archive_title('', false);
    }
    $obj = get_queried_object();
    if ($obj && isset($obj->name)) {
        return $obj->name;
    }
    return get_bloginfo('name');
}

/**
 * URL canónica de la página actual.
 */
function eu_seo_url() {
    if (is_front_page()) {
        return home_url('/');
    }
    global $wp;
    return home_url(add_query_arg(array(), $wp->request));
}

/**
 * Formato del separador en <title>.
 */
add_filter('document_title_separator', function () {
    return '|';
});

/**
 * Partes del <title> por tipo de página.
 */
add_filter('document_title_parts', function ($parts) {
    if (is_front_page()) {
        $tagline = eu_get_option('tagline');
        return array_filter(array(
            'title'   => get_bloginfo('name'),
            'tagline' => $tagline ?: null,
        ));
    }
    $parts['site'] = get_bloginfo('name');
    unset($parts['tagline']);
    return $parts;
});

/**
 * Descripción SEO según contexto.
 */
function eu_seo_description($length = 160) {
    if (is_front_page()) {
        return eu_get_option('tagline');
    }
    if (!is_singular()) {
        return '';
    }
    $excerpt = get_the_excerpt();
    if ($excerpt) {
        return wp_strip_all_tags($excerpt);
    }
    $content = wp_strip_all_tags(strip_shortcodes((string) get_the_content()));
    return mb_substr(trim($content), 0, $length);
}

/**
 * Imagen OG: imagen destacada del post o imagen hero del sitio.
 * Devuelve url, width, height (width/height son null si no están disponibles).
 */
function eu_seo_image_data() {
    if (is_singular()) {
        $thumb_id = get_post_thumbnail_id();
        if ($thumb_id) {
            $img = wp_get_attachment_image_src($thumb_id, 'large');
            if ($img) {
                return array('url' => $img[0], 'width' => (int) $img[1], 'height' => (int) $img[2]);
            }
        }
    }
    return array('url' => eu_get_option('home_hero_image'), 'width' => null, 'height' => null);
}

/**
 * Inyecta meta tags SEO en <head>.
 */
add_action('wp_head', 'eu_seo_head', 1);
function eu_seo_head() {
    $title       = eu_seo_title();
    $description = eu_seo_description();
    $url         = eu_seo_url();
    $site_name   = get_bloginfo('name');
    $image       = eu_seo_image_data();
    $og_type     = is_singular('eu_news') ? 'article' : 'website';

    ?>
    <?php if ($description) : ?>
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>

    <!-- Open Graph -->
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <?php if ($description) : ?>
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:type" content="<?php echo esc_attr($og_type); ?>">
    <?php if ($image['url']) : ?>
    <meta property="og:image" content="<?php echo esc_url($image['url']); ?>">
    <?php if ($image['width']) : ?>
    <meta property="og:image:width" content="<?php echo esc_attr($image['width']); ?>">
    <meta property="og:image:height" content="<?php echo esc_attr($image['height']); ?>">
    <?php endif; ?>
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <?php if ($description) : ?>
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>
    <?php if ($image['url']) : ?>
    <meta name="twitter:image" content="<?php echo esc_url($image['url']); ?>">
    <?php endif; ?>
    <?php
}
