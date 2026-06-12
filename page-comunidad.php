<?php
/**
 * Template Name: Comunidad
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main eu-community-page">
    <div class="eu-container eu-page-shell">
        <header class="eu-page-header eu-page-header--compact">
            <h1><?php the_title(); ?></h1>
        </header>
        <?php eu_page_content_or_fallback('eu_render_community_fallback'); ?>
    </div>
</main>
<?php
get_footer();

function eu_render_community_fallback() {
    ?>
    <section class="eu-community-flow">
        <div class="eu-community-flow__brand"><?php echo eu_logo_img('large', ''); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <span class="eu-arrow">→</span>
        <strong><?php esc_html_e('La Enriqueta', 'enclave-urbano'); ?></strong>
        <span class="eu-arrow">→</span>
        <span><?php esc_html_e('Distrito San Luis', 'enclave-urbano'); ?></span>
    </section>

    <p class="eu-lead eu-community-lead"><?php esc_html_e('En una empresa de desarrollo y construcción, el término comunidad no se refiere solo a los vecinos, sino a un ecosistema de relaciones que impacta directamente en la viabilidad del negocio.', 'enclave-urbano'); ?></p>
    <p class="eu-muted-green"><?php esc_html_e('Se puede desglosar en tres dimensiones principales:', 'enclave-urbano'); ?></p>

    <section class="eu-community-cards">
        <article>
            <h2><?php esc_html_e('Entorno Social y Vecindad', 'enclave-urbano'); ?></h2>
            <p><?php esc_html_e('Se refiere a las personas que viven o trabajan en el área de influencia de una obra. Una buena gestión de comunidad implica mitigar molestias, dialogar y buscar consenso social.', 'enclave-urbano'); ?></p>
        </article>
        <article>
            <h2><?php esc_html_e('Comunidad de Propietarios/Usuarios', 'enclave-urbano'); ?></h2>
            <p><?php esc_html_e('El enfoque es crear sentido de pertenencia a través de áreas comunes, servicios compartidos y una cultura de convivencia que aporte valor agregado al inmueble.', 'enclave-urbano'); ?></p>
        </article>
        <article>
            <h2><?php esc_html_e('Responsabilidad Social Corporativa', 'enclave-urbano'); ?></h2>
            <p><?php esc_html_e('La empresa actúa como ciudadano corporativo que invierte en la mejora del barrio, fortaleciendo reputación y facilitando futuras licencias o permisos.', 'enclave-urbano'); ?></p>
        </article>
    </section>

    <div class="eu-community-closing">
        <p><?php esc_html_e('Es pasar de construir estructuras aisladas a integrar proyectos vivos en el tejido social.', 'enclave-urbano'); ?></p>
        <blockquote><?php echo esc_html(eu_get_option('tagline')); ?></blockquote>
        <span aria-hidden="true"><?php echo eu_inline_icon('strategy'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
    </div>
    <?php
}
