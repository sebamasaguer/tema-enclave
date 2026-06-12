<?php
/**
 * Template Name: Filosofía
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main eu-philosophy-page">
    <div class="eu-container eu-page-shell">
        <header class="eu-page-header eu-page-header--compact">
            <h1><?php the_title(); ?></h1>
        </header>
        <?php eu_page_content_or_fallback('eu_render_philosophy_fallback'); ?>
    </div>
</main>
<?php
get_footer();

function eu_render_philosophy_fallback() {
    $cards = array(
        array('title' => 'Pasión y crecimiento', 'text' => 'Abordamos nuestro trabajo con entusiasmo y dedicación, buscando inspirar a los demás con compromiso y constancia.'),
        array('title' => 'Confianza y personalización', 'text' => 'Cada cliente está en el centro de lo que hacemos. Buscamos siempre satisfacer sus necesidades y generar vínculos fuertes.'),
        array('title' => 'Responsabilidad y compromiso', 'text' => 'Asumimos un acuerdo con clientes, inversores y proveedores, reconociendo el impacto positivo que la inversión puede tener en la sociedad.'),
        array('title' => 'Colaboración', 'text' => 'Valoramos la colaboración y el esfuerzo conjunto para alcanzar objetivos comunes con un equipo interdisciplinario.'),
        array('title' => 'Honestidad', 'text' => 'Nos manejamos con honestidad y transparencia, confiando en una comunicación eficaz y constante.'),
    );
    ?>
    <section class="eu-philosophy-cards">
        <?php foreach ($cards as $index => $card) : ?>
            <article class="<?php echo esc_attr(0 === $index || 3 === $index ? 'is-dark' : 'is-light'); ?>">
                <h2><?php echo esc_html($card['title']); ?></h2>
                <p><?php echo esc_html($card['text']); ?></p>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="eu-timeline-section">
        <h2><?php esc_html_e('Puntos clave de la filosofía del equipo de Enclave', 'enclave-urbano'); ?></h2>
        <div class="eu-timeline">
            <?php
            $points = array(
                array('Cliente', 'Enfoque en superar expectativas en cada proyecto.'),
                array('Crecimiento', 'Sostenido y gradual.'),
                array('Sustentabilidad', 'Integración de prácticas responsables en la cadena de valor.'),
                array('Seguridad', 'Reducción de riesgos.'),
                array('Compromiso', 'Bienestar laboral.'),
            );
            foreach ($points as $i => $point) :
                ?>
                <div class="eu-timeline-point <?php echo 0 === $i ? 'is-active' : ''; ?>">
                    <span></span>
                    <h3><?php echo esc_html($point[0]); ?></h3>
                    <p><?php echo esc_html($point[1]); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}
