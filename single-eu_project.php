<?php
/**
 * Single project template.
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-project-single">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $hero = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'eu-hero') : eu_get_option('home_hero_image');
        $location = eu_project_meta(get_the_ID(), 'location');
        $video = eu_project_meta(get_the_ID(), 'video_url');
        $embed = eu_project_meta(get_the_ID(), 'maps_embed_url');
        $kml = eu_project_meta(get_the_ID(), 'kml_url');
        $lat = eu_project_meta(get_the_ID(), 'map_lat');
        $lng = eu_project_meta(get_the_ID(), 'map_lng');
        $zoom = eu_project_meta(get_the_ID(), 'map_zoom') ?: '17';
        $whatsapp = eu_project_meta(get_the_ID(), 'whatsapp');
        $wa_link = eu_whatsapp_link($whatsapp, sprintf(__('Hola, quiero consultar por %s', 'enclave-urbano'), get_the_title()));
        ?>
        <header class="eu-project-hero" style="background-image:url('<?php echo esc_url($hero); ?>')">
            <div class="eu-project-hero__overlay"></div>
            <div class="eu-container eu-project-hero__content">
                <?php if ($location) : ?><p class="eu-kicker"><?php echo esc_html($location); ?></p><?php endif; ?>
                <h1><?php the_title(); ?></h1>
            </div>
        </header>

        <div class="eu-container eu-project-layout">
            <article class="eu-project-maincol">
                <?php if ($video) : ?>
                    <section class="eu-project-block">
                        <h2><?php esc_html_e('Video', 'enclave-urbano'); ?></h2>
                        <div class="eu-video-embed">
                            <?php
                            $video_html = wp_oembed_get($video);
                            if ($video_html) {
                                echo $video_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            } else {
                                echo '<a href="' . esc_url($video) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Ver video', 'enclave-urbano') . '</a>';
                            }
                            ?>
                        </div>
                    </section>
                <?php endif; ?>

                <section class="eu-project-block eu-project-description">
                    <h2><?php esc_html_e('Descripción', 'enclave-urbano'); ?></h2>
                    <div class="eu-page-editor-content"><?php the_content(); ?></div>
                </section>

                <?php if ($embed) : ?>
                    <section class="eu-project-block">
                        <h2><?php esc_html_e('Ubicación', 'enclave-urbano'); ?></h2>
                        <div class="eu-map-embed">
                            <iframe src="<?php echo esc_url($embed); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($kml || ($lat && $lng)) : ?>
                    <section class="eu-project-block">
                        <h2><?php esc_html_e('Urbanización', 'enclave-urbano'); ?></h2>
                        <?php if (eu_get_option('google_maps_api_key')) : ?>
                            <div class="eu-kml-map" data-kml="<?php echo esc_url($kml); ?>" data-lat="<?php echo esc_attr($lat); ?>" data-lng="<?php echo esc_attr($lng); ?>" data-zoom="<?php echo esc_attr($zoom); ?>"></div>
                        <?php else : ?>
                            <div class="eu-alert"><?php esc_html_e('Para mostrar el KML, cargá la Google Maps API Key en Apariencia > Ajustes Enclave.', 'enclave-urbano'); ?></div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
            </article>

            <aside class="eu-project-sidebar">
                <div class="eu-project-ficha">
                    <h2><?php esc_html_e('Ficha técnica', 'enclave-urbano'); ?></h2>
                    <dl>
                        <?php foreach (eu_project_technical_fields(get_the_ID()) as $label => $value) : ?>
                            <div>
                                <dt><?php echo esc_html($label); ?></dt>
                                <dd><?php echo esc_html($value); ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                    <?php if ($wa_link) : ?>
                        <a class="eu-button eu-button--accent" href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Consultar por WhatsApp', 'enclave-urbano'); ?></a>
                    <?php endif; ?>
                    <a class="eu-button eu-button--outline-light" href="#consulta-proyecto"><?php esc_html_e('Contactar', 'enclave-urbano'); ?></a>
                </div>
            </aside>
        </div>

        <section id="consulta-proyecto" class="eu-section eu-project-contact">
            <div class="eu-container eu-home-contact-grid">
                <div>
                    <h2 class="eu-section-title"><?php esc_html_e('Consultá por este proyecto', 'enclave-urbano'); ?></h2>
                    <p class="eu-lead"><?php echo esc_html(eu_get_option('tagline')); ?></p>
                </div>
                <?php eu_render_contact_form(array('context' => 'proyecto', 'project_id' => get_the_ID(), 'title' => __('Escribinos', 'enclave-urbano'))); ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
