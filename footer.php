<?php
/**
 * Site footer.
 *
 * @package Enclave_Urbano
 */
?>
<footer class="eu-site-footer">

   
    <div class="eu-container eu-footer-grid">
        <section class="eu-footer-col">
            <h2><?php esc_html_e('Contacto', 'enclave-urbano'); ?></h2>
            <p><strong><?php esc_html_e('Administración:', 'enclave-urbano'); ?></strong><br><?php echo esc_html(eu_get_option('phone_admin')); ?></p>
            <p><strong><?php esc_html_e('Comercialización:', 'enclave-urbano'); ?></strong><br><?php echo esc_html(eu_get_option('phone_sales')); ?></p>
            <p><strong>Email:</strong><br><a href="mailto:<?php echo esc_attr(eu_get_option('contact_email')); ?>"><?php echo esc_html(eu_get_option('contact_email')); ?></a></p>
            <?php if (eu_get_option('address')) : ?>
                <p><?php echo nl2br(esc_html(eu_get_option('address'))); ?></p>
            <?php endif; ?>
        </section>

        <section class="eu-footer-col">
            <h2><?php esc_html_e('Menú', 'enclave-urbano'); ?></h2>
            <?php eu_footer_menu(); ?>
        </section>

        <section class="eu-footer-col">
            <h2><?php esc_html_e('Redes sociales', 'enclave-urbano'); ?></h2>
            <?php echo eu_social_links(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php if (is_active_sidebar('footer-extra')) : ?>
                <div class="eu-footer-widgets"><?php dynamic_sidebar('footer-extra'); ?></div>
            <?php endif; ?>
        </section>
    </div>

    <div class="eu-footer-legal">
        <div class="eu-container">
            <p><a href="<?php echo esc_url(get_permalink(get_page_by_path('terminos-y-condiciones'))); ?>"><?php esc_html_e('Términos y Condiciones', 'enclave-urbano'); ?></a> / <a href="<?php echo esc_url(get_permalink(get_page_by_path('politicas-de-privacidad'))); ?>"><?php esc_html_e('Políticas de Privacidad', 'enclave-urbano'); ?></a> / <?php esc_html_e('Todos los derechos, imágenes y videos reservados.', 'enclave-urbano'); ?> Copyright &copy; <?php echo date('Y'); ?> ENCLAVEURBANO by <a href="https://www.instagram.com/mariadanielasaravia" target="_blank" rel="noopener noreferrer">Agencia de Diseño</a></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
