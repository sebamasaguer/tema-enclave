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
