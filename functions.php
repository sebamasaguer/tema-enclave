<?php
/**
 * Enclave Urbano theme bootstrap.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

define('EU_THEME_VERSION', '1.0.9');
define('EU_THEME_DIR', get_template_directory());
define('EU_THEME_URI', get_template_directory_uri());

require_once EU_THEME_DIR . '/inc/helpers.php';
require_once EU_THEME_DIR . '/inc/setup.php';
require_once EU_THEME_DIR . '/inc/cpt.php';
require_once EU_THEME_DIR . '/inc/theme-options.php';
require_once EU_THEME_DIR . '/inc/metaboxes.php';
require_once EU_THEME_DIR . '/inc/contact-form.php';
require_once EU_THEME_DIR . '/inc/template-tags.php';
require_once EU_THEME_DIR . '/inc/seo.php';


/**
 * CPT Novedades (eu_news)
 */
add_action('init', 'eu_register_news_cpt');

function eu_register_news_cpt() {

    $labels = array(
        'name'               => __('Novedades', 'enclave-urbano'),
        'singular_name'      => __('Novedad', 'enclave-urbano'),
        'add_new'            => __('Agregar novedad', 'enclave-urbano'),
        'add_new_item'       => __('Agregar novedad', 'enclave-urbano'),
        'edit_item'          => __('Editar novedad', 'enclave-urbano'),
        'new_item'           => __('Nueva novedad', 'enclave-urbano'),
        'view_item'          => __('Ver novedad', 'enclave-urbano'),
        'search_items'       => __('Buscar novedades', 'enclave-urbano'),
        'not_found'          => __('No se encontraron novedades', 'enclave-urbano'),
        'menu_name'          => __('Novedades', 'enclave-urbano'),
    );

    register_post_type('eu_news', array(
        'labels'       => $labels,
        'public'       => true,
        'menu_icon'    => 'dashicons-megaphone',
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive'  => 'novedades',
        'rewrite'      => array('slug' => 'novedades', 'with_front' => false),
        'show_in_rest' => true,
    ));
}

/**
 * Metabox: Novedad destacada
 */
add_action('add_meta_boxes', 'eu_register_news_metabox');
function eu_register_news_metabox() {
    add_meta_box(
        'eu_news_featured',
        __('Destacada en página principal', 'enclave-urbano'),
        'eu_render_news_featured_metabox',
        'eu_news',
        'side',
        'high'
    );
}

function eu_render_news_featured_metabox($post) {
    wp_nonce_field('eu_save_news_featured', 'eu_news_featured_nonce');
    $featured = get_post_meta($post->ID, '_eu_featured_news', true);
    ?>
    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
        <input type="checkbox" name="eu_featured_news" value="1" <?php checked($featured, '1'); ?> style="width:18px;height:18px;" />
        <?php esc_html_e('Marcar como destacada', 'enclave-urbano'); ?>
    </label>
    <p class="description" style="margin-top:8px;">
        <?php esc_html_e('Las novedades destacadas aparecen en la sección de Novedades de la página principal.', 'enclave-urbano'); ?>
    </p>
    <?php
}

add_action('save_post_eu_news', 'eu_save_news_featured');
function eu_save_news_featured($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['eu_news_featured_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_news_featured_nonce'])), 'eu_save_news_featured')) return;

    if (!empty($_POST['eu_featured_news'])) {
        update_post_meta($post_id, '_eu_featured_news', '1');
    } else {
        delete_post_meta($post_id, '_eu_featured_news');
    }
}

/**
 * Admin column: mostrar si es destacada
 */
add_filter('manage_eu_news_posts_columns', 'eu_news_admin_columns');
function eu_news_admin_columns($columns) {
    $new = array();
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ('title' === $key) {
            $new['eu_featured'] = __('Destacada', 'enclave-urbano');
        }
    }
    return $new;
}

