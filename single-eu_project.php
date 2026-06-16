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
        $video   = eu_project_meta(get_the_ID(), 'video_url');
        $video_2 = eu_project_meta(get_the_ID(), 'video_url_2');
        $embed = eu_project_meta(get_the_ID(), 'maps_embed_url');
        $kml = eu_project_meta(get_the_ID(), 'kml_url');
        $lat = eu_project_meta(get_the_ID(), 'map_lat');
        $lng = eu_project_meta(get_the_ID(), 'map_lng');
        $zoom = eu_project_meta(get_the_ID(), 'map_zoom') ?: '17';
        $whatsapp = eu_project_meta(get_the_ID(), 'whatsapp');
        $wa_link = eu_whatsapp_link($whatsapp, sprintf(__('Hola, quiero consultar por %s', 'enclave-urbano'), get_the_title()));
        $croquis_title    = get_post_meta(get_the_ID(), '_eu_croquis_title', true) ?: __('Croquis del barrio', 'enclave-urbano');
        $croquis_image    = get_post_meta(get_the_ID(), '_eu_croquis_image', true);
        $croquis_raw      = get_post_meta(get_the_ID(), '_eu_croquis_hotspots', true);
        $croquis_hotspots = ($croquis_raw) ? json_decode($croquis_raw, true) : array();
        if (!is_array($croquis_hotspots)) {
            $croquis_hotspots = array();
        }
        $croquis_hotspots = array_values(array_filter($croquis_hotspots, function ($h) {
            return !empty($h['name']);
        }));
        $project_logo = eu_project_meta(get_the_ID(), 'logo_url');
        ?>
        <header class="eu-project-hero" style="background-image:url('<?php echo esc_url($hero); ?>')">
            <div class="eu-project-hero__overlay"></div>
            <div class="eu-container eu-project-hero__content">
                <?php if ($location) : ?><p class="eu-kicker"><?php echo esc_html($location); ?></p><?php endif; ?>
                <?php if ($project_logo) : ?>
                    <img class="eu-project-hero__logo" src="<?php echo esc_url($project_logo); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                <?php else : ?>
                    <h1><?php the_title(); ?></h1>
                <?php endif; ?>
            </div>
        </header>

        <div class="eu-container eu-project-layout">
            <article class="eu-project-maincol">
                <?php if ($croquis_image) : ?>
                    <section class="eu-project-block eu-croquis">
                        <h2><?php echo esc_html($croquis_title); ?></h2>
                        <div class="eu-croquis__map">
                            <img
                                src="<?php echo esc_url($croquis_image); ?>"
                                alt="<?php esc_attr_e('Croquis del barrio', 'enclave-urbano'); ?>"
                                class="eu-croquis__img"
                            >
                            <?php
                            $lote_counter = 0;
                            $area_counter = 0;
                            foreach ($croquis_hotspots as $i => $hotspot) :
                                $type    = $hotspot['type'] ?? 'lote';
                                $is_lote = 'lote' === $type;
                                $is_area = 'area' === $type;
                                if ($is_lote) $lote_counter++;
                                if ($is_area) $area_counter++;
                            ?>
                                <button
                                    class="eu-croquis__dot eu-croquis__dot--<?php echo esc_attr($type); ?>"
                                    style="left:<?php echo esc_attr($hotspot['x'] ?? 50); ?>%;top:<?php echo esc_attr($hotspot['y'] ?? 50); ?>%"
                                    data-modal="eu-modal-<?php echo esc_attr($i); ?>"
                                    aria-label="<?php echo esc_attr($hotspot['name']); ?>"
                                    type="button"
                                ><?php
                                    if ($is_lote) echo esc_html($lote_counter);
                                    elseif ($is_area) echo esc_html($area_counter);
                                    else echo '&#9733;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                ?></button>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($video || $video_2) : ?>
                    <section class="eu-project-block">
                        <h2><?php esc_html_e('Video', 'enclave-urbano'); ?></h2>
                        <?php foreach (array_filter(array($video, $video_2)) as $vid_url) : ?>
                        <div class="eu-video-embed">
                            <?php
                            $video_html = wp_oembed_get($vid_url);
                            if ($video_html) {
                                echo $video_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            } else {
                                echo '<a href="' . esc_url($vid_url) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Ver video', 'enclave-urbano') . '</a>';
                            }
                            ?>
                        </div>
                        <?php endforeach; ?>
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

        <?php foreach ($croquis_hotspots as $i => $hotspot) : ?>
        <div
            id="eu-modal-<?php echo esc_attr($i); ?>"
            class="eu-modal"
            role="dialog"
            aria-modal="true"
            aria-label="<?php echo esc_attr($hotspot['name']); ?>"
            hidden
        >
            <div class="eu-modal__overlay"></div>
            <div class="eu-modal__box">
                <button class="eu-modal__close" aria-label="<?php esc_attr_e('Cerrar', 'enclave-urbano'); ?>" type="button">&#x2715;</button>
                <?php if (!empty($hotspot['image_url'])) : ?>
                    <img
                        src="<?php echo esc_url($hotspot['image_url']); ?>"
                        alt="<?php echo esc_attr($hotspot['name']); ?>"
                        class="eu-modal__img"
                    >
                <?php else : ?>
                    <div class="eu-modal__img-placeholder"></div>
                <?php endif; ?>
                <div class="eu-modal__body">
                    <h3 class="eu-modal__title"><?php echo esc_html($hotspot['name']); ?></h3>
                    <span class="eu-modal__badge eu-modal__badge--<?php echo esc_attr($hotspot['type'] ?? 'lote'); ?>">
                        <?php echo ('lote' === ($hotspot['type'] ?? 'lote')) ? esc_html__('Lote', 'enclave-urbano') : esc_html__('Sector', 'enclave-urbano'); ?>
                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

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
