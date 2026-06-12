<?php
/**
 * Template Name: Alianzas
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main eu-alliances-page">
    <div class="eu-container eu-page-shell">
       
      <div class="eu-alliances-content">
                <h2><?php esc_html_e('Empresas de servicios y construcción', 'enclave-urbano'); ?></h2>
                <?php eu_render_alliances(); ?>
            </div>

        <section class="eu-alliances-layout">
            

            
        </section>
    </div>
</main>
<?php
get_footer();

function eu_render_alliances() {
    $terms = get_terms(array(
        'taxonomy'   => 'eu_alliance_type',
        'hide_empty' => false,
        'orderby'    => 'name',
    ));

    if (!is_wp_error($terms) && !empty($terms)) {
        echo '<div class="eu-alliance-groups">';
        foreach ($terms as $term) {
            $query = new WP_Query(array(
                'post_type'      => 'eu_alliance',
                'posts_per_page' => -1,
                'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'eu_alliance_type',
                        'field'    => 'term_id',
                        'terms'    => $term->term_id,
                    ),
                ),
            ));
            echo '<section class="eu-alliance-group"><h3>' . esc_html($term->name) . '</h3><div class="eu-alliance-grid">';
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $website = get_post_meta(get_the_ID(), '_eu_alliance_website', true);
                    echo '<article class="eu-alliance-card">';
                    if (has_post_thumbnail()) {
                        echo '<div class="eu-alliance-logo">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</div>';
                    } else {
                        echo '<div class="eu-alliance-logo eu-alliance-logo--empty"></div>';
                    }
                    echo '<h4>' . esc_html(get_the_title()) . '</h4>';
                    if ($website) {
                        echo '<a href="' . esc_url($website) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Ver sitio', 'enclave-urbano') . '</a>';
                    }
                    echo '</article>';
                }
                wp_reset_postdata();
            } else {
                for ($i = 0; $i < 3; $i++) {
                    echo '<article class="eu-alliance-card eu-alliance-card--placeholder"><div></div></article>';
                }
            }
            echo '</div></section>';
        }
        echo '</div>';
        return;
    }

    $groups = array('Organismos', 'Profesionales', 'Inmobiliarias', 'Empresas de servicios y construcción');
    echo '<div class="eu-alliance-groups">';
    foreach ($groups as $group) {
        echo '<section class="eu-alliance-group"><h3>' . esc_html($group) . '</h3><div class="eu-alliance-grid">';
        for ($i = 0; $i < 3; $i++) {
            echo '<article class="eu-alliance-card eu-alliance-card--placeholder"><div></div></article>';
        }
        echo '</div></section>';
    }
    echo '</div>';
}
