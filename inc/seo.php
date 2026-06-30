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
 * Genera el array Schema.org según el tipo de página.
 * Devuelve null si no aplica ningún schema.
 */
function eu_seo_schema() {
    if (is_front_page()) {
        $same_as = array_values(array_filter(array(
            eu_get_option('instagram_url'),
            eu_get_option('facebook_url'),
            eu_get_option('linkedin_url'),
            eu_get_option('youtube_url'),
            eu_get_option('tiktok_url'),
        )));

        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => array('Organization', 'RealEstateAgent'),
            'name'     => get_bloginfo('name'),
            'url'      => home_url('/'),
        );

        $tagline = eu_get_option('tagline');
        if ($tagline) {
            $schema['description'] = $tagline;
        }

        $logo = eu_get_option('logo_large');
        if ($logo) {
            $schema['logo'] = $logo;
        }

        $address = eu_get_option('address');
        if ($address) {
            $schema['address'] = wp_strip_all_tags($address);
        }

        $phone = eu_get_option('phone_admin');
        if ($phone) {
            $schema['telephone'] = $phone;
        }

        $email = eu_get_option('contact_email');
        if ($email) {
            $schema['email'] = $email;
        }

        if (!empty($same_as)) {
            $schema['sameAs'] = $same_as;
        }

        return $schema;
    }

    if (is_singular('eu_project')) {
        $image  = eu_seo_image_data();
        $schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'RealEstateListing',
            'name'        => get_the_title(),
            'description' => eu_seo_description(),
            'url'         => get_permalink(),
        );

        if ($image['url']) {
            $schema['image'] = $image['url'];
        }

        $location = eu_project_meta(get_the_ID(), 'location');
        if ($location) {
            $schema['address'] = $location;
        }

        return $schema;
    }

    if (is_singular('eu_news')) {
        $image  = eu_seo_image_data();
        $schema = array(
            '@context'      => 'https://schema.org',
            '@type'         => 'NewsArticle',
            'headline'      => get_the_title(),
            'description'   => eu_seo_description(),
            'url'           => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'publisher'     => array_filter(array(
                '@type' => 'Organization',
                'name'  => get_bloginfo('name'),
                'logo'  => eu_get_option('logo_large') ?: null,
            )),
        );

        if ($image['url']) {
            $schema['image'] = $image['url'];
        }

        return $schema;
    }

    // Páginas genéricas y archivos
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'WebPage',
        'name'     => eu_seo_title(),
        'url'      => eu_seo_url(),
    );

    $desc = eu_seo_description();
    if ($desc) {
        $schema['description'] = $desc;
    }

    return $schema;
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
    // JSON-LD Schema.org
    $schema = eu_seo_schema();
    if ($schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
