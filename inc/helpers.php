<?php
/**
 * Helper functions.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Asset URL helper.
 */
function eu_asset($path) {
    return trailingslashit(EU_THEME_URI) . 'assets/' . ltrim($path, '/');
}

/**
 * Default theme options.
 */
function eu_default_options() {
    return array(
        'primary_color'       => '#336633',
        'accent_color'        => '#ffff00',
        'logo_large'          => eu_asset('img/logo-grande.png'),
        'logo_small'          => eu_asset('img/logo-chico.jpeg'),
        'home_hero_image'     => eu_asset('img/portada.jpeg'),
        'contact_qr'          => '',
        'tagline'             => 'Buenos resultados, entre creatividad e innovación.',
        'contact_email'       => 'info@enclaveurbano.com.ar',
        'phone_admin'         => '+54 9 (387) 000000',
        'phone_sales'         => '+54 9 (387) 000000',
        'whatsapp'            => '',
        'address'             => '',
        'instagram_url'       => '',
        'facebook_url'        => '',
        'linkedin_url'        => '',
        'youtube_url'         => '',
        'tiktok_url'          => '',
        'google_maps_api_key' => '',
        'home_mission'        => "Tiene como misión principal:\nDISEÑAR, CREAR Y CONSTRUIR, cuidando la coherencia proyectual y su contexto ambiental\nTrabaja en mejorar la vida de las comunidades y aportar a la sustentabilidad de las ciudades.\nConcretar proyectos que cambien realidades y perduren en el tiempo.",
        'home_genera_title'   => 'Genera',
        'home_genera_text'    => 'Propuestas de inversión para la industria de la construcción y diferentes conceptos inmobiliarios.',
        'home_alcance_title'  => 'Alcance',
        'home_alcance_text'   => 'Trabaja sobre grandes extensiones proyectando un desarrollo urbano, aplicando nuevos conceptos de construcción de espacios sustentables.',
        'team_intro_title'    => 'Sinergia Interdisciplinaria',
        'team_intro_text'     => 'Somos un equipo de profesionales en el área de la arquitectura, ingeniería, diseño, comercialización, tecnología e inversión que trabaja para generar un resultado superior.',
        'team_note'           => "+ Colaboración Estratégica:\nel equipo no solo trabaja unido, sino con un plan claro.\n+ Visión Compartida:\nla creación de ciudad planificada.\n+ Cohesión Profesional:\nTransmite solidez y confianza.",
        'projects_kicker'     => 'Desarrollo urbano',
        'projects_title'      => 'Proyectos',
        'projects_intro'      => '',
        'footer_text'         => 'Ciudad abierta',
        'footer_citybar_image' => eu_asset('img/ciudad-abierta.jpg'),
        'footer_citybar_height_desktop' => '185',
        'footer_citybar_height_mobile'  => '90',
        'footer_citybar_position_x'     => 'center',
        'footer_citybar_position_y'     => 'center',
        'footer_citybar_fit'            => 'cover',
    );
}

/**
 * Get a theme option with fallback.
 */
function eu_get_option($key, $fallback = '') {
    $options  = get_option('eu_theme_options', array());
    $defaults = eu_default_options();

    if (isset($options[$key]) && '' !== $options[$key]) {
        return $options[$key];
    }

    if (isset($defaults[$key])) {
        return $defaults[$key];
    }

    return $fallback;
}

/**
 * Convert hex color to rgba.
 */
function eu_hex_to_rgba($hex, $alpha = 1) {
    $hex = str_replace('#', '', (string) $hex);

    if (3 === strlen($hex)) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } elseif (6 === strlen($hex)) {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    } else {
        $r = 51;
        $g = 102;
        $b = 51;
    }

    return 'rgba(' . absint($r) . ',' . absint($g) . ',' . absint($b) . ',' . esc_attr($alpha) . ')';
}

/**
 * Safe HTML for regular editorial content.
 */
function eu_kses_content($html) {
    return wp_kses_post($html);
}

/**
 * Whether the current page content has meaningful text.
 */
function eu_has_editor_content() {
    $content = get_the_content(null, false, get_the_ID());
    return '' !== trim(wp_strip_all_tags(strip_shortcodes((string) $content)));
}

/**
 * Get an attachment/image URL from a post thumbnail or a custom meta URL.
 */
function eu_get_post_image_url($post_id, $size = 'large', $meta_key = '') {
    if (has_post_thumbnail($post_id)) {
        $url = get_the_post_thumbnail_url($post_id, $size);
        if ($url) {
            return $url;
        }
    }

    if ($meta_key) {
        $meta_url = get_post_meta($post_id, $meta_key, true);
        if ($meta_url) {
            return esc_url($meta_url);
        }
    }

    return '';
}
