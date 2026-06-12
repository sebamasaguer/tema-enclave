<?php
/**
 * Theme setup and assets.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', 'eu_theme_setup');
function eu_theme_setup() {
    load_theme_textdomain('enclave-urbano', EU_THEME_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('custom-logo', array(
        'height'      => 220,
        'width'       => 520,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    add_image_size('eu-card', 680, 520, true);
    add_image_size('eu-hero', 1920, 780, true);
    add_image_size('eu-team', 460, 620, true);

    register_nav_menus(array(
        'primary' => __('Menú principal', 'enclave-urbano'),
        'footer'  => __('Menú del footer', 'enclave-urbano'),
    ));
}

add_action('widgets_init', 'eu_register_sidebars');
function eu_register_sidebars() {
    register_sidebar(array(
        'name'          => __('Footer extra', 'enclave-urbano'),
        'id'            => 'footer-extra',
        'description'   => __('Área opcional para contenido adicional del footer.', 'enclave-urbano'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}

add_action('wp_enqueue_scripts', 'eu_enqueue_scripts');
function eu_enqueue_scripts() {
    wp_enqueue_style(
        'eu-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'eu-main',
        eu_asset('css/main.css'),
        array('eu-google-fonts'),
        EU_THEME_VERSION
    );

    $primary = eu_get_option('primary_color', '#336633');
    $accent  = eu_get_option('accent_color', '#ffff00');
    $inline  = ':root{--eu-green:' . esc_html($primary) . ';--eu-yellow:' . esc_html($accent) . ';--eu-green-08:' . esc_html(eu_hex_to_rgba($primary, 0.08)) . ';--eu-green-12:' . esc_html(eu_hex_to_rgba($primary, 0.12)) . ';--eu-green-70:' . esc_html(eu_hex_to_rgba($primary, 0.70)) . ';}';
    wp_add_inline_style('eu-main', $inline);

    wp_enqueue_script(
        'eu-main',
        eu_asset('js/main.js'),
        array(),
        EU_THEME_VERSION,
        true
    );

    if (is_singular('eu_project')) {
        wp_enqueue_script(
            'eu-maps',
            eu_asset('js/maps.js'),
            array(),
            EU_THEME_VERSION,
            true
        );

        $api_key = eu_get_option('google_maps_api_key', '');
        $has_kml = get_post_meta(get_the_ID(), '_eu_project_kml_url', true);
        $has_lat = get_post_meta(get_the_ID(), '_eu_project_map_lat', true);

        if ($api_key && ($has_kml || $has_lat)) {
            wp_enqueue_script(
                'google-maps',
                'https://maps.googleapis.com/maps/api/js?key=' . rawurlencode($api_key) . '&callback=euInitKmlMaps',
                array('eu-maps'),
                null,
                true
            );
        }
    }
}

add_action('admin_enqueue_scripts', 'eu_admin_enqueue_scripts');
function eu_admin_enqueue_scripts($hook) {
    $screen = get_current_screen();

    if (!$screen) {
        return;
    }

    $load_for_post_types = in_array($screen->post_type, array('eu_project', 'eu_team', 'eu_value', 'eu_alliance', 'eu_inquiry'), true);
    $load_for_options    = ('appearance_page_eu-theme-options' === $hook);

    if (!$load_for_post_types && !$load_for_options) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style('eu-admin', eu_asset('css/admin.css'), array(), EU_THEME_VERSION);
    wp_enqueue_script('eu-admin', eu_asset('js/admin.js'), array('jquery'), EU_THEME_VERSION, true);
}

add_action('after_switch_theme', 'eu_after_switch_theme');
function eu_after_switch_theme() {
    if (function_exists('eu_register_post_types')) {
        eu_register_post_types();
    }
    if (function_exists('eu_register_taxonomies')) {
        eu_register_taxonomies();
    }
    flush_rewrite_rules();

    if (!get_option('eu_theme_options')) {
        update_option('eu_theme_options', eu_default_options());
    }
}

add_filter('upload_mimes', 'eu_allow_kml_uploads');
function eu_allow_kml_uploads($mimes) {
    $mimes['kml'] = 'application/vnd.google-earth.kml+xml';
    $mimes['kmz'] = 'application/vnd.google-earth.kmz';
    return $mimes;
}

add_filter('wp_check_filetype_and_ext', 'eu_validate_kml_uploads', 10, 4);
function eu_validate_kml_uploads($data, $file, $filename, $mimes) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if ('kml' === $ext) {
        $data['ext']  = 'kml';
        $data['type'] = 'application/vnd.google-earth.kml+xml';
    }

    if ('kmz' === $ext) {
        $data['ext']  = 'kmz';
        $data['type'] = 'application/vnd.google-earth.kmz';
    }

    return $data;
}
