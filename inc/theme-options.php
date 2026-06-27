<?php
/**
 * Theme options page.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'eu_add_theme_options_page');
function eu_add_theme_options_page() {
    add_theme_page(
        __('Ajustes Enclave Urbano', 'enclave-urbano'),
        __('Ajustes Enclave', 'enclave-urbano'),
        'manage_options',
        'eu-theme-options',
        'eu_render_theme_options_page'
    );
}

add_action('admin_init', 'eu_register_theme_options');
function eu_register_theme_options() {
    register_setting('eu_theme_options_group', 'eu_theme_options', array(
        'type'              => 'array',
        'sanitize_callback' => 'eu_sanitize_theme_options',
        'default'           => eu_default_options(),
    ));
}

function eu_sanitize_theme_options($input) {
    $defaults = eu_default_options();
    $output   = array();

    foreach ($defaults as $key => $default) {
        $value = isset($input[$key]) ? $input[$key] : '';

        switch ($key) {
            case 'primary_color':
            case 'accent_color':
                $output[$key] = sanitize_hex_color($value) ? sanitize_hex_color($value) : $default;
                break;
            case 'logo_large':
            case 'logo_small':
            case 'home_hero_image':
            case 'contact_qr':
            case 'footer_citybar_image':
            case 'instagram_url':
            case 'facebook_url':
            case 'linkedin_url':
            case 'youtube_url':
            case 'tiktok_url':
                $output[$key] = esc_url_raw($value);
                break;
            case 'footer_citybar_height_desktop':
            case 'footer_citybar_height_mobile':
                $number = absint($value);
                $output[$key] = $number ? (string) $number : $default;
                break;
            case 'home_mission_align':
            case 'footer_citybar_position_x':
                $allowed_x = array('left', 'center', 'right');
                $output[$key] = in_array($value, $allowed_x, true) ? $value : $default;
                break;
            case 'footer_citybar_position_y':
                $allowed_y = array('top', 'center', 'bottom');
                $output[$key] = in_array($value, $allowed_y, true) ? $value : $default;
                break;
            case 'footer_citybar_fit':
                $allowed_fit = array('cover', 'contain');
                $output[$key] = in_array($value, $allowed_fit, true) ? $value : $default;
                break;
            case 'contact_email':
                $output[$key] = sanitize_email($value);
                break;
            case 'home_mission':
            case 'home_genera_text':
            case 'home_alcance_text':
            case 'team_intro_text':
            case 'team_note':
            case 'address':
            case 'projects_intro':
                $output[$key] = wp_kses_post($value);
                break;
            default:
                $output[$key] = sanitize_text_field($value);
                break;
        }
    }

    return $output;
}

function eu_render_theme_options_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $options = wp_parse_args(get_option('eu_theme_options', array()), eu_default_options());
    ?>
    <div class="wrap eu-options-wrap">
        <h1><?php esc_html_e('Ajustes Enclave Urbano', 'enclave-urbano'); ?></h1>
        <p><?php esc_html_e('Configuración global del tema: identidad visual, datos de contacto, portada, textos de home y Google Maps.', 'enclave-urbano'); ?></p>

        <form method="post" action="options.php">
            <?php settings_fields('eu_theme_options_group'); ?>

            <div class="eu-admin-card">
                <h2><?php esc_html_e('Identidad visual', 'enclave-urbano'); ?></h2>
                <?php
                eu_option_media_field('logo_large', __('Logo grande', 'enclave-urbano'), $options['logo_large']);
                eu_option_media_field('logo_small', __('Logo chico / isotipo', 'enclave-urbano'), $options['logo_small']);
                eu_option_media_field('home_hero_image', __('Imagen de portada de la home', 'enclave-urbano'), $options['home_hero_image']);
                eu_option_media_field('contact_qr', __('QR de contacto', 'enclave-urbano'), $options['contact_qr']);
                eu_option_text_field('primary_color', __('Verde principal', 'enclave-urbano'), $options['primary_color'], 'text', '#336633');
                eu_option_text_field('accent_color', __('Amarillo', 'enclave-urbano'), $options['accent_color'], 'text', '#ffff00');
                eu_option_text_field('tagline', __('Frase institucional', 'enclave-urbano'), $options['tagline']);
                ?>
            </div>

            <div class="eu-admin-card">
                <h2><?php esc_html_e('Datos de contacto', 'enclave-urbano'); ?></h2>
                <?php
                eu_option_text_field('contact_email', __('Email receptor de formularios', 'enclave-urbano'), $options['contact_email'], 'email');
                eu_option_text_field('phone_admin', __('Teléfono administración', 'enclave-urbano'), $options['phone_admin']);
                eu_option_text_field('phone_sales', __('Teléfono comercialización', 'enclave-urbano'), $options['phone_sales']);
                eu_option_text_field('whatsapp', __('WhatsApp global', 'enclave-urbano'), $options['whatsapp']);
                eu_option_editor_field('address', __('Dirección / texto de ubicación', 'enclave-urbano'), $options['address']);
                ?>
            </div>

            <div class="eu-admin-card">
                <h2><?php esc_html_e('Redes sociales', 'enclave-urbano'); ?></h2>
                <?php
                eu_option_text_field('instagram_url', __('Instagram URL', 'enclave-urbano'), $options['instagram_url'], 'url');
                eu_option_text_field('facebook_url', __('Facebook URL', 'enclave-urbano'), $options['facebook_url'], 'url');
                eu_option_text_field('linkedin_url', __('LinkedIn URL', 'enclave-urbano'), $options['linkedin_url'], 'url');
                eu_option_text_field('youtube_url', __('YouTube URL', 'enclave-urbano'), $options['youtube_url'], 'url');
                eu_option_text_field('tiktok_url', __('TikTok URL', 'enclave-urbano'), $options['tiktok_url'], 'url');
                ?>
            </div>

            <div class="eu-admin-card">
                <h2><?php esc_html_e('Home: Enclave Urbano', 'enclave-urbano'); ?></h2>
                <?php
                eu_option_select_field('home_mission_align', __('Alineación del texto de portada', 'enclave-urbano'), $options['home_mission_align'], array(
                    'left'   => __('Izquierda', 'enclave-urbano'),
                    'center' => __('Centrado', 'enclave-urbano'),
                    'right'  => __('Derecha', 'enclave-urbano'),
                ));
                eu_option_editor_field('home_mission', __('Texto de portada', 'enclave-urbano'), $options['home_mission']);
                eu_option_text_field('home_genera_title', __('Título bloque Genera', 'enclave-urbano'), $options['home_genera_title']);
                eu_option_editor_field('home_genera_text', __('Texto bloque Genera', 'enclave-urbano'), $options['home_genera_text']);
                eu_option_text_field('home_alcance_title', __('Título bloque Alcance', 'enclave-urbano'), $options['home_alcance_title']);
                eu_option_editor_field('home_alcance_text', __('Texto bloque Alcance', 'enclave-urbano'), $options['home_alcance_text']);
                ?>
            </div>

            <div class="eu-admin-card">
                <h2><?php esc_html_e('Home: Equipo', 'enclave-urbano'); ?></h2>
                <?php
                eu_option_text_field('team_intro_title', __('Título lateral', 'enclave-urbano'), $options['team_intro_title']);
                eu_option_editor_field('team_intro_text', __('Texto lateral', 'enclave-urbano'), $options['team_intro_text']);
                eu_option_editor_field('team_note', __('Caja inferior de equipo', 'enclave-urbano'), $options['team_note']);
                ?>
            </div>

            <div class="eu-admin-card">
                <h2><?php esc_html_e('Página de Proyectos', 'enclave-urbano'); ?></h2>
                <?php
                eu_option_text_field('projects_kicker', __('Kicker (texto pequeño sobre el título)', 'enclave-urbano'), $options['projects_kicker']);
                eu_option_text_field('projects_title', __('Título de la página', 'enclave-urbano'), $options['projects_title']);
                eu_option_editor_field_with_media('projects_intro', __('Texto e imágenes de introducción', 'enclave-urbano'), $options['projects_intro']);
                ?>
            </div>

            <div class="eu-admin-card">
                <h2><?php esc_html_e('Proyectos y Google Maps', 'enclave-urbano'); ?></h2>
                <?php eu_option_text_field('google_maps_api_key', __('Google Maps API Key', 'enclave-urbano'), $options['google_maps_api_key']); ?>
                <p class="description"><?php esc_html_e('Necesaria para visualizar archivos KML dentro de las páginas de proyecto. El archivo KML debe estar disponible en una URL pública.', 'enclave-urbano'); ?></p>
            </div>

            <div class="eu-admin-card">
                <h2><?php esc_html_e('Footer', 'enclave-urbano'); ?></h2>
                <?php
                eu_option_text_field('footer_text', __('Texto principal del footer', 'enclave-urbano'), $options['footer_text']);
                eu_option_media_field('footer_citybar_image', __('Imagen banda Ciudad Abierta', 'enclave-urbano'), $options['footer_citybar_image']);
                eu_option_text_field('footer_citybar_height_desktop', __('Altura de la banda en escritorio (px)', 'enclave-urbano'), $options['footer_citybar_height_desktop'], 'number', '185');
                eu_option_text_field('footer_citybar_height_mobile', __('Altura de la banda en mobile (px)', 'enclave-urbano'), $options['footer_citybar_height_mobile'], 'number', '90');
                eu_option_select_field('footer_citybar_position_x', __('Posición horizontal de la imagen', 'enclave-urbano'), $options['footer_citybar_position_x'], array(
                    'left'   => __('Izquierda', 'enclave-urbano'),
                    'center' => __('Centro', 'enclave-urbano'),
                    'right'  => __('Derecha', 'enclave-urbano'),
                ));
                eu_option_select_field('footer_citybar_position_y', __('Posición vertical de la imagen', 'enclave-urbano'), $options['footer_citybar_position_y'], array(
                    'top'    => __('Arriba', 'enclave-urbano'),
                    'center' => __('Centro', 'enclave-urbano'),
                    'bottom' => __('Abajo', 'enclave-urbano'),
                ));
                eu_option_select_field('footer_citybar_fit', __('Ajuste de la imagen', 'enclave-urbano'), $options['footer_citybar_fit'], array(
                    'cover'   => __('Cubrir todo el ancho', 'enclave-urbano'),
                    'contain' => __('Mostrar completa sin recortar', 'enclave-urbano'),
                ));
                ?>
                <p class="description"><?php esc_html_e('Estos campos permiten cambiar la imagen del footer, su altura y el encuadre sin editar el tema.', 'enclave-urbano'); ?></p>
            </div>

            <?php submit_button(__('Guardar ajustes', 'enclave-urbano')); ?>
        </form>
    </div>
    <?php
}

function eu_option_text_field($key, $label, $value, $type = 'text', $placeholder = '') {
    ?>
    <div class="eu-field-row">
        <label for="eu-option-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label>
        <input id="eu-option-<?php echo esc_attr($key); ?>" type="<?php echo esc_attr($type); ?>" name="eu_theme_options[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" class="regular-text" />
    </div>
    <?php
}

function eu_option_editor_field($key, $label, $value) {
    $editor_id = 'eu_option_' . $key;
    ?>
    <div class="eu-field-row eu-editor-row">
        <label><?php echo esc_html($label); ?></label>
        <?php
        wp_editor($value, $editor_id, array(
            'textarea_name' => 'eu_theme_options[' . $key . ']',
            'textarea_rows' => 8,
            'media_buttons' => false,
            'teeny'         => false,
            'tinymce'       => array(
                'toolbar1' => 'formatselect,|,bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,|,removeformat',
                'toolbar2' => '',
                'block_formats' => 'Párrafo=p;Título 2=h2;Título 3=h3',
            ),
            'quicktags'     => array('buttons' => 'strong,em,ul,ol,li,link'),
        ));
        ?>
    </div>
    <?php
}

function eu_option_editor_field_with_media($key, $label, $value) {
    $editor_id = 'eu_option_' . $key;
    ?>
    <div class="eu-field-row eu-editor-row">
        <label><?php echo esc_html($label); ?></label>
        <?php
        wp_editor($value, $editor_id, array(
            'textarea_name' => 'eu_theme_options[' . $key . ']',
            'textarea_rows' => 12,
            'media_buttons' => true,
            'teeny'         => false,
            'tinymce'       => array(
                'toolbar1' => 'bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,|,image,|,removeformat',
                'toolbar2' => '',
            ),
            'quicktags'     => array('buttons' => 'strong,em,ul,ol,li,link,img'),
        ));
        ?>
    </div>
    <?php
}

function eu_option_select_field($key, $label, $value, $choices) {
    ?>
    <div class="eu-field-row">
        <label for="eu-option-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label>
        <select id="eu-option-<?php echo esc_attr($key); ?>" name="eu_theme_options[<?php echo esc_attr($key); ?>]">
            <?php foreach ($choices as $choice_value => $choice_label) : ?>
                <option value="<?php echo esc_attr($choice_value); ?>" <?php selected($value, $choice_value); ?>><?php echo esc_html($choice_label); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}

function eu_option_media_field($key, $label, $value) {
    ?>
    <div class="eu-field-row eu-media-row">
        <label for="eu-option-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label>
        <div class="eu-media-control">
            <input id="eu-option-<?php echo esc_attr($key); ?>" type="url" name="eu_theme_options[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>" class="regular-text eu-media-url" />
            <button type="button" class="button eu-media-button" data-target="#eu-option-<?php echo esc_attr($key); ?>"><?php esc_html_e('Seleccionar', 'enclave-urbano'); ?></button>
            <button type="button" class="button eu-media-clear" data-target="#eu-option-<?php echo esc_attr($key); ?>"><?php esc_html_e('Quitar', 'enclave-urbano'); ?></button>
        </div>
        <?php if ($value) : ?>
            <img class="eu-media-preview" src="<?php echo esc_url($value); ?>" alt="" />
        <?php endif; ?>
    </div>
    <?php
}
