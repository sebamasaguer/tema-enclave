<?php
/**
 * Contact form handling.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('enclave_contacto', 'eu_contact_form_shortcode');
function eu_contact_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'context'    => 'shortcode',
        'project_id' => 0,
        'title'      => 'Escribinos',
    ), $atts, 'enclave_contacto');

    return eu_get_contact_form(array(
        'context'    => sanitize_text_field($atts['context']),
        'project_id' => absint($atts['project_id']),
        'title'      => sanitize_text_field($atts['title']),
    ));
}

function eu_get_contact_form($args = array()) {
    $args = wp_parse_args($args, array(
        'context'    => 'general',
        'project_id' => 0,
        'title'      => __('Escribinos', 'enclave-urbano'),
    ));

    ob_start();
    eu_render_contact_form($args);
    return ob_get_clean();
}

function eu_render_contact_form($args = array()) {
    $args = wp_parse_args($args, array(
        'context'    => 'general',
        'project_id' => 0,
        'title'      => __('Escribinos', 'enclave-urbano'),
    ));

    $sent  = isset($_GET['eu_sent']) ? sanitize_text_field(wp_unslash($_GET['eu_sent'])) : '';
    $error = isset($_GET['eu_error']) ? sanitize_text_field(wp_unslash($_GET['eu_error'])) : '';
    ?>
    <div class="eu-contact-form-wrap">
        <?php if (!empty($args['title'])) : ?>
            <h2 class="eu-contact-form-title"><?php echo esc_html($args['title']); ?></h2>
        <?php endif; ?>

        <?php if ('1' === $sent) : ?>
            <div class="eu-form-message eu-form-message--success"><?php esc_html_e('Gracias. Tu consulta fue enviada correctamente.', 'enclave-urbano'); ?></div>
        <?php endif; ?>

        <?php if ($error) : ?>
            <div class="eu-form-message eu-form-message--error"><?php esc_html_e('Revisá los campos obligatorios e intentá nuevamente.', 'enclave-urbano'); ?></div>
        <?php endif; ?>

        <form class="eu-contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="eu_contact_form">
            <input type="hidden" name="context" value="<?php echo esc_attr($args['context']); ?>">
            <input type="hidden" name="project_id" value="<?php echo absint($args['project_id']); ?>">
            <input type="hidden" name="redirect_to" value="<?php echo esc_url(eu_current_url()); ?>">
            <?php wp_nonce_field('eu_contact_form', 'eu_nonce'); ?>

            <div class="eu-honeypot" aria-hidden="true">
                <label for="eu-website"><?php esc_html_e('Website', 'enclave-urbano'); ?></label>
                <input id="eu-website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <label>
                <span><?php esc_html_e('Nombre y apellido', 'enclave-urbano'); ?></span>
                <input type="text" name="name" required>
            </label>

            <label>
                <span><?php esc_html_e('Email', 'enclave-urbano'); ?></span>
                <input type="email" name="email" required>
            </label>

            <label>
                <span><?php esc_html_e('Teléfono', 'enclave-urbano'); ?></span>
                <input type="tel" name="phone">
            </label>

            <label>
                <span><?php esc_html_e('Mensaje', 'enclave-urbano'); ?></span>
                <textarea name="message" rows="5" required></textarea>
            </label>

            <button type="submit" class="eu-button eu-button--primary"><?php esc_html_e('Enviar consulta', 'enclave-urbano'); ?></button>
        </form>
    </div>
    <?php
}

function eu_current_url() {
    $scheme = is_ssl() ? 'https://' : 'http://';
    $host   = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
    $uri    = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
    return esc_url_raw($scheme . $host . $uri);
}

add_action('admin_post_nopriv_eu_contact_form', 'eu_handle_contact_form');
add_action('admin_post_eu_contact_form', 'eu_handle_contact_form');
function eu_handle_contact_form() {
    $redirect = isset($_POST['redirect_to']) ? esc_url_raw(wp_unslash($_POST['redirect_to'])) : home_url('/contacto/');
    $redirect = wp_validate_redirect($redirect, home_url('/contacto/'));
    $redirect = remove_query_arg(array('eu_sent', 'eu_error'), $redirect);

    if (!isset($_POST['eu_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_nonce'])), 'eu_contact_form')) {
        wp_safe_redirect(add_query_arg('eu_error', 'nonce', $redirect));
        exit;
    }

    if (!empty($_POST['website'])) {
        wp_safe_redirect(add_query_arg('eu_sent', '1', $redirect));
        exit;
    }

    $name       = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email      = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $phone      = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $message    = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
    $context    = isset($_POST['context']) ? sanitize_text_field(wp_unslash($_POST['context'])) : 'general';
    $project_id = isset($_POST['project_id']) ? absint($_POST['project_id']) : 0;

    if (!$name || !$email || !$message || !is_email($email)) {
        wp_safe_redirect(add_query_arg('eu_error', 'required', $redirect));
        exit;
    }

    $post_id = wp_insert_post(array(
        'post_type'    => 'eu_inquiry',
        'post_status'  => 'publish',
        'post_title'   => sprintf(__('Consulta de %1$s - %2$s', 'enclave-urbano'), $name, current_time('d/m/Y H:i')),
        'post_content' => $message,
    ), true);

    if (!is_wp_error($post_id)) {
        update_post_meta($post_id, '_eu_inquiry_name', $name);
        update_post_meta($post_id, '_eu_inquiry_email', $email);
        update_post_meta($post_id, '_eu_inquiry_phone', $phone);
        update_post_meta($post_id, '_eu_inquiry_context', $context);
        update_post_meta($post_id, '_eu_inquiry_project_id', $project_id);
        update_post_meta($post_id, '_eu_inquiry_status', 'nuevo');
    }

    $to      = eu_get_option('contact_email', 'info@enclaveurbano.com.ar');
    $subject = sprintf(__('Nueva consulta desde %s', 'enclave-urbano'), get_bloginfo('name'));
    $project = $project_id ? get_the_title($project_id) : '';
    $body    = "Nueva consulta recibida desde el sitio web.\n\n";
    $body   .= "Nombre: {$name}\n";
    $body   .= "Email: {$email}\n";
    $body   .= "Teléfono: {$phone}\n";
    $body   .= "Contexto: {$context}\n";
    if ($project) {
        $body .= "Proyecto: {$project}\n";
    }
    $body .= "\nMensaje:\n{$message}\n";

    $headers = array('Reply-To: ' . $name . ' <' . $email . '>');
    wp_mail($to, $subject, $body, $headers);

    wp_safe_redirect(add_query_arg('eu_sent', '1', $redirect));
    exit;
}
