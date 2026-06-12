<?php
/**
 * Custom metaboxes.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('add_meta_boxes', 'eu_register_metaboxes');
function eu_register_metaboxes() {
    add_meta_box('eu_project_data', __('Datos del proyecto', 'enclave-urbano'), 'eu_render_project_metabox', 'eu_project', 'normal', 'high');
    add_meta_box('eu_team_data', __('Datos del miembro', 'enclave-urbano'), 'eu_render_team_metabox', 'eu_team', 'normal', 'high');
    add_meta_box('eu_value_data', __('Icono del valor', 'enclave-urbano'), 'eu_render_value_metabox', 'eu_value', 'side', 'default');
    add_meta_box('eu_alliance_data', __('Datos de la alianza', 'enclave-urbano'), 'eu_render_alliance_metabox', 'eu_alliance', 'normal', 'default');
    add_meta_box('eu_inquiry_data', __('Datos de la consulta', 'enclave-urbano'), 'eu_render_inquiry_metabox', 'eu_inquiry', 'normal', 'high');
}

function eu_project_fields() {
    return array(
        'location'       => array('label' => __('Ubicación', 'enclave-urbano'), 'type' => 'text'),
        'surface'        => array('label' => __('Superficie total', 'enclave-urbano'), 'type' => 'text'),
        'units'          => array('label' => __('Cantidad de unidades', 'enclave-urbano'), 'type' => 'text'),
        'investment'     => array('label' => __('Inversión mínima', 'enclave-urbano'), 'type' => 'text'),
        'delivery_date'  => array('label' => __('Fecha de entrega', 'enclave-urbano'), 'type' => 'text'),
        'stage'          => array('label' => __('Etapa actual', 'enclave-urbano'), 'type' => 'text'),
        'price_from'     => array('label' => __('Precio desde', 'enclave-urbano'), 'type' => 'text'),
        'video_url'      => array('label' => __('Video URL (YouTube/Vimeo)', 'enclave-urbano'), 'type' => 'url'),
        'maps_embed_url' => array('label' => __('Google Maps Embed URL', 'enclave-urbano'), 'type' => 'url'),
        'whatsapp'       => array('label' => __('WhatsApp de contacto', 'enclave-urbano'), 'type' => 'text'),
        'kml_url'        => array('label' => __('Archivo KML URL', 'enclave-urbano'), 'type' => 'url', 'media' => true, 'description' => __('Subir o pegar una URL pública de archivo .kml.', 'enclave-urbano')),
        'map_lat'        => array('label' => __('Latitud inicial del mapa', 'enclave-urbano'), 'type' => 'text'),
        'map_lng'        => array('label' => __('Longitud inicial del mapa', 'enclave-urbano'), 'type' => 'text'),
        'map_zoom'       => array('label' => __('Zoom inicial', 'enclave-urbano'), 'type' => 'number'),
    );
}

function eu_render_project_metabox($post) {
    wp_nonce_field('eu_save_project_data', 'eu_project_nonce');
    echo '<div class="eu-metabox-grid">';
    foreach (eu_project_fields() as $key => $field) {
        $meta_key = '_eu_project_' . $key;
        $value    = get_post_meta($post->ID, $meta_key, true);
        eu_render_meta_field($meta_key, $field, $value);
    }
    echo '</div>';
    echo '<p class="description">' . esc_html__('La imagen destacada funciona como portada del proyecto. El contenido principal del editor se muestra como descripción.', 'enclave-urbano') . '</p>';
}

function eu_team_fields() {
    return array(
        'position' => array('label' => __('Cargo / especialidad', 'enclave-urbano'), 'type' => 'text'),
        'qr_url'   => array('label' => __('QR personal', 'enclave-urbano'), 'type' => 'url', 'media' => true),
        'email'    => array('label' => __('Email', 'enclave-urbano'), 'type' => 'email'),
        'phone'    => array('label' => __('Teléfono', 'enclave-urbano'), 'type' => 'text'),
        'link_url' => array('label' => __('Link externo / matrícula / perfil', 'enclave-urbano'), 'type' => 'url'),
    );
}

function eu_render_team_metabox($post) {
    wp_nonce_field('eu_save_team_data', 'eu_team_nonce');
    echo '<div class="eu-metabox-grid">';
    foreach (eu_team_fields() as $key => $field) {
        $meta_key = '_eu_team_' . $key;
        $value    = get_post_meta($post->ID, $meta_key, true);
        eu_render_meta_field($meta_key, $field, $value);
    }
    echo '</div>';
    echo '<p class="description">' . esc_html__('La imagen destacada funciona como foto del profesional. El orden se controla desde Atributos de página > Orden.', 'enclave-urbano') . '</p>';
}

function eu_render_value_metabox($post) {
    wp_nonce_field('eu_save_value_data', 'eu_value_nonce');
    $field = array('label' => __('Icono personalizado', 'enclave-urbano'), 'type' => 'url', 'media' => true, 'description' => __('Opcional. También se puede usar imagen destacada.', 'enclave-urbano'));
    $value = get_post_meta($post->ID, '_eu_value_icon_url', true);
    eu_render_meta_field('_eu_value_icon_url', $field, $value);
}

function eu_alliance_fields() {
    return array(
        'website' => array('label' => __('Sitio web', 'enclave-urbano'), 'type' => 'url'),
        'phone'   => array('label' => __('Teléfono', 'enclave-urbano'), 'type' => 'text'),
        'email'   => array('label' => __('Email', 'enclave-urbano'), 'type' => 'email'),
    );
}

function eu_render_alliance_metabox($post) {
    wp_nonce_field('eu_save_alliance_data', 'eu_alliance_nonce');
    echo '<div class="eu-metabox-grid">';
    foreach (eu_alliance_fields() as $key => $field) {
        $meta_key = '_eu_alliance_' . $key;
        $value    = get_post_meta($post->ID, $meta_key, true);
        eu_render_meta_field($meta_key, $field, $value);
    }
    echo '</div>';
    echo '<p class="description">' . esc_html__('La imagen destacada funciona como logo o imagen de la alianza. Usar la taxonomía Tipo de alianza para Organismos, Profesionales, Inmobiliarias, etc.', 'enclave-urbano') . '</p>';
}

function eu_render_inquiry_metabox($post) {
    wp_nonce_field('eu_save_inquiry_data', 'eu_inquiry_nonce');
    $fields = array(
        'name'       => __('Nombre y apellido', 'enclave-urbano'),
        'email'      => __('Email', 'enclave-urbano'),
        'phone'      => __('Teléfono', 'enclave-urbano'),
        'context'    => __('Contexto', 'enclave-urbano'),
        'project_id' => __('Proyecto relacionado', 'enclave-urbano'),
        'status'     => __('Estado', 'enclave-urbano'),
    );

    echo '<table class="widefat striped eu-inquiry-table"><tbody>';
    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, '_eu_inquiry_' . $key, true);
        if ('project_id' === $key && $value) {
            $project = get_post($value);
            $value   = $project ? $project->post_title . ' (#' . $project->ID . ')' : $value;
        }
        echo '<tr><th>' . esc_html($label) . '</th><td>' . esc_html($value) . '</td></tr>';
    }
    echo '</tbody></table>';

    $status = get_post_meta($post->ID, '_eu_inquiry_status', true);
    if (!$status) {
        $status = 'nuevo';
    }
    ?>
    <p>
        <label for="eu-inquiry-status"><strong><?php esc_html_e('Cambiar estado', 'enclave-urbano'); ?></strong></label><br>
        <select name="eu_inquiry_status" id="eu-inquiry-status">
            <option value="nuevo" <?php selected($status, 'nuevo'); ?>><?php esc_html_e('Nuevo', 'enclave-urbano'); ?></option>
            <option value="leido" <?php selected($status, 'leido'); ?>><?php esc_html_e('Leído', 'enclave-urbano'); ?></option>
            <option value="respondido" <?php selected($status, 'respondido'); ?>><?php esc_html_e('Respondido', 'enclave-urbano'); ?></option>
            <option value="archivado" <?php selected($status, 'archivado'); ?>><?php esc_html_e('Archivado', 'enclave-urbano'); ?></option>
        </select>
    </p>
    <?php
}

function eu_render_meta_field($meta_key, $field, $value) {
    $type        = isset($field['type']) ? $field['type'] : 'text';
    $has_media   = !empty($field['media']);
    $description = isset($field['description']) ? $field['description'] : '';
    ?>
    <div class="eu-meta-field">
        <label for="<?php echo esc_attr($meta_key); ?>"><?php echo esc_html($field['label']); ?></label>
        <div class="eu-meta-input-wrap">
            <input id="<?php echo esc_attr($meta_key); ?>" type="<?php echo esc_attr($type); ?>" name="<?php echo esc_attr($meta_key); ?>" value="<?php echo esc_attr($value); ?>" class="widefat<?php echo $has_media ? ' eu-media-url' : ''; ?>" />
            <?php if ($has_media) : ?>
                <button type="button" class="button eu-media-button" data-target="#<?php echo esc_attr($meta_key); ?>"><?php esc_html_e('Seleccionar', 'enclave-urbano'); ?></button>
                <button type="button" class="button eu-media-clear" data-target="#<?php echo esc_attr($meta_key); ?>"><?php esc_html_e('Quitar', 'enclave-urbano'); ?></button>
            <?php endif; ?>
        </div>
        <?php if ($description) : ?>
            <p class="description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
        <?php if ($has_media && $value) : ?>
            <img class="eu-media-preview" src="<?php echo esc_url($value); ?>" alt="" />
        <?php endif; ?>
    </div>
    <?php
}

add_action('save_post', 'eu_save_metaboxes');
function eu_save_metaboxes($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $post_type = get_post_type($post_id);

    if ('eu_project' === $post_type) {
        if (!isset($_POST['eu_project_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_project_nonce'])), 'eu_save_project_data')) {
            return;
        }
        foreach (eu_project_fields() as $key => $field) {
            eu_save_meta_value($post_id, '_eu_project_' . $key, $field['type']);
        }
    }

    if ('eu_team' === $post_type) {
        if (!isset($_POST['eu_team_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_team_nonce'])), 'eu_save_team_data')) {
            return;
        }
        foreach (eu_team_fields() as $key => $field) {
            eu_save_meta_value($post_id, '_eu_team_' . $key, $field['type']);
        }
    }

    if ('eu_value' === $post_type) {
        if (!isset($_POST['eu_value_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_value_nonce'])), 'eu_save_value_data')) {
            return;
        }
        eu_save_meta_value($post_id, '_eu_value_icon_url', 'url');
    }

    if ('eu_alliance' === $post_type) {
        if (!isset($_POST['eu_alliance_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_alliance_nonce'])), 'eu_save_alliance_data')) {
            return;
        }
        foreach (eu_alliance_fields() as $key => $field) {
            eu_save_meta_value($post_id, '_eu_alliance_' . $key, $field['type']);
        }
    }

    if ('eu_inquiry' === $post_type) {
        if (!isset($_POST['eu_inquiry_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_inquiry_nonce'])), 'eu_save_inquiry_data')) {
            return;
        }
        if (isset($_POST['eu_inquiry_status'])) {
            update_post_meta($post_id, '_eu_inquiry_status', sanitize_text_field(wp_unslash($_POST['eu_inquiry_status'])));
        }
    }
}

function eu_save_meta_value($post_id, $meta_key, $type = 'text') {
    if (!isset($_POST[$meta_key])) {
        delete_post_meta($post_id, $meta_key);
        return;
    }

    $raw = wp_unslash($_POST[$meta_key]);

    switch ($type) {
        case 'url':
            $value = esc_url_raw($raw);
            break;
        case 'email':
            $value = sanitize_email($raw);
            break;
        case 'number':
            $value = is_numeric($raw) ? (string) $raw : '';
            break;
        default:
            $value = sanitize_text_field($raw);
            break;
    }

    if ('' === $value) {
        delete_post_meta($post_id, $meta_key);
    } else {
        update_post_meta($post_id, $meta_key, $value);
    }
}

add_filter('manage_eu_inquiry_posts_columns', 'eu_inquiry_columns');
function eu_inquiry_columns($columns) {
    $new = array();
    $new['cb'] = isset($columns['cb']) ? $columns['cb'] : '<input type="checkbox" />';
    $new['title'] = __('Consulta', 'enclave-urbano');
    $new['eu_name'] = __('Nombre', 'enclave-urbano');
    $new['eu_email'] = __('Email', 'enclave-urbano');
    $new['eu_phone'] = __('Teléfono', 'enclave-urbano');
    $new['eu_status'] = __('Estado', 'enclave-urbano');
    $new['date'] = __('Fecha', 'enclave-urbano');
    return $new;
}

add_action('manage_eu_inquiry_posts_custom_column', 'eu_inquiry_columns_content', 10, 2);
function eu_inquiry_columns_content($column, $post_id) {
    if ('eu_name' === $column) {
        echo esc_html(get_post_meta($post_id, '_eu_inquiry_name', true));
    }
    if ('eu_email' === $column) {
        echo esc_html(get_post_meta($post_id, '_eu_inquiry_email', true));
    }
    if ('eu_phone' === $column) {
        echo esc_html(get_post_meta($post_id, '_eu_inquiry_phone', true));
    }
    if ('eu_status' === $column) {
        echo esc_html(get_post_meta($post_id, '_eu_inquiry_status', true) ?: 'nuevo');
    }
}
