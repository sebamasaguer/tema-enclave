<?php
/**
 * Template tags and render helpers.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

function eu_primary_menu($class = '') {
    if (has_nav_menu('primary')) {
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'eu-menu ' . $class,
            'fallback_cb'    => false,
            'depth'          => 2,
        ));
        return;
    }

    echo '<ul class="eu-menu ' . esc_attr($class) . '">';
    echo '<li><a href="' . esc_url(home_url('/#enclave-urbano')) . '">' . esc_html__('Enclave Urbano', 'enclave-urbano') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/#equipo')) . '">' . esc_html__('Equipo', 'enclave-urbano') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/#valores')) . '">' . esc_html__('Valores', 'enclave-urbano') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/news/')) . '">' . esc_html__('News', 'enclave-urbano') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/comercializacion/')) . '">' . esc_html__('Comercialización', 'enclave-urbano') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/alianzas/')) . '">' . esc_html__('Alianzas', 'enclave-urbano') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/comunidad/')) . '">' . esc_html__('Comunidad', 'enclave-urbano') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/filosofia/')) . '">' . esc_html__('Filosofía', 'enclave-urbano') . '</a></li>';
    echo '<li class="menu-item-has-children"><a href="' . esc_url(home_url('/proyectos/')) . '">' . esc_html__('Proyectos', 'enclave-urbano') . '</a><ul class="sub-menu"><li><a href="' . esc_url(home_url('/proyectos/la-enriqueta/')) . '">' . esc_html__('La Enriqueta', 'enclave-urbano') . '</a></li></ul></li>';
    echo '<li><a href="' . esc_url(home_url('/contacto/')) . '">' . esc_html__('Contacto', 'enclave-urbano') . '</a></li>';
    echo '</ul>';
}

function eu_footer_menu() {
    if (has_nav_menu('footer')) {
        wp_nav_menu(array(
            'theme_location' => 'footer',
            'container'      => false,
            'menu_class'     => 'eu-footer-menu',
            'fallback_cb'    => false,
            'depth'          => 1,
        ));
        return;
    }

    echo '<ul class="eu-footer-menu">';
    $items = array(
        __('Enclave Urbano', 'enclave-urbano') => home_url('/#enclave-urbano'),
        __('Equipo', 'enclave-urbano')         => home_url('/#equipo'),
        __('Valores', 'enclave-urbano')        => home_url('/#valores'),
        __('News', 'enclave-urbano')           => home_url('/news/'),
        __('Comercialización', 'enclave-urbano') => home_url('/comercializacion/'),
        __('Alianzas', 'enclave-urbano')       => home_url('/alianzas/'),
        __('Comunidad', 'enclave-urbano')      => home_url('/comunidad/'),
        __('Filosofía', 'enclave-urbano')      => home_url('/filosofia/'),
        __('Proyectos', 'enclave-urbano')      => home_url('/proyectos/'),
        __('Contacto', 'enclave-urbano')       => home_url('/contacto/'),
    );
    foreach ($items as $label => $url) {
        echo '<li><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

function eu_logo_img($variant = 'small', $class = '') {
    $url = ('large' === $variant) ? eu_get_option('logo_large') : eu_get_option('logo_small');
    if (!$url) {
        return '';
    }

    return '<img class="' . esc_attr($class) . '" src="' . esc_url($url) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
}

function eu_social_links() {
    $links = array(
        'Instagram' => array(
            'url'  => eu_get_option('instagram_url'),
            'icon' => 'instagram',
        ),
        'Facebook'  => array(
            'url'  => eu_get_option('facebook_url'),
            'icon' => 'facebook',
        ),
        'LinkedIn'  => array(
            'url'  => eu_get_option('linkedin_url'),
            'icon' => 'linkedin',
        ),
        'YouTube'   => array(
            'url'  => eu_get_option('youtube_url'),
            'icon' => 'youtube',
        ),
        'TikTok'    => array(
            'url'  => eu_get_option('tiktok_url'),
            'icon' => 'tiktok',
        ),
    );

    $out = '<ul class="eu-social-links eu-social-links--icons">';
    $has = false;

    foreach ($links as $label => $data) {
        if (!empty($data['url'])) {
            $has  = true;
            $out .= '<li>';
            $out .= '<a class="eu-social-link eu-social-link--' . esc_attr($data['icon']) . '" href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($label) . '" title="' . esc_attr($label) . '">';
            $out .= eu_social_icon_svg($data['icon']);
            $out .= '<span class="screen-reader-text">' . esc_html($label) . '</span>';
            $out .= '</a>';
            $out .= '</li>';
        }
    }

    if (!$has) {
        $out .= '<li><span>' . esc_html__('Configurar redes desde Ajustes Enclave', 'enclave-urbano') . '</span></li>';
    }

    $out .= '</ul>';
    return $out;
}

function eu_social_icon_svg($network) {
    $common = 'width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"';

    switch ($network) {
        case 'facebook':
            return '<svg ' . $common . '><path fill="currentColor" d="M14.2 8.1h2.4V4.3c-.4-.1-1.8-.2-3.4-.2-3.4 0-5.7 2.1-5.7 6v3.4H3.8v4.3h3.7V24h4.5v-6.2h3.5l.6-4.3h-4.1v-3c0-1.2.3-2.4 2.2-2.4z"/></svg>';
        case 'linkedin':
            return '<svg ' . $common . '><path fill="currentColor" d="M5.3 7.7H1.1V21h4.2V7.7zM3.2 1C1.9 1 .8 2.1.8 3.4s1 2.4 2.4 2.4 2.4-1.1 2.4-2.4S4.6 1 3.2 1zM23.2 21v-7.3c0-3.9-2.1-5.8-4.9-5.8-2.3 0-3.3 1.3-3.8 2.1V7.7h-4.2V21h4.2v-7.4c0-2 1.3-2.5 2.2-2.5 1.4 0 2.2.9 2.2 2.8V21h4.3z"/></svg>';
        case 'youtube':
            return '<svg ' . $common . '><path fill="currentColor" d="M23.5 7.2s-.2-1.7-.9-2.4c-.9-.9-1.9-.9-2.3-1C17.1 3.6 12 3.6 12 3.6s-5.1 0-8.3.2c-.5.1-1.5.1-2.3 1C.7 5.5.5 7.2.5 7.2S.3 9.1.3 11v1.8c0 1.9.2 3.8.2 3.8s.2 1.7.9 2.4c.9.9 2 .9 2.5 1 1.8.2 8.1.2 8.1.2s5.1 0 8.3-.3c.5 0 1.5-.1 2.3-1 .7-.7.9-2.4.9-2.4s.2-1.9.2-3.8V11c0-1.9-.2-3.8-.2-3.8zM9.8 15.2V7.9l6.4 3.7-6.4 3.6z"/></svg>';
        case 'tiktok':
            return '<svg ' . $common . '><path fill="currentColor" d="M19.6 8.6a6.6 6.6 0 0 1-6.6-6.6h-2.9v13.6a3 3 0 0 1-3 2.8 3 3 0 0 1-3-3 3 3 0 0 1 3-3c.3 0 .6 0 .8.1V9.5a6 6 0 0 0-.8-.1 6 6 0 0 0-6 6 6 6 0 0 0 6 6 6 6 0 0 0 6-6V9.3a9.5 9.5 0 0 0 5.5 1.7V8.1a6.6 6.6 0 0 1-5.5 0h5.5V8.6z"/></svg>';
        case 'instagram':
        default:
            return '<svg ' . $common . '><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4.2" stroke="currentColor" stroke-width="2"/><circle cx="17.2" cy="6.8" r="1.3" fill="currentColor"/></svg>';
    }
}

function eu_demo_values() {
    return array(
        array('icon' => 'target', 'title' => 'Profesionalismo y compromiso', 'text' => 'Cada proyecto, cliente y sitio es único. La base del éxito es un análisis minucioso para evaluar potencialidades, fortalezas, amenazas y debilidades.'),
        array('icon' => 'strategy', 'title' => 'Planificación y estrategia', 'text' => 'Diseñamos y estudiamos estrategias para mejorar los rendimientos de cada desarrollo, generando sinergia en la ciudad.'),
        array('icon' => 'idea', 'title' => 'Originalidad e innovación', 'text' => 'La mirada integral y holística de cada proyecto transforma ciudades y territorios con nuevas ofertas de servicios.'),
        array('icon' => 'team', 'title' => 'Trabajo en equipo', 'text' => 'Incluimos las distintas miradas del territorio para garantizar el funcionamiento de cada proyecto.'),
        array('icon' => 'gears', 'title' => 'Responsabilidad empresaria', 'text' => 'Nos comprometemos con los territorios y ciudades que transformamos, mejorando la calidad de vida de sus habitantes.'),
    );
}

function eu_render_values() {
    $query = new WP_Query(array(
        'post_type'      => 'eu_value',
        'posts_per_page' => -1,
        'orderby'        => array('menu_order' => 'ASC', 'date' => 'DESC'),
        'order'          => 'ASC',
    ));

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $icon_url = get_post_meta(get_the_ID(), '_eu_value_icon_url', true);
            if (!$icon_url && has_post_thumbnail()) {
                $icon_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            }
            ?>
            <article class="eu-value-card">
                <div class="eu-value-icon">
                    <?php if ($icon_url) : ?>
                        <img src="<?php echo esc_url($icon_url); ?>" alt="">
                    <?php else : ?>
                        <?php echo eu_inline_icon('target'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php endif; ?>
                </div>
                <h3><?php the_title(); ?></h3>
                <div class="eu-value-text"><?php the_content(); ?></div>
            </article>
            <?php
        }
        wp_reset_postdata();
        return;
    }

    foreach (eu_demo_values() as $item) {
        ?>
        <article class="eu-value-card">
            <div class="eu-value-icon"><?php echo eu_inline_icon($item['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
            <h3><?php echo esc_html($item['title']); ?></h3>
            <div class="eu-value-text"><p><?php echo esc_html($item['text']); ?></p></div>
        </article>
        <?php
    }
}

function eu_render_team_cards() {
    $query = new WP_Query(array(
        'post_type'      => 'eu_team',
        'posts_per_page' => -1,
        'orderby'        => array('menu_order' => 'ASC', 'date' => 'DESC'),
        'order'          => 'ASC',
    ));

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $position = get_post_meta(get_the_ID(), '_eu_team_position', true);
            $qr       = get_post_meta(get_the_ID(), '_eu_team_qr_url', true);
            $link     = get_post_meta(get_the_ID(), '_eu_team_link_url', true);
            $photo    = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'eu-team') : '';
            eu_render_team_card(get_the_title(), $position, $photo, $qr, $link);
        }
        wp_reset_postdata();
        return;
    }

    eu_render_team_card('Haston Garcia Richard', 'Arquitecto', '', '', '');
    eu_render_team_card('Gisele Muchut', 'Arquitecta', '', '', '');
    eu_render_team_card('Maria Eugenia Angulo', 'Arquitecta', '', '', '');
}

function eu_render_team_card($name, $position, $photo = '', $qr = '', $link = '') {
    ?>
    <article class="eu-team-card">
        <div class="eu-team-photo <?php echo $photo ? '' : 'eu-team-photo--placeholder'; ?>">
            <?php if ($photo) : ?>
                <img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($name); ?>">
            <?php else : ?>
                <span><?php echo eu_inline_icon('compass'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            <?php endif; ?>
        </div>
        <div class="eu-team-meta">
            <?php if ($qr) : ?>
                <img class="eu-team-qr" src="<?php echo esc_url($qr); ?>" alt="QR <?php echo esc_attr($name); ?>">
            <?php else : ?>
                <span class="eu-team-qr eu-team-qr--empty"></span>
            <?php endif; ?>
            <div>
                <h3><?php echo esc_html($name); ?></h3>
                <?php if ($position) : ?><p><?php echo esc_html($position); ?></p><?php endif; ?>
                <?php if ($link) : ?><a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Ver perfil', 'enclave-urbano'); ?></a><?php endif; ?>
            </div>
        </div>
    </article>
    <?php
}

function eu_project_meta($post_id, $key) {
    return get_post_meta($post_id, '_eu_project_' . $key, true);
}

function eu_project_technical_fields($post_id) {
    $fields = array(
        __('Ubicación', 'enclave-urbano')          => eu_project_meta($post_id, 'location'),
        __('Superficie', 'enclave-urbano')         => eu_project_meta($post_id, 'surface'),
        __('Unidades', 'enclave-urbano')           => eu_project_meta($post_id, 'units'),
        __('Inversión', 'enclave-urbano')          => eu_project_meta($post_id, 'investment'),
        __('Entrega', 'enclave-urbano')            => eu_project_meta($post_id, 'delivery_date'),
        __('Etapa', 'enclave-urbano')              => eu_project_meta($post_id, 'stage'),
        __('Precio desde', 'enclave-urbano')       => eu_project_meta($post_id, 'price_from'),
    );

    return array_filter($fields);
}

function eu_whatsapp_link($number = '', $message = '') {
    $number = $number ? $number : eu_get_option('whatsapp', '');
    $clean  = preg_replace('/[^0-9]/', '', $number);
    if (!$clean) {
        return '';
    }

    return 'https://wa.me/' . $clean . ($message ? '?text=' . rawurlencode($message) : '');
}

function eu_inline_icon($name = 'target') {
    $common = 'width="82" height="82" viewBox="0 0 82 82" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"';
    $stroke = 'stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"';

    switch ($name) {
        case 'compass':
            return '<svg ' . $common . '><circle cx="41" cy="41" r="10" fill="currentColor"/><path d="M41 4l10 31 27-12-24 21 12 34-25-23-28 15 21-26L16 10l25 25z" fill="currentColor" opacity=".95"/><circle cx="41" cy="41" r="28" ' . $stroke . ' opacity=".45"/><circle cx="41" cy="41" r="34" ' . $stroke . ' opacity=".2"/></svg>';
        case 'strategy':
            return '<svg ' . $common . '><circle cx="39" cy="43" r="27" ' . $stroke . '/><circle cx="39" cy="43" r="17" ' . $stroke . '/><circle cx="39" cy="43" r="7" fill="currentColor"/><path d="M54 28l18-18M60 10h12v12M58 24l8 8" ' . $stroke . '/></svg>';
        case 'idea':
            return '<svg ' . $common . '><path d="M29 58h24M32 68h18M28 35c0-9 6-17 14-17s14 8 14 17c0 7-4 11-8 16H36c-4-5-8-9-8-16z" ' . $stroke . '/><path d="M16 22l-6-6M66 22l6-6M41 8V2M18 50l-8 4M64 50l8 4" ' . $stroke . ' opacity=".65"/></svg>';
        case 'team':
            return '<svg ' . $common . '><circle cx="41" cy="41" r="9" fill="currentColor"/><circle cx="41" cy="41" r="26" ' . $stroke . '/><path d="M41 3v14M41 65v14M3 41h14M65 41h14M22 22l10 10M60 22L50 32M22 60l10-10M60 60L50 50" ' . $stroke . '/></svg>';
        case 'gears':
            return '<svg ' . $common . '><circle cx="48" cy="31" r="13" ' . $stroke . '/><path d="M48 8v8M48 46v8M25 31h8M63 31h8M32 15l6 6M64 15l-6 6M32 47l6-6M64 47l-6-6" ' . $stroke . '/><circle cx="24" cy="57" r="9" ' . $stroke . '/><path d="M24 43v5M24 66v6M10 57h5M33 57h6M14 47l4 4M34 47l-4 4M14 67l4-4M34 67l-4-4" ' . $stroke . ' opacity=".8"/></svg>';
        case 'network':
            return '<svg ' . $common . '><circle cx="41" cy="41" r="12" ' . $stroke . '/><circle cx="20" cy="20" r="7" fill="currentColor"/><circle cx="63" cy="18" r="7" fill="currentColor"/><circle cx="18" cy="64" r="7" fill="currentColor"/><circle cx="65" cy="62" r="7" fill="currentColor"/><path d="M26 25l8 8M56 23l-8 10M26 59l8-10M56 57l-8-8" ' . $stroke . '/></svg>';
        case 'puzzle':
            return '<svg ' . $common . '><path d="M19 19h15c0-7 10-7 10 0h19v18c-7 0-7 10 0 10v16H45c0-7-10-7-10 0H19V45c7 0 7-10 0-10V19z" ' . $stroke . '/><path d="M41 19v44M19 41h44" ' . $stroke . ' opacity=".45"/></svg>';
	    case 'neural':
            return '<img class="eu-inline-icon" src="https://enclaveurbano.com.ar/wp-content/uploads/2026/05/neuronal.jpeg" loading="lazy" width="82" height="82"/>';
        case 'agenda':
            return '<img class="eu-inline-icon" src="https://enclaveurbano.com.ar/wp-content/uploads/2026/05/agenda.jpeg" loading="lazy" width="62" height="62"/>';
        case 'target':
        default:
            return '<svg ' . $common . '><circle cx="41" cy="41" r="26" ' . $stroke . '/><circle cx="41" cy="41" r="13" ' . $stroke . '/><circle cx="41" cy="41" r="5" fill="currentColor"/><path d="M41 3v17M41 62v17M3 41h17M62 41h17" ' . $stroke . '/></svg>';
    }
}

function eu_cityline_svg() {
    return '<svg class="eu-cityline" viewBox="0 0 690 82" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><g stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 65h660"/><path d="M26 65V35h25v30M31 35v-8h15v8M34 47h5M34 56h5M45 47h5M45 56h5"/><path d="M76 65V46h18v19M82 46V33h21v32M88 39h5M88 51h5M100 52h5"/><path d="M124 65V29h42v36M132 37h6M147 37h6M132 50h6M147 50h6"/><path d="M195 65V40h45v25M204 40c4-12 27-12 31 0M212 32V20h16v12"/><path d="M270 65V34h12v31M288 65V23h18v42M314 65V42h15v23"/><path d="M358 65V28h44v37M366 37h7M383 37h7M366 50h7M383 50h7"/><path d="M430 65V48h28v17M438 48V33h12v15"/><path d="M490 65V25h48v40M498 35h8M518 35h8M498 48h8M518 48h8"/><path d="M570 65V38h34v27M575 38v-8h24v8M581 49h16"/><circle cx="642" cy="33" r="10"/><path d="M642 43v22M632 53h20"/></g></svg>';
}

function eu_page_content_or_fallback($fallback_callback) {
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            if (eu_has_editor_content()) {
                echo '<article class="eu-page-editor-content">';
                the_content();
                echo '</article>';
            } else {
                call_user_func($fallback_callback);
            }
        }
    }
}