add_action('manage_eu_news_posts_custom_column', 'eu_news_admin_column_content', 10, 2);
function eu_news_admin_column_content($column, $post_id) {
    if ('eu_featured' === $column) {
        $featured = get_post_meta($post_id, '_eu_featured_news', true);
        echo $featured ? '<span style="color:#2ea44f;font-size:18px;" title="Destacada">★</span>' : '<span style="color:#ccc;font-size:18px;" title="No destacada">☆</span>';
    }
}
add_action('pre_get_posts', 'eu_order_projects_archive');

function eu_order_projects_archive($query) {

    if (
        !is_admin()
        && $query->is_main_query()
        && is_post_type_archive('eu_project')
    ) {

        $query->set('orderby', array(
            'menu_order' => 'ASC',
            'date'       => 'DESC'
        ));

        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
}
/**
 * =====================================================
 * EDITOR SOLO NOVEDADES
 * =====================================================
 */

function eu_is_editor_limitado() {

    $user = wp_get_current_user();

    return in_array(
        'editor',
        (array) $user->roles,
        true
    );
}


/**
 * Ocultar menús
 */
function eu_ocultar_menus_editor_limitado() {

    if (!eu_is_editor_limitado()) {
        return;
    }

    remove_menu_page('index.php');

    $permitidos = array(
        'upload.php',
        'edit.php?post_type=eu_news',
        'profile.php',
    );

    global $menu;

    foreach ($menu as $item) {

        if (
            isset($item[2]) &&
            !in_array($item[2], $permitidos, true)
        ) {
            remove_menu_page($item[2]);
        }
    }
}
add_action('admin_menu', 'eu_ocultar_menus_editor_limitado', 999);


/**
 * Redirección
 */
function eu_redirect_editor_limitado($redirect_to, $request, $user) {

    if (
        is_object($user) &&
        isset($user->roles) &&
        in_array('editor', (array) $user->roles, true)
    ) {
        return admin_url('edit.php?post_type=eu_news');
    }

    return $redirect_to;
}
add_filter('login_redirect', 'eu_redirect_editor_limitado', 10, 3);


/**
 * Bloquear Dashboard
 */
function eu_bloquear_dashboard_editor_limitado() {

    if (!eu_is_editor_limitado()) {
        return;
    }

    global $pagenow;

    if ('index.php' === $pagenow) {

        wp_safe_redirect(
            admin_url('edit.php?post_type=eu_news')
        );

        exit;
    }
}
add_action('admin_init', 'eu_bloquear_dashboard_editor_limitado');

/**
 * Limpiar barra superior para Editores
 */
function eu_limpiar_admin_bar_editor($wp_admin_bar) {

    $user = wp_get_current_user();

if (!in_array('editor', (array) $user->roles, true)) {
    return;
}
    // Logo WordPress
    $wp_admin_bar->remove_node('wp-logo');

    // Sitio
    $wp_admin_bar->remove_node('site-name');
    $wp_admin_bar->remove_node('dashboard');
    $wp_admin_bar->remove_node('appearance');

    // Comentarios y actualizaciones
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('updates');

    // Menú Agregar
    $wp_admin_bar->remove_node('new-content');
    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_node('new-page');
    $wp_admin_bar->remove_node('new-eu_project');
    $wp_admin_bar->remove_node('new-eu_value');
    $wp_admin_bar->remove_node('new-eu_team');
    $wp_admin_bar->remove_node('new-eu_alliance');
    $wp_admin_bar->remove_node('new-eu_inquiry');

    // Editar página de inicio
    $wp_admin_bar->remove_node('edit_home_page');

    // Botón editar genérico
    $wp_admin_bar->remove_node('edit');

}
add_action('admin_bar_menu', 'eu_limpiar_admin_bar_editor', 999);

function eu_ocultar_admin_bar_editor() {

    $user = wp_get_current_user();

    if (in_array('editor', (array) $user->roles, true)) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'eu_ocultar_admin_bar_editor');
/**
 * Ocultar completamente la barra de administración
 * para usuarios con rol Editor.
 */
function eu_hide_admin_bar_for_editor() {

    $user = wp_get_current_user();

    if (
        is_user_logged_in() &&
        in_array('editor', (array) $user->roles, true)
    ) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'eu_hide_admin_bar_for_editor');
